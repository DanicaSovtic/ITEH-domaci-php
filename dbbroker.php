<?php

class Broker{
   
    private $mysqli;
    private static $broker;
   
    private function __construct(){
        $this->mysqli = new mysqli("localhost","root","","stanovnistvo");
        $this->mysqli->set_charset("utf8");
    }

    public static function getBroker(){
        if(!isset($broker)){
            $broker=new Broker();
        }
        return $broker;
    }
    public function getLastId(){
        return $this->mysqli->insert_id;
    }
    public function izvrsiCitanje($upit){
        $rezultat=$this->mysqli->query($upit);
        $response=[];
        if(!$rezultat){
            $response['status']=false;
            $response['error']=$this->mysqli->error;
        }
        else{
            $response['status']=true;
            while($red=$rezultat->fetch_object()){
                $response['podaci'][]=$red;
            }
        }
        return $response;
    }
    public function izvrsiIzmenu($upit){
        $rezultat=$this->mysqli->query($upit);
        $response=[];
        $response['status']=(!$rezultat)?false:true;
        if(!$rezultat){
            $response['error']=$this->mysqli->error;
        }
        return $response;
    }
    
    
}


?>