<?php
/** 
 * 代理： 构建了透明置于两个不同对象之内的一个对象，从而能够截取或代理这两个对象间的通信或访问
 * 在需要截取两个对象之间的通信时，最佳的做法是使用一个基于代理设计模式的新对象
 * 
 */
//基础CD类
class CD {
	
	protected $_title  = "";
	protected $_band   = "";
	protected $_handle = NULL;
	
	public function __construct($title, $band) {
		$this->_title = $title;
		$this->_band  = $band;
	}
	
	public function buy() {
		$this->_connect();
		
		$query  = "update cd set bought = 1 where band = '";
		$query .= mysql_real_escape_string($this->_band, $this->_handle);
		$query .= " ' and title = '";
		$query .= mysql_real_escape_string($this->_title, $this->_handle);
		$query .= "'";
		
		mysql_query($query, $this->_handle);
		
		//var_dump("success");
	}
	
	protected function _connect() {
		$this->_handle = mysql_connect("localhost", "root", "root");
		mysql_select_db("test", $this->_handle);
	}
}

//现在我们需要访问位于德克萨斯州达拉斯某处的数据， 这就要求一个具有访问性能的Proxy对象， 
//该对象需要截取与本地数据库的链接, 转而链接到达拉斯网络运营中心。

//代理类
class DallasNoCCDProxy extends CD {
	protected function _connect() {
		$this->_handle = mysql_connect("dallas", "user", "pass");
		mysql_select_db("test", $this->_handle);
	}
}

//测试实例
$enternalTitle = "Waster of a Rib";
$externalBand  = "Neve Again";

$cd = new CD($enternalTitle, $externalBand);
$cd->buy();

$dallasCD = new DallasNoCCDProxy($enternalTitle, $externalBand);
$dallasCD->buy();
