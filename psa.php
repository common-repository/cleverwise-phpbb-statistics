<?php
/*
* Copyright 2014 Jeremy O'Connell  (email : cwplugins@cyberws.com)
* License: GPL2 .:. http://opensource.org/licenses/GPL-2.0
*/

////////////////////////////////////////////////////////////////////////////
//	Verify admin panel is loaded, if not fail
////////////////////////////////////////////////////////////////////////////
if (!is_admin()) {
	die();
}

////////////////////////////////////////////////////////////////////////////
//	Menu call
////////////////////////////////////////////////////////////////////////////
add_action('admin_menu', 'cw_phpbb_stats_aside_mn');

////////////////////////////////////////////////////////////////////////////
//	Load admin menu option
////////////////////////////////////////////////////////////////////////////
function cw_phpbb_stats_aside_mn() {
	add_submenu_page('options-general.php','phpBB Statistics Options','phpBB Statistics','manage_options','cw-phpbb-stats','cw_phpbb_stats_aside');
}

////////////////////////////////////////////////////////////////////////////
//	Load admin functions
////////////////////////////////////////////////////////////////////////////
function cw_phpbb_stats_aside() {
Global $wpdb,$current_user,$pbbs_wp_option,$cw_phpbb_stats_pull_url,$cwfa_phpbb;

	////////////////////////////////////////////////////////////////////////////
	//	Set action value
	////////////////////////////////////////////////////////////////////////////
	if (isset($_REQUEST['cw_action'])) {
		$cw_action=$_REQUEST['cw_action'];
	} else {
		$cw_action='main';
	}

	////////////////////////////////////////////////////////////////////////////
	//	Previous page link
	////////////////////////////////////////////////////////////////////////////
	$pplink='<a href="javascript:history.go(-1);">Return to previous page...</a>';

	////////////////////////////////////////////////////////////////////////////
	//	Define Variables
	////////////////////////////////////////////////////////////////////////////
	$cw_phpbb_stats_action='';
	$cw_phpbb_stats_html='';

	////////////////////////////////////////////////////////////////////////////
	//	Help Guide
	////////////////////////////////////////////////////////////////////////////
	if ($cw_action == 'settingshelp') {

$cw_phpbb_stats_html .=<<<EOM
<div style="margin: 10px 0px 5px 0px; width: 400px; border-bottom: 1px solid #c16a2b; padding-bottom: 5px; font-weight: bold;">Introduction:</div>
<p>This system allows you to display information from phpBB board installs on your Wordpress powered site.  You have total control over the layout of the information, what information will be displayed, how often it will be updated, where it will appear on your website, and may even pull information from multiple phpBB installs that are on other websites and servers.</p>
<p>This system has been designed to be as resource friendly as possible.  It stores the most recent information from a phpBB install and only recontacts phpBB when it is time for a refresh.  Therefore between refresh periods the statistics are being loaded from the Wordpress database.  Also this plugin will only wait two (2) seconds for a phpBB install to respond with updated information.  If no response is provided because phpBB is overloaded, slow, or down this plugin will just display the last saved information.  In addition information is only updated when a visitor (human or bot) loads your site.  So even if you have set information to refresh every five (5) minutes but there is no visitor for 38 minutes the statistics won't be updated until that point.</p>
Note: If you place the shortcode to display phpBB information in a text widget and it isn't working, then you need to add a filter code.  At the bottom of this guide you will find the code to add to your theme's <b>functions.php</b> file.  Place the code on a new line and save changes.  If necessary upload the file change to your website.  If you are confused no worries there is an easy to use drag and drop display widget too.
<p>Steps:</p>
<ol>
<li><p>Download <a href="https://www.cyberws.com/info/wpapi.zip">Cleverwise phpBB Statistics WPAPI files</a> to your computer then unzip, then open <b>wpapi.config.sample.php</b> in the <b>wpapi</b> directory and setup the security key for the phpBB install.  This will keep unauthorized sources from accessing the api.  You simply make up/generate this key, which is case sensitive.  The longer and more random the better.  For example: "access" isn't strong, while "&C!-9OV;8{:%K#2on51@YNkv4H!*uREg" is great.</p>
<p>Optional: There is a variable that allows you to omit specific forum ids from being used when generating the most recent post.  Therefore if you have some hidden forums or forums you just don't want included list their forum ids separated by commas.</p></li>
<li>Save changes to the new file name of <b>wpapi.config.php</b> and upload the whole <b>wpapi</b> directory to the <b>styles</b> directory in your phpBB install.  For example: public_html/phpbb3.1/styles/</li>
<li><p>In this plugin the main page loads the settings, which need to be setup:</p>
<ol>
<li>Name of the phpBB install.  Why isn't the name just grabbed from phpBB? It was considered, however stats are often included in sidebars or tight design areas and long named phpBB installs can cause formatting issues.  Therefore a custom name box is provided.</li>
<li>Full URL, including http:// or https://, to your phpBB install's main page.</li>
<li>The security key that was entered in the <b>wpapi.config.php</b> file.</li>
<li>The refresh interval to grab/load updated information.</li>
</ol>
<p>If you have additional phpBB installs set them in the provided optional forum slots.  However remember to check the <b>wpapi.config.php</b> settings as these could be different.  It is strongly recommended that  each phpBB install use its own unique key.</p>
</li>
<li>In the <b>Display/Theme Style</b> you may edit the layout to achieve a look that works with your site design.  It is in this box where you control what and how the information will be displayed.  This style will be repeated/used for all phpBB installs.
</li>
<li>Once the phpBB install settings and style have been completed save changes.  Obviously correct any errors that are displayed.</li>
<li><p>There are two methods to display the phpBB statistics.  You may used one or both.</p>
	<ol>
	<li><b>Widget method</b>: Visit "Appearance" in your Wordpress admin navigation then "Widgets".  You will find a widget called "phpBB Statistics" which you may add to the desired section(s)/area(s) of your site.  This is the easiest method.</li>
	<li><b>Shortcode method</b>: Add the shortcode <b>[cw_phpbb_stats]</b> to the area(s) of your Wordpress site (header, footer, widgets, sidebar, post(s), page(s), etc) where you wish the phpBB statistics to be displayed.  Do keep in mind that, by default, Wordpress doesn't process shortcodes in text widgets.  Therefore you will need to add the code below to your <b>functions.php</b> file.  This is the more versatile method.</li>
	</ol>
</li>
<li>Finally if you need to change your phpBB install information or style code simply edit the information necessary and save it.</li>
</ol>

<div style="margin: 10px 0px 5px 0px; width: 400px; border-bottom: 1px solid #c16a2b; padding-bottom: 5px; font-weight: bold;">Text widget filter code for your theme's functions.php:</div>
add_filter('widget_text', 'do_shortcode');
<p>Tip: If you only use the Widget display method you may skip the above code.</p>

<div style="margin: 10px 0px 5px 0px; width: 400px; border-bottom: 1px solid #c16a2b; padding-bottom: 5px; font-weight: bold;">Upgrading to Version 1.7 and up:</div>
<p>There are two important items to note!</p>

<p>First the WPAPI files have been updated (see link in Step 1 above) and thus it is <b>REQUIRED</b> you download and upload the new files to your forum.  This was done to support the phpBB 3.1 file changes as well as add a new feature (more in a moment).  To update WPAPI simply overwrite the old files with the new ones.  Your configuration settings should be saved.  It should be noted that due to phpBB 3.1 changes support for non MySQL compatible databases was dropped, therefore this version will <b>ONLY</b> work with phpBB forums installed using the MySQL database system.</p>

<p>Second a new feature was added that allows the last five posts to be displayed with the forum stats.  A new display template tag was created <b>{{RECENT_POSTS}}</b> to tell WordPress were to display this information.  You'll need to edit your template if you wish to use this new feature.</p>
EOM;

	////////////////////////////////////////////////////////////////////////////
	//	What Is New?
	////////////////////////////////////////////////////////////////////////////
	} elseif ($cw_action == 'settingsnew') {
		
		$cw_phpbb_stats_whats_new=array(
			'2.2'=>'Fixed: Widget code for PHP 8.0',
			'2.0'=>'Theme changes',
			'1.9'=>'Minor theme changes',
			'1.8'=>'Fixed: Clicking on post link now works correctly',
			'1.7'=>'Now supports phpBB 3.1 - REQUIRES updating WPAPI files - Only supports MySQL compatible databases|New: Ability to display last five posts (requires template change)',
			'1.6'=>'Background edits to eliminate some PHP notice messages',
			'1.5'=>'An easy to use display widget has been added',
			'1.4'=>'Fixed: Shortcode in certain areas would cause incorrect placement',
			'1.3'=>'Update: The wpapi was updated',
			'1.2'=>'Update: Minor alterations',
			'1.1'=>'Update: Altered framework code to fit Wordpress Plugin Directory terms|Update: Some structural changes where made',
			'1.0'=>'Initial release of plugin'
		);
		$cw_phpbb_stats_whats_new_build='';
		foreach ($cw_phpbb_stats_whats_new as $cw_phpbb_stats_whats_new_version => $cw_phpbb_stats_whats_new_news) {
			$cw_phpbb_stats_whats_new_build .='<p>Version: <b>'.$cw_phpbb_stats_whats_new_version.'</b></p>';
			$cw_phpbb_stats_whats_new_news=preg_replace('/\|/','</li><li>',$cw_phpbb_stats_whats_new_news);
			$cw_phpbb_stats_whats_new_build .='<ul style="list-style: disc; margin-left: 25px;"><li>'.$cw_phpbb_stats_whats_new_news.'</li></ul>';
		}

$cw_phpbb_stats_html .=<<<EOM
<p>The following lists the new changes from version-to-version.</p>
$cw_phpbb_stats_whats_new_build
EOM;

	////////////////////////////////////////////////////////////////////////////
	//	Settings Update
	////////////////////////////////////////////////////////////////////////////
	} elseif ($cw_action == 'settingsv') {
		//	Load form variables
		$forum_1_name=$cwfa_phpbb->cwf_san_all($_REQUEST['forum_1_name']);
		$forum_1_url=$cwfa_phpbb->cwf_san_url($_REQUEST['forum_1_url']);
		$forum_1_key=$cwfa_phpbb->cwf_san_all($_REQUEST['forum_1_key']);
		$forum_1_refresh_ts=$cwfa_phpbb->cwf_san_int($_REQUEST['forum_1_refresh_ts']);

		$forum_2_name=$cwfa_phpbb->cwf_san_all($_REQUEST['forum_2_name']);
		$forum_2_url=$cwfa_phpbb->cwf_san_url($_REQUEST['forum_2_url']);
		$forum_2_key=$cwfa_phpbb->cwf_san_all($_REQUEST['forum_2_key']);
		$forum_2_refresh_ts=$cwfa_phpbb->cwf_san_int($_REQUEST['forum_2_refresh_ts']);

		$forum_3_name=$cwfa_phpbb->cwf_san_all($_REQUEST['forum_3_name']);
		$forum_3_url=$cwfa_phpbb->cwf_san_url($_REQUEST['forum_3_url']);
		$forum_3_key=$cwfa_phpbb->cwf_san_all($_REQUEST['forum_3_key']);
		$forum_3_refresh_ts=$cwfa_phpbb->cwf_san_int($_REQUEST['forum_3_refresh_ts']);

		$phpbb_stats_format=$cwfa_phpbb->cwf_san_alls($_REQUEST['phpbb_stats_format']);

		$forum_1_stats='';
		$forum_2_stats='';
		$forum_3_stats='';

		//	Check for errors
		$error='';
		if (!$forum_1_name) {
			$error .='<li>No board one name</li>';
		}
		if (!$forum_1_url or substr_count($forum_1_url,'http') != '1') {
			$error .='<li>No board one URL</li>';
		} else {
			$forum_1_url=preg_replace('/index.php/','',$forum_1_url);
			$forum_1_url=$cwfa_phpbb->cwf_trailing_slash_on($forum_1_url);

			$forum_1_stats=sprintf($cw_phpbb_stats_pull_url,$forum_1_url,$forum_1_key);
			$forum_1_stats=cw_phpbb_stats_httpdata($forum_1_stats);

			if (!$forum_1_stats) {
				$error .='<li>Board one URL doesn\'t seem to be working.  Often caused by invalid secret key or slow site.</li>';
			}
		}
		if (!$forum_1_key) {
			$error .='<li>No board one key</li>';
		}
		if (!$forum_1_refresh_ts) {
			$forum_1_refresh_ts='300';
		}

		if ($forum_2_name) {
			if (!$forum_2_url or substr_count($forum_2_url,'http') != '1') {
				$error .=$forum_1_name.'<li>No board two URL</li>';
			} else {
				$forum_2_url=preg_replace('/index.php/','',$forum_2_url);
				$forum_2_url=$cwfa_phpbb->cwf_trailing_slash_on($forum_2_url);

				$forum_2_stats=sprintf($cw_phpbb_stats_pull_url,$forum_2_url,$forum_2_key);
				$forum_2_stats=cw_phpbb_stats_httpdata($forum_2_stats);
				if (!$forum_2_stats) {
					$error .='<li>Board two URL doesn\'t seem to be working.  Often caused by invalid secret key or slow site.</li>';
				}
			}
			if (!$forum_2_key) {
				$error .='<li>No board two key</li>';
			}
			if (!$forum_2_refresh_ts) {
				$forum_2_refresh_ts='300';
			}
		} else {
			$forum_2_url='';
			$forum_2_key='';
			$forum_2_refresh_ts='';
		}

		if ($forum_3_name) {
			if (!$forum_3_url or substr_count($forum_3_url,'http') != '1') {
				$error .='<li>No board three URL</li>';
			} else {
				$forum_3_url=preg_replace('/index.php/','',$forum_3_url);
				$forum_3_url=$cwfa_phpbb->cwf_trailing_slash_on($forum_3_url);

				$forum_3_stats=sprintf($cw_phpbb_stats_pull_url,$forum_3_url,$forum_3_key);
				$forum_3_stats=cw_phpbb_stats_httpdata($forum_3_stats);
				if (!$forum_3_stats) {
					$error .='<li>Board three URL doesn\'t seem to be working.  Often caused by invalid secret key or slow site.</li>';
				}
			}
			if (!$forum_3_key) {
				$error .='<li>No board three key</li>';
			}
			if (!$forum_3_refresh_ts) {
				$forum_3_refresh_ts='300';
			}
		} else {
			$forum_3_url='';
			$forum_3_key='';
			$forum_3_refresh_ts='';
		}

		if (!$phpbb_stats_format) {
			$error .='<li>No display/theme style</li>';
		}

		if ($error) {
			$cw_phpbb_stats_html='Please fix the following in order to save settings:<br><ul style="list-style: disc; margin-left: 25px;">'. $error .'</ul>'.$pplink;
		} else {
			//	If forum two is blank but forum three has data reassign it to forum two
			if (!$forum_2_name and $forum_3_name) {
				$forum_2_name=$forum_3_name;
				$forum_2_url=$forum_3_url;
				$forum_2_key=$forum_3_key;
				$forum_2_refresh_ts=$forum_3_refresh_ts;
				$forum_2_stats=$forum_3_stats;
				$forum_3_name='';
				$forum_3_url='';
				$forum_3_key='';
				$forum_3_refresh_ts='';
				$forum_3_stats='';
			}
			$forum_stats_update_ts=time();

			$pbbs_wp_option_array=array('forum'=>'3');
			$pbbs_wp_option_array['forum_1_name']="$forum_1_name";
			$pbbs_wp_option_array['forum_1_url']="$forum_1_url";
			$pbbs_wp_option_array['forum_1_key']="$forum_1_key";
			$pbbs_wp_option_array['forum_1_refresh_ts']="$forum_1_refresh_ts";
			$pbbs_wp_option_array['forum_1_updated_ts']="$forum_stats_update_ts";
			$pbbs_wp_option_array['forum_1_stats']="$forum_1_stats";

			$pbbs_wp_option_array['forum_2_name']="$forum_2_name";
			$pbbs_wp_option_array['forum_2_url']="$forum_2_url";
			$pbbs_wp_option_array['forum_2_key']="$forum_2_key";
			$pbbs_wp_option_array['forum_2_refresh_ts']="$forum_2_refresh_ts";
			$pbbs_wp_option_array['forum_2_updated_ts']="$forum_stats_update_ts";
			$pbbs_wp_option_array['forum_2_stats']="$forum_2_stats";

			$pbbs_wp_option_array['forum_3_name']="$forum_3_name";
			$pbbs_wp_option_array['forum_3_url']="$forum_3_url";
			$pbbs_wp_option_array['forum_3_key']="$forum_3_key";
			$pbbs_wp_option_array['forum_3_refresh_ts']="$forum_3_refresh_ts";
			$pbbs_wp_option_array['forum_3_updated_ts']="$forum_stats_update_ts";
			$pbbs_wp_option_array['forum_3_stats']="$forum_3_stats";

			$pbbs_wp_option_array['phpbb_stats_format']="$phpbb_stats_format";

			$pbbs_wp_option_array=serialize($pbbs_wp_option_array);
			$pbbs_wp_option_chk=get_option($pbbs_wp_option);

			if (!$pbbs_wp_option_chk) {
				add_option($pbbs_wp_option,$pbbs_wp_option_array);
			} else {
				update_option($pbbs_wp_option,$pbbs_wp_option_array);
			}

			$cw_phpbb_stats_html .='Settings have been saved! <a href="?page=cw-phpbb-stats">Continue to Main Menu</a>';
		}

	////////////////////////////////////////////////////////////////////////////
	//	Settings
	////////////////////////////////////////////////////////////////////////////
	} else {
		$pbbs_wp_option_array=get_option($pbbs_wp_option);
		$pbbs_wp_option_array=unserialize($pbbs_wp_option_array);

		$forum_1_name=$pbbs_wp_option_array['forum_1_name'];
		$forum_1_url=$pbbs_wp_option_array['forum_1_url'];
		$forum_1_key=$pbbs_wp_option_array['forum_1_key'];
		$forum_1_refresh_ts=$pbbs_wp_option_array['forum_1_refresh_ts'];

		$forum_2_name=$pbbs_wp_option_array['forum_2_name'];
		$forum_2_url=$pbbs_wp_option_array['forum_2_url'];
		$forum_2_key=$pbbs_wp_option_array['forum_2_key'];
		$forum_2_refresh_ts=$pbbs_wp_option_array['forum_2_refresh_ts'];

		$forum_3_name=$pbbs_wp_option_array['forum_3_name'];
		$forum_3_url=$pbbs_wp_option_array['forum_3_url'];
		$forum_3_key=$pbbs_wp_option_array['forum_3_key'];
		$forum_3_refresh_ts=$pbbs_wp_option_array['forum_3_refresh_ts'];

		$phpbb_stats_format=stripslashes($pbbs_wp_option_array['phpbb_stats_format']);

		$refresh_list=array('60'=>'1 minute','300'=>'5 minutes','600'=>'10 minutes','900'=>'15 minutes','1800'=>'30 minutes','3600'=>'1 hour','7200'=>'2 hours','14400'=>'4 hours','21600'=>'6 hours','43200'=>'12 hours','86400'=>'1 day');

		//	Set default refresh time if empty
		if (!$forum_1_refresh_ts) {
			$forum_1_refresh_ts='300';
		}
		if (!$forum_2_refresh_ts) {
			$forum_2_refresh_ts='300';
		}
		if (!$forum_3_refresh_ts) {
			$forum_3_refresh_ts='300';
		}

		//	Create refresh time lists
		$forum_1_refresh_ts_list='';
		$forum_2_refresh_ts_list='';
		$forum_3_refresh_ts_list='';
		foreach ($refresh_list as $refresh_list_time=> $refresh_list_name) {
			$forum_1_refresh_ts_list .='<option value="'.$refresh_list_time.'"';
				if ($forum_1_refresh_ts == $refresh_list_time) {
					$forum_1_refresh_ts_list .=' selected';
				}
			$forum_1_refresh_ts_list .='>'.$refresh_list_name.'</option>';

			$forum_2_refresh_ts_list .='<option value="'.$refresh_list_time.'"';
				if ($forum_2_refresh_ts == $refresh_list_time) {
					$forum_2_refresh_ts_list .=' selected';
				}
			$forum_2_refresh_ts_list .='>'.$refresh_list_name.'</option>';

			$forum_3_refresh_ts_list .='<option value="'.$refresh_list_time.'"';
				if ($forum_3_refresh_ts == $refresh_list_time) {
					$forum_3_refresh_ts_list .=' selected';
				}
			$forum_3_refresh_ts_list .='>'.$refresh_list_name.'</option>';
		}

		//	Default display
		if (!$phpbb_stats_format) {
$phpbb_stats_format .=<<<EOM
<div style="width: 296px; padding: 0px; margin: 0px; border: 1px solid #000000; background-color: #000000; color: #ffffff; font-family: tahoma; font-size: 14px; font-weight: bold; text-align: center; -moz-border-radius: 5px 5px 0px 0px; border-radius: 5px 5px 0px 0px;"><div style="padding: 1px;">{{FORUM_TITLE}}</div></div>
<div style="width: 296px; padding: 0px; margin-bottom: 10px; border: 1px solid #000000; border-top: 0px; font-family: tahoma; color: #000000; -moz-border-radius: 0px 0px 5px 5px; border-radius: 0px 0px 5px 5px;"><div style="padding: 5px;">
Last Post: {{LAST_POST}}<br>
Total Topics: {{TOPIC_COUNT}}<br>
Total Posts: {{POST_COUNT}}<br>
Total Members: {{MEMBER_COUNT}}<br>
Newest Member: {{NEWEST_MEMBER}}
<div style="width: 100%; margin-top: 3px; padding: 2px 0px 2px 0px; border-top: 1px #000000 dashed; text-align: center;">Visit: {{FORUM_LINK}}</div>
</div></div>

EOM;
		}

$cw_phpbb_stats_html .=<<<EOM
<form method="post">
<input type="hidden" name="cw_action" value="settingsv">
<p style="font-weight: bold;">Field Descriptions:</p>
<p>1) Name: What is used as title on Wordpress.<br>
2) URL: The URL, including http:// or https://, to your board.<br>
3) Secret Key: The key you assigned in the wpapi.config.php file on your board.<br>
4) Update: When the statistics to the forum will be updated (checked).</p>

<p>To remove a forum simply leave forum name blank and click "Save" button.</p>

<p style="font-weight: bold;">Board One: (Required)</p>
<div style="margin-left: 20px;">
<p>Name: <input type="text" name="forum_1_name" value="$forum_1_name" style="width: 400px;"></p>
<p>URL: <input type="text" name="forum_1_url" value="$forum_1_url" style="width: 400px;"></p>
<p>Secret Key: <input type="text" name="forum_1_key" value="$forum_1_key" style="width: 350px;"></p>
<p>Update: <select name="forum_1_refresh_ts">$forum_1_refresh_ts_list</select></p>
</div>

<p style="font-weight: bold;">Board Two: (Optional)</p>
<div style="margin-left: 20px;">
<p>Name: <input type="text" name="forum_2_name" value="$forum_2_name" style="width: 400px;"></p>
<p>URL: <input type="text" name="forum_2_url" value="$forum_2_url" style="width: 400px;"></p>
<p>Secret Key: <input type="text" name="forum_2_key" value="$forum_2_key" style="width: 350px;"></p>
<p>Update: <select name="forum_2_refresh_ts">$forum_2_refresh_ts_list</select></p>
</div>

<p style="font-weight: bold;">Board Three: (Optional)</p>
<div style="margin-left: 20px;">
<p>Name: <input type="text" name="forum_3_name" value="$forum_3_name" style="width: 400px;"></p>
<p>URL: <input type="text" name="forum_3_url" value="$forum_3_url" style="width: 400px;"></p>
<p>Secret Key: <input type="text" name="forum_3_key" value="$forum_3_key" style="width: 350px;"></p>
<p>Update: <select name="forum_3_refresh_ts">$forum_3_refresh_ts_list</select></p>
</div>

<p style="font-weight: bold;">Display/Theme Style
<div style="margin-left: 20px;">
	<p>The following are tags you may use in the display/theme style.  To omit information simply delete/remove the tag that displays it.  There is a preview box below that displays the saved code.  However keep in mind the background color of the admin panel may not match your site's background color.  Finally this theme will be repeated for each forum listed above.</p>
	<b>{{FORUM_TITLE}}</b> = Forum Title<br>
	<b>{{MEMBER_COUNT}}</b> = Total Member/user Count<br>
	<b>{{TOPIC_COUNT}}</b> = Total Topics/thread Count<br>
	<b>{{POST_COUNT}}</b> = Total Post Count<br>
	<b>{{LAST_POST}}</b> = Last Post To Forum<br>
	<b>{{RECENT_POSTS}}</b> = Display Last Five (5) Posts To Forum - <b>IMPORTANT</b>: For this tag to work you must be running WPAPI version 1.5 or higher<br>
	<b>{{NEWEST_MEMBER}}</b> = Newest Member To Join<br>
	<b>{{FORUM_LINK}}</b> = Creates Link To Main Forum Index
</div>
</p>
<p><textarea name="phpbb_stats_format" style="width: 400px; height: 400px;">$phpbb_stats_format</textarea></p>

<p>Saved Style Preview:</p>
$phpbb_stats_format

<p>Note: To display phpBB statistics on your Wordpress install see the Help Guide section.</p>

<p><input type="submit" value="Save" class="button">
</form>
EOM;
	}

	////////////////////////////////////////////////////////////////////////////
	//	Send to print out
	////////////////////////////////////////////////////////////////////////////
	cw_phpbb_stats_admin_browser($cw_phpbb_stats_html);
}

////////////////////////////////////////////////////////////////////////////
//	Print out to browser (wp)
////////////////////////////////////////////////////////////////////////////
function cw_phpbb_stats_admin_browser($cw_phpbb_stats_html) {
$cw_plugin_name='cleverwise-phpbb-statistics';
$cw_plugin_hname='Cleverwise phpBB Statistics';
print <<<EOM
<style type="text/css">
#cws-wrap {margin: 20px 20px 20px 0px;}
#cws-wrap a {text-decoration: none; color: #3991bb;}
#cws-wrap a:hover {text-decoration: underline; color: #ce570f;}
#cws-nav {padding: 5px 0px 7px 0px; margin-bottom: 10px; border-top: 1px solid #ab5c23; border-bottom: 1px solid #ab5c23;}
#cws-upd {padding: 0px; margin: 10px; background-color: #cc7c7c; color: #ffffff; -moz-border-radius: 5px; border-radius: 5px;}
#cws-resources {padding-top: 5px; margin: 30px 0px 20px 0px;  border-top: 1px solid #ab5c23; font-size: 12px; line-height: 1.9em;}
#cws-resources a:hover {text-decoration: none; background-color: #28394d; color: #ffffff;}
#cws-inner {padding: 5px;}
</style>
<div id="cws-wrap" name="cws-wrap">
<h2 style="padding: 0px; margin: 0px;">$cw_plugin_hname Options</h2>
<div style="margin: 15px; width: 90%; font-size: 10px; line-height: 1;">This plugin will load statistical information from phpBB 3.x board installs and display that information on your Wordpress powered site.  You have total control over the layout and look of the stats, where on your site it should be displayed, how often the information should be updated, and may even grab information from multiple boards (up to three) that don't even have to be on the same website or server.</div>
<div style="margin: 0px 15px 10px 15px; font-size: 12px;">&#9851; <a href="https://wordpress.org/support/view/plugin-reviews/$cw_plugin_name" target="_blank">Share your experience with $cw_plugin_hname by leaving a review!</a> (new window).</div>
<div id="cws-nav" name="cws-nav">&#10058; <a href="?page=cw-phpbb-stats">Main Panel</a> &#10058; <a href="?page=cw-phpbb-stats&cw_action=settingshelp">Help Guide</a> &#10058; <a href="?page=cw-phpbb-stats&cw_action=settingsnew">What Is New?</a></div>
<div id="cws-upd" name="cws-upd"><div id="cws-inner" name="cws-inner">WPAPI files updated! Version 1.7 and up requires a WPAPI upgrade! See the Upgrade notes in the "Help Guide" section!</div></div>
<p>$cw_phpbb_stats_html</p>
<div id="cws-resources" name="cws-resources"><i>Resources open in new windows:</i>
<p>&#10004; <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7VJ774KB9L9Z4" target="_blank">Donate - Thank You!</a>  &#10004; <a href="https://www.cyberws.com/professional-technical-consulting/" target="_blank">Professional Wordpress, PHP, Server Consulting</a><br>
&#10004;<a href="https://wordpress.org/support/plugin/$cw_plugin_name" target="_blank">Get $cw_plugin_hname Support</a>  &#10004; <a href="https://wordpress.org/support/view/plugin-reviews/$cw_plugin_name" target="_blank">Review $cw_plugin_hname</a>  &#10004; <a href="https://www.cyberws.com/cleverwise-plugins" target="_blank">See Other Cleverwise Plugins</a></p></div>
</div>
EOM;
}
