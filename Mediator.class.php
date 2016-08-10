<?php
/** 
 * 中介者: 中介者设计莫用于开发一个对象，这个对象能够在类似对象相互之间不直接交互的情况下传送或调节对这些对象的集合的修改 
 * 处理具有类似属性并且属性需要保持同步的非耦合对象时，最佳的做法是使用基于中介者设计模式的对象。
 *
 * 测试用例描述：示例代码不仅允许乐队进入和管理他们的音乐合集，而且还允许乐队更新他们的配置文件，修改乐队相关信息以及更新其CD信息
 *　　　　　　　 现在，艺术家可上传MP3集合并从Web站点撤下CD。 因此， Web站点需要保持相对应的CD和MP3彼此同步。
 */
//CD类
class CD {
	
	public $band  = '';
	public $title = '';
	protected $_mediator;
	
	public function __construct(MusicContainerMediator $mediator = NULL) {
		$this->_mediator = $mediator;
	}
	
	public function save() {
		//具体实现待定
		var_dump($this);
	}
	
	public function changeBandName($bandname) {
		if ( ! is_null($this->_mediator)) {
			$this->_mediator->change($this, array("band" => $bandname));
		}
		$this->band = $bandname;
		$this->save();
	}
}

//MP3Archive类
class MP3Archive {
	
	protected $_mediator;
	
	public function __construct(MusicContainerMediator $mediator = NULL) {
		$this->_mediator = $mediator;
	}
	
	public function save() {
		//具体实现待定
		var_dump($this);
	}
	
	public function changeBandName($bandname) {
		if ( ! is_null($this->_mediator)) {
			$this->_mediator->change($this, array("band" => $bandname));
		}
		$this->band = $bandname;
		$this->save();
	}
}

//中介者类
class MusicContainerMediator {
	
	protected $_containers = array();
	
	public function __construct() {
		$this->_containers[] = "CD";
		$this->_containers[] = "MP3Archive";
	}
	
	public function change($originalObject, $newValue) {
		$title = $originalObject->title;
		$band  = $originalObject->band;
		
		foreach ($this->_containers as $container) {
			if ( ! ($originalObject instanceof $container)) {
				$object = new $container;
				$object->title = $title;
				$object->band  = $band;
				
				foreach ($newValue as $key => $val) {
					$object->$key = $val;
				}
				
				$object->save();
			}
		}
	}
}

//测试实例
$titleFromDB = "Waste of a Rib";
$bandFromDB  = "Never Again";

$mediator = new MusicContainerMediator();

$cd = new CD($mediator);
$cd->title = $titleFromDB;
$cd->band  = $bandFromDB;

$cd->changeBandName("Maybe Once More");
