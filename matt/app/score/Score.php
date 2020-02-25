<?php 
class Score{
    private $score;
    private $pseudo;
    private $id;
    private $idUtilisateur;
    private $date;
    public function __construct($sc,$name,$iD,$idUser,$dat){
        $this->score=$sc;
        $this->pseudo=$name;
        $this->id=$iD;
        $this->date=$dat;
        $this->idUtilisateur=$idUser;
    }
    public function getScore(){
        return $this->score;
    }
    public function getPseudo(){
        return $this->pseudo;
    }
    public function getDate(){
        return $this->date;
    }
    public function getIdUtilisateur(){
        return $this->idUtilisateur;
    }
    public function getId(){
        return $this->id;
    }
}

?>
