<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


include_once ("technology_calc.php");

echo "<p>score: " . $wphpc_tech_score . "  (lower is better)</p><br/>";
?>
<canvas id="cvs_odo3" width="250" height="250">
    [No canvas support]
</canvas>
<script>
new RGraph.Gauge({
        id: 'cvs_odo3',
        min: 0,
        max: 10,
        value: <?php echo  $wphpc_tech_score ;?>,
options :{
            scaleDecimals : 0,
    labelsCount: 5,
            greenColor: '#afa',
borderInner: '#1FA7E1',
borderOuter: '#1FA7E1',
    greenEnd: 2,
    redStart: 5,
}
    }).draw()


</script>
<?php
echo "<p> Technology demerits</p><br/>";
echo "WordPress version: " . $wphpc_wp_version_demerits . " demerits<br/>";
echo "PHP version: " . $wphpc_php_version_demerits . " demerits<br/>";
echo "PHP handler version: " . $wphpc_phphandler_version_demerits . " demerits<br/>";
echo "MySQL version: " . $wphpc_mysql_version_demerits . " demerits<br/>";
?>
for more information on demerits, see <A href="admin.php?page=wphpc_sysinfo">sysinfo</A>.
