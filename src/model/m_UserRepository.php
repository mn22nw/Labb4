<?php

namespace model;

require_once('base/Repository.php');
require_once("src/helper/Misc.php");

class UserRepository extends \model\base\Repository {
	
	private static $key = 'uniqueKey';
	private static $user_id = 'user_id';
	private static $username = 'username';
	private static $password = 'password';
	private static $owner = 'userUnique';
	private $misc;

	public function __construct() {
		$this->dbTable = 'user_labb4';
		$this->misc = new \helper\Misc();
	}

	public function add(User $user){
	
		try {
			
			$db = $this->connection();
			$sql = "INSERT INTO $this->dbTable (".self::$user_id.",".self::$key.",".self::$username.", ".self::$password." ) VALUES (?,?,?,?)";   //?? för att slippa strängberoende
			
			$params = array('',$user->getUnique(), $user->getUsername(), $user->getPassword());
			
			$query = $db->prepare($sql);  //letar reda pa om du har ? namn inom parametrar osv, matchar mot array
			
			$query->execute($params);
			
			$this->misc->setAlert("Registrering av ny användare lyckades");
			
			$this->misc->setCreatedUsername($user->getUsername());
		}
		catch(\PDOExeption $e){  
			throw new \Exception($e);
		}
	}
	
	public function find($username, $password){ // is used in m_login.php
		 
		$db = $this->connection();
		$sql= "SELECT `".self::$username."` FROM $this->dbTable WHERE `".self::$username."` = '".$username. "' AND `".self::$password."`= '".$password. "'";			
		
		$query = $db->prepare($sql);  
		
		$query->execute();
		
		 if ($query->rowCount() > 0) {
        	return true;
	   	 } 

	    else {
	       throw new \Exception("Felaktigt användarnamn och/eller lösenord.");  
	    }
			
	}
	
	public function usernameAlreadyExists($username){
		
		try{
			$db = $this->connection();
			$sql = "SELECT * FROM $this->dbTable WHERE `" .self::$username . "` = ?";
			$params = array($username);
			$query = $db->prepare($sql);
			$query->execute($params);
			
			if ($query->rowCount() > 0) 
        		return true;

			return false;
		}
		catch(\PDOException $e){
			throw new \Exception($e->getMessage());   // TODO- handle exceptions  in UserRepository in a better way?
		}
	}
}
