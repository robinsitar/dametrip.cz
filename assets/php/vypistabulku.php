<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post">
            <input name="Tabulka" /> Název tabulky<br />
            <input type="submit" value="odeslat" name="cudlik" />
        </form>
       <?php
            if(isset($_POST["Tabulka"])){
                $tabulka=$_POST["Tabulka"];
                $dotaz="SELECT * FROM ".safeString($tabulka).";";
                echo "Vykonaný dotaz: $dotaz<br />";
                $vysledek=dotaz($dotaz);
                $radku=mysqli_num_rows($vysledek);
                $sloupcu=mysqli_num_fields($vysledek);
                echo "<table border='solid'>";
                for($radek=0; $radek<$radku; $radek++){
                    $data=mysqli_fetch_array($vysledek);
                    echo "<tr>";
                    for($sloupec=0; $sloupec<$sloupcu; $sloupec++){
                        echo "<td>";
                        echo $data[$sloupec];
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        ?>
    </body>
</html>