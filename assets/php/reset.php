<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post">
            <input name="Heslo" type="password" /> Heslo<br />
            <input type="submit" value="Reset" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["Heslo"])){
                if($_POST["Heslo"]=="brambora1808"){
                    $ok=inicializovat();
                    if($ok){echo "OK - Resetováno";}
                    else{echo "ERROR - Někde se stala chyba. Zkuste to ještě jednou... pokud se to bude opakovat, kontaktujte prosím okamžitě správce databáze";}
                }
            }
        ?>
    </body>
</html>