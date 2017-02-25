<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post">
            <input name="name" /> Předmět<br />
            <input name="email" /> Komu<br />
            Obsah zprávy:<br />
            <textarea name="message"></textarea><br />
            <input type="submit" value="odeslat" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["email"])){
                $ok=poslimail($_POST["email"],$_POST["name"],$_POST["message"]);
                if($ok){echo "OK - Mail odeslán";}else{echo "ERROR - Mail neodeslán, někde se stala chyba";}
            }
        ?>
    </body>
</html>