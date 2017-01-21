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
    // Submitování form skrz Aajax

        var indicator = true;
        $(".formularInput").on('input propertychange focus', function() {
            kontrolOtazka();
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
            $(".formularPredchoziSipka").animate({"marginTop":"0px"},400)
        },function() {
            $(".formularPredchoziSipka").animate({"marginTop":"19px"},150)
        })
        
        $(".formlularDalsi").click(function() {
            
        })
        
        function kontrolOtazka() {
          if($(".formularInput.active").val() !== "" ) {
              
            if(indicator == true) {
               $(".formularInput.active").css("box-shadow","0 0 0 2px #04a204");
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
                $(".formularInput.active").css("box-shadow","0 0 0 2px #c75546");
                indicator = true;
            }
            
        }
        kontrolOtazka();
        
        //PREDESLA OTAZKA
        activator = true;
        function prevOtazka() {
          if(activator == true) {
           if($(".formularOtazkaWrapper.active").hasClass("posledni")){
                $(".formularDalsiText").text("Další Otázka");
                $(".formularKonecnaSipka").hide(); //Prepinani z konečného submitu
                $(".formularDalsiSipka").show();
                $(".formularDalsiWrapper").off("click").on("click", function() { nextOtazka() }) //Vypnutí submitu
            }      
        
           activator = false;      
           var prevOtazka = $(".formularOtazkaWrapper.active").prev();
           $(".formularOtazkaWrapper.active").hide("slide", { direction: "down" }, 800);
           $(".formularOtazkaWrapper.active").removeClass("active");
           $(".formularInput.active").blur();
           $(".formularInput.active").removeClass("active")
           $(".formularImg.active").removeClass("active")
           
           prevOtazka.addClass("active");
           prevOtazka.hide();
           prevOtazka.delay(850).show("slide", { direction: "up" }, 800);
           var prevInput = prevOtazka.find(".formularInput");
           prevInput.addClass("active");
           setTimeout(function() {
                prevInput.focus();
                activator = true;
            },1800)
           var prevImg = prevOtazka.find(".formularImg");
           prevImg.addClass("active");
           $(".formularImg.active").attr('src', 'images/tick.png');
           indicator = false;

           if($(".formularOtazkaWrapper.active").hasClass("prvni")){
                $(".formularPredchozi").fadeOut(500);
            }
          }
        }
        
        //DALSI OTAZKA
        function nextOtazka() {
         if($(".formularInput.active").val() !== "" && activator == true) {
             
            if($(".formularOtazkaWrapper.active").hasClass("prvni")){
                $(".formularPredchozi").fadeIn(500);
            } 
           
            if($(".formularOtazkaWrapper.active").hasClass("posledni")){
                $(".formularOtazkaWrapper.active").hide("slide", { direction: "left" }, 1000);
                $(".formularDalsi").slideUp(1000);
                $(".formularPredchozi").slideUp(1000);
                $(".endingText").delay(1100).show("slide", { direction: "right" }, 1000);
   
            }
             if($(".formularOtazkaWrapper.active").hasClass("predposledni")) {
                
                $(".formularDalsiText").text("Odeslat");
                $(".formularDalsiSipka").hide(); // Přepínaní na konečnej submit
                $(".formularKonecnaSipka").show();
                $(".formularDalsiWrapper").off("click").on("click", function(e) {
            
                 $("#mainForm").submit(); //Zapnutí submitu
                
            });
          };
            
            activator = false;
           if($(".formularOtazkaWrapper.active").hasClass("posledni") == false) {    
            var nextOtazka = $(".formularOtazkaWrapper.active").next();
            $(".formularOtazkaWrapper.active").hide("slide", { direction: "up" }, 800);
            $(".formularOtazkaWrapper.active").removeClass("active");
            $(".formularInput.active").blur();
            $(".formularInput.active").removeClass("active");
            $(".formularImg.active").removeClass("active");
            nextOtazka.addClass("active");
            nextOtazka.hide();
            nextOtazka.delay(850).show("slide", { direction: "down" }, 800);
            var nextInput = nextOtazka.find(".formularInput");
            nextInput.addClass("active");
            setTimeout (function() {
                nextInput.focus();
                activator = true;
            },1800)
            var nextImg = nextOtazka.find(".formularImg");
            nextImg.addClass("active");
           }
             
            //Když zůstanou data 
            if($(".formularInput.active").val() !== "" ) {    
               $(".formularImg.active").attr('src', 'images/tick.png');
               $(".formularInput.active").css("box-shadow","0 0 0 2px #04a204")
               indicator = false;
           }
            
            else if($(".formularInput.active").val() == "") {
                $(".formularImg.active").attr('src', 'images/cross.png');
                $(".formularInput.active").css("box-shadow","0 0 0 2px #c75546");
                indicator = true;
            }
                  
             
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

$(document).ready(function() {
    
    $("#mainForm").on("submit", function (e) {
          $.ajax({
                type: "POST",
                url: "./pridat.php",
                data: $("#mainForm").serialize(),
                success: function () {
                    $(".formularOtazkaWrapper.active").hide("slide", { direction: "left" }, 1000);
                    $(".formularDalsi").slideUp(1000);
                    $(".formularPredchozi").slideUp(1000);
                    $(".endingText").delay(1100).show("slide", { direction: "right" }, 1000);
                }
            });
         e.preventDefault();
        });
});