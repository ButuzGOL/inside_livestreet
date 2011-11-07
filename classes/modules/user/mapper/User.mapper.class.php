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

class ModuleUser_MapperUser extends Mapper {
	protected $oUserCurrent=null;

	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
    public function GetInviteByCode($sCode,$iUsed=0) {
		$sql = "SELECT * FROM ".Config::Get('db.table.invite')." WHERE invite_code = ? and invite_used = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sCode,$iUsed)) {
			return Engine::GetEntity('User_Invite',$aRow);
		}
		return null;
	}
    public function GetUserByLogin($sLogin) {
		$sql = "SELECT
				u.user_id
			FROM
				".Config::Get('db.table.user')." as u
			WHERE
				u.user_login = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sLogin)) {
			return $aRow['user_id'];
		}
		return null;
	}
    public function GetUserByMail($sMail) {
		$sql = "SELECT
				u.user_id
			FROM
				".Config::Get('db.table.user')." as u
			WHERE u.user_mail = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sMail)) {
			return $aRow['user_id'];
		}
		return null;
	}
    public function GetUsersByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					u.*	,
					IF(ua.user_id IS NULL,0,1) as user_is_administrator
				FROM
					".Config::Get('db.table.user')." as u
					LEFT JOIN ".Config::Get('db.table.user_administrator')." AS ua ON u.user_id=ua.user_id
				WHERE
					u.user_id IN(?a)
				ORDER BY FIELD(u.user_id,?a) ";
		$aUsers=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=Engine::GetEntity('User',$aUser);
			}
		}
		return $aUsers;
	}
    public function GetSessionsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					s.*
				FROM
					".Config::Get('db.table.session')." as s
				WHERE
					s.user_id IN(?a) ";
		$aRes=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aRes[]=Engine::GetEntity('User_Session',$aRow);
			}
		}
		return $aRes;
	}
    public function Add(ModuleUser_EntityUser $oUser) {
		$sql = "INSERT INTO ".Config::Get('db.table.user')."
			(user_login,
			user_password,
			user_mail,
			user_date_register,
			user_ip_register,
			user_activate,
			user_activate_key
			)
			VALUES(?,  ?,	?,	?,	?,	?,	?)
		";
		if ($iId=$this->oDb->query($sql,$oUser->getLogin(),$oUser->getPassword(),$oUser->getMail(),$oUser->getDateRegister(),$oUser->getIpRegister(),$oUser->getActivate(),$oUser->getActivateKey())) {
			return $iId;
		}
		return false;
	}
    public function UpdateInvite(ModuleUser_EntityInvite $oInvite) {
		$sql = "UPDATE ".Config::Get('db.table.invite')."
			SET
				user_to_id = ? ,
				invite_date_used = ? ,
				invite_used =?
			WHERE invite_id = ?
		";
		if ($this->oDb->query($sql,$oInvite->getUserToId(), $oInvite->getDateUsed(), $oInvite->getUsed(), $oInvite->getId())) {
			return true;
		}
		return false;
	}
    public function CreateSession(ModuleUser_EntitySession $oSession) {
		$sql = "REPLACE INTO ".Config::Get('db.table.session')."
			SET
				session_key = ? ,
				user_id = ? ,
				session_ip_create = ? ,
				session_ip_last = ? ,
				session_date_create = ? ,
				session_date_last = ?
		";
		return $this->oDb->query($sql,$oSession->getKey(), $oSession->getUserId(), $oSession->getIpCreate(), $oSession->getIpLast(), $oSession->getDateCreate(), $oSession->getDateLast());
	}
    public function GetReminderByCode($sCode) {
		$sql = "SELECT 
					*										
				FROM 
					".Config::Get('db.table.reminder')." 				
				WHERE 	
					reminder_code = ?";
		if ($aRow=$this->oDb->selectRow($sql,$sCode)) {
			return Engine::GetEntity('User_Reminder',$aRow);
		}
		return null;
	}
    public function UpdateReminder(ModuleUser_EntityReminder $oReminder) {
		return $this->AddReminder($oReminder);
	}
    public function AddReminder(ModuleUser_EntityReminder $oReminder) {
		$sql = "REPLACE ".Config::Get('db.table.reminder')."
			SET
				reminder_code = ? ,
				user_id = ? ,
				reminder_date_add = ? ,
				reminder_date_used = ? ,
				reminder_date_expire = ? ,
				reminde_is_used = ?
		";
		return $this->oDb->query($sql,$oReminder->getCode(),$oReminder->getUserId(),$oReminder->getDateAdd(),$oReminder->getDateUsed(),$oReminder->getDateExpire(),$oReminder->getIsUsed());
	}
    public function GetUserBySessionKey($sKey) {
		$sql = "SELECT
					s.user_id
				FROM
					".Config::Get('db.table.session')." as s
				WHERE
					s.session_key = ?
				";
		if ($aRow=$this->oDb->selectRow($sql,$sKey)) {
			return $aRow['user_id'];
		}
		return null;
	}
    public function UpdateSession(ModuleUser_EntitySession $oSession) {
		$sql = "UPDATE ".Config::Get('db.table.session')."
			SET
				session_ip_last = ? ,
				session_date_last = ?
			WHERE user_id = ?
		";
		return $this->oDb->query($sql,$oSession->getIpLast(), $oSession->getDateLast(), $oSession->getUserId());
	}
    /**
	 * Получить отношей дружбы по массиву идентификаторов
	 *
	 * @param  array  $aArrayId
	 * @param  string $sUserId
	 * @param  int    $iStatus
	 * @return array
	 */
	public function GetFriendsByArrayId($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.friend')."
				WHERE
					( `user_from`=?d AND `user_to` IN(?a) )
					OR
					( `user_from` IN(?a) AND `user_to`=?d )
				";
		$aRows=$this->oDb->select(
			$sql,
			$sUserId,$aArrayId,
			$aArrayId,$sUserId
		);
		$aRes=array();
		if ($aRows) {
			foreach ($aRows as $aRow) {
				$aRow['user']=$sUserId;
				$aRes[]=Engine::GetEntity('User_Friend',$aRow);
			}
		}
		return $aRes;
	}
    /**
	 * Получить список друзей указанного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int    $iStatus
	 * @return array
	 */
	public function GetUsersFriend($sUserId) {
		$sql = "SELECT
					uf.user_from,
					uf.user_to
				FROM
					".Config::Get('db.table.friend')." as uf
				WHERE
					( uf.user_from = ?d
					OR
					uf.user_to = ?d )
					AND
					( 	uf.status_from + uf.status_to = ?d
					OR
						(uf.status_from = ?d AND uf.status_to = ?d )
					)
					;";
		$aUsers=array();
		if ($aRows=$this->oDb->select(
				$sql,
				$sUserId,
				$sUserId,
				ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER,
				ModuleUser::USER_FRIEND_ACCEPT,
				ModuleUser::USER_FRIEND_ACCEPT
			)
		) {
			foreach ($aRows as $aUser) {
				$aUsers[]=($aUser['user_from']==$sUserId)
							? $aUser['user_to']
							: $aUser['user_from'];
			}
		}
		return array_unique($aUsers);
	}
    public function GetUsersInvite($sUserId) {
		$sql = "SELECT
					i.user_to_id
				FROM
					".Config::Get('db.table.invite')." as i
				WHERE
					i.user_from_id = ?d	";
		$aUsers=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aUser) {
				$aUsers[]=$aUser['user_to_id'];
			}
		}
		return $aUsers;
	}
    public function GetUserInviteFrom($sUserIdTo) {
		$sql = "SELECT
					i.user_from_id
				FROM
					".Config::Get('db.table.invite')." as i
				WHERE
					i.user_to_id = ?d
				LIMIT 0,1;
					";
		if ($aRow=$this->oDb->selectRow($sql,$sUserIdTo)) {
			return $aRow['user_from_id'];
		}
		return null;
	}
}
?>