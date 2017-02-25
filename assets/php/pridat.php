<?php
    if(isset($_POST["Jmeno"]) && isset($_POST["Vek"]) && isset($_POST["Email"]) && isset($_POST["Bydliste"]) && isset($_POST["Cinnost"]) && isset($_POST["Destinace"])){
        
        $Jmeno=$_POST["Jmeno"];
        $Vek=$_POST["Vek"];
        $Email=$_POST["Email"];
        $Bydliste=$_POST["Bydliste"];
        $Cinnost=$_POST["Cinnost"];
        $Destinace=$_POST["Destinace"];
        
        //tady někde to nějak zvalidovat a možná vyřešit SQL injection.... nebo až v přidávací funkci?...
        
        $ok=pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace);
        
    }
?>