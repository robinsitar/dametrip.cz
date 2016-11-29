<html>
    <body>
    <?php
        if(isset($_REQUEST["kod"])){
            $kod=$_REQUEST["kod"];
            if($kod=="" or $kod*1 !=$kod){loguj("někdo se snaží nabourat databázi...."); return false;}
            $vysledek=dotaz("SELECT Id FROM lidi WHERE Kod=$kod");
            if(mysqli_num_rows($vysledek)>0){
                $ok=dotaz("UPDATE lidi SET Validovano=1 WHERE Kod='$kod'")
            }
        }
        if($ok){echo "váš email byl úspěšně ověřen!";}else{echo "někde se stala chyba";}
    ?>
    </body>
</html>