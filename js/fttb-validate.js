jQuery().ready(function() {
	jQuery('#fttb_settings').validate({
		rules: {
			fttb_topdistance: {
				required: true,
				digits: true
			},
			fttb_topspeed: {
				required: true,
				digits: true
			},
			fttb_animationinspeed: {
				required: true,
				digits: true
			},
			fttb_animationoutspeed: {
				required: true,
				digits: true
			},
			fttb_opacity: {
				required: true,
				digits: true,
				min: 0,
				max: 99
			}
		},
		messages: {
			fttb_topdistance: fttb_strings.topdistance,
			fttb_topspeed: fttb_strings.topspeed,
			fttb_animationinspeed: fttb_strings.animationinspeed,
			fttb_animationoutspeed: fttb_strings.animationoutspeed,
			fttb_opacity: fttb_strings.opacity
		}
	});
});