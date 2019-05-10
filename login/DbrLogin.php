<?php
require_once("../utils/DB.php");
class DbrLogin
{
    private $db;
    public function __construct(){
        try {
            $this->db = new DB();
        } catch(Exception $e){
            #TO DO
            echo "ERROR : ",$e->getMessage(),"\n";
        }
    }
    
    public function authenticate($username, $password){
        $responseCode = 401;
        $enPassword = $this->encrptPass($password);
        try{
            $query = "SELECT username,role,last_login from login WHERE username=:username AND password=:enPassword";
            if(!$this->db){
                throw new Exception("DB error"); 
            }
            $data = $this->db->selectQuery($query,array(':username'=>$username,':enPassword'=>$enPassword));
            if ($data) {
                $key = True;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $key));
                $this->setSession($token,$username,$data[0]['role'],$data[0]['last_login']);
                $responseCode = 200;
            }
        } catch(Exception $e){
            #TO DO
            echo "ERROR : ",$e->getMessage(),"\n";
            $responseCode = 500;
        }
        return $responseCode;
    }

    private function encrptPass($password){
        return hash('sha256', $password);
    }

    private function setSession($token,$username,$role,$lastLogin){
        #TO DO : for different roles
        $query = "SELECT * FROM user where username=:username";
        $data = $this->db->selectQuery($query,array(':username'=>$username));
        $_SESSION['role'] = $role;
        $_SESSION['username'] = $username;
        $_SESSION['secToken'] = $token;
        $_SESSION['userId'] = $data[0]['user_id'];
        $_SESSION['last_login'] = $lastLogin;
    }
}

?>