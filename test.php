<html>
    <?php
        include "assets/php/databaze.php";
        $random=rand();
    ?>
    <body>
        <form>
            <input name="Jmeno" /> Jméno<br />
            <input name="Vek" type="number" /> Věk<br />
            <input name="Email" type="email" /> Email<br />
            <input name="Bydliste" /> Bydliště<br />
            <input name="Cinnost" /> Činnost<br />
            <input name="Destinace" /> Destinace<br />
            <input name="kontrola1" type="hidden" value=<?php echo "'$random'"; ?> />
            <?php echo $random; ?><br />
            <input name="kontrola2" /> Prosím opište číslo
            <input type="submit" value="odeslat" name="cudlik" />
        </form>
        <?php
            if(isset($_REQUEST["cudlik"])){
                if($_REQUEST["kontrola1"]==$_REQUEST["kontrola1"]){
                    pridej($_REQUEST["Jmeno"],$_REQUEST["Vek"],$_REQUEST["Email"],$_REQUEST["Bydliste"],"",$_REQUEST["Cinnost"],$_REQUEST["Destinace"],0);
                    
                }else{
                    echo "špatně jste odpověděl na bezpečnostní otázku";
                }
            }
            $vysledek=dotaz("SELECT * FROM lidi ORDER BY Id");
            $radku=mysqli_num_rows($vysledek);
            $sloupcu=mysqli_num_fields($vysledek);
            echo "<table>";
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