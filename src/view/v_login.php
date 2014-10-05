<?php
  namespace view;

  require_once("src/controller/c_login.php");
  require_once("src/helper/CookieStorage.php");
  require_once("src/helper/Misc.php");

  class Login {
    private $model;
    private $cookieStorage;
    private $misc;

    private static $getLogin  = "login";
    private static $getLogout = "logout";
	private static $uniqueID  = "Login::UniqueID";
	private static $loginBtn  = "Login:loginBtn";
	private static $rememberUser = "Login:Remember";
	private static $username = "Login::Username";
	private static $password = "Login::Password";


    public function __construct(\model\Login $model) {
      $this->model = $model;
      $this->cookieStorage = new \helper\CookieStorage();
      $this->misc = new \helper\Misc();
    }

    /**
      * A view for users not logged in
      *
      * @return string - The page log in page
      */
    public function showLogin() {
	  $username =  $this->misc->getCreatedUsername();
	 
	  if (empty($username))
	    $username = empty($_POST[self::$username]) ? '' : $_POST[self::$username];

      $ret = "<h2>Laborationskod mn22nw (mh222zr) </h2>
      <a href='?register'>Registrera ny användare</a>
      <h3>Ej inloggad.</h3>
 	  <p>Tips: Användarnamnet är <em>Admin</em> och lösenordet är <em>Password</em></p>";

      $ret .= "<span class='alert'>" . $this->misc->getAlert() . "</span>";

      $ret .= "
	  <form action='?" . self::$getLogin . "' method='post'>
	    <input type='text' name='". self::$username . "' placeholder='Användarnamn' value='".$username."' maxlength='30'>
	    <input type='password' name='". self::$password. "' placeholder='Lösenord' value='' maxlength='30'>
	    <label for='remember'>Håll mig inloggad:</label>
	    <input type='checkbox' id='". self::$rememberUser. "' name='". self::$rememberUser. "'>
	    <input type='submit' value='Logga in' name='". self::$loginBtn. "'>
	  </form>";

      return $ret;
    }

    /**
      * A view for users logged in
      *
      * @return string - The page log out page
      */
    public function showLogout() {   //TODO - REMOVE USE OF SESSION IN VIEW (not my code!! Had no time to fix this )
      // Get the username either from session or cookie
      if (isset($_SESSION[self::$username])) {
        $username = $_SESSION[self::$username];
      } else {
      // $username = $this->cookieStorage->getCookieValue(self::$username);  // TODO denna strular tillde
      }

      $ret = "<h2>" . $username . " är inloggad</h2>";
      $ret .= "<span class='alert'>" . $this->misc->getAlert() . "</span> "; // If there are any alerts, show them
      $ret .= "<a href='?" . self::$getLogout . "'>Logga ut</a>
      ";

      return $ret;
    }

    /**
      * Checks if user submitted the form
      *
      * @return boolval
      */
     
    public function LoginAttempt() {
    	
      if (isset($_GET[self::$getLogin])) {
      	
        if (isset($_POST[self::$loginBtn])) {
            return true;
        }
      }
	  
      return false;
    }

    public function LogoutAttempt() {
      if (isset($_GET[self::$getLogout]))
        return true;

      return false;
    }
	
	public function rememberUser(){
		if (isset($_POST[self::$rememberUser]))
        return true;

      return false;	
	}	
	
	public function getUsernameInput(){
		if($this->LoginAttempt()) {		
					//makes input safe to use in the code
			return $this->misc->makeSafe($_POST[self::$username]);
		}
	}
	
	public function getPasswordInput(){
		if($this->LoginAttempt()) {
			return $this->misc->makeSafe($_POST[self::$password]);
		}
	}		
	
	public function setCookies($postRemember) {
	 // Make the inputs safe to use in the code
     	$un = $this->getUsernameInput();   
    	$pw =  $this->getPasswordInput();
		 // If $postRemember got a value then set a cookie
        if ($postRemember) {
        
          $this->cookieStorage->save(self::$uniqueID, $_SESSION[self::$uniqueID], true);
          $this->cookieStorage->save(self::$username, $un);  
          $this->cookieStorage->save(self::$password, $this->misc->encryptString($pw));

          $this->misc->setAlert("Inloggning lyckades och vi kommer ihåg dig nästa gång");
        } 
	}
	
    public function checkCookies() {
    if ($this->cookieStorage->isCookieSet(self::$uniqueID)) {
        // Check if cookie is valid
        if ($this->cookieStorage->getCookieValue(self::$uniqueID) === $this->misc->setUniqueID() &&
          $this->cookieStorage->getCookieValue(self::$username) === $this->getUsernameInput() &&
          $this->cookieStorage->getCookieValue(self::$password) === $this->misc->encryptString($this->getPasswordInput())) {

          // Check if the uniqid cookie is valid
          if (!$this->cookieStorage->isCookieValid($this->cookieStorage->getCookieValue(self::$uniqueID))) {
            // Destroy all cookies
            $this->cookieStorage->destroy(self::$uniqueID);
            $this->cookieStorage->destroy(self::$username);
            $this->cookieStorage->destroy(self::$password);

            // Set an alert
            $this->misc->setAlert("Felaktig information i cookie.");
            return false;
          }

          // All valid and good? Then log em in
          $this->misc->setAlert("Inloggning lyckades via cookies.");
          return true;
        } else {
          // Destroy all cookies
          $this->cookieStorage->destroy(self::$uniqueID);
          $this->cookieStorage->destroy(self::$username);
          $this->cookieStorage->destroy(self::$password);
		  
          // Set an alert
          $this->misc->setAlert("Felaktig information i cookie.");
          return false;
        }
      } else {
        return false;
      }
     }

  }
