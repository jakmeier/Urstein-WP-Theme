jQuery(document).ready(function($) {
	
  // Jubiläum 
  function refreshAnniversary(){
    var seconds = (new Date().getTime() - new Date(2018-1,8-1,31-0).getTime()) / 1000;
    var y = 98 + Math.floor( seconds / 31536000);
    var d = Math.floor((seconds % 31536000) / 86400);
    var h = Math.floor((seconds % 86400) / 3600);
    var m = Math.floor((seconds % 3600) / 60);
    var s = Math.floor(seconds % 60);
    var age = y + " Jahre ";
        if (d > 0) {
      age = age + d + " Tage "
    }
    if (h > 0) {
      age = age + h + " Stunden "
    }
    if (m > 0) {
      age = age + m + " Minuten "
    }
    if (s > 0) {
      age = age + s + " Sekunden"
    }
    $("p#age").html(age);
    setTimeout(refreshAnniversary, 1000);
  }
  
  refreshAnniversary();
  
	// Enable dropdown menus on touch devices
	$( '.main-menu li:has(ul)' ).doubleTapToGo();
	
	
	// Toggle navigation
	$(".nav-toggle").on("click", function(){	
		$(this).toggleClass("active");
		$(".mobile-navigation").slideToggle();
		return false;
	});
	
	
	// Hide mobile-navigation > 900
	$(window).resize(function() {
		if ($(window).width() > 900) {
			$(".nav-toggle").removeClass("active");
			$(".mobile-navigation").hide();
		}
	});
	
	
	// Load Flexslider
    $(".flexslider").flexslider({
        animation: "slide",
        controlNav: true,
        smoothHeight: true,
        nextText: '<span class="fa fw fa-angle-right"></span>',
        prevText: '<span class="fa fw fa-angle-left"></span>',
    });

        			
	// resize videos after container
	var vidSelector = ".post iframe, .post object, .post video, .widget-content iframe, .widget-content object, .widget-content iframe";	
	var resizeVideo = function(sSel) {
		$( sSel ).each(function() {
			var $video = $(this),
				$container = $video.parent(),
				iTargetWidth = $container.width();

			if ( !$video.attr("data-origwidth") ) {
				$video.attr("data-origwidth", $video.attr("width"));
				$video.attr("data-origheight", $video.attr("height"));
			}

			var ratio = iTargetWidth / $video.attr("data-origwidth");

			$video.css("width", iTargetWidth + "px");
			$video.css("height", ( $video.attr("data-origheight") * ratio ) + "px");
		});
	};

	resizeVideo(vidSelector);

	$(window).resize(function() {
		resizeVideo(vidSelector);
	});
	
	// Hide user information
	$('.user-avatar .info-box').each(function(){
		var el = $(this);
		if(el.children().length > 3) {
			var anker = el.children().first().next();
			anker.nextAll().hide();
			anker.after($('<a class="buttton">Mehr anzeigen<a/>'));
			anker.next().click(displaySiblingAfter.bind(anker.next()));
		}
	});
	
});
function displaySiblingAfter(){
	var el = jQuery(this);
	el.nextAll().show();
	el.hide();
}
( function( $ ) {
    $( document.body ).on( 'post-load', function () {
        $('.infinite-loader').remove();
        $('.posts .clear').remove();
		$('.posts').append('<div class="clear"></div>');
    } );
} )( jQuery );