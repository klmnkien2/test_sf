/* JS Custom */
$(document).ready(function(){
	/* Add class to body tag */
	$('body').addClass(bodyClass);
	
	/* Hover on .thumb */
	$('.thumb').hover(
		function(){
			$(this).addClass('onHover');
		}, function(){
			$(this).removeClass('onHover');
		}
	);
	
	/* Sidebar Nav */
	$('.sbNav').ssdVerticalNavigation();
	
	/* Grid View or List View */
	$('.sortBar .sortBtn a').click(function(){
		$('.sortBar .sortBtn a').removeClass('active');
		$(this).addClass('active');
		if($(this).hasClass('grid')){
			$('.gridList').removeClass('listView').addClass('gridView');
		}
		if($(this).hasClass('list')){
			$('.gridList').removeClass('gridView').addClass('listView');
		}
		return false;
	});
	
	/* Rating Star */
	$(".rating").rating();
	
	
	/* typed */
	var typedList = [];
	$('.typedList li').each(function(i){typedList[i] = $(this).html();});
	$('.typedList').hide();
	$('.typed').typed({
		strings: typedList,
		typeSpeed: 20,
		backDelay:2000,
		//loop: true
	});
	
	/* fancybox */
	$('.fancybox, .iconZoom, .writeReview, .login, .signup')
	.fancybox({
		helpers: {
			title: null            
		}
	});
	
	/* Register: Change Sign Up Type */
	/*var appendFields = "";
	$('.signupType input:radio[name="signupType"]').bind('change', function(e){
		if($('.signupType input#normalUser').is(':checked')){
			
		}
		if($('.signupType input#contentProducer').is(':checked')){
			
		}
	});*/
	
	/* Option Accordion */
	$('.optNav .itemHead h3').bind('click', function(e){
		if($(this).parents('.itemHead').next('.itemBody').is(':visible')){
			$(this).find('.glyphicon').removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign').parents('.itemHead').next('.itemBody').slideUp();
		}
		else{
			$(this).find('.glyphicon').removeClass('glyphicon-plus-sign').addClass('glyphicon-minus-sign').parents('.itemHead').next('.itemBody').slideDown();
		}
		return false;
	});
	
	
	/* Replace all SVG images with inline SVG */
	$('img.svg').each(function(){
		var $img = $(this);
		var imgID = $img.attr('id');
		var imgClass = $img.attr('class');
		var imgURL = $img.attr('src');
	
		$.get(imgURL, function(data) {
			var $svg = $(data).find('svg');
			if(typeof imgID !== 'undefined') {
				$svg = $svg.attr('id', imgID);
			}
			if(typeof imgClass !== 'undefined') {
				$svg = $svg.attr('class', imgClass+' iconsvg');
			}
			$svg = $svg.removeAttr('xmlns:a');
			$img.replaceWith($svg);
		}, 'xml');
	});
	
	
	
	// ----------------
	// DEFINE FUNCTIONS
	// ----------------
	var UIhead = $('#UIhead');
	var UIheadPrimaryH;
	var UIheadNavMainH;
	
	// Header on home page: Show, Hide
	function setPaddingTopOnUIcont(){
		var UIheadPrimaryH = $(UIhead).children('.primary').outerHeight();
		var UIheadNavMainH = $(UIhead).children('.navMain').outerHeight();
		
		$('#UIcont').css({'padding-top':(UIheadPrimaryH + UIheadNavMainH) + 'px'});
	}
	
	// When resize window browsers
	function resizeWindow(e){
		setPaddingTopOnUIcont();
	}

	// ----------------
	// RUN FUNCTIONS
	// ----------------
	setPaddingTopOnUIcont();
	$(window).bind("resize", resizeWindow);
});