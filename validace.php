<html>
    <body>
    <?php
        include "assets/php/databaze.php";
        if(isset($_REQUEST["kod"])){
            $kod=safeString($_REQUEST["kod"]);
            $ok=validuj($kod);
        }
        if($ok)
        {
            echo "váš email byl úspěšně ověřen!";
            $ja=mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Destinace, Bydliste, Email, Vek FROM lidi WHERE Kod=$kod"));
            $partak=matchni($ja[0]);
            if($partak && $partak[9]<=$range){
                //v tomhle bodě se našel vhodný match a měl by se obou odeslat mail alá "nazdar, našli jsme vám strašně super parťáka" apod...
                $partak=mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Destinace, Bydliste, Email, Vek FROM lidi WHERE Id=".$partak[4].";"));
                posliMail($ja[4],"Dámetrip.cz - Našli jsme ti parťáka!","Ahoj ".$ja[1].",\nNašli jsme ti parťáka. Jmenuje se ".$partak[1].", je z ".geo2name($partak[3]).", je mu ".$partak[5]." a chce jet do ".$partak[2].". \nNapiš mu na ".$partak[4]." a vyražte spolu na super trip!");
                
                posliMail($partak[4],"Dámetrip.cz - Našli jsme ti parťáka!","Ahoj ".$partak[1].",\nNašli jsme ti parťáka. Jmenuje se ".$ja[1].", je z ".geo2name($ja[3]).", je mu ".$ja[5]." a chce jet do ".$ja[2].". \nNapiš mu na ".$ja[4]." a vyražte spolu na super trip!");
                
                smaz($ja[0]);
                smaz($partak[0]);
                
                
            }else{
                //vhodného parťáka se najít nepodařilo... v podstatě teď tomuto uživateli nezbývá nic jiného, než čekat, až někdo pojede poblíž a vybere si ho jako parťáka
                posliMail($ja[4],"Dámetrip.cz - Registrace dokončena!","Ahoj ".$ja[1].",\nVítej v Dámetripu! Co nejdřívě ti najdeme super parťáka... Pak se ti ozveme na tento email.\n Zdraví kluci z Dámetripu");
            }
            
        }else{
            echo "někde se stala chyba";
        }
    ?>
    </body>
</html>