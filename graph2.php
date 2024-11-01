<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//include_once("bots.php");

$mylink = $wpdb->get_results("SELECT DATE(datetime ) as daynum , HOUR(datetime) AS hour, avg(secs) as seconds, avg(sr) as response FROM $table_name WHERE secs>0 and datetime between '$prevdate' and '$date' and secs<120 " .wphpc_sql_where_country($country) . " GROUP BY daynum,hour "); //ORDER BY HOUR(datetime) ASC");
?>
<br>

<canvas id="cvs_graph2"  width="1200" height="250" >
    [No canvas support]
</canvas>
</ br>
</ br>
<canvas id="cvs_graph2b" width="1200" height="250" >
    [No canvas support]
</canvas>

<?php

$vals1="";
$vals2="";
$labels1="";

	foreach($mylink as $datas)
	{
//		$pdata = $wpdb->get_results("SELECT HOUR(datetime) AS hour,AVG(sr) as response,url,AVG(secs) as seconds FROM $table_name WHERE HOUR(datetime) = '".$datas->hour."' and secs<20");

		$responses = $datas->response;
		$response = number_format($responses, 2, '.', '');
		$secondss = $datas->seconds;
		$seconds = number_format($secondss, 2, '.', '');
		$hours = intval( $datas->hour);
		$am="am";
		if ($hours == 12){
				$am="pm";
				}
                if ($hours == 0){
                                $hours=12;
                                }

		if ($hours > 12){
			$hours =$hours -12;
			$am="pm";
			}

$labels1.="'".date('M d',strtotime($datas->daynum))."\\n".$hours." ".$am."',";
$vals1.=$response.",";
$vals2.=$seconds.",";


	}
	?>



<script>


new RGraph.Bar({
        id: 'cvs_graph2',
        data: [<?php echo $vals1; ?>],
        options: {
            labels: [<?php echo $labels1; ?>],
            shadow: false,
colors: ['Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
            strokestyle: 'rgba(0,0,0,0)',
            textSize: 10,
            title: 'average seconds for web Server to respond',
            numyticks: 5,
            noxaxis: false,
            scaleDecimals : 1,
            gutterLeft: 50
        }
    }).draw();


new RGraph.Bar({
        id: 'cvs_graph2b',
        data: [<?php echo $vals2; ?>],
        options: {
            labels: [<?php echo $labels1; ?>],
            shadow: false,
colors: ['Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
            strokestyle: 'rgba(0,0,0,0)',
            textSize: 10,
            title: 'average seconds for End user browser to finish loading',
            numyticks: 5,
            noxaxis: false,
            scaleDecimals : 0,
            gutterLeft: 50
        }
    }).draw();



</script>
