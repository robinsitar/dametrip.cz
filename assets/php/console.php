<html>
    <body>
        <?php
            include "databaze.php";
            if(isset($_POST["dotaz"])){
                if(dotaz($_POST["dotaz"])){
                    echo"OK - Dotaz se zdařil";
                }
                else{
                    echo "ERROR - Při vykonávání dotazu nastala chyba";
                }
            }
        ?>
        <form method="post">
            <input name="dotaz" />
            <input type="submit" />
        </form>
    </body>
</html>