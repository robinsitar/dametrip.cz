<html>
    <body>
        <?php
            include "assets/php/databaze.php";
        
            if(isset($_REQUEST["cudlik"])){
                if($_REQUEST["heslo"]=="heslojeheslo"){
                    inicializovat();
                    echo "resetováno";
                }else{
                    echo "špatné heslo";
                    loguj("někdo se snažil resetnout databázi, zadal chybné heslo");
                }
            }
        ?>
        <form>
            <input name="heslo" />
            <input name="cudlik" type="submit" value="resetovat databázi" />
        </form>
    </body>
</html>