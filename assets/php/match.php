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
                    echo "<tr><td>".$ja[0]."</td><td>".$ja[1]."</td><td>".geo2name($ja[2])."</td><td>".geo2name($ja[3])."</td><td>".$on[0]."</td><td>".$on[1]."</td><td>".geo2name($on[2])."</td><td>".geo2name($on[3])."</td><td>".$match[5]."</td></tr>";
                }
                else{
                    echo "<tr><td>".$ja[0]."</td><td>".$ja[1]."</td><td>".geo2name($ja[2])."</td><td>".geo2name($ja[3])."</td><td>Nepodařilo se najít parťáka</td><td></td><td></td><td></td><td></td>";
                }
            }
            echo "</table>";
        ?>
    </body>
</html>