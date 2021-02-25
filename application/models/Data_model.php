<?php
class Data_model extends CI_Model
{

  private $connBDD;

  public function __construct(){
    try {
        $this->connBDD = new PDO("mysql:host=".$this->config->config["database"]["hostname"].";dbname=".$this->config->config["database"]["database"], $this->config->config["database"]["username"], $this->config->config["database"]["password"], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }
  }

  private function query($sql){

    $query = $this->connBDD->prepare($sql);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_CLASS);
    if(sizeof($result) > 0){
      return json_encode($result);
    } else {
      return json_encode(array());
    }

  }

  public function getNiveaux(){

    return $this->query("SELECT ni_id, ni_title, ni_url FROM niveaux");

  }

  public function getBesoins(){

    return $this->query("SELECT be_id, be_title, be_url FROM besoins");

  }

  public function getSolutions($niveau, $besoin){

    $sql = "SELECT DISTINCT fich_id, fich_title, fich_url, fich_prio, so_title, so_url, nbs_besoinID, nbs_niveauID FROM fiches ";
    $sql .= "INNER JOIN niveaux_besoins_solutions ON fich_idsolu = nbs_solutionID ";
    $sql .= "INNER JOIN solutions ON so_id = nbs_solutionID ";
    $sql .= "WHERE nbs_besoinID = :besoin AND nbs_niveauID = :niveau ";
    $sql .= "ORDER BY fich_prio";

    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':niveau', $niveau, PDO::PARAM_INT);
    $query->bindParam(':besoin', $besoin, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result);
    } else {
      return json_encode(array());
    }

  }

}
