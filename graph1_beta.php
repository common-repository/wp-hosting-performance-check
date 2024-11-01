<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once("bots.php");

$mylink = $wpdb->get_results("SELECT DATE(datetime) AS dates,AVG(sr) as response,AVG(secs) as seconds, STD(sr) as stdsr, STD(secs) as stdsecs FROM $table_name WHERE datetime between '$prevdate' and '$date' and secs<120 " . wphpc_get_useragent_exclusion() . wphpc_sql_where_country($country) . " GROUP BY dates"); // ORDER BY DAY(datetime) ASC");
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
$stddev="";
$stddev2="";
$vals2="";

	foreach($mylink as $datas)
	{
		
		$dates = $datas->dates;
		$newdate = date('M',strtotime($dates))."\\n".date('d',strtotime($dates)) ;
		$responses = $datas->response;
		$response = number_format($responses, 2, '.', '');
		$secondss = $datas->seconds;	
		$seconds = number_format($secondss, 2, '.', '');
		$stddev.=$datas->stdsr.",";
		$stddev2.=$datas->stdsecs.",";
$vals1.=$response.",";
$vals2.=$seconds.",";
$labels1.="'".$newdate."',";

	}

$vals1=rtrim($vals1,",");
$vals2=rtrim($vals2,",");
$labels1=rtrim($labels1,",");
$stddev= rtrim($stddev,",");
$stddev2= rtrim($stddev2,",");


?>

<script>
var central_line_data = [<?php echo $vals1; ?>];
var variance1 = [<?php echo $stddev; ?>];

 	var d1 = [];
        var d2 = [];
       
for (var i=0; i<central_line_data.length; ++i) {
            d1.push(central_line_data[i] + variance1[i]);
            d2.push(central_line_data[i] - variance1[i]);
            
        }
 

var line3 = new RGraph.Line({
            id: 'cvs_graph1',
            data: [d1, d2],
            options: {
                noxaxis: true,
                textSize: 14,
                filled: true,
                filledRange: true,
                fillstyle: 'rgba(0,0,255,0.2)',
                colors: ['rgba(0,0,0,0)'],

                linewidth: 3,
                ylabels: false,
                noaxes: true,
                backgroundGrid: false,
                hmargin: 5,
                tickmarks: null,
                textAccessible: true
            }
        }).draw();

        var line4 = new RGraph.Line({
            id: 'cvs_graph1',
            data: central_line_data,
            options: {
                noxaxis: true,
                textSize: 14,
                backgroundGrid: false,
            labels: [<?php echo $labels1; ?>],
            tooltips: [<?php echo $labels1; ?>],

                colors: ['blue'],
                linewidth: 3,
                numxticks: 11,
                tickmarks: null,
                hmargin: 5,
                shadow: false,
                textAccessible: true
            }
        }).draw();





var central_line_data2 = [<?php echo $vals2; ?>];
var variance2 = [<?php echo $stddev2; ?>];

        var d3 = [];
        var d4 = [];

for (var i=0; i<central_line_data2.length; ++i) {
            d3.push(central_line_data2[i] + variance2[i]);
            d4.push(central_line_data2[i] - variance2[i]);

        }

var line3 = new RGraph.Line({
            id: 'cvs_graph1b',
            data: [d3, d4],
            options: {
                noxaxis: true,
                textSize: 14,
                filled: true,
                filledRange: true,
                fillstyle: 'rgba(0,0,255,0.2)',
                colors: ['rgba(0,0,0,0)'],

                linewidth: 3,
                ylabels: false,
                noaxes: true,
                backgroundGrid: false,
                hmargin: 5,
                tickmarks: null,
                textAccessible: true
            }
        }).draw();
        var line4 = new RGraph.Line({
            id: 'cvs_graph1b',
            data: central_line_data2,
            options: {
                noxaxis: true,
                textSize: 14,
                backgroundGrid: false,
            labels: [<?php echo $labels1; ?>],
            tooltips: [<?php echo $labels1; ?>],

                colors: ['blue'],
                linewidth: 3,
                numxticks: 11,
                tickmarks: null,
                hmargin: 5,
                shadow: false,
                textAccessible: true
            }
        }).draw();




</script>
