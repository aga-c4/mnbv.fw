<?php
/**
 * SysLogs.class.php Обработчик логов работы системы
 *
 * Created by Konstantin Khachaturyan (aga-c4)
 * @author Konstantin Khachaturyan (AGA-C4)
 * Date: 09.04.15
 * Time: 00:00
 */

/**
 * Class SysLogs работа с логами системы
 */
class SysLogs {

    /**
     * @var string Лог работы скрипта
     */
    protected static $log = '';

    /**
     * @var boolean Определяет будет ли вообще накапливаться информация по логу. Не влияет на настройку вывода лога в консоль. Важно для длительно работающих циклических скриптов, где это может сожрать всю память
     */
    public static $logsEnable = true;

    /**
     * @var boolean Определяет будет ли вообще накапливаться информация по ошибкам. Не влияет на настройку вывода лога в консоль. Важно для длительно работающих циклических скриптов, где это может сожрать всю память
     */
    public static $errorsEnable = true;

    /**
     * @var boolean Перед каждой записью выводить дату-время
     */
    public static $logViewTime = false;

    /**
     * @var boolean Перед каждой записью выводить контроллер
     */
    public static $logViewController = false;

    /**
     * @var boolean Видимость Лога работы скрипта
     */
    public static $logView = false;

    /**
     * @var bool Выводить сообщения непосредственно при их формировании
     */
    public static $logRTView = false;

    /**
     * @var bool Сохранять в файл сообщения непосредственно при их формировании
     */
    public static $logRTSave = false;

    /**
     * @var bool Маркер пересоздания файла при первой записи
     */
    public static $logRTSaveFirst = true;

    /**
     * @var boolean Сохранение Лога работы скрипта
     */
    public static $logSave = false;

    /**
     * @var type полное имя файла лога, куда будет писаться лог, если это задано. устанавливается и создается командой self::setLogFile($filename)
     */
    public static $logFile = '';
    
    /**
     * @var string Ошибки работы скрипта
     */
    protected static $errors = '';

    /**
     * @var boolean Видимость Ошибок работы скрипта
     */
    public static $errorsView = false;
    
    /**
     * @var boolean Если true, то в лог выгрузили финальные записи о времени исполнения скрипта и т.п. (обычно проиходит в шаблоне MNBVf::putDBStatToLog();) 
     */
    public static $logComplete = false;
    
    /**
     * Устанавливает файл лога, куда будет происходить запись + создание файла
     * @param type $filename
     */
    public static function setLogFile($filename){
        if (empty($filename)) return false;
        self::$logFile = $filename;
        return true;
    }

    /**
     * Добавляет сообщение об ошибке в Лог ошибок и общий Лог
     * @param string $errorMessage
     * @param string $viewMem если 'mem' - показывать текущие данные по памяти
     */
    public static function addError($errorMessage,$viewMem=""){
        $dopStr = ((self::$logViewTime)?(date("Y-m-d G:i:s").'   '):'') . ((self::$logViewController && !empty(Glob::$vars['controller']))?(Glob::$vars['controller']. '   '):'');
        if (strlen(self::$log)>100000 || strlen(self::$errors)>100000) self::clearOutErrLog();
        if (self::$errorsEnable) self::$errors .= $dopStr . $errorMessage . "\n";
        if (self::$logsEnable) self::$log .= $dopStr . $errorMessage . "\n";
        if (self::$logRTView) echo $dopStr . $errorMessage . "\n"; //При необходимости тут же выводим
        if (self::$logRTSave) self::addToLogFile($dopStr.$errorMessage, $viewMem);
    }
	
    /**
     * Добавляет сообщение в Лог выполнения скрипта
     * @param string $errorMessage
     * @param string $viewMem если 'mem' - показывать текущие данные по памяти
     */
    public static function addLog($logMessage,$viewMem=""){
        $dopStr = ((self::$logViewTime)?(date("Y-m-d G:i:s").'   '):'') . ((self::$logViewController && !empty(Glob::$vars['controller']))?(Glob::$vars['controller']. '   '):'');
        if (strlen(self::$log)>100000) self::clearOutLog();
        if (self::$logsEnable) self::$log .= $dopStr . $logMessage . "\n";
        if (self::$logRTView && self::$logView) echo $dopStr . $logMessage . "\n"; //При необходимости тут же выводим
        if (self::$logRTSave) self::addToLogFile($dopStr.$logMessage, $viewMem);
    }
    
    /**
     * Добавляет запись в лог файл
     * @param string $logStr Строка лога
     * @param string $viewMem если 'mem' - показывать текущие данные по памяти
     * @return bool
     */
    public static function addToLogFile($logStr='',$viewMem=""){
        $logFileSave = APP_LOGSPATH.'Syslog'.date("Y-m-d-G-i-s").'.txt';
        if (self::$logFile) $logFileSave = self::$logFile;

        $memory_max_usage = number_format((memory_get_peak_usage() / (1024 * 1024)), 3);
        $memory_usage = number_format((memory_get_usage() / (1024 * 1024)), 3);
        
        if (self::$logRTSaveFirst) {$mode = "w"; self::$logRTSaveFirst = false;}
        else $mode = "a";
        $res=SysBF::saveFile($logFileSave, $logStr.(($viewMem==='mem')?" [MemUse: $memory_usage Mb][MemMaxUse: $memory_max_usage Mb]":'')."\n", $mode);
        if ($res){return true;}
        else {return false;}
    }

    /**
     * Оставляет в логе последние записи (сколько задано). По-умолчанию 100000 символов.
     * @param int $maxStr допустимое количество записей в логе
     */
    public static function clearOutLog($maxStr=100000){
        self::$log = substr(self::$log,(-1*$maxStr));
    }

    /**
     * Оставляет в логе ошибок последние записи (сколько задано). По-умолчанию 100000 символов.
     * @param int $maxStr допустимое количество записей в логе
     */
    public static function clearOutErrLog($maxStr=100000){
        self::$errors = substr(self::$errors,(-1*$maxStr));
        self::$log = substr(self::$log,(-1*$maxStr));
    }

    /**
     * Возвращает лог ошибок
     * @return array
     */
    public static function getErrors(){
        return (self::$errorsView)?self::$errors:'';
    }

    /**
     * Возвращает Лог выполнения скрипта
     * @return array
     */
    public static function getLog(){
        return (self::$logView)?self::$log:'';
    }
	
    /**
     * Сохранение Лога выполнения скрипта
     */
    public static function SaveLog(){
        if (self::$logSave){
            
            $logFileSave = APP_LOGSPATH.'Syslog'.date("Y-m-d-G-i-s").'.txt';
            if (self::$logFile) $logFileSave = self::$logFile;
        
            //Сформируем окончательный лог для сохранения
            $logTxt = "-=[ SysLog ".date("Y-m-d G:i:s")." ]=-\n";
            if (self::$errorsView && self::getErrors()!=''){$logTxt .= "ERRORS:\n" . self::getErrors() . "-------\n\n";}
            if (self::$logView && self::getLog()!=''){$logTxt .= "LOG:\n" . self::getLog() . "-------\n\n";}

            //Сохраним лог на диске
            SysBF::saveFile($logFileSave,$logTxt);
            
        }
    }

}
