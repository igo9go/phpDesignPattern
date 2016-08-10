<?php
/**
 * 迭代器：迭代器设计模式可帮助构造特定对象， 那些对象能够提供单一标准接口循环或迭代任何类型的可计数数据。
 * 处理需要遍历的可计数数据时， 最佳的解决办法是创建一个基于迭代器设计模式的对象。
 *
 */
class CD {
	
	public $band  = "";
	public $title = "";
	public $trackList = array();
	
	public function __construct($band, $title) {
		$this->band  = $band;
		$this->title = $title;
	}
	
	public function addTrack($track) {
		$this->trackList[] = $track;
	}
}

class CDSearchByBandIterator implements Iterator {
	
	private $_CDs   = array();
	private $_valid = FALSE;
	
	public function __construct($bandName) {
		$db = mysql_connect("localhost", "root", "root");
		mysql_select_db("test");
		
		$sql  = "select CD.id, CD.band, CD.title, tracks.tracknum, tracks.title as tracktitle ";
		$sql .= "from CD left join tracks on CD.id = tracks.cid ";
		$sql .= "where band = '" . mysql_real_escape_string($bandName) . "' ";
		$sql .= "order by tracks.tracknum";
		
		$results = mysql_query($sql);

		$cdID = 0;
		$cd   = NULL;
		
		while ($result = mysql_fetch_array($results)) {
			if ($result["id"] !== $cdID) {
				if ( ! is_null($cd)) {
					$this->_CDs[] = $cd;
				}
				
				$cdID = $result['id'];
				$cd   = new CD($result['band'], $result['title']);
			}
			
			$cd->addTrack($result['tracktitle']);
		}
		
		$this->_CDs[] = $cd;
	}
	
	public function next() {
		$this->_valid = (next($this->_CDs) === FALSE) ? FALSE : TRUE;
	}
	
	public function rewind() {
		$this->_valid = (reset($this->_CDs) === FALSE) ? FALSE : TRUE;
	}
	
	public function valid() {
		return $this->_valid;
	}
	
	public function current() {
		return current($this->_CDs);
	}
	
	public function key() {
		return key($this->_CDs);
	}
}

$queryItem = "Never Again";

$cds = new CDSearchByBandIterator($queryItem);

print "<h1>Found the Following CDs</h1>";
print "<table border='1'><tr><th>Band</th><th>Ttile</th><th>Num Tracks</th></tr>";
foreach ($cds as $cd) {
	print "<tr><td>{$cd->band}</td><td>{$cd->title}</td><td>";
	print count($cd->trackList). "</td></tr>";
}
print "</table>";
