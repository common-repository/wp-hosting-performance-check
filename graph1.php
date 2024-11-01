<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once("bots.php");

$mylink = $wpdb->get_results("SELECT DATE(datetime) AS dates,AVG(sr) as response,AVG(secs) as seconds FROM $table_name WHERE sr>0 and datetime between '$prevdate' and '$date' and secs<120 " . wphpc_get_useragent_exclusion() . wphpc_sql_where_country($country) . " GROUP BY dates"); // ORDER BY DAY(datetime) ASC");
?>
<br>
<canvas id="cvs_graph1"  width="1200" height="250" >
    [No canvas support]
</canvas>
<br>
<br>
<canvas id="cvs_graph1b" width="1200" height="250" >
    [No canvas support]
</canvas>

	<?php

$vals1="";
$labels1="";
$vals2="";

	foreach($mylink as $datas)
	{

//		$pdata = $wpdb->get_results("SELECT DAY(datetime) AS day,DATE(datetime) AS dates,YEAR(datetime) AS year,AVG(sr) as response,url,AVG(secs) as seconds FROM $table_name WHERE DAY(datetime) = '".$datas->day."'");
		$dates = $datas->dates;
		$newdate = date('M',strtotime($dates))."\\n".date('d',strtotime($dates)) ;
		$responses = $datas->response;
		$response = number_format($responses, 2, '.', '');
		$secondss = $datas->seconds;
		$seconds = number_format($secondss, 2, '.', '');
$vals1.=$response.",";
$vals2.=$seconds.",";
$labels1.="'".$newdate."',";

	}

$vals1=rtrim($vals1,",");
$vals2=rtrim($vals2,",");
$labels1=rtrim($labels1,",");

?>

<script>


new RGraph.Bar({
        id: 'cvs_graph1',
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
        id: 'cvs_graph1b',
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
