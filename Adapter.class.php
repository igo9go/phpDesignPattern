<?php
/**
 * 适配器模式
 * 解决问题：在转换一个对象的接口用于另一个对象时，实现Adapter对象不仅是最佳做法，而且也能减少很多麻烦
 */
class errorObject
{
    private $__error;
    public function __construct($error)
    {
        $this->__error = $error;
    }
    public function getError()
    {
        return $this->__error;
    }
}

//原需求
class logToConsole
{
    private $__errorObject;
    public function __construct($errorObject)
    {
        $this->__errorObject = $errorObject;
    }

    public function write()
    {
        fwrite(STDERR, $this->__errorObject->getError());
    }
}

//新需求改变了
class logToCSV
{
    const CSV_LOCATION = "log.csv";
    private $__errorObject;
    public function __construct($errorObject)
    {
        $this->__errorObject = $errorObject;
    }

    public function write()
    {
        $line = $this->__errorObject->getErrorNumber();
        $line .= ",";
        $line .= $this->__errorObject->getErrorText();
        $Line .= "\n";
        file_put_contents(self::CSV_LOCATION, $line, FILE_APPEND);
    }
}

//增加适配器，保持公共接口的标准性
class logToCsvAdapter extends errorObject
{
    private $__errorNumber, $__errorText;
    public function __construct($error)
    {
        parent::__construct($error);
        $parts = explode(":", $this->getError());
        $this->__errorNumber = $parts[0];
        $this->__errorText   = $parts[1];
    }

    public function getErrorNumber()
    {
        return $this->__errorNumber;
    }

    public function getErrorText()
    {
        return $this->__errorText;
    }
}

//$error = new errorObject("404:Not Found");
//$log = new logToConsole($error);
//使用适配器来更新接口
$error = new logToCsvAdapter("404:Not Found");
$log = logToCSV($error);
$log->write();

?>
