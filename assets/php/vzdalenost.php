<html>
    <body>
        <form method="post">
            <input name="lat1" /><input name="lon1" /> a <input name="lat2" /><input name="lon2" />
            <input type="submit" />
        </form>
        <?php
        include "databaze.php";
        
        if(isset($_POST["lat1"]) && isset($_POST["lon1"]) && isset($_POST["lat2"]) && isset($_POST["lon2"])){
            echo "vzdalenost: ".vzdalenost($_POST["lat1"], $_POST["lon1"], $_POST["lat2"], $_POST["lon2"]);
            echo "<br />";
            echo "vzdalenost 2: ".vzdalenost2($_POST["lat1"], $_POST["lon1"], $_POST["lat2"], $_POST["lon2"]);
        }
        ?>
    </body>
</html>