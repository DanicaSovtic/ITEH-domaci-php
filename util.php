<?php

    function kreirajGresku($greska){
        return [
            "status"=>false,
            "greska"=>$greska
        ];
    }
    function validanId($id){
       return isset($id) && intval($id)>0;
    }
?>