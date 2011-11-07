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
 * Модуль разговоров(почта)
 *
 */
class ModuleTalk extends Module {		
	/**
	 * Статус TalkUser в базе данных
	 */
	const TALK_USER_ACTIVE = 1;
	const TALK_USER_DELETE_BY_SELF = 2;
	const TALK_USER_DELETE_BY_AUTHOR = 4;
    
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
    /**
	 * Получает число новых тем и комментов где есть юзер
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetCountTalkNew($sUserId) {
		if (false === ($data = $this->Cache_Get("talk_count_all_new_user_{$sUserId}"))) {
			$data = $this->oMapper->GetCountCommentNew($sUserId)+$this->oMapper->GetCountTalkNew($sUserId);
			$this->Cache_Set($data, "talk_count_all_new_user_{$sUserId}", array("talk_new","update_talk_user","talk_read_user_{$sUserId}"), 60*60*24);
		}
        return $data;
	}
}
?>