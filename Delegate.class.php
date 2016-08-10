<?php
/**
 * 转自 《PHP设计模式》 第七章： 委托模式
 * 当一个对象包含复杂单独立的，必须基于判决执行的功能性的若干部分时，最佳的方法是适用基于委托设计模式的对象。 
 *
 * 示例： Web站点具有创建MP3文件播放列表的功能， 也具有选择以 M3U 或 PLS 格式下载播放列表的功能。
 * 以下代码示例展示常规与委托两种模式实现
 */
//常规实现 
class Playlist {
	
	private $_songs; 
	
	public function __construct() {
		$this->_songs = array();
	}
	
	public function addSong($location, $title) {
		$song = array("location" => $location, "title" => $title);
		$this->_songs[] = $song;
	}
	
	public function getM3U() {
		$m3u = "#EXTM3U\n\n";
		
		foreach ($this->_songs as $song) {
			$m3u .= "#EXTINF: -1, {$song['title']}\n";
			$m3u .= "{$song['location']}\n";
		}
		
		return $m3u;
	}
	
	public function getPLS() {
		$pls = "[playlist]]\nNumberOfEntries = ". count($this->_songs) . "\n\n";
		
		foreach ($this->_songs as $songCount => $song) {
			$counter = $songCount + 1;
			$pls .= "File{$counter} = {$song['location']}\n";
			$pls .= "Title{$counter} = {$song['title']}\n";
			$pls .= "LengthP{$counter} = -1 \n\n";
		}
		
		return $pls;
	}
}

$playlist = new Playlist();

$playlist->addSong("/home/aaron/music/brr.mp3", "Brr");
$playlist->addSong("/home/aaron/music/goodbye.mp3", "Goodbye");

$externalRetrievedType = "pls";

if ($externalRetrievedType == "pls") {
	$playlistContent =  $playlist->getPLS();
} else {
	$playlistContent =  $playlist->getM3U();
}

echo $playlistContent;

//委托模式实现 
class newPlaylist {

	private $_songs;
	private $_tyepObject;

	public function __construct($type) {
		$this->_songs = array();
		$object = "{$type}Playlist";
		$this->_tyepObject = new $object;
	}	
	
	public function addSong($location, $title) {
		$song = array("location" => $location, "title" => $title);
		$this->_songs[] = $song;
	}
	
	public function getPlaylist() {
		$playlist = $this->_tyepObject->getPlaylist($this->_songs);
		return $playlist;
	}
}

class m3uPlaylist {
	public function getPlaylist($songs) {
		$m3u = "#EXTM3U\n\n";
		
		foreach ($songs as $song) {
			$m3u .= "#EXTINF: -1, {$song['title']}\n";
			$m3u .= "{$song['location']}\n";
		}
		
		return $m3u;
	}	
}

class plsPlaylist {
	public function getPlaylist($songs) {
		$pls = "[playlist]]\nNumberOfEntries = ". count($songs) . "\n\n";
		
		foreach ($songs as $songCount => $song) {
			$counter = $songCount + 1;
			$pls .= "File{$counter} = {$song['location']}\n";
			$pls .= "Title{$counter} = {$song['title']}\n";
			$pls .= "LengthP{$counter} = -1 \n\n";
		}
		
		return $pls;
	}
}

$externalRetrievedType = "pls";
$playlist = new newPlaylist($externalRetrievedType);

$playlist->addSong("/home/aaron/music/brr.mp3", "Brr");
$playlist->addSong("/home/aaron/music/goodbye.mp3", "Goodbye");

$playlistContent = $playlist->getPlaylist();

echo $playlistContent;
