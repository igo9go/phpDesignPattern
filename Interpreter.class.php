<?php
/**
 * 解释器： 解释器设计模式用于分析一个实体的关键元素，并且针对每个元素都提供自己的解释或相应的动作。
 * 解释器设计模式最常用于PHP/HTML 模板系统。
 */
class User {
	
	protected $_username = "";
	
	public function __construct($username) {
		$this->_username = $username;
	}
	
	public function getProfilePage() {
		$profile  = "\n<h2>I like Never Again ! </h2>\n";
		$profile .= "I love all of their songs. \nMy favorite CD: \n";
		$profile .= "{{myCD.getTitle}}!!\n";
		
		return $profile;
	}
}

class userCD {
	
	protected $_user = NULL;
	
	public function setUser(User $user) {
		$this->_user = $user;
	}
	
	public function getTitle() {
		$title = "Waste of a Rib";
		
		return $title;
	}
}

class userCDInterpreter {
	
	protected $_user = NULL;
	
	public function setUser(User $user) {
		$this->_user = $user;
	}
	
	public function getInterpreted() {
		$profile = $this->_user->getProfilePage();
		
		if (preg_match_all('/\{\{myCD\.(.*?)\}\}/', $profile, $triggers, PREG_SET_ORDER)) {
			$replacements = array();
			
			foreach ($triggers as $trigger) {
				$replacements[] = $trigger[1];
			}
			
			$replacements = array_unique($replacements);
			
			$myCD = new userCD();
			$myCD->setUser($this->_user);
			
			foreach ($replacements as $replacement) {
				$profile = str_replace("{{myCD.{$replacement}}}", call_user_func(array($myCD, $replacement)), $profile);
			}
		}
		
		return $profile;
	}
	
}

$username = "aaron";
$user = new User($username);
$interpreter = new userCDInterpreter();
$interpreter->setUser($user);

print "<h1>{$username}'s Profile</h1>";
print $interpreter->getInterpreted();
