<?php
/**
 * 策略模式： 帮助构建的对象不必自身包含逻辑，而是根据需要利用其它对象中的算法
 *
 * 灵活的让对象生成 XML 或 JSON 字符串输出。
 * 
 */
//基准的 Strategy 对象类
class CDusersStrategy {
	
	public $title = "";
	public $band  = "";
	
	protected $_strategy;
	
	public function __construct($title, $band) {
		$this->title = $title;
		$this->band  = $band;
	}
	
	public function setStrategyContext($strategyObject) {
		$this->_strategy = $strategyObject;
	}
	
	public function get() {
		return $this->_strategy->get($this);
	}
	
}

//创建用于 XML 的 Strategy 对象类
class CDAsXMLStrategy {
	
	public function get(CDusersStrategy $cd) {
		$doc  = new DomDocument();
		$root = $doc->createElement("CD");
		$root = $doc->appendChild($root);
		
		$title = $doc->createElement("TTILE", $cd->title);
		$title = $root->appendChild($title);
		
		$band = $doc->createElement("BAND", $cd->band);
		$band = $root->appendChild($band);
		
		return $doc->saveXML();
	}
}

//创建用于 JSON 的 Strategy 对象类 
class CDAsJSONStrategy {
	
	public function get(CDusersStrategy $cd) {
		$json = array();
		$json["CD"]["title"] = $cd->title;
		$json["CD"]["band"]  = $cd->band;
		
		return json_encode($json);
	}
}

//创建其它格式输出的 Strategy 对象类
class CDAsOtherStrategy {
	
	public function get(CDusersStrategy $cd) {
		//依据实际情况编写代码
	}
}

//测试实例
$externalTitle = "Never Again";
$externalBand  = "Waste of a Rib";

$cd = new CDusersStrategy($externalTitle, $externalBand);

//XML output
$cd->setStrategyContext(new CDAsXMLStrategy());
print $cd->get();

//JSON output
$cd->setStrategyContext(new CDAsJSONStrategy());
print $cd->get();
