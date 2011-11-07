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
 * Абстракция плагина, от которой наследуются все плагины
 *
 */
abstract class Plugin extends Object {
    /**
	 * Путь к шаблонам с учетом наличия соответствующего skin`a
	 *
	 * @var array
	 */
	static protected $aTemplatePath=array();
    /**
	 * Возвращает правильный путь к директории шаблонов
	 *
	 * @return string
	 */
	static public function GetTemplatePath($sName) {
		$sName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sName,$aMatches)
			? strtolower($aMatches[1])
			: strtolower($sName);
		if(!isset(self::$aTemplatePath[$sName])) {
			$aPaths=glob(Config::Get('path.root.server').'/plugins/'.$sName.'/templates/skin/*',GLOB_ONLYDIR);
			$sTemplateName=($aPaths and in_array(Config::Get('view.skin'),array_map('basename',$aPaths)))
				? Config::Get('view.skin')
				: 'default';

			$sDir=Config::Get('path.root.server')."/plugins/{$sName}/templates/skin/{$sTemplateName}/";
			self::$aTemplatePath[$sName] = is_dir($sDir) ? $sDir : null;
		}

		return self::$aTemplatePath[$sName];
	}
    
    public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}

?>