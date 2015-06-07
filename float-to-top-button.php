<?php
$fttb_version      = '1.2.0';
$fttb_release_date = '06/07/2015';
/*
Plugin Name: Float To Top Button
Plugin URI: http://cagewebdev.com/float-to-top-button
Description: This plugin will add a floating scroll to top button to posts / pages
Version: 1.2.0
Date: 06/07/2015
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
{	// v1.1.5
	if(isset($GLOBALS['pagenow']))
		return !is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
	else
		return(!is_admin());
} // fttb_is_regular_page()


/********************************************************************************************

	ADD SCRIPTS

*********************************************************************************************/
// v1.1.2
if (fttb_is_regular_page())
{	// FRONT END - LOAD JAVASCRIPT FILES
	// Since v1.2.0: DISABLE BUTTON ON MOBILE DEVICES
	$fttb_disable_mobile = get_option('fttb_disable_mobile');
    if ($fttb_disable_mobile == "Y" && wp_is_mobile()) { return; }
	function fttb_fe_scripts()
	{	// v1.1.3
		wp_register_script( 'fttb-script', plugins_url('float-to-top-button/js/jquery.scrollUp.min.js'), array('jquery'), '1.0', true);
		wp_enqueue_script( 'fttb-script' );
		// v1.1.3
		wp_register_script( 'fttb-active', plugins_url('float-to-top-button/js/float-to-top-button.js'), array('jquery'), '1.0', true);
		wp_enqueue_script( 'fttb-active' );
	} // fttb_fe_scripts()
	add_action( 'init', 'fttb_fe_scripts' );
}
else
{	// BACK END - LOAD JAVASCRIPT FILES
	function fttb_be_scripts()
	{
		wp_enqueue_script('fttb-validate', plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js',array('jquery'),'0.1',true);
	} // fttb_be_scripts()
	add_action( 'admin_init', 'fttb_be_scripts' );
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

	ADD THE 'FLOAT TO TOP BUTTON SETTINGS' ITEM TO THE SETTINGS MENU

*********************************************************************************************/
function fttb_admin_menu()
{	
	if (function_exists('add_options_page'))
	{	add_options_page(__('Float to Top Button Settings', 'float-to-top-button'), __('Float to Top Button','float-to-top-button'), 'manage_options', 'fttb_settings', 'fttb_settings');
    }
} // fttb_admin_menu()
add_action( 'admin_menu', 'fttb_admin_menu' );


/********************************************************************************************
 *
 *	SHOW A LINK TO THE PLUGIN SETTINGS ON THE MAIN PLUGINS PAGE
 *
 *	Since: v1.1.6
 *
 ********************************************************************************************/
function fttb_settings_link($links)
{ 
  array_unshift($links, '<a href="options-general.php?page=fttb_settings">Settings</a>'); 
  return $links;
} // fttb_settings_link()
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'fttb_settings_link');


/********************************************************************************************

	SHOW SETTINGS PAGE

*********************************************************************************************/
function fttb_settings()
{
	global $fttb_version, $fttb_release_date;
	
	$plugin_basename = plugin_basename( __FILE__ );
	$plugin_basename = substr($plugin_basename, 0, strpos($plugin_basename, '/'));
	
	$imgurl = plugins_url().'/'.$plugin_basename.'/css/img/';
	$imgdir = plugin_dir_path(__FILE__).'css/img/';	
	
	// INITIALIZE SETTINGS (FIRST RUN)	
	fttb_init_settings();

	if (isset($_POST['action']) && $_POST['action']=='save_settings')
	{	// SAVE CHANGED SETTINGS
		update_option('fttb_topdistance', $_REQUEST['fttb_topdistance']);
		update_option('fttb_topspeed', $_REQUEST['fttb_topspeed']);
		update_option('fttb_animation', $_REQUEST['fttb_animation']);		
		update_option('fttb_animationinspeed', $_REQUEST['fttb_animationinspeed']);
		update_option('fttb_animationoutspeed', $_REQUEST['fttb_animationoutspeed']);				
		update_option('fttb_scrolltext', $_REQUEST['fttb_scrolltext']);
		update_option('fttb_arrow_img', $_REQUEST['fftb_arrow_img']);
		update_option('fttb_opacity', $_REQUEST['fttb_opacity']);
		// Since v1.2.0
		$fttb_disable_mobile = 'N';
		if(isset($_REQUEST['fttb_disable_mobile']))
			$fttb_disable_mobile = $_REQUEST['fttb_disable_mobile'];
		update_option('fttb_disable_mobile', $fttb_disable_mobile);	
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
	// Since v1.2.0
	$fttb_disable_mobile    = get_option('fttb_disable_mobile');
?>
<script type="text/javascript">
jQuery().ready(function() {
	jQuery("#fttb_settings").validate({
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
			fttb_topdistance: "<?php _e('Distance from top is a required number','float-to-top-button')?>",
			fttb_topspeed: "<?php _e('Speed back to top is a required number','float-to-top-button')?>",
			fttb_animationinspeed: "<?php _e('Animation in speed is a required number','float-to-top-button')?>",
			fttb_animationoutspeed: "<?php _e('Animation out speed is a required number','float-to-top-button')?>",
			fttb_opacity: "<?php _e('Opacity is a required number (0-99)','float-to-top-button')?>"
		}
	});
});
</script>

<div class="fttb-title-bar">
  <h2>
    <?php _e('Float to Top Button - Settings', 'float-to-top-button'); ?>
  </h2>
</div>
<div class="fttb-intro">
  <?php _e('Plugin version', 'float-to-top-button'); ?>: v<?php echo $fttb_version?> [<?php echo $fttb_release_date?>] - <a href="http://cagewebdev.com/index.php/float-to-top-button/" target="_blank">
  <?php _e('Plugin page', 'float-to-top-button'); ?>
  </a> - <a href="https://wordpress.org/plugins/float-to-top-button/" target="_blank">
  <?php _e('Download page', 'float-to-top-button'); ?>
  </a> - <a href="http://cagewebdev.com/index.php/donations-fttb/" target="_blank">
  <?php _e('Donation page', 'float-to-top-button'); ?>
  </a> </div>
<div id="fttb-settings-form">
  <form name="fttb_settings" id="fttb_settings" method="post" action="">
    <input type="hidden" name="action" value="save_settings" />
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td nowrap="nowrap"><?php _e('Distance from top before showing element (px)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_topdistance" id="fttb_topdistance" type="text" value="<?php echo $fttb_topdistance;?>" required="required" /></td>
      </tr>
      <tr>
        <td><?php _e('Speed back to top (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_topspeed" id="fttb_topspeed" type="text" value="<?php echo $fttb_topspeed;?>" /></td>
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
        <td><input name="fttb_animationinspeed" id="fttb_animationinspeed" type="text" value="<?php echo $fttb_animationinspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Animation out speed (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_animationoutspeed" id="fttb_animationoutspeed" type="text" value="<?php echo $fttb_animationoutspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Text for the button', 'float-to-top-button'); ?></td>
        <td><input name="fttb_scrolltext" id="fttb_scrolltext" type="text" value="<?php echo $fttb_scrolltext;?>" /></td>
      </tr>
      <tr>
        <td valign="top"><?php _e('"Top of Page" image', 'float-to-top-button'); ?></td>
        <td><?php
	$any_checked = false;
	for($i=0; $i<count($arrows); $i++)
	{
		$checked = '';
		if($fttb_arrow_img == $arrows[$i])
		{	$checked = ' checked';
			$any_checked = true;
		}
		echo '<div class="fftb-arrow-icon"><input name="fftb_arrow_img" id="fftb_arrow_img'.$i.'" type="radio" value="'.$arrows[$i].'" '.$checked.' /><img src="'.$imgurl.$arrows[$i].'" align="absmiddle" /></div>'."\n";
	} // for($i=0; $i<count($arrows); $i++)
	if(!$any_checked)
	{
?>
          <script type="text/javascript">		
		jQuery("#fftb_arrow_img0").prop("checked", true);
		</script>
          <?php
	}
?></td>
      </tr>
      <tr>
        <td><?php _e('Opacity of the "Top of Page" image (0-99)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_opacity" id="fttb_opacity" type="text" value="<?php echo $fttb_opacity;?>" /></td>
      </tr>
<?php
// Since v1.2.0
if($fttb_disable_mobile == 'Y')  $fttb_disable_mobile_checked  = ' checked="checked"'; else $fttb_disable_mobile_checked = '';
?>      
      <tr>
        <td><?php _e('Disable the button for mobile devices', 'float-to-top-button'); ?></td>
        <td><input type="checkbox" name="fttb_disable_mobile" id="fttb_disable_mobile" value="Y" <?php echo $fttb_disable_mobile_checked;?> /></td>
      </tr>      
      <tr>
        <td colspan="2"><input class="button-primary button-large fttb-save-button" type='submit' name='info_update' value='<?php echo __('Save Settings','float-to-top-button');?>' /></td>
      </tr>
    </table>
  </form>
</div>
<?php
} // fttb_settings()


/********************************************************************************************

	ADD DEFAULT VALUES (FIRST RUN)

*********************************************************************************************/
function fttb_init_settings()
{
	if(!get_option('fttb_animation'))
	{	// SETTINGS NOT IN DATABASE: INITIALIZE SETTINGS TO DEFAULT VALUES
		update_option('fttb_topdistance', 300);
		update_option('fttb_topspeed', 300);
		update_option('fttb_animation', 'fade');		
		update_option('fttb_animationinspeed', 200);
		update_option('fttb_animationoutspeed', 200);				
		update_option('fttb_scrolltext', __('Top of Page', 'float-to-top-button'));
		update_option('fttb_arrow_img', 'arrow001.png');
		update_option('fttb_opacity', 80);
	}
} // fttb_init_settings()


/********************************************************************************************

	PASS THE CURRENT SETTINGS TO JAVASCRIPT VARS

*********************************************************************************************/
function fttb_javascript_vars()
{
	global $fttb_version, $fttb_release_date;
	
	// INITIALIZE SETTINGS (FIRST RUN)
	fttb_init_settings();
	
	$plugin_basename = plugin_basename( __FILE__ );
	$plugin_basename = substr($plugin_basename, 0, strpos($plugin_basename, '/'));
	
	$imgurl = plugins_url().'/'.$plugin_basename.'/css/img/';
		
	echo '
<!-- START Float to Top Button v'.$fttb_version.' ['.$fttb_release_date.'] | http://cagewebdev.com/float-to-top-button | CAGE Web Design | Rolf van Gelder -->	
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
<!-- END Float to Top Button -->
	';
} // fttb_javascript_vars()
if(!is_admin()) add_action('wp_footer', 'fttb_javascript_vars');
?>
