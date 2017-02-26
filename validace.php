<!DOCTYPE HTML>

<html>
	<head>
		<title>Validace</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <script>
         (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

         ga('create', 'UA-87258660-1', 'auto');
         ga('send', 'pageview');

        </script>
	</head>
	<body class="landing">        

        <div id="page-wrapper">
        
        <section id="three" class="wrapper style3 special">
						<div class="inner">
                            <div class="formular">
                                <h2 class="formularHeading">VALIDACE</h2>
                            </div>
                            <div class="validaceInner">
                                <p class="validaceZprava">
    <?php
        include "assets/php/databaze.php";
    
        if(isset($_REQUEST["kod"])) {
            $kod = safeString($_REQUEST["kod"]);
            $ok = validuj($kod);
        }
    
        if($ok) {
            echo "Váš email byl úspěšně ověřen! <br> Koukněte se na email, jak jste dopadli v hledání parťáka. <br> Pokud se nám nepodařilo vám někoho najít, nezoufejte a nezapomeňte tento projekt sdílet, ať máte větší šanci někoho poznat a vydat se s ním na dobrodružství :) ";
            $ja = mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Destinace, Bydliste, Email, Vek FROM lidi WHERE Kod=$kod"));
            $partak = matchni($ja[0]);
            if($partak && $partak[9]<=$range) {
                //v tomhle bodě se našel vhodný match a měl by se obou odeslat mail alá "nazdar, našli jsme vám strašně super parťáka" apod...
                $partak = mysqli_fetch_array(dotaz("SELECT Id, Jmeno, Destinace, Bydliste, Email, Vek FROM lidi WHERE Id=".$partak[4].";"));
                
                posliMail($ja[4],"Dámetrip.cz - Našli jsme ti parťáka!","Ahoj ".$ja[1].",\nNašli jsme ti parťáka. Jmenuje se ".$partak[1].", je z ".geo2name($partak[3]).", je mu ".$partak[5]." a chce jet do ".$partak[2].". \nNapiš mu na ".$partak[4]." a vyražte spolu na super trip!");
                
                posliMail($partak[4],"Dámetrip.cz - Našli jsme ti parťáka!","Ahoj ".$partak[1].",\nNašli jsme ti parťáka. Jmenuje se ".$ja[1].", je z ".geo2name($ja[3]).", je mu ".$ja[5]." a chce jet do ".$ja[2].". \nNapiš mu na ".$ja[4]." a vyražte spolu na super trip!");
                
                dotaz("UPDATE lidi SET Aktivni=0 WHERE Id=".$ja[0]";");
                dotaz("UPDATE lidi SET Aktivni=0 WHERE Id=".$partak[0]";");
            } 
            else {
                //vhodného parťáka se najít nepodařilo... v podstatě teď tomuto uživateli nezbývá nic jiného, než čekat, až někdo pojede poblíž a vybere si ho jako parťáka
                posliMail($ja[4],"Dámetrip.cz - Registrace dokončena!","Ahoj ".$ja[1].",\nVítej v Dámetripu! Bohužel se nám v tuto chvíli nepodařilo najít toho pravého parťáka. Každopádně hned jakmile ho najdeme, ozveme se na tento email. Nezapomeň tedy tento projekt sdílet, ať máš větší šanci narazit na toho pravého parťáka. \n \n S přáním krásných tripů, \n Team Dámetrip");
            }
            
        }
        else {
            echo "Někde nastala chyba :/ Nejspíše jste email už jednou ověřili.";
        }
    ?>                          </p>
                            </div>
                        </div>
					</section>



        <!-- Footer -->
        <footer id="footer">
            <ul class="icons">
				<li><a href="https://www.facebook.com/Dáme-Trip-106801046470424" class="icon fa-facebook"><span class="label">Facebook</span></a></li>

				<li><a href="mailto:hajnina11@gmail.com" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
            </ul>
            <ul class="copyright">
				<li>&copy; DámeTrip.cz - Martin Hajný, <a href="http://krystofmitka.cz">Kryštof Mitka</a> a Robin Sitař. Logo design by Vojta Jungmann</li>
            </ul>
        </footer>
            
    </div> 
        
    </body>
</html>