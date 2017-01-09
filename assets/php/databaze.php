<?php
    //TODO:
    //udělat nějakou zabezpečovací funkci, kterou se budou prohánět všechny user inputy.
    //automatchování
    //cachovanou apíčkovou funkci - oboustranně
    //uživatel musí mít
        //mezeru ve jméně
        //zavináč a tečku v mailu
        //věk mezi 1 - 120
        //validované město původu
        //validní destinaci
        //do budoucna jednu z povolených aktivit

    $mysqlLogin="a148986_2";
    $mysqlHeslo="FmVW6LUj";
    $mysqlDatabase="d148986_2";
    $mysqlServer="wm133.wedos.net";
    $apiKey="AIzaSyBCJLPKH2GQ-uGV_F6B6gvVweFO_MQrbNQ";

    function inicializovat(){ //vyčistění databáze
        loguj("RESET DATABÁZE!!!");
        //tabulka uživatelů
        $dotaz="DROP TABLE lidi;";
        dotaz($dotaz);
        $dotaz="CREATE TABLE lidi(
                Id INT UNIQUE PRIMARY KEY,
                Jmeno TEXT,
                Vek INT,
                Email TEXT,
                Bydliste TEXT,
                Cinnost TEXT,
                Destinace TEXT,
                Validovano INT,
                Kod TEXT);";
        $ok1=dotaz($dotaz);
        
        $dotaz="DROP TABLE geocodes;";
        dotaz($dotaz);
        $dotaz="CREATE TABLE geocodes(
                Nazev TEXT,
                Vysledek TEXT,
                Timestamp INT);";
        $ok2=dotaz($dotaz);
        if($ok1 && $ok2){
            return true;
        }else{
            return false;
        }
        
    }
    
    function pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace){ //přidá uživatele do databáze
        loguj("Přidávám nového uživatele do databáze....");
        $nextId=rand(0,999999999);
        $kod=rand(11111111,99999999);
        $Bydliste=geocode($Bydliste);
        $Destinace=geocode($Destinace);
        $ok=dotaz("INSERT INTO lidi VALUES($nextId,'$Jmeno',$Vek,'$Email','$Bydliste','$Cinnost','$Destinace',0,$kod)");
        
        if($ok){
            posliMail("team@dametrip.cz",$Email,"Dámetrip.cz - Potvrzení emailové adresy","http://beta.dametrip.cz/validace.php?kod=$kod");
            return true;
        }else{
            return false;
        }
        
    }

    function smaz($id){
        if($id=="" or !$id){return false;}
        $ok=dotaz("DELETE FROM lidi WHERE Id='$id';");    
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $Bydliste, $Cinnost, $Destinace, $Validovano, $Kod){
        if($id=="" or !$id){return false;}
        $Bydliste=geocode($Bydliste);
        $Destinace=geocode($Destinace);
        $ok=dotaz("UPDATE lidi SET Jmeno='$Jmeno', Vek='$Vek', Email='$Email', Pohlavi='$Pohlavi', Bydliste='$Bydliste', Cinnost='$Cinnost', Destinace='$Destinace', Validovano=$Validovano, Kod='$Kod' WHERE Id='$id';");
        if($ok){
            return true;
        }else{
            return false;
        }
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
        
        $link=mysqli_connect($mysqlServer,$mysqlLogin, $mysqlHeslo);
        $ok=mysqli_select_db($link, $mysqlDatabase);
        return $link;
    }

    function geocode($nazev){
        global $apiKey;
        $maxAge=2629743; //jednou za měsíc obnovit
        
        loguj("Geocoduju $nazev");
        $nazev=str_replace(" ","+",$nazev); //snad tam nebudou dvojité mezery... ty by to neměly rozbít, ale stejně...
        loguj("hledam v $nazev v cache tabulce");
        $ok=dotaz("SELECT Vysledek, Timestamp FROM geocodes WHERE Nazev='$nazev';");
        $timestamp=time();
        if(mysqli_num_rows($ok)<1){//nic to nenašlo, jdeme googlovat
            loguj("Nic jsem nenašel");
            $vystup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$nazev&key=$apiKey");
            //echo $vystup;
            dotaz("INSERT INTO geocodes VALUES ('$nazev','$vystup','$timestamp');");
        }else{
            loguj("Našel jsem. zjišťuji stáří...");
            $vysledky=mysqli_fetch_array($ok);
            $stari=time()-$vysledky[2];
            //if($stari<$maxAge){
                loguj("stáří $stari je v pořádku");
                $vystup=$vysledky[0];
            /*}
            else{
                loguj("záznam je příliš starý - $stari, rekurzivně aktualizuji");
                dotaz("DELETE FROM geocodes WHERE Nazev='$nazev'");
                $vysledek=geocode($nazev);
                loguj("rekurze úspěšně ukončena");
            }*/
        }
        //echo "$vystup<br />";
        /*echo "$soubor <br />";
        $fp=fopen($soubor,"r");
        //$vystup=fread($fp,filesize($soubor));
        $vystup="";
        while(!feof($fp)){
            $vystup+=fgets($fp);
        }
        loguj("vysledkem je $vystup");*/
        loguj("vystupem geokódování je: $vystup");
        return $vystup;
    }

    function distance($lat1,$lon1,$lat2,$lon2){
        
        $R=6378;
        $dLat=deg2rad($lat1-$lat2);
        $dLon=deg2rad($lon1-$lon2);
        $lat1=deg2rad($lat1);
        $lat2=deg2rad($lat2);
        
        $a=sin($dLat/2)*sin($dLat/2)+sin($dLon/2)*sin($dLon/2)*cos($lat1)*cos($lat2);
        $c=2*atan2(sqrt(a),sqrt(1-a));
        
        return $R*$c;
    }

    function matchni($id){
        $kdo=mysqli_fetch_array(dotaz("SELECT Destinace, Bydliste, Cinnost, Vek WHERE Id=$id;"));
        $vysledek=dotaz("SELECT Destinace, Bydliste, Cinnost, Vek WHERE Id!=$id;");
        $kandidatu=mysqli_fet
        return $match;    
    }

    function loguj($zapis){
        //echo "$zapis<br />";
        //chtělo by to zapisovat do csvčka
        $timestamp=microtime();
        $fp=fopen("log.html","a");
        fwrite($fp, "$timestamp: $zapis </br>");
        fclose($fp);
    }

    function poslimail($komu, $predmet, $zprava ){
        $from = '<team@dametrip.cz>'; //change this to your email address
        $to = $komu; // change to address
        $subject = $predmet; // subject of mail
        $body = $zprava; //content of mail

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject
        );

        $smtp = Mail::factory('smtp', array(
                'host' => 'smtp-148986.m86.wedos.net',
                'port' => '465',
                'auth' => true,
                'username' => 'team@dametrip.cz', //your gmail account
                'password' => 'Barbucha26' // your password
            ));

        // Send the mail
        $mail = $smtp->send($to, $headers, $body);
    }

?>