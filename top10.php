<div class="wphpc_url_table">
    <h1 id="wphpc_top10">Top 10 Slow Urls</h1>

    <?php
    if (!defined('ABSPATH'))
        exit; // Exit if accessed directly
    global $wpdb;
    include("bots.php");

if(isset($_REQUEST['wphpc_sortByPageload']) &&
intval($_REQUEST['wphpc_sortByPageload']) == 1
)
        {
	$wphpc_top10_orderby="secs";
        }
        else
        {
	$wphpc_top10_orderby="sr";

        }


    $table_name = $wpdb->prefix . "webpages_data";

    $data_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name where secs < 120 " . $whereDate . wphpc_get_useragent_exclusion() . wphpc_sql_where_country($country));
    if ($data_count > 0) {

        $mylink = $wpdb->get_results("SELECT * FROM $table_name where secs <120" . $whereDate . wphpc_get_useragent_exclusion() . wphpc_sql_where_country($country) . " order by ".$wphpc_top10_orderby." desc LIMIT 10");

//$sortImg = esc_url(WPHPC__PLUGIN_URL."iso/".strtolower($country).".png");
$wphpc_sortByPageload= esc_url( add_query_arg( 'wphpc_sortByPageload', '1' ) );
$wphpc_sortByPageloadOff= esc_url( add_query_arg( 'wphpc_sortByPageload', '0' ) );



        ?>
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <td >Url</td>
                    <td width="80">Page Load Time
<a href="<?php echo $wphpc_sortByPageload; ?>#wphpc_top10">
 &or;
</a>
</td>
                    <td width="80">Server Response Time
<a href="<?php echo $wphpc_sortByPageloadOff; ?>#wphpc_top10">
 &or;
</a>

</td>
                    <td width="100">IP address</td>
                    <td width="100">Date</td>
                </tr>
            </thead>

            <tbody id="the-list">
            </tbody>
            <?php
            foreach ($mylink as $datas) {
                ?>     

                <tr>   

                    <td>
			<a href="http://<?php echo esc_html($datas->url); ?>" target="_blank" ><?php echo esc_html($datas->url); ?>
</td>    

                    <td><?php echo esc_html($datas->secs); ?> secs</td>   

                    <td><?php echo esc_html($datas->sr); ?> secs</td> 

                    <td><?php echo esc_html($datas->ip . " " . $datas->country); ?></td> 

                    <td><?php echo esc_html($datas->datetime); ?></td> 		

                </tr>
                <tr>
                    <td colspan="5">
                        <?php echo $datas->useragent; ?>
                    </td>
                </tr>

                <?php
            }
            ?>

        </table>
    </div>
    <?php
} else {
    echo "<p>No data to display..</p>";
}
?>
