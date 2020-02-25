<?php

//rq: $req = $this->bd->prepare($sql); (se prémunir des injections)
// $req->bind_param("i",$id); //i pour integer, s pour string (pour remplacer les ? du sql)
class mainModele{
	private $bd;
	public function __construct(){
		$this->bd = new mysqli('127.0.0.1', 'root', '', 'dbname');
        if($this->bd->connect_errno)
            echo "Failed to connect to MySql: ".$this->bd->connect_error;
    }
    public function __destruct(){
        $this->bd->close();
    }
	public function recupereTous(){
		// récupération de tous les enregistrements
		$sql = "SELECT * FROM score";
		$req = $this->bd->prepare($sql);
        //$req->execute()or die($req->error); 
		if($req->execute()){
			$ret = $req->get_result();
                }
		return $ret;
	}

	public function ajouteEnregistrement($donnees){
		$ret = -1;
		$valeur = $donnees['valeur'];
		$idUser = $_SESSION['id'];
		// ajout d'un enregistrement
		$sql = "INSERT INTO score (`id`, `valeur`, `idUtilisateur`)
			VALUES (NULL, ?, ?);";
		$req = $this->bd->prepare($sql);
		$req->bind_param("ss",$valeur,$idUser);
		if($req->execute())
		{
			$ret = $this->bd->insert_id;
		}
		return $ret;
	}
	public function recupereEnregistrementParId($id){
		// récupération d'un enregistrement
		$ret = null;
		$sql = "SELECT * FROM score WHERE id = ?";
		$req = $this->bd->prepare($sql);
		$req->bind_param("s",$id);
		if($req->execute()){
			$tmp = $req->get_result();
			$ret =$tmp->fetch_assoc();
		}
		return $ret;
	}	
	public function modifieEnregistrement($id, $donnees){
		$ret = -1;
		$valeur = $donnees['valeur'];
		// récupération d'un enregistrement
		$sql = "UPDATE score SET `valeur` = ? WHERE id = ?;";
		$req = $this->bd->prepare($sql);
		$req->bind_param("ss",$valeur,$id);
		if($req->execute()){
			$ret = $id;
		}
		return $ret;
	}
	public function supprimeEnregistrement($id){
		// suppression d'un enregistrement
		$ret = -1;
		$sql = "DELETE FROM score WHERE id = ?";
		$req = $this->bd->prepare($sql);
		$req->bind_param("i",$id);
		if($req->execute()){
			$ret = $id;
		}
		return $ret;
	}	
}
?>
