<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );
wp_enqueue_script('jquery');

?>


<?php 
global $wpdb;


$table_name = $wpdb->prefix."webpages_data";


$days=1;
$country="";
if(isset($_REQUEST['days']))
	{
	$days = intval($_REQUEST['days']);
	$daysDisplay = "last ".$days;
	}
	else
	{
	$daysDisplay= "last 24 hours";
	}
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


?>
<div id="wphpc_head_container" style="position:relative;display: inline:block;height=150px; width=800px;" >
<div id="wphpc_head_left" style="float:left; margin: 10px; width: 500px;">
<?php
	echo "<h3>wp Hosting Performance check - ".$daysDisplay."</h3></div>";
?><div id="wphpc_head_right" style="float:right; margin: 10px; width: 200px;">
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

<?php


	echo "</div>";


$data_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name where datetime between '$prevdate' and '$date'" );
if($data_count > 0)
{


include('tally.php');
if(isset($_REQUEST['days']))
	{
	include ('graph1_beta.php');
	}
else
	{
	include ('graph1_beta.php');
	}

include('slowurls2.php');

}
else 
{
	echo "<p>No data to display..</p> <br />";
	echo "this plugin tracks real human web users surfing your site, and web crawlers/bot like google search engine. So now that you plugin is installed, you have to either wait some, or logout of WordPress and Surf it yourself. <br/>";
	echo "if you use a full page cache like W3TC you should clear the page cache now, or wait until the old pages expire also.";

}

