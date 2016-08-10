<?php
/**
 * 原型：原型设计模式创建对象的方式是复制和克隆初始对象或原型，这种方式比创建新实例更为有效。
 *
 */
//初始CD类
class CD {
	
	public $title = "";
	public $band  = "";
	public $trackList = array();
	public function __construct($id) {
		$handle = mysql_connect("localhost", "root", "root");
		mysql_select_db("test", $handle);
		
		$query  = "select * from cd where id = {$id}";
		$results= mysql_query($query, $handle);
		
		if ($row = mysql_fetch_assoc($results)) {
			$this->band  = $row["band"];
			$this->title = $row["title"];
		}
	}
	
	public function buy() {
		var_dump($this);
	}
}

//采用原型设计模式的混合CD类, 利用PHP的克隆能力。
class MixtapeCD extends CD {
	public function __clone() {
		$this->title = "Mixtape";
	}
}

//示例测试
$externalPurchaseInfoBandID = 1;
$bandMixproto = new MixtapeCD($externalPurchaseInfoBandID);

$externalPurchaseInfo   = array();
$externalPurchaseInfo[] = array("brrr", "goodbye");
$externalPurchaseInfo[] = array("what it means", "brrr");

//因为使用克隆技术， 所以每个新的循环都不需要针对数据库的新查询。
foreach ($externalPurchaseInfo as $mixed) {
	$cd = clone $bandMixproto;
	$cd->trackList = $mixed;
	$cd->buy();
}
