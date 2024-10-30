=== Plugin Name ===
Contributors: cyberws
Donate link: http://www.cyberws.com/cleverwise-plugins/
Tags: downloads, phpbb,statistics,forum
Requires at least: 3.0.1
Tested up to: 6.0
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays key phpBB statistics from up to three boards on your Wordpress site with total control over layout and what is displayed.

== Description ==

<p>This plugin will check your phpBB board and grab vital statistics such as total members, total threads, total posts, newest member, and last post and display that information on your Wordpress site.  phpBB can even be on another site, server, and datacenter.  You have total control over which statistics are displayed.  The theme is totally customizable and its easy to modify to match your design concept.</p>

<p>You are able to pull statistics from up to three (3) boards and display them on your Wordpress powered site.  Each board may have its own refresh setting.  So if you have a busy board you may update its information more frequently than one that isn't so heavily traveled.</p>

<p>Why a refresh interval? When loading statistics from your phpBB install a web query is required to grab the information.  This makes sense, right?  Well this action is taxing because another system must be contacted and possibly another datacenter too.  So the data collected from your board(s) is stored in your local Wordpress database.  Thus between refresh periods the phpBB statistics are loaded from your site's database, which makes this process more optimized for performance.  However there are two other important optimization features.</p>

<p>First when the refresh period has lapsed and its time for new information only when the next page load occurs will this plugin contact phpBB.  So if you have a 15 minute refresh period and your site gets no visitors for 32 minutes then at that time the plugin will grab the updated information and start a new 15 minute count down.  After all why grab the information when no one is visiting the site.  Yes, if you are wondering, spiders and bots count as visitors and thus new information will be pulled if they are the visitor.</p>

<p>Second when it comes time to update the board information this plugin will not wait long for phpBB to respond.  In fact it only waits up to two seconds.  If the board can't be contacted in that time due to a network issue, slow server, or server is down the last saved statistics are displayed.  Cleverwise phpBB Statistics will then automatically keep trying with every page load to update the information.  So as you can see this plugin is designed to reduce the amount of resources required to accomplish the goal at hand.</p>

<p>It should be noted that there are two required parts to this setup.  First is, obviously, this very plugin.  The second involves some important files that allow this plugin to communicate with your phpBB install(s) called the <wrong>wpapi</strong>.  You see this system talks to the wpapi, that is uploaded to your phpBB styles directory, and that is what gathers the actual information from your phpBB(s).  Now there are two important settings in the wpapi.</p>

<p>First is a secret key, that you create/make up, which keeps unauthorized sources from grabbing your data.  Second is an optional setting that allows you to direct the wpapi system to omit specific forum(s) when looking for the latest post.  Why this feature? Well if you have a hidden forum on your phpBB board you probably don't want any topics from it displayed in the last post to your board do you? Nope.</p>

<p>Now you are wondering where do you get this wpapi? The download link is posted in the "Help Guide" in this plugin.  So once you have installed this plugin simply download those key files and follow the instructions in the "Help Guide".</p>

<p>Language Support: Should work for all languages that use the A-Z alphabet.  The default language to display the statistics is in English but is easily changeable into any language.</p>

<p>Live Site Preview: Want to see this plugin in action on a real live site?  <a href="http://www.armadafleetcommand.com/">ArmadaFleetCommand.com</a> and look in the right sidebar.</p>

<p>Shameless Promotion: See other <a href="http://wordpress.org/plugins/search.php?q=cleverwise">Cleverwise Wordpress Directory Plugins</a></p>

<p>Thanks for looking at the Cleverwise Plugin Series! To help out the community reviews and comments are highly encouraged.  If you can't leave a good review I would greatly appreciate opening a support thread before hand to see if I can address your concern(s).  Enjoy!</p>

== Installation ==

<ol>
<li>Upload the <strong>cleverwise-phpbb-statistics</strong> directory to your plugins.</li>
<li>In Wordpress management panel activate "<strong>Cleverwise phpBB Statistics</strong>" plugin.</li>
<li>In the "<strong>Settings</strong>" menu a new option "<strong>phpBB Statistics</strong>" will appear.</li>
<li>Once you have loaded the main panel for the plugin click on the "<strong>Help Guide</strong>" link which explains in detail how to use the plugin.</li>
</ol>

== Frequently Asked Questions ==

= Can I display multiple phpBB board statistics at once? =

Yes.  Up to three phpBB board statistics may be displayed on your site.

= Where do you find the wpapi files? =

Visit the plugin control panel and click on the "Help Guide" link.  On that screen is the download link.

= May I change the look? =

Yes.  You may easily modify the layout/look of the information.  Plus you may choose to reorganize and/or omit any information; its all up to you.

== Screenshots ==

1. screenshot-1.png

== Changelog ==

= 2.2 =
Fixed: Widget code for PHP 8.0

= 2.0 =
Theme changes

= 1.9 =
Minor theme changes

= 1.8 =
Fixed: Clicking on post link now works correctly

= 1.7 =
Now supports phpBB 3.1 - REQUIRES updating WPAPI files - Only supports MySQL compatible databases<br>
New: Ability to display last five posts (requires template change)</li>

= 1.6 =
Background edits to eliminate some PHP notice messages

= 1.5 =
An easy to use display widget has been added

= 1.4 =
Fixed: Shortcode in certain areas would cause incorrect placement

= 1.3 =
Update: The wpapi was updated

= 1.2 =
Update: Minor alterations

= 1.1 =
Update: Altered framework code to fit Wordpress Plugin Directory terms<br>
Update: Some structural changes where made

= 1.0 =
Initial release of plugin

== Upgrade Notice ==

= 1.8 =
Fixed: Clicking on post link now works correctly
