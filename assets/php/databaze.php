<?php
    //TODO:
    //udělat nějakou zabezpečovací funkci, kterou se budou prohánět všechny user inputy.
    //automatchování
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
        loguj("byla zavolana funkce inicializovat() - RESETUJE VŠECHNY TABULKY, kromě geocache");
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
                Timestamp INT);";
        $ok1=dotaz($dotaz);

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

    function pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace){ //přidá uživatele do databáze
        loguj("byla zavolana funkce pridej($Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace)");
        $nextId=rand(0,999999999);
        $kod=rand(11111111,99999999);
        $Bydliste=geocode($Bydliste);
        $Destinace=geocode($Destinace);
        $timestamp=time();
        if(json_decode($Bydliste)->status =="ZERO_RESULTS" || json_decode($Destinace)->status =="ZERO_RESULTS"){
            return false;
        }
        $ok=dotaz("INSERT INTO lidi VALUES($nextId,'$Jmeno',$Vek,'$Email','$Bydliste','$Cinnost','$Destinace',0,$kod,$timestamp)");

        if($ok){
            posliMail($Email,"Dámetrip.cz - Potvrzení emailové adresy","http://beta.dametrip.cz/validace.php?kod=$kod");
            return true;
        }else{
            return false;
        }

    }

    function smaz($id){
        loguj("byla zavolana funkce smaz($id)");
        
        if($id=="" or !$id){return false;}
        $ok=dotaz("DELETE FROM lidi WHERE Id='$id';");
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $Bydliste, $Cinnost, $Destinace, $Validovano, $Kod){
        loguj("byla zavolana funkce uprav($id, $Jmeno, $Vek, $Email,$Pohlavi, $Bydliste, $Cinnost, $Destinace, $Validovano, $Kod)");
        
        if($id=="" or !$id){return false;}
        $Bydliste=geocode($Bydliste);
        $Destinace=geocode($Destinace);
        $timestamp=time();
        $ok=dotaz("UPDATE lidi SET Jmeno='$Jmeno', Vek='$Vek', Email='$Email', Pohlavi='$Pohlavi', Bydliste='$Bydliste', Cinnost='$Cinnost', Destinace='$Destinace', Validovano=$Validovano, Kod='$Kod', Timestamp='$timestamp' WHERE Id='$id';");
        if($ok){
            return true;
        }else{
            return false;
        }
    }

    function dotaz($dotaz){
        loguj("byla zavolana funkce dotaz($dotaz)");
        
        global $link;

        if(!$link){prihlasit();}
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
        loguj("byla zavolana funkce prihlasit()");
        
        global $mysqlLogin, $mysqlHeslo, $mysqlServer, $mysqlDatabase, $link;

        $link=mysqli_connect($mysqlServer,$mysqlLogin, $mysqlHeslo);
        $ok=mysqli_select_db($link, $mysqlDatabase);
        return $link;
    }

    function geocode($nazev){
        loguj("byla zavolana funkce geocode($nazev)");
        
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
        loguj("byla zavolana funkce geo2lat($geo)");
        
        return json_decode($geo)->results[0]->geometry->location->lat;
    }

    function geo2lon($geo){
        loguj("byla zavolana funkce geo2lon($geo)");
        
        return json_decode($geo)->results[0]->geometry->location->lng;
    }

    function geo2name($geo){
        loguj("byla zavolana funkce geo2name($geo)");
        
        return json_decode($geo)->results[0]->formatted_address;
    }

    function vzdalenost($lat1,$lon1,$lat2,$lon2){
        loguj("byla zavolana funkce vzdalenost($lat1,$lon1,$lat2,$lon2)");
    
        $R=6378;
        $lat2=deg2rad($lat2);
        $lat1=deg2rad($lat1);
        $dLat=deg2rad($lat1-$lat2);
        $dLon=deg2rad($lon1-$lon2);

        $a=sin($dLat/2)*sin($dLat/2)+sin($dLon/2)*sin($dLon/2)*cos($lat1)*cos($lat2);
        $c=2*atan2(sqrt($a),sqrt(1-$a));

        //return rand(1,100);
        $vzdalenost=$R*$c;
        loguj("Vzdálenost je $vzdalenost Km");
        return $vzdalenost;
    }

    function vzdalenost2($lat1, $lon1, $lat2, $lon2){
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
        loguj("byla zavolana funkce matchni($id)");
        
        $iDestinace=1;
        $iBydliste=0;
        $iCinnost=0; //zatím moc nefunguje
        $iVek=0;


        $ja=mysqli_fetch_array(dotaz("SELECT Destinace, Bydliste, Cinnost, Vek, Id FROM lidi WHERE Id='$id';"));
        $vysledek=dotaz("SELECT Destinace, Bydliste, Cinnost, Vek, Id FROM lidi WHERE Id!='$id' and Validovano='1';");
        $kandidatu=mysqli_num_rows($vysledek);
        $min=100000000000000;//nahradit něčím jako float.max v C#
        for($x=0; $x<$kandidatu; $x++){
            loguj("Zvažuji kandidáta č. $x");
            
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
            if($kandidat[2]==$ja[2]){$kandidat[7]=0;}else{$kandidat[7]=1;} //shodují se aktivity?
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

    function loguj($zapis){
        //echo "$zapis<br />";
        //chtělo by to zapisovat do csvčka
        $timestamp=microtime(true);
        $fp=fopen("log.html","a");
        fwrite($fp, "$timestamp: $zapis <hr/>");
        fclose($fp);
    }

    function poslimail($komu, $predmet, $zprava){
        loguj("byla zavolana funkce poslimail($komu, $predmet, $zprava )");
        
        echo "<p style='background-color: red; color: white;padding: 5px;'>Milý majiteli emailu $komu. v této kritické chvíli vám měl přijít email s předmětem '$predmet', který by vám pověděl následující: '$zprava'. To, že vám nepřišel, je nám srdečné líto. Až se boj se zaměstnaneckou disciplínou opět dostane pod kontrolu, budete od nás emaily dostávat zase tak, jak by měli. Martin z Dámetripu</p>";
        /*$from = '<team@dametrip.cz>'; //change this to your email address
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
        $mail = $smtp->send($to, $headers, $body);*/
    }

function pridejZCSV($soubor){ //ve formátu 0 Jméno, 1 Bydliště, 2 Destinace, 3 Email
    //$Jmeno, $Vek, $Email, $Bydliste, $Cinnost, $Destinace - pořadí parametrů v přidávací funkci
    loguj("byla zavolana funkce pridejZCSV($soubor)");
    $fp=fopen($soubor,"r");
    $i=0;
    while (true){
        $clovek=fgets($fp);
        if($clovek!=""){
            $lidi[$i]=explode(",",$clovek);        
        pridej($lidi[$i][0], 99, $lidi[$i][3], $lidi[$i][1], "nezadano", $lidi[$i][2]);    
        $ok=dotaz("UPDATE lidi SET Validovano=1 WHERE Email='".$lidi[$i][3]."';");
        $i++;
        }
        else{
            break;
        }
    }
    vypisTabulku($lidi);
    return $lidi;
}

function vypisTabulku($tabulka){
    loguj("byla zavolana funkce vypisTabulku($tabulka)");
    
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

?>
