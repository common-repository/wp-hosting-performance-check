<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$wphpc_wp_version=get_bloginfo('version');
$wphpc_php_handler=get_defined_constants()['PHP_SAPI'];
global $wpdb;
$wphpc_mysql_version = $wpdb->get_var("select version();");

$wphpc_tech_score=0;

$wphpc_mysql_version_demerits=0;
$wphpc_wp_version_demerits=0;
$wphpc_php_version_demerits=0;
$wphpc_phphandler_version_demerits=0;
if ( $wphpc_wp_version < 4.9) $wphpc_wp_version_demerits++;
if ( $wphpc_wp_version < 4.8) $wphpc_wp_version_demerits++;
if ( $wphpc_wp_version < 4.7) $wphpc_wp_version_demerits++;
if ( $wphpc_wp_version < 4.6) $wphpc_wp_version_demerits++;
if ( $wphpc_wp_version < 4.5) $wphpc_wp_version_demerits++;
if ( $wphpc_wp_version < 4) $wphpc_wp_version_demerits++;
if ( $wphpc_wp_version < 3) $wphpc_wp_version_demerits++;
if ( PHP_VERSION < 7.2 && strpos(PHP_VERSION,'hhvm')===false) $wphpc_php_version_demerits++;
if ( PHP_VERSION < 7 && strpos(PHP_VERSION,'hhvm')===false) $wphpc_php_version_demerits++;
if ( PHP_VERSION < 5.6) $wphpc_php_version_demerits++;
if ( PHP_VERSION < 5.5) $wphpc_php_version_demerits++;
if ( PHP_VERSION < 5.4) $wphpc_php_version_demerits++;
if ( strpos($wphpc_php_handler, 'fpm') === FALSE
     && strpos($wphpc_php_handler, 'fcgi') === FALSE
    && strpos($wphpc_php_handler, 'srv') === FALSE
    && strpos($wphpc_php_handler, 'fcgi') === FALSE
    && strpos($wphpc_php_handler, 'litespeed') === FALSE
) $wphpc_phphandler_version_demerits+=3;
if ( $wphpc_mysql_version < 5.6  ) $wphpc_mysql_version_demerits++;
if ( $wphpc_mysql_version < 5.7  ) $wphpc_mysql_version_demerits++;

if ( $wphpc_mysql_version < 10.2 && stripos($wphpc_mysql_version,'MariaDB')==true ) $wphpc_mysql_version_demerits++;
if ( $wphpc_mysql_version < 10.1 && stripos($wphpc_mysql_version,'MariaDB')==true ) $wphpc_mysql_version_demerits++;


$wphpc_tech_score= $wphpc_mysql_version_demerits + $wphpc_wp_version_demerits + $wphpc_php_version_demerits + $wphpc_phphandler_version_demerits;

?>
