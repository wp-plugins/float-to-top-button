<?php
/*
Plugin Name: Float To Top Button
Plugin URI: http://cagewebdev.com/float-to-top-button
Description: This plugin will add a floating scroll to top button to posts / pages
Version: 2.0.5
Date: 07/24/2015
Author: Rolf van Gelder
Author URI: http://cagewebdev.com
License: GPLv2 or later
*/
	 
/***********************************************************************************
 * 	MAIN CLASS
 ***********************************************************************************/	 
class Fttb
{
	var $fttb_version = '2.0.5';
	var $fttb_release_date = '07/24/2015';
	
	/*******************************************************************************
	 * 	CONSTRUCTOR
	 *******************************************************************************/
	function __construct()
	{
		// GET OPTIONS FROM DB (JSON FORMAT)
		$this->fttb_options = get_option('fttb_options');

		// FIRST RUN: SET DEFAULT SETTINGS (since v2.0.1)
		$this->fttb_init_settings();

		// BASE NAME OF THE PLUGIN
		$this->plugin_basename = plugin_basename(__FILE__);
		$this->plugin_basename = substr($this->plugin_basename, 0, strpos( $this->plugin_basename, '/'));
		
		// IMAGE LOCATION
		$this->imgurl = plugins_url().'/'.$this->plugin_basename.'/images/';
		$this->imgdir = plugin_dir_path( __FILE__ ).'images/';

		// LOCALIZATION
		add_action('init', array(&$this, 'fttb_i18n'));

		if ($this->fttb_is_regular_page())
		{	// ADD FRONTEND SCRIPTS
			if ('Y' === $this->fttb_options['disable_mobile'] && wp_is_mobile()) return;
			add_action( 'init', array( &$this, 'fttb_fe_scripts' ) );
		} else
		{	// ADD BACKEND SCRIPTS
			add_action('admin_enqueue_scripts', array(&$this, 'fttb_be_scripts'));
		} // if ($this->fttb_is_regular_page())

		// ADD STYLE SHEET(S)
		add_action('init', array(&$this, 'fttb_styles'));

		if (is_admin())
		{	// ADD BACKEND ACTIONS
			add_action('admin_menu', array(&$this, 'fttb_admin_menu'));
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array(&$this, 'fttb_settings_link'));
		} else
		{	// ADD FRONTEND ACTIONS
			add_action('wp_footer', array(&$this, 'fttb_javascript_vars'));
		} // if (is_admin())
	} // function __construct()


	/*******************************************************************************
	 * 	DEFINE TEXT DOMAIN
	 *******************************************************************************/
	function fttb_i18n()
	{	load_plugin_textdomain('float-to-top-button', false, dirname(plugin_basename( __FILE__ )).'/language/');
	} // fttb_action_init()


	/*******************************************************************************
	 * 	IS THIS A FRONTEND PAGE?
	 *******************************************************************************/
	function fttb_is_regular_page()
	{	if (isset($GLOBALS['pagenow']))
			return !is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
		else
			return !is_admin();
	} // fttb_is_regular_page()


	/*******************************************************************************
	 * 	LOAD STYLESHEET(S)
	 *******************************************************************************/
	function fttb_styles()
	{	if(isset($fttb_options))
			if ('Y' === $fttb_options['disable_mobile'] && wp_is_mobile()) return;
		wp_register_style('plugin-style', plugins_url( 'css/float-to-top-button.css', __FILE__ ));
		wp_enqueue_style('plugin-style');
	} // fttb_styles()


	/*******************************************************************************
	 * 	ADD PAGE TO THE SETTINGS MENU
	 *******************************************************************************/
	function fttb_admin_menu()
	{	if (function_exists('add_options_page'))
		{	global $fttb_options;
			$fttb_options = add_options_page(__('Float to Top Button Settings', 'float-to-top-button'), __( 'Float to Top Button', 'float-to-top-button' ), 'manage_options', 'fttb_settings', array( &$this, 'fttb_settings'));
		}
	} // fttb_admin_menu()


	/*******************************************************************************
	 * 	ADD 'SETTINGS' LINK TO THE MAIN PLUGIN PAGE
	 *******************************************************************************/
	function fttb_settings_link($links)
	{	array_unshift($links, '<a href="options-general.php?page=fttb_settings">Settings</a>');
		return $links;
	} // fttb_settings_link()


	/*******************************************************************************
	 * 	LOAD FRONTEND JAVASCRIPT
	 *******************************************************************************/
	function fttb_fe_scripts()
	{	wp_register_script('fttb-script', plugins_url('float-to-top-button/js/jquery.scrollUp.min.js'), array('jquery'), '1.0', true);
		wp_enqueue_script('fttb-script');
		wp_register_script('fttb-active', plugins_url('float-to-top-button/js/float-to-top-button.js'), array('jquery'), '1.0', true);
		wp_enqueue_script('fttb-active');
	} // fttb_fe_scripts()


	/*******************************************************************************
	 * 	LOAD BACKEND JAVASCRIPT
	 *******************************************************************************/
	function fttb_be_scripts($hook)
	{	global $fttb_options;
		if ($hook !== $fttb_options) return;
		wp_enqueue_script('fttb-jquery-validate', plugin_dir_url( __FILE__ ).'js/jquery.validate.min.js', array('jquery'), '0.1', true);
		wp_register_script('fttb-validate', plugin_dir_url( __FILE__ ) . 'js/fttb-validate.min.js', array( 'jquery', 'fttb-jquery-validate' ), '1.0' );
		$fttb_js_strings = array();
		$fttb_js_strings['topdistance'] = __( 'Distance from top is a required number', 'float-to-top-button' );
		$fttb_js_strings['topspeed'] = __( 'Speed back to top is a required number', 'float-to-top-button' );
		$fttb_js_strings['animationinspeed'] = __( 'Animation in speed is a required number', 'float-to-top-button' );
		$fttb_js_strings['animationoutspeed'] = __( 'Animation out speed is a required number', 'float-to-top-button' );
		$fttb_js_strings['opacity_out'] = __( 'Opacity is a required number (0-99)', 'float-to-top-button' );
		$fttb_js_strings['opacity_over'] = __( 'Opacity is a required number (0-99)', 'float-to-top-button' );		
		wp_localize_script('fttb-validate', 'fttb_strings', $fttb_js_strings);
		wp_enqueue_script('fttb-validate');
	} // fttb_be_scripts()


	/*******************************************************************************
	 * 	INITIALIZE SETTINGS (FIRST RUN)
	 *******************************************************************************/
	function fttb_settings()
	{	// INITIALIZE SETTINGS (FIRST RUN)
		include_once(trailingslashit(dirname( __FILE__ )).'/admin/settings.php');
	} // fttb_settings()


	/*******************************************************************************
	 * 	INITIALIZE SETTINGS
	 *******************************************************************************/
	function fttb_init_settings()
	{
		if(isset($this->fttb_options['opacity']))
		{	unset($this->fttb_options['opacity']);
			$this->fttb_options['position'] = 'lowerright';
			$this->fttb_options['spacing_horizontal'] = '20px';
			$this->fttb_options['spacing_vertical'] = '20px';
			$this->fttb_options['opacity_out'] = 70;
			$this->fttb_options['opacity_over'] = 99;
		}
				
		if (false === $this->fttb_options)
		{	// NO SETTINGS YET: SET DEFAULTS
			$this->fttb_options['topdistance'] = 300;
			$this->fttb_options['topspeed'] = 300;
			$this->fttb_options['animation'] = 'fade';
			$this->fttb_options['animationinspeed'] = 200;
			$this->fttb_options['animationoutspeed'] = 200;
			$this->fttb_options['scrolltext'] = __( 'Top of Page', 'float-to-top-button' );
			$this->fttb_options['arrow_img'] = 'arrow001.png';
			$this->fttb_options['position'] = 'lowerright';
			$this->fttb_options['spacing_horizontal'] = '20px';
			$this->fttb_options['spacing_vertical'] = '20px';			
			$this->fttb_options['opacity_out'] = 75;
			$this->fttb_options['opacity_over'] = 99;			
			$this->fttb_options['disable_mobile'] = "N";

			if (false !== get_option('fttb_topdistance')){
				global $wpdb;
				$old_options = $wpdb->get_col("SELECT option_name from $wpdb->options where option_name LIKE 'fttb%'");
				if (!empty($old_options))
				{	// DELETE ALL OPTIONS FROM v1.2.1 AND EARLIER
					foreach ($old_options as $option)
					{	$value = get_option($option);
						$option_array = substr($option, 5);
						$this->fttb_options[$option_array] = $value;
						delete_option($option);
					} // foreach ($old_options as $option)
				} // if (!empty($old_options))
			} // if (false !== get_option( 'fttb_topdistance')){
		}

		// SAVE OPTIONS ARRAY
		update_option('fttb_options', $this->fttb_options);
	} // fttb_init_settings()


	/*******************************************************************************
	 * 	PASS OPTIONS TO JAVASCRIPT
	 *******************************************************************************/
	function fttb_javascript_vars()
	{	
		echo '
<!-- START Float to Top Button v'.$this->fttb_version.' ['.$this->fttb_release_date.'] | http://cagewebdev.com/float-to-top-button | CAGE Web Design | Rolf van Gelder -->
<script type="text/javascript">
var fttb_topdistance	    = '.$this->fttb_options['topdistance'].';
var fttb_topspeed		    = '.$this->fttb_options['topspeed'].';
var fttb_animation		    = "'.$this->fttb_options['animation'].'";
var fttb_animationinspeed   = '.$this->fttb_options['animationinspeed'].';
var fttb_animationoutspeed  = '.$this->fttb_options['animationoutspeed'].';
var fttb_scrolltext		    = "'. __( $this->fttb_options['scrolltext'], 'float-to-top-button' ).'";
var fttb_imgurl			    = "'.$this->imgurl.'";
var fttb_arrow_img		    = "'.$this->fttb_options['arrow_img'].'";
var fttb_position           = "'.$this->fttb_options['position'].'";
var fttb_spacing_horizontal = "'.$this->fttb_options['spacing_horizontal'].'";
var fttb_spacing_vertical   = "'.$this->fttb_options['spacing_vertical'].'";
var fttb_opacity_out	    = '.$this->fttb_options['opacity_out'].';
var fttb_opacity_over	    = '.$this->fttb_options['opacity_over'].';
</script>
<!-- END Float to Top Button -->
';
	} // fttb_javascript_vars()


	/*******************************************************************************
	 * 	SANITIZE INTEGER FIELD
	 *******************************************************************************/	
	function fttb_sanitize_int($var, $digits)
	{
		$safe_int = intval($var);
		if(!$safe_int) $safe_int = '';
		if (strlen($safe_int) > $digits) $safe_int = substr($safe_int, 0, $digits);
		return $safe_int;
	} // fttb_sanitize_int()

} // Fttb

global $fttb_class;
$fttb_class = new Fttb;
?>