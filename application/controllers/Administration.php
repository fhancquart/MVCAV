<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administration extends CI_Controller {

	private $datas = array();

	public function __construct(){
	 	parent::__construct();
		$this->Page->load();

		$this->datas["page"] = $this->Page;
		$this->datas["config"] = $this->config->config;

		if(!isset($_COOKIE["userlog"])){
	 		header("Location: http://" . $this->config->config["URL_AV"]."/financervotreprojet");
	 		exit();
	 	}

	 	$this->load->model("Fiche_model", "fiche");
	}

	public function index(){

		if(isset($_POST["todo"]) && $_POST["todo"] == "delete"){
			if(!$this->fiche->delete($_POST["idfiche"])){
				$this->datas["error"] = "NOKDELETE";
			} else {
				header("Location: http://".$this->config->config["URL_AV"]."/financervotreprojet/administration");
			}
		}

		$fiches = $this->fiche->getFiches();
		$this->datas["fiches"] = json_decode($fiches);

		$this->twig->display('administration/index', $this->datas);
	}

	public function fiche($id){

		$this->datas["error"] = false;

		if(isset($_POST["todo"]) && $_POST["todo"] == "delete"){
			if(!$this->fiche->delete($_POST["idfiche"])){
				$this->datas["error"] = "NOKDELETE";
			} else {
				header("Location: http://".$this->config->config["URL_AV"]."/financervotreprojet/administration");
			}
		}

		if(isset($_POST["todo"]) && $_POST["todo"] == "update"){
			if(!$this->fiche->update(json_encode($_POST))){
				$this->datas["error"] = "NOK";
			}
		}

		$fiche = $this->fiche->getFicheById($id);
		$this->datas["fiche"] = json_decode($fiche);
		$this->datas["solutions"] = json_decode($this->fiche->getSolutions());

		$this->twig->display('administration/fiche', $this->datas);

	}

	public function addFiche(){
		$this->datas["error"] = "";
		if(isset($_POST["todo"]) && $_POST["todo"] == 'add'){
			if(!$this->fiche->add(json_encode($_POST))){
				$this->datas["error"] = "NOK";
			} else {
				header("Location: http://".$this->config->config["URL_AV"]."/financervotreprojet/administration");
			}
		}

		$this->datas["solutions"] = json_decode($this->fiche->getSolutions());

		$this->twig->display('administration/addfiche', $this->datas);

	}

	public function addSolution(){

		$this->load->model("Data_model", "data_model");

		if(isset($_POST["todo"]) && $_POST["todo"] == 'add'){
			if(!$this->fiche->addSolution(json_encode($_POST))){
				$this->datas["error"] = "NOK";
			} else {
				header("Location: http://".$this->config->config["URL_AV"]."/financervotreprojet/administration");
			}
		}

		$this->datas["niveaux"] = json_decode($this->data_model->getNiveaux());
		$this->datas["besoins"] = json_decode($this->data_model->getBesoins());

		$this->twig->display('administration/addsolution', $this->datas);

	}
}

?>
