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
                Kod TEXT,
                Timestamp INT);";
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
        $timestamp=time();
        $ok=dotaz("INSERT INTO lidi VALUES($nextId,'$Jmeno',$Vek,'$Email','$Bydliste','$Cinnost','$Destinace',0,$kod,$timestamp)");

        if($ok){
            posliMail($Email,"Dámetrip.cz - Potvrzení emailové adresy","http://beta.dametrip.cz/validace.php?kod=$kod");
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
        $timestamp=time();
        $ok=dotaz("UPDATE lidi SET Jmeno='$Jmeno', Vek='$Vek', Email='$Email', Pohlavi='$Pohlavi', Bydliste='$Bydliste', Cinnost='$Cinnost', Destinace='$Destinace', Validovano=$Validovano, Kod='$Kod', Timestamp='$timestamp' WHERE Id='$id';");
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

    function vzdalenost($lat1=99,$lon1=99,$lat2=99,$lon2=99){

        loguj("počítám vzdálenost mezi $lat1, $lon1 a $lat2,$lon2");
        $R=6378;
        $dLat=deg2rad($lat1-$lat2);
        $dLon=deg2rad($lon1-$lon2);
        $lat1=deg2rad($lat1);
        $lat2=deg2rad($lat2);

        $a=sin($dLat/2)*sin($dLat/2)+sin($dLon/2)*sin($dLon/2)*cos($lat1)*cos($lat2);
        $c=2*atan2(sqrt(a),sqrt(1-a));

        //return rand(1,100);
        return $R*$c;
    }

    function matchni($id){ //zatím na základě vzdáleností destinací a bydlišť bez vah
        $iDestinace=1;
        $iBydliste=1;
        $iCinnost=1; //zatím moc nefunguje
        $iVek=1;


        $ja=mysqli_fetch_array(dotaz("SELECT Destinace, Bydliste, Cinnost, Vek, Id FROM lidi WHERE Id='$id';"));
        $vysledek=dotaz("SELECT Destinace, Bydliste, Cinnost, Vek, Id FROM lidi WHERE Id!='$id' and Validovano='1';");
        $kandidatu=mysqli_num_rows($vysledek);
        $min=100000000000000;//nahradit něčím jako float.max v C#
        for($x=0; $x<$kandidatu; $x++){
            $kandidat=mysqli_fetch_array($vysledek);
            $loguj("fetchuju x $x -> výsledkem je $kandidat");
            //echo json_decode($kandidati[$x][0])->results[0]->geometry->location->lat;
            $latJa=json_decode($ja[0])->results[0]->geometry->location->lat; //tohle funguje
            $lonJa=json_decode($ja[0])->results[0]->geometry->location->lng;
            $latKandidat=json_decode($kandidati[0])->results[0]->geometry->location->lat;
            $lonKandidat=json_decode($kandidati[0])->results[0]->geometry->location->lng;
            echo $latKandidat;
            $kandidat[5]=vzdalenost($latKandidat,$lonKandidat,$latJa,$lonJa); //vzájemná  vzdálesnost destinací
            $latJa=json_decode($ja[1])->results[0]->geometry->location->lat;
            $lonJa=json_decode($ja[1])->results[0]->geometry->location->lng;
            $latKandidat=json_decode($kandidat[1])->results[0]->geometry->location->lat;
            $lonKandidat=json_decode($kandidat[1])->results[0]->geometry->location->lng;
            $kandidat[6]=vzdalenost($latKandidat,$lonKandidat,$latJa,$lonJa);; //vzájemná vzdálesnost bydlišť
            if($kandidat[2]==$ja[2]){$kandidat[7]=1;}else{$kandidat[7]=0;} //shodují se aktivity?
            $kandidat[8]=abs($kandidat[3]-$ja[3]); //rozdíl věku
            if($kandidat[5]+$kandidat[6]<$min){$min=$kandidat[5]+$kandidat[6]; $match=$kandidat;}
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
        */
    }

    function loguj($zapis){
        //echo "$zapis<br />";
        //chtělo by to zapisovat do csvčka
        $timestamp=time();
        $fp=fopen("log.html","a");
        fwrite($fp, "$timestamp: $zapis <hr/>");
        fclose($fp);
    }

    function poslimail($komu, $predmet, $zprava ){
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

?>
