<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_enqueue_script('jquery');
?>

<h2>Webpage Speed Tracker</h2>


<?php 
global $wpdb;


$table_name = $wpdb->prefix."webpages_data";
if(isset($_REQUEST['days']))
{
$days = $_REQUEST['days'];
$date = date('Y-m-d G:i:s');
$prevdate = date('Y-m-d G:i:s',strtotime($date.' -'.$days.' day'));
$data_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name where datetime between '$prevdate' and '$date'" );
if($data_count > 0)
{

include("tally.php");

include('graph1.php');

}
else 
{
	echo "<p>No data to display..</p>";
}
}
else
{
$date = date('Y-m-d G:i:s');
$prevdate = date('Y-m-d G:i:s',strtotime($date.' -1 day'));

// select SUM(IF (secs<1,1,0)) as sec0, SUM(IF (secs>1,IF (secs<2,1,0),0)) as sec1, SUM(IF (secs>2,1,0)) as sec2, AVG(secs) as avg, COUNT(secs) as cnt from wp_webpages_data;
$data_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name where datetime between '$prevdate' and '$date'" );
if($data_count > 0)
{


include("tally.php");

include ('graph2.php');

}
else 
{
	echo "<p>No data to display..</p>";
}
}
?>
