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
 * Управление простым конфигом в виде массива
 */
class Config {

	/**
	 * Default instance to operate with
	 *
	 * @var string
	 */
	const DEFAULT_CONFIG_INSTANCE = 'general';
    /**
	 * Store for configuration entries for current instance
	 *
	 * @var array
	 */
	protected $aConfig=array();
    /**
	 * Массив сущностей класса
	 *
	 * @var array
	 */
	static protected $aInstance=array();
	/**
	 * Disabled constract process
	 */
	protected function __construct() {
		
	}
	/**
	 * Load configuration array from file
	 *
	 * @param  string $sFile
	 * @param  bool $bRewrite
	 * @return ConfigSimple
	 */
	static public function LoadFromFile($sFile,$bRewrite=true,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Check if file exists
		if (!file_exists($sFile)) {
			return false;
		}
		// Get config from file
		$aConfig=include($sFile);
		return self::Load($aConfig,$bRewrite,$sInstance);
	}

    /**
	 * Load configuration array from given array
	 *
	 * @param  string $aConfig
	 * @param  bool   $bRewrite
	 * @return ConfigSimple
	 */
	static public function Load($aConfig,$bRewrite=true,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Check if it`s array
		if(!is_array($aConfig)) {
			return false;
		}
		// Set config to current or handle instance
		self::getInstance($sInstance)->SetConfig($aConfig,$bRewrite);
		return self::getInstance($sInstance);
	}
    /**
	 * Ограничиваем объект только одним экземпляром
	 *
	 * @return ConfigSimple
	 */
	static public function getInstance($sName=self::DEFAULT_CONFIG_INSTANCE) {
		if (isset(self::$aInstance[$sName])) {
			return self::$aInstance[$sName];
		} else {
			self::$aInstance[$sName]= new self();
			return self::$aInstance[$sName];
		}
	}
    public function SetConfig($aConfig=array(),$bRewrite=true) {
		if (is_array($aConfig)) {
			if ($bRewrite) {
				$this->aConfig=$aConfig;
			} else {
				$this->aConfig=$this->ArrayEmerge($this->aConfig,$aConfig);
			}
			return true;
		}
		$this->aConfig=array();
		return false;
	}
    protected function ArrayEmerge($aArr1,$aArr2) {
		return $this->func_array_merge_assoc($aArr1,$aArr2);
	}
    /**
	 * Retrive information from configuration array
	 *
	 * @param  string $sKey      Path to needed value
	 * @param  string $sInstance Name of needed instance
	 * @return mixed
	 */
	static public function Get($sKey='', $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return all config array
		if($sKey=='') {
			return self::getInstance($sInstance)->GetConfig();
		}

		return self::getInstance($sInstance)->GetValue($sKey,$sInstance);
	}
    public function GetConfig() {
		return $this->aConfig;
	}
    /**
	 * Получает значение из конфигурации по переданному ключу
	 *
	 * @param  string $sKey
	 * @param  string $sInstance
	 * @return mixed
	 */
	public function GetValue($sKey, $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return config by path (separator=".")
		$aKeys=explode('.',$sKey);

		$cfg=$this->GetConfig();
		foreach ((array)$aKeys as $sK) {
			if(isset($cfg[$sK])) {
				$cfg=$cfg[$sK];
            } else {
				return null;
			}
		}
        
        $cfg = self::KeyReplace($cfg,$sInstance);
		return $cfg;
	}
    static public function KeyReplace($cfg,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		if(is_array($cfg)) {
			foreach($cfg as $k=>$v) {
				$k_replaced = self::KeyReplace($k, $sInstance);
				if($k==$k_replaced) {
					$cfg[$k] = self::KeyReplace($v,$sInstance);
				} else {
					$cfg[$k_replaced] = self::KeyReplace($v,$sInstance);
					unset($cfg[$k]);
				}
			}
		} else {
			if(preg_match('~___([\S|\.|]+)___~Ui',$cfg))
				$cfg = preg_replace_callback(
					'~___([\S|\.]+)___~Ui',
					create_function('$value','return Config::Get($value[1],"'.$sInstance.'");'),
					$cfg
				);
		}
		return $cfg;
	}
    /**
	 * Try to find element by given key
	 * Using function ARRAY_KEY_EXISTS (like in SPL)
	 *
	 * Workaround for http://bugs.php.net/bug.php?id=40442
	 *
	 * @param  string $sKey      Path to needed value
	 * @param  string $sInstance Name of needed instance
	 * @return bool
	 */
	static public function isExist($sKey, $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return all config array
		if($sKey=='') {
			return (count((array)self::getInstance($sInstance)->GetConfig())>0);
		}
		// Analyze config by path (separator=".")
		$aKeys=explode('.',$sKey);
		$cfg=self::getInstance($sInstance)->GetConfig();
		foreach ((array)$aKeys as $sK) {
			if (array_key_exists($sK, $cfg)) {
				$cfg=$cfg[$sK];
			} else {
				return false;
			}
		}
		return true;
	}
    /**
	 * Add information in config array by handle path
	 *
	 * @param  string $sKey
	 * @param  mixed $value
	 * @param  string $sInstance
	 * @return bool
	 */
	static public function Set($sKey,$value,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		$aKeys=explode('.',$sKey);
		$sEval='self::getInstance($sInstance)->aConfig';
		foreach ($aKeys as $sK) {
			$sEval.="['$sK']";
		}
		$sEval.='=$value;';
		eval($sEval);

		return true;
	}
    /**
	 * Сливает два ассоциативных массива
	 *
	 * @param unknown_type $aArr1
	 * @param unknown_type $aArr2
	 * @return unknown
	 */
	protected function func_array_merge_assoc($aArr1,$aArr2) {
		$aRes=$aArr1;
		foreach ($aArr2 as $k2 => $v2) {
			$bIsKeyInt=false;
			if (is_array($v2)) {
				foreach ($v2 as $k => $v) {
					if (is_int($k)) {
						$bIsKeyInt=true;
						break;
					}
				}
			}
			if (is_array($v2) and !$bIsKeyInt and isset($aArr1[$k2])) {
				$aRes[$k2]=$this->func_array_merge_assoc($aArr1[$k2],$v2);
			} else {
				$aRes[$k2]=$v2;
			}
		}
		return $aRes;
	}
}
?>