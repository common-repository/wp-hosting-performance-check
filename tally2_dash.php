<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once("bots.php");

$values="";
$labels="";



$tally = $wpdb->get_row( "select SUM(IF (sr<0.01,1,0)) as sec0,SUM(IF (sr>0.01,IF (sr<0.1,1,0),0)) as sec01,
 SUM(IF (sr>0.1,IF (sr<0.2,1,0),0)) as sec02, 
SUM(IF (sr>0.2,IF (sr<0.5,1,0),0)) as sec05, 
SUM(IF (sr>0.5,IF (sr<1,1,0),0)) as sec1, 
SUM(IF (sr>1,IF (sr<2,1,0),0)) as sec2, 
SUM(IF (sr>2,1,0)) as sec99, AVG(sr) as avg, COUNT(sr) as cnt FROM $table_name where datetime between '$prevdate' and '$date'  ");

echo "average: " .  number_format($tally->avg, 3, '.', '') . " secs (" . number_format($tally->avg*1000, 0, '.', '')  . " msecs) <br />";
echo "hits: " . $tally->cnt . "<br />";
?>
<canvas id="cvs_odo2" width="200" height="200">
    [No canvas support]
</canvas>
<script>

new RGraph.Gauge({
        id: 'cvs_odo2',
        min: 0,
        max: 2,
        value: <?php echo number_format($tally->avg, 1, '.', '');?>,
options :{
            scaleDecimals : 1,
            greenColor: '#afa',
borderInner: '#1FA7E1',
borderOuter: '#1FA7E1',
    greenEnd: 1,
    redStart: 1.5,


}
    }).draw()


</script>


