<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include("bots.php");

$values="";
$labels="";




$tally = $wpdb->get_row( "select SUM(IF (secs<0.5,1,0)) as sec0,SUM(IF (secs>0.5,IF (secs<1,1,0),0)) as sec05, SUM(IF (secs>1,IF (secs<2,1,0),0)) as sec1,
SUM(IF (secs>2,IF (secs<4,1,0),0)) as sec2,
SUM(IF (secs>4,IF (secs<7,1,0),0)) as sec4,
SUM(IF (secs>7,IF (secs<12,1,0),0)) as sec7,
SUM(IF (secs>12,IF (secs<20,1,0),0)) as sec12,
SUM(IF (secs>20,1,0)) as sec99, AVG(secs) as avg, COUNT(secs) as cnt FROM $table_name where  secs>0 and datetime between '$prevdate' and '$date' and secs<30 ". wphpc_get_useragent_exclusion() );

?>
<div id="wphpc_dash_container" style="position:relative;display: inline:block;height:300px;" >
<div id="wphpc_dash_left" style="float: left; margin: 10px; width: 220px;">
<h4>tally of end user web browser load speed</h4>
<?php
echo "average: " .  number_format($tally->avg, 1, '.', '') . " secs<br />";
echo "hits: " . $tally->cnt . " (excludes bots)<br />";
?>
<canvas id="cvs_odo1" width="200" height="200">
    [No canvas support]
</canvas>
</div>
<script>

new RGraph.Gauge({
        id: 'cvs_odo1',
        min: 0,
        max: 10,
        value: <?php echo number_format($tally->avg, 1, '.', '');?>,
options :{
            greenColor: '#afa',
borderInner: '#1FA7E1',
borderOuter: '#1FA7E1',
    greenEnd: 5,
    redStart: 7.5,


}
    }).draw()


</script>



<div id="wphpc_dash_right" style="margin: 10px; width: 220px;  float:right;">

<h4>tally of hosting/php Server responses</h4>

<?php
include ("tally2_dash.php");
?>
</div>
    <div id="wphpc_dash_right" style="margin: 10px; width: 220px;  float:right;">

        <h4>Technology Level</h4>

        <?php
        include ("technology_dash.php");
        ?>
    </div>


</div>
