<html>
    <?php
        include "assets/php/databaze.php";
        $random=rand();
    ?>
    <body>
        <?php
            if(isset($_REQUEST["cudlik"])){
                pridej($_REQUEST["Jmeno"],$_REQUEST["Vek"],$_REQUEST["Email"],$_REQUEST["Bydliste"],$_REQUEST["Cinnost"],$_REQUEST["Destinace"]);
                    
                }else{
        ?>
        <form>
            <input name="Jmeno" /> Jméno<br />
            <input name="Vek" type="number" /> Věk<br />
            <input name="Email" type="email" /> Email<br />
            <input name="Bydliste" /> Bydliště<br />
            <input name="Cinnost" /> Činnost<br />
            <input name="Destinace" /> Destinace<br />
            <input type="submit" value="odeslat" name="cudlik" />
        </form>
        <?php
            }
            $vysledek=dotaz("SELECT * FROM lidi ORDER BY Id");
            $radku=mysqli_num_rows($vysledek);
            $sloupcu=mysqli_num_fields($vysledek);
            echo "<table border=solid>";
            for($radek=0; $radek<$radku; $radek++){
                $dataRadku=mysqli_fetch_array($vysledek);
                echo "<tr>";
                for($sloupec=0; $sloupec<$sloupcu; $sloupec++){
                    echo "<td>".$dataRadku[$sloupec]."</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </body>
</html>