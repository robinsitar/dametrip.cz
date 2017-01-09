<html>
    <?php
        include "databaze.php";
    ?>
    <body>
        <form method="post">
            <h1>Přidat uživatele</h1>
            <input name="Jmeno" /> Jméno<br />
            <input name="Vek" /> Věk<br />
            <input name="Email" /> Email<br />
            <input name="Bydliste" /> Bydliště<br />
            <input name="Cinnost" /> Činnost<br />
            <input name="Destinace" /> Destinace<br />
            
            <input type="submit" value="Přidat" name="cudlik" />
        </form>
        
        <form method="post">
            <h1>Smazat uživatele</h1>
            <input name="Id" /> Id toho, koho chceme smazat<br />
            <input type="submit" value="Smazat" name="cudlik" />
            <?php
                if(isset($_POST["Id"])){
                    echo smaz($_POST["Id"]);    
                }
            ?>
        </form>
        
        <form method="post">
            <h1>Najít parťáka</h1>
            <input name="Id" /> Id toho, koho chceme s někým spojit<br />
            <input type="submit" value="Spojit" name="cudlik" />
            <?php
                if(isset($_POST["Id"])){
                    echo matchni($_POST["Id"]);    
                }
            ?>
        </form>
       <?php
            if(isset($_POST["Jmeno"]) && isset($_POST["Vek"])&& isset($_POST["Email"])&& isset($_POST["Bydliste"])&& isset($_POST["Cinnost"])&& isset($_POST["Destinace"])){
                if(pridej($_POST["Jmeno"], $_POST["Vek"], $_POST["Email"], $_POST["Bydliste"], $_POST["Cinnost"], $_POST["Destinace"])){
                    echo "OK - uživatel přidán";
                }else{
                    echo "ERROR - někde se stala chyba";
                }
            }
            
                //výpis z tabulky lidí                                                                             
                $vysledek=dotaz("SELECT * FROM $tabulka;");
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
        ?>
    </body>
</html>