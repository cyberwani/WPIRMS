<?php
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
if ('uninstall.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h2>Direct File Access Prohibited</h2>');
/**
 * The plugin uninstallation script
 * 1. Remove the tables
 * 2. Remove options
 * 3. Remove capabilities
 * Done
 */

/**
 * Set global variables
 * @var wpdb
 */
global $wpdb;
$prefix = '';

if(is_multisite()) {
    $prefix = $wpdb->base_prefix;
    $blogs = (array) $wpdb->get_col("SELECT blog_id FROM {$prefix}blogs");

    foreach($blogs as $blog) {
        $msprefix = $prefix . $blog . '_';

        //delete database
        if($wpdb->get_var("SHOW TABLES LIKE '{$msprefix}ipt_rpm_reg'")) {
            $wpdb->query("DROP TABLE IF EXISTS {$msprefix}ipt_rpm_reg");
        }

        //delete options
        delete_blog_option($blog, 'ipt_rpm_info');
        delete_blog_option($blog, 'ipt_rpm_settings');
        delete_blog_option($blog, 'ipt_rpm_reg');
        delete_blog_option($blog, 'ipt_rpm_registrant_data');
    }
} else {
    //delete table
    $prefix = $wpdb->prefix;
    if($wpdb->get_var("SHOW TABLES LIKE '{$prefix}ipt_rpm_reg'")) {
        $wpdb->query("DROP TABLE IF EXISTS {$prefix}ipt_rpm_reg");
    }

    delete_option('ipt_rpm_info');
    delete_option('ipt_rpm_settings');
    delete_option('ipt_rpm_reg');
    delete_option('ipt_rpm_registrant_data');
}




//Users and roles
$admin_capabilities = array(
    'ipt_rpm_view_dashboard' => true,
    'ipt_rpm_new_reg' => true,
    'ipt_rpm_view_all' => true,
    'ipt_rpm_view_reg' => true,
    'ipt_rpm_gen_report' => true,
    'ipt_rpm_settings' => true,
);

/**
 * @var WP_Roles
 */
global $wp_roles;
$wp_roles->remove_role('ipt_rpm_registrant');
$wp_roles->remove_role('ipt_rpm_admin');
$wp_roles->remove_role('ipt_rpm_registrar');
foreach($admin_capabilities as $cap => $grant) {
    $wp_roles->remove_cap('administrator', $cap);
}

