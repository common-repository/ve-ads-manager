<?php
/*-------------------------------------------------
 Start Virtual Ads Meta Boxes
 ------------------------------------------------- */
if(!function_exists('add_ve_ads_manager_meta_box')){ 
	function add_ve_ads_manager_meta_box()
	{
		$screens = get_option('ve_ads_page_type');
		foreach ( $screens as $screen ) {
			echo $screen;
			add_meta_box(
				've-ads-meta-box',
				__( 'Virtual Ads Manager', 'virtualemployee' ),
				'show_ve_ads_manager_meta_box',
				$screen
			);
		}

	}
}
  //Define meta box fields
  $prefix='ve_ads_';
  $ve_ads_meta_box = array(
		'id'      => 've-ads-box',
		'title'   => 'Virtual Ads Manager Section',
		'page'    => '',
		'context' => 'normal',
		'priority'=> 'high',
		'fields'  => 
				  array(
						array(
						'name' => 'Head Section Banner',
						'desc' => 'define Ads script for header section',
						'id'   => $prefix.'head_banner',
						'type' => 'textarea',
						'std'  => ''
						),
						array(
						'name' => 'Sidebar Section Banner',
						'desc' => 'define Ads script for sidebar section',
						'id'   => $prefix.'sidebar_banner',
						'type' => 'textarea',
						'std'  => ''
						),
						array(
						'name' => 'Ads Banner for Footer Section',
						'desc' => 'define Ads script for footer section',
						'id'   => $prefix.'footer_banner',
						'type' => 'textarea',
						'std'  => ''
						),
						array(
						'name' => 'Ads banner above the content',
						'desc' => 'define Ads script for above of content section',
						'id'   => $prefix.'content_above_banner',
						'type' => 'textarea',
						'std'  => ''
						),
						array(
						'name' => 'Ads banner below the content',
						'desc' => 'define Ads script for below of content section',
						'id'   => $prefix.'content_below_banner',
						'type' => 'textarea',
						'std'  => ''
						)
		)
    );
//Display Adds Blog Meta Box
if(!function_exists('show_ve_ads_manager_meta_box')){ 
	function show_ve_ads_manager_meta_box()
	{
		global $ve_ads_meta_box, $post;
		$crnimg='';
		wp_nonce_field( 've_ads_box_field', 've_ads_meta_box_once' );
		echo '<table class="form-table"><tbody>';
		foreach ($ve_ads_meta_box['fields'] as $field) {
			// get current post meta data
			$meta = get_post_meta($post->ID, $field['id'], true);
			echo '<tr>',
			'<td><label for="', $field['id'], '">', $field['name'], '</label>','</td>';
			switch ($field['type']) {
			case 'text':
			echo '<td><input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" />', '<br />', $field['desc'],'</td>';
			break;
			case 'checkbox':
			echo '<td><input type="checkbox" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'],'"', checked( $meta, 'yes' ),' size="30" />', '<br />', $field['desc'],'</td>';
			break;
			case 'image':
				echo '<td><input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" />', '<br />', $field['desc'],'</td>';
				break;
			case 'textarea':
			echo '<td><textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'],'</td>';
			break;
			case 'select':
			echo '<td><select name="', $field['id'], '" id="', $field['id'], '" >';
			$optionVal=$field['options'];
			foreach($optionVal as $optVal):
			if($meta==$optVal){
			$valseleted =' selected="selected"';}else {
				 $valseleted ='';
				}
			echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
		endforeach;
		echo '</select>','<br />',$field['desc'],'</td>';
		break;
		echo '</tr>';
		
		
		}

		}
	echo '</tbody></table>';
	}
}

if(!function_exists('save_ve_ads_manager_meta_box')){ 
	function save_ve_ads_manager_meta_box($post_id) {
		global $ve_ads_meta_box;
		// Check if our nonce is set.
		 if ( ! isset( $_POST['ve_ads_meta_box_once'] ) ) {
				return;
			}
			
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
		}

	    $posttpes = get_option('ve_ads_page_type');
		// check permissions
		if (in_array($_POST['post_type'],$posttpes)) 
		{
			if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} 
		elseif(!current_user_can('edit_post', $post_id)){
		return $post_id;
		}
		//print_r($ve_blog_meta_box['fields']); exit;
		foreach ($ve_ads_meta_box['fields'] as $field) 
		{
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];
			if ($new && $new != $old){
			 update_post_meta($post_id, $field['id'], $new);
			} 
			elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
			}
		}
	}
}
//define action for create new meta boxes
add_action( 'add_meta_boxes', 'add_ve_ads_manager_meta_box' );
//Define action for save to "Blog" Meta Box fields Value
add_action( 'save_post', 'save_ve_ads_manager_meta_box' );
/*-------------------------------------------------
 End Virtual Ads Meta Boxes
 ------------------------------------------------- */
/*-------------------------------------------------
 Start Virtual Ads shortcodes Boxes
 ------------------------------------------------- */

if(!function_exists('ve_ads_manager_head_banner_func')):
function ve_ads_manager_head_banner_func( $atts ) {
	global $post;
	$enableAdson=get_option('ve_ads_page_type');
	if(is_singular($enableAdson))
	{
		$ve_ads_head_banner=get_post_meta($post->ID,'ve_ads_head_banner',true) ? get_post_meta($post->ID,'ve_ads_head_banner',true) : '';
		$getval1=get_option('ve_ads_header_val');
		if($ve_ads_head_banner=='' && $getval1!='')
		{
			$headbanner=$getval1;
		}else
		{
			$headbanner=$ve_ads_head_banner;
		}

	
	}
	
	if(is_home() || is_front_page())
	{
	$headbanner=get_option('ve_ads_home_header_val') ? get_option('ve_ads_home_header_val') : '';
	}

	if($headbanner!='')
	{
	$headbanner = $headbanner;
	}
	return $headbanner;
}
endif;
add_shortcode( 've_head_ads', 've_ads_manager_head_banner_func' );
/** Sidebar section */
if(!function_exists('ve_ads_manager_sidebar_banner_func')):
function ve_ads_manager_sidebar_banner_func( $atts ) {
	global $post;
	$enableAdson=get_option('ve_ads_page_type');
	if(is_singular($enableAdson))
	{
	$ve_ads_sidebar_banner=get_post_meta($post->ID,'ve_ads_sidebar_banner',true) ? get_post_meta($post->ID,'ve_ads_sidebar_banner',true) : '';
	$getval2=get_option('ve_ads_sidebar_val');
	if($ve_ads_sidebar_banner=='' && $getval2!='')
	{
	$sidebarbanner=$getval2;
	}else
	{
		$sidebarbanner=$ve_ads_sidebar_banner;
		}
	}

	if($sidebarbanner!='')
	{
	$sidebarbanner=$sidebarbanner;
	}
	return $sidebarbanner;

}
endif;
add_shortcode( 've_sidebar_ads', 've_ads_manager_sidebar_banner_func' );
/** Footer section */
if(!function_exists('ve_ads_manager_footer_banner_func')):
function ve_ads_manager_footer_banner_func( $atts ) {
	global $post;
	$enableAdson=get_option('ve_ads_page_type');
	if(is_singular($enableAdson)){
		$ve_ads_footer_banner=get_post_meta($post->ID,'ve_ads_footer_banner',true) ? get_post_meta($post->ID,'ve_ads_footer_banner',true) : '';
		$getval3=get_option('ve_ads_footer_val');
		if($ve_ads_footer_banner=='' && $getval3!='')
		{
		$footerbanner=$getval3;
		}else
		{
			$footerbanner=$ve_ads_footer_banner;
			}
	
	}

	if(is_home() || is_front_page())
	{
	$footerbanner=get_option('ve_ads_home_footer_val');
	}

	if($footerbanner!='')
	{
	$footerbanner=$footerbanner;
	}

	return $footerbanner;
}
endif;
add_shortcode( 've_footer_ads', 've_ads_manager_footer_banner_func' );

/*-------------------------------------------------
 End Virtual Ads shortcode Boxes
 ------------------------------------------------- */
 
/*-------------------------------------------------
 Start Virtual Ads content section
 ------------------------------------------------- */
 add_filter( 'the_content', 've_ads_manager_the_content_filter', 20 );
/**
 * Add a icon to the beginning of every post page.
 *
 * @uses is_single()
 */
 if(!function_exists('ve_ads_manager_the_content_filter')):
	function ve_ads_manager_the_content_filter( $content ) {

		global $post;
		$enableAdson=get_option('ve_ads_page_type');
		if(is_singular($enableAdson)){
			
			$ve_ads_content_above_banner=get_post_meta($post->ID,'ve_ads_content_above_banner',true) ? get_post_meta($post->ID,'ve_ads_content_above_banner',true) : get_option('ve_ads_content_above_val');	
			if($ve_ads_content_above_banner!='')
			$content = '<!-- start vritual add manager --><center>'.$ve_ads_content_above_banner.'</center><!-- End vritual add manager -->'.$content;
			$ve_ads_content_below_banner=get_post_meta($post->ID,'ve_ads_content_below_banner',true) ? get_post_meta($post->ID,'ve_ads_content_below_banner',true) : get_option('ve_ads_content_below_val');
			if($ve_ads_content_below_banner!='')
			$content =$content.'<!-- start vritual add manager --><center>'.$ve_ads_content_below_banner.'</center><!-- End vritual add manager -->';
			
			}
		// Returns the content.
		return $content;
	}
endif;
/*-------------------------------------------------
 End Virtual Ads content section
 ------------------------------------------------- */
