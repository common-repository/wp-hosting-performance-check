<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @package wp Hosting Performance Check
 * @version 1.0
 */
/*
Plugin Name: wp Hosting Performance Check
Description: This plugin is used to graph your wp Hosting Performance
Author: Scott Farrell
Version: 2.18.8
Author URI: https://www.wpdone.com.au
License:     GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: https://www.wpdone.com.au/wp-hosting-performance-check/

wp Hosting Performance Check is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

wp Hosting Performance Check is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

*/


/********
Global
********/
include_once ('vendor/persist-admin-notices-dismissal.php');

define( 'WPHPC_VERSION', '2.18.8' );
define( 'WPHPC__MINIMUM_WP_VERSION', '3.2' );
define( 'WPHPC__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPHPC__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPHPC_DELETE_LIMIT', 100000 );
global $WPHPC_WEB_PAGE_SPEED_START;
$WPHPC_WEB_PAGE_SPEED_START=microtime(true);

register_activation_hook( __FILE__, 'wphpc_install' );
register_uninstall_hook( __FILE__, 'wphpc_uninstall' );

require_once( WPHPC__PLUGIN_DIR . 'functions.php' );

add_action('parse_request', 'wphpc_custom_url_handler');

function wphpc_custom_url_handler() {
   if(strtok($_SERVER["REQUEST_URI"],'?') == '/wphpc_loadtest_keyview') {
		 $wphpc_loadtest_key = get_option("wphpc_loadtest_key", bin2hex(openssl_random_pseudo_bytes(16)));

		 echo $wphpc_loadtest_key;
		 update_option("wphpc_loadtest_key",bin2hex(openssl_random_pseudo_bytes(16)),false);

      exit();
   }
}




//admin pages
function wphpc_page(){
	//ob_start();
	require_once( WPHPC__PLUGIN_DIR . 'page.php' );
}

function wphpc_page2(){
	//ob_start();
	$_REQUEST['days']="7";
	require_once( WPHPC__PLUGIN_DIR . 'page.php' );
}
function wphpc_page3(){
	//ob_start();
	$_REQUEST['days']="30";
	require_once( WPHPC__PLUGIN_DIR . 'page.php' );
}
function wphpc_page4(){
	//ob_start();
	require_once( WPHPC__PLUGIN_DIR . 'slowurls.php' );
}

function wphpc_page_bots(){
	//ob_start();
	require_once( WPHPC__PLUGIN_DIR . 'bots_page.php' );
}


function wphpc_page5(){
        //ob_start();
        $_REQUEST['days']="30";

        require_once( WPHPC__PLUGIN_DIR . 'page_beta.php' );
}

function wphpc_page_compare(){
    require_once( WPHPC__PLUGIN_DIR . 'page_compare.php' );
}


function wphpc_country_page(){

    require_once( WPHPC__PLUGIN_DIR . 'country.php' );
}
function wphpc_sysinfo(){

	require_once( WPHPC__PLUGIN_DIR . 'sysinfo.php' );
}

function wphpc_loadtest_page(){

	require_once( WPHPC__PLUGIN_DIR . 'loadtest.php' );
}
//admin Tab

function wphpc_tab(){

add_options_page('web page','wp Hosting Performance Check','manage_options','wphpc_graphs','wphpc_page');

}

//add_action('admin_menu','wphpc_tab');




function wphpc_admin_add_css_and_js_files(){

        //wp_enqueue_script('your-script-name', $this->urlpath  . '/your-script-filename.js', array('jquery'), '1.2.3', true);

//wp_enqueue_style( 'style', plugins_url('/css/style.css', __FILE__), false, '1.0.0', 'all');
wp_enqueue_script( 'wphpc_custom_script1', plugins_url( '/libraries/RGraph.common.core.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script2', plugins_url( '/libraries/RGraph.hbar.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script3', plugins_url( '/libraries/RGraph.bar.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script4', plugins_url( '/libraries/RGraph.odo.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script5', plugins_url( '/libraries/RGraph.gauge.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_style( 'wphpc_custom_script6', plugins_url( '/css/style.css', __FILE__), array(), '4.66' );

wp_enqueue_script( 'wphpc_custom_script7', plugins_url( '/libraries/RGraph.common.dynamic.js', __FILE__), array('jquery'), '4.66' );

wp_enqueue_script( 'wphpc_custom_script8', plugins_url( '/libraries/RGraph.common.tooltips.js', __FILE__), array('jquery'), '4.66' );

wp_enqueue_script( 'wphpc_custom_script9', plugins_url( '/libraries/RGraph.line.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script10', plugins_url( '/libraries/RGraph.svg.common.core.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script11', plugins_url( '/libraries/RGraph.svg.pie.js', __FILE__), array('jquery'), '4.66' );
wp_enqueue_script( 'wphpc_custom_script12', plugins_url( '/libraries/RGraph.svg.common.key.js', __FILE__), array('jquery'), '4.66' );





    }

function wphpc_add_css_and_js_files(){

wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', "wphpc_add_css_and_js_files");

add_action( 'admin_enqueue_scripts', 'wphpc_admin_add_css_and_js_files' );


add_action( 'admin_menu', 'wphpc_admin_menu' );


function wphpc_admin_menu() {
	add_menu_page( 'Graphs', 'Hosting Check', 'manage_options', 'wphpc_graphs', 'wphpc_page', 'dashicons-chart-line' );
	add_submenu_page( 'wphpc_graphs', 'Last 7 Days Graph', 'Last 7 Days Graph', 'manage_options', '7daysgraph', 'wphpc_page2' );
	add_submenu_page( 'wphpc_graphs', 'Last 30 Days Graph', 'Last 30 Days Graph', 'manage_options', '30daysgraph', 'wphpc_page3' );
	add_submenu_page( 'wphpc_graphs', '10 slow Urls', 'Top 10 Slow Urls', 'manage_options', 'wphpc_slowurls', 'wphpc_page4' );
  add_submenu_page( 'wphpc_graphs', 'Bot Analysis', 'Bot Analysis', 'manage_options', 'wphpc_page_bots', 'wphpc_page_bots' );

    add_submenu_page( 'wphpc_graphs', 'Hosting Compare', 'Hosting Compare', 'manage_options', 'wphpc_compare', 'wphpc_page_compare' );
	add_submenu_page( 'wphpc_graphs', 'Sysinfo', 'Sysinfo', 'manage_options', 'wphpc_sysinfo', 'wphpc_sysinfo' );
	add_submenu_page( 'wphpc_graphs', 'Load Test', 'Load Test', 'manage_options', 'wphpc_loadtest_page', 'wphpc_loadtest_page' );
    add_submenu_page( 'wphpc_graphs', 'Settings', 'Settings', 'manage_options', 'wphpc_settings', 'wphpc_options_page' );

    add_submenu_page( 'wphpc_graphs', 'country', 'country', 'manage_options', 'wphpc_country', 'wphpc_country_page' );

//        add_submenu_page( 'wphpc_graphs', 'beta graphs', 'beta graphs', 'manage_options', 'wphpc_betagraph', 'wphpc_page5' );



}


add_action( 'admin_init', 'wphpc_settings_init' );

function wphpc_settings_validate( $input ) {
	if($input['wphpc_checkbox_enableReporting']==1) {

		$toTest = array('wphpc_text_hostingName', 'wphpc_text_hostingPlanType', 'wphpc_text_hostingPlanLevel');
		foreach ($toTest as $testField) {
		}
		if (strlen($input['wphpc_text_hostingName']) < 5){
return false;
		}
	}
	return $input;
}


function wphpc_settings_init(  ) {

	register_setting( 'pluginPage', 'wphpc_settings','wphpc_settings_validate' );

	add_settings_section(
		'wphpc_pluginPage_section',
		__( 'Settings', 'wordpress' ),
		'wphpc_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'wphpc_checkbox_ShowDashboardWidget',
		__( 'Show dashboard widget', 'wordpress' ),
		'wphpc_checkbox_ShowDashboardWidget_render',
		'pluginPage',
		'wphpc_pluginPage_section'
	);

    add_settings_section(
        'wphpc_pluginPage_section_tracking',
        __( 'Settings - benchmarking', 'wordpress' ),
        'wphpc_settings_section_callback_tracking',
        'pluginPage'
    );


    add_settings_field(
		'wphpc_checkbox_enableReporting',
		__( 'enable reporting of anonymous stats', 'wordpress' ),
		'wphpc_checkbox_enableReporting_render',
		'pluginPage',
		'wphpc_pluginPage_section_tracking'
	);



	add_settings_field(
		'wphpc_text_hostingName',
		__( 'hosting company shortname', 'wordpress' ),
		'wphpc_text_hostingName_render',
		'pluginPage',
		'wphpc_pluginPage_section_tracking'
	);

	add_settings_field(
		'wphpc_text_hostingPlanType',
		__( 'hosting plan type', 'wordpress' ),
		'wphpc_text_hostingPlanType_render',
		'pluginPage',
		'wphpc_pluginPage_section_tracking'
	);

	add_settings_field(
		'wphpc_text_hostingPlanLevel',
		__( 'hosting plan level', 'wordpress' ),
		'wphpc_text_hostingPlanLevel_render',
		'pluginPage',
		'wphpc_pluginPage_section_tracking'
	);







	add_settings_field(
		'wphpc_text_maxServerSeconds',
		__( 'Maximum seconds for email alert', 'wordpress' ),
		'wphpc_text_maxServerSeconds_render',
		'pluginPage',
		'wphpc_pluginPage_section'
	);

	add_settings_field(
		'wphpc_text_emailAddress',
		__( 'Alert email address', 'wordpress' ),
		'wphpc_text_emailAddress_render',
		'pluginPage',
		'wphpc_pluginPage_section'
	);

	add_settings_field(
		'wphpc_text_emailSubject',
		__( 'Alert email subject', 'wordpress' ),
		'wphpc_text_emailSubject_render',
		'pluginPage',
		'wphpc_pluginPage_section'
	);


	add_settings_field(
		'wphpc_textarea_emailBody',
		__( 'Alert email body', 'wordpress' ),
		'wphpc_textarea_emailBody_render',
		'pluginPage',
		'wphpc_pluginPage_section'
	);


}

function wphpc_checkbox_ShowDashboardWidget_render(  ) {

	$options = get_option( 'wphpc_settings' );
$checkedDash=1;
	if (!empty($options) &&
		array_key_exists('wphpc_checkbox_ShowDashboardWidget', $options))
				$checkedDash=$options['wphpc_checkbox_ShowDashboardWidget'];
	if ( !
		($checkedDash==1 || $checkedDash==0 )
	    ) {
		$checkedDash=1;
		}
	?>
	<input type='checkbox' name='wphpc_settings[wphpc_checkbox_ShowDashboardWidget]' <?php checked( $checkedDash, 1 ); ?> value='1'>
	<?php

}


function wphpc_checkbox_enableReporting_render(  ) {

	$options = get_option( 'wphpc_settings' );
	$checkedDash=$options['wphpc_checkbox_enableReporting'];
	?>
	<input type='checkbox' name='wphpc_settings[wphpc_checkbox_enableReporting]' <?php checked( $checkedDash, 1 ); ?> value='1'>
	<?php

}




function wphpc_text_hostingName_render(  ) {

$hostingCompanies=['wpDone','BlueHost','A2 Hosting','Cloudways','Conetix','DreamHost','FlyWHeel','GoDaddy','Incendia Web Works','Kinsta','LightningBase','LiquidWeb','MediaTemple','Pagely','Pantheon','Pressable','Pressed.net','Pressidium','Pressjitsu','PressLabs','SiteGround','WordPress.com','WPEngine'];

        wphpc_render_htmltextoption($hostingCompanies,'wphpc_text_hostingName');
    }

function wphpc_text_hostingName_render_list(  ) {

    $hostingCompanies=['wpDone','BlueHost','A2 Hosting','Cloudways','Conetix','DreamHost','FlyWHeel','GoDaddy','Incendia Web Works','Kinsta','LightningBase','LiquidWeb','MediaTemple','Pagely','Pantheon','Pressable','Pressed.net','Pressidium','Pressjitsu','PressLabs','SiteGround','WordPress.com','WPEngine'];
         wphpc_render_htmltextoption($hostingCompanies,'wphpc_text_hostingName');

}


        function wphpc_text_hostingPlanType_render(  ) {

        $hostingPlans=['standard','shared','reseller','dedicated','cloud','enterprise','VPS managed','VPS self managed','professional','premium','business','plus','basic','deluxe','ultimate','developer','entry','business plus'];

            wphpc_render_htmltextoption ($hostingPlans,'wphpc_text_hostingPlanType');

            }

            function wphpc_text_hostingPlanLevel_render(  ) {

            $hostingPlans=['N/A','under 1gb','1gb','2gb','4gb','8gb','16gb','32 gb','over 32gb','1 core','2 cores','4 cores','8 cores','16 cores','over 16 cores'];

                wphpc_render_htmltextoption($hostingPlans, 'wphpc_text_hostingPlanLevel' );
echo "<br />we are mostly using the Mb of RAM as an estimate of the plan size.<br />";
                }


function wphpc_render_htmlselect ( $arrayTestList, $textSelected){


    foreach ($arrayTestList  as &$hostname) {
        $hostname=esc_html($hostname);
        ?>
        <option value='<?php echo $hostname; ?>' <?php selected( $textSelected, $hostname ); ?>><?php echo $hostname; ?></option>
        <?php
    } //end foreach

    echo "</select>";

}


                function wphpc_render_htmltextoption ( $arrayTestList, $fieldName){
        $options = get_option( 'wphpc_settings' );

                    ?>
                    <input list="<?php echo $fieldName; ?>" name="wphpc_settings[<?php echo $fieldName; ?>]" value="<?php echo $options[$fieldName] ;?>">

                <datalist id='<?php echo $fieldName; ?>'>
                    <?php
                    foreach ($arrayTestList  as &$hostname) {
                        $hostname=esc_html($hostname);
                        ?>
                        <option><?php echo $hostname; ?></option>
                        <?php
                    } //end foreach

                    echo "</datalist>";

                }

function wphpc_text_maxServerSeconds_render(  ) {

	$options = get_option( 'wphpc_settings' );
	?>
	<input type='text' name='wphpc_settings[wphpc_text_maxServerSeconds]' value='<?php echo $options['wphpc_text_maxServerSeconds']; ?>'>
	<?php

}


function wphpc_text_emailAddress_render(  ) {

	$options = get_option( 'wphpc_settings' );
	?>
	<input type='text' name='wphpc_settings[wphpc_text_emailAddress]' value='<?php echo $options['wphpc_text_emailAddress']; ?>'>
	<?php

}


function wphpc_text_emailSubject_render(  ) {

	$options = get_option( 'wphpc_settings' );
	?>
	<input type='text' name='wphpc_settings[wphpc_text_emailSubject]' value='<?php echo $options['wphpc_text_emailSubject']; ?>'>
	<?php

}



function wphpc_textarea_emailBody_render(  ) {

	$options = get_option( 'wphpc_settings' );
	?>
	<textarea cols='40' rows='5' name='wphpc_settings[wphpc_textarea_emailBody]'>
		<?php echo $options['wphpc_textarea_emailBody']; ?>
 	</textarea>
	<?php

}


function wphpc_settings_section_callback(  ) {

	echo __( 'please customize your settings', 'wordpress' );

}

                    function wphpc_settings_section_callback_tracking(  ) {

                        echo __( 'If you enable these settings, we will centrally collect your anonymised stats. You\'ll be able to benchmark your hosting against other hosting providers.<br />
The plugin will transmit your daily summary each day, the data transmitted is small (a few kilo bytes).<br />Please try to choose from the dropdown menus.', 'wordpress' );


                    }


function wphpc_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>wp Hosting Performance Check</h2>


		<?php
		require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );
//echo ("current settings :".print_r (get_option( 'wphpc_settings' )));
        if (function_exists("geoip_detect2_get_info_from_current_ip")) {
            $geoip_record = geoip_detect2_get_info_from_current_ip($locales = array('en'), $options = array());
            $country = $geoip_record->country->isoCode;
            echo "current country:" . $country." , country tracking is enabled. <br />";
        } else {
            echo "current country : 'GeoIP Detection' plugin not loaded. The plugin won't be able to track country of users. <br />";
        }

		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}



add_action( 'admin_init', array( 'wphpc_PAnD', 'init' )  );



                    function wphpc_admin_notice__success() {
                        if ( ! wphpc_PAnD::is_admin_notice_active( 'wphpc-notice-forever' ) ) {
                            return;
                        }
                        ?>
                        <div data-dismissible="wphpc-notice-forever" class="updated notice notice-success is-dismissible">
                            <p><?php _e( 'wp Hosting Performance Check new feature! There is an ability to benchmark your hosting against other hosting providers. You need to enable it in <a href="admin.php?page=wphpc_settings">settings</a>.' , 'sample-text-domain' ); ?></p>
                        </div>
                        <?php
                    }

                        add_action('admin_notices', 'wphpc_admin_notice__success');

                    function wphpc_admin_notice__country() {
                        if ( ! wphpc_PAnD::is_admin_notice_active( 'wphpc-notice-country-forever' ) ) {
                            return;
                        }
                        ?>
                        <div  data-dismissible="wphpc-notice-country-forever" class="updated notice notice-success is-dismissible">
                            <p><?php _e( 'wp Hosting Performance Check new feature! There is an ability to track the country of web site users, and display graphs of countries. More info about <a href="admin.php?page=wphpc_country">country</a>..' , 'sample-text-domain' ); ?></p>
                        </div>
                        <?php
                    }

                    if ( !(function_exists("geoip_detect2_get_info_from_current_ip")) ) {
                        add_action('admin_notices', 'wphpc_admin_notice__country');
                    }

                    function wphpc_admin_notice_loadtest() {
                        if ( ! wphpc_PAnD::is_admin_notice_active( 'wphpc-notice-loadtest-forever' ) ) {
                            return;
                        }
                        ?>
                        <div data-dismissible="wphpc-notice-loadtest-forever" class="updated notice notice-success is-dismissible">
                            <p><?php _e( 'wp Hosting Performance Check new feature! You can now load test your WordPress website - see how many users it can take <a href="admin.php?page=wphpc_loadtest_page">Load Test</a>.' , 'sample-text-domain' ); ?></p>
                        </div>
                        <?php
                    }

                    add_action('admin_notices', 'wphpc_admin_notice_loadtest');

                    ?>
