<?php
class Page extends CI_Model
{
    var $id= "";
    var $url = "";
    var $title = "";
    var $description = "";
    var $domain = "";
    var $path = "";
    var $view = "";
    var $db;
    var $dynamique = false;
    var $BASE_URL = "";

    private $connBDD;

    public function __construct(){
      try {
          $this->connBDD = new PDO("mysql:host=".$this->config->config["database"]["hostname"].";dbname=".$this->config->config["database"]["database"], $this->config->config["database"]["username"], $this->config->config["database"]["password"], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
      } catch (PDOException $e) {
          echo "Failed to get DB handle: " . $e->getMessage() . "\n";
          exit;
      }
      $this->domain = $_SERVER["HTTP_HOST"];
      if($this->path == ""){
          //$this->path = explode("?", $_SERVER["REQUEST_URI"]);
          //$this->path = $this->path[0];
      }
      $this->url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      if($this->path == ""){ $this->path = "/"; }
      $arrayUrl = explode(".", $_SERVER['SERVER_NAME']);
    }

    public function load(){
      $infosPage = $this->getInfosPage();
      if(isset($infosPage->id) && isset($infosPage->view)){
        $this->id = $infosPage->id;
        $this->view = $infosPage->view;
      }

      $this->title = $this->getTitle();
      $this->description = $this->getDescription();
    }

    public function getUrl($view){
      $sql = "SELECT pa_url AS 'url' FROM pages WHERE pa_view = :view ";

      $query = $this->connBDD->prepare($sql);
      $query->bindParam(':view', $view, PDO::PARAM_STR);
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_CLASS);

      if(sizeof($result)>0){
          return "//".$this->domain.$result[0]->url;
      }
    }

    public function getInfosPage(){
        $sql = "SELECT pa_id AS 'id', pa_url AS 'url', pa_view AS 'view'";
        $sql .= "FROM pages ";
        $sql .= "WHERE (pa_url = '".$this->path."') OR (pa_dynamique = 1 AND pa_view LIKE '".$this->router->fetch_class()."/".$this->router->fetch_method()."%')";
        
        $query = $this->connBDD->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS);

        if(sizeof($result)>0){
            return $result[0];
        } else{
          header("location: ".$base_url.'/404');
          exit;
        }
    }

    public function getTitle(){
        return $this->getMetaValue("title");
    }

    public function getDescription(){
        return $this->getMetaValue("description");
    }

    private function getMetaValue($meta){
        $sql = "SELECT meta_value AS 'value' FROM pages_meta WHERE meta_key = $meta AND meta_pid = " . $this->id;
        $query = $this->connBDD->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS);

        if(sizeof($result)>0){
            return $result[0]->value;
        }
    }

}
