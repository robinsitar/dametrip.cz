<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post" target="https://beta.dametrip.cz/contact.php">
            <input name="name" /> jméno<br />
            <input name="mail" /> váš mail<br />
            Obsah zprávy:<br />
            <textarea name="message"></textarea><br />
            <input type="submit" value="odeslat" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["Komu"])){
                $ok=poslimail($_POST["Komu"],$_POST["Predmet"],$_POST["Text"]);
                if($ok){echo "OK - Mail odeslán";}else{echo "ERROR - Mail neodeslán, někde se stala chyba";}
            }
        ?>
    </body>
</html>