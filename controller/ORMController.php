<?php
require(__DIR__.'/../model/ORM.php');

class ORMController{

    public function getAll($table){
        $orm = new ORM();
        $data = $orm->getAll($table);
        return $data;
    }

    public function getById($id, $table){
        $orm = new ORM();
        $data = $orm->getById($id, $table);
        return $data;
    }

    public function getTargetById($id, $target, $table){
        $orm = new ORM();
        $data = $orm->getTargetById($id, $target, $table);
        return $data;
    }

    public function getTargetByField($table, $target, $value){
        $orm = new ORM();
        $data = $orm->getTargetByField($table, $target, $value);

        return $data;
    }

    public function insertData($table, $data){
        $orm = new ORM();
        $orm->insertData($table, $data);
    }

    public function initialData(){
        $orm = new ORM();
        $orm->initialData();
    }
}