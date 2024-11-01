<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
wp_enqueue_script('jquery');

?>



<?php 
global $wpdb;
$date = date('Y-m-d G:i:s');
$prevdate = date('Y-m-d G:i:s',strtotime($date.' -1 day'));
$table_name = $wpdb->prefix."webpages_data";
$data_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name where datetime between '$prevdate' and '$date'" );
if($data_count > 0)
{

include("tally_dash.php");

//include('graph2.php');



}
else 
{
	echo "<p>No data to display..</p>";
        echo "this plugin tracks real human web users surfing your site, and web crawlers/bot like google search engine. So now that you plugin is installed, you have to either wait some, or logout of WordPress and Surf it yourself. <br/>";
        echo "if you use a full page cache like W3TC you should clear the page cache now, or wait until the old pages expire also.";

}
?>
