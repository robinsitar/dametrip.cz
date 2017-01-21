<html>
    <body>
        <?php
            $vysledek=dotaz("SELECT Id FROM lidi;");
            $lidi=mysqli_num_rows($vysledek);
            echo "<table>";
            for($clovek=0;$clovek<$lidi; $lidi++){
                $myId=mysqli_fetch_array($vysledek)[0];
                $match=matchni($myId);
                if($match){
                    $ja=mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Bydliste, Destinace WHERE Id='$myId'"));
                    $on=mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Bydliste, Destinace WHERE Id='$match'"));
                    echo "<tr><td>".$ja[0]."</td><td>".$ja[1]."</td><td>".json_decode($ja[2])->results[0]->formatted_address."</td><td>".json_decode($ja[3])->results[0]->formatted_address."</td><td>".$match[0]."</td><td>".$match[1]."</td><td>".json_decode($match[2])->results[0]->formatted_address."</td><td>".json_decode($match[3])->results[0]->formatted_address."</td></tr>";
                }
            }
            echo "</table>";
        ?>
    </body>
</html>