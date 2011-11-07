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

class ModuleTalk_MapperTalk extends Mapper {	
	public function GetCountCommentNew($sUserId) {
		$sql = "
					SELECT
						SUM(tu.comment_count_new) as count_new												
					FROM   						
  						".Config::Get('db.table.talk_user')." as tu
					WHERE   						
  						tu.user_id = ?d  
  						AND
  						tu.talk_user_active=?d							
		";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId, ModuleTalk::TALK_USER_ACTIVE)) {
			return $aRow['count_new'];
		}
		return false;
	}
	
	public function GetCountTalkNew($sUserId) {
		$sql = "
					SELECT
						COUNT(tu.talk_id) as count_new												
					FROM   						
  						".Config::Get('db.table.talk_user')." as tu
					WHERE
						tu.user_id = ?d 
  						AND
  						tu.date_last IS NULL 
  						AND
  						tu.talk_user_active=?d						
		";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId,ModuleTalk::TALK_USER_ACTIVE)) {
			return $aRow['count_new'];
		}
		return false;
	}
}
?>