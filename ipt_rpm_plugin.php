<?php
/*
Plugin Name: Registration Portal Management System
Plugin URI: http://ipanelthemes.com/fsqm/
Description: A robust system to manage registration portals right inside wordpress admin areas.
Author: swashata
Version: 0.0.1
Author URI: http://www.swashata.com/
License: GPLv3
Text Domain: ipt_rpm
 */

/**
 * Copyright iPanel Themes, 2013
 * Our plugins are created for WordPress, an open source software
    released under the GNU public license
    <http://www.gnu.org/licenses/gpl.html>. Therefore any part of
    my plugins which constitute a derivitive work of WordPress are also
    licensed under the GPL 3.0. My plugins are comprised of several
    different file types, including: php, cascading style sheets,
    javascript, as well as several image types including GIF, JPEG, and
    PNG. All PHP and JS files are released under the GPL 3.0 unless
    specified otherwised within the file itself. If specified as
    otherwise the files are licesned or dual licensed (as stated in
    the file) under the MIT <http://www.opensource.org/licenses/mit-license.php>,
    a compatible GPL license.
 */

/* Disable direct access to the file */
if('ipt_rpm_plugin.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die('<h1>Direct Access Prohibited</h1>');

class ipt_rpm_init {
    public static function init($abbr, $version) {
        include_once dirname(__FILE__) . '/classes/loader.php';

        if(is_admin()) {
            include_once dirname(__FILE__) . '/classes/admin-class.php';
            do_action($abbr . '_is_admin_do');
        } else {

        }

        /**
         * Holds the plugin information <br />
         * 'version' => The version of this plugin <br />
         * ...
         *
         * @global array
         */
        global ${$abbr . '_info'};

        $class_name = $abbr . '_loader';

        $plugin_init = new $class_name(__FILE__, $abbr, $version, 'http://www.intechgrity.com/wp-plugins/registration-portal-management/', '$support_forum');

        $plugin_init->load();
    }
}

ipt_rpm_init::init('ipt_rpm', '0.0.1');
