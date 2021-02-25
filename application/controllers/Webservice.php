<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webservice extends CI_Controller {

	public function __construct(){
	 	parent::__construct();
	 	$this->load->model("Data_model", "data_model");
	 	$this->load->model("Fiche_model", "fiche");
	}

	public function getSolutions(){

		$datas = json_decode(file_get_contents("php://input"));

		if(!empty($datas) && isset($datas->niveau) && isset($datas->besoin)){
			print_r($this->data_model->getSolutions($datas->niveau, $datas->besoin));

		} else {

			print_r("[]");

		}

	}

	public function getFichesBySolution($solutionID){

		if(!empty($solutionID)){

			print_r($this->fiche->getFichesBySolution($solutionID));

		} else {

			print_r("[]");

		}

	}

	public function uploadImage(){

		if(isset($_FILES['upload']['name']))
		{
		 $file = $_FILES['upload']['tmp_name'];
		 $file_name = $_FILES['upload']['name'];
		 $file_name_array = explode(".", $file_name);
		 $extension = end($file_name_array);
		 $new_image_name = rand() . '.' . $extension;
		 chmod('upload', 0777);
		 $allowed_extension = array("jpg", "gif", "png");
		 if(in_array($extension, $allowed_extension))
		 {
		  move_uploaded_file($file, 'upload/' . $new_image_name);
		  $function_number = $_GET['CKEditorFuncNum'];
		  $url = 'http://' . $this->config->config["URL_AV"] . '/upload/' . $new_image_name;
		  $message = '';
		  echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
		 }
		}
	}


}
