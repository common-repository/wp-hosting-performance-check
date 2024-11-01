<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

include("bots.php");

$values = "";
$labels = "";




$tally = $wpdb->get_row("select SUM(IF (secs<0.5,1,0)) as sec0,SUM(IF (secs>0.5,IF (secs<1,1,0),0)) as sec05, SUM(IF (secs>1,IF (secs<2,1,0),0)) as sec1,
SUM(IF (secs>2,IF (secs<4,1,0),0)) as sec2,
SUM(IF (secs>4,IF (secs<7,1,0),0)) as sec4,
SUM(IF (secs>7,IF (secs<12,1,0),0)) as sec7,
SUM(IF (secs>12,IF (secs<20,1,0),0)) as sec12,
SUM(IF (secs>20,1,0)) as sec99, AVG(secs) as avg, COUNT(secs) as cnt FROM $table_name where secs>0 and datetime between '$prevdate' and '$date' and secs<30 " . wphpc_get_useragent_exclusion() . wphpc_sql_where_country($country));
?>
<table cellpadding="0" cellspacing="0" style="width: 100%;" class="wphpc_table">

    <tr class="wphpc_table_tr">
        <td style="width: 32%;">
            <h4>web browser load speed (on page performance)</h4>
            <?php
            echo "<p>average: " . number_format($tally->avg, 1, '.', '') . " seconds  ,  ";
            echo "hits: " . $tally->cnt . " (excludes bots)</p><br />";
            ?>
            <canvas id="cvs_odo1" width="250" height="250">
                [No canvas support]
            </canvas>
            <script>
                new RGraph.Gauge({
                    id: 'cvs_odo1',
                    min: 0,
                    max: 10,
                    value: <?php echo number_format($tally->avg, 1, '.', ''); ?>,
                    options: {
                        greenColor: '#afa',
borderInner: '#1FA7E1',
borderOuter: '#1FA7E1',
                        greenEnd: 5,
                        redStart: 7.5,
                    }
                }).draw()
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

            $values.=$tally->sec0 . ",";
            $labels.="'0 - 0.5',";
            $values.=$tally->sec05 . ",";
            $labels.="'0.5 - 1',";
            $values.=$tally->sec1 . ",";
            $labels.="'1-2',";
            $values.=$tally->sec2 . ",";
            $labels.="'2-4',";
            $values.=$tally->sec4 . ",";
            $labels.="'4-7',";
            $values.=$tally->sec7 . ",";
            $labels.="'7-12',";
            $values.=$tally->sec12 . ",";
            $labels.="'12-20',";
            $values.=$tally->sec99;
            $labels.="'20+'";
            ?>
            <canvas id="cvs"  width="400" height="200" >
                [No canvas support]
            </canvas>



            <script>
                new RGraph.HBar({
                    id: 'cvs',
                    data: [<?php echo $values; ?>],
                    options: {
                        labels: [<?php echo $labels; ?>],
//                        colors: ['red','red','red','orange','Gradient(#99f:#27afe9:#058DC7:#058DC7)', 'Gradient(#99f:#27afe9:#058DC7:#058DC7)', 'Gradient(#99f:#27afe9:#058DC7:#058DC7)','Gradient(#99f:#27afe9:#058DC7:#058DC7)'],
                        colors: ['green','green','yellow','orange','red', 'red', 'red','red'],
                        textSize: 10,
                        gutterLeftAutosize: true,
                        scaleZerostart: true,
                        textAccessible: true,
                        colorsSequential: true,

                    }
                }).draw();
            </script>




        </td>
        <td style="width: 32%;">
            <h4>Hosting/php Server response</h4>

            <?php
            include ("tally2.php");
            ?>
        </td>

        <td style="width: 32%;">
            <h4>Technology Level</h4>

            <?php
            include ("technology.php");
            ?>
        </td>

    </tr>
</table>
