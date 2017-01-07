<html>
    <body>
        <?php
            include "assets/php/databaze.php";
            
            if(isset($_POST["Jmeno"]) && $_POST["Vek"]) &&$_POST["Email"]) &&$_POST["Bydliste"]) &&$_POST["Cinnost"]) &&$_POST["Destinace"])){
                $ok=pridej($_POST["Jmeno"],$_POST["Vek"],$_POST["Email"],$_POST["Bydliste"],$_POST["Cinnost"],$_REQUEST["Destinace"]);
                if($ok){echo "Super! Byl jste přidán do databáze. Pro aktivaci prosím potvďte váš email...";}
            }
        ?>
    </body>
</html>