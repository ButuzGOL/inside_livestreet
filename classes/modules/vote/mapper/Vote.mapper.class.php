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

class ModuleVote_MapperVote extends Mapper {	
		
	public function GetVoteByArray($aArrayId,$sTargetType,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}				
		$sql = "SELECT 
					*							 
				FROM 
					".Config::Get('db.table.vote')."
				WHERE 					
					target_id IN(?a) 	
					AND
					target_type = ? 
					AND
					user_voter_id = ?d ";
		$aVotes=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sTargetType,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aVotes[]=Engine::GetEntity('Vote',$aRow);
			}
		}		
		return $aVotes;
	}	
}
?>