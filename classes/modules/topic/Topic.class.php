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
 * Модуль для работы с топиками
 *
 */
class ModuleTopic extends Module {		
	protected $oMapperTopic;
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapperTopic=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
    /**
	 * Получает число новых топиков в коллективных блогах
	 *
	 * @return unknown
	 */
	public function GetCountTopicsCollectiveNew() {
		$sDate=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}
		return $this->GetCountTopicsByFilter($aFilter);
	}
    /**
	 * Количество топиков по фильтру
	 *
	 * @param unknown_type $aFilter
	 * @return unknown
	 */
	public function GetCountTopicsByFilter($aFilter) {
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_count_{$s}"))) {
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_{$s}", array('topic_update','topic_new'), 60*60*24*1);
		}
		return 	$data;
	}
    /**
	 * Получает число новых топиков в персональных блогах
	 *
	 * @return unknown
	 */
	public function GetCountTopicsPersonalNew() {
		$sDate=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);
		return $this->GetCountTopicsByFilter($aFilter);
	}
    /**
	 * Получает список хороших топиков для вывода на главную страницу(из всех блогов, как коллективных так и персональных)
	 *
	 * @param  int    $iPage
	 * @param  int    $iPerPage
	 * @param  bool   $bAddAccessible Указывает на необходимость добавить в выдачу топики,
	 *                                из блогов доступных пользователю. При указании false,
	 *                                в выдачу будут переданы только топики из общедоступных блогов.
	 * @return array
	 */
	public function GetTopicsGood($iPage,$iPerPage,$bAddAccessible=true) {
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open'
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => Config::Get('module.blog.index_good'),
				'type'  => 'top',
				'publish_index'  => 1,
			)
		);
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $bAddAccessible) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}

		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
    /**
	 * Список топиков по фильтру
	 *
	 * @param  array $aFilter
	 * @param  int   $iPage
	 * @param  int   $iPerPage
	 * @return array
	 */
	public function GetTopicsByFilter($aFilter,$iPage=0,$iPerPage=0,$aAllowData=array('user'=>array(),'blog'=>array('owner'=>array(),'relation_user'),'vote','favourite','comment_new')) {
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}"))) {
			$data = ($iPage*$iPerPage!=0)
				? array(
						'collection'=>$this->oMapperTopic->GetTopics($aFilter,$iCount,$iPage,$iPerPage),
						'count'=>$iCount
					)
				: array(
						'collection'=>$this->oMapperTopic->GetAllTopics($aFilter),
						'count'=>$this->GetCountTopicsByFilter($aFilter)
					);
			$this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection'],$aAllowData);
		return $data;
	}
    /**
	 * Получает дополнительные данные(объекты) для топиков по их ID
	 *
	 */
	public function GetTopicsAdditionalData($aTopicId,$aAllowData=array('user'=>array(),'blog'=>array('owner'=>array(),'relation_user'),'vote','favourite','comment_new')) {
		func_array_simpleflip($aAllowData);
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		/**
		 * Получаем "голые" топики
		 */
		$aTopics=$this->GetTopicsByArrayId($aTopicId);
		/**
		 * Формируем ID дополнительных данных, которые нужно получить
		 */
		$aUserId=array();
		$aBlogId=array();
		$aTopicIdQuestion=array();
		foreach ($aTopics as $oTopic) {
			if (isset($aAllowData['user'])) {
				$aUserId[]=$oTopic->getUserId();
			}
			if (isset($aAllowData['blog'])) {
				$aBlogId[]=$oTopic->getBlogId();
			}
			if ($oTopic->getType()=='question')	{
				$aTopicIdQuestion[]=$oTopic->getId();
			}
		}
		/**
		 * Получаем дополнительные данные
		 */
		$aTopicsVote=array();
		$aFavouriteTopics=array();
		$aTopicsQuestionVote=array();
		$aTopicsRead=array();
		$aUsers=isset($aAllowData['user']) && is_array($aAllowData['user']) ? $this->User_GetUsersAdditionalData($aUserId,$aAllowData['user']) : $this->User_GetUsersAdditionalData($aUserId);
		$aBlogs=isset($aAllowData['blog']) && is_array($aAllowData['blog']) ? $this->Blog_GetBlogsAdditionalData($aBlogId,$aAllowData['blog']) : $this->Blog_GetBlogsAdditionalData($aBlogId);
		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aTopicsVote=$this->Vote_GetVoteByArray($aTopicId,'topic',$this->oUserCurrent->getId());
			$aTopicsQuestionVote=$this->GetTopicsQuestionVoteByArray($aTopicIdQuestion,$this->oUserCurrent->getId());
		}
		if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
			$aFavouriteTopics=$this->GetFavouriteTopicsByArray($aTopicId,$this->oUserCurrent->getId());
		}
		if (isset($aAllowData['comment_new']) and $this->oUserCurrent) {
			$aTopicsRead=$this->GetTopicsReadByArray($aTopicId,$this->oUserCurrent->getId());
		}
		/**
		 * Добавляем данные к результату - списку топиков
		 */
		foreach ($aTopics as $oTopic) {
			if (isset($aUsers[$oTopic->getUserId()])) {
				$oTopic->setUser($aUsers[$oTopic->getUserId()]);
			} else {
				$oTopic->setUser(null); // или $oTopic->setUser(new ModuleUser_EntityUser());
			}
			if (isset($aBlogs[$oTopic->getBlogId()])) {
				$oTopic->setBlog($aBlogs[$oTopic->getBlogId()]);
			} else {
				$oTopic->setBlog(null); // или $oTopic->setBlog(new ModuleBlog_EntityBlog());
			}
			if (isset($aTopicsVote[$oTopic->getId()])) {
				$oTopic->setVote($aTopicsVote[$oTopic->getId()]);
			} else {
				$oTopic->setVote(null);
			}
			if (isset($aFavouriteTopics[$oTopic->getId()])) {
				$oTopic->setIsFavourite(true);
			} else {
				$oTopic->setIsFavourite(false);
			}
			if (isset($aTopicsQuestionVote[$oTopic->getId()])) {
				$oTopic->setUserQuestionIsVote(true);
			} else {
				$oTopic->setUserQuestionIsVote(false);
			}
			if (isset($aTopicsRead[$oTopic->getId()]))	{
				$oTopic->setCountCommentNew($oTopic->getCountComment()-$aTopicsRead[$oTopic->getId()]->getCommentCountLast());
				$oTopic->setDateRead($aTopicsRead[$oTopic->getId()]->getDateRead());
			} else {
				$oTopic->setCountCommentNew(0);
				$oTopic->setDateRead(date("Y-m-d H:i:s"));
			}
		}
		return $aTopics;
	}
    /**
	 * Получить список топиков по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsByArrayId($aTopicId) {
		if (!$aTopicId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTopicsByArrayIdSolid($aTopicId);
		}

		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopics=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aTopics[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopics));
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsByArrayId($aTopicIdNeedQuery)) {
			foreach ($data as $oTopic) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopics[$oTopic->getId()]=$oTopic;
				$this->Cache_Set($oTopic, "topic_{$oTopic->getId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopic->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_{$sId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTopics=func_array_sort_by_keys($aTopics,$aTopicId);
		return $aTopics;
	}
	/**
	 * Получить список топиков по списку айдишников, но используя единый кеш
	 *
	 * @param unknown_type $aTopicId
	 * @return unknown
	 */
	public function GetTopicsByArrayIdSolid($aTopicId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopics=array();
		$s=join(',',$aTopicId);
		if (false === ($data = $this->Cache_Get("topic_id_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsByArrayId($aTopicId);
			foreach ($data as $oTopic) {
				$aTopics[$oTopic->getId()]=$oTopic;
			}
			$this->Cache_Set($aTopics, "topic_id_{$s}", array("topic_update"), 60*60*24*1);
			return $aTopics;
		}
		return $data;
	}
    /**
	 * Получить список голосований в топике-опросе по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsQuestionVoteByArray($aTopicId,$sUserId) {
		if (!$aTopicId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTopicsQuestionVoteByArraySolid($aTopicId,$sUserId);
		}
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsQuestionVote=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_question_vote_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aTopicsQuestionVote[$data[$sKey]->getTopicId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopicsQuestionVote));
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsQuestionVoteByArray($aTopicIdNeedQuery,$sUserId)) {
			foreach ($data as $oTopicVote) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopicsQuestionVote[$oTopicVote->getTopicId()]=$oTopicVote;
				$this->Cache_Set($oTopicVote, "topic_question_vote_{$oTopicVote->getTopicId()}_{$oTopicVote->getVoterId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopicVote->getTopicId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_question_vote_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTopicsQuestionVote=func_array_sort_by_keys($aTopicsQuestionVote,$aTopicId);
		return $aTopicsQuestionVote;
	}
    /**
	 * Получить список голосований в топике-опросе по списку айдишников, но используя единый кеш
	 *
	 * @param unknown_type $aTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicsQuestionVoteByArraySolid($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsQuestionVote=array();
		$s=join(',',$aTopicId);
		if (false === ($data = $this->Cache_Get("topic_question_vote_{$sUserId}_id_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsQuestionVoteByArray($aTopicId,$sUserId);
			foreach ($data as $oTopicVote) {
				$aTopicsQuestionVote[$oTopicVote->getTopicId()]=$oTopicVote;
			}
			$this->Cache_Set($aTopicsQuestionVote, "topic_question_vote_{$sUserId}_id_{$s}", array("topic_question_vote_user_{$sUserId}"), 60*60*24*1);
			return $aTopicsQuestionVote;
		}
		return $data;
	}
    /**
	 * Получает привязку топика к ибранному(добавлен ли топик в избранное у юзера)
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetFavouriteTopic($sTopicId,$sUserId) {
		return $this->Favourite_GetFavourite($sTopicId,'topic',$sUserId);
	}
    /**
	 * Получить список избранного по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetFavouriteTopicsByArray($aTopicId,$sUserId) {
		return $this->Favourite_GetFavouritesByArray($aTopicId,'topic',$sUserId);
	}
    /**
	 * Получить список просмотром/чтения топиков по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsReadByArray($aTopicId,$sUserId) {
		if (!$aTopicId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTopicsReadByArraySolid($aTopicId,$sUserId);
		}
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsRead=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_read_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aTopicsRead[$data[$sKey]->getTopicId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopicsRead));
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsReadByArray($aTopicIdNeedQuery,$sUserId)) {
			foreach ($data as $oTopicRead) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopicsRead[$oTopicRead->getTopicId()]=$oTopicRead;
				$this->Cache_Set($oTopicRead, "topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopicRead->getTopicId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_read_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTopicsRead=func_array_sort_by_keys($aTopicsRead,$aTopicId);
		return $aTopicsRead;
	}
    /**
	 * Получить список просмотром/чтения топиков по списку айдишников, но используя единый кеш
	 *
	 * @param unknown_type $aTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicsReadByArraySolid($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsRead=array();
		$s=join(',',$aTopicId);
		if (false === ($data = $this->Cache_Get("topic_read_{$sUserId}_id_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsReadByArray($aTopicId,$sUserId);
			foreach ($data as $oTopicRead) {
				$aTopicsRead[$oTopicRead->getTopicId()]=$oTopicRead;
			}
			$this->Cache_Set($aTopicsRead, "topic_read_{$sUserId}_id_{$s}", array("topic_read_user_{$sUserId}"), 60*60*24*1);
			return $aTopicsRead;
		}
		return $data;
	}
    /**
	 * Получает список тегов из топиков открытых блогов (open,personal)
	 *
	 * @param  int $iLimit
	 * @return array
	 */
	public function GetOpenTopicTags($iLimit) {
		if (false === ($data = $this->Cache_Get("tag_{$iLimit}_open"))) {
			$data = $this->oMapperTopic->GetOpenTopicTags($iLimit);
			$this->Cache_Set($data, "tag_{$iLimit}_open", array('topic_update','topic_new'), 60*60*24*3);
		}
		return $data;
	}
    /**
	 * список топиков из коллективных блогов
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @param unknown_type $sShowType
	 * @return unknown
	 */
	public function GetTopicsCollective($iPage,$iPerPage,$sShowType='good') {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
		);
		switch ($sShowType) {
			case 'good':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.collective_good'),
					'type'  => 'top',
				);
				break;
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.collective_good'),
					'type'  => 'down',
				);
				break;
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
				break;
			default:
				break;
		}
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
    /**
	 * список топиков из персональных блогов
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @param unknown_type $sShowType
	 * @return unknown
	 */
	public function GetTopicsPersonal($iPage,$iPerPage,$sShowType='good') {
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,
		);
		switch ($sShowType) {
			case 'good':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.personal_good'),
					'type'  => 'top',
				);
				break;
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.personal_good'),
					'type'  => 'down',
				);
				break;
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
				break;
			default:
				break;
		}
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
    /**
	 * Получает топики по рейтингу и дате
	 *
	 * @param unknown_type $sDate
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetTopicsRatingByDate($sDate,$iLimit=20) {
		/**
		 * Получаем список блогов, топики которых нужно исключить из выдачи
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();

		$s=serialize($aCloseBlogs);

		if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$iLimit}_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsRatingByDate($sDate,$iLimit,$aCloseBlogs);
			$this->Cache_Set($data, "topic_rating_{$sDate}_{$iLimit}_{$s}", array('topic_update'), 60*60*24*2);
		}
		$data=$this->GetTopicsAdditionalData($data);
		return $data;
	}

    /**
	 * Возвращает число топиков в избранном
	 *
	 * @param  string $sUserId
	 * @return int
	 */
	public function GetCountTopicsFavouriteByUserId($sUserId) {
		$aCloseTopics = array();
		return ($this->oUserCurrent && $sUserId==$this->oUserCurrent->getId())
			? $this->Favourite_GetCountFavouritesByUserId($sUserId,'topic',$aCloseTopics)
			: $this->Favourite_GetCountFavouriteOpenTopicsByUserId($sUserId);
	}
    /**
	 * Возвращает количество топиков которые создал юзер
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iPublish
	 * @return unknown
	 */
	public function GetCountTopicsPersonalByUser($sUserId,$iPublish) {
		$aFilter=array(
			'topic_publish' => $iPublish,
			'user_id' => $sUserId,
			'blog_type' => array('open','personal'),
		);
		/**
		 * Если пользователь смотрит свой профиль, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $this->oUserCurrent->getId()==$sUserId) {
			$aFilter['blog_type'][]='close';
		}
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_count_user_{$s}"))) {
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_user_{$s}", array("topic_update_user_{$sUserId}"), 60*60*24);
		}
		return 	$data;
	}
}
?>