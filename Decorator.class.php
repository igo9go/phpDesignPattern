<?php
/**
 *
 * 装饰器设计模式适用于下列工作场合： 需求变化是快速和细小的，而且几乎不影响应用程序的其他部分
 * 使用装饰器设计模式设计类的目标是： 不必重写任何已有的功能性代码，而是对某个基于对象应用增量变化。
 * 装饰器设计模式采用这样的构建方式： 在主代码流中应该能够直接插入一个或多个更改或“装饰”目标对象的装饰器，同时不影响其他代码流。
 *
 */
class CD {
	public $trackList;
	
	public function __construct() {
		$this->trackList = array();
	}
	
	public function addTrack($track) {
		$this->trackList[] = $track;
	}
	
	public function getTrackList() {
		$output = '';
		
		foreach ($this->trackList as $num => $track) {
			$output .= ($num + 1) . ") {$track}.";
		}
		
		return $output;
	}
}

$tracksFroExternalSource = array("What It Means", "Brr", "Goodbye");

$myCD = new CD();
foreach ($tracksFroExternalSource as $track) {
	$myCD->addTrack($track);
}

print "The CD contains:{$myCD->getTrackList()}\n";

/**
 * 需求发生小变化： 要求每个输出的参数都采用大写形式. 对于这么小的变化而言， 最佳的做法并非修改基类或创建父 - 子关系， 
 *                  而是创建一个基于装饰器设计模式的对象。 
 *
 */
class CDTrackListDecoratorCaps {
	private $_cd;
	
	public function __construct(CD $cd) {
		$this->_cd = $cd;
	}
	
	public function makeCaps() {
		foreach ($this->_cd->trackList as & $track) {
			$track = strtoupper($track);
		}
	}
}

$myCD = new CD();
foreach ($tracksFroExternalSource as $track) {
	$myCD->addTrack($track);
}

//新增以下代码实现输出参数采用大写形式
$myCDCaps = new CDTrackListDecoratorCaps($myCD);
$myCDCaps->makeCaps();

print "The CD contains:{$myCD->getTrackList()}\n";
