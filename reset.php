<html>
    <?php
    include "assets/php/databaze.php";
    if(isset($_REQUEST["cudlik"])){
        if($_REQUEST["heslo"]=="heslojeheslo"){
            inicializovat();
        }else{
            echo "špatné heslo";
            loguj("někdo se snažil resetnout databázi, zadal chybné heslo");
        }
    }
    ?>
    <body>
        <input name="heslo" />
        <input name="cudlik" type="submit" value="resetovat databázi" />
    </body>
</html>