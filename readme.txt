=== wp Hosting Performance Check ===
Plugin Name: wp Hosting Performance Check
Description: This plugin is used to graph your wp Hosting Performance. It records the real users and crawlers accessing your site, and the hosting server response and web page load times.
Author: Scott Farrell
Contributors: sfarrell5123
Version: 2.18.8
Tags: performance, speed, hosting, graph, benchmark, php, version, sysinfo, bot, loadtest
Requires at least: 3.2
Tested up to: 4.9.8
Stable tag: 2.18.8
Author URI: https://www.wpdone.com.au
License:     GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Records the performance of your wp hosting server, display graphs , software levels, benchmark, and load test.

== Description ==

This plugin will record the performance of your wp Hosting company.

Ever noticed your site seems to perform OK when you check, but other folk say its slow later. Like gremlins are getting in and making it slower ?

It's often difficult to know how your wp web site is performing, and how real end users are experiencing the performance of your wp web site.

This plugin will display graphs of the performance of the server (so you'll be able to see what the gremlins are doing).

It also tracks performance of the web page load speed, which includes all the assets jpg/png/css/js.

You'll be able to view graphs for different periods of time, to see how your server is performing, and how real world users are observing the performance of your wp site.

You'll be able to observe when the server is sluggish, or when it is fast.

It is designed to run async, so any performance hit is after the end user's web page has finished loading. This should result in no impact to your WordPress website delivering pages.

It also has a feature to email you when the server response becomes unacceptably slow.

New Features
- ability to load test your WordPress website - see how many visitors you can comfortably accommodate.
- ability to benchmark your current hosting against other hosting companies
- technology index - to give you an idea of how good/poor the technology your WordPress site is running on
- artificial benchmark of php and mysql (on the sysinfo page for now).
- php and technology index on the hosting comparison pages.
- bot analysis - how much resources are bot consuming ?

== Installation ==


1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. the plugin will start working straight away.
4. Use the wp Hosting Performance Check -> Settings screen to configure the plugin
5. log out of WordPress. And go surf a few pages of your site.
6. log back into /wp-admin/ , and observe your wp Hosting Performance Check stats.
7. [optional] install 'GeoIP detection' plugin, and this plugin will track the country of the visitors. You can check that the GeoIP plugin is working in the settings of eiter this plugin, or the geoip plugin. I recommend you install a local copy of the geomind database through the settings of the geoip plugin. https://wordpress.org/plugins/geoip-detect/

== Frequently Asked Questions ==

= will it slow my site down ? =

No.
We do an ajax request asyncronously. So after the page loads, it sends a short request.

= will it make my database slow ? =

we've taken reasonable steps to not slow the database.
we have no indexes on the table, so inserts are fast.
we prune the table to only keep the last 30 days.

= does it log admin users ? =

no , we ignore all logged in users.

= does this use some sort of web whacking to test the site performance ? =

no, it records the real hits from real users and web crawlers.

= it says there is no data to display =

this plugin logs realtime users, and excludes logged in users. So log out of WordPress, and surf your site a little.

= Does it work with caching ? =

It works reasonably with full page caching.
You'll need to clear your cache after installing the plugin.
You can cache bust to test yourself, just add ?r3we4 to the end of your page name, just some random junk after the question mark - will make it so the cache doesn't work.

== Screenshots ==

1. a few of the graphs. This shows a tally (or distribution) of the main 2 timings that are tracked. The first graph shows end users real world web browser load speed. This is a combination of the server, and how the page is designed. The second gaphs shows the pure response time of the hosting server

2. this screen shot is giving you a quick indication of how well your wp hosting server performance is doing. Green zone is good, you don't want it being in the red zone.

3. This is from the loadtest report, showing WordPress appdex - a measure of user responsiveness of your WordPress SiteGround

4. This is from the loadtest report

5. This is from the loadtest report

6. This is part of the sysinfo report - showing your software technology, and artificial benchmark results.

7. Bot analysis - showing CPU resources consumed by bot usage.

== Changelog ==

= 1.5 =
* Add nonce for security in settings.php

= 1.6 =
* updates to screenshot filenames - trying to get them to display on wordpress.org/plugins/

= 1.8 =
* got screenshots to work

= 1.9.5 =
* fix for jqyer pre-processing by wordpress

= 2.0.0 =
* FAQ

= 2.0.1 =
* install update

= 2.1 =
* some guestimates using javascript if the page is full page cached, by the likes of w3tc or varnish etc

= 2.1.1 =
* updated message when you've just installed the plugin and there is no data, to be more informative.

= 2.2 =
* updated the dashboard display
* disabled the width setting for the dashboard as it was screwing up the dashboard

= 2.2.1 =
* further bashboard cleanup

= 2.3 =
* better fix for dont log admin

= 2.4 =
* tracking country code, thanks to idea from Dimitri Cassimatis

= 2.4.2 =
* fix CREATE table statement

= 2.5 =
* hash the IP address to avoid leaks from full page caching

= 2.5.1 =
* minor country bug

= 2.5.2 =
* dial indicator graph updates, and dashboard div update

= 2.6 =
* did away with our hideous custom settings tables

= 2.6.1 =
* fix for top10 to support country selection

= 2.7 =
* update UI

= 2.7.1 =
* UI change to top10

= 2.7.2 =
* oops fixed a bug in the country code

= 2.7.3 =
* fixed slug on page urls

= 2.7.4 =
* remove some old code for database schema change

= 2.7.5 =
* put a sort on the top 10 table

= 2.8 =
* navigation to go prev/next in time

= 2.8.1 =
* fix the prev/next so it works when country is disabled

= 2.8.2 =
* bug fix functions 304

= 2.8.3 =
* fix for missing getcsv function

= 2.9 =
* introduce central logging for benchmarking (need to work on the graphs next).

= 2.9.2 =
* add plugin message

= 2.9.3 =
* add country page and admin messages

= 2.9.4 =
* add userHash

= 2.9.6 =
* fix dismissal of admin notices

= 2.9.7 =
* add tracking of php version for comparison

= 2.10 =
* comparison graphs now work

= 2.11 =
* turned off some debugging

= 2.11.2 =
* fix bug in upload

= 2.11.7 =
* small fix to graph scaling

= 2.11.10 =
* colour changes on main graphs

= 2.11.11 =
* hardcode jquery in javascript if it isn't loaded, some sort of failure elsewhere on WordPress I think.

= 2.11.12 =
* remove unwanted code

= 2.11.13 =
* small graph changes

= 2.11.14 =
* heading changes

= 2.12 =
* added a sysinfo page

= 2.12.3 =
* .1 .2 .3 all bug fixes trying to get sysinfo page working

= 2.12.8 =
* remove prepare from cron task

= 2.12.9 =
* add a note about fcgi

= 2.12.10 =
* changed menu position

= 2.13 =
* added technology level graph

= 2.13.4 =
* add technology level to benchmark  logging

= 2.13.6 =
* added litespeed as a good php handler - thanks Steven D.

= 2.13.7 =
* updated tested release

= 2.14 =
* added some artifical benchmarks to sysinfo (because it was easier to fit, should be on the main page).

= 2.15 =
* updated for php 7.2

= 2.15.3 =
* colourize the technology text based on demerits.

= 2.15.5 =
* hosting comparo now includes php version and technology deremits

= 2.15.6 =
* added a br tag in the graph headings

= 2.15.12 =
* clean some output data to html

= 2.16.0 =
* load test released

= 2.18.0 =
* bot analysis released
