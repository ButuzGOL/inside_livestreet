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
 * Модуль логирования
 * Имеет 3 уровня логирования: 'DEBUG','NOTICE','ERROR'
 *
 */
class ModuleLogger extends Module {
    /**
	 * Уровни логгирования
	 *
	 * @var array
	 */
	protected $logLevel=array('DEBUG','NOTICE','ERROR');
	/**
	 * Имя файла куда писать лог
	 *
	 * @var string
	 */
	protected $sFileName=null;
	/**
	 * Путь до каталога с логами
	 *
	 * @var string
	 */
	protected $sPathLogs=null;
    /**
	 * Текущий уровень логирования
	 *
	 * @var int
	 */
	protected $writeLevel=0;
    /**
	 * Использовать или нет трассировку
	 *
	 * @var bool
	 */
	protected $bUseTrace=false;
    /**
	 * Максимальный размер файла при ротации логов в байтах
	 *
	 * @var int
	 */
	protected $iSizeForRotate=1000000;
	/**
	 * Инициализация, устанавливает имя файла лога
	 *
	 */
	public function Init() {	
			$this->sPathLogs=Config::Get('path.root.server').'/logs/';		
			$this->SetFileName(Config::Get('sys.logs.file'));
	}
    /**
	 * Устанавливает имя файла лога
	 *
	 * @param string $sFile
	 */
	public function SetFileName($sFile){
		$this->sFileName=$sFile;
	}
    /**
	 * Получает имя файла лога
	 *
	 * @return string
	 */
	public function GetFileName(){
		return $this->sFileName;
	}
    /**
	 * Запись в лог с уровнем логирования 'ERROR'
	 *
	 * @param string $msg
	 */
	public function Error($msg) {
		$this->log($msg,'ERROR');
	}
    /**
	 * Записывает лог
	 *
	 * @param string $msg
	 * @param string $sLevel
	 */
	protected function log($msg,$sLevel) {
		/**
		 * Если уровень записи в лог больше либо равен текущему уровню то пишем в лог
		 */
		if (($key=array_search(strtoupper($sLevel),$this->logLevel))!==false and $key>=$this->writeLevel) {
			/**
			 * Формируем текст лога
			 */
			$msgOut ='['.date("Y-m-d H:i:s").']';
			$msgOut.='['.getmypid().']';
			$msgOut.='['.$this->logLevel[$key].']';
			$msgOut.='['.$msg.']';
			/**
			 * Если нужно то трассируем
			 */
			if ($this->getUseTrace()) {
				$msgOut.='['.$this->parserTrace(debug_backtrace()).']';
			}
			/**
			 * Записываем
			 */
			$this->write($msgOut);
		}
	}
    /**
	 * Использует трассировку или нет
	 *
	 * @return bool
	 */
	public function GetUseTrace() {
		return $this->bUseTrace;
	}
    /**
	 * Выполняет форматирование трассировки
	 *
	 * @param array $aTrace
	 * @return unknown
	 */
	protected function parserTrace($aTrace) {
		$msg='';
		for ($i=count($aTrace)-1;$i>=0;$i--) {
			if (isset($aTrace[$i]['class'])) {
				$funct=$aTrace[$i]['class'].$aTrace[$i]['type'].$aTrace[$i]['function'].'()';
			} else {
				$funct=$aTrace[$i]['function'].'()';
			}
			$msg.=$aTrace[$i]['file'].'(line:'.$aTrace[$i]['line'].'){'.$funct.'}';
			if ($i!=0) {
				$msg.=' => ';
			}
		}
		return $msg;
	}
    /**
	 * Производит сохранение в файл
	 *
	 * @param string $msg
	 * @return unknown
	 */
	protected function write($msg) {
		/**
		 * Если имя файла не задано то ничего не делаем
		 */
		if (is_null($this->sFileName) or $this->sFileName=='') {
			//throw new Exception("Empty file name for log!");
			return false;
		}
		/**
		 * Если имя файла равно '-' то выводим сообщение лога на экран(браузер)
		 */
		if ($this->sFileName=='-') {
			echo($msg."<br>\n");
		} else {
			/**
			 * Запись в файл
			 */
			if ($fp=fopen($this->sPathLogs.$this->sFileName,"a")) {
    			fwrite($fp,$msg."\n");
    			fclose($fp);
    			/**
    			 * Если нужно то делаем ротацию
    			 */
    			if ($this->bUseRotate) {
    				$this->rotate();
    			}
			}
		}
	}
    /**
	 * Производит ротацию логов
	 *
	 */
	protected function rotate() {
		clearstatcache();
		/**
		 * Если размер файло лога привысил максимальный то сохраняем текущий файл в архивный, а текущий становится пустым
		 */
		if (filesize($this->sPathLogs.$this->sFileName)>=$this->iSizeForRotate) {
			$pathinfo=pathinfo($this->sPathLogs.$this->getFileName());
			$name=$pathinfo['basename'];
			$aName=explode('.',$name);
			$i=1;
			while (1) {
				$sNameNew=$aName[0].".$i.".$aName[1];
				$sFullNameNew=$pathinfo['dirname'].'/'.$sNameNew;
				if (!file_exists($sFullNameNew)) {
					$this->rotateRename($i-1);
					break;
				}
				$i++;
			}
		}
	}
    /**
	 * Переименовывает все файлы логов согласно их последовательности
	 *
	 * @param int $numberLast
	 */
	protected function rotateRename($numberLast) {
		$pathinfo=pathinfo($this->sPathLogs.$this->getFileName());
		$aName=explode('.',$pathinfo['basename']);
		for ($i=$numberLast;$i>0;$i--) {
			$sFullNameCur=$pathinfo['dirname'].'/'.$aName[0].".$i.".$aName[1];
			$sFullNameNew=$pathinfo['dirname'].'/'.$aName[0].'.'.($i+1).'.'.$aName[1];
			rename($sFullNameCur,$sFullNameNew);
		}
		rename($this->sPathLogs.$this->getFileName(),$pathinfo['dirname'].'/'.$aName[0].".1.".$aName[1]);
	}
    /**
	 * Запись в лог с уровнем логирования 'DEBUG'
	 *
	 * @param string $msg
	 */
	public function Debug($msg) {
		$this->log($msg,'DEBUG');
	}
}
?>