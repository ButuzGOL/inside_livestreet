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

class ModuleTopic_MapperTopic extends Mapper {	
		
	public function GetCountTopics($aFilter) {
		$sWhere=$this->buildFilter($aFilter);
		$sql = "SELECT
					count(t.topic_id) as count
				FROM
					".Config::Get('db.table.topic')." as t,
					".Config::Get('db.table.blog')." as b
				WHERE
					1=1

					".$sWhere."

					AND
					t.blog_id=b.blog_id;";
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}

    protected function buildFilter($aFilter) {
		$sWhere='';
		if (isset($aFilter['topic_publish'])) {
			$sWhere.=" AND t.topic_publish =  ".(int)$aFilter['topic_publish'];
		}
		if (isset($aFilter['topic_rating']) and is_array($aFilter['topic_rating'])) {
			$sPublishIndex='';
			if (isset($aFilter['topic_rating']['publish_index']) and $aFilter['topic_rating']['publish_index']==1) {
				$sPublishIndex=" or topic_publish_index=1 ";
			}
			if ($aFilter['topic_rating']['type']=='top') {
				$sWhere.=" AND ( t.topic_rating >= ".(float)$aFilter['topic_rating']['value']." {$sPublishIndex} ) ";
			} else {
				$sWhere.=" AND ( t.topic_rating < ".(float)$aFilter['topic_rating']['value']."  ) ";
			}
		}
		if (isset($aFilter['topic_new'])) {
			$sWhere.=" AND t.topic_date_add >=  '".$aFilter['topic_new']."'";
		}
		if (isset($aFilter['user_id'])) {
			$sWhere.=is_array($aFilter['user_id'])
				? " AND t.user_id IN(".implode(', ',$aFilter['user_id']).")"
				: " AND t.user_id =  ".(int)$aFilter['user_id'];
		}
		if (isset($aFilter['blog_id'])) {
			if(!is_array($aFilter['blog_id'])) {
				$aFilter['blog_id']=array($aFilter['blog_id']);
			}
			$sWhere.=" AND t.blog_id IN ('".join("','",$aFilter['blog_id'])."')";
		}
		if (isset($aFilter['blog_type']) and is_array($aFilter['blog_type'])) {
			$aBlogTypes = array();
			foreach ($aFilter['blog_type'] as $sType=>$aBlogId) {
				/**
				 * Позиция вида 'type'=>array('id1', 'id2')
				 */
				if(!is_array($aBlogId) && is_string($sType)){
					$aBlogId=array($aBlogId);
				}
				/**
				 * Позиция вида 'type'
				 */
				if(is_string($aBlogId) && is_int($sType)) {
					$sType=$aBlogId;
					$aBlogId=array();
				}

				$aBlogTypes[] = (count($aBlogId)==0)
					? "(b.blog_type='".$sType."')"
					: "(b.blog_type='".$sType."' AND t.blog_id IN ('".join("','",$aBlogId)."'))";
			}
			$sWhere.=" AND (".join(" OR ",(array)$aBlogTypes).")";
		}
		return $sWhere;
	}
	public function GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage) {
		$sWhere=$this->buildFilter($aFilter);

		if(isset($aFilter['order']) and !is_array($aFilter['order'])) {
			$aFilter['order'] = array($aFilter['order']);
		} else {
			$aFilter['order'] = array('t.topic_date_add desc');
		}

		$sql = "SELECT
						t.topic_id
					FROM
						".Config::Get('db.table.topic')." as t,
						".Config::Get('db.table.blog')." as b
					WHERE
						1=1
						".$sWhere."
						AND
						t.blog_id=b.blog_id
					ORDER BY ".
						implode(', ', $aFilter['order'])
				."
					LIMIT ?d, ?d";
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
    public function GetAllTopics($aFilter) {
		$sWhere=$this->buildFilter($aFilter);

		$sql = "SELECT
						t.topic_id
					FROM
						".Config::Get('db.table.topic')." as t,
						".Config::Get('db.table.blog')." as b
					WHERE
						1=1
						".$sWhere."
						AND
						t.blog_id=b.blog_id
					ORDER by t.topic_id desc";
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}

		return $aTopics;
	}

    public function GetTopicsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					t.*,
					tc.*							 
				FROM 
					".Config::Get('db.table.topic')." as t	
					JOIN  ".Config::Get('db.table.topic_content')." AS tc ON t.topic_id=tc.topic_id				
				WHERE 
					t.topic_id IN(?a) 									
				ORDER BY FIELD(t.topic_id,?a) ";
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=Engine::GetEntity('Topic',$aTopic);
			}
		}		
		return $aTopics;
	}
    public function GetTopicsQuestionVoteByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					v.*
				FROM
					".Config::Get('db.table.topic_question_vote')." as v
				WHERE
					v.topic_id IN(?a)
					AND
					v.user_voter_id = ?d
				";
		$aVotes=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aVotes[]=Engine::GetEntity('Topic_TopicQuestionVote',$aRow);
			}
		}
		return $aVotes;
	}
    public function GetTopicsReadByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					t.*
				FROM
					".Config::Get('db.table.topic_read')." as t
				WHERE
					t.topic_id IN(?a)
					AND
					t.user_id = ?d
				";
		$aReads=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aReads[]=Engine::GetEntity('Topic_TopicRead',$aRow);
			}
		}
		return $aReads;
	}
    public function GetOpenTopicTags($iLimit) {
		$sql = "
			SELECT
				tt.topic_tag_text,
				count(tt.topic_tag_text)	as count
			FROM
				".Config::Get('db.table.topic_tag')." as tt,
				".Config::Get('db.table.blog')." as b
			WHERE
				tt.blog_id = b.blog_id
				AND
				b.blog_type IN ('open','personal')
			GROUP BY
				tt.topic_tag_text
			ORDER BY
				count desc
			LIMIT 0, ?d
				";
		$aReturn=array();
		$aReturnSort=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[mb_strtolower($aRow['topic_tag_text'],'UTF-8')]=$aRow;
			}
			ksort($aReturn);
			foreach ($aReturn as $aRow) {
				$aReturnSort[]=Engine::GetEntity('Topic_TopicTag',$aRow);
			}
		}
		return $aReturnSort;
	}
    public function GetTopicsRatingByDate($sDate,$iLimit,$aExcludeBlog=array()) {
		$sql = "SELECT
						t.topic_id
					FROM
						".Config::Get('db.table.topic')." as t
					WHERE
						t.topic_publish = 1
						AND
						t.topic_date_add >= ?
						AND
						t.topic_rating >= 0
						{ AND t.blog_id NOT IN(?a) }
					ORDER by t.topic_rating desc, t.topic_id desc
					LIMIT 0, ?d ";
		$aTopics=array();
		if ($aRows=$this->oDb->select(
				$sql,$sDate,
				(is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
				$iLimit
			)
		) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
}
?>