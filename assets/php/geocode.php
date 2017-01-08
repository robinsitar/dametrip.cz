<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post">
            <input name="Vyraz" /> Adresa<br />
            <input type="submit" value="Vyhodnoť" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["Vyraz"])){
                $vystup=geocode($_POST["Vyraz"]);
                print_r($vystup);
                $vysledek=simplexml_load_string($vystup);
                echo "lat: ";
                echo $lat=$vysledek->result->geometry->location->lat;
                echo "<br />lon: ";
                echo $lng=$vysledek->result->geometry->location->lng;
                echo "<hr /><img src='https://maps.googleapis.com/maps/api/staticmap?center=$lat,$lng&zoom=10&size=640x640&key=$apiKey'";
            }
        ?>
    </body>
</html>