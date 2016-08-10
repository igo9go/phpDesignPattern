<?php 
/**
 * 1. 观察者：能够更便利地创建查看目标对象状态的对象，并且提供与核心对象非耦合的指定功能性
 * 2. 在创建其核心功能可能包含可观察状态变化的对象时候，最佳的做法是基于观察者设计模式创建于目标对象
 *    进行交互的其他类
 * 3. 常见的观察者设计模式示例有：插件系统，RSS源缓存器构建
 *
 */
//被观察者： 基础CD类
class CD {
	
	public $title = "";
	public $band  = "";
	protected $_observers = array();   // 观察者对象数组
	
	public function __construct($title, $band) {
		$this->title = $title;
		$this->band  = $band;
	}
	
	public function attachObserver($type, $observer) {
		$this->_observers[$type][] = $observer;
	}
	
	public function notifyObserver($type) {
		if (isset($this->_observers[$type])) {
			foreach ($this->_observers[$type] as $observer) {
				$observer->update($this);
			}
		}
	}
	
	public function buy() {
		$this->notifyObserver("purchased");
	}
}

//观察者类 后台处理
class buyCDNotifyStreamObserver {
	public function update(CD $cd) {
		$activity  = "The CD named {$cd->title} by ";
		$activity .= "{$cd->band} was just purchased.";
		activityStream::addNewItem($activity);
	}
}

//消息同事类 前台输出
class activityStream {
	static public function addNewItem($item) {
		print $item;
	}
}

//测试实例
$title = "Waste of a Rib";
$band  = "Never Again";

$cd    = new CD($title, $band);
$observer = new buyCDNotifyStreamObserver();

$cd->attachObserver("purchased", $observer);
$cd->buy();
