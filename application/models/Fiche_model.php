<?php
class Fiche_model extends CI_Model
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

  public function getAllFiches(){
    $sql = "SELECT DISTINCT fich_id, fich_title, fich_url, fich_text, fich_logo, fich_siteI, fich_desc, fich_prio, so_title, so_url, nbs_besoinID FROM fiches ";
    $sql .= "INNER JOIN niveaux_besoins_solutions ON fich_idsolu = nbs_solutionID ";
    $sql .= "INNER JOIN solutions ON so_id = nbs_solutionID ";
    $sql .= "ORDER BY fich_prio";

    $query = $this->connBDD->prepare($sql);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result);
    } else {
      return json_encode(array());
    }
  }

  public function getFiches($idcat = null){

    $sql = "SELECT DISTINCT fiches.* FROM fiches INNER JOIN niveaux_besoins_solutions ON fich_idsolu = nbs_id WHERE fich_active = 1 ";
    if(!empty($idcat)){ $sql .= "AND fich_idcat = :idcat "; }
    $sql .= "ORDER BY fich_id DESC";

    $query = $this->connBDD->prepare($sql);
    if(!empty($idcat)){ $query->bindParam(':idcat', $idcat, PDO::PARAM_INT); }
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result);
    } else {
      return json_encode(array());
    }

  }

  public function getFicheById($id){

    $sql = "SELECT * FROM fiches WHERE fich_id = :id ";

    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result[0]);
    } else {
      return json_encode(array());
    }

  }

  public function getFicheByURL($url){

    $sql = "SELECT * FROM fiches WHERE fich_url = :url ";

    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':url', $url, PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result[0]);
    } else {
      header("location: ".$base_url.'/404');
      exit;
    }

  }

  public function getSolutions(){

    $sql = "SELECT * FROM solutions ";

    $query = $this->connBDD->prepare($sql);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result);
    } else {
      return json_encode(array());
    }

  }

  public function getFichesBySolution($idSolu){

    $sql = "SELECT DISTINCT fich_id, fich_title, fich_url, fich_text, fich_logo, fich_siteI, fich_desc, so_title, so_url ";
    $sql .= "FROM fiches ";
    $sql .= "INNER JOIN niveaux_besoins_solutions ON fich_idsolu = nbs_solutionID ";
    $sql .= "INNER JOIN solutions ON so_id = nbs_solutionID ";
    $sql .= "WHERE fich_idsolu = :idsolu ";

    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':idsolu', $idSolu, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_CLASS);

    if(sizeof($result) > 0){
      return json_encode($result);
    } else {
      return json_encode(array());
    }

  }

  public function update($datas){
    $datas = json_decode($datas);

    if(isset($datas->fich_id)){

      $update = "UPDATE fiches SET";
      if(isset($datas->fich_url)){ $update .= " fich_url = :fich_url,"; }
      if(isset($datas->fich_title)){ $update .= " fich_title = :fich_title,"; }
      if(isset($datas->fich_text)){ $update .= " fich_text = :fich_text,"; }
      if(isset($datas->fich_logo)){ $update .= " fich_logo = :fich_logo,"; }
      if(isset($datas->fich_siteI)){ $update .= " fich_siteI = :fich_siteI,"; }
      if(isset($datas->fich_desc)){ $update .= " fich_desc = :fich_desc,"; }
      if(isset($datas->fich_kpi)){ $update .= " fich_kpi = :fich_kpi,"; }
      if(isset($datas->fich_zone)){ $update .= " fich_zone = :fich_zone,"; }
      if(isset($datas->fich_secteur)){ $update .= " fich_secteur = :fich_secteur,"; }
      if(isset($datas->fich_active)){ $update .= " fich_active = :fich_active,"; }
      if(isset($datas->fich_idsolu)){ $update .= " fich_idsolu = :fich_idsolu,"; }
      if(isset($datas->fich_prio)){ $update .= " fich_prio = :fich_prio,"; }
      $update = substr($update, 0, -1);
      $update .= " WHERE fich_id = :fich_id ";



      $query = $this->connBDD->prepare($update);
      if(isset($datas->fich_url)){ $query->bindParam(':fich_url', $datas->fich_url, PDO::PARAM_STR); }
      if(isset($datas->fich_title)){ $query->bindParam(':fich_title', $datas->fich_title, PDO::PARAM_STR); }
      if(isset($datas->fich_text)){ $query->bindParam(':fich_text', $datas->fich_text, PDO::PARAM_STR); }
      if(isset($datas->fich_logo)){ $query->bindParam(':fich_logo', $datas->fich_logo, PDO::PARAM_STR); }
      if(isset($datas->fich_siteI)){ $query->bindParam(':fich_siteI', $datas->fich_siteI, PDO::PARAM_STR); }
      if(isset($datas->fich_desc)){ $query->bindParam(':fich_desc', $datas->fich_desc, PDO::PARAM_STR); }
      if(isset($datas->fich_kpi)){ $query->bindParam(':fich_kpi', $datas->fich_kpi, PDO::PARAM_STR); }
      if(isset($datas->fich_zone)){ $query->bindParam(':fich_zone', $datas->fich_zone, PDO::PARAM_STR); }
      if(isset($datas->fich_secteur)){ $query->bindParam(':fich_secteur', $datas->fich_secteur, PDO::PARAM_STR); }
      if(isset($datas->fich_active)){ $query->bindParam(':fich_active', $datas->fich_active, PDO::PARAM_INT); }
      if(isset($datas->fich_idsolu)){ $query->bindValue(':fich_idsolu', $datas->fich_idsolu, PDO::PARAM_INT); }
      if(isset($datas->fich_prio)){ $query->bindValue(':fich_prio', $datas->fich_prio, PDO::PARAM_INT); }
      $query->bindParam(':fich_id', $datas->fich_id, PDO::PARAM_INT);

      if($query->execute()){
        return true;
      } else {
        return false;
      }

    }

  }

  public function add($datas){

    $datas = json_decode($datas);

    $sql = "INSERT INTO fiches (fich_date, fich_url, fich_title, fich_text, fich_logo, fich_siteI, fich_desc, fich_kpi, fich_zone, fich_secteur, fich_active, fich_idsolu, fich_prio) ";
    $sql .= "SELECT NOW(), :fich_url, :fich_title, :fich_text, :fich_logo, :fich_siteI, :fich_desc, :fich_kpi, :fich_zone, :fich_secteur, :fich_active, :fich_idsolu, :fich_prio ";
    $sql .= "WHERE NOT EXISTS (SELECT fich_id FROM fiches WHERE fich_url = :fich_url2)";

    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':fich_url', $datas->fich_url, PDO::PARAM_STR);
    $query->bindParam(':fich_title', $datas->fich_title, PDO::PARAM_STR);
    $query->bindParam(':fich_text', $datas->fich_text, PDO::PARAM_STR);
    $query->bindParam(':fich_logo', $datas->fich_logo, PDO::PARAM_STR);
    $query->bindParam(':fich_siteI', $datas->fich_siteI, PDO::PARAM_STR);
    $query->bindParam(':fich_desc', $datas->fich_desc, PDO::PARAM_STR);
    $query->bindParam(':fich_kpi', $datas->fich_kpi, PDO::PARAM_STR);
    $query->bindParam(':fich_zone', $datas->fich_zone, PDO::PARAM_STR);
    $query->bindParam(':fich_secteur', $datas->fich_secteur, PDO::PARAM_STR);
    $query->bindParam(':fich_active', $datas->fich_active, PDO::PARAM_INT);
    $query->bindValue(':fich_idsolu', $datas->fich_idsolu, PDO::PARAM_INT);
    $query->bindValue(':fich_prio', $datas->fich_prio, PDO::PARAM_INT);
    $query->bindParam(':fich_url2', $datas->fich_url, PDO::PARAM_STR);
    $query->execute();

    if($this->connBDD->lastInsertId() == 0){
      return false;
    } else {
      return true;
    }

  }

  public function addSolution($datas){

    $datas = json_decode($datas);

    $sql = "INSERT INTO solutions (so_title, so_active) VALUES (:so_title, 1); ";
    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':so_title', $datas->so_title, PDO::PARAM_STR);
    $query->execute();

    $so_id = $this->connBDD->lastInsertId();

    $sql = "INSERT INTO niveaux_besoins_solutions (nbs_niveauID, nbs_besoinID, nbs_solutionID) VALUES (:nbs_niveauID, :nbs_besoinID, :nbs_solutionID) ";
    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':nbs_niveauID', $datas->nbs_niveauID, PDO::PARAM_INT);
    $query->bindParam(':nbs_besoinID', $datas->nbs_besoinID, PDO::PARAM_INT);
    $query->bindParam(':nbs_solutionID', $so_id, PDO::PARAM_INT);
    if($query->execute()){
      return true;
    } else {
      return false;
    }
  }

  public function delete($id){

    $sql = "DELETE FROM fiches WHERE fich_id = :id ";
    $query = $this->connBDD->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    if($query->execute()){
      return true;
    } else {
      return false;
    }

  }

}
