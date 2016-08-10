<?php
/**
 * 外观设计模式的目标是： 控制外部错综复杂的关系， 并且提供简单的接口以利用上述组件的能力。
 * 为了隐藏复杂的，执行业务进程某个步骤所需的方法和逻辑组，就应当使用基于外观设计模式的类。
 *
 * 代码示例： 获取CD对象，对其所有属性应用大写形式，并且创建一个要提交给Web服务的，格式完整的XML文档。
 */
class CD {
	
	public $tracks = array();
	public $band   = '';
	public $title  = '';
	
	public function __construct($tracks, $band, $title) {
		$this->tracks = $tracks;
		$this->band   = $band;
		$this->title  = $title;
	}

}

class CDUpperCase {
	
	public static function makeString(CD $cd, $type) {
		$cd->$type = strtoupper($cd->$type);
	}
	
	public static function makeArray(CD $cd, $type) {
		$cd->$type = array_map("strtoupper", $cd->$type);
	}	
}

class CDMakeXML {
	
	public static function create(CD $cd) {
		$doc  = new DomDocument();
		
		$root = $doc->createElement("CD");
		$root = $doc->appendChild($root);
		
		$title = $doc->createElement("TITLE", $cd->title);
		$title = $root->appendChild($title);
		
		$band = $doc->createElement("BAND", $cd->band);
		$band = $root->appendChild($band);
		
		$tracks = $doc->createElement("TRACKS");
		$tracks = $root->appendChild($tracks);
		
		foreach ($cd->tracks as $track) {
			$track = $doc->createElement("TRACK", $track);
			$track = $tracks->appendChild($track);
		}
		
		return $doc->saveXML();
	}
}

class WebServiceFacade {
	
	public static function makeXMLCall(CD $cd) {
		CDUpperCase::makeString($cd, "title");
		CDUpperCase::makeString($cd, "band");
		CDUpperCase::makeArray($cd, "tracks");
		
		$xml = CDMakeXML::create($cd);
		
		return $xml;
	}
}

$tracksFromExternalSource = array("What It Means", "Brr", "Goodbye");
$band  = "Never Again";
$title = "Waster of a Rib";

$cd = new CD($tracksFromExternalSource, $band, $title);

$xml = WebServiceFacade::makeXMLCall($cd);

echo $xml;
