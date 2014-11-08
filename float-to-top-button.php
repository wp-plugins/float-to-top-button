<?php
/*
Plugin Name: Float To Top Button
Plugin URI: http://cagewebdev.com
Description: This plugin will add a floating scroll to top button to posts / pages
Author: Rolf van Gelder
Author URI: http://cagewebdev.com
Version: 1.1
*/

/********************************************************************************************

	ADD JAVASCRIPTS

*********************************************************************************************/
function fttb_scripts()
{	wp_register_script( 'fttb-script', plugins_url('float-to-top-button/js/jquery.scrollUp.min.js'), false, '1.0', true);
    wp_enqueue_script( 'fttb-script' );
	wp_register_script( 'fttb-active', plugins_url('float-to-top-button/js/float-to-top-button.js'), false, '1.0', true);
    wp_enqueue_script( 'fttb-active' );	
} // fttb_scripts()
add_action( 'init', 'fttb_scripts' );


/********************************************************************************************

	ADD STYLESHEETS

*********************************************************************************************/
function fttb_styles()
{	wp_register_style( 'plugin-style', plugins_url('css/float-to-top-button.css', __FILE__) );
    wp_enqueue_style( 'plugin-style' );
} // fttb_styles()
add_action( 'wp_enqueue_scripts', 'fttb_styles' );


/********************************************************************************************

	ADD THE 'FLOAT TO TOP BUTTON OPTIONS' ITEM TO THE SETTINGS MENU

*********************************************************************************************/
function fttb_admin_menu()
{	
	if (function_exists('add_options_page'))
	{	add_options_page(__('Float to Top Button Options', 'fttb'), __('Float to Top Button Options','fttb'), 'manage_options', 'fttb_options', 'fttb_options_page');
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
		echo "<div class='updated'><p><strong>".__('Float to Top Button - Settings UPDATED!','fttb')."</strong></p></div>";
	}

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
?>

<div id="options-form">
  <form name="options" method="post" action="">
    <input type="hidden" name="action" value="save_options" />
    <h1><?php echo __('Float to Top Button - Options', 'fttb'); ?></h1>
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><?php echo __('Distance from top before showing element (px)', 'fttb'); ?></td>
        <td><input name="fttb_topdistance" type="text" value="<?php echo $fttb_topdistance;?>" /></td>
      </tr>
      <tr>
        <td><?php echo __('Speed back to top (ms)', 'fttb'); ?></td>
        <td><input name="fttb_topspeed" type="text" value="<?php echo $fttb_topspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php echo __('Animation', 'fttb'); ?></td>
        <td><select name="fttb_animation" id="fttb_animation">
            <option value="fade">fade</option>
            <option value="slide">slide</option>
            <option value="none">none</option>
          </select></td>
      </tr>
      <script type="text/javascript">
	  jQuery("#fttb_animation").val("<?php echo $fttb_animation;?>");
	  </script>
      <tr>
        <td><?php echo __('Animation in speed (ms)', 'fttb'); ?></td>
        <td><input name="fttb_animationinspeed" type="text" value="<?php echo $fttb_animationinspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php echo __('Animation out speed (ms)', 'fttb'); ?></td>
        <td><input name="fttb_animationoutspeed" type="text" value="<?php echo $fttb_animationoutspeed;?>" /></td>
      </tr>
      <tr>
        <td><?php echo __('Text for the button', 'fttb'); ?></td>
        <td><input name="fttb_scrolltext" type="text" value="<?php echo $fttb_scrolltext;?>" /></td>
      </tr>
      <tr>
        <td valign="top"><?php echo __('"Top of Page" image', 'fttb'); ?></td>
        <td><?php
for($i=0; $i<count($arrows); $i++)
{
	$checked = '';
	if($fttb_arrow_img == $arrows[$i]) $checked = ' checked';
	echo '<div style="float:left; margin-right:20px; border: solid 1px #d0d0d0; padding: 5px;"><input name="fftb_arrow_img" id="fftb_arrow_img'.$i.'" type="radio" value="'.$arrows[$i].'" '.$checked.' /><img src="'.$imgurl.$arrows[$i].'" align="absmiddle" /></div>';
	
} // for($i=0; $i<count($arrows); $i++)
?></td>
      </tr>
      <tr>
        <td><?php echo __('Opacity of the to top image (0-100)', 'fttb'); ?></td>
        <td><input name="fttb_opacity" type="text" value="<?php echo $fttb_opacity;?>" /></td>
      </tr>      
      <tr>
        <td colspan="2"><input class="button-primary button-large" type='submit' name='info_update' value='<?php echo __('Save Options','fttb');?>' style="font-weight:bold;" /></td>
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
		update_option('fttb_scrolltext', 'Top of Page');
		update_option('fttb_arrow_img', 'arrow1.png');
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
var fttb_scrolltext        = "'.get_option('fttb_scrolltext').'";
var fttb_imgurl            = "'.$imgurl.'";
var fttb_arrow_img         = "'.get_option('fttb_arrow_img').'";
var fttb_opacity           = '.get_option('fttb_opacity').';
</script>	
	';
} // fttb_javascript_vars()
add_action('wp_footer', 'fttb_javascript_vars');
?>
