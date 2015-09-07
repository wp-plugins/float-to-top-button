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
 *	activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
 *	zIndex: 2147483647 // Z-Index for the overlay
 *
 * -------------------------------------------------*/	
jQuery(document).ready(function(){
	
	var fttb_img = new Image();
	
	fttb_img.src = fttb.imgurl+fttb.arrow_img;
	
	jQuery("#scrollUp").width(fttb_img.width);
	jQuery("#scrollUp").height(fttb_img.height);

	/* CREATE THE SCROLLUP INSTANCE */
	jQuery.scrollUp({
		topDistance: fttb.topdistance,				// DISTANCE FROM TOP BEFORE SHOWING ELEMENT (PX)
		topSpeed: fttb.topspeed,					// SPEED BACK TO TOP (MS)
		animation: fttb.animation,					// FADE, SLIDE, NONE
		animationInSpeed: fttb.animationinspeed,	// ANIMATION IN SPEED (MS)
		animationOutSpeed: fttb.animationoutspeed,	// ANIMATION OUT SPEED (MS)
		scrollText: fttb.scrolltext,				// TTTLE FOR THE IMAGE
		zIndex: fttb.zindex							// Z-INDEX FOR THE OVERLAY
	});
		
	/* SET THE 'TO TOP' IMAGE TO THE SELECTED IMAGE */
	if(fttb.arrow_img_url == '')
		/* STANDARD IMAGE */
		jQuery("#scrollUp").css({"background-image":"url("+fttb.imgurl+fttb.arrow_img+")"});
	else
		/* CUSTOM IMAGE URL */
		jQuery("#scrollUp").css({"background-image":"url("+fttb.arrow_img_url+")"});
	/* Z-INDEX OF THE BUTTON */
	jQuery("#scrollUp").css('z-index', fttb.zindex);

	if(fttb.position == 'lowerleft')
	{
		jQuery("#scrollUp").css('left', fttb.spacing_horizontal);
		jQuery("#scrollUp").css('bottom', fttb.spacing_vertical);		
	}
	else if(fttb.position == 'lowerright')
	{
		jQuery("#scrollUp").css('right', fttb.spacing_horizontal);
		jQuery("#scrollUp").css('bottom', fttb.spacing_vertical);			
	}
	else if(fttb.position == 'upperleft')
	{
		jQuery("#scrollUp").css('left', fttb.spacing_horizontal);
		jQuery("#scrollUp").css('top', fttb.spacing_vertical);
	}
	else if(fttb.position == 'upperright')
	{
		jQuery("#scrollUp").css('right', fttb.spacing_horizontal);
		jQuery("#scrollUp").css('top', fttb.spacing_vertical);		
	}
	
	/* SET THE OPACITY OF THE 'TO TOP' IMAGE (FROM THE SETTINGS) */
	setOpacity(fttb.opacity_out);
	
	jQuery("#scrollUp").mouseover(function() {
		setOpacity(fttb.opacity_over);
	});
	
	jQuery("#scrollUp").mouseout(function() {
		setOpacity(fttb.opacity_out);
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