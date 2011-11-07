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
 * Модуль рассылок уведомлений пользователям
 *
 */
class ModuleNotify extends Module {
	/**
	 * Статусы степени обработки заданий отложенной публикации в базе данных
	 */
	const NOTIFY_TASK_STATUS_NULL=1;
	/**
	 * Объект локального вьювера для рендеринга сообщений
	 *
	 * @var ModuleViewer
	 */
	protected $oViewerLocal=null;
	/**
	 * Массив заданий на удаленную публикацию
	 * 
	 * @var array
	 */
	protected $aTask=array();
	/**
	 * Меппер
	 *
	 * @var Mapper_Notify
	 */
	protected $oMapper=null;
	/**
	 * Инициализация модуля
	 * Создаём локальный экземпляр модуля Viewer
	 * Момент довольно спорный, но позволяет избавить основной шаблон от мусора уведомлений
	 *
	 */
	public function Init() {		
		if (!class_exists('ModuleViewer')) {
			require_once(Config::Get('path.root.engine')."/modules/viewer/Viewer.class.php");
		}
		$this->oViewerLocal=$this->Viewer_GetLocalViewer();
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	
	/**
	 * При завершении работы модуля проверяем наличие 
	 * отложенных заданий в массиве и при необходимости
	 * передаем их в меппер
	 */	
	public function Shutdown() {
		if(!empty($this->aTask) && Config::Get('module.notify.delayed')) {
			$this->oMapper->AddTaskArray($this->aTask);
			$this->aTask=array();
		}
	}
    /**
	 * Отправляет уведомление при регистрации с активацией
	 *
	 * @param ModuleUser_EntityUser $oUser
	 * @param string $sPassword
	 */
	public function SendRegistrationActivate(ModuleUser_EntityUser $oUser,$sPassword) {
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);
		$this->oViewerLocal->Assign('sPassword',$sPassword);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.registration_activate.tpl'));

		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_registration_activate'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
    /**
	 * Возвращает путь к шаблону по переданному имени
	 *
	 * @param  string $sName
	 * @param  string $sPluginName
	 * @return string
	 */
	public function GetTemplatePath($sName,$sPluginName=null) {
		if ($sPluginName) {
			$sPluginName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sPluginName,$aMatches)
			? strtolower($aMatches[1])
			: strtolower($sPluginName);

			$sLangDir=Plugin::GetTemplatePath($sPluginName).'notify/'.$this->Lang_GetLang();
			if(is_dir($sLangDir)) {
				return $sLangDir.'/'.$sName;
			}
			return Plugin::GetTemplatePath($sPluginName).'notify/'.$this->Lang_GetLangDefault().'/'.$sName;
		} else {
			$sLangDir = 'notify/'.$this->Lang_GetLang();
			/**
		 	* Если директория с сообщениями на текущем языке отсутствует,
		 	* используем язык по умолчанию
		 	*/
			if(is_dir(rtrim(Config::Get('path.static.skin'),'/').'/'.$sLangDir)) {
				return $sLangDir.'/'.$sName;
			}
			return 'notify/'.$this->Lang_GetLangDefault().'/'.$sName;
		}
	}
    /**
	 * Отправляет уведомление о регистрации
	 *
	 * @param ModuleUser_EntityUser $oUser
	 * @param string $sPassword
	 */
	public function SendRegistration(ModuleUser_EntityUser $oUser,$sPassword) {
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);
		$this->oViewerLocal->Assign('sPassword',$sPassword);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.registration.tpl'));

		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_registration'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
    /**
	 * Уведомление с новым паролем после его восставновления
	 *
	 * @param ModuleUser_EntityUser $oUser
	 * @param unknown_type $sNewPassword
	 */
	public function SendReminderPassword(ModuleUser_EntityUser $oUser,$sNewPassword) {
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);
		$this->oViewerLocal->Assign('sNewPassword',$sNewPassword);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.reminder_password.tpl'));

		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_reminder_password'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
    /**
	 * Уведомление при восстановлении пароля
	 *
	 * @param ModuleUser_EntityUser $oUser
	 * @param ModuleUser_EntityReminder $oReminder
	 */
	public function SendReminderCode(ModuleUser_EntityUser $oUser,ModuleUser_EntityReminder $oReminder) {
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);
		$this->oViewerLocal->Assign('oReminder',$oReminder);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.reminder_code.tpl'));

		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_reminder_code'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
}
?>