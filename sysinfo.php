<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
require_once( WPHPC__PLUGIN_DIR . 'advertise.php' );
$theme = wp_get_theme();

$plugins = get_plugins();
$active_plugins = get_option('active_plugins', array());
$memory_limit = ini_get('memory_limit');
$memory_usage = round(memory_get_usage() / 1024 / 1024, 2);
$all_options = wp_load_alloptions();
$all_options_serialized = serialize($all_options);
$all_options_bytes = round(mb_strlen($all_options_serialized, '8bit') / 1024, 2);
$all_options_transients = get_transients_in_options($all_options);


global $wpdb;
//$wphpc_phpinfo=phpinfo();
//$wphpc_phpinfo=phpinfo();

$mysqlversion = $wpdb->get_var("select version();");

?>

<div id="sysinfo">
    <div class="wrap">


        <h2 class="title"><?php _e('SysInfo', 'sysinfo') ?></h2>

        <div class="clear"></div>

        <div class="section">
            <div class="header">
                <?php _e('System Information', 'sysinfo') ?>
            </div>
            <h4>Technology Level</h4>
<table>
    <tr>
        <td valign="top">

            <?php
            include ("technology.php");
            ?>

</td>
        <td valign="top">
            <?php
            include ("benchmark.php");
            ?>


        </td>
    </tr>
</table>
            <div class="inside">
<div id="wphpc_sysinfo" contenteditable="true">
<?php

$mysqlversion = $wpdb->get_var("select version();");



wphpc_getColour($wphpc_wp_version_demerits) ;
   ?>
                    WordPress Version:      <?php echo get_bloginfo('version') . "\n"; ?> <br />
<?php wphpc_getColourEnd($wphpc_wp_version_demerits) ; ?>

<br />
<?php
wphpc_getColour($wphpc_php_version_demerits);
 ?>
                    PHP version 5.6 is OK, below 5.6 is TERRIBLE. PHP 7.0 is GOOD. PHP 7.2 or HHVM is GREAT.<br />
                    PHP Version:            <?php echo PHP_VERSION . "\n"; ?><br />
<br />
<?php
wphpc_getColourEnd($wphpc_php_version_demerits);

wphpc_getColour($wphpc_phphandler_version_demerits);
 ?>

                    You want to have an FPM or fcgi handler, everything else is rubbish. I think nginx says srv here, that's fine also (as I think that's pph-fpm). litespeed is another good choice.<br />
                    PHP Handler:            <?php echo get_defined_constants()['PHP_SAPI']. "\n"; ?><br />
                    <br />

<?php
wphpc_getColourEnd($wphpc_phphandler_version_demerits);

wphpc_getColour($wphpc_mysql_version_demerits);
 ?>

                    You need version 5.7 or above here. Or if you have MariaDB 10.1 is OK, 10.2 is GREAT.<br />
                    MySQL Version:          <?php  echo $mysqlversion . "\n"; ?><br />
                    <?php
                    wphpc_getColourEnd($wphpc_mysql_version_demerits);
                     ?>
<br />
                    Web Server:             <?php echo $_SERVER['SERVER_SOFTWARE'] . "\n"; ?><br />
<br />
                    If you regularly use URLs other than these, you might have some redirects slowing things down.<br />
                    WordPress URL:          <?php echo get_bloginfo('wpurl') . "\n"; ?><br />
                    Home URL:               <?php echo get_bloginfo('url') . "\n"; ?><br />
<br />
<br />
                    Multi-Site Active:      <?php echo is_multisite() ? _e('Yes', 'sysinfo') . "\n" : _e('No', 'sysinfo') . "\n" ?><br />
<br />
                    PHP cURL Support:       <?php echo (function_exists('curl_init')) ? _e('Yes', 'sysinfo') . "\n" : _e('No', 'sysinfo') . "\n"; ?><br />
                    PHP GD Support:         <?php echo (function_exists('gd_info')) ? _e('Yes', 'sysinfo') . "\n" : _e('No', 'sysinfo') . "\n"; ?><br />
                    PHP Memory Limit:       <?php echo $memory_limit . "\n"; ?><br />
                    PHP Memory Usage:       <?php echo $memory_usage . "M (" . round($memory_usage / $memory_limit * 100, 0) . "%)\n"; ?><br />
                    PHP Post Max Size:      <?php echo ini_get('post_max_size') . "\n"; ?><br />
                    PHP Upload Max Size:    <?php echo ini_get('upload_max_filesize') . "\n"; ?><br />
<br />
                    WP Options Count:       <?php echo count($all_options) . "\n"; ?><br />
                    WP Options Size:        <?php echo $all_options_bytes . "kb\n" ?><br />
                    WP Options Transients:  <?php echo count($all_options_transients) . "\n"; ?><br />
<br />
                    WP_DEBUG:               <?php echo defined('WP_DEBUG') ? WP_DEBUG ? _e('Enabled', 'sysinfo') . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?><br />
                    SCRIPT_DEBUG:           <?php echo defined('SCRIPT_DEBUG') ? SCRIPT_DEBUG ? _e('Enabled', 'sysinfo') . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?><br />
                    SAVEQUERIES:            <?php echo defined('SAVEQUERIES') ? SAVEQUERIES ? _e('Enabled', 'sysinfo') . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?><br />
                    AUTOSAVE_INTERVAL:      <?php echo defined('AUTOSAVE_INTERVAL') ? AUTOSAVE_INTERVAL ? AUTOSAVE_INTERVAL . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?><br />
                    WP_POST_REVISIONS:      <?php echo defined('WP_POST_REVISIONS') ? WP_POST_REVISIONS ? WP_POST_REVISIONS . "\n" : _e('Disabled', 'sysinfo') . "\n" : _e('Not set', 'sysinfo') . "\n" ?><br />
<br />
                    Active Theme:<br />
- <?php echo $theme->get('Name') ?> <?php echo $theme->get('Version') . "\n"; ?><br />
                    <?php echo $theme->get('ThemeURI') . "\n"; ?><br />
                    <br />
<?php


//print_r(get_defined_constants());
?>
                    Active Plugins:<br />
                    <?php
                    foreach ($plugins as $plugin_path => $plugin) {
                        // Only show active plugins
                        if (in_array($plugin_path, $active_plugins)) {
                            echo '- ' . $plugin['Name'] . ' ' . $plugin['Version'] . "\n";

                            if (isset($plugin['PluginURI'])) {
                                echo '  ' . $plugin['PluginURI'] . "\n<br />";
                            }

                            echo "\n<br />";
                        }
                    }
                    ?>
				</div>
            </div>

        </div>
    </div>
</div>
<br />
with thanks to the original sysinfo plugin https://wordpress.org/plugins/sysinfo/<br />

<?php
function get_transients_in_options($options)
{
    $transients = array();

    foreach ($options as $name => $value) {
        if (stristr($name, 'transient')) {
            $transients[$name] = $value;
        }
    }
}

function wphpc_getColour($wphpc_demerit){
  if ($wphpc_demerit >2)
        echo "<span style=\"color: red;\">";
        elseif ($wphpc_demerit >0)
  echo "<span style=\"color: orange;\">";
}
function wphpc_getColourEnd($wphpc_demerit){
  if ($wphpc_demerit >0)
    echo "</span>";
}
?>
