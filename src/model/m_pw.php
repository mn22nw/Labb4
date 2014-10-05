<?php

namespace model;

require_once("src/helper/Misc.php");

class Password {

	const minPwLen = 6;
	private $pw;
	private $misc;

	public function __construct($password) {
		$this->misc = new \helper\Misc();
			
		if ($this->validatePw($password)) {
			$this->pw = $password;
		}
		
	}
	
	public function getHashedPassword(){
		return $this->misc->encryptString($this->pw);
	}
	
	public function validatePw($password) {
		
		if (mb_strlen($password) < self::minPwLen) {
			$this->misc->setAlert("Lösenordet har för få tecken. Minst 6 tecken.");  //medveten om ev. strängberoende men ville ha validering även här.
			throw new \Exception();  
		}
		
		return true;
	}
}