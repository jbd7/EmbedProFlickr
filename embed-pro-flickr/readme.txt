=== Embed Pro Flickr ===
Contributors: jbd7
Tags: flickr, embed, oembed, responsive, srcset, image, photo, customize, block, sizes, images, figure, caching, art direction
Tested up to: 6.2
Stable tag: 1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://www.buymeacoffee.com/jbd7

Embed Pro Flickr helps you customize Flickr photos embedded in your WordPress site, improving user experience, performance and SEO.

== Description ==

Embed Pro Flickr is a WordPress plugin designed to help you customize the Flickr images embedded on your website, especially when working with the Flickr Embed block inside the Gutenberg editor.
It also takes cares of Flickr images embedded manually, especially before WordPress 5.0, ensuring consistency in the visitor experience, and without modifying the database.

It is aimed at Flickr photographers who are also WordPress bloggers, and particularly to those who have been using this combo over the years and seen breaking changes. 

Expected impact:
*	Faster page load: visitors are only served the smallest image size necessary for their screen  
*	More visually appealing: higher resolution images are made available to the browser, for example to visitors with Retina screens, thanks to the image sizes already processed by Flickr 
* 	Ensures visual consistency between images embedded manually from Flickr, images embedded using the Caption shortcode and images embedded using the Flick Embed block
*	Increased SEO by automatically using Flickr metadata to set titles and captions


Plugin features:
*	Systematically add classes to Flickr Embed blocks
* 	Customize what happens when clicking on an image embedded with the Flickr Embed block 
* 	Assign the Flickr photo title to the <img> tag
*	Make the Flickr photo page URL open in a new window
*	Automatically add a caption with the Flickr image title, and optionally hyperlink it
*	Make Flickr images responsive with `srcset` and `sizes` attributes, just like native WordPress images, improving the website loading time while serving better images to visitors. 
*	Replace the hyperlinked Flickr page with the JPG image
*	Wrap lone <img> tags inside <figure> tags
*	Responsify caption shortcodes with fixed width, used in older version of WordPress


Plugin chracteristics:
* 	Does NOT create new image sizes
*	Does NOT modify the database to operate. However, for debugging purposes, it can clear up cached oEmbed meta_keys in `wp_postmeta` faster than the 24-hour default
*	Lightweight (one single PHP page)
*	Has no visible component on the front end
*	Does not increase the site response time, provided a caching plugin is used



== Installation ==

1.	Install from your WordPress site by adding a new plugin, search for Embed Pro Flickr

or

1.	Upload the file Embed-Pro-Flickr.zip into the '/wp-content/plugins/' folder of your WordPress installation
1.	Unzip the plugin to create the Embed Pro Flickr folder
1.	Activate the plugin through the 'Plugins' menu in WordPress


== Configuration ==

To configure plugin, go to 'Settings' -> 'Embed Pro Flickr' from the WordPress dashboard


== Screenshots ==

1. Example of visible changes when using Embed Pro Flickr
2. Example of an image HTML code, with srcset multiple sizes, after when using the Responsify Flickr feature 
3. Plugin settings page


== Changelog ==

= 1.0 (20230401) = 
* Initial release, tested with WordPress 6.2