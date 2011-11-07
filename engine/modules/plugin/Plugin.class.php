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
 * Модуль управления плагинами сообщений
 *
 */
class ModulePlugin extends Module {
	
	/**
	 * Путь к директории с плагинами
	 * 
	 * @var string
	 */
	protected $sPluginsDir;
    /**
	 * Список engine-rewrite`ов (модули, экшены, сущности, шаблоны)
	 *
	 * @var array
	 */
	protected $aDelegates=array(
		'module' => array(),
		'mapper' => array(),
		'action' => array(),
		'entity' => array(),
		'template' => array()
	);
    /**
	 * Стек наследований
	 *
	 * @var array
	 */
	protected $aInherits=array();
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
		$this->sPluginsDir=Config::Get('path.root.server').'/plugins/';
	}
    /**
	 * Возвращает делегат модуля, экшена, сущности.
	 * Если делегат не определен, пытается найти наследника, иначе отдает переданный в качестве sender`a параметр
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @return string
	 */
	public function GetDelegate($sType,$sFrom) {
        if (isset($this->aDelegates[$sType][$sFrom]['delegate'])) {
			return $this->aDelegates[$sType][$sFrom]['delegate'];
		} elseif ($aInherit=$this->GetLastInherit($sFrom)) {
			return $aInherit['inherit'];
		}
        return $sFrom;
	}
    public function GetLastInherit($sFrom) {
		if (isset($this->aInherits[trim($sFrom)])) {
			return $this->aInherits[trim($sFrom)]['items'][count($this->aInherits[trim($sFrom)]['items'])-1];
		}
        return null;
	}
    /**
	 * Возвращает true, если устано
	 *
	 * @param  string $sType
	 * @param  string $sTo
	 * @return bool
	 */
	public function isDelegated($sType,$sTo) {
		/**
		 * Фильтруем меппер делегатов/наследников
		 * @var array
		 */
		$aDelegateMapper=array_filter(
			$this->aDelegates[$sType],
			create_function('$item','return $item["delegate"]=="'.$sTo.'";')
		);
		if (is_array($aDelegateMapper) and count($aDelegateMapper))	{
			return true;
		}
		foreach ($this->aInherits as $k=>$v) {
			$aInheritMapper=array_filter(
				$v['items'],
				create_function('$item','return $item["inherit"]=="'.$sTo.'";')
			);
			if (is_array($aInheritMapper) and count($aInheritMapper))	{
				return true;
			}
		}
		return false;
	}
    /**
	 * Возвращает делегирующий объект по имени делегата
	 *
	 * @param  string $sType Объект
	 * @param  string $sTo   Делегат
	 * @return string
	 */
	public function GetDelegater($sType,$sTo) {
		$aDelegateMapper=array_filter(
			$this->aDelegates[$sType],
			create_function('$item','return $item["delegate"]=="'.$sTo.'";')
		);
		if (is_array($aDelegateMapper) and count($aDelegateMapper))	{
			return array_shift(array_keys($aDelegateMapper));
		}
		foreach ($this->aInherits as $k=>$v) {
			$aInheritMapper=array_filter(
				$v['items'],
				create_function('$item','return $item["inherit"]=="'.$sTo.'";')
			);
			if (is_array($aInheritMapper) and count($aInheritMapper))	{
				return $k;
			}
		}
		return $sTo;
	}
    /**
	 * Возвращает подпись делегата модуля, экшена, сущности.
	 *
	 * @param  string $sType
	 * @param  string $sFrom
	 * @return string|null
	 */
	public function GetDelegateSign($sType,$sFrom) {
		if (isset($this->aDelegates[$sType][$sFrom]['sign'])) {
			return $this->aDelegates[$sType][$sFrom]['sign'];
		}
		if ($aInherit=$this->GetLastInherit($sFrom)) {
			return $aInherit['sign'];
		}
		return null;
	}
}
?>