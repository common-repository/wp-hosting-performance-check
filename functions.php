<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;

function wphpc_headertop() {
    $rand = microtime(true);
global $WPHPC_WEB_PAGE_SPEED_START;

    if (isset($WEB_PAGE_SPEED_START) && $WEB_PAGE_SPEED_START > 0) {
        $rand = $WEB_PAGE_SPEED_START;
    }
    if (isset($WPHPC_WEB_PAGE_SPEED_START) && $WPHPC_WEB_PAGE_SPEED_START > 0) {
        $rand = $WPHPC_WEB_PAGE_SPEED_START;
    }

    define('wphpc_RANDOM_ID', $rand);
}

add_action('plugins_loaded', 'wphpc_headertop', 0);
//add_action('muplugins_loaded', 'wphpc_headertop', 0);
function wphpc_header_add() {

    if (!current_user_can('manage_options')) {
        ?>

        <script>
            wphpc_startTime = new Date();
        </script>
        <?php

    } //end if
}

add_action('wp_head', 'wphpc_header_add', 1, 1);
$options = get_option('wphpc_settings');

//if (in_array('wphpc_checkbox_ShowDashboardWidget', $options ))



if (
  !empty($options) &&
array_key_exists('wphpc_checkbox_ShowDashboardWidget', $options) &&
  !($options['wphpc_checkbox_ShowDashboardWidget'] == 0 )
) {
    add_action('wp_dashboard_setup', 'register_wphpc_dashboard_widget');

    function register_wphpc_dashboard_widget() {
        wp_add_dashboard_widget(
                'wphpc_dashboard_widget', 'wp Host Performance For Last 24 Hours', 'wphpc_dashboard_widget_display'
        );
    }

    function wphpc_dashboard_widget_display() {
      //print_r( get_option('wphpc_settings'));
        require_once( WPHPC__PLUGIN_DIR . 'dashboard.php' );
    }

//function wphpc_header_addnew() {
//	global $wpdb;
//
//echo '<style>#wpbody-content #dashboard-widgets .postbox-container { width:'.$wphpc_data1[0]->dashwidth.'% !important;}</style>';
//}
//add_action( 'admin_head', 'wphpc_header_addnew', 100 );
}

function wphpc_action_callback() {

    global $wpdb;
    $table_name = $wpdb->prefix . "webpages_data";
    $options = get_option('wphpc_settings');


    if (is_numeric($_REQUEST['uid']) && is_numeric($_REQUEST['eid'])) {

        $uid = floatval($_REQUEST['uid']);
        $newuid = microtime(true);
        $secs = $newuid - $uid;
        $sr = number_format($_REQUEST['eid'], 3, '.', '');
        $lastid = intval ($_REQUEST['lastid']);

        $loadsecs = number_format($secs, 3, '.', '');
        $wphpc_browserTime = floatval($_REQUEST['wphpc_browserTime']) / 1000 + $sr;

        $peak = round(memory_get_peak_usage() / 1048576, 2);
        $url = filter_var($_REQUEST['url'], FILTER_SANITIZE_URL);
//$url = base64_decode($url);
        $ip = wphpc_get_the_user_ip();
        $ip_param = sanitize_text_field($_REQUEST['hash']);
        echo "browser timing from php:" . $loadsecs . " , timing from js :" . $wphpc_browserTime;
        if (hash("crc32", $ip) != $ip_param) {
            $loadsecs = $wphpc_browserTime;
        } else {
// this is mostly to catch cached pages
            if ($loadsecs > $wphpc_browserTime * 5 + 0.5) {
                $loadsecs = $wphpc_browserTime;
            } else {
                $loadsecs = ($wphpc_browserTime + $loadsecs) / 2;
            }
        }

        $date = date('Y-m-d');
        $datetime = date('Y-m-d G:i:s');
        $useragent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_URL);
        $country = "";
        if (function_exists("geoip_detect2_get_info_from_current_ip")) {
            $geoip_record = geoip_detect2_get_info_from_current_ip($locales = array('en'), $options = array());
            $country = $geoip_record->country->isoCode;
            echo ", country:" . $country;
        } else {
            echo "'GeoIP Detection' plugin not loaded <br />";
        }


if (hash("crc32", $ip) != $ip_param) {
echo "hash miss - insert into table";
  $wpdb->query($wpdb->prepare("INSERT INTO
$table_name(`secs`,`peakmb`,`url`,`ip`,`sr`,`gdate`,`datetime` , `useragent`, `country`)
VALUES
( %f,%f,%s,%s,%f,%s,%s,%s,%s)", array(
              $loadsecs,
              $peak,
              $url,
              wphpc_get_the_user_ip(),
              $sr,
              $date,
              $datetime,
              $useragent,
              $country
                  )
  ));
 }
 else {

echo "<br />hash hit - update table - id=".$lastid.", data=".$loadsecs.",".$peak.",".$country;

   $wpdb->query($wpdb->prepare("UPDATE
   $table_name SET secs=%f,peakmb=%f, country=%s
   where
   id = %s", array(
               $loadsecs,
               $peak,
               $country,
               $lastid
                   )
   ));
 }

       // wphpc_task_function();

        if ($sr > floatval($options['wphpc_text_maxServerSeconds']) &&
                floatval($options['wphpc_text_maxServerSeconds']) > 0
        ) {
            $message = $options['wphpc_textarea_emailBody'];
            $message .= "\n " . $url . " having webpage load time " . $secs . " seconds and server response time " . $sr . " seconds.";
            wp_mail($options['wphpc_text_emailAddress'], $options['wphpc_text_emailSubject'], $message, array(
                $loadsecs,
                $peak,
                $url,
                wphpc_get_the_user_ip(),
                $sr,
                $date,
                $datetime,
                $useragent
                    )
            );
        }
        wp_die();
    }
}

add_action('wp_ajax_wphpc_action', 'wphpc_action_callback');
add_action('wp_ajax_nopriv_wphpc_action', 'wphpc_action_callback');

function wphpc_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . "webpages_data";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  secs FLOAT(10) NOT NULL,
  peakmb tinytext NOT NULL,
  url varchar(255) NOT NULL,
  ip varchar(100) DEFAULT '' NOT NULL,
  sr FLOAT(10) NOT NULL,
  gdate date DEFAULT '0000-00-00' NOT NULL,
  datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  useragent varchar(255) DEFAULT '' NOT NULL,
  country CHAR(2),
  PRIMARY KEY (`id`),
  UNIQUE KEY id (id)
) $charset_collate;";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function wphpc_uninstall() {

    global $wpdb;
    $table_name = $wpdb->prefix . "webpages_data";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "DROP TABLE $table_name";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

function wphpc_get_the_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//check ip from share internet
        $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//to check ip is pass from proxy
        $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
    } else {
        $ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
    }

    if (strpos($ip, ",") > 0) {
        $ip = substr($ip, 0, strpos($ip, ","));
    }

    $ip = filter_var($ip, FILTER_SANITIZE_URL);

//if (filter_var($ip, FILTER_VALIDATE_IP) === false){
//	$ip = "";
//	}



    return apply_filters('wpb_get_ip', $ip);
}

if (!wp_next_scheduled('wphpc_task_hook')) {
    wp_schedule_event(time(), 'daily', 'wphpc_task_hook');
}

add_action('wphpc_task_hook', 'wphpc_task_function');

function wphpc_task_function() {
    global $wpdb;
    $table_name = $wpdb->prefix . "webpages_data";

    $date = date('Y-m-d G:i:s');
    $prevdate = date('Y-m-d G:i:s', strtotime($date . ' -3 month'));

    $sql = "delete from $table_name where `gdate` < '$prevdate'";
    $wpdb->query($sql);

    $options = get_option('wphpc_settings');

    if ( $options['wphpc_checkbox_enableReporting'] == 1) {

        $setting_lastSendDate = 'wphpc_lastSendDate17'; // FIX PAGE_COMPARE !!!!!!!!!
        $lastSendDate = get_option($setting_lastSendDate, date('Y-m-d', strtotime(date('Y-m-d') . ' -3 month')));

//$lastSendDate=date('Y-m-d G:i:s', strtotime(date('Y-m-d') . ' -3 month'));

        $date = date('Y-m-d G:i:s');
        $prevdate = date('Y-m-d', strtotime($date));
     //  echo "searching between " . $lastSendDate . " and " . $prevdate;
        $mylink = $wpdb->get_results("SELECT gdate , count(*) as count, ROUND(avg(secs),3) as seconds, ROUND(avg(sr),3) as response, ROUND(STDDEV(secs),3) as stddevseconds , ROUND(STDDEV(sr),3) as stddevresponse  FROM $table_name WHERE datetime between '$lastSendDate' and '$prevdate' and secs<120 GROUP BY gdate");

        $senddata = array();

        foreach ($mylink as $datas) {
            $senddata[] = $datas;
        }
$result=wphpc_sendData($senddata);
  //      echo 'result:'.$result;
       if(
       ( !( $result==false))
       &&
       strpos($result,'insert OK')>-1
       ) {
           update_option($setting_lastSendDate, $prevdate, false);

       }
    } //end if reporting enabled.
}

function wphpc_footer_add() {

//    if (current_user_can('manage_options')) {
//        return;
//    }
    global $wpdb;
    $table_name = $wpdb->prefix . "webpages_data";


    $endtime = microtime(true);
    $srtime = $endtime - wphpc_RANDOM_ID;

    $url = sanitize_text_field($_SERVER["HTTP_HOST"]) . sanitize_text_field($_SERVER["REQUEST_URI"]);
    $ip = wphpc_get_the_user_ip();
    wp_enqueue_script('jquery');

    $date = date('Y-m-d');
    $datetime = date('Y-m-d G:i:s');
    $useragent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_URL);


    $wpdb->query($wpdb->prepare("INSERT INTO
  $table_name(  `secs`,`peakmb`, `url`,`ip`,`sr`,`gdate`,`datetime` , `useragent`)
  VALUES
  (%s,%s, %s,%s,%f,%s,%s,%s)", array(
              0,
              0,
                $url,
                wphpc_get_the_user_ip(),
                $srtime,
                $date,
                $datetime,
                $useragent
                  )
    ));
 $lastid = $wpdb->insert_id;

    echo '

<script type="text/javascript">
if (typeof jQuery == \'undefined\') {
  document.write(\'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"><\/script>\');
  }
</script>

<script type="text/javascript">


jQuery(function($) {
$(window).bind("load", function() {
var uid ="' . wphpc_RANDOM_ID . '";
var eid ="' . $srtime . '";
var lastid ="' . $lastid . '";
var hash  ="' . hash("crc32", $ip) . '";
var wphpc_now=new Date();
var wphpc_browserTime=wphpc_now - wphpc_startTime;

var ajaxurl = "' . admin_url('admin-ajax.php') . '";
var url ="' . $url . '";
  $.ajax({
        url: ajaxurl,
        data: {
            "action":"wphpc_action",
            "uid" : uid,
			"eid" : eid,
			"url" : url,
      "lastid": lastid,
			"hash" : hash,
			"wphpc_browserTime" : wphpc_browserTime
        },
		cache: false,
        success:function(data) {
            // This outputs the result of the ajax request
            //console.log(data);
			//alert(data);
			//jQuery(".site-title").html(data);
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });

});

});
</script>';
}

add_action('wp_footer', 'wphpc_footer_add');

function wphpc_do_php_shutdown_function() {
    //do your request here
}

register_shutdown_function('wphpc_do_php_shutdown_function');

function wphpc_sql_where_country($cc) {
    if (strlen($cc) < 2) {
        return "";
    } else {
        return " and country='" . $cc . "' ";
    }
}

//////////////////////////////////////////////////////

add_action('admin_footer', 'wphpc_admin_footer' ,99) ;

function wphpc_admin_footer (){

 ?>
<style>

</style>
<?php
};

function wphpc_sendData ($datain){

    $wphpc_debug_senddata=false;

$url="http://logging.wpdone.com.au/log.php";

    $options = get_option('wphpc_settings');
$postdata=array();
    $postdata['results']=$datain;
    $postdata['hostingName']=$options['wphpc_text_hostingName'] ;
     $postdata['hostingPlanType']=$options['wphpc_text_hostingPlanType'];
     $postdata['hostingPlan']= $options['wphpc_text_hostingPlanLevel'];
if (strpos(PHP_VERSION,'hhvm')===false)
      $postdata['hostingPhp']=phpversion();
    else
      $postdata['hostingPhp']="7.2";

    $userHash = get_option('wphpc_senddata_userhash' , md5(get_site_url()));
    $postdata['userHash']= $userHash;
    include_once ("technology_calc.php");
    $wphpc_tech_score ++;
    $postdata['hostingTechnologyLevel']= $wphpc_tech_score;

    set_screen_options('wphpc_senddata_userhash',$userHash,false);

$postdata=serialize($postdata);
if ($wphpc_debug_senddata==true) echo "sending:".$postdata;

    $public_key = "-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAsoU8dCVopt94lZ+2gKXC
IbtH9aT2TCKvRSTkH26LGI6fsd1SFFdI8laYMT1XNgVYPMvIthblTYpCEmUB1S9P
MnBhQr0l4IF523qGRcKd1EzdiDEqd/OgBg8RIjhEcEDdSRNSjvgEE97lF5nBsAbw
nSM6kC1mLYEHMXXWOYtv/KNPxoljAffsJT2TtFQ2D3C9tMwPWP2drl05NSVkMPQj
HzZ8miooM8LbiHbsco+ZrXAPV1vyQBYMGNkpIgPv5Om79dLL4xDBQwDa54d479RZ
qsRP3IOmKnAPersM3A0X85KZoWxPkCwcShxu8zXVpElp/xA6ZZRZd1o5gIWYmcph
FRBbpmhDv/tLw4m3RUtl6afGQ++SlIA3f9giR5jiW37KE6zYSENbC8p+WdL102tk
D7egFK2NosDYUMqy8r6BnTMUlaaw7fOm1mCv2FNlt8nogkGdVYOc01+jwWBTrOSk
XctzRysfjIHgZ7kAXGc+ruv2Zxhn/nCbg5osSO+oeyYIUR0VoMMfbKB6yNhrHYVL
3yb0mM6GJshnzG86gL6BodwzcDMgJe49Ukg08cbvovRZ0a+lN16kAugKcRMR+z0j
JAVC4r2N0bD3QbCUxqy/DEYS7oRC31JmFGUpafEVo8JAMeeVFMyuhCGmpwf79XYi
BjnupIlgauGuKl3EAZfaRCkCAwEAAQ==
-----END PUBLIC KEY-----
";




$encrypted="";
    if ($wphpc_debug_senddata==true) echo "data to send:".$postdata;

    $postdata=gzcompress($postdata,9);
   // echo PHP_EOL."# bytes to encrypt:".strlen($postdata);
//    openssl_public_encrypt($postdata, $encrypted, $public_key);

$encrypted=$postdata;
    $iv = openssl_random_pseudo_bytes(32);
//$pubkeys= openssl_get_publickey($public_key);
  //  var_dump($pubkeys);
   // openssl_seal( $postdata , $encrypted, $ekeys, $pubkeys, "AES256", $iv);
  //  openssl_seal( $postdata , $encrypted, $ekeys, $pubkeys);

//    openssl_seal($postdata, $encrypted, $ekeys, array(openssl_get_publickey($public_key)) );

    //echo PHP_EOL."# bytes after encrypt:".strlen($encrypted);


   // global $wp;
 //   $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
    curl_setopt ($ch, CURLOPT_USERAGENT,  "wp Hosting Performance Check v".WPHPC_VERSION);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_REFERER, $url);
    $send="enc=".bin2hex($encrypted);
    if ($wphpc_debug_senddata==true) echo "sennding this:".$send;
curl_setopt ($ch, CURLOPT_POSTFIELDS,$send );

curl_setopt ($ch, CURLOPT_POST, 1);
$result = curl_exec ($ch);

    if ($wphpc_debug_senddata==true) echo $result;
curl_close($ch);
return $result;


}
