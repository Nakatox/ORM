<?php


class ORM{

    public function getById($id, $table){
        require(__DIR__.'/../config/connexion.php');

        $req = $database->prepare("SELECT * FROM $table WHERE id = :id");
        $req->execute(array(
            'id' => $id
        ));

        $data = $req->fetch(PDO::FETCH_ASSOC);


        return $data;
    }

    public function getAll($table){
        require(__DIR__.'/../config/connexion.php');

        $req = $database->prepare("SELECT * FROM $table");
        $req->execute();

        $data = $req->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getTargetById($id, $table, $target){
        require(__DIR__.'/../config/connexion.php');

        $req = $database->prepare("SELECT * FROM $target WHERE ".$table."_id = :id");
        $req->execute(array(
            'id' => $id
        ));

        $data = $req->fetchAll(PDO::FETCH_ASSOC);


        return $data;
    }

    public function getTargetByField($table, $target, $value){
        require(__DIR__.'/../config/connexion.php');

        $new_str = str_replace(' ', '', $value);


        $req = $database->prepare("SELECT * FROM `$table` WHERE `$target` = ".'"'.$new_str.'"'); 

        $req->execute();

        $data = $req->fetch(PDO::FETCH_ASSOC);


        return $data;
    }

    public function initialData(){

        require(__DIR__.'/../config/connexion.php');

        $req = $database->prepare("INSERT INTO `State`(`libelle`) VALUES ('En cours'), ('Fermé'), ('Ouvert')");
        $req->execute();

        $req = $database->prepare("INSERT INTO `Service`(`libelle`) VALUES ('réseau'), ('administration'), ('informatique'), ('comptabilité')");
        $req->execute();

    }
    public function insertData($table, $data){
        require(__DIR__.'/../config/connexion.php');

        $date = date('d-m-y h:i:s');

        $data['date'] = $date;   
        if ($table == 'ticket') {
            $tabState = $this->getTargetByField('state', 'libelle', 'Ouvert');
            $data['state_idstate'] = $tabState['idstate'];
        }   

        $values = "";
        $keys = "";

        foreach($data as $key => $value){

            if ($key == 'title') {
                $values .="'".str_replace(' ', '', $value)."', ";
                $keys .= "`".$key."`, ";
            }else{
                $values .="'".$value."', ";
                $keys .= "`".$key."`, ";
            }
        }

        $values = substr($values, 0, -2);
        $keys = substr($keys, 0, -2);

        $req = $database->prepare("INSERT INTO $table ($keys) VALUES ($values)");
        $req->execute();
    }
}