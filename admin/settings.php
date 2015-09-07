<?php
/***********************************************************************************
 *
 * 	SETTINGS PAGE
 *
 ***********************************************************************************/
if (!function_exists('add_action')) exit;

if (isset($_POST['action']) && 'save_settings' === $_POST['action'])
{	// SAVE SETTINGS
	check_admin_referer('fttb_settings_'.$this->fttb_version);

	$this->fttb_options['topdistance']        = $this->fttb_sanitize_int($_POST['fttb_topdistance'], 4);
	$this->fttb_options['topspeed']           = $this->fttb_sanitize_int($_POST['fttb_topspeed'], 4);
	$this->fttb_options['animation']          = sanitize_text_field($_POST['fttb_animation']);
	$this->fttb_options['animationinspeed']   = $this->fttb_sanitize_int($_POST['fttb_animationinspeed'], 4);
	$this->fttb_options['animationoutspeed']  = $this->fttb_sanitize_int($_POST['fttb_animationoutspeed'], 4);
	$this->fttb_options['scrolltext']         = sanitize_text_field($_POST['fttb_scrolltext']);
	if(isset($_POST['fttb_arrow_img']))
		$this->fttb_options['arrow_img']      = sanitize_text_field($_POST['fttb_arrow_img']);
	else
		$this->fttb_options['arrow_img']      = '';
	$this->fttb_options['arrow_img_url']      = sanitize_text_field($_POST['fttb_arrow_img_url']);
	$this->fttb_options['position']           = sanitize_text_field($_POST['fttb_position']);
	$this->fttb_options['spacing_horizontal'] = sanitize_text_field($_POST['fttb_spacing_horizontal']);
	$this->fttb_options['spacing_vertical']   = sanitize_text_field($_POST['fttb_spacing_vertical']);	
	$this->fttb_options['opacity_out']        = $this->fttb_sanitize_int($_POST['fttb_opacity_out'], 2);
	$this->fttb_options['opacity_over']       = $this->fttb_sanitize_int($_POST['fttb_opacity_over'], 2);
	$this->fttb_options['zindex']             = $this->fttb_sanitize_int($_POST['fttb_zindex'], 10);
	
	if (isset($_POST['fttb_disable_mobile']))
		$this->fttb_options['disable_mobile'] = sanitize_text_field($_POST['fttb_disable_mobile']);
	else
		$this->fttb_options['disable_mobile'] = 'N';
		
	update_option('fttb_options', $this->fttb_options);
	echo '<div class="updated"><p><strong>'.__('Float to Top Button - Settings UPDATED!', 'float-to-top-button').'</strong></p></div>';
} // if (isset($_POST['action']) && 'save_settings' === $_POST['action'])


/***********************************************************************************
 * 	FIND AVAILABLE ARROW IMAGES
 ***********************************************************************************/
$arrows = array();
foreach (glob($this->imgdir.'arrow*.png') as $file)
{	$fn = substr($file, strrpos( $file, '/' ) + 1);
	array_push($arrows, $fn);
}
?>

<?PHP
/***********************************************************************************
 * 	TITLE BAR
 ***********************************************************************************/
?>
<div class="fttb-title-bar">
  <h2>
    <?php _e( 'Float to Top Button - Settings', 'float-to-top-button' ); ?>
  </h2>
</div>

<?php
/***********************************************************************************
 * 	INTRO
 ***********************************************************************************/
?>
<div class="fttb-intro">
  <?php _e( 'Plugin version', 'float-to-top-button' ); ?>: v<?php echo $this->fttb_version?> [<?php echo $this->fttb_release_date?>] - <a href="http://cagewebdev.com/index.php/float-to-top-button/" target="_blank">
  <?php _e( 'Plugin page', 'float-to-top-button' ); ?></a> - <a href="https://wordpress.org/plugins/float-to-top-button/" target="_blank">
  <?php _e( 'Download page', 'float-to-top-button' ); ?></a> - <a href="http://rvg.cage.nl/" target="_blank">
  <?php _e( 'Author', 'order-your-posts-manually' ); ?></a> - <a href="http://cagewebdev.com/" target="_blank">
  <?php _e( 'Company', 'order-your-posts-manually' ); ?></a> - <a href="http://cagewebdev.com/index.php/donations-fttb/" target="_blank">
  <?php _e( 'Donation page', 'float-to-top-button' ); ?></a>
</div>

<?php
/***********************************************************************************
 * 	FORM
 ***********************************************************************************/
?>
<script type="text/javascript">
function fttb_custom_img_onchange()
{
	var src = jQuery("#fttb_arrow_img_url").val();
	if(src != '')
	{
		jQuery("#fttb_custom_img").attr("src", src);
		jQuery("#fttb_icon_preview").show();
	}
	else
		jQuery("#fttb_icon_preview").hide();
} // fttb_custom_img_onchange()
function  fttb_arrow_img_onchange()
{
	jQuery("#fttb_arrow_img_url").val("");
	jQuery("#fttb_icon_preview").hide();
} // fttb_arrow_img_onchange()
</script>
<div id="fttb-settings-form">
  <form name="fttb-settings" id="fttb-settings" method="post" action="">
    <?php wp_nonce_field('fttb_settings_'.$this->fttb_version); ?>
    <input type="hidden" name="action" value="save_settings" />
    <table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td nowrap="nowrap"><?php _e('Distance from top before showing element (px)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_topdistance" id="fttb_topdistance" type="text" value="<?php echo $this->fttb_options['topdistance'];?>" required="required" /></td>
      </tr>
      <tr>
        <td><?php _e('Speed back to top (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_topspeed" id="fttb_topspeed" type="text" value="<?php echo $this->fttb_options['topspeed'];?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Animation', 'float-to-top-button'); ?></td>
        <td><select name="fttb_animation" id="fttb_animation" class="fttb_animation">
            <option value="fade">
            <?php _e('fade', 'float-to-top-button');?>
            </option>
            <option value="slide">
            <?php _e('slide', 'float-to-top-button');?>
            </option>
            <option value="none">
            <?php _e('none', 'float-to-top-button');?>
            </option>
          </select></td>
      </tr>
      <script type="text/javascript">
      jQuery('#fttb_animation').val("<?php echo $this->fttb_options['animation'];?>");
      </script>
      <tr>
        <td><?php _e('Animation in speed (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_animationinspeed" id="fttb_animationinspeed" type="text" value="<?php echo $this->fttb_options['animationinspeed'];?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Animation out speed (ms)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_animationoutspeed" id="fttb_animationoutspeed" type="text" value="<?php echo $this->fttb_options['animationoutspeed'];?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Text for the button', 'float-to-top-button'); ?></td>
        <td><input name="fttb_scrolltext" id="fttb_scrolltext" type="text" value="<?php echo $this->fttb_options['scrolltext'];?>" /></td>
      </tr>
      <tr>
        <td valign="top"><?php _e('"Top of Page" image', 'float-to-top-button'); ?></td>
        <td><?php
			$any_checked = false;
			for ($i = 0; $i < count( $arrows ); $i++ )
			{
				$checked = '';
				if ($this->fttb_options['arrow_img'] === $arrows[ $i ] && $this->fttb_options['arrow_img_url'] == '')
				{	$checked     = 'checked ';
					$any_checked = true;
				}
				echo '<div class="fttb-arrow-icon"><input name="fttb_arrow_img" id="fttb_arrow_img'.$i.'" type="radio" value="'.$arrows[$i].'" onchange="fttb_arrow_img_onchange();" '.$checked.'/><img src="'.$this->imgurl.$arrows[$i].'" align="absmiddle" /></div>'."\n";
			}
			if ( !$any_checked && $this->fttb_options['arrow_img_url'] == '')
			{
			?>
          <script type="text/javascript">
          jQuery('#fttb_arrow_img0').prop('checked', true);
          </script>
          <?php
			}
			?></td>
      </tr>
      <?php
	  $custom_image = '';
	  $custom_css   = 'none';
	  if($this->fttb_options['arrow_img_url'] != '')
	  {	$custom_image = $this->fttb_options['arrow_img_url'];
	  	$custom_css   = 'block';
	  }
	  ?>
      <tr>
        <td valign="top"><?php _e('URL of a custom "Top of Page" image<br />Overrules the image selection above!', 'float-to-top-button'); ?></td>
        <td valign="top"><input name="fttb_arrow_img_url" id="fttb_arrow_img_url" type="text" value="<?php echo $this->fttb_options['arrow_img_url'];?>" onchange="fttb_custom_img_onchange()" /><br />
          <div class="fttb-arrow-icon" style="display:<?php echo $custom_css; ?>" id="fttb_icon_preview"><img id="fttb_custom_img" src="<?php echo $custom_image?>" align="absmiddle" /></div>
        </td>
      </tr>      
      <tr>
        <td valign="top"><?php _e('Position of the Button', 'float-to-top-button'); ?></td>
        <td valign="top"><select name="fttb_position" id="fttb_position">
            <option value="lowerright"><?php _e('Lower Right', 'float-to-top-button');?></option>
            <option value="lowerleft"><?php _e('Lower Left', 'float-to-top-button');?></option>
            <option value="upperright"><?php _e('Upper Right', 'float-to-top-button');?></option>
            <option value="upperleft"><?php _e('Upper Left', 'float-to-top-button');?></option>
          </select>
        </td>
      </tr>
      <script type="text/javascript">
      jQuery('#fttb_position').val("<?php echo $this->fttb_options['position'];?>");
      </script>
      <tr>
        <td valign="top"><?php _e('Horizontal spacing', 'float-to-top-button'); ?></td>
        <td valign="top"><select name="fttb_spacing_horizontal" id="fttb_spacing_horizontal">
            <option value="15px">15px</option>
            <option value="20px">20px</option>
            <option value="50px">50px</option>
            <option value="75px">75px</option>
            <option value="100px">100px</option>
          </select></td>
      </tr>
      <script type="text/javascript">
      jQuery('#fttb_spacing_horizontal').val("<?php echo $this->fttb_options['spacing_horizontal'];?>");
      </script>
      <tr>
        <td valign="top"><?php _e('Vertical spacing', 'float-to-top-button'); ?></td>
        <td valign="top"><select name="fttb_spacing_vertical" id="fttb_spacing_vertical">
            <option value="15px">15px</option>
            <option value="20px">20px</option>
            <option value="50px">50px</option>
            <option value="75px">75px</option>
            <option value="100px">100px</option>
          </select></td>
      </tr>
      <script type="text/javascript">
      jQuery('#fttb_spacing_vertical').val("<?php echo $this->fttb_options['spacing_vertical'];?>");
      </script>               
      <tr>
        <td><?php _e('Opacity of the image, mouseout (0-99)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_opacity_out" id="fttb_opacity_out" type="text" value="<?php echo $this->fttb_options['opacity_out'];?>" /></td>
      </tr>
      <tr>
        <td><?php _e('Opacity of the image, mouseover (0-99)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_opacity_over" id="fttb_opacity_over" type="text" value="<?php echo $this->fttb_options['opacity_over'];?>" /></td>
      </tr>      
      <?php
		$fttb_disable_mobile_checked = '';
		if(isset($this->fttb_options['disable_mobile']))
			if ('Y' === $this->fttb_options['disable_mobile'])
				$fttb_disable_mobile_checked = ' checked="checked"';
		?>
      <tr>
        <td><?php _e('Disable the button for mobile devices', 'float-to-top-button'); ?></td>
        <td><input type="checkbox" name="fttb_disable_mobile" id="fttb_disable_mobile" value="Y" <?php echo $fttb_disable_mobile_checked;?> /></td>
      </tr>
      <tr>
        <td><?php _e('Z-index of the overlay', 'float-to-top-button'); ?></td>
        <td><input name="fttb_zindex" id="fttb_zindex" type="text" value="<?php echo $this->fttb_options['zindex'];?>" /></td>
      </tr>      
      <tr>
        <td colspan="2"><input class="button-primary button-large fttb-save-button" type='submit' name='info_update' value='<?php echo __('Save Settings', 'float-to-top-button');?>' /></td>
      </tr>
    </table>
  </form>
</div>
<?php
include(ABSPATH.'wp-admin/admin-footer.php');
// JUST TO BE SURE
die;
?>
