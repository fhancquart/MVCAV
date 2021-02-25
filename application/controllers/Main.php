<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		 parent::__construct();
		 $this->Page->load();
		 $this->datas["page"] = $this->Page;
		 $this->datas["config"] = $this->config->config;
		 // print_r($this->datas["config"]); exit();

		 $this->load->model("Fiche_model", "fiche");
		 $this->load->model("Data_model", "data_model");
	}

	public function index(){
		header("Location: http://".$this->config->config["URL_AV"]."/financervotreprojet");
	}

	public function financervotreprojet(){
		$this->datas["niveaux"] = json_decode($this->data_model->getNiveaux());
		$this->datas["besoins"] = json_decode($this->data_model->getBesoins());
		$allFiches = json_decode($this->fiche->getAllFiches());

    $this->datas["allFiches"] = $allFiches;

		$this->twig->display('main/index', $this->datas);
	}

	public function solutions(){
		$this->datas["niveaux"] = json_decode($this->data_model->getNiveaux());
		$this->datas["besoins"] = json_decode($this->data_model->getBesoins());
		$allFiches = json_decode($this->fiche->getAllFiches());

    $this->datas["allFiches"] = $allFiches;

		$this->twig->display('main/solutions', $this->datas);
	}

	public function details($url){

		$fiche = $this->fiche->getFicheByURL($url);
		$fiche = json_decode($fiche);

		if(empty($fiche)){
			$this->twig->display('errors/404', $this->datas);
		} else {
			$this->datas["fiche"] = $fiche;
		}
		$this->twig->display('main/details', $this->datas);
	}

	public function login(){
		$this->load->library("Authentication");

		if(isset($_POST["todo"]) && $_POST["todo"] == "login"){

			$authentication = New Authentication;
			$authentication->username = trim($_POST["username"]);
			$authentication->password = trim($_POST["password"]);

			if($authentication->login()){
				setcookie("userlog", $authentication->username);
				header("Location: http://".$this->config->config["URL_AV"]."/administration");
			} else {
				$this->datas["error"] = "Utilisateur ou mot de passe incorrect";
			}
		}

		$this->twig->display('main/login', $this->datas);
	}

	public function error404(){
    http_response_code(404);
    $this->twig->display('errors/404', $this->datas);
  }
}
