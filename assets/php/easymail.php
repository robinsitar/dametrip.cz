<html>
    <?php
        include "databaze.php";
        $random=rand();
    ?>
    <body>
        <form method="post">
            <input name="Komu" /> E-mail příjemce<br />
            <input name="Od" /> E-mail odesílatele<br />
            <input name="Predmet" /> Předmět<br />
            Obsah zprávy:<br />
            <textarea name="Text"></textarea><br />
            <input type="submit" value="odeslat" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["Komu"])&&isset($_POST["Od"])){
                $ok=easymail($_POST["Komu"],$_POST["Od"],$_POST["Predmet"],$_POST["Text"]);
                if($ok){echo "OK - Mail odeslán";}else{echo "ERROR - Mail neodeslán, někde se stala chyba";}
            }
        ?>
    </body>
</html>