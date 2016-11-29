<?php
    //TODO:
    //udělat nějakou zabezpečovací funkci, kterou se budou prohánět všechny user inputy.
    //automatchování
    //validace
    //mezera ve jméně
    //

    $mysqlLogin="root";
    $mysqlHeslo="";
    $mysqlDatabase="dametrip";
    $mysqlServer="localhost";

    function inicializovat(){ //vyčistění databáze
        loguj("RESET DATABÁZE!!!");
        //tabulka
        $dotaz="DROP TABLE lidi;";
        dotaz($dotaz);
        $dotaz="CREATE TABLE lidi(
                Id INT UNIQUE PRIMARY KEY,
                Jmeno TEXT,
                Vek INT,
                Email TEXT,
                BydlisteStat TEXT,
                BydlisteMesto TEXT,
                Cinnost TEXT,
                DestinaceStat TEXT,
                DestinaceMesto TEXT,
                Validovano INT);";
        $ok=dotaz($dotaz);
        if($ok){
            return true;
        }else{
            return false;
        }
        
    }
    
    function pridej($Jmeno, $Vek, $Email, $BydlisteStat, $BydlisteMesto, $Cinnost, $DestinaceStat, $DestinaceMesto){ //přidá uživatele do databáze
        loguj("Přidávám nového uživatele do databáze....");
        $vysledek=dotaz("SELECT Id FROM lidi ORDER BY Id DESC;");
        $nextId=mysqli_fetch_array($vysledek)[0]+1;
        $ok=dotaz("INSERT INTO lidi VALUES($nextId,'$Jmeno',$Vek,'$Email','$BydlisteStat','$BydlisteMesto','$Cinnost','$DestinaceStat','$DestinaceMesto',0)");
        
        if($ok){
            return true;
        }else{
            return false;
        }
        
    }

    function smaz($id){
        if($id=="" or !$id){return false;}
        $ok=dotaz("DELETE FROM lidi WHERE Id=$id;");    
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $BydlisteStat, $BydlisteMesto, $Cinnost, $DestinaceStat, $DestinaceMesto){
        dotaz("UPDATE lidi SET Jmeno='$Jmeno', Vek='$Vek', Email='$Email', Pohlavi='$Pohlavi', BydlisteStat='$BydlisteStat', BydlisteMesto='$BydlisteMesto', Cinnost='$DestinaceStat', DestinaceMesto='$DestinaceMesto';");
        
    }

    function dotaz($dotaz){
        global $link;
        if(!$link){prihlasit();}
        loguj("Spouštím dotaz: $dotaz");
        $vysledek=mysqli_query($link, $dotaz);
        if($vysledek){
            loguj("Dotaz se zdařil");
            return $vysledek;
        }
        else{
            loguj("Dotaz se nezdařil");
            return false;
        }
        
    }

    function prihlasit(){
        global $mysqlLogin, $mysqlHeslo, $mysqlServer, $mysqlDatabase, $link;
        $link=mysqli_connect($mysqlAdress,$mysqlLogin, $mysqlPassword);
        $ok=mysqli_select_db($link, $mysqlDatabase);
        return $link;
    }

    function loguj($zapis){
        echo $zapis;
        $fp=fopen("log.txt","a");
        fwrite($fp, "$zapis \n");
        fclose($fp);
    }

    function poslimail($od, $komu, $predmet, $zprava )
    {
        loguj("posílám mail: od $od na mail $komu. predmet: $predmet, text: $zprava");
        mail($komu,$predmet,$zprava,$od);
    }


?>