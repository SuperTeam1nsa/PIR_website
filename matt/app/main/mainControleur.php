<?php
/*
session_start(); //deja dans app

if (!isset ($_GET["action"])) {
	die("requ&ecirc;te non autoris&eacute;e");
}
*/
require_once(dirname(__FILE__)."/../main/mainModele.php");

class mainControleur {
	private $modele;

	public function __construct(){
		$this->modele = new mainModele;
	}

	// fonctions
	public function show(){
		//$titre = "Liste de scores";
		// récupération des enregistrements 
		//$liste = $this->modele->recupereTous();
		// affichage de la vue
		//$data = array("liste"=>$liste, "titre"=>$titre); 
        $data="";
		$this->view("Histoire", $data,'main');
	}
	public function view($view, $data = [], $module){
		require(dirname(__FILE__).'/../'.$module.'/'.$view.'Vue.php');
	}
    public function showIceCreams(){
        $data="";
		$this->view("Glace", $data,'main');
    }   
	public function showForm(){
        //check with bd if icecream available 
        //ajax ? (pour rafraichir stock dans un 2ème temps)
        //mail de confirmation commande qui recheck la bd (1er temps)
        //send it in $data 
        $data="";
		$this->view("Form", $data,'main');
        //action du clic : post sur url : main/checkForm
        //la fonction checkForm check les data et envoie mail/ sms à matt
    }   
        
	public function afficherFormulaire($mode, $donnees, $erreurs){
		if($mode == "creation"){
			$titre = "Création";
			$action = "creer";
		} else	if($mode == "modification"){
			$titre = "Modification";
			$action = "modifier";
		}
		// affichage de la vue
		$data = array("donnees"=>$donnees, "erreurs"=>$erreurs, "action"=>$action, 'titre'=>$titre); 
		$this->view("autre", $data,'accueil');
	}

}
?>
