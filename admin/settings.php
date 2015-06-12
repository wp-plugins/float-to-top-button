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

	$this->fttb_options['topdistance']       = $_REQUEST['fttb_topdistance'];
	$this->fttb_options['topspeed']          = $_REQUEST['fttb_topspeed'];
	$this->fttb_options['animation']         = $_REQUEST['fttb_animation'];
	$this->fttb_options['animationinspeed']  = $_REQUEST['fttb_animationinspeed'];
	$this->fttb_options['animationoutspeed'] = $_REQUEST['fttb_animationoutspeed'];
	$this->fttb_options['scrolltext']        = $_REQUEST['fttb_scrolltext'];
	$this->fttb_options['arrow_img']         = $_REQUEST['fftb_arrow_img'];
	$this->fttb_options['opacity']           = $_REQUEST['fttb_opacity'];
	
	if (isset($_REQUEST['fttb_disable_mobile']))
		$this->fttb_options['disable_mobile'] = $_REQUEST['fttb_disable_mobile'];
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
  <?php _e( 'Plugin version', 'float-to-top-button' ); ?>
  : v<?php echo $this->fttb_version?> [<?php echo $this->fttb_release_date?>] - <a href="http://cagewebdev.com/index.php/float-to-top-button/" target="_blank">
  <?php _e( 'Plugin page', 'float-to-top-button' ); ?>
  </a> - <a href="https://wordpress.org/plugins/float-to-top-button/" target="_blank">
  <?php _e( 'Download page', 'float-to-top-button' ); ?>
  </a> - <a href="http://cagewebdev.com/index.php/donations-fttb/" target="_blank">
  <?php _e( 'Donation page', 'float-to-top-button' ); ?>
  </a>
</div>

<?php
/***********************************************************************************
 * 	FORM
 ***********************************************************************************/
?>
<div id="fttb-settings-form">
  <form name="fttb_settings" id="fttb_settings" method="post" action="">
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
        <td><select name="fttb_animation" id="fttb_animation">
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
				if ($this->fttb_options['arrow_img'] === $arrows[ $i ])
				{	$checked     = 'checked ';
					$any_checked = true;
				}
				echo '<div class="fftb-arrow-icon"><input name="fftb_arrow_img" id="fftb_arrow_img'.$i.'" type="radio" value="'.$arrows[$i].'" '.$checked.'/><img src="'.$this->imgurl.$arrows[$i].'" align="absmiddle" /></div>'."\n";
			}
			if ( !$any_checked )
			{
			?>
          <script type="text/javascript">
          jQuery('#fftb_arrow_img0').prop('checked', true);
          </script>
          <?php
			}
			?></td>
      </tr>
      <tr>
        <td><?php _e('Opacity of the "Top of Page" image (0-99)', 'float-to-top-button'); ?></td>
        <td><input name="fttb_opacity" id="fttb_opacity" type="text" value="<?php echo $this->fttb_options['opacity'];?>" /></td>
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
