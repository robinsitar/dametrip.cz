<html>
    <?php
        include "databaze.php";
        $random=rand();
    ?>
    <body>
        <form method="post">
            <input name="Vyraz" /> Adresa<br />
            <input type="submit" value="Vyhodnoť" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["Vyraz"])){
                $vysledek=geocode($_POST["Vyraz"]);
                echo "lat: ";
                echo $lat=$vysledek->result->geometry->location->lat;
                echo "<br />lon: ";
                echo $lng=$vysledek->result->geometry->location->lng;
                echo "<hr /><img src='https://maps.googleapis.com/maps/api/staticmap?center=$lat,$lng&zoom=10&size=640x640&key=$apiKey'";
            }
        ?>
    </body>
</html>