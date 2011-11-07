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
 * Модуль безопасности 
 *
 */
class ModuleSecurity extends Module {
	/**
	 * Инициализируем модуль
	 *
	 */
	public function Init() {
		
	}

    public function Shutdown() {
		$this->SetSessionKey();
	}
    /**
	 * Устанавливает security-ключ в сессию
	 *
	 * @return string
	 */
	public function SetSessionKey() {
		$sCode = $this->GenerateSessionKey();
        $this->Viewer_Assign('LIVESTREET_SECURITY_KEY',$sCode);

		return $sCode;
	}
    /**
	 * Генерирует текущий security-ключ
	 *
	 * @return string
	 */
	protected function GenerateSessionKey() {
		return md5($this->Session_GetId().Config::Get('module.security.hash'));
	}
}
?>