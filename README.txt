=== Wordpress Plugin Framework ===
Contributors: husterk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=husterk%40doubleblackdesign%2ecom&item_name=Wordpress%20Plugin%20Framework&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: wordpress, plugin framework, plugin development
Requires at least: 2.5
Tested up to: 2.5.1
Stable tag: 0.06

The Wordpress Plugin Framework (WPF) is a PHP class that is used to provide a framework for the development of Wordpress plugins.

== Description ==

The Wordpress Plugin Framework (WPF) is a PHP class that is used to provide a framework for the development of Wordpress plugins.
This package also contains a demo plugin which is used to demonstrate the features and capabilities of the (WPF). The overall
intention of the WPF is to generalize and simplify plugin design while also helping plugins adhere to a common administration
and usage standard.

For more information please see the *wpf_user_manual.pdf* file included with this project. You may also download the
latest copy of the [Wordpress Plugin Framework User Manual](http://code.google.com/p/wordpress-plugin-framework/ "Wordpress Plugin Framework User Manual") from
the WPF project website.

Currently, the WPF demo plugin demonstrates the features of the WPF listed below.

1. Deriving a plugin from the WordpressPluginFramework base class.
2. Creating options for the plugin.
3. Initializing the plugin.
4. Creating content blocks for the plugin's administration page.
5. Registering the plugin's administration page with Wordpress.
6. Updating and resetting of the plugin's options.
7. Uninstallation of the plugin.

= License =

This WPF demo plugin and Wordpress Plugin Framework are being developed under the GNU General Public License, version 2.

[GNU General Public License, version 2](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html "GNU General Public License, version 2")

= Support WPF Development =

Help support development of the Wordpress Plugin Framework by making a donation.

[Donate to Double Black Design](https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=husterk%40doubleblackdesign%2ecom&item_name=Wordpress%20Plugin%20Framework&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8 "Donate to Double Black Design")

= WPF Project Locations =

The latest version of the Wordpress Plugin Framework can be found on the Wordpress Extend website. A link to this site is provided below.

[Download the Wordpress Plugin Framework](http://wordpress.org/extend/plugins/wordpress-plugin-framework/ "Download the Wordpress Plugin Framework")

The Wordpress Plugin Framework project can be found on the Google Code website. A link to this site is provided below.

[Wordpress Plugin Framework Project](http://code.google.com/p/wordpress-plugin-framework/ "Wordpress Plugin Framework Project")

== Installation ==

1. Unzip the archive file.
2. Verify the name of the unzipped folder to be "wordpress-plugin-framework".
3. Upload the "wordpress-plugin-framework" folder to the root of your Wordpress "plugins" folder.
4. Activate the "Wordpress Plugin Framework" demo plugin in your website's plugin administration page.
5. Navigate to the "Plugins" ~ "WPF Demo Plugin" administration page.

== Frequently Asked Questions ==

= What is the Wordpress Plugin Framework project? =

The Wordpress Plugin Framework (WPF) project is a framework used for the development and implementation of Wordpress plugins. The
Wordpress Plugin Framework demo plugin provided with this framework is used simply to demonstrate the features and capabilities
of the WPF.

= Do I need to modify the wordpress-plugin-framework.php file? =

Yes. There you will need to prefix the WordpressPluginFramework class name with the name of your plugin in order to prevent class
duplication errors within the Wordpress core.
   - i.e. "class DemoPlugin_WordpressPluginFramework" -> "class YourPluginName_WordpressPluginFramework".

= How can I request new features for the WPF? =

New feature requests may be submitted via the [Wordpress Plugin Framework Feature Request Forum](http://doubleblackdesign.com/forums/wordpress-plugin-framework/wpf-feature-requests/0/ "Wordpress Plugin Framework Feature Request Forum").

= How can I report issues found while using the WPF? =

New issue submissions may be submitted via the [Wordpress Plugin Framework Issue Submission Forum](http://doubleblackdesign.com/forums/wordpress-plugin-framework/wpf-issue-submissions/0/ "Wordpress Plugin Framework Issue Submission Forum").

== Screenshots ==

1. WPF Demo Plugin administration interface.

2. WPF Demo Plugin uninstall option.

3. WPF Demo Plugin uninstalled (requesting deactivation).

== Change Log ==

* v0.06 (7/6/2008) - Minor bugfix for handling plugin option updates.

* v0.05 (7/6/2008) - Updated for new WP 2.5 administration interface.

* v0.04 (11/20/2007) - Minimized user required WPF base class modifications, improved options support, and added user manual and screenshots.

* v0.03 (11/16/2007) - Added support for multiple plugins to simultaneously utilize the WPF.

* v0.02 (11/07/2007) - Updated to support more option types and better plugin administration.

* v0.01 (11/01/2007) - Initial release of the Wordpress Plugin Framework and demo plugin.
