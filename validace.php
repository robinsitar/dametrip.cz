<html>
    <body>
    <?php
        include "assets/php/databaze.php";
        if(isset($_REQUEST["kod"])){
            $kod=$_REQUEST["kod"];
            $ok=dotaz("UPDATE lidi SET Validovano=1 WHERE Kod='$kod';");
        }
        if($ok){echo "váš email byl úspěšně ověřen!";}else{echo "někde se stala chyba";}
    ?>
    </body>
</html>