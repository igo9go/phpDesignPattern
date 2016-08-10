<?php
/**
 * 工厂设计模式： 提供获取某个对象的新实例的一个接口， 同时使调用代码避免确定实际实例化基类的步骤
 */
//基础标准CD类
abstract class baseCD{
	protected $tracks = array();
	protected $band   = '';
	protected $title  = '';
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function setBand($band){
		$this->band = $band;
	}

	public function addTrack($track){
		$tmp_count = count($track);	
		$this->tracks[$tmp_count] = $track;
	}
	public function printTrack(){
		var_dump($this->tracks);
	}
}

class CD extends baseCD{
	public $type = 'cd';
}

//增强型CD类， 与标准CD的唯一不同是写至CD的第一个track是数据track("DATA TRACK")
class enhadcedCD extends baseCD{
	public $type = 'enhadced';
}

//CD工厂类，实现对以上两个类具体实例化操作
class CDFactory {
	
	public static function create($type) {
		$class = strtolower($type) . "CD";
		return new $class;
	}
}

//实例操作
$type = "enhadced";
$objCd   = CDFactory::create($type);
$tracksFromExternalSource = array("What It Means", "Brr", "Goodbye");

$objCd->setBand("Never Again");
$objCd->setTitle("Waste of a Rib");
foreach ($tracksFromExternalSource as $track) {
	$objCd->addTrack($track);
}
$objCd->printTrack();
