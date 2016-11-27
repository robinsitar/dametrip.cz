/*
	Spectral by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
*/

(function($) {

	skel
		.breakpoints({
			xlarge:	'(max-width: 1680px)',
			large:	'(max-width: 1280px)',
			medium:	'(max-width: 980px)',
			small:	'(max-width: 736px)',
			xsmall:	'(max-width: 480px)'
		});

	$(function() {

		var	$window = $(window),
			$body = $('body'),
			$wrapper = $('#page-wrapper'),
			$banner = $('#banner'),
			$header = $('#header');

		// Disable animations/transitions until the page has loaded.
			$body.addClass('is-loading');

			$window.on('load', function() {
				window.setTimeout(function() {
					$body.removeClass('is-loading');
				}, 100);
			});

		// Mobile?
			if (skel.vars.mobile)
				$body.addClass('is-mobile');
			else
				skel
					.on('-medium !medium', function() {
						$body.removeClass('is-mobile');
					})
					.on('+medium', function() {
						$body.addClass('is-mobile');
					});

		// Fix: Placeholder polyfill.
			$('form').placeholder();

		// Prioritize "important" elements on medium.
			skel.on('+medium -medium', function() {
				$.prioritize(
					'.important\\28 medium\\29',
					skel.breakpoint('medium').active
				);
			});

		// Scrolly.
			$('.scrolly')
				.scrolly({
					speed: 1500,
					offset: $header.outerHeight()
				});

		// Menu.
			$('#menu')
				.append('<a href="#menu" class="close"></a>')
				.appendTo($body)
				.panel({
					delay: 500,
					hideOnClick: true,
					hideOnSwipe: true,
					resetScroll: true,
					resetForms: true,
					side: 'right',
					target: $body,
					visibleClass: 'is-menu-visible'
				});

		// Header.
			if (skel.vars.IEVersion < 9)
				$header.removeClass('alt');

			if ($banner.length > 0
			&&	$header.hasClass('alt')) {

				$window.on('resize', function() { $window.trigger('scroll'); });

				$banner.scrollex({
					bottom:		$header.outerHeight() + 1,
					terminate:	function() { $header.removeClass('alt'); },
					enter:		function() { $header.addClass('alt'); },
					leave:		function() { $header.removeClass('alt'); }
				});

			}

	});

})(jQuery);
        var indicator = true;
        $(".formularInput").on('input propertychange', function() {
          if($(".formularInput.active").val() !== "" ) {
              
            if(indicator == true) {
               $(".formularImg.active").attr('src', 'images/animace.gif');
               indicator = false;
              
            setTimeout(function() {
                if($(".formularInput.active").val() !== "" ) {
               $(".formularImg.active").attr('src', 'images/tick.png');
                }
            },1800)
           }
          }
            
         else if($(".formularInput.active").val() == "") {
                $(".formularImg.active").attr('src', 'images/cross.png');
                indicator = true;
            }
            
        }) 
       
        $(".formularDalsiWrapper").hover(function() {
            $(".formularDalsiSipka").animate({"marginTop":"34px"},300)
        },function() {
            $(".formularDalsiSipka").animate({"marginTop":"20px"},150)
        })
        
        $(".formularDalsiWrapper").hover(function() {
                    $(".formularKonecnaSipka").animate({"marginLeft":"30px"},300)
        },function() {
                    $(".formularKonecnaSipka").animate({"marginLeft":"0px"},150)
        })
        
        $(".formularPredchozi").hover(function() {
            $(".formularPredchoziSipka").animate({"marginTop":"0px"},300)
        },function() {
            $(".formularPredchoziSipka").animate({"marginTop":"19px"},150)
        })
        
        $(".formlularDalsi").click(function() {
            
        })
        
        function prevOtazka() {
           if($(".formularOtazkaWrapper.active").hasClass("posledni")){
                $(".formularDalsiText").text("Další Otázka");
                $(".formularKonecnaSipka").hide();
                $(".formularDalsiSipka").show();
            }      
        
           $(".formularOtazkaWrapper.active").hide();
           var prevOtazka = $(".formularOtazkaWrapper.active").prev(); 
           $(".formularOtazkaWrapper.active").removeClass("active");
           $(".formularInput.active").removeClass("active")
           $(".formularImg.active").removeClass("active")
           
           prevOtazka.addClass("active");
           prevOtazka.hide();
           prevOtazka.fadeIn(500);
           var prevInput = prevOtazka.find(".formularInput");
           prevInput.addClass("active");
           var prevImg = prevOtazka.find(".formularImg");
           prevImg.addClass("active");
           $(".formularImg.active").attr('src', 'images/tick.png');
           indicator = false;

           if($(".formularOtazkaWrapper.active").hasClass("prvni")){
                $(".formularPredchozi").hide();
            }
        }
        
        function nextOtazka() {
         if($(".formularInput.active").val() !== "") {
           
            if($(".formularOtazkaWrapper.active").hasClass("prvni")){
                $(".formularPredchozi").fadeIn(500);
            } 
            if($(".formularOtazkaWrapper.active").hasClass("posledni")){
                $(".formularInput.active").hide();
                $(".formularDalsi").hide();
                $(".formularPredchozi").hide();
                $(".endingText").fadeIn(500);
   
            }
             if($(".formularOtazkaWrapper.active").hasClass("predposledni")){
                
                $(".formularDalsiText").text("Odeslat");
                $(".formularDalsiSipka").hide();
                $(".formularKonecnaSipka").show(); 
                
            }
            
            
            $(".formularOtazkaWrapper.active").hide();
            var nextOtazka = $(".formularOtazkaWrapper.active").next();
            $(".formularOtazkaWrapper.active").removeClass("active");
            $(".formularInput.active").removeClass("active")
            $(".formularImg.active").removeClass("active")
            nextOtazka.addClass("active");
            nextOtazka.hide();
            nextOtazka.fadeIn(500);
            var nextInput = nextOtazka.find(".formularInput");
            nextInput.addClass("active");
            var nextImg = nextOtazka.find(".formularImg");
            nextImg.addClass("active");
             
            indicator = true;
            $(".formularInput.active").focus();
                  
             
         }
        }

        $(".formularPredchozi").on("click", function() {
            prevOtazka()
        })        

        $(".formularDalsiWrapper").on("click", function() {
            nextOtazka()
        })
        
        $("body").on("keyup",function(e) {   
            if(e.keyCode == 13 || e.keyCode == 40) {
                nextOtazka();
            }
            if(e.keyCode == 38) {
             if($(".formularOtazkaWrapper.active").hasClass("prvni") == false){
                prevOtazka();
             }
            }
        });