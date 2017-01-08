<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post">
            <input name="Komu" /> E-mail příjemce<br />
            <input name="Predmet" /> Předmět<br />
            Obsah zprávy:<br />
            <textarea name="Text"></textarea><br />
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