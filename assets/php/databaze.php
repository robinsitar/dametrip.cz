<?php
    //TODO:
    //udělat nějakou zabezpečovací funkci, kterou se budou prohánět všechny user inputy.
    //automatchování
    //validace
        //mezera ve jméně

    $mysqlLogin="a148986_2";
    $mysqlHeslo="FmVW6LUj";
    $mysqlDatabase="d148986_2";
    $mysqlServer="wm133.wedos.net";
    $apiKey="AIzaSyBCJLPKH2GQ-uGV_F6B6gvVweFO_MQrbNQ";

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
                Bydliste TEXT,
                Cinnost TEXT,
                Destinace TEXT,
                Validovano INT,
                Kod TEXT);";
        $ok=dotaz($dotaz);
        if($ok){
            return true;
        }else{
            return false;
        }
        
    }
    
    function pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace){ //přidá uživatele do databáze
        loguj("Přidávám nového uživatele do databáze....");
        $vysledek=dotaz("SELECT Id FROM lidi ORDER BY Id DESC;");
        $nextId=mysqli_fetch_array($vysledek)[0]+1;
        $kod=rand();
        $Bydliste=str_replace(" ","+",$Bydliste);
        $Destinace=str_replace(" ","+",$Destinace);
        $Bydliste=file_get_contents("https://maps.googleapis.com/maps/api/geocode/xml?address=$Bydliste&key=AIzaSyBCJLPKH2GQ-uGV_F6B6gvVweFO_MQrbNQ");
        $Destinace=file_get_contents("https://maps.googleapis.com/maps/api/geocode/xml?address=$Destinace&key=AIzaSyBCJLPKH2GQ-uGV_F6B6gvVweFO_MQrbNQ");
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
        $ok=dotaz("DELETE FROM lidi WHERE Id=$id;");    
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $Bydliste, $Cinnost, $Destinace, $Validovano, $Kod){
        if($id=="" or !$id){return false;}
        $ok=dotaz("UPDATE lidi SET Jmeno='$Jmeno', Vek='$Vek', Email='$Email', Pohlavi='$Pohlavi', Bydliste='$Bydliste', Cinnost='$Cinnost', Destinace='$Destinace', Validovano=$Validovano, Kod='$Kod';");
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

    function geocode($vstup){
        global $apiKey;
        loguj("Geocoduju $vstup");
        $vstup=str_replace(" ","+",$vstup);
        $soubor="https://maps.googleapis.com/maps/api/geocode/xml?address=$vstup&key=$apiKey";
        /*echo "$soubor <br />";
        $fp=fopen($soubor,"r");
        //$vystup=fread($fp,filesize($soubor));
        $vystup="";
        while(!feof($fp)){
            $vystup+=fgets($fp);
        }
        loguj("vysledkem je $vystup");*/
        $vystup=simplexml_load_file($soubor);
        return $vystup;
    }

    function loguj($zapis){
        //echo "$zapis<br />";
        //chtělo by to zapisovat do csvčka
        $fp=fopen("log.txt","a");
        fwrite($fp, "$zapis \n");
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

    function easymail($odesilatel, $prijemce, $predmet, $obsah){
        if(mail($prijemce, $predmet, $obsah, $odesilatel)){
            loguj("odeslán email na adresu $prijemce od $odesilatel s předmětem $predmet a textem $obsah");
        }
        else{
            loguj("ERROR - nepodařilo se odeslat mail na adresu $prijemce od $odesilatel s předmětem $predmet a textem $obsah");
        }
    }


?>