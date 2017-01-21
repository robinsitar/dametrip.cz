<html>
    <body>
        <?php
            include "databaze.php";
        
            $vysledek=dotaz("SELECT Id FROM lidi;");
            $lidi=mysqli_num_rows($vysledek);
            echo "<table border='solid'>";
            for($clovek=0;$clovek<$lidi; $clovek++){
                $myId=mysqli_fetch_array($vysledek)[0];
                $match=matchni($myId);
                $ja=mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Bydliste, Destinace FROM lidi WHERE Id='$myId'"));
                if($match){
                    $on=mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Bydliste, Destinace FROM lidi WHERE Id='".$match[4]."'"));
                    echo "<tr><td>".$ja[0]."</td><td>".$ja[1]."</td><td>".json_decode($ja[2])->results[0]->formatted_address."</td><td>".json_decode($ja[3])->results[0]->formatted_address."</td><td>".$match[0]."</td><td>".$match[1]."</td><td>".json_decode($match[2])->results[0]->formatted_address."</td><td>".json_decode($match[3])->results[0]->formatted_address."</td></tr>";
                }
                else{
                    echo "<tr><td>".$ja[0]."</td><td>".$ja[1]."</td><td>".json_decode($ja[2])->results[0]->formatted_address."</td><td>".json_decode($ja[3])->results[0]->formatted_address."</td><td>Nepodařilo se najít parťáka</td><td></td><td></td><td></td>";
                }
            }
            echo "</table>";
        ?>
    </body>
</html>