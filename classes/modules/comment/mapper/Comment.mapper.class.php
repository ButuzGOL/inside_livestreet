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

class ModuleComment_MapperComment extends Mapper {	
	
		public function GetCommentsOnline($sTargetType,$aExcludeTargets,$iLimit) {
		$sql = "SELECT
					comment_id
				FROM
					".Config::Get('db.table.comment_online')."
				WHERE
					target_type = ?
				{ AND target_parent_id NOT IN(?a) }
				ORDER by comment_online_id desc limit 0, ?d ; ";

		$aComments=array();
		if ($aRows=$this->oDb->select(
				$sql,$sTargetType,
				(count($aExcludeTargets)?$aExcludeTargets:DBSIMPLE_SKIP),
				$iLimit
			)
		) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
    public function GetCommentsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.comment')."
				WHERE
					comment_id IN(?a)
				ORDER by FIELD(comment_id,?a)";
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aComments[]=Engine::GetEntity('Comment',$aRow);
			}
		}
		return $aComments;
	}
    public function GetCommentsRatingByDate($sDate,$sTargetType,$iLimit,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
			$sql = "SELECT
					comment_id
				FROM
					".Config::Get('db.table.comment')."
				WHERE
					target_type = ?
					AND
					comment_date >= ?
					AND
					comment_rating >= 0
					AND
					comment_delete = 0
					AND
					comment_publish = 1
					{ AND target_id NOT IN(?a) }
					{ AND target_parent_id NOT IN (?a) }
				ORDER by comment_rating desc, comment_id desc
				LIMIT 0, ?d ";
		$aComments=array();
		if ($aRows=$this->oDb->select(
				$sql,$sTargetType, $sDate,
				(is_array($aExcludeTarget)&&count($aExcludeTarget)) ? $aExcludeTarget : DBSIMPLE_SKIP,
				(count($aExcludeParentTarget) ? $aExcludeParentTarget : DBSIMPLE_SKIP),
				$iLimit
			)
		) {
			foreach ($aRows as $aRow) {
				$aComments[]=$aRow['comment_id'];
			}
		}
		return $aComments;
	}
    public function GetCountCommentsByUserId($sId,$sTargetType,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
		$sql = "SELECT
					count(comment_id) as count
				FROM
					".Config::Get('db.table.comment')."
				WHERE
					user_id = ?d
					AND
					target_type= ?
					AND
					comment_delete = 0
					AND
					comment_publish = 1
					{ AND target_id NOT IN (?a) }
					{ AND target_parent_id NOT IN (?a) }
					";
		if ($aRow=$this->oDb->selectRow(
				$sql,$sId,$sTargetType,
				(count($aExcludeTarget) ? $aExcludeTarget : DBSIMPLE_SKIP),
				(count($aExcludeParentTarget) ? $aExcludeParentTarget : DBSIMPLE_SKIP)
			)
		) {
			return $aRow['count'];
		}
		return false;
	}
}
?>