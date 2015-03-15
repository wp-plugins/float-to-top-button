<?php
$opm_version      = '1.1.4';
$opm_release_date = '03/15/2015';
/*
Plugin Name: Float To Top Button
Plugin URI: http://cagewebdev.com/float-to-top-button
Description: This plugin will add a floating scroll to top button to posts / pages
Version: 1.1.4
Date: 03/15/2015
Author: Rolf van Gelder
Author URI: http://cagewebdev.com
License: GPLv2 or later
*/

/********************************************************************************************

	ADD THE LANGUAGE SUPPORT (LOCALIZATION)

*********************************************************************************************/
function fttb_action_init()
{
	// v1.1.4
	load_plugin_textdomain('float-to-top-button', false, dirname(plugin_basename(__FILE__)).'/language/');
}
// INIT HOOK
add_action('init', 'fttb_action_init');


/********************************************************************************************

	CHECK IF THIS PAGE IS A REGULAR PAGE (NOT AN ADMIN NOR LOGIN PAGE)

*********************************************************************************************/
function fttb_is_regular_page()
{	// v1.1.2
	return !is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
} // fttb_is_regular_page()


/********************************************************************************************

	ADD SCRIPTS

*********************************************************************************************/
// v1.1.2
if (fttb_is_regular_page())
{	// LOAD JAVASCRIPT FILES
	function fttb_scripts()
	{	// v1.1.3
		wp_register_script( 'fttb-script', plugins_url('float-to-top-button/js/jquery.scrollUp.min.js'), array('jquery'), '1.0', true);
		wp_enqueue_script( 'fttb-script' );
		// v1.1.3
		wp_register_script( 'fttb-active', plugins_url('float-to-top-button/js/float-to-top-button.js'), array('jquery'), '1.0', true);
		wp_enqueue_script( 'fttb-active' );
	} // fttb_scripts()
	add_action( 'init', 'fttb_scripts' );
} // if (fttb_is_regular_page())


/********************************************************************************************

	ADD STYLES (BOTH FRONT END AND BACK END)

*********************************************************************************************/
// v1.1.4
function fttb_styles()
{	wp_register_style( 'plugin-style', plugins_url('css/float-to-top-button.css', __FILE__) );
	wp_enqueue_style( 'plugin-style' );
} // fttb_styles()
add_action( 'init', 'fttb_styles' );


/********************************************************************************************

	ADD THE 'FLOAT TO TOP BUTTON OPTIONS' ITEM TO THE SETTINGS MENU

*********************************************************************************************/
function fttb_admin_menu()
{	
	if (function_exists('add_options_page'))
	{	add_options_page(__('Float to Top Button Options', 'float-to-top-button'), __('Float to Top Button','float-to-top-button'), 'manage_options', 'fttb_options', 'fttb_options_page');
    }
} // fttb_admin_menu()
add_action( 'admin_menu', 'fttb_admin_menu' );


/********************************************************************************************

	SHOW OPTIONS PAGE

*********************************************************************************************/
function fttb_options_page()
{
	$plugin_basename = plugin_basename( __FILE__ );
	$plugin_basename = substr($plugin_basename, 0, strpos($plugin_basename, '/'));
	
	$imgurl = plugins_url().'/'.$plugin_basename.'/css/img/';
	$imgdir = plugin_dir_path(__FILE__).'css/img/';	
	
	// INITIALIZE OPTIONS (FIRST RUN)	
	fttb_init_options();

	if (isset($_POST['action']) && $_POST['action']=='save_options')
	{	// SAVE CHANGED OPTIONS
		update_option('fttb_topdistance', $_REQUEST['fttb_topdistance']);
		update_option('fttb_topspeed', $_REQUEST['fttb_topspeed']);
		update_option('fttb_animation', $_REQUEST['fttb_animation']);		
		update_option('fttb_animationinspeed', $_REQUEST['fttb_animationinspeed']);
		update_option('fttb_animationoutspeed', $_REQUEST['fttb_animationoutspeed']);				
		update_option('fttb_scrolltext', $_REQUEST['fttb_scrolltext']);
		update_option('fttb_arrow_img', $_REQUEST['fftb_arrow_img']);
		update_option('fttb_opacity', $_REQUEST['fttb_opacity']);
		echo "<div class='updated'><p><strong>".__('Float to Top Button - Settings UPDATED!','float-to-top-button')."</strong></p></div>";
	}

	# FIND AVAILABLE ARROW IMAGES
	$arrows = array();
	foreach(glob($imgdir.'arrow*.png') as $file)
	{
		$fn = substr($file, strrpos($file,'/')+1);
		array_push($arrows, $fn);
	}
	
	$fttb_topdistance       = get_option('fttb_topdistance');
	$fttb_topspeed          = get_option('fttb_topspeed');
	$fttb_animation         = get_option('fttb_animation');
	$fttb_animationinspeed  = get_option('fttb_animationinspeed');
	$fttb_animationoutspeed = get_option('fttb_animationoutspeed');
	$fttb_scrolltext        = get_option('fttb_scrolltext');
	$fttb_arrow_img         = get_option('fttb_arrow_img');
	$fttb_opacity           = get_option('fttb_opacity');
	
	if(strlen($fttb_arrow_img)<12)
		// OLD RELEASE: TEMPORARY FIX... (arrow1.png .. arrow8.png => arrow001.png .. arrow008.png)
		$fttb_arrow_img = substr($fttb_arrow_img, 0, 4).'00.png';
?>

<div id="options-form">
  <form name="options" method="post" action="">
    <input type="hidden" name="action" value="save_options" />
    <h1>
      <?php _e('Float to Top Button - Options', 'float-to-top-button'); ?>
    </h1>
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><?php _e('Distance from top before showing element (px)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_topdistance" type="text" value="<?php echo $fttb_topdistance;?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Speed back to top (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_topspeed" type="text" value="<?php echo $fttb_topspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Animation', 'float-to-top-button'); ?></td>
        <td><select name="fttb_animation" id="fttb_animation">
            <option value="fade">
            <?php _e('fade', 'float-to-top-button')?>
            </option>
            <option value="slide">
            <?php _e('slide', 'float-to-top-button')?>
            </option>
            <option value="none">
            <?php _e('none', 'float-to-top-button')?>
            </option>
          </select></td>
      </tr>
      <script type="text/javascript">
	  jQuery("#fttb_animation").val("<?php echo $fttb_animation;?>");
	  </script>
      <tr>
        <td><?php _e('Animation in speed (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_animationinspeed" type="text" value="<?php echo $fttb_animationinspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Animation out speed (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_animationoutspeed" type="text" value="<?php echo $fttb_animationoutspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Text for the button', 'float-to-top-button'); ?></td>
        <td><input name="fttb_scrolltext" type="text" value="<?php echo $fttb_scrolltext;?>" /></td>
      </tr>
      <tr>
        <td valign="top"><?php _e('"Top of Page" image', 'float-to-top-button'); ?></td>
        <td><?php
	for($i=0; $i<count($arrows); $i++)
	{
		$checked = '';
		if($fttb_arrow_img == $arrows[$i]) $checked = ' checked';
		echo '<div class="fftb-arrow-icon"><input name="fftb_arrow_img" id="fftb_arrow_img'.$i.'" type="radio" value="'.$arrows[$i].'" '.$checked.' /><img src="'.$imgurl.$arrows[$i].'" align="absmiddle" /></div>';
	} // for($i=0; $i<count($arrows); $i++)
?></td>
      </tr>
      <tr>
        <td><?php _e('Opacity of the to top image (0-100)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_opacity" type="text" value="<?php echo $fttb_opacity;?>" /></td>
      </tr>
      <tr>
        <td colspan="2"><input class="button-primary button-large save-button" type='submit' name='info_update' value='<?php echo __('Save Options','float-to-top-button');?>' /></td>
      </tr>
    </table>
  </form>
</div>
<?php
} // fttb_options_page()


/********************************************************************************************

	SAVE DEFAULT VALUES (FIRST RUN)

*********************************************************************************************/
function fttb_init_options()
{
	if(!get_option('fttb_animation'))
	{	// OPTIONS NOT IN DATABASE: INITIALIZE OPTIONS TO DEFAULT VALUES
		update_option('fttb_topdistance', 300);
		update_option('fttb_topspeed', 300);
		update_option('fttb_animation', 'fade');		
		update_option('fttb_animationinspeed', 200);
		update_option('fttb_animationoutspeed', 200);				
		update_option('fttb_scrolltext', __('Top of Page', 'float-to-top-button'));
		update_option('fttb_arrow_img', 'arrow001.png');
		update_option('fttb_opacity', 80);
	}
} // fttb_init_options()


/********************************************************************************************

	PASS THE CURRENT OPTIONS TO JAVASCRIPT VARS

*********************************************************************************************/
function fttb_javascript_vars()
{
	// INITIALIZE OPTIONS (FIRST RUN)
	fttb_init_options();
	
	$plugin_basename = plugin_basename( __FILE__ );
	$plugin_basename = substr($plugin_basename, 0, strpos($plugin_basename, '/'));
	
	$imgurl = plugins_url().'/'.$plugin_basename.'/css/img/';
		
	echo '
<script type="text/javascript">
var fttb_topdistance       = ' .get_option('fttb_topdistance').';
var fttb_topspeed          = ' .get_option('fttb_topspeed').';
var fttb_animation         = "'.get_option('fttb_animation').'";
var fttb_animationinspeed  = ' .get_option('fttb_animationinspeed').';
var fttb_animationoutspeed = ' .get_option('fttb_animationoutspeed').';
var fttb_scrolltext        = "'.__(get_option('fttb_scrolltext'),'float-to-top-button').'";
var fttb_imgurl            = "'.$imgurl.'";
var fttb_arrow_img         = "'.get_option('fttb_arrow_img').'";
var fttb_opacity           = '.get_option('fttb_opacity').';
</script>	
	';
} // fttb_javascript_vars()
if(!is_admin()) add_action('wp_footer', 'fttb_javascript_vars');
?>
