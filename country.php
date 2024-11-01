<?php
/**
 * Created by PhpStorm.
 * User: notes
 * Date: 18-Sep-16
 * Time: 12:56 PM
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );
?>

The WPHPC plugin can detect the country of origin of your website users. It uses a plugin called '<a href="https://en-au.wordpress.org/plugins/geoip-detect/" target="_blank">GeoIP Detection</a>'.<br />
<br />
This page will check that the GeoIP is installed and working. <br /><br /><hr>
<h2>Current status:</h2><br />
<?php
if (function_exists("geoip_detect2_get_info_from_current_ip")) {
    $geoip_record = geoip_detect2_get_info_from_current_ip($locales = array('en'), $options = array());
    $country = $geoip_record->country->isoCode;
    echo "current country:" . $country." , country tracking is <h3>enabled</h3>. <br />";

} else {
    echo "current country : 'GeoIP Detection' plugin <h3>not loaded</h3>. The plugin won't be able to track country of users. <br />";
    ?>
    You need to enable the plugin '<a href="https://en-au.wordpress.org/plugins/geoip-detect/" target="_blank">GeoIP Detection</a>'.<br />
    <br />
    <?php

}

?>