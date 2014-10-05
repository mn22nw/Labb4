<?php
  session_start();
  require_once("src/view/HTMLView.php");
  require_once("src/controller/c_login.php");
  require_once("src/controller/c_register.php");
 
  error_reporting(0);
  
  $registerController = new \controller\Register();
  
  //if user pressed register then render register-view
  if($registerController->didUserPressRegister()){
	  $body =$registerController->viewPage();
  }
	
  else {
  	  $LoginController = new \controller\Login();
	  $body = $LoginController->viewPage();
  }
  
  	  $view = new \view\HTMLView();
	  $view->echoHTML("Laboration 4 - mn22nw", $body);
