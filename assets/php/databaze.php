<?php
    
    $mysqlLogin="root";
    $mysqlHeslo="";
    $mysqlDatabase="dametrip";
    $mysqlServer="localhost";
    
    function reset(){
        loguj("RESET DATABÁZE!!!");
        //tabulka
        $dotaz="DROP TABLE lidi;";
        dotaz($dotaz);
        $dotaz="CREATE TABLE lidi(
                Id INT UNIQUE PRIMARY KEY,
                Jmeno TEXT,
                Vek INT,
                Email TEXT,
                Pohlavi TEXT,
                BydlisteStat TEXT,
                BydlisteMesto TEXT,
                Cinnost TEXT,
                DestinaceStat TEXT,
                DestinaceMesto TEXT,);";
        $ok=dotaz($dotaz);
        
    }
    
    function pridej($Jmeno, $Vek, $Email,$Pohlavi, $BydlisteStat, $BydlisteMesto, $Cinnost, $DestinaceStat, $DestinaceMesto){
        $vysledek=dotaz("SELECT Id FROM lidi ORDER BY Id DESC;");
    }

    function dotaz($dotaz){
        public $link;
        if(!$link){prihlasit();}
        loguj("Spouštím dotaz: $dotaz");
        $vysledek=mysqli_query($link, $dotaz);
        if($vysledek){
            return $vysledek;
        }
        else{
            loguj("Dotaz se nezdařil");
            return false;
        }
        
    }

    function prihlasit(){
        public $mysqlLogin, $mysqlHeslo, $mysqlServer, $mysqlDatabase, $link;
        $link=mysqli_connect($mysqlAdress,$mysqlLogin, $mysqlPassword);
        $ok=mysqli_select_db($link, $mysqlDatabase);
        return $link;
    }

    function loguj($zapis){
        //echo $zapis;
    }

?>