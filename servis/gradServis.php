
<?php
    include '../dbbroker.php';
    include '../util.php';
    $broker=Broker::getBroker();
    $metoda='';

    if(isset($_GET['metoda'])){
        $metoda=$_GET['metoda'];
        
    }
    if(isset($_POST['metoda'])){
        $metoda=$_POST['metoda'];
        
    }
    if($metoda==''){
        exit;
    }
    if($metoda=='sve'){
        echo json_encode($broker->izvrsiCitanje("select g.*, count(o.id) from grad g left join osoba o on (g.id=o.grad_id) group by g.id"));
        exit;
    }
    if($metoda=='obrisi'){
        $id=$_POST['id'];
        if(!validanId($id)){
            echo json_encode(kreirajGresku("id nije prosledjen"));
            exit;
        }
        echo json_encode($broker->izvrsiIzmenu("delete from grad where id=".$id));
    }
    if($metoda=='kreiraj'){
        $naziv=$_POST['naziv'];
        $postanski_broj=$_POST['postanski_broj'];
        echo json_encode($broker->izvrsiIzmenu("insert into grad(naziv,postanski_broj) values ('".$naziv."','".$postanski_broj."')"));
        exit;
    }
    if($metoda=='izmeni'){
        $id=$_POST['id'];
        if(!validanId($id)){
            echo json_encode(kreirajGresku("id nije prosledjen"));
            exit;
        }
        $naziv=$_POST['naziv'];
        $postanski_broj=$_POST['postanski_broj'];
        echo json_encode($broker->izvrsiIzmenu("update grad set naziv='".$naziv."', postanski_broj='".$postanski_broj."' where id=".$id));
     
    }

?>