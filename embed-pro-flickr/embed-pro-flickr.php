<?php

	/*
	 * Plugin Name: Embed Pro Flickr
	 * Plugin URI: https://wordpress.org/plugins/embedproflickr/
	 * Description: Embed Pro Flickr helps you customize Flickr photos embedded in your WordPress site, inproving user experience, performance and SEO.
	 * Author: jbd7
	 * Author URI: https://github.com/jbd7
	 * License: GNU General Public License (GPL), v3 (or newer)
	 * License URI: https://www.gnu.org/licenses/gpl-3.0.html	
	 * Version: 1.0
	 * Requires at least: 5.0
	 * Requires PHP: 7.3
	 *
	 * Copyright (c) 2023 jbd7. All rights reserved.
	 * 
	 * This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 * 
	 * You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.
	 */
	
	# Uncomment the next two lines to see error messages if things fail...
	# error_reporting(E_ALL);
	# ini_set("display_errors", 1);

	// flickrembedpro_consolelog("Starting plugin"); --> Gets output in the oembed.

	add_action( 'admin_enqueue_scripts', 'flickrembedpro_admin_enqueue_styles' );
	// Load CSS styles for the admin page
	function flickrembedpro_admin_enqueue_styles($hook) {
		
		if ('settings_page_embed-pro-flickr/embed-pro-flickr' != $hook) {
			return;
		}
		flickrembedpro_consolelog("Enqueing styles");
		wp_enqueue_style( 'flickrembedpro_style', plugin_dir_url( __FILE__ ) . 'embed-pro-flickr.css' );
	}


	add_action('admin_init', 'flickrembedpro_admininit' );
     //Functions for settings page
	function flickrembedpro_admininit(){
		
		flickrembedpro_consolelog("Registering settings");
		
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_addclassestoflickrimg');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_replacehyperlinkedflickrpagewithjpg');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_setflickrtitletoimgtitle');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_openphotopageinnewtab');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_addsrcsetandsizes');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_flickrsizes');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_insertflickrpagelinkincaption');

		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_wraploneimgtagsinfiguretags');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_responsifycaptionshortcodes');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_wordpresssizes');

		
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_debugmode');
		register_setting( 'flickrembedpro_plugin_options', 'flickrembedpro_disableoembedcache');
		

	}

	function flickrembedpro_consolelog( $message ) {

		$message = htmlspecialchars( stripslashes( $message ) );
		$message = str_replace( '"', "-", $message );
		$message = str_replace( "'", "-", $message );
		$message = str_replace( "&#039;", "-", $message );
		
		$message = 'Embed Pro Flickr: ' . $message;
		
		if (get_option('flickrembedpro_debugmode')) {
			echo "<script>console.log('$message')</script>";
		}
	}


	function flickrembedpro_options_page() {
		 ?>
		

		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2>Embed Pro Flickr by <a href="https://github.com/jbd7/FlickrEmbedPro/" target="_blank">jbd7</a></h2>
			<hr>
			<p>Embed Pro Flickr is aimed at Flickr photographers who are also WordPress bloggers, to showcase their Flickr personal work in the best light on their personal site.<br>
			Always respect the <a target="_blank" href="https://www.flickr.com/help/guidelines/">Flickr Community guidelines</a>: Play nice, respect copyrights, link back to Flickr and expand your horizons!
			</p>
			<p>These options do not change content in the database, instad, they alter the content served by WordPress. Therefore, turning off an option or deactivating the plugin will revert all changes.<br>
			Options [1] to [5] affect content embedded with the <a target="_blank", href="https://wordpress.com/support/images/flickr-photos/">Flickr Embed block</a> (not the Flickr Embedr), which is usually cached by WordPress. To see the changes take effect right away, you may need to activate option [92].<br>
			Options [6] to [51] only operate on content of Posts and Pages.
			</p>
			<hr>
				<form method="post" action="options.php">
					<?php 
					
					settings_fields('flickrembedpro_plugin_options');
					
					/* Options [1] to [7] operating on Flickr Embed blocks */
					$flickrembedpro_addclassestoflickrimg = get_option('flickrembedpro_addclassestoflickrimg');
					$flickrembedpro_replacehyperlinkedflickrpagewithjpg = get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg');
					$flickrembedpro_setflickrtitletoimgtitle = get_option('flickrembedpro_setflickrtitletoimgtitle');
					$flickrembedpro_openphotopageinnewtab = get_option('flickrembedpro_openphotopageinnewtab');
					$flickrembedpro_insertflickrpagelinkincaption = get_option('flickrembedpro_insertflickrpagelinkincaption');
					$flickrembedpro_addsrcsetandsizes = get_option('flickrembedpro_addsrcsetandsizes');
					$flickrembedpro_flickrsizes = get_option('flickrembedpro_flickrsizes');

					/* Options [51] to [53] operating on legacy Flickr and non-FLickr images, to ensure consistency with older posts. */
					$flickrembedpro_wraploneimgtagsinfiguretags = get_option('flickrembedpro_wraploneimgtagsinfiguretags');
					$flickrembedpro_responsifycaptionshortcodes = get_option('flickrembedpro_responsifycaptionshortcodes');
					$flickrembedpro_wordpresssizes = get_option('flickrembedpro_wordpresssizes');

					/* Options [91] to [92] for debugging */					
					$flickrembedpro_debugmode = get_option('flickrembedpro_debugmode');
					$flickrembedpro_disableoembedcache = get_option('flickrembedpro_disableoembedcache');
					
					
					?>
					<table class="form-table">
						<tr>
							<th scope="row" id="setting01">[1] Add classes to Flickr Embed blocks</th>
							<td>
								<input type="text" size="57" name="flickrembedpro_addclassestoflickrimg" value="<?php echo esc_attr($flickrembedpro_addclassestoflickrimg); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							Space-separated list of comma classes to systematically add to the <code>img</code> element of every Flickr embed block.<br>
							Note that adding the classes manually in the "Advanced" section of the Flickr Embed block settings panel would add them to the <code>figure</code> element.<br>
							Can be useful to target a lightbox or gallery plugin.
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting02">[2] Replace hyperlinked Flickr page with JPG</th>
							<td>
								<input type="radio" name="flickrembedpro_replacehyperlinkedflickrpagewithjpg" value="0" <?php checked(0, $flickrembedpro_replacehyperlinkedflickrpagewithjpg); ?> /> No<br>
								<input type="radio" name="flickrembedpro_replacehyperlinkedflickrpagewithjpg" value="1" <?php checked(1, $flickrembedpro_replacehyperlinkedflickrpagewithjpg); ?> /> Yes (with the default Flickr oEmbed data url)<br>
								<input type="radio" name="flickrembedpro_replacehyperlinkedflickrpagewithjpg" value="2" <?php checked(2, $flickrembedpro_replacehyperlinkedflickrpagewithjpg); ?> /> Yes (with the Flickr 1024px version, if available)<br>
								<input type="radio" name="flickrembedpro_replacehyperlinkedflickrpagewithjpg" value="3" <?php checked(3, $flickrembedpro_replacehyperlinkedflickrpagewithjpg); ?> /> Just remove hyperlink<br>
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							In the <code>&lt;a&gt;</code> tag wrapping the <code>&lt;img&gt;</code> tag, the Flickr Embed block sets the Flickr photo page of the URL as the <code>href</code> attribute. Check that option to assign the URL of the JPG file returned by Flickr oEmbed.<br>
							You may want to check this option if the Flick Embed block breaks your lightboxes or gallery plugins. If you are losing visitors taken away from your website, consider simply <a href="#setting04">opening the link in a new tab.</a><br>
							<strong>Remember to link back to Flickr!</strong> See <a target="_blank" href="https://www.flickrhelp.com/hc/en-us/articles/4404078014356-Share-or-embed-Flickr-photos-albums">Flickr's T&Cs</a>. If you remove the Flickr photo page URL with this option, you must link it somewhere else, for example with <a href="#setting05">Option [5]</a>.	
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting03">[3] Set Flickr photo title as <code>&lt;img&gt;</code> tag title</th>
							<td>
								<input type="checkbox" name="flickrembedpro_setflickrtitletoimgtitle" value="1" <?php checked(1, $flickrembedpro_setflickrtitletoimgtitle); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							Assigns the photo title, returned in the Flickr oEmbed data, to the <code>title</code> attribute of the <code>&lt;img&gt;</code> tag in the Flickr Embed block.<br>
							Checking this option will improve accessibility (when using assistive technologies like screen readers), SEO (to determine relevance of the webpage) and user experience (the photo title will be displayed as a tooltip when hovering over the image).
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting04">[4] Open Flickr photo page in a new tab</th>
							<td>
								<input type="checkbox" name="flickrembedpro_openphotopageinnewtab" value="1" <?php checked(1, $flickrembedpro_openphotopageinnewtab); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							Makes the default hyperlink to the photo page open in a new tab, instead of the default behavior directing visitors away from WordPress.<br>
							</td>
						</tr>						
						<tr>
							<th scope="row" id="setting05">[5] Add caption with Flickr title and hyperlink to the Flickr photo page</th>
							<td>
								<input type="radio" name="flickrembedpro_insertflickrpagelinkincaption" value="0" <?php checked(0, $flickrembedpro_insertflickrpagelinkincaption); ?> /> No<br>
								<input type="radio" name="flickrembedpro_insertflickrpagelinkincaption" value="1" <?php checked(1, $flickrembedpro_insertflickrpagelinkincaption); ?> /> Yes (without hyperlink)<br>
								<input type="radio" name="flickrembedpro_insertflickrpagelinkincaption" value="2" <?php checked(2, $flickrembedpro_insertflickrpagelinkincaption); ?> /> Yes (with hyperlink)<br>
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							If the caption of the Flickr Embed block is empty, checking this option will dynamically set up a caption with a <code>figcaption</code> element using the Flickr metadata title, and assigning the class <code>fep-caption</code>.<br>
							<strong>Remember to link back to Flickr!</strong> If you also use <a href="#setting02">Option [2]</a>, then it is recommended to insert the title with hyperlink, so as to respect <a target="_blank" href="https://www.flickrhelp.com/hc/en-us/articles/4404078014356-Share-or-embed-Flickr-photos-albums">Flickr's T&Cs</a>. 
							</td>
						</tr>						
						<tr>
							<th scope="row" id="setting06">[6] Responsify Flickr images</th>
							<td>
								<input type="checkbox" name="flickrembedpro_addsrcsetandsizes" value="1" <?php checked(1, $flickrembedpro_addsrcsetandsizes); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							Adds the <code>srcset</code> and <code>sizes</code> attributes to <code>&lt;img&gt;</code> tags containing Flickr images.<br>
							<code>srcset</code> is set for images of 240px, 320x, 400px, 500px, 640px, 800px and 1024px, which are <a target="_blank" href="https://www.flickr.com/services/api/misc.urls.html">the sizes offered by Flickr</a>. Sizes from 1600px and above may be available on Flickr but are not offered in this plugin.<br>
							<code>sizes</code> is set from the <a href="#setting07">next option</a>.
							<a target"_blank" href="https://medium.com/@woutervanderzee/responsive-images-with-srcset-and-sizes-fc434845e948">Read more</a> about using <code>srcset</code> and <code>sizes</code> to adapt to the <a target"_blank" href="https://html.spec.whatwg.org/multipage/images.html#introduction-3">HTML living standard</a>.<br>
							Responsified images are tagged with the class <code>fep-responsified</code>, which you could target with custom CSS to restyle a potential <code>width</code> attribute.
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting07">[7] <code>sizes</code> attribute for Flickr images</th>
							<td>
								<input type="text" size="57" name="flickrembedpro_flickrsizes" value="<?php echo esc_attr($flickrembedpro_flickrsizes); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							<code>sizes</code> attribute used with Responsify Flickr. It depends on your layout, and could look like <code>sizes="(max-width: 390px) 320px, (max-width: 900px) 500px, (max-width: 1080px) 640px, 800px"</code> or <code>(max-width: 30em) 100vw, (max-width: 50em) 50vw, calc(33vw - 100px)</code>, for example.<br>
							Will be ignored if  <a href="#setting06">Responsify Flickr</a> is not checked.<br>
							If left blank when <a href="#setting06">Responsify Flickr</a> is used, <code>100vw</code> will be used (if the <code>srcset</code> attribute uses width descriptors, the <code>sizes</code> attribute must also be present, or the <code>srcset</code> itself will be ignored).
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting51">[51] Backward compatibility: Wrap lone <code>&lt;img&gt;</code> tags in <code>&lt;figure&gt;</code> tags</th>
							<td>
								<input type="checkbox" name="flickrembedpro_wraploneimgtagsinfiguretags" value="1" <?php checked(1, $flickrembedpro_wraploneimgtagsinfiguretags); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							In older versions of WordPress, images inserted into posts had the structure following <code>&lt;p&gt;</code><code>&lt;a&gt;</code><code>&lt;img&gt;</code><code>&lt;/a&gt;</code><code>&lt;/p&gt;</code>.<br>
							By checking this option, for posts and pages, they will be replaced with a <code>&lt;figure&gt;</code><code>&lt;a&gt;</code><code>&lt;img&gt;</code><code>&lt;/a&gt;</code><code>&lt;/figure&gt;</code> structure, so as to match with HTML5's semantic guidelines. The class <code>img-fep-legacy</code> will be assigned to the <code>figure</code> element.<br>
							Flick and non-Flick images are processed.
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting52">[52] Backward compatibility: Responsify caption shortcodes</th>
							<td>
								<input type="checkbox" name="flickrembedpro_responsifycaptionshortcodes" value="1" <?php checked(1, $flickrembedpro_responsifycaptionshortcodes); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							In older versions of WordPress, images inserted with the <code>[caption]</code> could contain a fixed width, such as <code>[caption width="500"]&lt;a ... &gt;&lt;img ... &gt;&lt;/a&gt; Caption [/caption]</code>.<br>
							By checking this option, for posts and pages, caption shortcodes will be processed to be stripped of their fixed width and height attributes, and be assigned the classes <code>wp-block-image wp-caption-fep-legacy aligncenter</code> to the <code>figure</code> element, and classes <code>wp-element-caption wp-caption-text-fep-legacy</code> to the <code>figcaption</code> element. This allows you to style these images and captions with CSS instead.<br>
							Flick and non-Flick images are processed.
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting53">[53] Backward compatibility: <code>sizes</code> attribute for WordPress images</th>
							<td>
								<input type="text" size="57" name="flickrembedpro_wordpresssizes" value="<?php echo esc_attr($flickrembedpro_wordpresssizes); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							<code>sizes</code> attribute to replace the default WordPress behavior.<br>
							Since WordPress 4.4, WordPress <a target="_blank" href="https://make.wordpress.org/core/2015/11/10/responsive-images-in-wordpress-4-4/">injects</a> the following default <code>sizes</code> attribute: <code>sizes= "(max-width: [image width]) 100vw, [image width]"</code> to images uploaded as WordPress media.<br>
							Enter value to customize <code>wp_calculate_image_sizes</code>. If left blank, the default WordPress settings will apply.<br>
							Only applies to WordPress media, therefore Flickr embedded images are not impacted.
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting91">[91] Debug to browser console</th>
							<td>
								<input type="checkbox" name="flickrembedpro_debugmode" value="1" <?php checked(1, $flickrembedpro_debugmode); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							Outputs a comment with <code>console.log()</code> when taking action on a Flickr image.<br>
							Do not leave checked on a production website to avoid performance impact.
							</td>
						</tr>
						<tr>
							<th scope="row" id="setting92">[92] Disable oEmbed cache</th>
							<td>
								<input type="checkbox" name="flickrembedpro_disableoembedcache" value="1" <?php checked(1, $flickrembedpro_disableoembedcache); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
							<td>
							Ensures a regeneration of the oEmbed cache, which may be necesary to observe immediate changes when using options [1] to [5].<br>
							Note that a caching plugin could also cache pages, and that a "hard refresh" (<code>Ctrl + F5</code> or <code>⌘ + ⇧ + R</code>) helps your browser ignore its own cache.<br>
							Do not leave checked on a production website to avoid performance impact.
							</td>
						</tr>
					</table>
					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>
				</form>
			<hr>
			<details>
			<summary><h3>How do Flick Embed blocks work? A peak under the hood ...</h3></summary>
				<p>Let's take <a target="_blank" href="https://www.flickr.com/photos/197789480@N05/52722444862">this Flickr photo</a> as an exemple.</p>
				<p>When inserting a Flickr image, as a block with the Gutenberg code editor, Wordpress registers the following in the database in <code>wp_posts</code>:</p>
				<p><pre class="html">
					&lt;!-- wp:embed {"url":"https://www.flickr.com/photos/197789480@N05/52722444862","type":"photo","providerNameSlug":"flickr","allowResponsive":false,"responsive":true,"className":"manuallytypedinclass1 manuallytypedinclass2"} -->
					&lt;figure class="wp-block-embed is-type-photo is-provider-flickr wp-block-embed-flickr manuallytypedinclass1 manuallytypedinclass2"&gt;
					&lt;div class="wp-block-embed__wrapper"&gt;https://www.flickr.com/photos/197789480@N05/52722444862
					&lt;/div&gt;&lt;/figure&gt;
					&lt;!-- /wp:embed --&gt;</pre>
				</p>
				<p>Upon publication of the post, Wordpress calls <a target="_blank" href=https://oembed.com/">oEmbed providers</a>, including Flickr, that convert the $url into $data. For example, for the URL example above, it will return the following, which <a target="_blank" href="https://www.flickr.com/services/oembed/?format=json&url=https://www.flickr.com/photos/197789480@N05/52722444862">can be seen in your browser</a>.</p>
				<p><pre class="json">
				{
				  <span class="key">"type"</span>: <span class="string">"photo"</span>,
				  <span class="key">"flickr_type"</span>: <span class="string">"photo"</span>,
				  <span class="key">"title"</span>: <span class="string">"Preikestolen (pulpit rock), Norway"</span>,
				  <span class="key">"author_name"</span>: <span class="string">"jbd7-wordpress"</span>,
				  <span class="key">"author_url"</span>: <span class="string">"https://www.flickr.com/photos/197789480@N05/"</span>,
				  <span class="key">"width"</span>: <span class="number">1024</span>,
				  <span class="key">"height"</span>: <span class="number">683</span>,
				  <span class="key">"url"</span>: <span class="string">"https://live.staticflickr.com/65535/52722444862_a5aff24af0_b.jpg"</span>,
				  <span class="key">"web_page"</span>: <span class="string">"https://www.flickr.com/photos/197789480@N05/52722444862/"</span>,
				  <span class="key">"thumbnail_url"</span>: <span class="string">"https://live.staticflickr.com/65535/52722444862_a5aff24af0_q.jpg"</span>,
				  <span class="key">"thumbnail_width"</span>: <span class="number">150</span>,
				  <span class="key">"thumbnail_height"</span>: <span class="number">150</span>,
				  <span class="key">"web_page_short_url"</span>: <span class="string">"https://flic.kr/p/2ojUj33"</span>,
				  <span class="key">"license"</span>: <span class="string">"All Rights Reserved"</span>,
				  <span class="key">"license_id"</span>: <span class="number">0</span>,
				  <span class="key">"html"</span>: <span class="string">&lt;a data-flickr-embed=&quot;true&quot; href=&quot;https://www.flickr.com/photos/197789480@N05/52722444862/&quot; title=&quot;Preikestolen (pulpit rock), Norway by jbd7-wordpress, on Flickr&quot;&gt;&lt;img src=&quot;https://live.staticflickr.com/65535/52722444862_a5aff24af0_b.jpg&quot; width=&quot;1024&quot; height=&quot;683&quot; alt=&quot;Preikestolen (pulpit rock), Norway&quot;&gt;&lt;/a&gt;&lt;script async src=&quot;https://embedr.flickr.com/assets/client-code.js&quot; charset=&quot;utf-8&quot;&gt;&lt;/script&gt;</span>,
				  <span class="key">"version"</span>: <span class="string">"1.0"</span>,
				  <span class="key">"cache_age"</span>: <span class="number">3600</span>,
				  <span class="key">"provider_name"</span>: <span class="string">"Flickr"</span>,
				  <span class="key">"provider_url"</span>: <span class="string">"https://www.flickr.com/"</span>,
				}</pre>
				</p>
				<p>It then uses the <code>data2html()</code> function to convert this oEmbed response into HTML code. For this example, the HTML result is:</p>
				<p><pre class="html">
				&lt;a href="https://www.flickr.com/photos/197789480@N05/52722444862"&gt;
				&lt;img src="https://live.staticflickr.com/65535/52722444862_a5aff24af0_z.jpg" alt="Preikestolen (pulpit rock), Norway" width="640" height="427" /&gt;
				&lt;/a&gt;</pre>
				</p>
				<p>It therefore hardcodes the <code>img</code> tag with one Flickr size, here with suffix <code>_z</code> (size of 640px, see <a target="_blank" href="https://www.flickr.com/services/api/misc.urls.html">All Flickr sizes</a>).</p>
				<p>That HTML is stored in the table <code>wp_postmeta</code> under the meta_key <code>_oembed_*key*</code>. It also creates the meta_key <code>_oembed_time_*key*</code> to store the timestamp, so that Wordpress knows the age of the oEmbed data.</p>
				<p>This HTML, cached in <code>wp_postmeta</code>, is served to visitors when browsing your site. If it is not present at the time of a visit, WordPress will regenerate it.</p>
				<pe>The <a target="_blank" href="https://www.flickrhelp.com/hc/en-us/articles/4404078014356-Share-or-embed-Flickr-photos-albums">Flick Help Center</a> describes ways to share or embed Flickr photos and albums. <em>Remember to link back to Flickr! Per Flickr's <a target="_blank" href="https://www.flickr.com/help/terms">Terms of Use</a>, whenever you place an image you're storing on Flickr on an external web site, you must also include a link back to Flickr</em>. Respect also the Attribution term of most <a target="blank" href="https://creativecommons.org/licenses/by/2.0/">CC licenses</a>.</p>
				<div>
				</div>
			</details>
		</div>
	<?php
	}
	

	add_action('admin_menu', 'flickrembedpro_config_page');
	// Adds the Config page, the only page of the plugin
	function flickrembedpro_config_page() {
		add_options_page(__('Embed Pro Flickr'), __('Embed Pro Flickr'), 'manage_options', __FILE__, 'flickrembedpro_options_page');
	}


	add_filter( 'plugin_action_links', 'flickrembedpro_plugin_action_links', 10, 2 );
	// Display a Settings link on the main Plugins page
	function flickrembedpro_plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$flickrembedpro_posk_links = '<a href="'.get_admin_url().'options-general.php?page=embed-pro-flickr/embed-pro-flickr.php">'.__('Settings').'</a>';
			// make the 'Settings' link appear first
			array_unshift( $links, $flickrembedpro_posk_links );
		}

		return $links;
	}


	
	add_filter( 'plugin_row_meta', 'flickrembedpro_plugin_row_meta', 10, 2 );
	// Display links under plugin name
	function flickrembedpro_plugin_row_meta( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$flickrembedpro_pluginpage_link = '<a target="_blank" href="https://wordpress.org/plugins/flickrembedpro/">'.__('Visit plugin site').'</a>';
			$flickrembedpro_donate_link = '<a target="_blank" href="https://www.buymeacoffee.com/jbd7">'.__('Donate').'</a>';
			// array_push( $links, $geoflickr_pluginpage_link );
			array_push( $links, $flickrembedpro_donate_link );
		}

		return $links;
	}	


/* ###############################################################
	
							ACTIVATION

   ############################################################### */ 


	if ( ! function_exists('flickrembedpro_activate_modules') ) {
		function flickrembedpro_activate_modules() {

			// Do not run the plugin is not a post or page
			if ( ! is_singular() ) {
				flickrembedpro_consolelog("Aborting load as not a post or page");
        		return;
			}
			flickrembedpro_consolelog("Activating plugin on detected post/page");


			/* Activates option [1] */
			if ( !empty(trim(get_option('flickrembedpro_addclassestoflickrimg'))) ) {
				flickrembedpro_consolelog("Activating Add Classes to Flickr Embed block img");
				if ( !has_filter('oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse')) {
					add_filter( 'oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse', 99, 4 );
				}
				// Does the replacement over oembed html loadeed from postmeta
				if ( !has_filter('embed_oembed_html', 'flickrembedpro_flickr_oembed_load')) {
					add_filter( 'embed_oembed_html', 'flickrembedpro_flickr_oembed_load', 24, 4);
				}
			}
			
			
			/* Activates option [2] */
			if ( !empty(trim(get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg'))) ) {
				flickrembedpro_consolelog("Activating Replace Hyperlinked Flickr page with JPG");
				if ( !has_filter('oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse')) {
					add_filter( 'oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse', 99, 4 );
				}
				// Does the replacement over oembed html loadeed from postmeta
				if ( !has_filter('embed_oembed_html', 'flickrembedpro_flickr_oembed_load')) {
					add_filter( 'embed_oembed_html', 'flickrembedpro_flickr_oembed_load', 24, 4);
				}
			}
			
			/* Activates option [3] */
			if ( get_option('flickrembedpro_setflickrtitletoimgtitle') ) {
				flickrembedpro_consolelog("Activating Set Flickr Title to IMG title");
				if ( !has_filter('oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse')) {
					add_filter( 'oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse', 99, 4 );
				}
				// Does the replacement over oembed html loadeed from postmeta
				if ( !has_filter('embed_oembed_html', 'flickrembedpro_flickr_oembed_load')) {
					add_filter( 'embed_oembed_html', 'flickrembedpro_flickr_oembed_load', 24, 4);
				}
			}

			/* Activates option [4] */
			if ( !empty(trim(get_option('flickrembedpro_openphotopageinnewtab'))) ) {
				flickrembedpro_consolelog("Activating Open Photo page in new tab");
				if ( !has_filter('oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse')) {
					add_filter( 'oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse', 99, 4 );
				}
				// Does the replacement over oembed html loadeed from postmeta
				if ( !has_filter('embed_oembed_html', 'flickrembedpro_flickr_oembed_load')) {
					add_filter( 'embed_oembed_html', 'flickrembedpro_flickr_oembed_load', 24, 4);
				}
			}

			/* Activates option [5] */
			if ( !empty(trim(get_option('flickrembedpro_insertflickrpagelinkincaption'))) ) {
				flickrembedpro_consolelog("Activating Add Caption with Flickr title");
				// Need to save the Flickr title and URL first 
				if ( !has_filter('oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse')) {
					add_filter( 'oembed_dataparse', 'flickrembedpro_flickr_oembed_dataparse', 99, 4 );
				}
				// Does the replacement over oembed html loadeed from postmeta
				if ( !has_filter('embed_oembed_html', 'flickrembedpro_flickr_oembed_load')) {
					add_filter( 'embed_oembed_html', 'flickrembedpro_flickr_oembed_load', 24, 4);
				}
				// Does the replacement over the_content
				if ( !has_filter('the_content', 'flickrembedpro_parse_img_thecontent')) {
					add_filter( 'the_content', 'flickrembedpro_parse_img_thecontent', 24);
				}
			}

			/* Activates option [6], using option [7] */
			if ( get_option('flickrembedpro_addsrcsetandsizes') ) {
				flickrembedpro_consolelog("Activating Responsify Flickr images");
				if ( !has_filter('the_content', 'flickrembedpro_parse_img_thecontent')) {
					add_filter( 'the_content', 'flickrembedpro_parse_img_thecontent', 24);
				}
			}	
			
			/* Activates option [51] */	
			if ( get_option('flickrembedpro_wraploneimgtagsinfiguretags') ) {
				flickrembedpro_consolelog("Activating Wrap Lone img tags in figure tags");
				if ( !has_filter('the_content', 'flickrembedpro_parse_img_thecontent')) {
					add_filter( 'the_content', 'flickrembedpro_parse_img_thecontent', 24);
				}
			}
			
			/* Activates option [52] */				
			if ( get_option('flickrembedpro_responsifycaptionshortcodes') ) {
				flickrembedpro_consolelog("Activating Responsify Caption shortcodes");
				add_filter('img_caption_shortcode', 'flickrembedpro_responsifycaptionshortcodes',10,3);
			}

			/* Activates option [53] */
			if ( !empty(trim(get_option('flickrembedpro_wordpresssizes'))) ) {
				flickrembedpro_consolelog("Activating Replacing WordPress sizes attribute");
				add_filter('wp_calculate_image_sizes', 'flickrembedpro_content_image_sizes_attr', 10 , 2);
			}

			/* Activates option [92] */			
			if ( get_option('flickrembedpro_disableoembedcache') ) {
				flickrembedpro_consolelog("Activating Disable oEmbed cache");
				flickrembedpro_disable_oembed_cache();
			}
		}
		add_action('wp', 'flickrembedpro_activate_modules', 99);
		// in_singular() is known right after 'wp' is fully loaded
	}




/* ###############################################################
	
								MODULES

   ############################################################### */ 


	/* Overrides oembed_data parse, as all changes required for modules [1] to [5] can be done on the fly on flickrembedpro_flickr_oembed_load */
	if ( ! function_exists('flickrembedpro_flickr_oembed_dataparse') ) {
		function flickrembedpro_flickr_oembed_dataparse($return, $data, $url) {
			return $return;
		}
	}


	/* Module for options [1], [2], [3], [4] and [5] */	
	if ( ! function_exists('flickrembedpro_flickr_oembed_dataparse') ) {
		function flickrembedpro_flickr_oembed_dataparse($return, $data, $url) {
			
			if  ( false !== stripos($data->provider_name, 'flickr')  &&  false !== stripos($data->type, 'photo')  &&  false !== stripos($data->flickr_type, 'photo') ) {
				# Prevent errors so we can parse HTML5
				libxml_use_internal_errors(true); # https://stackoverflow.com/questions/9149180/domdocumentloadhtml-error
				
				/* Create a DOM for the oEmbed response. Most time-consuming step, worth between ~4X and ~10X the time of preg_replace(), needing 10ms at most */
				$newhtml = new DOMDocument();
				$newhtml->loadHTML('<?xml encoding="utf-8" ?>' . $return, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
				$flickrimgs = $newhtml->getElementsByTagName('img');
				$flickras = $newhtml->getElementsByTagName('a');
				
				/* Process option [1], Add classes to img tag */ 
				if ( get_option('flickrembedpro_addclassestoflickrimg') && ( $flickrimgs->length > 0 ) ) {
					$flickrimg = $flickrimgs->item(0);
					$newclasses = trim(get_option('flickrembedpro_addclassestoflickrimg'));
					$flickrimg->setAttribute('class', $flickrimg->getAttribute('class') . ' ' . $newclasses);
					$oembedtext = "Setting up classes for URL " . $url . " with new class(es): ". $newclasses; 
					flickrembedpro_consolelog($oembedtext);		
				}

				/* Process option [2], Replace URL in a href with JPG */
				if ( get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg' ) && ( $flickras->length > 0 ) &&  ( ! empty( $data->url ) ) && is_string( $data->url) ) {
					$flickra = $flickras->item(0);
					$newhref = $data->url;
					
					if ( '2' == strval(get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg')) ) {
						$oembedtext = "Replacing a href, looking for 1024px version of URL " . $url; 
						flickrembedpro_consolelog($oembedtext);
						$info = pathinfo($newhref);
						$basename = $info['filename'];
						$suffix = "_b"; // Suffix for 1024px photos, including the underscore
							if (preg_match('/_[a-zA-Z]$/', $basename)) {
								// replace existing suffix if exists
								$basename = preg_replace('/_[a-zA-Z]$/ui', $suffix, $basename);
							} else {
								// add new suffix if photo in data->url is 500px
								$basename .= $suffix;
							}
						$newhref_large = $info['dirname'] . "/" . $basename . "." . $info['extension'];
						// Checks if the large size image does exist, as file Could be missing
						$oembedtext = "Checking if exists: " . $newhref_large; 
						flickrembedpro_consolelog($oembedtext);
						if ( (bool) get_headers($newhref_large, 1)["Content-Type"] ) {
							$newhref = $newhref_large;
							}
					} 
					
					if ( '3' == strval(get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg')) ) {
						if ($flickra->hasAttribute('href') ) {
							$flickra->removeAttribute('href');
							$flickra->setAttribute('data-fep-removed', '');
						}
						$oembedtext = "Removing a href for URL " . $url; 
						flickrembedpro_consolelog($oembedtext);
					} else {
						$flickra->setAttribute('href', $newhref);
						$flickra->setAttribute('data-fep-replaced', '');
						$oembedtext = "Replacing a href for URL " . $url . " with href ". $newhref; 
						flickrembedpro_consolelog($oembedtext);
					}
				
					// Keeping the Flickr photo URL for linking it somewhere else. Implying the option [5] code has to test this attribute too
					$flickra->setAttribute('data-flickrphotopage', $url);
				}
				
				/* Process option [3], Set Flickr photo title to IMG title */ 
				if ( get_option('flickrembedpro_setflickrtitletoimgtitle') && ( $flickrimgs->length > 0 ) &&  ( ! empty( $data->title ) ) && is_string( $data->title) ) {
					$flickrimg = $flickrimgs->item(0);
					$newtitle = $data->title;
					$flickrimg->setAttribute('title', $newtitle);
					$oembedtext = "Setting up title for URL " . $url . " with title ". $newtitle; 
					flickrembedpro_consolelog($oembedtext);	
				}
				
				/* Process option [4], Open the Flickr photo page in a new tab */
				if ( get_option('flickrembedpro_openphotopageinnewtab' ) && ( $flickras->length > 0 ) ) {
					$flickra = $flickras->item(0);
					$flickra->setAttribute('target', '_blank');
					$flickra->setAttribute('data-fep-newtab', '');
					$oembedtext = "Making URL open in a new tab for URL " . $url; 
					flickrembedpro_consolelog($oembedtext);	
				}
				
				/* Prepares data for option [5], Add caption with Flickr title */
				if ( get_option('flickrembedpro_insertflickrpagelinkincaption' ) && ( $flickras->length > 0 ) ) {
					$flickra->setAttribute('data-flickrphotopage', $url);
					$flickra->setAttribute('data-flickrtitle', $data->title);
				}
				
				# Turn on errors again...
				libxml_use_internal_errors(false);
			}

			if (!isset($newhtml)) {
				return $return;
			}
			
			return str_replace('<?xml encoding="utf-8" ?>', '', $newhtml->saveHTML());
		}
	}
	



	/* Module for options [1], [2], [3], [4] and [5] */	
	if ( ! function_exists('flickrembedpro_flickr_oembed_load') ) {
		function flickrembedpro_flickr_oembed_load( $html, $url, $args, $postid ) {
			
			if  ( false !== stripos($url, 'flickr.com')  ||  false !== stripos($url, 'flic.kr') ) {
				# Prevent errors so we can parse HTML5
				libxml_use_internal_errors(true); # https://stackoverflow.com/questions/9149180/domdocumentloadhtml-error
				
				/* Create a DOM for the oEmbed response. Most time-consuming step, worth between ~4X and ~10X the time of preg_replace(), needing 10ms at most */
				$newhtml = new DOMDocument();
				$newhtml->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
				$flickrimgs = $newhtml->getElementsByTagName('img');
				$flickras = $newhtml->getElementsByTagName('a');
				
				/* Process option [1], Add classes to img tag */ 
				if ( get_option('flickrembedpro_addclassestoflickrimg') && ( $flickrimgs->length > 0 ) ) {
					$flickrimg = $flickrimgs->item(0);
					$newclasses = trim(get_option('flickrembedpro_addclassestoflickrimg'));
					$flickrimg->setAttribute('class', $flickrimg->getAttribute('class') . ' ' . $newclasses);
					$oembedtext = "Setting up classes for URL " . $url . " with new class(es): " . $newclasses; 
					flickrembedpro_consolelog($oembedtext);		
				}

				/* Process option [2], Replace URL in a href with JPG */
				if ( get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg' ) && ( $flickras->length > 0 ) && ( $flickrimgs->length > 0 ) ) {
					$flickra = $flickras->item(0);
					$flickrimg = $flickrimgs->item(0);
					$newhref = $flickrimg->getAttribute('src');
					
					if ( '2' == strval(get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg')) ) {
						$oembedtext = "Replacing a href, looking for 1024px version of URL " . $url; 
						flickrembedpro_consolelog($oembedtext);
						$info = pathinfo($newhref);
						$basename = $info['filename'];
						$suffix = "_b"; // Suffix for 1024px photos, including the underscore
							if (preg_match('/_[a-zA-Z]$/', $basename)) {
								// replace existing suffix if exists
								$basename = preg_replace('/_[a-zA-Z]$/ui', $suffix, $basename);
							} else {
								// add new suffix if photo in img src is 500px
								$basename .= $suffix;
							}
						$newhref_large = $info['dirname'] . "/" . $basename . "." . $info['extension'];
						// Checks if the large size image does exist, as file Could be missing
						$oembedtext = "Checking if exists: " . $newhref_large; 
						flickrembedpro_consolelog($oembedtext);
						if ( (bool) get_headers($newhref_large, 1)["Content-Type"] ) {
							$newhref = $newhref_large;
							}
					}
					
					if ( '3' == strval(get_option('flickrembedpro_replacehyperlinkedflickrpagewithjpg')) ) {
						if ($flickra->hasAttribute('href') ) {
							$flickra->removeAttribute('href');
							$flickra->setAttribute('data-fep-removed', '');
						}
						$oembedtext = "Removing a href for URL " . $url; 
						flickrembedpro_consolelog($oembedtext);
					} else {
						$flickra->setAttribute('href', $newhref);
						$flickra->setAttribute('data-fep-replaced', '');
						$oembedtext = "Replacing a href for URL " . $url . " with href ". $newhref; 
						flickrembedpro_consolelog($oembedtext);
					}
				
					// Keeping the Flickr photo URL for linking it somewhere else. Implying the option [5] code has to test this attribute too
					$flickra->setAttribute('data-flickrphotopage', $url);
				}
				
				/* Process option [3], Set Flickr photo title to IMG title */ 
				if ( get_option('flickrembedpro_setflickrtitletoimgtitle') && ( $flickrimgs->length > 0 ) ) {
					$flickrimg = $flickrimgs->item(0);
					$newtitle = $flickrimg->getAttribute('alt');
					$flickrimg->setAttribute('title', $newtitle);
					$oembedtext = "Setting up title for URL " . $url . " with title ". $newtitle; 
					flickrembedpro_consolelog($oembedtext);			
				}
				
				/* Process option [4], Open the Flickr photo page in a new tab */
				if ( get_option('flickrembedpro_openphotopageinnewtab' ) && ( $flickras->length > 0 ) ) {
					$flickra = $flickras->item(0);
					$flickra->setAttribute('target', '_blank');
					$flickra->setAttribute('data-fep-newtab', '');
					$oembedtext = "Making URL open in a new tab for URL " . $url; 
					flickrembedpro_consolelog($oembedtext);	
				}
				
				/* Prepares data for option [5], Add caption with Flickr title */
				if ( get_option('flickrembedpro_insertflickrpagelinkincaption' ) && ( $flickras->length > 0 ) &&  ( $flickrimgs->length > 0 ) ) {
					$flickra = $flickras->item(0);
					$flickrimg = $flickrimgs->item(0);
					$flickra->setAttribute('data-flickrphotopage', $url);
					$flickra->setAttribute('data-flickrtitle', $flickrimg->getAttribute('alt'));
				}
				
				# Turn on errors again...
				libxml_use_internal_errors(false);
			}

			if (!isset($newhtml)) {
				return $html;
			}
			
			return str_replace('<?xml encoding="utf-8" ?>', '', $newhtml->saveHTML());
		}
	}
	



	/* Module for options [5], [6], [7], [51] */
	if ( ! function_exists('flickrembedpro_parse_img_thecontent') ) {
		function flickrembedpro_parse_img_thecontent( $content ) {
			// Source: https://wordpress.stackexchange.com/questions/174582/always-use-figure-for-post-images			
			# Prevent errors so we can parse HTML5
			libxml_use_internal_errors(true); # https://stackoverflow.com/questions/9149180/domdocumentloadhtml-error

			# Load the content
			$dom = new DOMDocument();

			# With UTF-8 support, this workaround is not needed since PHP 5.4.0. However, it still fails at parsing things like "5° and 6°" into "5Â° and 6Â°" without <?xml encoding="utf-8" ? >
			# https://stackoverflow.com/questions/8218230/php-domdocument-loadhtml-not-encoding-utf-8-correctly
			# $dom->loadHTML('<?xml encoding="utf-8" ? >' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
			$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

			# Find all images
			$images = $dom->getElementsByTagName('img');

			/* Process option [51], Wrap lone img tags in figure tags */ 
			if ( get_option('flickrembedpro_wraploneimgtagsinfiguretags') && ( $images->length > 0 ) ) {
				$imageswrapped = 0;
				# Go through all the images
				foreach ($images as $image) {
					$child = $image; # Store the child element
					$wrapper = $image->parentNode; # And the wrapping element

					# If the image is linked
					if ($wrapper->tagName == 'a') {
						$child = $wrapper; # Store the link as the child
						$wrapper = $wrapper->parentNode; # And its parent as the wrapper
					}

					# If the parent is a <p> - replace it with a <figure>
					if ($wrapper->tagName == 'p') {
						$figure = $dom->createElement('figure');

						$figure->setAttribute('class', trim($image->getAttribute('class') . ' img-fep-legacy')); # Give figure same class as img
						$image->setAttribute('class', ''); # Remove img class
						$image->setAttribute('height', ''); # Remove img height class
						$image->setAttribute('width', '100%'); # Remove img width class
						$figure->appendChild($child); # Add img to figure
						$wrapper->parentNode->replaceChild($figure, $wrapper); # Replace <p> with <figure>
						
						$urltext = "Wrap IMG tags in Figure tags for " . $image->getAttribute('src');
						flickrembedpro_consolelog($urltext);
						$imageswrapped++;
						
					}
				}
				
				flickrembedpro_consolelog("Wrap IMG tags in Figure tags completed, performed " . strval($imageswrapped) . " replacements");
			}


			/* Process option [5], Add caption with Flickr Title */    
			if ( get_option('flickrembedpro_insertflickrpagelinkincaption') && ( $images->length > 0 ) ) {
				$imagestitled = 0;
				# Go through all the images
				foreach ($images as $image) {
					
					if ( false === stripos($image->getAttribute('src'), 'flickr.com') ) {
						// Not a flickr image
						continue;
					}
					
					$child = $image; # Store the child element
					$wrapper = $image->parentNode; # And the wrapping element
					$flickrphotopage = '';
					$flickrtitle = '';

					# If the image is linked, fetch metadata
					if ($wrapper->tagName == 'a') {
						$child = $wrapper; # Store the link as the child
						$wrapper = $wrapper->parentNode; # And its parent as the wrapper
						$flickrphotopage = $child->getAttribute('data-flickrphotopage');
						$flickrtitle = $child->getAttribute('data-flickrtitle');
					}
					
					# If there is a wrapping div, as is the case with Gutenberg Embed blocks, we go one level up
					if ($wrapper->tagName == 'div') {
						$child = $wrapper; # Store the div as the child
						$wrapper = $wrapper->parentNode; # And its parent (figure?) as the wrapper
						if ( '' == $flickrphotopage ) {
							$flickrphotopage = $child->getAttribute('data-flickrphotopage');
						}
						if ( '' == $flickrtitle ) {
							$flickrtitle = $child->getAttribute('data-flickrtitle');
						}
					}
					
					# If the parent is a <figure> and we have a valid URL and Title
					if ( 'figure' == $wrapper->tagName && '' != $flickrphotopage && '' != $flickrtitle) {
						// Check if the figure element has a figcaption
						$hasCaption = false;
						foreach ($wrapper->childNodes as $childnodes) {
							if ( 'figcaption' == $childnodes->tagName ) {
								$hasCaption = true;
								break;
							}
						}
						// If the figure element does not have a figcaption, create one and insert a random caption
						if ( !$hasCaption ) {
							if ( '2' == strval(get_option('flickrembedpro_insertflickrpagelinkincaption')) ) {
								// Add hyperlink to Flickr photo page on caption 
								$link = $dom->createElement('a',  $flickrtitle);
								$link->setAttribute('href', $flickrphotopage);
								$link->setAttribute('target', '_blank');
								$caption = $dom->createElement( 'figcaption' );
								$caption->appendChild($link);	
							} else {
								$caption = $dom->createElement( 'figcaption', $flickrtitle);
							}

							$caption->setAttribute('class', 'wp-element-caption fep-caption');
							$wrapper->appendChild($caption);
							
							$captiontext = "Add caption with Flickr title for " . $image->getAttribute('src');
							flickrembedpro_consolelog($captiontext);
							$imagestitled++;
						} else {
							$captiontext = "Cannot add caption (already present) with Flickr title for " . $image->getAttribute('src');
							flickrembedpro_consolelog($captiontext);
						}
					}
				}
				
				flickrembedpro_consolelog("Add caption with Flickr title completed, performed " . strval($imagestitled) . " replacements");
			} 


			/* Process option [6] with [7], Responsify Flickr */ 
			if ( get_option('flickrembedpro_addsrcsetandsizes') && ( $images->length > 0 ) ) {

				$srcsizes = trim( get_option('flickrembedpro_flickrsizes') );
				if ( empty($srcsizes) ) {
					$srcsizes = "100vw";
				}

				$imagessrcset = 0;
				# Go through all the images
				foreach ($images as $image) {
					
					if ( false === stripos($image->getAttribute('src'), 'flickr.com') ) {
						// Not a flickr image
						continue;
					}
					
					$src = $image->getAttribute('src');
					
					$info = pathinfo($src);
					$basename = $info['filename'];
					// Checks which suffix is present 
					
					$parts = explode('_', $basename);
					$last_part = end($parts);
					// Suffixes for which staticFlickr URL has a secret
					// All flickr sizes and suffixes on https://www.flickr.com/services/api/misc.urls.html
				    $suffix_escape = array('h', 'k', '3k', '4k', 'f', '5k', '6k', '7k', '8k', '9k', 'o');

					if (in_array($last_part, $suffix_escape)) {
						continue;
					}
					
					// Removes suffix if exists
					$basename = preg_replace('/_[a-zA-Z]$/', '', $basename);
					$stem = $info['dirname'] . "/" . $basename;
					
					$srcset = $stem .'_m.jpg 240w, '. $stem .'_n.jpg 320w, '. $stem .'_w.jpg 400w, '. $stem . '.jpg 500w, ' . $stem . '_z.jpg 640w, ' . $stem .'_c.jpg 800w, '. $stem .'_b.jpg 1024w';

					if ( false !== stripos($info['extension'], 'jp') ) {
						// Only adds attributes to JPEGs
						$image->setAttribute('sizes', $srcsizes);
						$image->setAttribute('srcset', $srcset);
						$image->setAttribute('class', trim($image->getAttribute('class') . ' fep-responsified'));
						
						$urltext = "Attached srcset and sizes for " . $image->getAttribute('src');
						flickrembedpro_consolelog($urltext);
						$imagessrcset++;
					}

				}
				
				flickrembedpro_consolelog("Responsify Flickr completed, performed " . strval($imagessrcset) . " replacements");

			}


			# Turn on errors again...
			libxml_use_internal_errors(false);

			# Strip DOCTYPE etc from output
			// return str_replace(['<body>', '</body>'], '', $dom->saveHTML($dom->getElementsByTagName('body')->item(0)));
			return $dom->saveHTML();
			
		}
	}
	

	
	
	/* Module for option [52] */
	if ( ! function_exists('flickrembedpro_responsifycaptionshortcodes') ) {		
		function flickrembedpro_responsifycaptionshortcodes($val, $attr, $content = null)
		{

			extract(shortcode_atts(array(
				'id'    => '',
				'align' => '',
				'width' => '',
				'caption' => ''
			), $attr));

			if ( 1 > (int) $width || empty($caption) )
				return $val;


			$capid = '';
			if ( $id ) {
				$id = esc_attr($id);
				$capid = 'id="figcaption_'. $id . '" ';
				$id = 'id="' . $id . '" aria-labelledby="figcaption_' . $id . '" ';
			}

			// Replace width with width 100%
			/* An alternative could be better in the long term, see https://www.ltvco.com/engineering/height-width-attributes-img-tag/ */
			$content = preg_replace('/(<img\s[^>]*)\bwidth\s*=\s*[\'"]?\s*(\d+(?:px|%)?)(\s*[\'"]?[^>]*>)/ui', '$1 width="100%" $3', $content);
			// Remove height if any
			$content = preg_replace('/(<img\s[^>]*)\bheight\s*=\s*[\'"]?\s*(\d+(?:px|%)?)(\s*[\'"]?[^>]*>)/ui', '$1 $3', $content);

			
			$captiontext = "Responsified caption shortcode for id (" . $id . ") and caption (" . $caption . ")";
			flickrembedpro_consolelog($captiontext);

			return '<figure ' . $id . 'class="wp-block-image wp-caption-fep-legacy aligncenter">'
			. do_shortcode( $content ) . '<figcaption ' . $capid 
			. 'class="wp-element-caption wp-caption-text-fep-legacy">' . $caption . '</figcaption></figure>';
			/* aligncenter as a class ensures that the margin-top of photos is 10px consistent with Gutenberg blocks*/
			
		}
	}
	

	
	/* Module for option [53] */
	if ( ! function_exists('flickrembedpro_content_image_sizes_attr') ) {		
		function flickrembedpro_content_image_sizes_attr($sizes, $size) {
		
			$customsizes = get_option('flickrembedpro_wordpresssizes');
		

			$replacementtext = "WordPress media images sizes attribute replaced with " . $customsizes; 
			flickrembedpro_consolelog($replacementtext);

			return $customsizes;
		}
	}		


	
	/* Legacy function */
	if ( ! function_exists('flickrembedpro_responsify_flickr_pregreplace') ) {		
		function flickrembedpro_responsify_flickr_pregreplace($content) {
			$srcsizes = trim( get_option('flickrembedpro_flickrsizes') );
			if ( empty($srcsizes) ) {
				$srcsizes = "100vw";
			}
			// All flickr sizes and suffixes on https://www.flickr.com/services/api/misc.urls.html
			$patterns = array(
			  /* Responsifies LEGACY vertical photos, for which src already has a photo in _b*/
				'/<img(.*src=\"(.*static.*flickr.com.*)(_b).jpg\")(.*)width=\"(\d+)\"(.*)height=\"(\d+)\"(.*)\/?>/i' => function($matches) use ($srcsizes) {
				return '<img'.$matches[1].' srcset="'.$matches[2].'_n.jpg 211w, '.$matches[2].'.jpg 331w, '.$matches[2].'_z.jpg 424w, '.$matches[2].'_b.jpg 678w" '.$srcsizes.$matches[6].$matches[8].'/>';
			  },
			  /* Responsifies GUTENBERG vertical photos */
				'/<figure(.*)wp-block-image(.*)<img(.*src=\"(.*static.*flickr.com.*)(_b).jpg\")(.*)><\/figure>/i' => function($matches) use ($srcsizes) {
				return '<figure'.$matches[1].'wp-block-image'.$matches[2].'<img'.$matches[3].' srcset="'.$matches[4].'_n.jpg 211w, '.$matches[4].'.jpg 331w, '.$matches[4].'_z.jpg 424w, '.$matches[4].'_b.jpg 678w" '.$srcsizes.$matches[6].'></figure>';
			  },
			  /* Responsifies all other GUTENBERG photos */
				'/<img(.*src=\"(.*static.*flickr.com.*[^_].)(_.)?\.jpg\")(.*?)>/i' => function($matches) use ($srcsizes) {
				return '<img'.$matches[1].' srcset="'.$matches[2].'_n.jpg 320w, '.$matches[2].'.jpg 500w, '.$matches[2].'_z.jpg 640w, '.$matches[2].'_c.jpg 800w, '.$matches[2].'_b.jpg 1024w" '.$srcsizes.$matches[4].'>';
			  }
			);
			
			$content = preg_replace_callback_array($patterns, $content, $limit = -1, $replacements);
			
			$replacementtext = "Responsify Flickr (pregreplace) performed " . $replacements . " replacements"; 
			flickrembedpro_consolelog($replacementtext);
		  
			return $content;
		}
	}
	




	/* Module for option [92] */	
	if ( ! function_exists('flickrembedpro_disable_oembed_cache') ) {
		/* Source: WPSE */
		function flickrembedpro_disable_oembed_cache() {	
			add_filter( 'oembed_ttl', function($ttl) {
				/* Prevents page from serving cache */
				$GLOBALS['wp_embed']->usecache = 0;
				$ttl = 0;
				/* Removes caches in wp_postmeta */	
				do_action( 'wpse_do_cleanup' );
				
				return $ttl;
			} );

			add_filter( 'embed_oembed_discover', function( $discover ) {
				if( 1 === did_action( 'wpse_do_cleanup' ) ) {
					$GLOBALS['wp_embed']->usecache = 1;
				}
				return $discover;
			} );
		}
	}