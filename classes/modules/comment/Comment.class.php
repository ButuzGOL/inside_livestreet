<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Модуль для работы с комментариями
 *
 */
class ModuleComment extends Module {
	protected $oMapper;	
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {			
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();		
	}
	/* Получть все комменты сгрупированные по топику(для вывода прямого эфира)
	 *
	 * @param unknown_type $sTargetType
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetCommentsOnline($sTargetType,$iLimit) {
		/**
		 * Исключаем из выборки идентификаторы закрытых блогов (target_parent_id)
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();
			
		$s=serialize($aCloseBlogs);
		
		if (false === ($data = $this->Cache_Get("comment_online_{$sTargetType}_{$s}_{$iLimit}"))) {			
			$data = $this->oMapper->GetCommentsOnline($sTargetType,$aCloseBlogs,$iLimit);
			$this->Cache_Set($data, "comment_online_{$sTargetType}_{$s}_{$iLimit}", array("comment_online_update_{$sTargetType}"), 60*60*24*1);
		}
		$data=$this->GetCommentsAdditionalData($data);
		return $data;		
	}
    /**
	 * Получает дополнительные данные(объекты) для комментов по их ID
	 *
	 */
	public function GetCommentsAdditionalData($aCommentId,$aAllowData=array('vote','target','favourite','user'=>array())) {
		func_array_simpleflip($aAllowData);
		if (!is_array($aCommentId)) {
			$aCommentId=array($aCommentId);
		}
		/**
		 * Получаем комменты
		 */
		$aComments=$this->GetCommentsByArrayId($aCommentId);
		/**
		 * Формируем ID дополнительных данных, которые нужно получить
		 */
		$aUserId=array();
		$aTargetId=array('topic'=>array(),'talk'=>array());
		foreach ($aComments as $oComment) {
			if (isset($aAllowData['user'])) {
				$aUserId[]=$oComment->getUserId();
			}
			if (isset($aAllowData['target'])) {
				$aTargetId[$oComment->getTargetType()][]=$oComment->getTargetId();
			}
		}

		/**
		 * Получаем дополнительные данные
		 */
		$aUsers=isset($aAllowData['user']) && is_array($aAllowData['user']) ? $this->User_GetUsersAdditionalData($aUserId,$aAllowData['user']) : $this->User_GetUsersAdditionalData($aUserId);
		/**
		 * В зависимости от типа target_type достаем данные
		 */
		$aTargets=array();
		//$aTargets['topic']=isset($aAllowData['target']) && is_array($aAllowData['target']) ? $this->Topic_GetTopicsAdditionalData($aTargetId['topic'],$aAllowData['target']) : $this->Topic_GetTopicsAdditionalData($aTargetId['topic']);
		$aTargets['topic']=$this->Topic_GetTopicsAdditionalData($aTargetId['topic'],array('blog'=>array('owner'=>array())));
		$aVote=array();
		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aVote=$this->Vote_GetVoteByArray($aCommentId,'comment',$this->oUserCurrent->getId());
		}
		if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
			$aFavouriteComments=$this->Favourite_GetFavouritesByArray($aCommentId,'comment',$this->oUserCurrent->getId());
		}
		/**
		 * Добавляем данные к результату
		 */
		foreach ($aComments as $oComment) {
			if (isset($aUsers[$oComment->getUserId()])) {
				$oComment->setUser($aUsers[$oComment->getUserId()]);
			} else {
				$oComment->setUser(null); // или $oComment->setUser(new ModuleUser_EntityUser());
			}
			if (isset($aTargets[$oComment->getTargetType()][$oComment->getTargetId()])) {
				$oComment->setTarget($aTargets[$oComment->getTargetType()][$oComment->getTargetId()]);
			} else {
				$oComment->setTarget(null);
			}
			if (isset($aVote[$oComment->getId()])) {
				$oComment->setVote($aVote[$oComment->getId()]);
			} else {
				$oComment->setVote(null);
			}
			if (isset($aFavouriteComments[$oComment->getId()])) {
				$oComment->setIsFavourite(true);
			} else {
				$oComment->setIsFavourite(false);
			}
		}

		return $aComments;
	}
    /**
	 * Список комментов по ID
	 *
	 * @param array $aUserId
	 */
	public function GetCommentsByArrayId($aCommentId) {
		if (!$aCommentId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetCommentsByArrayIdSolid($aCommentId);
		}
		if (!is_array($aCommentId)) {
			$aCommentId=array($aCommentId);
		}
		$aCommentId=array_unique($aCommentId);
		$aComments=array();
		$aCommentIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aCommentId,'comment_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aComments[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aCommentIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких комментов не было в кеше и делаем запрос в БД
		 */
		$aCommentIdNeedQuery=array_diff($aCommentId,array_keys($aComments));
		$aCommentIdNeedQuery=array_diff($aCommentIdNeedQuery,$aCommentIdNotNeedQuery);
		$aCommentIdNeedStore=$aCommentIdNeedQuery;
		if ($data = $this->oMapper->GetCommentsByArrayId($aCommentIdNeedQuery)) {
			foreach ($data as $oComment) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aComments[$oComment->getId()]=$oComment;
				$this->Cache_Set($oComment, "comment_{$oComment->getId()}", array(), 60*60*24*4);
				$aCommentIdNeedStore=array_diff($aCommentIdNeedStore,array($oComment->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aCommentIdNeedStore as $sId) {
			$this->Cache_Set(null, "comment_{$sId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aComments=func_array_sort_by_keys($aComments,$aCommentId);
		return $aComments;
	}
	public function GetCommentsByArrayIdSolid($aCommentId) {
		if (!is_array($aCommentId)) {
			$aCommentId=array($aCommentId);
		}
		$aCommentId=array_unique($aCommentId);
		$aComments=array();
		$s=join(',',$aCommentId);
		if (false === ($data = $this->Cache_Get("comment_id_{$s}"))) {
			$data = $this->oMapper->GetCommentsByArrayId($aCommentId);
			foreach ($data as $oComment) {
				$aComments[$oComment->getId()]=$oComment;
			}
			$this->Cache_Set($aComments, "comment_id_{$s}", array("comment_update"), 60*60*24*1);
			return $aComments;
		}
		return $data;
	}
    /**
	 * Получить комменты по рейтингу и дате
	 *
	 * @param  string $sDate
	 * @param  string $sTargetType
	 * @param  int    $iLimit
	 * @return array
	 */
	public function GetCommentsRatingByDate($sDate,$sTargetType,$iLimit=20) {
		/**
		 * Выбираем топики, комметарии к которым являются недоступными для пользователя
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();
		$s=serialize($aCloseBlogs);

		//т.к. время передаётся с точностью 1 час то можно по нему замутить кеширование
		if (false === ($data = $this->Cache_Get("comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}"))) {
			$data = $this->oMapper->GetCommentsRatingByDate($sDate,$sTargetType,$iLimit,array(),$aCloseBlogs);
			$this->Cache_Set($data, "comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}", array("comment_new_{$sTargetType}","comment_update_status_{$sTargetType}","comment_update_rating_{$sTargetType}"), 60*60*24*2);
		}
		$data=$this->GetCommentsAdditionalData($data);
		return $data;
	}
    /**
	 * Получает количество комментариев одного пользователя
	 *
	 * @param  string $sId
	 * @param  string $sTargetType
	 * @return int
	 */
	public function GetCountCommentsByUserId($sId,$sTargetType) {
		/**
		 * Исключаем из выборки идентификаторы закрытых блогов
		 */
		$aCloseBlogs = ($this->oUserCurrent && $sId==$this->oUserCurrent->getId())
			? array()
			: $this->Blog_GetInaccessibleBlogsByUser();
		$s=serialize($aCloseBlogs);

		if (false === ($data = $this->Cache_Get("comment_count_user_{$sId}_{$sTargetType}_{$s}"))) {
			$data = $this->oMapper->GetCountCommentsByUserId($sId,$sTargetType,array(),$aCloseBlogs);
			$this->Cache_Set($data, "comment_count_user_{$sId}_{$sTargetType}", array("comment_new_user_{$sId}_{$sTargetType}","comment_update_status_{$sTargetType}"), 60*60*24*2);
		}
		return $data;
	}
    /**
	 * Возвращает число комментариев в избранном
	 *
	 * @param  string $sUserId
	 * @return int
	 */
	public function GetCountCommentsFavouriteByUserId($sUserId) {
		return ($this->oUserCurrent && $sUserId==$this->oUserCurrent->getId())
			? $this->Favourite_GetCountFavouritesByUserId($sUserId,'comment')
			: $this->Favourite_GetCountFavouriteOpenCommentsByUserId($sUserId);
	}
}
?>