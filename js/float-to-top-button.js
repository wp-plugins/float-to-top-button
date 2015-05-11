/* ---------------------------------------------------
 *
 *	Initialize the scrollUp object
 *
 *	http://markgoodyear.com/2013/01/scrollup-jquery-plugin/
 *
 *	Options
 *	scrollName: 'scrollUp',
 *	topDistance: '300', // Distance from top before showing element (px)
 *	topSpeed: 300, // Speed back to top (ms)
 *	animation: 'fade', // Fade, slide, none
 *	animationInSpeed: 200, // Animation in speed (ms)
 *	animationOutSpeed: 200, // Animation out speed (ms)
 *	scrollText: 'Scroll to top', // Text for element
 *	scrollImg: false,
 *	activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF' 
 *
 * -------------------------------------------------*/	
jQuery(document).ready(function(){
	/* CREATE THE SCROLLUP INSTANCE */
	jQuery.scrollUp({
		topDistance: fttb_topdistance,             // DISTANCE FROM TOP BEFORE SHOWING ELEMENT (PX)
		topSpeed: fttb_topspeed,                   // SPEED BACK TO TOP (MS)
		animation: fttb_animation,                 // FADE, SLIDE, NONE
		animationInSpeed: fttb_animationinspeed,   // ANIMATION IN SPEED (MS)
		animationOutSpeed: fttb_animationoutspeed, // ANIMATION OUT SPEED (MS)
		scrollText: fttb_scrolltext                // TTTLE FOR THE IMAGE
	});
	
	/* SET THE 'TO TOP' IMAGE TO THE SELECTED IMAGE */
	jQuery("#scrollUp").css({"background-image":"url("+fttb_imgurl+fttb_arrow_img+")"});
	
	/* SET THE OPACITY OF THE 'TO TOP' IMAGE (FROM THE OPTIONS) */
	setOpacity(fttb_opacity);
	
	jQuery("#scrollUp").mouseover(function() {
		setOpacity("99");
	});
	
	jQuery("#scrollUp").mouseout(function() {
		setOpacity(fttb_opacity);
	});
});		

function setOpacity(opac)
{
	jQuery("#scrollUp").css({"-khtml-opacity":"."+opac});
	jQuery("#scrollUp").css({"-moz-opacity":"."+opac});
	jQuery("#scrollUp").css({"-ms-filter":'"alpha(opacity='+opac+')"'});
	jQuery("#scrollUp").css({"filter":"alpha(opacity="+opac+")"});
	jQuery("#scrollUp").css({"filter":"progid:DXImageTransform.Microsoft.Alpha(opacity=0."+opac+")"});
	jQuery("#scrollUp").css({"opacity":"."+opac});		
} // setOpacity()