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

class ModuleBlog_MapperBlog extends Mapper {	
	protected $oUserCurrent=null;
	
	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
	
	public function AddBlog(ModuleBlog_EntityBlog $oBlog) {
		$sql = "INSERT INTO ".Config::Get('db.table.blog')." 
			(user_owner_id,
			blog_title,
			blog_description,
			blog_type,			
			blog_date_add,
			blog_limit_rating_topic,
			blog_url,
			blog_avatar
			)
			VALUES(?d,  ?,	?,	?,	?,	?, ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oBlog->getOwnerId(),$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateAdd(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar())) {
			return $iId;
		}		
		return false;
	}
    public function GetBlogs() {
		$sql = "SELECT
			b.blog_id
			FROM
				".Config::Get('db.table.blog')." as b
			WHERE
				b.blog_type<>'personal'
				";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		return $aBlogs;
	}
    	public function GetBlogsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					b.*
				FROM
					".Config::Get('db.table.blog')." as b
				WHERE
					b.blog_id IN(?a)
				ORDER BY FIELD(b.blog_id,?a) ";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=Engine::GetEntity('Blog',$aBlog);
			}
		}
		return $aBlogs;
	}
    public function GetBlogsByOwnerId($sUserId) {
		$sql = "SELECT
			b.blog_id
			FROM
				".Config::Get('db.table.blog')." as b
			WHERE
				b.user_owner_id = ?
				AND
				b.blog_type<>'personal'
				";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		return $aBlogs;
	}
    public function GetBlogUsers($aFilter) {
		$sWhere=' 1=1 ';
		if (isset($aFilter['blog_id'])) {
			$sWhere.=" AND bu.blog_id =  ".(int)$aFilter['blog_id'];
		}
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND bu.user_id =  ".(int)$aFilter['user_id'];
		}
		if (isset($aFilter['user_role'])) {
			if(!is_array($aFilter['user_role'])) {
				$aFilter['user_role']=array($aFilter['user_role']);
			}
			$sWhere.=" AND bu.user_role IN ('".join("', '",$aFilter['user_role'])."')";
		} else {
			$sWhere.=" AND bu.user_role>".ModuleBlog::BLOG_USER_ROLE_GUEST;
		}

		$sql = "SELECT
					bu.*
				FROM
					".Config::Get('db.table.blog_user')." as bu
				WHERE
					".$sWhere."
				;
					";
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=Engine::GetEntity('Blog_BlogUser',$aUser);
			}
		}
		return $aBlogUsers;
	}
    public function GetCloseBlogs() {
		$sql = "SELECT b.blog_id
				FROM ".Config::Get('db.table.blog')." as b
				WHERE b.blog_type='close'
			;";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_id'];
			}
		}
		return $aReturn;
	}
	public function GetBlogsRating(&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT
					b.blog_id
				FROM
					".Config::Get('db.table.blog')." as b
				WHERE
					b.blog_type<>'personal'
				ORDER by b.blog_rating desc
				LIMIT ?d, ?d 	";
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_id'];
			}
		}
		return $aReturn;
	}
}
?>