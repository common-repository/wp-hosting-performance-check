<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );
wp_enqueue_script('jquery');
include("bots.php");
global $wpdb;


$table_name = $wpdb->prefix."webpages_data";


$days=1;
$country="";
if(isset($_REQUEST['days']))
	{
	$days = intval($_REQUEST['days']);
	}
	$daysDisplay = "last ".$days." days";

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
<?php
	echo "<h3>wp Hosting Performance check - Bot Analysis - ".$daysDisplay."</h3><br /> ";
	$wphpc_botpage_durations=array("1","7","30");

?>



Days<SELECT   id="days"

onchange="window.open( 'admin.php?page=wphpc_page_bots'
+'&days='+document.getElementById('days').value
  , '_self')"
>
<?php

foreach($wphpc_botpage_durations as $row  ) {
?><OPTION value="<?php
echo htmlspecialchars($row);
echo '"';
if ($row==$days)
	echo ' SELECTED ';

echo'> ' . htmlspecialchars($row) ;
echo '</option>';

}
 ?>
</SELECT>

<?php


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
<h4>What is this all about?</h4>
No matter what hosting platform you have, there is a limit to CPU resources.<br />
Shared hosting often will ban your account if you use too many resources. Some hosting services will simply slow your site down.<br />
Some will ask you to upgrade to the next plan. <br />
If you have a VPS, you've sort of agreed to some amount of CPU resources, and you don't want to squander them either.<br />
You'll eventually be asked to upgrade to the next plan. And you'll sit there and think ' hey, my hits and  users aren't that big'.<br />
Enter the bots - the good the bad and the ugly. <br />
You need some bots like google and bing - many others you can do without. They all take up some resources.<br />
The below is a measure of CPU seconds that each group is using in the most recent 30 days.<br/><br/>
<?php


$bot_array=wphpc_get_bot_array();
$BotResults=array();


$botSums = $wpdb->get_results( "SELECT useragent,sum(sr) as srsum FROM $table_name where secs=0 and  (datetime between '$prevdate' and '$date') group by useragent" );



foreach($bot_array as &$bot)
{
//	$data_count = $wpdb->get_var( "SELECT sum(sr) FROM $table_name where useragent LIKE '%".$bot."%' and (datetime between '$prevdate' and '$date')" );
//echo $bot." = ".$data_count."<br />";
//echo "SELECT sum(sr) FROM $table_name where useragent LIKE '%".$bot."%' and (datetime between '$prevdate' and '$date')"."<br/>";
$data_count=0;
foreach ($botSums as $botRow) {
            if( strpos ($botRow->useragent,$bot)!== FALSE ){
							$data_count+=$botRow->srsum;
						}

    }
if ($data_count>0){
	$BotResults[$bot]=$data_count;
}
}//end foreach

arsort($BotResults);

$otherbotstotal=0;

while (count($BotResults)>8){

	$removed=array_pop($BotResults);

	$otherbotstotal=$otherbotstotal+$removed;
}

if ($otherbotstotal>0){
	$BotResults['Other Bots']=$otherbotstotal;
}


$data_count = $wpdb->get_var( "SELECT sum(sr) FROM $table_name where secs=0 and (datetime between '$prevdate' and '$date') " .wphpc_get_useragent_exclusion());
if ($data_count >0){
$BotResults['Unidentified Bots']=$data_count;
}

$data_count = $wpdb->get_var( "SELECT sum(sr) FROM $table_name where secs>0 and (datetime between '$prevdate' and '$date') " .wphpc_get_useragent_exclusion());
if ($data_count >0){
$BotResults['Humans']=$data_count;
}

$pie_values="";
$pie_keys="";

	foreach ($BotResults as $key => $value) {
//		echo $key." = ".$value."<br />";
$pie_values=$pie_values.$value.",";
$pie_keys=$pie_keys."'".$key."'".",";
	}
$pie_values=	rtrim($pie_values,",");
$pie_keys=	rtrim($pie_keys,",");

//print_r($BotResults);


?>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:300;border-width: 1px;border-color: #729ea5;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#acc8cc;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;}


.bots_page {
  overflow:hidden;
}

.bots_page div {
   min-height: 450px;
   padding: 10px;
}
#chart-container {
  float:left;
  width:755px;
}
#botTable {
	background-color: white;
  overflow:hidden;
  min-height:170px;
	width:200px;
}
@media screen and (max-width: 755px) {
   #botTable {
    float: none;
    margin-right:0;
    width:auto;
  }

</style>


<div  class="bots_page">

<div id="chart-container" ></div>

<div  id="botTable">



	<table class="tftable" border="1" >
	<tr><th>Bot</th><th>CPU seconds</th></tr>
	<?php 	foreach ($BotResults as $key => $value) { ?>

	<tr><td><?php  echo $key; ?></td><td><?php  echo intval($value*10)/10; ?></td></tr>
	<?php } ?>
	</table>


</div>
</div>

<script>
    new RGraph.SVG.Pie({
    id: 'chart-container',
            data: [<?php echo $pie_values;?>],
        options: {
            labels: [<?php echo $pie_keys;?>],
            shadow: true,
                textSize: 8,


            colors: [
							"#FF0000", "#00FF00", "#0000FF", "#FFFF00", "#FF00FF", "#00FFFF", "#000000",
        "#800000", "#008000", "#000080", "#808000", "#800080", "#008080", "#808080",
        "#C00000", "#00C000", "#0000C0", "#C0C000", "#C000C0", "#00C0C0", "#C0C0C0",
        "#400000", "#004000", "#000040", "#404000", "#400040", "#004040", "#404040",
        "#200000", "#002000", "#000020", "#202000", "#200020", "#002020", "#202020",
        "#600000", "#006000", "#000060", "#606000", "#600060", "#006060", "#606060",
        "#A00000", "#00A000", "#0000A0", "#A0A000", "#A000A0", "#00A0A0", "#A0A0A0",
        "#E00000", "#00E000", "#0000E0", "#E0E000", "#E000E0", "#00E0E0", "#E0E0E0"

            ]
        }
    }).draw();
</script>
