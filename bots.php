<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!function_exists('str_getcsv')) {
    function str_getcsv($input, $delimiter = ',', $enclosure = '"', $escape = '\\', $eol = '\n') {
        $fh = fopen('php://temp', 'r+');
        fwrite($fh, $input);
        rewind($fh);

        $row = fgetcsv($fh);

        fclose($fh);
        return $row;
    }
}

if (!function_exists('wphpc_get_bot_list')){
function wphpc_get_bot_list(){
return "ia_archiver,AdsBot-Google,Baiduspider,LSSRocketCrawler,ltx71,Googlebot-Image,Googlebot/,AhrefsBot,aiHitBot,TeeRaidBot,UptimeRobot/,PaperLiBot,TrendsmapResolver,OnalyticaBot,Go-http-client,facebookexternalhit,EveryoneSocialBot,Pixel2XLBuild,archive.org_bot,BLEXBot,BLEXBot,Cliqzbot,DeuSu,DomainAppender,DotBot,electricmonk,Exabot,houzzbot,linkdexbot,Mail.RU_Bot,Mail.RU_Bot,meanpathbot,MegaIndex.ru,MJ12bot,360Spider,HaosouSpider,oBot,OrangeBot,Plukkie,Qwantify,SearchmetricsBot,SemrushBot/,SemrushBot-SA,SeznamBot,spbot,Yahoo,YandexBot,yelpspider,BingLocalSearch,SurveyBot,msnbot,msnbot-media,netEstate,psbot,python-requests,Riddler,rogerbot,Twitterbot,Datanyze,voltron,Wget";
}
}


if (!function_exists('wphpc_get_bot_array')){
function wphpc_get_bot_array(){
return str_getcsv(wphpc_get_bot_list(), ",");
}
}

if (!function_exists('wphpc_get_useragent_exclusion')){
function wphpc_get_useragent_exclusion(){

$Data = str_getcsv(wphpc_get_bot_list(), ","); //parse the entries

$sql = "";

foreach($Data as &$agent)
	$sql = $sql . " and useragent NOT Like '%" . $agent . "%' ";

return $sql;
}
}

// echo get_useragent_exclusion();
?>
