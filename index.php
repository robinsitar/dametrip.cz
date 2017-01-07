<!--
    TODO:
    udělat custom styl scrollbaru?
-->

<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>DámeTrip</title>
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

		<!-- Page Wrapper -->
			<div id="page-wrapper">


				<!-- Banner -->
					<section id="banner">
						<div class="inner">
							<h2>Poznejte parťáka na cesty</h2>
							<p>Věříme, že by se lidé měli stále poznávat.<br />
							Ta nejlepší dobrodružství jsou ta, která strávíte s někým novým.<br />
                            Strachem z poznávání ostatních sami sebe okrádáte.<br />
                            Neokrádejte se.
							</p>
							<ul class="actions">
								<li><a href="#three" class="button special scrolly">Poznat parťáka</a></li>
							</ul>
						</div>
						<a href="#one" class="more scrolly">Dozvědět se více</a>
					</section>

				<!-- One -->
					<section id="one" class="wrapper style1 special">
						<div class="inner">
							<header class="major">
								<h2>Jak to funguje?</h2>

                                <p><b>Vyplníte dotazník</b></p>
								<p>Vyplníte dotazník krátkými otázkami.</p>
                                <p><b>Někdo další vyplní dotazník</b></p>
								<p>Stejným způsobem jako vy.</p>
                                <p><b>Spojíme vás</b></p>
								<p>Vyhodnotíme dotazníky a spojíme vás s lidmi s podobnými zájmy.</p>

                                <ul class="actions">
								<li><a href="#three" class="button special scrolly">Jdeme na to</a></li>
							    </ul>
							</header>
<!--
							<ul class="icons major">
								<li><span class="icon fa-paper-plane major style1"><span class="label">Lorem</span></span></li>
								<li><span class="icon fa-heart-o major style2"><span class="label">Ipsum</span></span></li>
								<li><span class="icon fa-code major style3"><span class="label">Dolor</span></span></li>
							</ul>
-->
						</div>
					</section>


				<!-- Three -->
					<section id="three" class="wrapper style3 special">
						<div class="inner">
                            <div class="formular">
                                <h2 class="formularHeading">Přihlaš se</h2>
                            </div>
                              <form action="index.php" method="post">
                                <div class="formularInner">
                                   <div class="formularOtazka">
                                   <div class="wrapperPomoc">  
                                    <div class="formularOtazkaWrapper active prvni">
                                        <p class="formularText">Kam by jste rád/a jel/a?</p>
                                        <div class="formularWrapper">
                                        <input type="text" name="Destinace" class="formularInput active" />
                                        <img class="formularImg active" src="images/cross.png" />
                                        </div>
                                    </div>
                                    <div class="formularOtazkaWrapper">
                                        <p class="formularText">Co byste tam rád/a dělal/a?</p>
                                        <div class="formularWrapper">
                                        <input type="text" name="Cinnost" class="formularInput" />
                                        <img class="formularImg" src="images/cross.png" />
                                        </div>
                                    </div>
                                    <div class="formularOtazkaWrapper">
                                        <p class="formularText">Odkud pocházíš?</p>
                                        <div class="formularWrapper">
                                        <input type="text" name="Bydliste" class="formularInput" />
                                        <img class="formularImg" src="images/cross.png" />
                                        </div>
                                    </div>
                                       <div class="formularOtazkaWrapper">
                                        <p class="formularText">Váš e-mail?</p>
                                        <div class="formularWrapper">
                                        <input type="text" name="Email" class="formularInput" />
                                        <img class="formularImg" src="images/cross.png" />
                                        </div>
                                    </div>
                                    <div class="formularOtazkaWrapper predposledni">
                                        <p class="formularText">Vaše jméno?</p>
                                        <div class="formularWrapper">
                                        <input type="text" name="Jmeno" class="formularInput" />
                                        <img class="formularImg" src="images/cross.png" />
                                        </div>
                                    </div>
                                    <div class="formularOtazkaWrapper posledni">
                                        <p class="formularText">Kolik vám je let?</p>
                                        <div class="formularWrapper">
                                        <input type="text" name="Vek" class="formularInput" />
                                        <img class="formularImg" src="images/cross.png" />
                                        </div>
                                    </div>
                                    </div>
                                    <div class="formularPredchozi">
                                     <img src="assets/css/images/arrow-u.svg"  class="formularPredchoziSipka">
                                        <p class="formularPredchoziText">Předchozí otázka</p>
                                   </div>
                                   <div class="formularDalsi">
                                    <div class="formularDalsiWrapper">   
                                     <p class="formularDalsiText">Další otázka</p>
                                     <img src="assets/css/images/arrow.svg"  class="formularDalsiSipka">
                                     <img src="assets/css/images/arrow-r.svg"  class="formularKonecnaSipka">
                                    </div>
                                   </div>
                                   <p class="endingText">Děkujeme vám za účast v našem projektu.<br><br>Budeme vás co nejdříve kontaktovat.</p>
                                </div>
						</div>
                     </form>                          
					</section>



				<!-- Footer -->
					<footer id="footer">
						<ul class="icons">
							<li><a href="https://www.facebook.com/Dáme-Trip-106801046470424" class="icon fa-facebook"><span class="label">Facebook</span></a></li>

							<li><a href="mailto:hajnina11@gmail.com" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
						</ul>
						<ul class="copyright">
							<li>&copy; DámeTrip.cz - Martin Hajný, Robin Sitař, Kryštof Mitka, Vojta Jungmann</li>
						</ul>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
            <script src="assets/js/jquery-ui.min.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	   <?php
            include "assets/php/databaze.php";
            
            if(isset($_POST["Jmeno"]) && $_POST["Vek"]) &&$_POST["Email"]) &&$_POST["Bydliste"]) &&$_POST["Cinnost"]) &&$_POST["Destinace"])){
                $ok=pridej($_POST["Jmeno"],$_POST["Vek"],$_POST["Email"],$_POST["Bydliste"],$_POST["Cinnost"],$_REQUEST["Destinace"]);
                if($ok){echo "Super! Byl jste přidán do databáze. Pro aktivaci prosím potvďte váš email...";}
            }
        ?>
    </body>
</html>
