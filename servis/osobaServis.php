
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
        echo json_encode($broker->izvrsiCitanje("SELECT o.*, g.naziv as 'grad' from osoba o left join grad g on (g.id=o.grad_id)"));
        exit;
    }
    if($metoda=='obrisi'){
        $id=$_POST['id'];
        if(!validanId($id)){
            echo json_encode(kreirajGresku("id nije prosledjen"));
            exit;
        }
        echo json_encode($broker->izvrsiIzmenu("delete from osoba where id=".$id));
    }
    if($metoda=='kreiraj'){
        $ime=$_POST['ime'];
        $prezime=$_POST['prezime'];
        $datum_rodjenja=$_POST['datum_rodjenja'];
        $pol=$_POST['pol'];
        $drzavljanstvo=$_POST['drzavljanstvo'];
        $nacionalnost=$_POST['nacionalnost'];
        $grad_id=$_POST['grad_id'];
        echo json_encode($broker->izvrsiIzmenu("insert into osoba(ime,prezime,datum_rodjenja,pol,drzavljanstvo,nacionalnost,grad_id) values ('".$ime."','".$prezime."','".$datum_rodjenja."','".$pol."','".$drzavljanstvo."','".$nacionalnost."',".$grad_id.")"));
        exit;
    }
    if($metoda=='izmeni'){
        $id=$_POST['id'];
        if(!validanId($id)){
            echo json_encode(kreirajGresku("id nije prosledjen"));
            exit;
        }
        $ime=$_POST['ime'];
        $prezime=$_POST['prezime'];
        $datum_rodjenja=$_POST['datum_rodjenja'];
        $pol=$_POST['pol'];
        $drzavljanstvo=$_POST['drzavljanstvo'];
        $nacionalnost=$_POST['nacionalnost'];
        $grad_id=$_POST['grad_id'];
        echo json_encode($broker->izvrsiIzmenu("update osoba set ime='".$ime."', prezime='".$prezime."',datum_rodjenja='".$datum_rodjenja."', pol='".$pol."', drzavljanstvo='".$drzavljanstvo."',nacionalnost='".$nacionalnost."', grad_id=".$grad_id." where id=".$id));
     
    }

?>