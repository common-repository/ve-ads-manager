<?php
/*
Plugin Name: VE Ads Manager
Plugin URI: http://www.virtualemployee.com/
Description: This plugin will help to manage your ads on your site.
Version: 1.2
Author: virtualemployee
Author URI: http://www.virtualemployee.com
Text Domain: ve-importer
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    /**
     * Determine value of option $name from database, $default value or $params,
     * save it to the db if needed and return it.
     *
     * @param string $name
     * @param mixed  $default
     * @param array  $params
     * @return string
     */
    /** Define Action for register "Virtual Ads" Options */
	add_action('admin_init','ve_ads_manager_register_settings_init');
	if(!function_exists('ve_ads_manager_register_settings_init')):
		function ve_ads_manager_register_settings_init(){
			 register_setting('ve_ads_manager_setting_options','ve_ads_header_val');
			 register_setting('ve_ads_manager_setting_options','ve_ads_sidebar_val');
			 register_setting('ve_ads_manager_setting_options','ve_ads_footer_val');  
			 register_setting('ve_ads_manager_setting_options','ve_ads_content_above_val');
			 register_setting('ve_ads_manager_setting_options','ve_ads_content_below_val');
			 register_setting('ve_ads_manager_setting_options','ve_ads_home_header_val');
			 register_setting('ve_ads_manager_setting_options','ve_ads_home_footer_val');
			 register_setting('ve_ads_manager_setting_options','ve_ads_page_type');
		} 
	endif;
    /**
     * Plugin's interface
     *
     * @return void
     */
	if(!function_exists('ve_ads_manager_form')):
		function ve_ads_manager_form() 
		{
		?>
			<div id="virtual-settings"> 
			<div class="wrap">
				<h1>VE Ads Manager Settings</h1><a href="http://www.virtualemployee.com/contactus">Click here</a> for send to your query on plugin support<hr />
				 <form action="options.php" method="post" id="ve-ads-manager-admin-form">
					 
					<table class="form-table">
					<tr valign="top">
					<th scope="row" nowrap>Choose post type where want to display Ads section </th>
					<td><?php $ve_ads_page_type=get_option('ve_ads_page_type');?>
					<select name="ve_ads_page_type[]" id="ve_ads_page_type" style="width:500px;" multiple>
						<?php 
						$args = array(
						   'public'   => true,
						   '_builtin' => false
						);

						$output = 'names'; // names or objects, note names is the default
						$operator = 'and'; // 'and' or 'or'

						$post_types = get_post_types( $args, $output, $operator ); 
						array_push($post_types,'post');array_push($post_types,'page');
						foreach ( $post_types  as $post_type ) {

							echo '<option value="'.$post_type.'" '.selected(true, in_array($post_type, $ve_ads_page_type), false).'>'.$post_type.'</option>';
						}

						?>
					</select><br><i>(Ads section will be publish below of content editor on edit screen.)</i></td>
					</tr>
					<tr valign="top">
					<th scope="row">Define default Ads for header section</th>
					<td><textarea rows="5" cols="60" name="ve_ads_header_val" placeholder="Add default ads code here for head section"><?php echo get_option('ve_ads_header_val'); ?></textarea>
					<br><i>Use <b>[ve_head_ads]</b> shortcode to add banner into header section</i></td>
					</tr>
					 
					<tr valign="top">
					<th scope="row">Define default Ads for sidebar section </th>
					<td><textarea rows="5" cols="60" name="ve_ads_sidebar_val" placeholder="Add default ads code here for sidebar section"><?php echo get_option('ve_ads_sidebar_val'); ?></textarea>
					<br><i>Use <b>[ve_sidebar_ads]</b> shortcode to add banner into sidebar </i></td>
					</tr>
					
					<tr valign="top">
					<th scope="row">Define default Ads for footer section</th>
					<td><textarea rows="5" cols="60" name="ve_ads_footer_val" placeholder="Add default ads code here for footer section"><?php echo get_option('ve_ads_footer_val'); ?></textarea>
					<br><i>Use <b>[ve_footer_ads]</b> shortcode to add banner into footer section</i></td>
					</tr>
					<tr valign="top">
					<th scope="row">Define default Ads above the content </th>
					<td><textarea rows="5" cols="60" name="ve_ads_content_above_val" placeholder="Add default ads code here just above of content"><?php echo get_option('ve_ads_content_above_val'); ?></textarea></td>
					</tr>
					<tr valign="top">
					<th scope="row">Define default Ads  below the content section </th>
					<td><textarea rows="5" cols="60" name="ve_ads_content_below_val" placeholder="Add default ads code here just below of content"><?php echo get_option('ve_ads_content_below_val'); ?></textarea></td>
					</tr>
				<hr>
				<tr valign="top">
					<th scope="row">Define home page Ads for header section</th>
					<td><textarea rows="5" cols="60" name="ve_ads_home_header_val" placeholder="Add home page ads code here for head section"><?php echo get_option('ve_ads_home_header_val'); ?></textarea>
					<br><i>Use <b>[ve_head_ads]</b> shortcode to add banner into home page header section</i></td>
					</tr>
				<tr valign="top">
					<th scope="row">Define home page Ads for footer section</th>
					<td><textarea rows="5" cols="60" name="ve_ads_home_footer_val" placeholder="Add home page ads code here for footer section"><?php echo get_option('ve_ads_home_footer_val'); ?></textarea>
					<br><i>Use <b>[ve_footer_ads]</b> shortcode to add banner into home page footer section</i></td>
					</tr>
				</table>
					<span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
					 <?php settings_fields('ve_ads_manager_setting_options'); ?>
				</form>
				<p>&nbsp;</p>
				<hr>
				<p><h2>Shortcodes</h2></p>

				<table class="form-table">
				<tr valign="top">
				<th scope="row">[ve_head_ads]</th>
				<td> Use this shortcode for display Ads in header section</td>
				</tr>
				<tr valign="top">
				<th scope="row">[ve_sidebar_ads]</th>
				<td> Use this shortcode for display Ads in sidebar section</td>
				</tr>
				<tr valign="top">
				<th scope="row">[ve_footer_ads]</th>
				<td> Use this shortcode for display Ads in footer setion</td>
				</tr>
				</table>
			</div><!-- end wrap -->
			</div>
		<?php
		 }
	endif;
	/** add js into admin footer */
	if(isset($_GET['page']) && $_GET['page']=='ve-ads-manager'){
	add_action('admin_footer','init_ve_ads_manager_admin_scripts');
		if(!function_exists('init_ve_ads_manager_admin_scripts')):
			function init_ve_ads_manager_admin_scripts()
			{
			  echo $script='<style type="text/css">
				#virtual-settings {width: 90%; padding: 10px; margin: 10px;}
				 #virtual-settings label{width: 100%;display:block;}
				</style>';
			}
		endif;
	}	
	// Add settings link to plugin list page in admin
	if(!function_exists('ve_ads_manager_settings_link')):
		function ve_ads_manager_settings_link( $links ) {
		  $settings_link = '<a href="options-general.php?page=ve-ads-manager">' . __( 'Settings', 'virtualemployee' ) . '</a>';
		   array_unshift( $links, $settings_link );
		  return $links;
		}
	endif;
	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 've_ads_manager_settings_link' );
	// admin menu
	if(!function_exists('ve_ads_manager_admin_menu')):
		function ve_ads_manager_admin_menu() {
			add_submenu_page('options-general.php','VE Ads Manager', 'VE Ads Manager', 'manage_options','ve-ads-manager','ve_ads_manager_form');
		}
	endif;
	add_action('admin_menu', 've_ads_manager_admin_menu');

   require dirname(__FILE__).'/lib/class.php';
?>
