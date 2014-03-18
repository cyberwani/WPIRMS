<?php
/**
 * The file containing the loader class of <Plugin Name>
 *
 * Responsible for all direct actions throughout all of the site
 *
 * @acronym abbr
 * @author iPanel Themes <contact@ipanelthemes.com>
 * @version 1.0.0
 * @package <Plugin Name>
 * @subpackage Loader Class
 */

/* Disable direct access to the file */
if('loader.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die('<h1>Direct Access Prohibited</h1>');


/**
 * ipt_rpm Registration Portal Management Loader class
 *
 * @author Swashata Ghosh <swashata@intechgrity.com>
 * @version 1.0.0
 */
class ipt_rpm_loader {
    /**
     * The init classes used to generate the admin menu
     * The class should initialize and hook itself
     * @see /classes/admin-class.php and extend from the base abstract class
     * @staticvar array
     */
    static $init_classes = array();

    /**
     * @staticvar string
     * Holds the absolute path of the main plugin file directory
     */
    static $abs_path;

    /**
     * @staticvar string
     * Holds the absolute path of the main plugin file
     */
    static $abs_file;

    /**
     * @staticvar string
     * The current version of the plugin
     */
    static $version;

    /**
     * @staticvar string
     * The abbreviated name of the plugin
     * Mainly used for the enqueue style and script of the default admin.css and admin.js file
     */
    static $abbr;

    /**
     * The Documentation Link - From InTechgrity
     * @var string
     */
    static $documentation;

    /**
     * The support forum link - From WordPress Extends
     * @var string
     */
    static $support_forum;

    /**
     * Indicates if the loader has been instantiated once
     * @var boolean
     */
    static $is_inited = false;

    /**
     * The constructor function
     *
     * @global array $ipt_rpm_info Stored plugin information
     * @param string $file_loc The path of the main file
     * @param string $abbr Acronym and Text domain name for the plugin
     * @param string $version Script version of the plugin
     * @param string $doc URL of the documentation
     * @param string $sup URL of the support forum
     */
    public function __construct($file_loc, $abbr = 'default', $version = '1.0.0', $doc = '', $sup = '') {
        //do nothing if inited
        if(self::$is_inited == true)
            return;

        self::$abs_path = dirname($file_loc);
        self::$abs_file = $file_loc;
        self::$abbr = $abbr;
        self::$version = $version;
        self::$init_classes = array();
        self::$documentation = $doc;
        self::$support_forum = $sup;

        global ${self::$abbr . '_info'};
        ${self::$abbr . '_info'} = get_option(self::$abbr . '_info');
    }

    /**
     * The Load function
     * It inits the overall plugin
     */
    public function load() {
        //do nothing if inited
        if(self::$is_inited == true)
            return;

        //activation hook
        register_activation_hook(self::$abs_file, array(&$this, 'plugin_install'));

        //Load text domain
        add_action('plugins_loaded', array(&$this, 'plugin_textdomain'));

        //Check for Version and Database Compatibility
        add_action('plugins_loaded', array(&$this, 'database_version'));

        //Check for Update from our own update server
        //...

        self::$init_classes = array('ipt_rpm_admin_dashboard', 'ipt_rpm_admin_new_reg', 'ipt_rpm_admin_view_all', 'ipt_rpm_admin_view_reg', 'ipt_rpm_admin_gen_report', 'ipt_rpm_admin_settings');
        //Filter the Admin Classes
        self::$init_classes = apply_filters(self::$abbr . '_admin_menus', self::$init_classes);

        //Do for admin OR frontend
        if(is_admin()) { //Do for admin area
            //Initialize the admin menus
            $this->init_admin_menus();
            add_action('admin_init', array(&$this, 'gen_admin_menu'), 20);

            //Add proper redirection for new user roles
            add_action('admin_menu', array(&$this, 'redirect_users'), 100);
            add_action('login_redirect', array(&$this, 'redirect_login'), 100, 3);

            //add the global view registration
            //http://localhost/wordpress/wp-admin/admin-ajax.php?action=ipt_rpm_view_registration&id=1&width=640&height=500
            add_action('wp_ajax_ipt_rpm_view_registration', array(&$this, 'view_registration'), 10, 0);
        } else { //Do for frontend area
            add_action('wp_print_styles', array(&$this, 'enqueue_script_style'));
        }

        //Do for admin AND frontend

        //Set the is_inited to true to avoid future collisions
        self::$is_inited = true;
    }

    public function view_registration() {
        $id = (int) $_GET['id'];
        $reg_helper = new ipt_rpm_registration_helper($id);
        $reg_helper->show_data();
        die();
    }

    public function redirect_users() {
        //var_dump($this->determine_redirect() && !isset($_GET['page']));
        if($this->determine_redirect() && !isset($_GET['page'])) {
            wp_redirect(admin_url('admin.php?page=ipt_rpm_menu_dashboard'));
            die();
        }
    }

    public function redirect_login($redirect_to, $request, $user) {
        if($this->determine_redirect($user)) {
            return admin_url('admin.php?page=ipt_rpm_menu_dashboard');
        } else {
            return $redirect_to;
        }
    }

    private function determine_redirect($current_user = null) {
        $redirect = true;

        if($current_user == null)
            $current_user = wp_get_current_user();
        $roles = $current_user->roles;
        //var_dump($roles);

        foreach((array) $roles as $role) {
            if(!in_array($role, array('ipt_rpm_admin', 'ipt_rpm_registrar'))) {
                $redirect = false;
                break;
            }
        }
        //var_dump($redirect);
        return $redirect;
    }

    /**
     * Initialize the Admin Menus
     */
    public function init_admin_menus() {
        foreach((array) self::$init_classes as $class) {
            if(class_exists($class)) {
                global ${self::$abbr . '_admin_menu' . $class};
                ${self::$abbr . '_admin_menu' . $class} = new $class(self::$abbr);
            }
        }
        do_action(self::$abbr . '_admin_menus');
    }


    /**
     * Attach the script and styles to the admin menus
     */
    public function gen_admin_menu() {
        $admin_menus = array();
        foreach((array) self::$init_classes as $class) {
            if(class_exists($class)) {
                global ${self::$abbr . '_admin_menu' . $class};
                $admin_menus[] = ${self::$abbr . '_admin_menu' . $class}->get_pagehook();
            }
        }

        foreach($admin_menus as $menu) {
            add_action('admin_print_styles-' . $menu, array(&$this, 'admin_enqueue_script_style'));
        }

    }


    /**
     * Admin enqueues
     */
    public function admin_enqueue_script_style() {

        //common scripts
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-color');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-progressbar');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('ColorPicker', plugins_url('/static/admin/js/colorpicker.js', self::$abs_file), array('jquery'), self::$version);
        wp_enqueue_script('jquery-ui-timepicker', plugins_url('/static/admin/js/jquery-ui-timepicker-addon.js', self::$abs_file), array('jquery'), self::$version, true);
        wp_enqueue_script(self::$abbr . '_ipt_sortable', plugins_url('/static/admin/js/iptSortable.js', self::$abs_file), array('jquery', 'jquery-ui-sortable', 'jquery-ui-dialog'), self::$version);
        wp_enqueue_script(self::$abbr . '_admin_js', plugins_url('/static/admin/js/admin.js', self::$abs_file), array('jquery'), self::$version);
        wp_enqueue_script('jquery_printarea', plugins_url('/static/admin/js/jquery.printElement.min.js', self::$abs_file), array('jquery'), '1.0.0');

        //common styles
        wp_enqueue_style(self::$abbr . '_admin_css', plugins_url('/static/admin/css/admin.css', self::$abs_file), array(), self::$version);
        wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
        wp_enqueue_style('ColorPicker', plugins_url('/static/admin/css/colorpicker.css', self::$abs_file), array(), self::$version);
        wp_enqueue_style('jQuery-UI-style', plugins_url('/static/admin/css/jquery-ui-1.8.23.custom.css', self::$abs_file), array(), self::$version);
    }

    /**
     * Frontend enqueues
     */
    public function enqueue_script_style() {
        //...
    }


    /**
     * Function to install the plugin
     */
    public function plugin_install() {
        include_once self::$abs_path . '/classes/install-class.php';
        $class_name = self::$abbr . '_install';
        $install = new $class_name(self::$abbr);
        $install->install();
    }


    /**
     * Function to update the script
     *
     * It compares script version and database version
     */
    public function database_version() {
        global ${self::$abbr . '_info'};

        $d_version = ${self::$abbr . '_info'}['version'];
        $s_version = self::$version;

        if($d_version == $s_version)
            return;

        if(version_compare($d_version, $s_version, '<')) {
            include_once self::$abs_path . '/classes/install-class.php';

            $class_name = self::$abbr . '_install';
            $install = new $class_name(self::$abbr);
            $install->checkop();
            $install->checkdb();
        }
    }

    /**
     * Load the text domain on plugin load
     * Hooked to the plugins_loaded via the load method
     */
    public function plugin_textdomain() {
        load_plugin_textdomain(self::$abbr, false, dirname(plugin_basename(self::$abs_file)) . '/translations/');
    }
}
