

<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly


$values = "";
$labels = "";




$tally = $wpdb->get_row("select
 AVG(secs) as avg_secs, AVG(sr) as avg_sr, stddev(secs) as stddev_secs, stddev(sr) as stddev_sr, COUNT(secs) as cnt
FROM $table_name where datetime between '$prevdate' and '$date' and secs<30 "  );
?>

            <canvas id="cvs_compare_sr1" width="400" height="200">
                [No canvas support]
            </canvas>
            <script>
var datasr = [<?php echo number_format($tally->avg_sr, 1, '.', ''); ?>,<?php echo $compare_sr; ?>,<?php echo $wpdone_sr; ?>];
                var maxsr =Math.round(RGraph.arrayMax(datasr)+0.499)

                var bar = new RGraph.Bar({
                    id: 'cvs_compare_sr1',
                    data: [<?php echo number_format($tally->avg_sr, 1, '.', ''); ?>,<?php echo $compare_sr; ?>,<?php echo $wpdone_sr; ?>],
                    options: {
                        labels: ['this server','<?php echo $hostingName; ?>','wpDone'],
                        shadowOffsetx: 2,
                        shadowOffsety: 2,
                        shadowBlur: 2,
                        textAccessible: true,
                        colors: ['red','green','Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
                        colorsSequential: true,
                        title: 'seconds - lower is better',
                        scaleDecimals : 1,
                        ymax: maxsr,
                        ymin: 0,
                        tooltipsEvent: 'onmousemove',
                        tooltips: ['<?php echo number_format($tally->avg_sr, 1, '.', ''); ?> seconds','<?php echo $compare_sr; ?> seconds','<?php echo $wpdone_sr; ?> seconds'],

                    }
                }).draw();

            </script>

            <canvas id="cvs_compare_tech"  width="400" height="200" >
                [No canvas support]
            </canvas>


<?php include_once ("technology_calc.php");
#we have to -1 from the db value, as we added 1 when uploaded to avoid storing 0.
$disp_compare_hostingTechnologyLevel=(float)$compare_hostingTechnologyLevel-1;
if ($disp_compare_hostingTechnologyLevel<0)
  $disp_compare_hostingTechnologyLevel=0;
 ?>
            <script>
  var datasr = [<?php echo $wphpc_tech_score . "," . $disp_compare_hostingTechnologyLevel. "," . $wpdone_hostingTechnologyLevel; ?>];
                var maxsr =Math.round(RGraph.arrayMax(datasr)+0.499)

                var bar = new RGraph.Bar({
                    id: 'cvs_compare_tech',
                    data: [<?php echo $wphpc_tech_score . "," . $disp_compare_hostingTechnologyLevel. "," . $wpdone_hostingTechnologyLevel; ?>],
                    options: {
                        labels: ['this server','<?php echo $hostingName; ?>','wpDone'],
                        shadowOffsetx: 2,
                        shadowOffsety: 2,
                        shadowBlur: 2,
                        textAccessible: true,
                        colors: ['red','green','Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
                        colorsSequential: true,
                        title: 'technology demerits - lower is better',
                        scaleDecimals : 1,
                        ymax: maxsr,
                        ymin: 0,
                        tooltipsEvent: 'onmousemove',
                        tooltips: ['<?php echo $wphpc_tech_score . " ',' " . $disp_compare_hostingTechnologyLevel. " ',' " . $wpdone_hostingTechnologyLevel; ?>'],

                    }
                }).draw();

            </script>
