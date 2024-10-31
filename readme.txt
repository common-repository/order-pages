=== ShiftThis | Order Pages ===
Contributors: shiftthis
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal%40shiftthis%2enet&item_name=WordPress%20Plugin%20Development%20Donation&no_shipping=0&return=http%3a%2f%2fwww%2eshiftthis%2enet%2f&no_note=1&tax=0&currency_code=CAD&lc=CA&bn=PP%2dDonationsBF&charset=UTF%2d8&notify_url=http%3a%2f%2fwww%2eshiftthis%2enet%2f
Tags: order, page
Requires at least: 2.0.2
Tested up to: 2.1
Stable tag: 0.3

A handy plugin for re-arranging your page-order easily. 

== Description ==


Here is a handy plugin for re-arranging your page-order easily. If you've ever been frustrated with having to move one page's order, only to find you have to update every other pages order as well, then this plugin is for you! It's very basic at the moment. No fancy ajax drag & drop, but it does the job.

This plugin currently supports a maximum of 2 levels of Page Children (ie. Sub-Sub-Pages).

== Installation ==


1. Upload 'st-orderpages.php' to the 'wp-content\plugins' directory
2. Activate the plugin titled 'ShiftThis | Order Pages' through the 'Plugins' menu in WordPress
3. In your theme's template make sure your _wp\_list\_pages_ function has a parameter of _sort\_column=menu\_order_ like so: `<?php wp_list_pages('sort_column=menu_order'); ?>`
4. Access the **Manage** Tab in your Admin Panel and select the **Order Pages** SubMenu Item.
5. Use the + & - buttons to move a page up or down in page order. The actual page order value appears to the left.

== Frequently Asked Questions ==

= Where can I ask support questions for this plugin? =

[support.ShiftThis.net](http://support.shiftthis.net)

== Screenshots ==
1. The Manage Page for re-arranging Page Order.