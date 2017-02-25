<?php
    include "databaze.php";

    loguj("Pokus o přidání nového uživatele...");
    if(isset($_POST["Jmeno"]) && isset($_POST["Vek"]) && isset($_POST["Email"]) && isset($_POST["Bydliste"]) && isset($_POST["Cinnost"]) && isset($_POST["Destinace"])){
        
        loguj("Isset validace úspěšná!");
        $Jmeno=sefeString($_POST["Jmeno"]);
        $Vek=sefeString($_POST["Vek"]);
        $Email=sefeString($_POST["Email"]);
        $Bydliste=sefeString($_POST["Bydliste"]);
        $Cinnost=sefeString($_POST["Cinnost"]);
        $Destinace=sefeString($_POST["Destinace"]);
        
        $ok=pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace);
        
    }
    else{
        loguj("issetová zkouška neuspěla, asi nebyly vyplněny všechny parametry");
    }
?>