<?php
    include "databaze.php";

    loguj("Pokus o přidání nového uživatele...");
    if(isset($_POST["Jmeno"]) && isset($_POST["Vek"]) && isset($_POST["Email"]) && isset($_POST["Bydliste"]) && isset($_POST["Cinnost"]) && isset($_POST["Destinace"])){
        
        loguj("Isset validace úspěšná!");
        $Jmeno=safeString($_POST["Jmeno"]);
        $Vek=safeString($_POST["Vek"]);
        $Email=safeString($_POST["Email"]);
        $Bydliste=safeString($_POST["Bydliste"]);
        $Cinnost=safeString($_POST["Cinnost"]);
        $Destinace=safeString($_POST["Destinace"]);
        $ok=pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace);
        if($ok){
            loguj("Uživatel úspěšně přidán");
        }else{
            loguj("Přidání uživatele se nezdařilo!");
        }
    }
    else{
        loguj("issetová zkouška neuspěla, asi nebyly vyplněny všechny parametry");
    }
?>