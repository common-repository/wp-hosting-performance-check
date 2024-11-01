<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );

//$date = date('Y-m-d G:i:s');
//$prevdate = date('Y-m-d G:i:s',strtotime($date.' -1 day'));

$whereDate = " and datetime between '$prevdate' and '$date'";

include ("top10.php");
?>
