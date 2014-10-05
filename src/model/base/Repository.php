<?php 
namespace model\base;

abstract class Repository {  //DAO   Data access object
	protected $dbUsername = 'mianygren_nu'; 
	protected $dbPassword = 'miaaim123'; 
	protected $dbConnString = 'mysql:dbname=mianygren_nu;host=mianygren.nu.mysql';
	protected $dbConnection;
	protected $dbTable;
	
	protected function connection() {;
	try{	
		if ($this->dbConnection == NULL){

		$this->dbConnection = new \PDO($this->dbConnString, $this->dbUsername, $this->dbPassword);
	}
				
		$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		
		return $this->dbConnection; 
	}
	catch (\Exception $dbe) {
		throw new \Exception("Couldn't connect to Database");
	}
	}	
}
