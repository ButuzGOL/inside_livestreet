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
 * Если не стоит расширения mb
 *
 * @param unknown_type $s
 * @return unknown
 */
if (!function_exists('mb_strlen')) {
	function mb_strlen($s,$sEncode="UTF-8") {		
		$length = strlen(iconv($sEncode, 'Windows-1251', $s));
      	return (int)$length;
	}
}

/**
 * Если не стоит расширения mb
 */
if (!function_exists('mb_strtolower')) {
	function mb_strtolower($s,$sEncode="UTF-8") {		
		$s=iconv($sEncode,"Windows-1251",$s);
		$s=strtolower($s);
		$s=iconv("Windows-1251",$sEncode,$s);
		return $s;
	}
}

/**
 * функция вывода отладочных сообщений через "хакерскую" консоль Дмитрия Котерова
 */
if (defined('SYS_HACKER_CONSOLE')) {
	if (SYS_HACKER_CONSOLE) {
		require_once Config::Get('path.root.server')."/engine/lib/external/HackerConsole/Main.php";
		new Debug_HackerConsole_Main(true);
	}
}

if (!function_exists('array_fill_keys')) {
	function array_fill_keys($aArr, $values) {
		if (!is_array($aArr)) {
			$aArr=array($aArr);
		}
		$aArrOut=array();
		foreach($aArr as $key => $value) {
			$aArrOut[$value] = $values;
		}
		return $aArrOut;
	}
}

if (!function_exists('array_intersect_key')) {
	function array_intersect_key($isec, $keys)   {
		$argc = func_num_args();
		if ($argc > 2) {
			for ($i = 1; !empty($isec) && $i < $argc; $i++) {
				$arr = func_get_arg($i);
				foreach (array_keys($isec) as $key) {
					if (!isset($arr[$key])) {
						unset($isec[$key]);
					}
				}
			}
			return $isec;
		} else {
			$res = array();
			foreach (array_keys($isec) as $key) {
				if (isset($keys[$key])) {
					$res[$key] = $isec[$key];
				}
			}
			return $res;
		}
	}
}

if (!function_exists('class_alias')) {
    function class_alias($original, $alias) {
        eval('abstract class ' . $alias . ' extends ' . $original . ' {}');
    }
}

/**
 * генерирует случайную последовательность символов
 *
 * @param unknown_type $iLength
 * @return unknown
 */
function func_generator($iLength=10) {
	if ($iLength>32) {
		$iLength=32;
	}
	return substr(md5(uniqid(mt_rand(),true)),0,$iLength);
}

/**
 * stripslashes умеющая обрабатывать массивы
 *
 * @param unknown_type $data
 */
function func_stripslashes(&$data) {
	if (is_array($data)) {
		foreach ($data as $sKey => $value) {
			if (is_array($value)) {
				func_stripslashes($data[$sKey]);
			} else {
				$data[$sKey]=stripslashes($value);
			}
		}
	} else {
		$data=stripslashes($data);
	}
}

function dump($msg) {
	if (SYS_HACKER_CONSOLE) {
		call_user_func(array('Debug_HackerConsole_Main', 'out'), $msg);
	} else {
		//var_dump($msg);
	}
}

/**
 * Меняет числовые ключи массива на их значения
 *
 * @param unknown_type $arr
 * @param unknown_type $sDefValue
 */
function func_array_simpleflip(&$arr,$sDefValue=1) {
	foreach ($arr as $key => $value) {
		if (is_int($key) and is_string($value)) {
			unset($arr[$key]);
			$arr[$value]=$sDefValue;
		}
	}
}
/**
 * Определяет был ли передан указанный параметр методом POST
 *
 * @param  string $sName
 * @return bool
 */
function isPost($sName) {
	return (getRequest($sName,null,'post')!==null);
}

/**
 * функция доступа к GET POST параметрам
 *
 * @param  string $sName
 * @param  mixed  $default
 * @param  string $sType
 * @return mixed
 */
function getRequest($sName,$default=null,$sType=null) {
	/**
	 * Выбираем в каком из суперглобальных искать указанный ключ
	 */
	switch (strtolower($sType)) {
		default:
		case null:
			$aStorage = $_REQUEST;
			break;
		case 'get':
			$aStorage = $_GET;
			break;
		case 'post':
			$aStorage = $_POST;
			break;
	}

	if (isset($aStorage[$sName])) {
		if (is_string($aStorage[$sName])) {
			return trim($aStorage[$sName]);
		} else {
			return $aStorage[$sName];
		}
	}
	return $default;
}
/**
 * htmlspecialchars умеющая обрабатывать массивы
 *
 * @param unknown_type $data
 */
function func_htmlspecialchars(&$data) {
	if (is_array($data)) {
		foreach ($data as $sKey => $value) {
			if (is_array($value)) {
				func_htmlspecialchars($data[$sKey]);
			} else {
				$data[$sKey]=htmlspecialchars($value);
			}
		}
	} else {
		$data=htmlspecialchars($data);
	}
}
/**
 * Проверяет на корректность значение соглавно правилу
 *
 * @param unknown_type $sValue
 * @param unknown_type $sParam
 * @param unknown_type $iMin
 * @param unknown_type $iMax
 * @return unknown
 */
function func_check($sValue,$sParam,$iMin=1,$iMax=100) {
	if (is_array($sValue)) {
		return false;
	}
	switch($sParam)
	{
		case 'id': if (preg_match("/^\d{".$iMin.','.$iMax."}$/",$sValue)){ return true; } break;
		case 'float': if (preg_match("/^[\-]?\d+[\.]?\d*$/",$sValue)){ return true; } break;
		case 'mail': if (preg_match("/^[\da-z\_\-\.\+]+@[\da-z_\-\.]+\.[a-z]{2,5}$/i",$sValue)){ return true; } break;
		case 'login': if (preg_match("/^[\da-z\_\-]{".$iMin.','.$iMax."}$/i",$sValue)){ return true; } break;
		case 'md5': if (preg_match("/^[\da-z]{32}$/i",$sValue)){ return true; } break;
		case 'password': if (mb_strlen($sValue,'UTF-8')>=$iMin){ return true; } break;
		case 'text': if (mb_strlen($sValue,'UTF-8')>=$iMin and mb_strlen($sValue,'UTF-8')<=$iMax){ return true; } break;
		default:
			return false;
	}
	return false;
}
function func_build_cache_keys($array,$sBefore='',$sAfter='') {
	$aRes=array();
	foreach ($array as $key => $value) {
		$aRes[$value]=$sBefore.$value.$sAfter;
	}
	return $aRes;
}
function func_array_sort_by_keys($array,$aKeys) {
	$aResult=array();
	foreach ($aKeys as $iKey) {
		if (isset($array[$iKey])) {
			$aResult[$iKey]=$array[$iKey];
		}
	}
	return $aResult;
}
/**
 * Шифрование
 *
 * @param unknown_type $sData
 * @return unknown
 */
function func_encrypt($sData) {
	return md5($sData);
}
/**
 * Определяет IP адрес
 *
 * @return unknown
 */
function func_getIp() {
    return $_SERVER['REMOTE_ADDR'];
}
/**
 * Заменяет стандартную header('Location: *');
 *
 * @param unknown_type $sLocation
 */
function func_header_location($sLocation) {
	header("HTTP/1.1 301 Moved Permanently");
    header('Location: '.$sLocation);
    exit();
}
?>