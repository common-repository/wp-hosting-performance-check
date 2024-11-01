<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );

//    $wphpc_loadtest_key = get_option("wphpc_loadtest_key", bin2hex(openssl_random_pseudo_bytes(16)));
$wphpc_loadtest_key=bin2hex(openssl_random_pseudo_bytes(16));
    update_option("wphpc_loadtest_key",$wphpc_loadtest_key,false);


    $wphpc_loadtest_prevtest_list=get_option("wphpc_loadtest_prevtest_list" , array());



//echo $wphpc_loadtest_key;
$wphpc_loadtest_submit = array(
    "secret" => $wphpc_loadtest_key
  );

  $wphpc_loadtest_urls=array();
  array_push($wphpc_loadtest_urls ,get_home_url() );
  $wphpc_loadtest_submit['homepage']=get_home_url();
  $wphpc_posts = get_posts(
    array(
            'post_status' => 'publish',
            'numberposts'        => '-1'
        )  );

  if ( ! empty($wphpc_posts) ) {

      foreach ( $wphpc_posts as $wphpc_post ) :
          setup_postdata( $wphpc_post );
  array_push($wphpc_loadtest_urls ,get_permalink($wphpc_post));
      endforeach;
      wp_reset_postdata();
  }

$wphpc_products = get_posts( array( 'post_type' => 'product','numberposts'        => '-1' ));
if ( ! empty($wphpc_products) ) {

    foreach ( $wphpc_products as $wphpc_product ) {

array_push($wphpc_loadtest_urls ,get_permalink($wphpc_product->ID));
array_push($wphpc_loadtest_urls ,get_permalink($wphpc_product->ID));
array_push($wphpc_loadtest_urls ,get_permalink($wphpc_product->ID));
array_push($wphpc_loadtest_urls ,get_permalink($wphpc_product->ID));
array_push($wphpc_loadtest_urls ,get_permalink($wphpc_product->ID)."?add-to-cart=".($wphpc_product->ID));
array_push($wphpc_loadtest_urls ,get_home_url()."/cart/?add-to-cart=".($wphpc_product->ID));
array_push($wphpc_loadtest_urls ,get_home_url()."/checkout/?add-to-cart=".($wphpc_product->ID));


}

}

/**
$wphpc_categories=get_categories(array(
  'orderby' => 'name',
  'order' => 'ASC'
));

foreach($wphpc_categories as $wphpc_category) {
  array_push($wphpc_loadtest_urls,get_cat_ID($wphpc_category));
  array_push($wphpc_loadtest_urls , get_category_link( get_cat_ID($wphpc_category) ));
}
**/

  $wphpc_pages = get_pages();

  foreach ( $wphpc_pages as $wphpc_page ) {
  array_push($wphpc_loadtest_urls ,get_page_link( $wphpc_page->ID ));
  }
  $wphpc_loadtest_submit["urls"]= $wphpc_loadtest_urls;


  $wphpc_loadtest_load=0;
  if(isset($_REQUEST['load'])) {
      $wphpc_loadtest_load = intval($_REQUEST['load']);
  }
  $wphpc_loadtest_duration=30;
  if(isset($_REQUEST['duration'])) {
      $wphpc_loadtest_duration = intval($_REQUEST['duration']);
  }
  $wphpc_loadtest_wait=5;
  if(isset($_REQUEST['wait'])) {
      $wphpc_loadtest_wait = intval($_REQUEST['wait']);
  }
$wphpc_tolocation='AU';
if(isset($_REQUEST['location'])) {
    $wphpc_tolocation = $_REQUEST['location'];
}


  if ($wphpc_loadtest_load >0){
  ?>
  <h2> Test Results </h2>
  <?php
  $wphpc_loadtest_submit["load"]=$wphpc_loadtest_load;
  $wphpc_loadtest_submit["duration"]=$wphpc_loadtest_duration;
  $wphpc_loadtest_submit["wait"]=$wphpc_loadtest_wait;

  $wphpc_loadtest_prevtest=array (
    "secretkey" => $wphpc_loadtest_key,
    "date" => date("d-m-Y h:i:s"),
    "load" => $wphpc_loadtest_load,
    "location" => $wphpc_tolocation,
    "duration" => $wphpc_loadtest_duration,
    "wait" => $wphpc_loadtest_wait
  );
  array_push($wphpc_loadtest_prevtest_list,$wphpc_loadtest_prevtest);
update_option("wphpc_loadtest_prevtest_list",$wphpc_loadtest_prevtest_list,false);
$wphpc_loadtest_return_data_temp= wphpc_sendData_loadtest($wphpc_loadtest_submit, $wphpc_tolocation);

  $wphpc_loadtest_return_data= unserialize($wphpc_loadtest_return_data_temp);
  if (strpos($wphpc_loadtest_return_data['secretmatch'],'NOT')>0)

  {
    echo "secret did not match , tested aborted.<br />";

  }
  else {
      echo "secret matched , continuing.<br />";
  echo $wphpc_loadtest_return_data['details']."<br />";
  $wphpc_loctmp="";
  if ( $wphpc_tolocation=='US'){
        $wphpc_loctmp="us";
      }
?>

<iframe  id="piegraph" width="900" height="610" frameborder="0" src="https://loadtest<?php echo $wphpc_loctmp;?>.wpdone.com.au/<?php echo $wphpc_loadtest_key;?>/piegraph_wp.php"></iframe>


<?php


} // end secret matched
  if (strlen ($wphpc_loadtest_return_data['details'])<20)
  { echo $wphpc_loadtest_return_data_temp;}
  #echo $wphpc_loadtest_return_data['apdex']."<br />";
#echo $wphpc_loadtest_return_data_temp;
#$wphpc_piedata=$wphpc_loadtest_return_data['apdex'];
#include_once('piegraph.php');
  }



if (! empty($wphpc_loadtest_prevtest_list)){
  echo "<h2> Previous Test Results </h2>";
  foreach ($wphpc_loadtest_prevtest_list as $wphpc_loadtest_prevtest){
    $wphpc_loctmp="";
    if ( array_key_exists('location',$wphpc_loadtest_prevtest) &&
       $wphpc_loadtest_prevtest['location']=='US'){
          $wphpc_loctmp="us";
        }
    echo '<a href="https://loadtest'.$wphpc_loctmp.'.wpdone.com.au/'.$wphpc_loadtest_prevtest['secretkey'] .'/" target="_new">';
    echo $wphpc_loadtest_prevtest['date'].'</a>';
    echo $wphpc_loadtest_prevtest['location'];
    if ( array_key_exists ('duration' , $wphpc_loadtest_prevtest))
      echo ' duration '.$wphpc_loadtest_prevtest['duration'];
    echo ' load '.$wphpc_loadtest_prevtest['load'].'<br />';
  }
}
?>


<br /><br />
<h2>WARNING</h2>
After you hit 'GO' an external service will create web hits on your WordPress website. It will simulate load on your server.<br /><br />
This is used to test how many web users your WordPress website can handle.<br /><br />
If you choose larger numbers on this test it is likely to hurt your hosting provider. It is a good idea to get permission first. <br /><br />
If you are using shared hosting, or reseller hosting, then this might get your account blocked.<br />
<br /><br />
<h2>What does it do ? </h2>
<ul>
<li>The test will web a selection of pages, posts and products from your site.</li><br />
<li>It simlulates a browser - downloading and caching page assets (images/css/js) and cookies for each user. Each simluated user hits the URLs in a random order.
This is likely to cache bust any WordPress caching if you have WooCommerce. The First few pags might cache, but as soon as it hits a 'add_to_cart' URL, the following pages requests will bypass caching.</li><br />
<li>It will run to scale up to the selected user load for 15 seconds, then 30 seconds of the load you requested, then a ramp down. The test should take about 70 seconds to run. If it's 100 or more user test, it takes about 150 seconds as we create additional servers to help.</li><br />
<li>The random wait is to simulate users reading/thinking. The wait will be between 1 and X that you selected seconds. The average will be (1 + X)/2 - or just over half the value you choose. </li><br />
<li>Currently it's running from Australia or the US. I am excluding connect latency for main APDEX graph, but not others yet.
</li><br />

</ul>
<h2>Interpreting the results </h2>
This is pretty tricky.<br /><br />
Firstly, anything less than 100% "APDEX" means your web server was breaking, and you should consider that a fail.<br /><br />
You should start with a reasonable number of user load. <br /><br />
You are looking for a point where errors start, or latency increases, or response time increases - all of which then means your web server is struggling.
Instead of returning a good user experience, your server is getting sloppy slow, and your users are twiddling their thumbs.<br /><br />
When you find the point that it is struggling at, note the time (mouse over works well).
Then compare that to the 'active threads over time' graph to see how many users/thread were active at that time.<br /><br />
<br />
<h2>Security </h2>
I didn't want to make it too easy for the tool to be abused, so there is some security. <br /><br />
The basics is installing the plugin is your authentication to run a loadtest on the site that it's installed on. <br /><br />
I generate a secret key, and record the Site Name from WordPress settings. Before the load test runs, I call back on the Site Name and
make sure the sitename and the key secret matches. Then I destroy the secret key.<br /><br />
I also check all the URLs start with the correct site name.<br /><br />
<?php

#<h2>Current Testing Sites available/busy/OK</h2>
#If the response is empty, it means that testing location is down for maintenance (or broken). This refreshes every 15 seconds.<br /><br />
#The Aussie testing site is lacking resources for more than 500 thread test, please use the US test site for greater than 500 user test. I subtract the latency from the main apdex result anyway.<br /><br />
#If the Aussie server is running a test, but the load is under 4, and you are running under 100 user test, go ahead. <br />

#Australia <iframe  id="auFrame" width="350" height="40" frameborder="0" src="https://loadtest.wpdone.com.au/showload.php"></iframe>
#US <iframe id="usFrame" width="350" height="40" frameborder="0" src="https://loadtestus.wpdone.com.au/showload.php"></iframe><br />
?>
<br />
<script>
window.setInterval("reloadIFrame();", 15000);

function reloadIFrame() {
 //document.frames["auFrame"].location.reload();
 //document.frames["usFrame"].location.reload();
 [].forEach.call(document.querySelectorAll('iframe'), function(ifr) {
      ifr.src=ifr.src;
    })
}
function checkLoad(select) {
  var location = document.getElementById('location').value;
  var load = select.options[select.selectedIndex].text;
if ( location=='AU' && +load > 500)
     alert('please use United States test location for tests over 500 users.');
}
</script>
<?php
$wphpc_loadtest_loads=array("1","2","5","10","20","50","100","200","500","750","1000","2000","3000","5000","7200","10000","15000","20000","25000");
$wphpc_loadtest_durations=array("60","120","300");
?>
<form>
  Test Location :
  <SELECT id=location>
    <OPTION VALUE="AU">Australia</OPTION>
    <OPTION VALUE="US">United States</OPTION>
    </SELECT>
    Simulated Users :
<SELECT   id="load" onchange="checkLoad(this)">

<?php
foreach($wphpc_loadtest_loads as $row  ) {

#echo "<OPTION value=\"".   add_query_arg(
#  array(
#      'load'=> htmlspecialchars($row),
#      'go'=>'true'
#      ))  . "\"> " . $row . "</option>";
?><OPTION value="<?php
  echo htmlspecialchars($row) .'"' ;
  if ($row ==5 )
    echo " selected";
echo   '>'.htmlspecialchars($row) . '</option>';
}
?>
</SELECT>
Test duration seconds :
<SELECT   id="duration">
<OPTION value="30">30</OPTION>
<?php
foreach($wphpc_loadtest_durations as $row  ) {
?><OPTION value="<?php
echo htmlspecialchars($row);
echo '"> ' . htmlspecialchars($row) . '</option>';
}
?>
</SELECT>
Random wait in seconds between user page clicks :
<SELECT   id="wait">
<OPTION value="1">1</OPTION>
<OPTION value="2">2</OPTION>
<OPTION value="5" selected>5</OPTION>
<OPTION value="10">10</OPTION>
<OPTION value="20">20</OPTION>


 <br />
<input id="GO" type="button" value="GO" onclick="window.open( 'admin.php?page=wphpc_loadtest_page&go=true'
+'&duration='+document.getElementById('duration').value
+'&location='+ document.getElementById('location').value
+'&wait='+ document.getElementById('wait').value
+'&load='+ document.getElementById('load').value  , '_self')"/>
</form>

<h2>Here is a list of the URLs to be tested </h2>
<?php


foreach ($wphpc_loadtest_urls as $wphpc_loadtest_urlt){

  echo $wphpc_loadtest_urlt."<br >";
}//end for each



function wphpc_sendData_loadtest ($datain, $wphpc_tolocation){

    $wphpc_debug_senddata=false;

$url="http://loadtest.wpdone.com.au/loadtest3.php";
if (
  strlen($wphpc_tolocation)==2 &&
  $wphpc_tolocation=='US'){
    $url="http://loadtestus.wpdone.com.au/loadtest3.php";
}
$postdata=serialize($datain);
if ($wphpc_debug_senddata==true) echo "sending:".$postdata;

    $postdata=gzcompress($postdata,9);
   // echo PHP_EOL."# bytes to encrypt:".strlen($postdata);
//    openssl_public_encrypt($postdata, $encrypted, $public_key);

$encrypted=$postdata;
    $iv = openssl_random_pseudo_bytes(32);

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

#$header = array('Content-Type: text/xml;charset=UTF-8','Content-Encoding: gzip',);
#curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
#curl_setopt($ch, CURLOPT_ENCODING, 'gzip');

//curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
    curl_setopt ($ch, CURLOPT_USERAGENT,  "wp Hosting Performance Check v".WPHPC_VERSION);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 180);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_REFERER, $url);
//  $send="enc=".bin2hex($encrypted);
//$send="enc=".$encrypted;
##    if ($wphpc_debug_senddata==true) echo "sennding this:".$send;
#curl_setopt ($ch, CURLOPT_POSTFIELDS,$send );
curl_setopt ($ch, CURLOPT_POSTFIELDS, $encrypted);

curl_setopt ($ch, CURLOPT_POST, 1);
$result = curl_exec ($ch);

    if ($wphpc_debug_senddata==true) echo $result;
curl_close($ch);
return $result;


}

?>
