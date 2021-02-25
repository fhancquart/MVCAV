<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Model
{

  private $connBDD;

	public $username = "";
	public $password = "";

	public function __construct(){
      try {
            $this->connBDD = new PDO("mysql:host=".$this->config->config["database"]["hostname"].";dbname=".$this->config->config["database"]["database"], $this->config->config["database"]["username"], $this->config->config["database"]["password"], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
            exit;
        }
 	}

 	public function login(){
 		if(!empty($this->username) && !empty($this->password)){

      $sql = "SELECT username, password FROM login WHERE username = :username";

      $query = $this->connBDD->prepare($sql);
      $query->bindParam(':username', $this->username, PDO::PARAM_STR);
      $query->execute();

      $result = $query->fetchAll(PDO::FETCH_CLASS);

      if(sizeof($result) > 0){
        $result = $result[0];

        if(isset($result->password) && !empty($result->password)){
          if($result->password == hash("sha256", $this->password)){
            return true;
          } else {
            return false;
          }
        } else {
          return false;
        }
      } else {
        return false;
      }

 		} else {
      return false;
    }
 	}

}

?>