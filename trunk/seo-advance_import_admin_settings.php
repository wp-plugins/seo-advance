<?php
function seo_plugin_update_options($options){
global $seo_pluginall;
//This is the section where we add individual rules to single options (see checkbox part.)

// End that section.
	while (list($option, $value) = each($options)) {
// this line here just fixes individual server bugs.
// If our user has magic quotes turned on and then wordpress tries to add slashes to it we will have everything double slashed.
		if( get_magic_quotes_gpc() ) {
		$value = stripslashes($value);
		}
		$seo_pluginall[$option] = esc_attr($value);
	}
return $seo_pluginall;
}
$seo_plugin_fields = get_option('seo_plugin');
$seo_homepage_title = $seo_plugin_fields['seo_homepage_title'];
$seo_homepage_meta_desc = $seo_plugin_fields['seo_homepage_meta_desc'];
$seo_homepage_meta_keyw = $seo_plugin_fields['seo_homepage_meta_keyw'];
$seo_google_verification_tag = $seo_plugin_fields['seo_google_verification_tag'];
$seo_msn_verification_tag = $seo_plugin_fields['seo_msn_verification_tag'];
$seo_google_publisher_tag =  $seo_plugin_fields['seo_google_publisher_tag'];
$seo_google_author_tag =  $seo_plugin_fields['seo_google_author_tag'];
$seo_google_analytical_code = $seo_plugin_fields['seo_google_analytical_code'];
$seo_extra_header_code = $seo_plugin_fields['seo_extra_header_code'];
$seo_xml_sitemap = $seo_plugin_fields['seo_xml_sitemap'];


if ($_POST["action"] == "saveconfiguration") {
			$seo_pluginall = seo_plugin_update_options($_REQUEST['seo_plugin']);
			update_option('seo_plugin',$seo_pluginall);
$seo_plugin_fields = get_option('seo_plugin');
$seo_homepage_title = sanitize_text_field($seo_plugin_fields['seo_homepage_title']);
$seo_homepage_meta_desc = sanitize_text_field($seo_plugin_fields['seo_homepage_meta_desc']);
$seo_homepage_meta_keyw = sanitize_text_field($seo_plugin_fields['seo_homepage_meta_keyw']);
$seo_google_verification_tag = sanitize_text_field($seo_plugin_fields['seo_google_verification_tag']);
$seo_msn_verification_tag = sanitize_text_field($seo_plugin_fields['seo_msn_verification_tag']);
$seo_google_publisher_tag =  sanitize_title($seo_plugin_fields['seo_google_publisher_tag']);
$seo_google_author_tag =  sanitize_title($seo_plugin_fields['seo_google_author_tag']);
$seo_google_analytical_code = sanitize_text_field($seo_plugin_fields['seo_google_analytical_code']);
$seo_extra_header_code = sanitize_text_field($seo_plugin_fields['seo_extra_header_code']);
$seo_xml_sitemap = sanitize_text_field($seo_plugin_fields['seo_xml_sitemap']);

			$message .= 'SEO Advance Settings have been updated.<br/>';

		//$seo_pluginall doesn't need to be updated because it has the new values added to it immediately

	echo '<div class="updated"><p><strong>'.$message;
	echo '</strong></p></div>';
}
screen_icon('setting');
	echo '<div class="wrap"><div class="icon32" id="icon-tools"> <br /> </div> <h2>SEO Advance Settings</h2><fieldset class="manage" style="width:100%; text-align:left;">';
	echo '<form method="post">';
	echo '<table width="90%"><tr><td><br /></td></tr>';?>

<tr>
<td>Homepage browser title:</td>
<td><input type="text" value="<?php echo $seo_homepage_title; ?>" name="seo_plugin[seo_homepage_title]" placeholder="<?php echo $seo_homepage_title; ?>" style="width:100%;" /><br />
<span class="description">Add browser title for Homepage</span></td>
</tr>
<tr>
<td>Homepage meta description:</td>
<td><textarea name="seo_plugin[seo_homepage_meta_desc]" id="seo_plugin[seo_homepage_meta_desc]" value="<?php echo $seo_homepage_meta_desc; ?>" placeholder="<?php echo $seo_homepage_meta_desc; ?>" style="width:100%;"><?php echo $seo_homepage_meta_desc; ?></textarea><br />
<span class="description">Add meta description for Homepage</span></td>
</tr>
<tr>
<td>Homepage meta keywords:</td>
<td><textarea name="seo_plugin[seo_homepage_meta_keyw]" id="seo_plugin[seo_homepage_meta_keyw]" value="<?php echo $seo_homepage_meta_keyw; ?>" placeholder="<?php echo $seo_homepage_meta_keyw; ?>" style="width:100%;"><?php echo $seo_homepage_meta_keyw; ?></textarea><br />
<span class="description">Add meta keywords for Homepage</span></td>
</tr>
<tr>
<th scope="row"><?php _e('Google verification tag:'); ?></th>
<td><input type="text" value="<?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_google_verification_tag))))); ?>" name="seo_plugin[seo_google_verification_tag]" placeholder="<?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_google_verification_tag))))); ?>" style="width:100%;" /><br />
<span class="description">Example: &lt;meta name="google-site-verification" content="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"/&gt;</span></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<th scope="row"><?php _e('MSN verification tag:'); ?></th>
<td><input type="text" value="<?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_msn_verification_tag))))); ?>" name="seo_plugin[seo_msn_verification_tag]" placeholder="<?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_msn_verification_tag))))); ?>" style="width:100%;" /><br />
<span class="description">Example: &lt;meta name="msvalidate.01" content="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"/&gt;</span></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<th scope="row">Google publisher tag:</th>
<td><input type="text" value="<?php echo $seo_google_publisher_tag; ?>" name="seo_plugin[seo_google_publisher_tag]" placeholder="<?php echo $seo_google_publisher_tag; ?>" style="width:100%;" /><br />
<span class="description">Example: https://plus.google.com/xxxxxxxxxxxxxxxxxxxxxx</span></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<th scope="row"><?php _e('Google authorship tag:'); ?></th>
<td><input type="text" value="<?php echo $seo_google_author_tag; ?>" name="seo_plugin[seo_google_author_tag]" placeholder="<?php echo $seo_google_author_tag; ?>" style="width:100%;" /><br />
<span class="description">Example: https://plus.google.com/xxxxxxxxxxxxxxxxxxxxxx</span></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<th scope="row"><?php _e('Google analytical code:'); ?></th>
<td><textarea name="seo_plugin[seo_google_analytical_code]" id="seo_plugin[seo_google_analytical_code]" placeholder="<?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_google_analytical_code))))); ?>" rows="5" cols="50" style="width:100%;"><?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_google_analytical_code))))); ?></textarea><br />
<span class="description">Add google analytical code</span></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<th scope="row"><?php _e('Extra header code:'); ?></th>
<td><textarea name="seo_plugin[seo_extra_header_code]" id="seo_plugin[seo_extra_header_code]" placeholder="<?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_extra_header_code))))); ?>" rows="5" cols="50" style="width:100%;"><?php echo esc_html(str_replace("\'", "'", str_replace('\"', '"',htmlspecialchars_decode(wp_kses_decode_entities($seo_extra_header_code))))); ?></textarea>
<span class="description">Extra code header will display in between "&lt;head&gt;&lt;/head&gt;" tag</span></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<th scope="row"><?php _e('Enable XML Sitemap'); ?></th>
<td>
<input type="radio" name="seo_plugin[seo_xml_sitemap]" value="enable" <?php if($seo_xml_sitemap == "enable"){ echo ' checked="checked"';} ?>>Enable &nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="seo_plugin[seo_xml_sitemap]" value="disable"  <?php if($seo_xml_sitemap == "disable"){ echo ' checked="checked"';} ?>>Disable<br />
<span class="description">Select Enable to create auto-generated XML sitemap</span></td>
</tr>
<tr><td><br /></td></tr>
<?php
	echo '</table>
			<input type="hidden" name="action" value="saveconfiguration">
			<input type="submit" class="button button-primary" value="Update SEO Settings">
		</form>
	</fieldset>';
?>
<?php
if($seo_xml_sitemap == "enable"){
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
}elseif($seo_xml_sitemap == "disable"){
    @unlink( get_home_path() . "sitemap.xml" );
}
 ?>