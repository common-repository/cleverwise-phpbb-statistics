<?php
/**
* Plugin Name: Cleverwise phpBB Statistics
* Description: Display phpBB 3.x board statistics on your Wordpress powered site.  This plugin allows you to control the layout of the information, what information is displayed, where it should be displayed, how often it should be updated, and even allows information to be pulled from multiple installs that don't need to be located on the same website or server.
* Version: 2.2
* Author: Jeremy O'Connell
* Author URI: http://www.cyberws.com/cleverwise-plugins/
* License: GPL2 .:. http://opensource.org/licenses/GPL-2.0
*/

////////////////////////////////////////////////////////////////////////////
//	Load Cleverwise Framework Library
////////////////////////////////////////////////////////////////////////////
include_once('cwfa.php');
$cwfa_phpbb=new cwfa_phpbb;

////////////////////////////////////////////////////////////////////////////
//	Wordpress database option
////////////////////////////////////////////////////////////////////////////
Global $wpdb,$p2w_wp_option_version_txt,$p2w_wp_option,$p2w_wp_option_version_num;

$pbbs_wp_option_version_num='2.2';
$pbbs_wp_option='phpbb_stats';
$pbbs_wp_option_version_txt=$pbbs_wp_option.'_version';

////////////////////////////////////////////////////////////////////////////
//	Important Variables
////////////////////////////////////////////////////////////////////////////
//	Stats Pull URL
$cw_phpbb_stats_pull_url='%sstyles/wpapi/wpapi.stats.php?s=%s';

////////////////////////////////////////////////////////////////////////////
//	If admin panel is showing and user can manage options load menu option
////////////////////////////////////////////////////////////////////////////
if (is_admin()) {
	//	Hook admin code
	include_once("psa.php");

	//	Activation code
	register_activation_hook( __FILE__, 'cw_phpbb_stats_activate');

	//	Check installed version and if mismatch upgrade
	Global $wpdb;
	$pbbs_wp_option_db_version=get_option($pbbs_wp_option_version_txt);
	if ($pbbs_wp_option_db_version < $pbbs_wp_option_version_num) {
		update_option($pbbs_wp_option_version_txt,$pbbs_wp_option_version_num);
	}
}

////////////////////////////////////////////////////////////////////////////
//	Register shortcut to display visitor side
////////////////////////////////////////////////////////////////////////////
add_shortcode('cw_phpbb_stats', 'cw_phpbb_stats_vside');

////////////////////////////////////////////////////////////////////////////
//	Register Widget
////////////////////////////////////////////////////////////////////////////
add_action('widgets_init','cw_phpbb_stats_register_widgets');

function cw_phpbb_stats_register_widgets() {
	register_widget('cw_ps_widget');
}

////////////////////////////////////////////////////////////////////////////
//	Visitor Display
////////////////////////////////////////////////////////////////////////////
function cw_phpbb_stats_vside() {
Global $wpdb,$pbbs_wp_option;

	////////////////////////////////////////////////////////////////////////////
	//	Load data from wp db
	////////////////////////////////////////////////////////////////////////////
	$pbbs_wp_option_array=get_option($pbbs_wp_option);
	$pbbs_wp_option_array=unserialize($pbbs_wp_option_array);

	$forum_1_name=$pbbs_wp_option_array['forum_1_name'];
	$forum_1_url=$pbbs_wp_option_array['forum_1_url'];
	$forum_1_key=$pbbs_wp_option_array['forum_1_key'];
	$forum_1_refresh_ts=$pbbs_wp_option_array['forum_1_refresh_ts'];
	$forum_1_updated_ts=$pbbs_wp_option_array['forum_1_updated_ts'];
	$forum_1_stats=$pbbs_wp_option_array['forum_1_stats'];

	$forum_2_name=$pbbs_wp_option_array['forum_2_name'];
	$forum_2_url=$pbbs_wp_option_array['forum_2_url'];
	$forum_2_key=$pbbs_wp_option_array['forum_2_key'];
	$forum_2_refresh_ts=$pbbs_wp_option_array['forum_2_refresh_ts'];
	$forum_2_updated_ts=$pbbs_wp_option_array['forum_2_updated_ts'];
	$forum_2_stats=$pbbs_wp_option_array['forum_2_stats'];

	$forum_3_name=$pbbs_wp_option_array['forum_3_name'];
	$forum_3_url=$pbbs_wp_option_array['forum_3_url'];
	$forum_3_key=$pbbs_wp_option_array['forum_3_key'];
	$forum_3_refresh_ts=$pbbs_wp_option_array['forum_3_refresh_ts'];
	$forum_3_updated_ts=$pbbs_wp_option_array['forum_3_updated_ts'];
	$forum_3_stats=$pbbs_wp_option_array['forum_3_stats'];

	$phpbb_stats_format=stripslashes($pbbs_wp_option_array['phpbb_stats_format']);

	////////////////////////////////////////////////////////////////////////////
	//	Process data
	////////////////////////////////////////////////////////////////////////////
	$cw_phpbb_stats_html='';
	$phpbb_stats_process='';
	$phpbb_stats_wp_options_sv='n';
	$phpbb_stats_ctime=time();

	//	Forum One
	if ($forum_1_name) {
		//	Build forum details array
		$forum_details=array('forum_name'=>$forum_1_name,'forum_url'=>$forum_1_url,'forum_key'=>$forum_1_key,'forum_refresh_ts'=>$forum_1_refresh_ts,'forum_updated_ts'=>$forum_1_updated_ts,'forum_stats'=>$forum_1_stats);

		//	Submit data for html processing
		$phpbb_stats_process=cw_phpbb_stats_build($forum_details,$phpbb_stats_format);

		//	Grab html to display
		if (isset($phpbb_stats_process['forum_html'])) {
			$cw_phpbb_stats_html .=$phpbb_stats_process['forum_html'];
		}

		//	Check if db update is required
		if ($phpbb_stats_process['stats_update'] == 'new') {
			$phpbb_stats_wp_options_sv='y';
			$pbbs_wp_option_array['forum_1_updated_ts']=$phpbb_stats_ctime;
			$pbbs_wp_option_array['forum_1_stats']=$phpbb_stats_process['forum_stats'];
		}
	}

	//	Forum Two
	if ($forum_2_name) {
		//	Build forum details array
		$forum_details=array('forum_name'=>$forum_2_name,'forum_url'=>$forum_2_url,'forum_key'=>$forum_2_key,'forum_refresh_ts'=>$forum_2_refresh_ts,'forum_updated_ts'=>$forum_2_updated_ts,'forum_stats'=>$forum_2_stats);

		//	Submit data for html processing
		$phpbb_stats_process=cw_phpbb_stats_build($forum_details,$phpbb_stats_format);

		//	Grab html to display
		if (isset($phpbb_stats_process['forum_html'])) {
			$cw_phpbb_stats_html .=$phpbb_stats_process['forum_html'];
		}

		//	Check if db update is required
		if ($phpbb_stats_process['stats_update'] == 'new') {
			$phpbb_stats_wp_options_sv='y';
			$pbbs_wp_option_array['forum_2_updated_ts']=$phpbb_stats_ctime;
			$pbbs_wp_option_array['forum_2_stats']=$phpbb_stats_process['forum_stats'];
		}
	}

	//	Forum Three
	if ($forum_3_name) {
		//	Build forum details array
		$forum_details=array('forum_name'=>$forum_3_name,'forum_url'=>$forum_3_url,'forum_key'=>$forum_3_key,'forum_refresh_ts'=>$forum_3_refresh_ts,'forum_updated_ts'=>$forum_3_updated_ts,'forum_stats'=>$forum_3_stats);

		//	Submit data for html processing
		$phpbb_stats_process=cw_phpbb_stats_build($forum_details,$phpbb_stats_format);

		//	Grab html to display
		if (isset($phpbb_stats_process['forum_html'])) {
			$cw_phpbb_stats_html .=$phpbb_stats_process['forum_html'];
		}

		//	Check if db update is required
		if ($phpbb_stats_process['stats_update'] == 'new') {
			$phpbb_stats_wp_options_sv='y';
			$pbbs_wp_option_array['forum_3_updated_ts']=$phpbb_stats_ctime;
			$pbbs_wp_option_array['forum_3_stats']=$phpbb_stats_process['forum_stats'];
		}
	}

	//	Save changes to db if necessary
	if ($phpbb_stats_wp_options_sv == 'y') {
		$pbbs_wp_option_array=serialize($pbbs_wp_option_array);
		update_option($pbbs_wp_option,$pbbs_wp_option_array);
	}

	////////////////////////////////////////////////////////////////////////////
	//	Print to browser
	////////////////////////////////////////////////////////////////////////////
	return $cw_phpbb_stats_html;
}

////////////////////////////////////////////////////////////////////////////
//	Grab data from website with timeout
////////////////////////////////////////////////////////////////////////////
function cw_phpbb_stats_httpdata($cwurl) {
	$cwhttpdata='';
	if ($cwurl) {
		$strhrds=stream_context_create(array(
    			'http'=>array(
      			 	'timeout'=>'2',
       			)
   			)
		);
		$cwhttpdata=file_get_contents($cwurl,0,$strhrds);
		if (substr_count($cwhttpdata,'user_cnt') != '1') {
			$cwhttpdata='';
		}
	}
	return $cwhttpdata;
}

function cw_phpbb_stats_httpdataAA ($cwurl) {
	$ch = curl_init();
	$timeout = 2;
	curl_setopt($ch, CURLOPT_URL, $cwurl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$webdata = curl_exec($ch);
	curl_close($ch);
	return $webdata;
}

////////////////////////////////////////////////////////////////////////////
//	Build forum stat html box
////////////////////////////////////////////////////////////////////////////
function cw_phpbb_stats_build($forum_details,$phpbb_stats_format) {
Global $wpdb,$pbbs_wp_option,$cw_phpbb_stats_pull_url;

	//	Update time check
	$forum_current_time=time();
	$forum_next_update_ts=$forum_details['forum_updated_ts']+$forum_details['forum_refresh_ts'];

	//	If necessary refresh stats
	$stats_update_setting='cur';
	if ($forum_current_time > $forum_next_update_ts) {
		$new_forum_stats=sprintf($cw_phpbb_stats_pull_url,$forum_details['forum_url'],$forum_details['forum_key']);
		$new_forum_stats=cw_phpbb_stats_httpdata($new_forum_stats);
		if ($new_forum_stats) {
			$stats_update_setting='new';
			$forum_details['forum_stats']=$new_forum_stats;
		}
	}

	//	Load style
	$phpbb_stats_format_tmp=$phpbb_stats_format;

	//	Unseralize forum stats
	$forum_stats=unserialize($forum_details['forum_stats']);

	//	Replace placeholders with data
	$phpbb_stats_format_tmp=preg_replace('/{{FORUM_TITLE}}/',$forum_details['forum_name'],$phpbb_stats_format_tmp);
	$phpbb_stats_format_tmp=preg_replace('/{{MEMBER_COUNT}}/',$forum_stats['user_cnt'],$phpbb_stats_format_tmp);
	$phpbb_stats_format_tmp=preg_replace('/{{TOPIC_COUNT}}/',$forum_stats['topic_cnt'],$phpbb_stats_format_tmp);
	$phpbb_stats_format_tmp=preg_replace('/{{POST_COUNT}}/',$forum_stats['post_cnt'],$phpbb_stats_format_tmp);

	//	Build post list
	//$cw_phpbb_stats_post_url='%sviewtopic.php?f=%s&t=%s#p%s';
	//$form_status_post_url_build=sprintf($cw_phpbb_stats_post_url,$forum_details['forum_url'],$forum_stats_post[0],$forum_stats_post[2],$forum_stats_post[3]);
	
	$cw_phpbb_stats_post_url='%sviewtopic.php?p=%s#p%s';
	
	$cw_phpbb_stats_last_post='';
	$forum_stats_post_list='';
	$forum_stats_posts=$forum_stats['last_post'];
	if (isset($forum_stats_posts)) {
			$cw_phpbb_i='0';
			foreach ($forum_stats_posts as $forum_stats_post) {
				$forum_stats_post=explode('|',$forum_stats_post);
				$form_status_post_url_build=sprintf($cw_phpbb_stats_post_url,$forum_details['forum_url'],$forum_stats_post[3],$forum_stats_post[3]);
				$forum_stats_post_list .='<li><a href="'.$form_status_post_url_build.'">'.$forum_stats_post[4].'</a> - '.$forum_stats_post[1].'</li>';
				if ($cw_phpbb_i === '0') {
						$cw_phpbb_stats_last_post='<a href="'.$form_status_post_url_build.'">'.$forum_stats_post[4].'</a>';
				}
				$cw_phpbb_i++;
			}
	}
	$phpbb_stats_format_tmp=preg_replace('/{{LAST_POST}}/',$cw_phpbb_stats_last_post,$phpbb_stats_format_tmp);
	$phpbb_stats_format_tmp=preg_replace('/{{RECENT_POSTS}}/',$forum_stats_post_list,$phpbb_stats_format_tmp);

	//	Build last member signup	
	$forum_stats_last_mem=explode('|',$forum_stats['last_mem']);
	$cw_phpbb_stats_last_mem_url='%smemberlist.php?mode=viewprofile&u=%s';
	$cw_phpbb_stats_last_mem_url=sprintf($cw_phpbb_stats_last_mem_url,$forum_details['forum_url'],$forum_stats_last_mem[0]);
	$cw_phpbb_stats_last_mem_url='<a href="'.$cw_phpbb_stats_last_mem_url.'">'.$forum_stats_last_mem[1].'</a>';
	$phpbb_stats_format_tmp=preg_replace('/{{NEWEST_MEMBER}}/',$cw_phpbb_stats_last_mem_url,$phpbb_stats_format_tmp);

	//	Build forum link
	$forum_link='<a href="%s">%s</a>';
	$forum_link_build=sprintf($forum_link,$forum_details['forum_url'],$forum_details['forum_name']);
	$phpbb_stats_format_tmp=preg_replace('/{{FORUM_LINK}}/',$forum_link_build,$phpbb_stats_format_tmp);

	//	Set information variables
	$forum_details['forum_html']=$phpbb_stats_format_tmp;
	$forum_details['stats_update']=$stats_update_setting;

	//	Unset variables
	unset($forum_stats_last_post);
	unset($forum_stats_last_mem);
	unset($phpbb_stats_format_tmp);

	//	Return data
	return($forum_details);
}

////////////////////////////////////////////////////////////////////////////
//	Widget Logic
////////////////////////////////////////////////////////////////////////////
class cw_ps_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		/* Widget settings. */
		parent::__construct(
			'cw_ps_widget', // Base ID
			__('phpBB Statistics', 'text_domain'), // Name
			array( 'description'=>__('This will display your phpBB statistics.', 'text_domain'),) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args,$instance) {
		$cw_phpbb_stats_widget_html=cw_phpbb_stats_vside();
		print $cw_phpbb_stats_widget_html;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form($instance) {
		// outputs the options form on admin
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update($new_instance, $old_instance) {
		// processes widget options to be saved
	}
}
