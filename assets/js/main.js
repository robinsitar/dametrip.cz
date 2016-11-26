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
          if($(".formularInput").val() !== "" ) {
          if(indicator == true ) {
               $(".formularImg").attr('src', 'images/animace.gif');
          
            setTimeout(function() {
               $(".formularImg").attr('src', 'images/tick.png');
                indicator = false
              
            },1840)
           }
          }
            else if($(".formularInput").val() == "" ){
                $(".formularImg").attr('src', 'images/cross.png');
                indicator = true;
            }
        }) 
       
        $(".formularDalsi").hover(function() {
            $(".formularDalsiSipka").animate({"marginTop":"34px"},300)
        },function() {
            $(".formularDalsiSipka").animate({"marginTop":"20px"},150)
        })
        
        $(".formularDalsi").on("click", function() {
         if($(".formularInput").val() !== "" ) {
             
            $(".formularInput").val("") 
            var next = $("p.active").next();
            $("p.active").removeClass("active");
            next.addClass("active");
            
            $(".formularInput").hide();
            $(".p.active").hide();

            $(".formularInput").fadeIn(500);
            $(".p.active").fadeIn(500);
         }
        })