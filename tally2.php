<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once("bots.php");

$values="";
$labels="";



$tally = $wpdb->get_row( "select SUM(IF (sr<0.01,1,0)) as sec0,SUM(IF (sr>0.01,IF (sr<0.1,1,0),0)) as sec01,
 SUM(IF (sr>0.1,IF (sr<0.2,1,0),0)) as sec02,
SUM(IF (sr>0.2,IF (sr<0.5,1,0),0)) as sec05,
SUM(IF (sr>0.5,IF (sr<1,1,0),0)) as sec1,
SUM(IF (sr>1,IF (sr<1.5,1,0),0)) as sec15,
SUM(IF (sr>1.5,IF (sr<2,1,0),0)) as sec2,
SUM(IF (sr>2,1,0)) as sec99, AVG(sr) as avg, COUNT(sr) as cnt FROM $table_name where datetime between '$prevdate' and '$date'  ");

echo "<p>average: " .  number_format($tally->avg, 3, '.', '') . " seconds (" . number_format($tally->avg*1000, 0, '.', '')  . " msecs)   ,  ";
echo "hits: " . $tally->cnt . "</p><br/>";
?>
<canvas id="cvs_odo2" width="250" height="250">
    [No canvas support]
</canvas>
<script>
new RGraph.Gauge({
        id: 'cvs_odo2',
        min: 0,
        max: 2,
        value: <?php echo number_format($tally->avg, 2, '.', '');?>,
options :{
            scaleDecimals : 1,
    labelsCount: 4,
            greenColor: '#afa',
borderInner: '#1FA7E1',
borderOuter: '#1FA7E1',
    greenEnd: 1,
    redStart: 1.5,

}
    }).draw()


</script>
<?php


$values.=$tally->sec0.",";
$labels.="'0 - 0.01',";
$values.=$tally->sec01.",";
$labels.="'0.01 - 0.1',";
$values.=$tally->sec02.",";
$labels.="'0.1 - 0.2',";
$values.=$tally->sec05.",";
$labels.="'0.2 - 0.5',";
$values.=$tally->sec1.",";
$labels.="'0.5 - 1',";
$values.=$tally->sec15.",";
$labels.="'1 - 1.5',";
$values.=$tally->sec2.",";
$labels.="'1.5 - 2',";
$values.=$tally->sec99;
$labels.="'2+'";


?>
<canvas id="cvs_tally2" <canvas id="cvs"  width="400" height="200" >
 >
    [No canvas support]
</canvas>


<script>
    new RGraph.HBar({
        id: 'cvs_tally2',
        data: [<?php echo $values; ?>],
        options: {
            labels: [<?php echo $labels; ?>],
//colors: ['red','orange','orange','Gradient(#99f:#27afe9:#058DC7:#058DC7)','Gradient(#99f:#27afe9:#058DC7:#058DC7)', 'Gradient(#99f:#27afe9:#058DC7:#058DC7)', 'Gradient(#99f:#27afe9:#058DC7:#058DC7)','Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
        colors: ['green','green','green','yellow','orange','red', 'red', 'red'],
                    colorsSequential: true,
            textSize: 10,
            gutterLeftAutosize: true,
            scaleZerostart: true,
            textAccessible: true
        }
    }).draw();
</script>
