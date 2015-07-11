<?php
/*
Plugin Name: SEO Advance Plugin
Version: 1.0
Plugin URI: https://profiles.wordpress.org/rajeshzala/
Description: SEO Advance plugin is used to add browser title, meta description & meta keywords to your site. It also provide you facilities to add google verifiction tag, msn verificatons tag, google authorship tag & google publisher tag. It helps to add google analytical code & extra metas to your WordPress site. It provides powerful tool to help you your site to rank in search engine.
Author: Rajesh Jhala
Author URI: https://profiles.wordpress.org/rajeshzala/
*/
/******************************************************************************************/
/******************************************************************************************/
/******************************************************************************************/
define('SEO_VERSION', '1.0');
define('PLUGIN_NAME', 'SEO Advance plugin');

register_activation_hook( __FILE__, 'seo_plugin_install' );
function seo_plugin_install(){
global $wp_version;
	if ( version_compare( $wp_version, '3.9.1', '<' ) ) {
	wp_die( 'This plugin requires WordPress version 3.9.1 or higher.' );
	}
}

register_deactivation_hook( __FILE__, 'seo_plugin_deactivate' );
function seo_plugin_deactivate(){ }

// Add meta boxes to pages
add_action( 'add_meta_boxes', 'seo_meta_box_add' );
function seo_meta_box_add() {
    add_meta_box( 'seo-meta-box-id', 'SEO Advance Settings', 'seo_meta_box_cb', 'page', 'normal', 'high' );
    add_meta_box( 'seo-meta-box-id', 'SEO Advance Settings', 'seo_meta_box_cb', 'post', 'normal', 'high' );
    add_meta_box( 'seo-meta-box-id', 'SEO Advance Settings', 'seo_meta_box_cb', 'product', 'normal', 'high' );
}
function seo_meta_box_cb( $post ) {
    $values = get_post_custom( $post->ID );
    $pagetitle = isset( $values['seo_pagetitle'] ) ? esc_attr( $values['seo_pagetitle'][0] ) : '';
    $pagedesc = isset( $values['seo_metadesc'] ) ? esc_attr( $values['seo_metadesc'][0] ) : '';
    $pagekewy = isset( $values['seo_metakeyw'] ) ? esc_attr( $values['seo_metakeyw'][0] ) : '';
    $seo_robots = isset( $values['seo_meta_robots'] ) ? esc_attr( $values['seo_meta_robots'][0] ) : '';
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

echo '<p><label for="seo_pagetitle"><strong>Page Title: </strong></label><br />'."\n";
echo '<input type="text" name="seo_pagetitle" id="seo_pagetitle" value="'. $pagetitle .'" placeholder="'. $pagetitle .'" style="width:100%;" />'."\n";
echo '<span class="description">Add browser title here</span></p>'."\n";
echo '<p><label for="seo_metadesc"><strong>Meta Description: </strong></label><br />'."\n";
echo '<textarea name="seo_metadesc" id="seo_metadesc" value="'. $pagedesc .'" placeholder="'. $pagedesc .'" style="width:100%;">'. $pagedesc .'</textarea>'."\n";
echo '<span class="description">Add meta description here</span></p>'."\n";
echo '<p><label for="seo_metakeyw"><strong>Meta Keywords: </strong></label><br />'."\n";
echo '<textarea name="seo_metakeyw" id="seo_metakeyw" value="'. $pagekewy .'" placeholder="'. $pagekewy .'" style="width:100%;">'. $pagekewy .'</textarea>'."\n";
echo '<span class="description">Add meta keywords here</span></p>'."\n";
echo '<p><label for="seo_meta_robots"><strong>Disallow Page in Meta Robots: </strong></label>'."\n";
echo '<select name="seo_meta_robots">'."\n";
echo '<option value="allow" '; if($seo_robots == "allow"){ echo ' selected="selected"'; } echo '>Allow</option>'."\n";
echo '<option value="disallow" '; if($seo_robots == "disallow"){ echo ' selected="selected"';} echo '>Disallow</option>'."\n";
echo '</select>'."\n";
echo '<br /><span class="description">Allow or Disallow page in meta robots</span></p>'."\n";
}

add_action( 'save_post', 'seo_meta_box_save' );
function seo_meta_box_save( $post_id ) {
    //cheking for auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    //verify for nonce field
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
    //checcking current user privilege to editing
    if( !current_user_can( 'edit_post', $post_id ) ) return;

    //saving datas for pagetitle
    if( isset( $_POST['seo_pagetitle'] ) )
        update_post_meta( $post_id, 'seo_pagetitle', sanitize_text_field( $_POST['seo_pagetitle'] ) );

    //saving datas for meta description
    if( isset( $_POST['seo_metadesc'] ) )
        update_post_meta( $post_id, 'seo_metadesc', sanitize_text_field( $_POST['seo_metadesc'] ) );

    //saving datas for meta keywords
    if( isset( $_POST['seo_metakeyw'] ) )
        update_post_meta( $post_id, 'seo_metakeyw', sanitize_text_field( $_POST['seo_metakeyw'] ) );

    //saving datas for meta robots
    if( isset( $_POST['seo_meta_robots'] ) )
        update_post_meta( $post_id, 'seo_meta_robots', sanitize_text_field( $_POST['seo_meta_robots'] ) );
}

/*******************for category***********************/
//add extra fields to category edit form hook
add_action('category_edit_form_fields', 'extra_category_fields', 10, 1);
add_action('product_cat_edit_form_fields', 'extra_category_fields', 10, 1);

//add extra fields to category edit form
function extra_category_fields( $tag ) {
//checking for existing ID
$t_id = $tag->term_id;
$cat_meta = get_option("seo_category_$t_id");

$cat_pagetitle = isset( $cat_meta['seo_pagetitle'] ) ? sanitize_text_field( $cat_meta['seo_pagetitle'] ) : '';
$cat_pagedesc = isset( $cat_meta['seo_metadesc'] ) ? sanitize_text_field( $cat_meta['seo_metadesc'] ) : '';
$cat_pagekewy = isset( $cat_meta['seo_metakeyw'] ) ? sanitize_text_field( $cat_meta['seo_metakeyw'] ) : '';

echo '<tr class="form-field">'."\n";
echo '<th scope="row" valign="top"><label for="cat_page_title"><strong>Page Title: </strong></label></th>'."\n";
echo '<td>'."\n";
echo '<input type="text" name="Cat_meta[seo_pagetitle]" id="Cat_meta[seo_pagetitle]" size="3" style="width:95%;" value="'. $cat_pagetitle .'"><br />'."\n";
echo '<span class="description">Add browser title here</span>'."\n";
echo '</td>'."\n";
echo '</tr>'."\n";
echo '<tr class="form-field">'."\n";
echo '<th scope="row" valign="top"><label for="cat_meta_desc"><strong>Meta Description: </strong></label></th>'."\n";
echo '<td>'."\n";
echo '<textarea name="Cat_meta[seo_metadesc]" id="Cat_meta[seo_metadesc]" style="width:95%;">'. $cat_pagedesc  .'</textarea><br />'."\n";
echo '<span class="description">Add meta description here</span>'."\n";
echo '</td>'."\n";
echo '</tr>'."\n";
echo '<tr class="form-field">'."\n";
echo '<th scope="row" valign="top"><label for="cat_meta_keyw"><strong>Meta Keywords: </strong></label></th>'."\n";
echo '<td>'."\n";
echo '<textarea name="Cat_meta[seo_metakeyw]" id="Cat_meta[seo_metakeyw]" style="width:95%;">'. $cat_pagekewy .'</textarea><br />'."\n";
echo '<span class="description">Add meta keywords here</span>'."\n";
echo '</td>'."\n";
echo '</tr>'."\n";
}

//saving extra category extra fields hook
add_action ( 'edited_category', 'save_extra_category_fileds');
add_action ( 'edited_product_cat', 'save_extra_category_fileds');
//saving extra category extra fields
function save_extra_category_fileds( $term_id ) {
  if ( isset( $_POST['Cat_meta'] ) ) {
  $t_id = $term_id;
  $cat_meta = get_option( "seo_category_$t_id");
  $cat_keys = array_keys($_POST['Cat_meta']);
    foreach ($cat_keys as $key){
    if (isset($_POST['Cat_meta'][$key])){
      $cat_meta[$key] = $_POST['Cat_meta'][$key];
    }
    }
  //saving category option array
  update_option( "seo_category_$t_id", $cat_meta );
  }
}

add_action( 'wp_head', 'seo_meta_filter' );
function seo_meta_filter() {
$my_fields = get_option('seo_plugin');
$seo_homepage_title = $my_fields['seo_homepage_title'];
$seo_homepage_meta_desc = $my_fields['seo_homepage_meta_desc'];
$seo_homepage_meta_keyw = $my_fields['seo_homepage_meta_keyw'];
$seo_google_verification_tag = $my_fields['seo_google_verification_tag'];
$seo_msn_verification_tag = $my_fields['seo_msn_verification_tag'];
$seo_google_publisher_tag = $my_fields['seo_google_publisher_tag'];
$seo_google_author_tag = $my_fields['seo_google_author_tag'];
$seo_google_analytical_code = $my_fields['seo_google_analytical_code'];
$seo_extra_header_code = $my_fields['seo_extra_header_code'];
$seo_google_verification_tag = str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_google_verification_tag))));
$seo_msn_verification_tag = str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_msn_verification_tag))));
$seo_google_analytical_code = str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_google_analytical_code))));
$seo_extra_header_code = str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_extra_header_code))));
$seo_page_url = get_permalink();

if( is_home() || is_front_page() ){
  $post_meta_desc = $seo_homepage_meta_desc;
  $post_meta_keyw = $seo_homepage_meta_keyw;
  $post_meta_robots = get_post_meta( get_the_ID(), 'seo_meta_robots', true );
  $seo_page_url = get_site_url().'/';
}elseif( is_page() || is_single() ){
  $post_meta_desc = get_post_meta( get_the_ID(), 'seo_metadesc', true );
  $post_meta_keyw = get_post_meta( get_the_ID(), 'seo_metakeyw', true );
  $post_meta_robots = get_post_meta( get_the_ID(), 'seo_meta_robots', true );
}elseif( is_category() || has_term('product_cat')){
  $category = get_queried_object();
  $cat_id = $category->term_id;
  $cat_meta = get_option( "seo_category_$cat_id");
  $post_meta_desc = $cat_meta['seo_metadesc'];
  $post_meta_keyw = $cat_meta['seo_metakeyw'];
}else{
  $post_meta_desc = '';
  $post_meta_keyw = '';
}

if($post_meta_robots == 'disallow'){
  $post_meta_robots = 'noindex,nofollow';
}elseif( is_404() ){
  $post_meta_robots = 'noindex,nofollow';
}else{
  $post_meta_robots = 'index,follow';
}

echo '<!-- Generated from '. PLUGIN_NAME .' '. SEO_VERSION .' -->'."\n";
echo '<meta name="description" content="'. $post_meta_desc .'" />'."\n";
echo '<meta name="keywords" content="'. $post_meta_keyw .'" />'."\n";
echo '<meta name="robots" content="'. $post_meta_robots .'" />'."\n\n";

if( is_home() || is_front_page() ){
    if($seo_homepage_title != ''){
        $title = $seo_homepage_title;
    }else{
      $title = get_the_title().' | '. get_bloginfo('name');
    }
}elseif( is_page() || is_single() ){
$meta_pg_title = get_post_meta( get_the_ID(), 'seo_pagetitle', true );
    if($meta_pg_title != ''){
        $title = $meta_pg_title;
    }else{
        $title = get_the_title().' | '. get_bloginfo('name').' 1';
    }
}elseif( is_category() || has_term('product_cat') ){
$category = get_queried_object();
$cat_id = $category->term_id;
  $cat = single_cat_title("", false);
  $cat_meta = get_option( "seo_category_$cat_id");
  if($cat_meta['seo_pagetitle'] != ''){
    $title = $cat_meta['seo_pagetitle'];
  }else{
    $title = $cat.' | '. get_bloginfo('name').' 2';
    }
}else{}

echo '<meta property="og:locale" content="'. get_bloginfo( 'language' ) .'" />'."\n";
echo '<meta property="og:title" content="'. $title .'" />'."\n";
echo '<meta property="og:description" content="'. $post_meta_desc .'" />'."\n";
echo '<meta property="og:url" content="'. $seo_page_url .'" />'."\n";
echo '<meta property="og:site_name" content="'. get_bloginfo('name') .'" />'."\n";

if($seo_google_verification_tag != ''){ echo $seo_google_verification_tag ."\n"; }
if($seo_msn_verification_tag != ''){ echo $seo_msn_verification_tag."\n"; }

if($seo_google_author_tag != ''){ echo '<link rel="author" href="'. $seo_google_author_tag .'" />'."\n"; }
if($seo_google_publisher_tag != ''){  echo '<link rel="publisher" href="'. $seo_google_publisher_tag.'" />'."\n"; }

if($seo_extra_header_code != ''){ echo $seo_extra_header_code."\n"; }

if($seo_google_analytical_code != ''){
echo '<!-- Google Analytical Code -->'."\n";
echo $seo_google_analytical_code."\n";
echo '<!-- / Google Analytical Code -->'."\n";
}

echo "\n".'<!-- / '. PLUGIN_NAME .' -->'."\n\n";
}

add_filter( 'wp_title', 'filter_wp_title', 100);
function filter_wp_title( $title ){
$my_fields = get_option('seo_plugin');
$seo_homepage_title = $my_fields['seo_homepage_title'];

$cat_id = get_query_var('cat');
$cat_data = get_option("category_$cat_id");
if( is_home() || is_front_page() ){
    if($seo_homepage_title != ''){
        $title = $seo_homepage_title;
    }else{
      $title = get_the_title().' | '. get_bloginfo('name');
    }
}elseif( is_page() || is_single() ){
$meta_pg_title = get_post_meta( get_the_ID(), 'seo_pagetitle', true );
    if($meta_pg_title != ''){
        $title = $meta_pg_title;
    }else{
      $title = get_the_title().' | '. get_bloginfo('name');
    }
}elseif(is_category() || has_term('product_cat') ){
$category = get_queried_object();
$cat_id = $category->term_id;

  $cat = single_cat_title("", false);
  $cat_meta = get_option( "seo_category_$cat_id");
  if($cat_meta['seo_pagetitle'] != ''){
    $title = $cat_meta['seo_pagetitle'];
  }else{
    $title = $cat.' | '. get_bloginfo('name');
    }
}else{}
 return( $title );
}

function seo_plugin_options() {
if ( !current_user_can( 'manage_options' ) ) {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}
include('seo-advance_import_admin_settings.php');
}

add_action('admin_menu', 'seo_plugin_menu_actions');
function seo_plugin_menu_actions() {
add_menu_page( 'SEO Advance Setting', 'SEO Settings', 'manage_options', 'seo_plugin_settings', 'seo_plugin_options', 'dashicons-admin-tools', 81 );
}

add_action( 'publish_post', 'eg_create_sitemap');
add_action( "save_post", "eg_create_sitemap" );
function eg_create_sitemap() {
    if ( str_replace( '-', '', get_option( 'gmt_offset' ) ) < 10 ) {
        $tempo = '-0' . str_replace( '-', '', get_option( 'gmt_offset' ) );
    } else {
        $tempo = get_option( 'gmt_offset' );
    }
    if( strlen( $tempo ) == 3 ) { $tempo = $tempo . ':00'; }
    $postsForSitemap = get_posts( array(
        'numberposts' => -1,
        'orderby'     => 'modified',
        'post_type'   => array('page', 'post', 'product'),
        'order'       => 'DESC'
    ) );
    $sitemap .= '<?xml version="1.0" encoding="UTF-8"?>' . '<?xml-stylesheet type="text/xsl" href="' .
        esc_url( plugins_url( 'xml/sitemap.xsl', __FILE__ ) ) . '"?>';
    $sitemap .= "\n" . '<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
    $sitemap .= "\t" . '<url>' . "\n" .
        "\t\t" . '<loc>' . esc_url( home_url( '/' ) ) . '</loc>' .
        "\n\t\t" . '<lastmod>' . date( "Y-m-d\TH:i:s", current_time( 'timestamp', 0 ) ) . $tempo . '</lastmod>' .
        "\n\t\t" . '<changefreq>daily</changefreq>' .
        "\n\t\t" . '<priority>1.0</priority>' .
        "\n\t" . '</url>' . "\n";
    foreach( $postsForSitemap as $post ) {
        setup_postdata( $post);
        $postdate = explode( " ", $post->post_modified );
        $sitemap .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_permalink( $post->ID ) . '</loc>' .
            "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $tempo . '</lastmod>' .
            "\n\t\t" . '<changefreq>Weekly</changefreq>' .
            "\n\t\t" . '<priority>0.5</priority>' .
            "\n\t" . '</url>' . "\n";
    }
    $sitemap .= '</urlset>
<!-- XML Sitemap generated by .PLUGIN_NAME. -->';
    $fp = @fopen( get_home_path() . "sitemap.xml", 'w' );
    @fwrite( $fp, $sitemap );
    @fclose( $fp );
}
?>