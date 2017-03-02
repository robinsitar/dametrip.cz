<?php

    //include "hesla.php";
    $mysqlLogin="a148986_2";
    $mysqlHeslo="HESLO_SEM";
    $mysqlDatabase="d148986_2";
    $mysqlServer="wm133.wedos.net";
    $apiKey="AIzaSyBCJLPKH2GQ-uGV_F6B6gvVweFO_MQrbNQ";
    $range=300; //prozatím kilometry, pozor, až se do toho začne mixovat nějaké další parametry alá delta věk, shodnost aktivit, tak už to bude spíš takovej index
    //TODO:
    //uživatel musí mít
        //mezeru ve jméně
        //zavináč a tečku v mailu
        //věk mezi 1 - 120
        //do budoucna jednu z povolených aktivit

    function inicializovat(){ //vyčistění databáze
        loguj("byla zavolana funkce inicializovat() - RESETUJE VŠECHNY TABULKY, kromě geocache a logu",100,"Reset");
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
                Kod TEXT,
                Timestamp INT,
                Aktivni INT);";
        $ok1=dotaz($dotaz);
        
        /*
        $dotaz="DROP TABLE log;";
        dotaz($dotaz);
        $dotaz="CREATE TABLE log(
                Timestamp TEXT,
                Zprava TEXT,
                Dulezitost INT,
                Typ TEXT);";
        $ok2=dotaz($dotaz);
        */

        /*$dotaz="DROP TABLE geocodes;";
        dotaz($dotaz);
        $dotaz="CREATE TABLE geocodes(
                Nazev TEXT,
                Vysledek TEXT,
                Timestamp INT);";
        $ok2=dotaz($dotaz);*/
        if($ok1){
            return true;
        }else{
            return false;
        }

    }

    function pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace,$predaktivovat=false){ //přidá uživatele do databáze
        loguj("byla zavolana funkce pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace)",5,"PridaniUzivatele");
        $nextId=rand(0,999999999);
        $kod=rand(11111111,99999999);
        $Bydliste=geocode($Bydliste);
        $Destinace=geocode($Destinace);
        $timestamp=time();
        if(json_decode($Bydliste)->status =="ZERO_RESULTS"){
            return "bydliste_nenalezeno";
        }
        if(json_decode($Destinace)->status =="ZERO_RESULTS"){
            return "destinace_nenalezena";
        }
        if(mysqli_num_rows(dotaz("SELECT * FROM lidi WHERE Email='$Email';"))>0){
            loguj("při přidávání uživatele nastala duplicita emailů");
            return "duplicita_emailu";
        }
        $ok=dotaz("INSERT INTO lidi VALUES($nextId,'$Jmeno',$Vek,'$Email','$Bydliste','$Cinnost','$Destinace',0,$kod,$timestamp,0)");

        if($ok){
            if($predaktivovat==false){
                posliMail($Email,"Dámetrip.cz - Potvrzení emailové adresy","http://dametrip.cz/validace.php?kod=$kod");    
            }
            else{
                validuj($kod);
            }
            return true;
        }else{
            return "false";
        }

    }

    function smaz($id){
        loguj("byla zavolana funkce smaz($id)",5,"SmazaniUzivatele");
        
        if($id=="" or !$id){return false;}
        $ok=dotaz("DELETE FROM lidi WHERE Id='$id';");
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $Bydliste, $Cinnost, $Destinace, $Validovano, $Kod, $aktivni){
        loguj("byla zavolana funkce uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $Bydliste, $Cinnost, $Destinace, $Validovano, $Kod, $aktivni)",5,"UpravaUzivatele");
        
        if($id=="" or !$id){return false;}
        $Bydliste=geocode($Bydliste);
        $Destinace=geocode($Destinace);
        $timestamp=time();
        $ok=dotaz("UPDATE lidi SET Jmeno='$Jmeno', Vek='$Vek', Email='$Email', Pohlavi='$Pohlavi', Bydliste='$Bydliste', Cinnost='$Cinnost', Destinace='$Destinace', Validovano=$Validovano, Kod='$Kod', Timestamp='$timestamp', Aktivni='$aktivni' WHERE Id='$id';");
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function validuj($kod){
        loguj("byla zavolana funkce validuj($kod)",1,"Funkce");
        
        if(mysqli_num_rows(dotaz("SELECT Validovano FROM lidi WHERE Kod=$kod and Validovano=0"))==1){
            $ok=dotaz("UPDATE lidi SET Validovano=1, Aktivni=1 WHERE Kod=$kod");
            if($ok){
                return true;
            }else{
                return false;
            }    
        }else{
            loguj("Uživatel už zvalidován");
            return false;
        }        
    }

    function dotaz($dotaz){
        loguj("byla zavolana funkce dotaz($dotaz)",2,"Dotaz");
        
        global $link;

        if(!$link){prihlasit();}
        mysqli_set_charset($link, "utf8");
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
        loguj("byla zavolana funkce prihlasit()",1,"Funkce");
        
        global $mysqlLogin, $mysqlHeslo, $mysqlServer, $mysqlDatabase, $link;

        $link=mysqli_connect($mysqlServer,$mysqlLogin, $mysqlHeslo);
        $ok=mysqli_select_db($link, $mysqlDatabase);
        return $link;
    }

    function geocode($nazev){
        loguj("byla zavolana funkce geocode($nazev)",1,"Funkce");
        
        global $apiKey;

        loguj("Geocoduju $nazev");
        $nazev=str_replace(" ","+",$nazev); //snad tam nebudou dvojité mezery... ty by to neměly rozbít, ale stejně...
        loguj("hledam v $nazev v cache tabulce");
        $ok=dotaz("SELECT Vysledek, Timestamp FROM geocodes WHERE Nazev='$nazev';");
        $timestamp=time();
        if(mysqli_num_rows($ok)==0){//nic to nenašlo, jdeme googlovat
            loguj("V cache tabulce nic není");
            $vystup=file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$nazev&key=$apiKey");
            //echo $vystup;
            dotaz("INSERT INTO geocodes VALUES ('$nazev','$vystup','$timestamp');");
        }else{
            loguj("Našel jsem.");
            $vysledky=mysqli_fetch_array($ok);
            $vystup=$vysledky[0];
        }
        /*if($vystup->status=="OK"){
            loguj("vystupem geokódování je: $vystup");
            return $vystup;    
        }else{
            loguj("při geokódování nastala chyba: '".$vystup->status."'");
            return false;
        }*/
        return $vystup;
    }

    function geo2lat($geo){
        loguj("byla zavolana funkce geo2lat($geo)",1,"Funkce");
        
        return json_decode($geo)->results[0]->geometry->location->lat;
    }

    function geo2lon($geo){
        loguj("byla zavolana funkce geo2lon($geo)",1,"Funkce");
        
        return json_decode($geo)->results[0]->geometry->location->lng;
    }

    function geo2name($geo){
        loguj("byla zavolana funkce geo2name($geo)",1,"Funkce");
        
        return json_decode($geo)->results[0]->formatted_address;
    }

    function geo2human($geo){
        loguj("byla zavolana funkce geo2human($geo)",1,"Funkce");        
        
        return str_replace("+"," ",mysqli_fetch_array(dotaz("SELECT Nazev FROM geocodes WHERE Vysledek='$geo';"))[0]);
    }

    function vzdalenost($lat1,$lon1,$lat2,$lon2){
        loguj("byla zavolana funkce vzdalenost($lat1,$lon1,$lat2,$lon2)",1,"Funkce");
    
        $R=6378;
        $lat2=deg2rad($lat2);
        $lat1=deg2rad($lat1);
        $dLat=deg2rad($lat1-$lat2);
        $dLon=deg2rad($lon1-$lon2);

        $a=sin($dLat/2)*sin($dLat/2)+sin($dLon/2)*sin($dLon/2)*cos($lat1)*cos($lat2);
        $c=2*atan2(sqrt($a),sqrt(1-$a));

        //return rand(1,100);
        $vzdalenost=$R*$c;
        return $vzdalenost;
    }

    function vzdalenost2($lat1, $lon1, $lat2, $lon2){  //smazat tuhle funkci, nebo vzdalenost. Jedna z nich nefunguje
        loguj("byla zavolana funkce vzdalenost2($lat1,$lon1,$lat2,$lon2)",1,"Funkce");
        
        $R=6371;
        $dLat=deg2rad($lat2-$lat1);
        $dLon=deg2rad($lon2-$lon1);
        $lat1=deg2rad($lat1);
        $lat2=deg2rad($lat2);
        
        $a=sin($dLat/2)*sin($dLat/2)+sin($dLon/2)*sin($dLon/2)*cos($lat1)*cos($lat2);
        $c=2*atan2(sqrt($a),sqrt(1-$a));
        $d=$R*$c;
        
        loguj("Vzdálenost je $vzdalenost");
        return $d;
    }

    function matchni($id){ //zatím na základě součtu vzdáleností destinací a bydlišť bez vah
        loguj("byla zavolana funkce matchni($id)",4,"Matchovani");
        
        $iDestinace=1;
        $iBydliste=0;
        $iCinnost=0; //zatím moc nefunguje
        $iVek=0;


        $ja=mysqli_fetch_array(dotaz("SELECT Destinace, Bydliste, Cinnost, Vek, Id FROM lidi WHERE Id='$id';"));
        $vysledek=dotaz("SELECT Destinace, Bydliste, Cinnost, Vek, Id FROM lidi WHERE Id!='$id' and Validovano='1' and Aktivni='1';");
        $kandidatu=mysqli_num_rows($vysledek);
        $min=100000000000000;//nahradit něčím jako float.max v C#
        for($x=0; $x<$kandidatu; $x++){
            loguj("Zvažuji kandidáta č. $x", 1);
            
            $kandidat=mysqli_fetch_array($vysledek);
            $latJa=geo2lat($ja[0]);
            $lonJa=geo2lon($ja[0]);
            $latKandidat=geo2lat($kandidat[0]);
            $lonKandidat=geo2lon($kandidat[0]);
            $kandidat[5]=vzdalenost($latKandidat,$lonKandidat,$latJa,$lonJa); //vzájemná  vzdálesnost destinací
            $latJa=geo2lat($ja[1]);
            $lonJa=geo2lon($ja[1]);
            $latKandidat=geo2lat($kandidat[1]);
            $lonKandidat=geo2lon($kandidat[1]);
            $kandidat[6]=vzdalenost($latKandidat,$lonKandidat,$latJa,$lonJa);; //vzájemná vzdálesnost bydlišť
            if($kandidat[2]==$ja[2]){$kandidat[7]=0;}else{$kandidat[7]=1;} //shodují se aktivity? 0 znamená shodu
            $kandidat[8]=abs($kandidat[3]-$ja[3]); //rozdíl věku
            $kandidat[9]=$kandidat[5]*$iDestinace+$kandidat[6]*$iBydliste+$kandidat[7]*$iCinnost+$kandidat[8]*$iVek;
            if($kandidat[9]<$min){$min=$kandidat[9]; $match=$kandidat;}
        }

        return $match;
        /*
        $match[0]...destinace
        $match[1]...bydliště
        $match[2]...činnost
        $match[3]...věk
        $match[4]...Id
        $match[5]...vzájemná vzdálenost destinací
        $match[6]...vzájemná vzdálenost bydlišť
        $match[7]...stejná aktivita? 1/0
        $match[8]...rozdíl věku
        $match[9]...index super parťáka - nižší je lepší, index 0 jsou klonové.
        */
    }

    function loguj($zprava,$dulezitost=1,$typ="nespecifikovano"){
        global $mysqlLogin, $mysqlHeslo, $mysqlServer, $mysqlDatabase, $link;
        
        $timestamp=microtime(true);
        
        if(15<$dulezitost){
            //posliMail("hajnina11@gmail.com","Dámetrip - Critical log event","$timestamp: '$zprava'. typ: $typ. Důležitost: $dulezitost'");
            $hlavicka = "MIME-Version: 1.0" . "\r\n";
            $hlavicka .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $hlavicka .= 'From: <systemlog@dametrip.cz>' . "\r\n";
            $ok=mail("hajnina11@gmail.com","Dámetrip - Critical log event","$timestamp: '$zprava'. typ: $typ. Důležitost: $dulezitost'",$hlavicka);
        }
        
        if(!$link){
            $link=mysqli_connect($mysqlServer,$mysqlLogin, $mysqlHeslo);
            $ok=mysqli_select_db($link, $mysqlDatabase);
        }
        
        $zprava=mysqli_real_escape_string($link,$zprava);
        $dulezitost=mysqli_real_escape_string($link,$dulezitost);
        $typ=mysqli_real_escape_string($link,$typ);
        
        $dotaz="INSERT INTO log (Timestamp, Zprava, Dulezitost, Typ) VALUES ('$timestamp', '$zprava', $dulezitost, '$typ');";
        mysqli_query($link,$dotaz);
    }

    function posliMail($komu, $predmet, $zprava, $odesilatel="team@dametrip.cz"){
        loguj("byla zavolana funkce poslimail($komu, $predmet, $zprava)",5,"Mail");
        
        $hlavicka = "MIME-Version: 1.0" . "\r\n";
        $hlavicka .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $hlavicka .= 'From: <'.$odesilatel.'>' . "\r\n";
        
        
        $ok=mail($komu,$predmet,$zprava,$hlavicka);
        if($ok){
            return true;
        }else{
            return false;
        }
    }

function pridejZCSV($soubor){ //ve formátu 0 Jméno, 1 Bydliště, 2 Destinace, 3 Email
    //$Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace - pořadí parametrů v přidávací funkci
    loguj("byla zavolana funkce pridejZCSV($soubor)",10,"Funkce");
    $fp=fopen($soubor,"r");
    $i=0;
    while (true){
        $clovek=fgets($fp);
        if($clovek!=""){
            $lidi[$i]=explode(",",$clovek);        
            pridej($lidi[$i][0], $lidi[$i][1], $lidi[$i][2], $lidi[$i][3], $lidi[$i][4], $lidi[$i][5],true);    
            $ok=dotaz("UPDATE lidi SET Validovano=0, Aktivni=0 WHERE Email='".$lidi[$i][3]."';");
            $i++;
        }
        else{
            break;
        }
    }
    vypisTabulku($lidi);
    return $lidi;
}

//možná udělat export databáze do CSV

function safeString($text){ //prostě odebrat uvozovky, středníky a podobné zbytečnosti, stejně nejsou potřeba...
    loguj("byla zavolana funkce safeString($text)",1,"Funkce"); //zabezpečit logovací funkci!!!!!!!!!!!!§
    
    global $link;
    
    $text=str_replace(";","",$text);
    $text=str_replace("'","",$text);
    $text=str_replace('"',"",$text);
    $text=str_replace("&","",$text);
    $text=str_replace("|","",$text);
    $text=str_replace("*","",$text);
    $text=str_replace("?","",$text);
    $text=str_replace("_","",$text);
    $text=str_replace("=","",$text);
    $text=str_replace("<","",$text);
    $text=str_replace(">","",$text);
    
    
    if(!$link){$link=prihlasit();}
    $text=mysqli_real_escape_string($link,$text);
    return $text;
    
}

function vypisTabulku($tabulka){
    loguj("byla zavolana funkce vypisTabulku($tabulka)",1,"Funkce");
    
    echo "<table border='solid'>";
    for($y=0; $y<sizeof($tabulka); $y++){
        echo "<tr>";
        for($x=0; $x<sizeof($tabulka[$y]); $x++){
            echo "<td>".$tabulka[$y][$x]."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

dotaz("SET SESSION CHARACTER_SET_RESULTS=utf-8;");
dotaz("SET SESSION CHARACTER_SET_CLIENT=utf-8;");

?>
