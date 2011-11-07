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

class ModuleFavourite_MapperFavourite extends Mapper {	
		
	public function GetFavouritesByArray($aArrayId,$sTargetType,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}				
		$sql = "SELECT *							 
				FROM ".Config::Get('db.table.favourite')."
				WHERE 			
					user_id = ?d
					AND		
					target_id IN(?a) 	
					AND
					target_type = ? ";
		$aFavourites=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$aArrayId,$sTargetType)) {
			foreach ($aRows as $aRow) {
				$aFavourites[]=Engine::GetEntity('Favourite',$aRow);
			}
		}		
		return $aFavourites;
	}	
	public function GetCountFavouritesByUserId($sUserId,$sTargetType,$aExcludeTarget) {
		$sql = "SELECT
					count(target_id) as count
				FROM
					".Config::Get('db.table.favourite')."
				WHERE
						user_id = ?
					AND
						target_publish = 1
					AND
						target_type = ?
					{ AND target_id NOT IN (?a) }
					;";
		return ( $aRow=$this->oDb->selectRow(
						$sql,$sUserId,
						$sTargetType,
						(count($aExcludeTarget) ? $aExcludeTarget : DBSIMPLE_SKIP)
					)
				)
					? $aRow['count']
					: false;
	}
    public function GetCountFavouriteOpenCommentsByUserId($sUserId) {
		$sql = "SELECT
					count(f.target_id) as count
				FROM
					".Config::Get('db.table.favourite')." AS f,
					".Config::Get('db.table.comment')." AS c,
					".Config::Get('db.table.topic')." AS t,
					".Config::Get('db.table.blog')." AS b
				WHERE
						f.user_id = ?d
					AND
						f.target_publish = 1
					AND
						f.target_type = 'comment'
					AND
						f.target_id = c.comment_id
					AND
						c.target_id = t.topic_id
					AND
						t.blog_id = b.blog_id
					AND
						b.blog_type IN ('open', 'personal')
					;";
		return ( $aRow=$this->oDb->selectRow($sql,$sUserId) )
					? $aRow['count']
					: false;
	}
}
?>