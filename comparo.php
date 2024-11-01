<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

include("bots.php");

include_once ("technology_calc.php");

$values = "";
$labels = "";




$tally = $wpdb->get_row("select
 AVG(secs) as avg_secs, AVG(sr) as avg_sr, stddev(secs) as stddev_secs, stddev(sr) as stddev_sr, COUNT(secs) as cnt
FROM $table_name where datetime between '$prevdate' and '$date' and secs<30 " . wphpc_get_useragent_exclusion() . wphpc_sql_where_country($country));
?>
<table cellpadding="0" cellspacing="0" style="width: 100%;" class="wphpc_table">

    <tr class="wphpc_table_tr">
        <td style="width: 50%;">
END USER WEB BROWSER LOAD SPEED<br />
            <?php
/*
            echo "<p>average: " . number_format($tally->avg_secs, 1, '.', '') . " secs</p><br />";
            echo "<p>stddev: " . number_format($tally->stddev_secs, 1, '.', '') . " secs</p><br />";
            echo "<p>variance: " . number_format(($tally->stddev_secs * 100) / $tally->avg_secs, 1, '.', '') . " secs</p><br />";

            echo "<p>hits: " . $tally->cnt . " (excludes bots)</p><br />";
 */
?>
            <canvas id="cvs_compare_secs1" width="400" height="200">
                [No canvas support]
            </canvas>
            <script>
var data=[<?php echo number_format($tally->avg_secs, 1, '.', ''); ?>,<?php echo $compare_secs; ?>,<?php echo $wpdone_secs; ?>];
                var max = Math.round(RGraph.arrayMax(data)+0.499);
                var scale=0;
                if (max <5) scale=1;
                var bar = new RGraph.Bar({
                    id: 'cvs_compare_secs1',
                    data: [<?php echo number_format($tally->avg_secs, 1, '.', ''); ?>,<?php echo $compare_secs; ?>,<?php echo $wpdone_secs; ?>],
                    options: {
                        labels: ['this site','<?php echo $hostingName; ?>','wpDone'],
                        shadowOffsetx: 2,
                        shadowOffsety: 2,
                        shadowBlur: 2,
                        textAccessible: true,
                        colors: ['red','green','Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
                        colorsSequential: true,
                        title: 'seconds - lower is better',
                        ymax: max,
                        ymin: 0,
                        scaleDecimals : scale,
                        tooltipsEvent: 'onmousemove',
                        tooltips: ['<?php echo number_format($tally->avg_secs, 1, '.', ''); ?> seconds','<?php echo $compare_secs; ?> seconds','<?php echo $wpdone_secs; ?> seconds'],


                    }
                }).draw();

            </script>
            <?php
            //echo "0 - 0.5 secs: " . $tally->sec0 . "<br />";
            //echo "0.5-1 secs: " . $tally->sec05 . "<br />";
            //echo "1-2 secs: " . $tally->sec1 . "<br />";
            //echo "2-4 secs: " . $tally->sec2 . "<br />";
            //echo "4-7 secs: " . $tally->sec4 . "<br />";
            //echo "7-12 secs: " . $tally->sec7 . "<br />";
            //echo "12-20 secs: " . $tally->sec12 . "<br />";
            //echo "20+ secs: " . $tally->sec99 . "<br />";

            ?>
            <canvas id="cvs_compare_php"  width="400" height="200" >
                [No canvas support]
            </canvas>



            <script>
var datasr = [<?php echo round((float)PHP_VERSION,1) . "," . $compare_php. "," . $wpdone_php; ?>];
                var maxsr =Math.round(RGraph.arrayMax(datasr)+0.499)

                var bar = new RGraph.Bar({
                    id: 'cvs_compare_php',
                    data: [<?php echo round((float)PHP_VERSION,1) . "," . $compare_php. "," . $wpdone_php; ?>],
                    options: {
                        labels: ['this server','<?php echo $hostingName; ?>','wpDone'],
                        shadowOffsetx: 2,
                        shadowOffsety: 2,
                        shadowBlur: 2,
                        textAccessible: true,
                        colors: ['red','green','Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
                        colorsSequential: true,
                        title: 'PHP version - higher is better',
                        scaleDecimals : 1,
                        ymax: maxsr,
                        ymin: 5.3,
                        tooltipsEvent: 'onmousemove',
                        tooltips: ['<?php echo round((float)PHP_VERSION,1) . " ',' " . $compare_php. " ',' " . $wpdone_php ; ?>'],

                    }
                }).draw();

            </script>




        </td>
        <td style="width: 50%;">
HOSTING/PHP SERVER RESPONSES<br />

            <?php
            include ("comparo2.php");
            ?>
        </td>
    </tr>
</table>
