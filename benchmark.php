<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


set_time_limit(30); 

$options = array();

global $result;
$result = array();
global $wphc_timeStart ;
$wphc_timeStart = microtime(true);
// -----------------------------------------------------------------------------
// Main
// -----------------------------------------------------------------------------
// check performance
$benchmarkResult = test_benchmark($options);



//graph
global $grand;

echo "<p>score: " . round($grand) . "  (lower is better)</p><br/>";
?>
<canvas id="cvs_bench" width="250" height="250">
    [No canvas support]
</canvas>
<script>
    new RGraph.Gauge({
        id: 'cvs_bench',
        min: 0,
        max: 120,
        value: <?php echo  round($grand) ;?>,
        options :{
            scaleDecimals : 0,
            labelsCount: 6,
            greenColor: '#afa',
            borderInner: '#1FA7E1',
            borderOuter: '#1FA7E1',
            greenEnd: 40,
            redStart: 80,
        }
    }).draw()


</script>


<?php
// html output
echo "<br />Artifical benchmark<br /><br />";
echo wphpc_array_to_html($benchmarkResult);


// -----------------------------------------------------------------------------
// Benchmark functions
// -----------------------------------------------------------------------------

function test_benchmark($settings)
{
    $timeStart = microtime(true);

global $result, $grand;
//    $result['version'] = '1.1';
//    $result['sysinfo']['time'] = date("Y-m-d H:i:s");
//    $result['sysinfo']['php_version'] = PHP_VERSION;
 //   $result['sysinfo']['platform'] = PHP_OS;
  //  $result['sysinfo']['server_name'] = $_SERVER['SERVER_NAME'];
   // $result['sysinfo']['server_addr'] = $_SERVER['SERVER_ADDR'];
    $grand=0.01;
    test_math($result);
    test_string($result);
    test_loops($result);
    test_ifelse($result);
//    if (isset($settings['db.host'])) {
        test_mysql($result, $settings);
//    }


    //$result['total'] = timer_diff($timeStart);
    global $wphc_timeStart ;
    $wphc_overalltime=  timer_diff($wphc_timeStart);
    $result['Duration']['start'] = round($wphc_timeStart,1);
    $result['Duration']['end'] = round(microtime(true),1);

    $result['Duration']['Elapsed'] = round($wphc_overalltime,1);
    $result['Duration']['Score'] = round(pow($wphc_overalltime,2),1);

    $grand +=pow($wphc_overalltime,2);
    $grand=$grand*20/32*2/3;
    $result['Overall Score'] = round($grand);

    return $result;
}

function test_math(&$result, $count = 99999)
{
    $timeStart = microtime(true);

    $mathFunctions = array("abs", "acos", "asin", "atan", "bindec", "floor", "exp", "sin", "tan", "pi", "is_finite", "is_nan", "sqrt");
    for ($i = 0; $i < $count; $i++) {
        foreach ($mathFunctions as $function) {
            call_user_func_array($function, array($i));
            $a=(4+9*5/4-3+2)/7;
        }
    }
    global $grand;
    $grand+=timer_diff($timeStart)*10;
    $result['benchmark']['php']['math'] = round(timer_diff($timeStart)*10,1);
}

function test_string(&$result, $count = 99999)
{
    $timeStart = microtime(true);
    $stringFunctions = array("addslashes", "chunk_split", "metaphone", "strip_tags", "md5", "sha1", "strtoupper", "strtolower", "strrev", "strlen", "soundex", "ord");

    $string = 'the quick brown fox jumps over the lazy dog';
    for ($i = 0; $i < $count; $i++) {
        foreach ($stringFunctions as $function) {
            call_user_func_array($function, array($string));
        }
    }
    global $grand;
    $grand+=timer_diff($timeStart)*10;
    $result['benchmark']['php']['string'] = round(timer_diff($timeStart)*10,1);
}

function test_loops(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; ++$i) {

    }
    $i = 0;
    while ($i < $count) {
        ++$i;
    }
    global $grand;
    $grand+=timer_diff($timeStart)*100;
    $result['benchmark']['php']['loops'] = round(timer_diff($timeStart)*100,1);
}

function test_ifelse(&$result, $count = 999999)
{
    $timeStart = microtime(true);
    for ($i = 0; $i < $count; $i++) {
        if ($i == -1) {

        } elseif ($i == -2) {

        } else if ($i == -3) {

        }
    }
    global $grand;
    $grand+=timer_diff($timeStart)*100;
    $result['benchmark']['php']['ifelse'] = round(timer_diff($timeStart)*100,1);
}

function test_mysql(&$result, $settings)
{
    global $grand, $wpdb;


    $timeStart = microtime(true);

  //  $link = mysqli_connect($settings['db.host'], $settings['db.user'], $settings['db.pw']);
   // $result['benchmark']['mysql']['connect'] = timer_diff($timeStart)*1000;
    //$grand+=timer_diff($timeStart)*1000;

    //$arr_return['sysinfo']['mysql_version'] = '';

    //mysqli_select_db($link, $settings['db.name']);
    //$result['benchmark']['mysql']['select_db'] = timer_diff($timeStart)*1000;
    //$grand+=timer_diff($timeStart)*1000;

    //$mylink =
   // $arr_row = mysqli_fetch_array($mylink);
     $wpdb->get_var('SELECT VERSION() as version;');
    $result['benchmark']['mysql']['query_version'] = timer_diff($timeStart)*1000;
    $grand+=timer_diff($timeStart)*1000;

//    $query = "SELECT BENCHMARK(100000,ENCODE('hello',RAND()));";
//	$query = "BENCHMARK(100000,ENCODE(select sum(ID)) from wp_users)";
//SELECT option_name, option_value FROM wp_options WHERE autoload = 'yes'
//$query = "BENCHMARK(100000,select stddev(option_id),sum(option_id) from wp_options limit 100)";
    for ($i = 0; $i < 10; $i++) {

            $mylink = $wpdb->get_var( "select BENCHMARK(1000000,(select sum(ID) from ".$wpdb->prefix."users limit 5))");
       // $dbResult = mysqli_query($link, $query);
        $mylink = $wpdb->get_var("select BENCHMARK(1000000,(SELECT  stddev(option_id) FROM ".$wpdb->prefix."options WHERE autoload = 'yes' limit 100))");
       // $dbResult = mysqli_query($link, $query);
    }

    $result['benchmark']['mysql']['query_benchmark'] = round(timer_diff($timeStart)*20,1);
    $grand+=timer_diff($timeStart)*20;

    //mysqli_close($link);

    //$result['benchmark']['mysql']['total'] = timer_diff($timeStart);
    return $result;
}

function timer_diff($timeStart)
{
    return number_format(microtime(true) - $timeStart, 4);
}

function wphpc_array_to_html($array)
{
    $result = '';
    if (is_array($array)) {
        $result .= '<table>';
        foreach ($array as $k => $v) {
            $result .= "\n<tr><td>";
            $result .= '<strong>' . htmlentities($k) . "</strong></td><td>";
            $result .= wphpc_array_to_html($v);
            $result .= "</td></tr>";
        }
        $result .= "\n</table>";
    } else {
        $result = htmlentities($array);
    }
    return $result;
}
?>
