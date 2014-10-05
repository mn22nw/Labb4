<?php
  namespace controller;

  require_once("src/model/m_login.php");
  require_once("src/view/v_login.php");
  require_once("src/view/v_register.php");
  require_once("src/helper/Misc.php");

// TODO - vid omladdning av sidan efter logout blir det massa error ?? bara IBLAND


  class Login {
    private $model;
    private $view;
    private $misc;
	private static $username = "Login::Username"; //TODO remove!

    public function __construct() {
      $this->model = new \model\Login();
      $this->view = new \view\Login($this->model);
      $this->misc = new \helper\Misc();
    }

    public function viewPage() {
    	
      // Check if user is logged in with session or with cookies
      if ($this->model->userIsLoggedIn() || $this->view->checkCookies()) {

        // Check if user pressed log out
        if ($this->view->LogoutAttempt()) {
          // Then log out
          if ($this->model->logOut()) {
            // And then present the login page
            return $this->view->showLogin();
          }
        }

      // Logged in and did not press log out, then show the logout page
      return $this->view->showLogout();
      } 
      
      else {
        // Check if the user did press login
        if ($this->view->LoginAttempt()) {  
          
          //CHECK IN MODEL IF LOGIN IS CORRECT AND SET A SESSION
         if ($this->model->logIn($this->view->getUsernameInput(), $this->view->getPasswordInput(), $this->view->rememberUser())) {
				
			
			// error here might be that session always sets userSession?
			
			// Sets cookies if user wants to be remembered    // 
			$this->view->setCookies($this->view->rememberUser());
			
          // Then show the logout page  
          return $this->view->showLogout();
		 }
		 
        // Else show the login page
        return $this->view->showLogin();
      }
    }
	  return $this->view->showLogin(); 
  }
 }