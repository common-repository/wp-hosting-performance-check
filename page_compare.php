<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );
wp_enqueue_script('jquery');


global $wpdb;

$options = get_option('wphpc_settings');

if ( !($options['wphpc_checkbox_enableReporting'] == 1)) {
echo "please enable reporting in settings.";
    return;
}
$table_name = $wpdb->prefix."webpages_data";

$setting_lastSendDate = 'wphpc_lastSendDate17';
if( get_option($setting_lastSendDate) == false) wphpc_task_function();

$response=get_transient("wphpc_comparison_data_v5");
if ($response===false ||is_null($response)|| strlen($response)<1500) {
    $response = get_web_page("http://logging.wpdone.com.au/list.php");
    set_transient("wphpc_comparison_data_v5",$response,10*60);
}
$hosting = unserialize($response);
if (empty($hosting) || count($hosting)<5){
    $response = get_web_page("http://logging.wpdone.com.au/list.php");
    set_transient("wphpc_comparison_data_v5",$response,10*60);
    $hosting = unserialize($response);
}

$days=30;
$country="";
if(isset($_REQUEST['days'])) {
    $days = intval($_REQUEST['days']);
}
$daysDisplay = "last ".$days;

/*
 * else
{
    $daysDisplay= "last 24 hours";
}
*/

$prevdays=0;
if(isset($_REQUEST['prevdays']))
{
    $prevdays = intval($_REQUEST['prevdays']);
    if ($prevdays <0 || $prevdays>90) {
        $prevdays=0;
    }
}


$date = date('Y-m-d G:i:s');

if($prevdays>0){
    $date = date ('Y-m-d G:i:s', strtotime ($date.' -'.$prevdays.' day'));

}
$prevdate = date ('Y-m-d G:i:s', strtotime ($date.' -'.$days.' day'));

if($prevdays>0){
    $daysDisplay = date ('Y-m-d', strtotime ($prevdate)) . " to " . date ('Y-m-d', strtotime ($date));
}
$daysDisplay.=" days";

$hostingName="";
if(isset($_REQUEST['hostingName']))
{
    $hostingName = sanitize_text_field($_REQUEST['hostingName']);
    $daysDisplay.=" vs ".$hostingName;
}

?>
    <div id="wphpc_head_container" style="position:relative;display: inline:block;height=150px; width=800px;" >
    <div id="wphpc_head_left" style="float:left; margin: 10px; width: 500px;">
<?php
echo "<h3>wp Hosting Performance check - hosting provider comparison<br /> - ".$daysDisplay."</h3></div>";

?>
        <form>
            Choose hosting provider :
<SELECT   onchange="window.open( this.options[ this.selectedIndex ].value, '_self')">
    <OPTION value=""#">select</OPTION>
    <?php
    foreach($hosting as $row  ) {
      if ( strpos($row["hostingName"],"&") == false)
      echo "<OPTION value=\"".   add_query_arg( 'hostingName2', htmlspecialchars($row["hostingName"]) )  . "\"". $isselected . "> " . $row["hostingName"] . "</option>";
    }
    ?>
</SELECT>
        </form>

        <div id="wphpc_head_right" style="float:right; margin: 10px; width: 200px;">
<?php



if(isset($_REQUEST['country']))
{
    $country = sanitize_text_field($_REQUEST['country']);
    $countryImg = esc_url(WPHPC__PLUGIN_URL."iso/".strtolower($country).".png");
    ?>
    <img src="<?php echo $countryImg; ?>" title="country" ></a>
    <?php

}
if (strlen ($country) < 2 && function_exists("geoip_detect2_get_info_from_current_ip")) {
    $geoip_record = geoip_detect2_get_info_from_current_ip($locales = array('en'), $options = array());
    $current_country= $geoip_record->country->isoCode;
    $newURL= esc_url( add_query_arg( 'country', $current_country ) );

    ?>
    <a href="<?php echo $newURL;?>"> change country to <?php echo $current_country; ?></a><br />
    <?php
} //end if country code in url and geoip exists

$nextURL= esc_url( add_query_arg( 'prevdays', ($prevdays - $days) ) );
$prevURL= esc_url( add_query_arg( 'prevdays', ($prevdays + $days) ) );
$daysinc= "24 hours";
if ($days>1){
    $daysinc=$days." days";
}
?>
    <a href="<?php echo $prevURL;?>"> < prev <?php echo $daysinc;?></a>

<?php
if ($prevdays>0){
    ?>
    <a href="<?php echo $nextURL;?>" > next <?php echo $daysinc;?> ></a>
    <?php
}
?>

    <br />
        </div>
<?php



$data_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name where datetime between '$prevdate' and '$date'" );
if($data_count > 0)
{
    $compare_sr="";
    $compare_secs="";
    $compare_var_sr="";
    $compare_var_secs="";
    $compare_php="";
    $compare_hostingTechnologyLevel="";


    $wpdone_sr="";
    $wpdone_secs="";
    $wpdone_var_sr="";
    $wpdone_var_secs="";
    $wpdone_php="";
    $wpdone_hostingTechnologyLevel="";

    foreach($hosting as $row  ) {
    if ($row['hostingName'] == $hostingName) {

        $compare_sr = $row['sr'];
        $compare_secs = $row['secs'];
        $compare_var_sr = $row['var_sr'];
        $compare_var_secs = $row['var_secs'];
        $compare_php=$row['php'];
        $compare_hostingTechnologyLevel=$row['hostingTechnologyLevel'];
    } //end if


        if ($row['hostingName'] == 'wpDone') {
            $wpdone_sr = $row['sr'];
            $wpdone_secs = $row['secs'];
            $wpdone_var_sr = $row['var_sr'];
            $wpdone_var_secs = $row['var_secs'];
            $wpdone_php=$row['php'];
            $wpdone_hostingTechnologyLevel=$row['hostingTechnologyLevel'];
        } //end if


    }// end forech
    include('comparo.php');

}
else
{
    echo "<p>No data to display..</p> <br />";
    echo "this plugin tracks real human web users surfing your site, and web crawlers/bot like google search engine. So now that you plugin is installed, you have to either wait some, or logout of WordPress and Surf it yourself. <br/>";
    echo "if you use a full page cache like W3TC you should clear the page cache now, or wait until the old pages expire also.";

}





function get_web_page($url) {
    $options = array(
        CURLOPT_RETURNTRANSFER => true,   // return web page
        CURLOPT_HEADER         => false,  // don't return headers
        CURLOPT_FOLLOWLOCATION => true,   // follow redirects
        CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
        CURLOPT_ENCODING       => "gzip",     // handle compressed
        CURLOPT_USERAGENT      => "wp Hosting Performance Check v".WPHPC_VERSION, // name of client
        CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 5,    // time-out on connect
        CURLOPT_TIMEOUT        => 5,    // time-out on response
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content  = curl_exec($ch);

    curl_close($ch);

    return $content;
}
?>
