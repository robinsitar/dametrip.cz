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
            $id=mysqli_fetch_array(dotaz("SELECT Id WHERE Kod=$kod"))[0];
            $partak=matchni($id);
            if($partak[9]<=$range){
                //v tomhle bodě se našel vhodný match a měl by se obou odeslat mail alá "nazdar, našli jsme vám strašně super parťáka" apod...
            }else{
                //vhodného parťáka se najít nepodařilo... v podstatě teď tomuto uživateli nezbývá nic jiného, než čekat, až někdo pojede poblíž a vybere si ho jako parťáka
            }
            
        }else{
            echo "někde se stala chyba";
        }
    ?>
    </body>
</html>