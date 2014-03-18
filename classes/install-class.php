<?php
/**
 * The file containing the installation class
 *
 * Responsible for plugin activation install
 * and database and script version compare update
 *
 * @acronym abbr
 * @author iPanel Themes <contact@ipanelthemes.com>
 * @version 1.0.0
 * @package <Plugin Name>
 * @subpackage Install Class
 */

/* Disable direct access to the file */
if('install-class.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die('<h1>Direct Access Prohibited</h1>');

/**
 * <ABBR> <Plugin Name> Install class
 *
 * @author Swashata Ghosh <swashata@intechgrity.com>
 * @version 1.0.0
 */
class ipt_rpm_install {
    /**
     * Database prefix
     * Mainly used for MS compatibility
     * @var string
     */
    var $prefix;

    /**
     * Abbr to access the default options
     * @var string
     */
    var $abbr;

    /**
     * Constructor function
     *
     * Mainly used to correctly determine the prefix
     * And also to set abbr
     *
     * @global wpdb $wpdb
     * @global int $blog_id
     */
    public function __construct($abbr = '') {
        global $wpdb;
        $prefix = '';
        if(is_multisite()) {
            global $blog_id;
            $prefix = $wpdb->base_prefix . $blog_id . '_';
        } else {
            $prefix = $wpdb->prefix;
        }

        $this->prefix = $prefix;
        $this->abbr = $abbr;
    }

    /**
     * install
     * Do the things
     */
    public function install() {
        $this->checkversions();
        $this->checkdb();
        $this->checkop();
        $this->set_capability();
    }

    /**
     * Restores the WP Options to the defaults
     * Deletes the default options set and calls checkop
     */
    public function restore_op() {
        delete_option($this->abbr . '_info');
        delete_option($this->abbr . '_settings');

        $this->checkop();
    }

    /**
     * Restores the database
     * Deletes the current tables and freshly installs the new one
     * @global wpdb $wpdb
     */
    public function restore_db() {
        global $wpdb;

        //$wpdb->query("DROP TABLE IF EXISTS {$this->prefix}table_name");
        $this->checkdb();
    }

    /**
     * Checks whether PHP version 5 or greater is installed or not
     * Also checks whether WordPress version is greater than or equal to the required
     *
     * If fails then it automatically deactivates the plugin
     * and gives error
     * @return void
     */
    private function checkversions() {
        if (version_compare(PHP_VERSION, '5.0.0', '<')) {
            deactivate_plugins(plugin_basename(wp_feedback_loader::$abs_file));
            wp_die(__('The plugin requires PHP version greater than or equal to 5.x.x', $this->abbr));
            return;
        }

        if(version_compare(get_bloginfo('version'), '3.3.0', '<')) {
            deactivate_plugins(plugin_basename(wp_feedback_loader::$abs_file));
            wp_die(__('The plugin requires WordPress version greater than or equal to 3.1.x', $this->abbr));
            return;
        }
    }

    /**
     * Creates the table and options
     * @access public
     * @global string $charset_collate
     */
    public function checkdb() {
        /**
         * Include the necessary files
         * Also the global options
         */
        if (file_exists(ABSPATH . 'wp-admin/includes/upgrade.php')) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        } else {
            require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
        }
        global $charset_collate;


        $prefix = $this->prefix;
        $sqls = array();

        $sqls[] = "CREATE TABLE {$prefix}ipt_rpm_reg (
                        id BIGINT(20) UNSIGNED NOT NULL auto_increment,
                        code BIGINT(20) UNSIGNED NOT NULL default 0,
                        name VARCHAR(255) NOT NULL default '',
                        email VARCHAR(255) NOT NULL default '',
                        phone VARCHAR(255) NOT NULL default '',
                        reg_data LONGTEXT NOT NULL,
                        p_data LONGTEXT NOT NULL,
                        log LONGTEXT NOT NULL,
                        portal INT(10) UNSIGNED NOT NULL default 0,
                        user INT(10) UNSIGNED NOT NULL default 1,
                        status TINYINT(1) UNSIGNED NOT NULL default 0,
                        fees DECIMAL(20,2) UNSIGNED NOT NULL default 0,
                        created DATETIME NOT NULL default '0000-00-00 00:00:00',
                        UNIQUE KEY reg_code (code, portal),
                        PRIMARY KEY  (id)
                   ) $charset_collate;";

        foreach($sqls as $sql)
            dbDelta ($sql);
    }

    /**
     * Creates the options
     */
    public function checkop() {
        $class_name = $this->abbr . '_loader';
        $info = array(
            'version' => $class_name::$version,
            'reg_table' => $this->prefix . 'ipt_rpm_reg',
        );


        //check to create/update
        if(!get_option($this->abbr . '_info')) { //New Installation
            add_option($this->abbr . '_info', $info);

            $default_settings = array(
                'currency' => '',
                'email' => get_option('admin_email'),
                'titles' => array(
                    'portal' => __('Registration Portal', 'ipt_rpm'),
                    'registrant' => __('Registrant\'s Information', 'ipt_rpm'),
                    'registration_topic' => __('Subscriptions', 'ipt_rpm'),
                    'registrar' => __('Registrar\'s Information', 'ipt_rpm'),
                ),
                'portals' => array(
                    0 => array(
                        'name' => __('Portal #1', 'ipt_rpm'),
                        'users' => get_users(array(
                            'fields' => 'ID',
                            'role' => 'administrator',
                        )),
                        'prefix' => 'A-',
                    ),
                ),
            );
            add_option('ipt_rpm_settings', $default_settings);

            $default_reg = array(
                0 => array(
                    'name' => __('Gaming Event', 'ipt_rpm'),
                    'fee' => '50',
                    'opt_in' => __('Subscribe', 'ipt_rpm'),
                    'desc' => __('Register to gaming event for 50 bucks.', 'events'),
                    'pdata' => array(
                        0 => array(
                            'question' => __('Select the gaming events you wish to register for', 'ipt_rpm'),
                            'options' => "NFS Most Wanted\r\nCounter Strike\r\nFIFA '10",
                            'type' => 'single',
                            'required' => true,
                        ),
                    ),
                ),
            );

            add_option('ipt_rpm_reg', $default_reg);

            $default_registrant_data = array(
                'name' => array(
                    'enabled' => true,
                    'required' => true,
                ),
                'email' => array(
                    'enabled' => true,
                    'required' => true,
                ),
                'phone' => array(
                    'enabled' => true,
                    'required' => true,
                ),
                'others' => array(
                    0 => array(
                        'question' => __('College', 'ipt_rpm'),
                        'options' => "",
                        'type' => 'smalltext',
                        'required' => true,
                    ),
                    1 => array(
                        'question' => __('Team Name', 'ipt_rpm'),
                        'options' => "",
                        'type' => 'smalltext',
                        'required' => false,
                    ),
                ),
            );

            add_option('ipt_rpm_registrant_data', $default_registrant_data);
        } else { //Possible Update
            $old_info = get_option($this->abbr . '_info');

            switch($old_info['version']) {
                default :
                case '1.0.0' :
                    //something here
                    break;
            }

            //delete all transient cache
            //...

            //update the options
            update_option($this->abbr . '_info', $info);
        }
    }

    /**
     * Create and set custom capabilities
     * @global WP_Roles $wp_roles
     */
    private function set_capability() {
        global $wp_roles;

        //Add new Roles, ie user types
        $admin_capabilities = array(
            'ipt_rpm_view_dashboard' => true,
            'ipt_rpm_new_reg' => true,
            'ipt_rpm_view_all' => true,
            'ipt_rpm_view_reg' => true,
            'ipt_rpm_gen_report' => true,
            'ipt_rpm_settings' => true,
        );
        $registrat_capabilities = array(
            'ipt_rpm_view_dashboard' => true,
            'ipt_rpm_new_reg' => true,
            'ipt_rpm_view_all' => true,
            'ipt_rpm_view_reg' => true,
            'ipt_rpm_gen_report' => true,
        );
        $wp_roles->add_role('ipt_rpm_admin', __('Registration Portal Administrator', 'ipt_rpm'), $admin_capabilities);

        $wp_roles->add_role('ipt_rpm_registrar', __('Registration Portal Registrar', 'ipt_rpm'), $registrat_capabilities);

        //Add admin capabilities to site admins
        foreach($admin_capabilities as $admin_cap => $grant) {
            $wp_roles->add_cap('administrator', $admin_cap, $grant);
        }
    }
}
