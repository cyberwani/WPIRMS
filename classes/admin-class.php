<?php
/**
 * The file containing the admin classes
 *
 * Responsible for all admin side pages and ajax responses
 *
 * @acronym abbr
 * @author iPanel Themes <contact@ipanelthemes.com>
 * @version 1.0.0
 * @package <Plugin Name>
 * @subpackage Loader Class
 */

/* Disable direct access to the file */
if('admin-class.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die('<h1>Direct Access Prohibited</h1>');


/** ____________ ADMIN CLASSES ____________ **/

class ipt_rpm_admin_dashboard extends ipt_rpm_admin_base {
    var $reg_helper;
    public function __construct($abbr = '') {
        $this->capability = 'ipt_rpm_view_dashboard';
        $this->action_nonce = 'ipt_rpm_view_dashboard_nonce';

        parent::__construct($abbr);

        $this->icon_url = $this->url['images'] . 'dashboard.png';
        $this->is_metabox = true;
        $this->reg_helper = new ipt_rpm_registration_helper();
    }

    public function admin_menu() {
        $this->pagehook = add_menu_page(__('Registration Portal Management - Dashboard', 'ipt_rpm'), __('R.P. Manager', 'ipt_rpm'), $this->capability, 'ipt_rpm_menu_dashboard', array(&$this, 'index'), $this->url['images'] . 'rp_manager.png');
        add_submenu_page('ipt_rpm_menu_dashboard', __('Registration Portal Management - Dashboard', 'ipt_rpm'), __('Dashboard', 'ipt_rpm'), $this->capability, 'ipt_rpm_menu_dashboard', array(&$this, 'index'));
        parent::admin_menu();
    }

    public function index() {
        $this->index_head(__('Registration Portal Management &raquo; Dashboard', 'ipt_rpm'));
        ?>
<div id="dashboard-widgets">
    <div class="metabox-holder">
        <?php $this->print_metabox_containers('normal'); ?>
        <?php $this->print_metabox_containers('side'); ?>
    </div>
    <div class="clear"></div>
    <div class="metabox-holder">
        <?php $this->print_metabox_containers('debug'); ?>
    </div>
</div>
        <?php
        $this->index_foot(false);
    }

    public function save_post() {
        parent::save_post();

        //do something

        wp_redirect(add_query_arg(array('post_result' => 1), $_POST['_wp_http_referer']));
        die();
    }

    public function on_load_page() {
        parent::on_load_page();

        add_meta_box('ipt_rpm_meta_info', __('Registration Information', 'ipt_rpm'), array(&$this, 'meta_system_info'), $this->pagehook, 'normal', 'high');
        add_meta_box('ipt_rpm_meta_tq', __('R.P. Management Plugin Information', 'ipt_rpm'), array(&$this, 'meta_thank_you'), $this->pagehook, 'side', 'high');


        add_meta_box('ipt_rpm_stats', __('Portal Registration Statistics', 'ipt_rpm'), array(&$this, 'meta_stats'), $this->pagehook, 'debug', 'high');
        add_meta_box('ipt_rpm_meta_social', __('Support & Social', 'ipt_rpm'), array(&$this, 'meta_social'), $this->pagehook, 'debug', 'high');

        get_current_screen()->add_help_tab(array(
            'title' => __('Overview', 'ipt_rpm'),
            'id' => 'ipt_rpm_db_overview',
            'content' => '',
            'callback' => array(&$this, 'help_texts'),
        ));
        get_current_screen()->add_help_tab(array(
            'title' => __('Basic Working', 'ipt_rpm'),
            'id' => 'ipt_rpm_db_working',
            'content' => '',
            'callback' => array(&$this, 'help_texts'),
        ));
        get_current_screen()->add_help_tab(array(
            'title' => __('Credits', 'ipt_rpm'),
            'id' => 'ipt_rpm_db_credit',
            'content' => '',
            'callback' => array(&$this, 'help_texts'),
        ));

        get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Documentation</a>', 'ipt_rpm'), ipt_rpm_loader::$documentation) . '</p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Support Forums</a>', 'ipt_rpm'), ipt_rpm_loader::$support_forum) . '</p>'
	);
    }

    public function help_texts($obj, $tab) {
        switch($tab['id']) {
            case 'ipt_rpm_db_credit' :
                ?>
<?php $this->print_p_okay(__('R.P. Manager proudly uses the iPt Plugin Framework which will be released shortly.', 'ipt_rpm')); ?>
<div class="p-message">
    <p>
        <strong><?php _e('Developer:') ?></strong> <a href="http://www.swashata.com/">Swashata</a> |
        <strong><?php _e('Community Blog:') ?></strong> <a href="http://www.intechgrity.com/">InTechgrity.com</a> |
        <strong><?php _e('Services:') ?></strong> <a href="http://www.itgwebsolutions.biz">iTg Web Solutions</a> <span class="description"><?php _e('Coming soon...', 'ipt_rpm') ?></span>
    </p>
</div>
                <?php
                break;
            case 'ipt_rpm_db_overview' :
                ?>
<p><?php _e('Thank you for using our R.P. Manager Plugin. This product is brought to you by iPanelThemes.com, premium WordPress Themes and Plugins. Since this is a free plugin, so the documentation and support is hosted through our blog <a href="http://www.intechgrity.com/">InTechgrity.com</a> and WordPress Codex.', 'ipt_rpm') ?></p>
<p><?php _e('The idea of the plugin came from our college fest, where we had to give registration IDs and collect subscription fees for people registering in our Tech Fest. We had 5 registration portals and needed a way to streamline the registration system throughout all the portals and the event zones.', 'ipt_rpm') ?></p>
<p><?php _e('This is when we came up with this particular solution. Our workflow was something like this:', 'ipt_rpm'); ?></p>
<ul>
    <li><?php _e('All the portals had their own prefix and own set of registration cards with printed IDs.', 'ipt_rpm'); ?></li>
    <li><?php _e('People came to a particular portal and asked for events s/he wants to subscribe in.', 'ipt_rpm'); ?></li>
    <li><?php _e('The registrar filled up the form and generated an ID for the registrant.', 'ipt_rpm'); ?></li>
    <li><?php _e('The registrar handed over the card to the registrant for further use.', 'ipt_rpm'); ?></li>
    <li><?php _e('Finally for verifications, the coordinator at every event zone, used the registrant\'s ID to check whether s/he has registered for the event or not.', 'ipt_rpm'); ?></li>
</ul>
<p><?php _e('This is the summarized version of our registration system. To know a detailed version, you can check the documentation link.', 'ipt_rpm'); ?></p>
                <?php
                break;
            case 'ipt_rpm_db_working' :
                ?>
<p><?php _e('Working of the Registration Portal Manager Plugin is somewhat like this.', 'ipt_rpm'); ?></p>
<ul>
    <li><?php _e('Admin Setup of Users & Portals', 'ipt_rpm'); ?>. <a href="admin.php?page=ipt_rpm_menu_settings"><?php _e('Click Here', 'ipt_rpm') ?>.</a></li>
    <li><?php _e('Admin Setup of Registrations & Subscriptions', 'ipt_rpm'); ?> <a href="admin.php?page=ipt_rpm_menu_settings"><?php _e('Click Here', 'ipt_rpm') ?>.</a></li>
    <li><?php _e('Adding a new registration', 'ipt_rpm'); ?> <a href="admin.php?page=ipt_rpm_menu_new_reg"><?php _e('Click Here', 'ipt_rpm') ?>.</a></li>
    <li><?php _e('Handing over the Registration Card.', 'ipt_rpm'); ?> <a href="admin.php?page=ipt_rpm_menu_view_reg"><?php _e('Click Here', 'ipt_rpm') ?>.</a></li>
    <li><?php _e('Verification of a registration.', 'ipt_rpm'); ?> <a href="admin.php?page=ipt_rpm_menu_view_reg"><?php _e('Click Here', 'ipt_rpm') ?>.</a></li>
</ul>
                <?php
                break;

        }
    }


    /*_______________METABOX CB____________________*/
    public function meta_system_info() {
        global $wpdb, $ipt_rpm_info;
        $portal_in = implode(',', $this->reg_helper->portals);
        $registrant = array(
            'total' => $wpdb->get_var("SELECT COUNT(id) FROM {$ipt_rpm_info['reg_table']} WHERE portal IN ({$portal_in})"),
            'paid' => $wpdb->get_var("SELECT COUNT(id) FROM {$ipt_rpm_info['reg_table']} WHERE status = 1 AND portal IN ({$portal_in})"),
            'unpaid' => $wpdb->get_var("SELECT COUNT(id) FROM {$ipt_rpm_info['reg_table']} WHERE status = 0 AND portal IN ({$portal_in})"),
        );

        $fees = array(
            'total' => $wpdb->get_var("SELECT SUM(fees) FROM {$ipt_rpm_info['reg_table']} WHERE portal IN ({$portal_in})"),
            'paid' => $wpdb->get_var("SELECT SUM(fees) FROM {$ipt_rpm_info['reg_table']} WHERE status = 1 AND portal IN ({$portal_in})"),
            'unpaid' => $wpdb->get_var("SELECT SUM(fees) FROM {$ipt_rpm_info['reg_table']} WHERE status = 0 AND portal IN ({$portal_in})"),
        );
        $total_portals = count($this->reg_helper->portals);
        $portal_links = array();
        foreach($this->reg_helper->portals_options as $portal => $label) {
            $portal_links[] = '<a href="admin.php?page=ipt_rpm_menu_view_all&portal_id=' . $portal . '">' . $label . '</a>';
        }
        ?>
<div class="table table_content">
    <p class="sub"><?php _e('Registrants', 'ipt_rpm'); ?></p>
    <table>
        <tbody>
            <tr class="first">
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all"><?php echo $registrant['total']; ?></a>
                </td>
                <td class="t">
                    <a href="admin.php?page=ipt_rpm_menu_view_all"><?php _e('Total', 'ipt_rpm'); ?></a>
                </td>
            </tr>
            <tr>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all&status=paid"><?php echo $registrant['paid']; ?></a>
                </td>
                <td class="t">
                    <a class="approved" href="admin.php?page=ipt_rpm_menu_view_all&status=paid"><?php _e('Paid', 'ipt_rpm') ?></a>
                </td>
            </tr>
            <tr>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all&status=unpaid"><?php echo $registrant['unpaid']; ?></a>
                </td>
                <td class="t">
                    <a class="waiting" href="admin.php?page=ipt_rpm_menu_view_all&status=unpaid"><?php _e('Unpaid', 'ipt_rpm') ?></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="table table_content right">
    <p class="sub"><?php _e('Fees', 'ipt_rpm'); ?></p>
    <table>
        <tbody>
            <tr class="first">
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all"><?php echo $this->reg_helper->settings['currency']; ?></a>
                </td>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all"><?php echo number_format($fees['total'], 2); ?></a>
                </td>
                <td class="t">
                    <a href="admin.php?page=ipt_rpm_menu_view_all"><?php _e('Total', 'ipt_rpm'); ?></a>
                </td>
            </tr>
            <tr>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all&status=1"><?php echo $this->reg_helper->settings['currency']; ?></a>
                </td>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all&status=1"><?php echo number_format($fees['paid'], 2); ?></a>
                </td>
                <td class="t">
                    <a class="approved" href="admin.php?page=ipt_rpm_menu_view_all&status=1"><?php _e('Paid', 'ipt_rpm') ?></a>
                </td>
            </tr>
            <tr>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all&status=0"><?php echo $this->reg_helper->settings['currency']; ?></a>
                </td>
                <td class="b">
                    <a href="admin.php?page=ipt_rpm_menu_view_all&status=0"><?php echo number_format($fees['unpaid'], 2); ?></a>
                </td>
                <td class="t">
                    <a class="waiting" href="admin.php?page=ipt_rpm_menu_view_all&status=0"><?php _e('Unpaid', 'ipt_rpm') ?></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="clear"></div>
<?php if(count($this->reg_helper->portals)) : ?>
<div class="p-message">
    <p>
        <?php printf(__('You have access to %d %s: %s', 'ipt_rpm'), $total_portals, _n('portal', 'portals', $total_portals, 'ipt_rpm'), implode(' | ', $portal_links)); ?>
    </p>
</div>
<?php else : ?>
<?php $this->print_p_error(__('You do not have access to any of the portals yet. Please ask your administrator to gain access.', 'ipt_rpm')); ?>
<?php endif; ?>
        <?php
    }
    public function meta_thank_you() {
        global ${$this->abbr . '_info'};
        $loader_class_name = $this->abbr . '_loader';
        ?>
<p>
    <?php _e('Thank you for using Registration Portal Management Plugin. I\'ve made this plugin keeping the priority of our college fest in mind. But I would like to listen to your suggestions to improve it.', 'ipt_rpm'); ?>
</p>
<ul class="ul-square">
    <li><?php _e('<strong>Plugin Author</strong>: <a href="http://www.intechgrity.com">Swashata</a>', 'ipt_rpm'); ?></li>
    <li><?php _e('<strong>Documentation</strong>: <a href="http://www.intechgrity.com/wp-plugins/registration-portal-management/">Click Here</a>', 'ipt_rpm'); ?></li>
    <li><?php _e('<strong>Hire Us</strong>: <a href="mailto:swashata@intechgrity.com">Get in Touch</a>', 'ipt_rpm'); ?></li>
</ul>
<div class="p-message">
    <p>
        <span style="float: right">
            <span class="help">
                <?php _e('Both the versions should be same, the first one says the version which the script is running, the second one says the version stored in database. If they fail to match, then please just deactivate and activate the plugin. If it persists, then contact the developer.', 'ipt_rpm'); ?>
            </span>
        </span>
        <?php printf(__('<strong>Plugin Version:</strong> <em>%s</em> (Script Version)/<em>%s</em> (Database Version)', 'ipt_rpm'), $loader_class_name::$version, ${$this->abbr . '_info'}['version']); ?>
    </p>
</div>
        <?php
    }

    public function meta_social() {
        ?>
<div class="p-message">
    <p style="text-align: center;">
        <a href="http://www.intechgrity.com/about/buy-us-some-beer/" target="_blank" title="Buy us some beer? Thank you!"><img src="<?php echo $this->url['images'] . 'donate.png' ?>" alt="Donate" /></a>
    </p>
</div>
<p style="float: left; text-align: center">
    <a href="http://www.facebook.com/swashata" target="_blank" title="Wanna be my friend? Go ahead... :-)"><img src="<?php echo $this->url['images'] . 'facebook_add.png' ?>" alt="Facebook Friends" /></a><br />
    <a href="http://www.facebook.com/intechgrity" target="_blank" title="Be our Facebook Page Fan? Thanks again :)"><img alt="Facebook FanPage" src="<?php echo $this->url['images'] . 'facebook_follow.png'; ?>" /></a>
</p>
<p style="float: right; text-align: center">
    <a href="http://www.twitter.com/swashata" target="_blank" title="Follow my tweets!"><img src="<?php echo $this->url['images'] . 'twitter_follow.png' ?>" alt="Twitter Follow" /></a><br />
</p>

<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fintechgrity&amp;width=450&amp;height=170&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false&amp;appId=124472621612" scrolling="no" frameborder="0" style="display: block; border:none; overflow:hidden; width:450px; height:170px; margin: 0 auto" allowTransparency="true"></iframe>
<p style="text-align: center; margin: 2.5px auto 0">
    <?php _e('Icons Used from: ', 'ipt_rpm'); ?> <a href="http://twitterbuttons.sociableblog.com/" target="_blank">SociableBlog</a> | <a href="http://www.happyiconstudio.com/free-mobile-icon-kit.htm" target="_blank">Free Mobile Icon Kit</a> | <a href="http://www.iconshock.com/android-icons/" target="_blank">Android Icons</a>
</p>
        <?php
    }

    public function meta_stats() {
        if(!count($this->reg_helper->portals)) {
            $this->print_p_error(__('You do not have access to any of the portals yet. Please ask your administrator to gain access.', 'ipt_rpm'));
            return;
        }
        global $wpdb, $ipt_rpm_info;

        $info = array();
        foreach($this->reg_helper->portals as $portal) {
            $info[$portal] = array(
                'registration' => $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM {$ipt_rpm_info['reg_table']} WHERE portal = %d", $portal)),
                'paid' => $wpdb->get_var($wpdb->prepare("SELECT SUM(fees) FROM {$ipt_rpm_info['reg_table']} WHERE portal = %d and status = 1", $portal)),
                'unpaid' => $wpdb->get_var($wpdb->prepare("SELECT SUM(fees) FROM {$ipt_rpm_info['reg_table']} WHERE portal = %d and status = 0", $portal)),
            );
        }

        $json = array();
        $json[0] = array(
            __('Portal', 'ipt_rpm'), __('Paid Fees', 'ipt_rpm'), __('Unpaid Fees', 'ipt_rpm'), __('Total Fees', 'ipt_rpm'), __('Registrations', 'ipt_rpm'),
        );
        foreach($this->reg_helper->portals_options as $key => $portal) {
            $json[] = array(
                $portal, (float) $info[$key]['paid'], (float) $info[$key]['unpaid'], (float) ($info[$key]['paid'] + $info[$key]['unpaid']), (int) $info[$key]['registration']
            );
        }

        ?>
<div id="ipt_rpm_dashboard_chart">
    <img src="<?php echo $this->url['images'] . 'ajax.gif' ?>" style="margin: 10px auto; display: block;" />
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1.0', {'packages':['corechart', 'motionchart']});
</script>
<script type="text/javascript">
function drawDashboardChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($json); ?>);

    var ac = new google.visualization.ComboChart(document.getElementById('ipt_rpm_dashboard_chart'));
    ac.draw(data, {
        title : '<?php _e('Statistics per portal', 'fbsr'); ?>',
        height : 300,
        vAxis : {title : '<?php _e('Amount', 'fbsr') ?>'},
        hAxis : {title : '<?php _e('Portals', 'fbsr'); ?>'},
        seriesType : 'bars',
        colors : ['green', 'red', '#cccc00', 'blue'],
        series : {3 : {type : 'line'}}
    });
}
google.setOnLoadCallback(drawDashboardChart);
</script>
        <?php
    }
}

class ipt_rpm_admin_new_reg extends ipt_rpm_admin_base {
    public $reg_helper;
    public function __construct($abbr = '') {
        $this->capability = 'ipt_rpm_new_reg';
        $this->action_nonce = 'ipt_rpm_new_reg_nonce';

        parent::__construct($abbr);

        $this->icon_url = $this->url['images'] . 'new_reg.png';
        $this->is_metabox = false;
        $this->reg_helper = new ipt_rpm_registration_helper();
        add_action('wp_ajax_ipt_rpm_reg_code_ajax', array($this->reg_helper, 'ajax_validate'));
        add_action('wp_ajax_ipt_rpm_gen_reg_code', array(&$this->reg_helper, 'ajax_gen_code'));
        add_action('wp_ajax_ipt_rpm_new_reg_nonce_post_action', array($this->reg_helper, 'save'), 10, 0);
    }

    public function admin_menu() {
        $this->pagehook = add_submenu_page('ipt_rpm_menu_dashboard', __('New Registration', 'ipt_rpm'), __('New Registration', 'ipt_rpm'), $this->capability, 'ipt_rpm_menu_new_reg', array(&$this, 'index'));
        parent::admin_menu();
    }

    public function index() {
        $this->index_head(__('Registration Portal Management &raquo; New Registration', 'ipt_rpm'));
        $submit = $this->reg_helper->show_form();
        $show_submit = true;
        if(false === $submit)
            $show_submit = false;
        $this->index_foot($show_submit, __('Add Registration', 'ipt_rpm'));
    }

    public function save_post() {
        parent::save_post();

        $save_result = $this->reg_helper->save(false);

        if($save_result['status'] == true) {
            wp_redirect(add_query_arg(array('post_result' => 1), $_POST['_wp_http_referer']));
        } else {
            wp_redirect(add_query_arg(array('post_result' => 2), $_POST['_wp_http_referer']));
        }
        //save the action submit

        //redirect

        die();
    }

    public function on_load_page() {
        parent::on_load_page();

        $this->reg_helper->enqueue();
        //add help
        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-reg-overview',
            'title' => __('Overview', 'ipt_rpm'),
            'content' => '',
            'callback' => array(&$this, 'help_text'),
        ));
        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-reg-info',
            'title' => __('Registrant\'s Information', 'ipt_rpm'),
            'content' => '',
            'callback' => array(&$this, 'help_text'),
        ));
        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-subscription',
            'title' => __('Subscriptions', 'ipt_rpm'),
            'content' => '',
            'callback' => array(&$this, 'help_text'),
        ));
        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-registrar-info',
            'title' => __('Registrar\'s Information', 'ipt_rpm'),
            'content' => '',
            'callback' => array(&$this, 'help_text'),
        ));

        get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Documentation</a>', 'ipt_rpm'), ipt_rpm_loader::$documentation) . '</p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Support Forums</a>', 'ipt_rpm'), ipt_rpm_loader::$support_forum) . '</p>'
	);
    }

    public function help_text($obj, $tab) {
        switch($tab['id']) {
            case 'ipt_rpm-reg-overview' :
                ?>
<p><?php _e('This page is used to add new registration entry to the database. The form itself is pretty self explainatory. But you can explore the different help tabs to learn more.', 'ipt_rpm'); ?></p>
<?php $this->print_p_error(__('If you are not an administrator, then once you add a registration information, you can not delete or downgrade it. Please be careful while adding an entry.', 'ipt_rpm')); ?>
                <?php
                break;
            case 'ipt_rpm-reg-info' :
                ?>
<p><?php _e('While filling out the Registrant\'s Information, you should take a note of the followings:', 'ipt_rpm'); ?></p>
<ul>
    <li><strong><?php _e('Registration Code:', 'ipt_rpm'); ?></strong> <?php _e('You can simply click on the Generate button to generate the next available number for the selected portal. You can also enter manual number in which case, it will automatically validate.', 'ipt_rpm'); ?></li>
    <li><strong><?php _e('Other Fields:', 'ipt_rpm'); ?></strong> <?php _e('Please fill in the other fields accordingly. If you are wrong at any point, then the system will show you a warning.', 'ipt_rpm'); ?></li>
</ul>
                <?php
                break;
            case 'ipt_rpm-subscription' :
                ?>
<p><?php _e('Here you need to select the events the registrant wishes to subscribe for.', 'ipt_rpm'); ?></p>
<ul>
    <li><strong><?php _e('Subscription and Fees:', 'ipt_rpm') ?></strong> <?php _e('As you select more subscriptions, the invoice will automatically add up the corresponding subscription fees. It is shown at the bottom right corner of the table.', 'ipt_rpm') ?></li>
    <li><strong><?php _e('Payment Status:', 'ipt_rpm') ?></strong> <?php _e('You can set the payment status from the bottom left of the table.', 'ipt_rpm'); ?></li>
</ul>
                <?php
                break;
            case 'ipt_rpm-registrar-info' :
                ?>
<p><?php _e('From here, you can basically select from your available portals.', 'ipt_rpm'); ?></p>
<p><?php _e('Changing your portal will also require revalidating the registration code.', 'ipt_rpm'); ?></p>
                <?php
                break;
        }
    }
}

class ipt_rpm_admin_view_all extends ipt_rpm_admin_base {
    var $reg_helper;
    var $table_helper;
    public function __construct($abbr = '') {
        $this->capability = 'ipt_rpm_view_all';
        $this->action_nonce = 'ipt_rpm_view_all_nonce';

        parent::__construct($abbr);

        $this->icon_url = $this->url['images'] . 'view_all.png';
        $this->is_metabox = false;

        $id = null;
        if(!empty($_GET['item_id']))
            $id = $_GET['item_id'];
        $this->reg_helper = new ipt_rpm_registration_helper($id);
        $this->post_result[4] = array(
            'type' => 'update',
            'msg' => __('Successfully deleted the registration.', 'ipt_rpm'),
        );
        $this->post_result[5] = array(
            'type' => 'error',
            'msg' => __('Please select some registration for bulk action.', 'ipt_rpm'),
        );
        $this->post_result[6] = array(
            'type' => 'update',
            'msg' => __('Successfully deleted the registrations.', 'ipt_rpm'),
        );
        $this->post_result[7] = array(
            'type' => 'update',
            'msg' => __('Successfully marked the registrations paid.', 'ipt_rpm'),
        );
        $this->post_result[8] = array(
            'type' => 'update',
            'msg' => __('Successfully marked the registrations unpaid.', 'ipt_rpm'),
        );
        $this->post_result[9] = array(
            'type' => 'update',
            'msg' => __('Successfully updated the registration.', 'ipt_rpm'),
        );
        $this->post_result[10] = array(
            'type' => 'update',
            'msg' => __('Some error had occured. Could not update the registration.', 'ipt_rpm'),
        );

        add_filter('set-screen-option', array(&$this, 'table_set_option'), 10, 3);
        add_action('wp_ajax_ipt_rpm_view_all_nonce_post_action', array($this->reg_helper, 'save'), 10, 0);
    }

    public function table_set_option($status, $option, $value) {
        if('iptrpm_per_page' == $option)
            return $value;
    }

    public function admin_menu() {
        $title = __('View or Renew', 'ipt_rpm');
        if(current_user_can('ipt_rpm_settings')) {
            $title = __('View or Edit', 'ipt_rpm');
        }
        $this->pagehook = add_submenu_page('ipt_rpm_menu_dashboard', __('View Edit or Renew', 'ipt_rpm'), $title, $this->capability, 'ipt_rpm_menu_view_all', array(&$this, 'index'));
        parent::admin_menu();
    }

    public function index() {
        if(empty($_GET['item_id'])) {
            //table
            $this->index_head(__('Registration Portal Management &raquo; View All Registrations', 'ipt_rpm'), false);
            $this->table_helper->prepare_items();
            ?>
<form action="" method="get">
    <?php foreach($_GET as $k => $v) : if($k == 'order' || $k == 'orderby' || $k == 'page') : ?>
    <input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
    <?php endif; endforeach; ?>
    <?php $this->table_helper->search_box(__('Search Submissions', 'fbsr'), 'search_id'); ?>
    <?php $this->table_helper->display(); ?>
</form>
            <?php
            $this->index_foot();
        } else {
            //edit form
            $this->index_head(__('Registration Portal Management &raquo; ', 'ipt_rpm') . (current_user_can('ipt_rpm_settings')? __('Edit', 'ipt_rpm') : __('Renew', 'ipt_rpm')));
            $this->reg_helper->show_form();
            $this->index_foot();
        }
    }

    public function save_post() {
        parent::save_post();

        //save the action submit
        if($this->reg_helper->save(false)) {
            wp_redirect(add_query_arg(array('post_result' => 9), 'admin.php?page=ipt_rpm_menu_view_all'));
        } else {
            wp_redirect(add_query_arg(array('post_result' => 10), 'admin.php?page=ipt_rpm_menu_view_all'));
        }
        die();
    }

    public function on_load_page() {
        global $wpdb, $ipt_rpm_info;

        //List Table Stuff
        if(empty($_GET['item_id'])) {

            //view table
            $this->table_helper = new ipt_rpm_view_all_table();

            add_screen_option('per_page', array(
                'label' => __('Registration Rows', 'ipt_rpm'),
                'default' => 20,
                'option' => 'iptrpm_per_page',
            ));

            //get action
            $action = $this->table_helper->current_action();

            //single delete request
            if($action == 'delete' && !empty($_GET['reg_id'])) {
                if(!current_user_can('ipt_rpm_settings')) {
                    wp_die(__('Cheatin&#8217; uh?'));
                }
                $wpdb->query($wpdb->prepare("DELETE FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $_GET['reg_id']));
                wp_redirect(add_query_arg(array('post_result' => 4), 'admin.php?page=' . $_GET['page']));
                die();
            } else if(false !== $action) { //bulk actions

                if(!wp_verify_nonce($_GET['_wpnonce'], 'bulk-ipt_rpm_view_all_items')) {
                    wp_die(__('Cheatin&#8217; uh?'));
                }

                if(empty($_GET['registrations'])) {
                    wp_redirect(add_query_arg(array('post_result' => 5), $_GET['_wp_http_referer']));
                    die();
                }

                $registrations = implode(',', $_GET['registrations']);

                switch($action) {
                    case 'delete' :
                        if(!current_user_can('ipt_rpm_settings')) {
                            wp_die(__('Cheatin&#8217; uh?'));
                        }
                        $wpdb->query("DELETE FROM {$ipt_rpm_info['reg_table']} WHERE id IN ({$registrations})");
                        wp_redirect(add_query_arg(array('post_result' => 6), $_GET['_wp_http_referer']));
                        break;
                    case 'paid' :
                        $wpdb->query("UPDATE {$ipt_rpm_info['reg_table']} SET status = 1 WHERE id IN ({$registrations})");
                        wp_redirect(add_query_arg(array('post_result' => 7), $_GET['_wp_http_referer']));
                        break;
                    case 'unpaid' :
                        $wpdb->query("UPDATE {$ipt_rpm_info['reg_table']} SET status = 0 WHERE id IN ({$registrations})");
                        wp_redirect(add_query_arg(array('post_result' => 8), $_GET['_wp_http_referer']));
                        break;
                    default :
                        wp_die(__('Cheatin&#8217; uh?'));
                }
                die();
            }
        } else {
            $this->reg_helper->enqueue();
        }


        //clean up the URL
        if(!empty($_GET['_wp_http_referer'])) {
            wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce')));
            die();
        }

        parent::on_load_page();

        //add help
        get_current_screen()->add_help_tab(array(
            'id' => 'global-options',
            'title' => __('Actions', 'ipt_rpm'),
            'content' =>
                '<p>' . __('From here view or edit or renew your registration entries.', 'ipt_rpm') . '</p>' .
                '<ul>' .
                        '<li>' . __('<strong>View:</strong> Simply clicking on the name or the View button will show up the details of the registration.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Edit:</strong> If you are an administrator, then the system will show you an edit button which you can use to edit the registration entry. You can upgrade/degrade the entry and can also change portals and/or registration ID.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Renew:</strong> If you are not an administrator, then the system will only let you renew the entry. In this case, you can only add more subscriptions to the existing entry. You can not degrade or change the portal or reistration ID.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Delete :</strong> Simply delete the entry.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Bulk Action:</strong> You can bulk delete, mark paid or mark unpaid the entries.', 'ipt_rpm') . '</li>' .
                '</ul>',
        ));

        get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Documentation</a>', 'ipt_rpm'), ipt_rpm_loader::$documentation) . '</p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Support Forums</a>', 'ipt_rpm'), ipt_rpm_loader::$support_forum) . '</p>'
	);
    }
}

class ipt_rpm_admin_view_reg extends ipt_rpm_admin_base {
    var $reg_helper;
    public function __construct($abbr = '') {
        $this->capability = 'ipt_rpm_view_reg';
        $this->action_nonce = 'ipt_rpm_view_reg_nonce';

        parent::__construct($abbr);

        $this->icon_url = $this->url['images'] . 'view_reg.png';
        $this->is_metabox = false;

        global $wpdb, $ipt_rpm_info;

        if(isset($_GET['code']) && isset($_GET['portal'])) {
            $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$ipt_rpm_info['reg_table']} WHERE code = %d AND portal = %d", $_GET['code'], $_GET['portal']));
            $this->reg_helper = new ipt_rpm_registration_helper($id);
        } else {
            $this->reg_helper = new ipt_rpm_registration_helper();
        }

    }

    public function admin_menu() {
        $this->pagehook = add_submenu_page('ipt_rpm_menu_dashboard', __('View a Registration', 'ipt_rpm'), __('View a Registration', 'ipt_rpm'), $this->capability, 'ipt_rpm_menu_view_reg', array(&$this, 'index'));
        parent::admin_menu();
    }

    public function index() {
        $this->index_head(__('Registration Portal Management &raquo; View Registration', 'ipt_rpm'), false);
        if(isset($_GET['code']) && isset($_GET['portal'])) {
            $this->reg_helper->show_data();
        } else {
            $this->show_form();
        }
        $this->index_foot();
    }

    public function show_form() {
        ?>
<form action="" method="get">
    <?php foreach($_GET as $key => $val) : ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo esc_attr($val); ?>" />
    <?php endforeach; ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="portal"><?php _e('Select Portal', 'ipt_rpm'); ?></label></th>
                <td>
                    <select name="portal" id="portal">
                        <?php foreach($this->reg_helper->settings['portals'] as $p_key => $portal) : ?>
                        <option value="<?php echo $p_key; ?>"><?php echo $portal['name']; ?> - [<?php echo $portal['prefix']; ?>XXXX]</option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <span class="help">
                        <?php _e('Usually the portal prefix is printed on the registration card and/or invoice. Match the prefix to select the particular portal.') ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="code"><?php _e('Registration Code:', 'ipt_rpm'); ?></label><br /><span class="description"><?php _e('Without any prefix.', 'ipt_rpm'); ?></span></th>
                <td>
                    <?php $this->print_input_text('code', '', 'regular-text code'); ?>
                </td>
                <td>
                    <span class="help">
                        <?php _e('Enter just the registration code here, without the portal prefix. This should just be a number.', 'ipt_rpm'); ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('View the Registration Entry', 'ipt_rpm'); ?>" />
    </p>
</form>
        <?php
    }

    public function save_post() {
        parent::save_post();

        //save the action submit

        //redirect
        wp_redirect(add_query_arg(array('post_result' => 1), $_POST['_wp_http_referer']));
        die();
    }

    public function on_load_page() {
        parent::on_load_page();

        //add help
        get_current_screen()->add_help_tab(array(
            'id' => 'global-overview',
            'title' => __('Overview', 'ipt_rpm'),
            'content' =>
                '<p>' . __('View a particular registration, if you happen to know it\'s portal and registration code.', 'ipt_rpm') . '</p>' .
                '<p>' . __('You do not need permission to access the portal. This is used just for previewing the registration entry.', 'ipt_rpm') . '</p>' .
                '<p>' . __('You can get more help by clicking the [?] icon beside every options.', 'ipt_rpm') . '</p>'
        ));

        get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Documentation</a>', 'ipt_rpm'), ipt_rpm_loader::$documentation) . '</p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Support Forums</a>', 'ipt_rpm'), ipt_rpm_loader::$support_forum) . '</p>'
	);
    }
}

class ipt_rpm_admin_gen_report extends ipt_rpm_admin_base {
    var $reg_helper;
    public function __construct($abbr = '') {
        $this->capability = 'ipt_rpm_gen_report';
        $this->action_nonce = 'ipt_rpm_gen_report_nonce';

        parent::__construct($abbr);

        $this->icon_url = $this->url['images'] . 'report.png';
        $this->is_metabox = false;
        $this->reg_helper = new ipt_rpm_registration_helper();
        add_action('wp_ajax_ipt_rpm_ajax_action', array(&$this, 'ajax_stat'));
    }

    public function admin_menu() {
        $this->pagehook = add_submenu_page('ipt_rpm_menu_dashboard', __('Generate Report', 'ipt_rpm'), __('Generate Report', 'ipt_rpm'), $this->capability, 'ipt_rpm_menu_gen_report', array(&$this, 'index'));
        parent::admin_menu();
    }

    public function index() {
        $this->index_head(__('Registration Portal Management &raquo; Generate Report', 'ipt_rpm'), false);
        if(isset($_GET['report_init'])) {
            $this->show_report();
        } else {
            $this->show_form();
        }
        $this->index_foot();
    }

    public function ajax_stat() {
        if(!wp_verify_nonce($_POST['_wpnonce'], 'ipt_rpm_stat_ajax')) {
            wp_die(__('Cheatin&#8217; uh?'));
        }
        wp_create_nonce('ipt_rpm_ajax_action');
        global $wpdb, $ipt_rpm_info;
        $post_data = $this->post['post_data'];

        $info = new stdClass();
        $query = "SELECT reg_data, portal, status FROM {$ipt_rpm_info['reg_table']} WHERE portal IN(" . implode(',', $post_data['portals']) . ")";
        if($post_data['custom_date'] == 'true') {
            $query .= " AND created >= '" . date('Y-m-d H:i:s', strtotime($post_data['start_date'])) . "' AND created <= '" . date('Y-m-d H:i:s', strtotime($post_data['end_date'])) . "'";
        }

        $query .= " ORDER BY created DESC LIMIT " . ($this->post['hit'] * $post_data['per_hit']) . ", " . $post_data['per_hit'];

        $results = $wpdb->get_results($query);
        foreach($results as $result) {
            $portal_key = $result->portal;
            if(!isset($info->$portal_key)) {
                $info->$portal_key = new stdClass();
            }
            $reg_data = maybe_unserialize($result->reg_data);
            foreach($reg_data as $reg_key => $reg) {
                if(in_array((string) $reg_key, $post_data['registrations'])) {
                    if(!isset($info->$portal_key->$reg_key)) {
                        $info->$portal_key->$reg_key = new stdClass();
                        $info->$portal_key->$reg_key->paid = 0;
                        $info->$portal_key->$reg_key->unpaid = 0;
                    }

                    if($result->status == 0) {
                        $info->$portal_key->$reg_key->unpaid++;
                    } else {
                        $info->$portal_key->$reg_key->paid++;
                    }
                }
            }
        }

        $info->query = $query;

        echo json_encode($info);
        die();
    }

    public function show_report() {
        if(!count($this->reg_helper->portals)) {
            $this->print_p_error(__('You do not have access to any of the portals yet. Please ask your administrator to gain access.', 'ipt_rpm'));
            return;
        }
        global $wpdb, $ipt_rpm_info;
        $js_info = array();

        //properly set portals
        if(empty($_GET['portals'])) {
            $this->print_p_error(__('You did not select any of the portals. All the portals available to you have been selected.', 'ipt_rpm'));
            $_GET['portals'] = $this->reg_helper->portals;
        }
        //get currency
        $js_info['currency'] = $this->reg_helper->settings['currency'];

        //get registration data
        $js_info['reg_data'] = array();
        $js_info['reg_name'] = array();
        foreach($this->reg_helper->regs as $reg) {
            $js_info['reg_data'][] = (float) $reg['fee'];
            $js_info['reg_name'][] = $reg['name'];
        }

        //get portals
        $js_info['portals'] = array();
        foreach((array) $_GET['portals'] as $portal) {
            if(!in_array((int) $portal, $this->reg_helper->portals)) {
                $this->print_p_error(__('You do not have permission to view one of the selected portals. Are you trying to cheat?', 'ipt_rpm'));
                return;
            }
            $js_info['portals'][] = (int) $portal;
        }

        //get requested registrations
        if(empty($_GET['registrations'])) {
            foreach($this->reg_helper->regs as $reg_key => $data) {
                $_GET['registrations'][] = $reg_key;
            }
            $this->print_p_error(__('You did not select any of the registrations. All of the available registration topics have been selected.', 'ipt_rpm'));
        }
        $js_info['registrations'] = array();
        foreach($_GET['registrations'] as $reg) {
            $js_info['registrations'][] = (int) $reg;
        }

        $post_data = array();
        $post_data['custom_date'] = isset($_GET['custom_date']) ? true : false;
        $post_data['start_date'] = $_GET['start_date'];
        $post_data['end_date'] = $_GET['end_date'];
        $post_data['portals'] = $js_info['portals'];
        $post_data['registrations'] = $js_info['registrations'];
        $post_data['per_hit'] = 25;
        $c_query = "SELECT COUNT(id) FROM {$ipt_rpm_info['reg_table']} WHERE portal IN (" . implode(',', $post_data['portals']) . ")";
        if($post_data['custom_date'] == 'true') {
            $c_query .= " AND created >= '" . date('Y-m-d H:i:s', strtotime($post_data['start_date'])) . "' AND created <= '" . date('Y-m-d H:i:s', strtotime($post_data['end_date'])) . "'";
        }
        //var_dump($c_query);
        $post_data['counts'] = $wpdb->get_var($c_query);
        $post_data['total_hits'] = ceil($post_data['counts']/$post_data['per_hit']);

        //var_dump($post_data, date('Y-m-d H:i:s', strtotime($post_data['start_date'])));

        if($post_data['total_hits'] == 0) {
            $this->print_p_error(__('No registrations yet for the selected portals. Please be patient.', 'ipt_rpm'));
            return;
        }

        //thats it. Now prepare the HTML
        ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1.0', {'packages':['corechart']});
</script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
        var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
        return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
    };

    $('#ipt_rpm_pgbar').progressbar();
    $('#ipt_rpm_percent').html('0%');

    var js_info = <?php echo json_encode((object) $js_info); ?>;
    var post_data = <?php echo json_encode((object) $post_data); ?>;

    if(post_data.total_hits == 0) {
        alert('No data found in the database.');
        return;
    }

    var _wpnonce = '<?php echo wp_create_nonce('ipt_rpm_stat_ajax'); ?>';

    var store_data = new Object();

    for(var portal in js_info.portals) {
        var portal_key = js_info.portals[portal].toString();
        //alert(portal_key);
        store_data[portal_key] = new Object();

        for(var registration in js_info.registrations) {
            var registration_key = js_info.registrations[registration].toString();
            store_data[portal_key][registration_key] = new Object();
            store_data[portal_key][registration_key]['unpaid'] = 0;
            store_data[portal_key][registration_key]['paid'] = 0;
        }
    }

    var get_report = function(hit) {
        var data = {
            action : 'ipt_rpm_ajax_action',
            hit : hit,
            post_data : post_data,
            _wpnonce : _wpnonce
        };

        $.post(ajaxurl, data, function(obj) {
            //get data from obj
            for(var portal in obj) {
                if(portal == 'query') {
                    continue;
                }
                //alert($.param(obj));
                /*if(store_data[portal] == undefined) {
                    store_data[portal] = new Object();
                }*/

                for(var registration in obj[portal]) {
                    /*if(store_data[portal][registration] == undefined) {
                        store_data[portal][registration] = new Object();
                        store_data[portal][registration]['paid'] = 0;
                        store_data[portal][registration]['unpaid'] = 0;
                    }*/
                    store_data[portal][registration]['paid'] += parseInt(obj[portal][registration]['paid']);
                    store_data[portal][registration]['unpaid'] += parseInt(obj[portal][registration]['unpaid']);
                    //alert('Portal ' + portal + ' Paid ' + obj[portal][registration]['paid']);
                }
            }
            //update the progressbar
            var progress_percent = Math.round(100 * (hit + 1) / post_data.total_hits);
            if(progress_percent > 100) {
                progress_percent = 100;
            }
            $('#ipt_rpm_pgbar').progressbar('value', progress_percent);
            $('#ipt_rpm_percent').html(progress_percent + '%');

            //either recurse or populate the data
            if((hit + 1) != post_data.total_hits) {
                get_report(hit+1);
            } else {
                $('#ipt_rpm_stat_ajax').hide();
                $('#ipt_rpm_stat_report').show();

                //alert($.param(store_data));
                //populate
                var total = new Object();
                var currency = js_info.currency;
                for(var reg in js_info.registrations) {
                    total[js_info.registrations[reg]] = new Object();
                    total[js_info.registrations[reg]]['paid'] = 0;
                    total[js_info.registrations[reg]]['unpaid'] = 0;
                    //alert(js_info['reg_name'][js_info.registrations[reg]]);
                }
                for(var portal in store_data) {
                    var subtotal = new Object();
                    subtotal['status'] = new Object();
                    subtotal['fees'] = new Object();
                    subtotal['status']['paid'] = 0;
                    subtotal['status']['unpaid'] = 0;
                    subtotal['fees']['paid'] = 0;
                    subtotal['fees']['unpaid'] = 0;

                    //add the visulation
                    var data_column = new Array();
                    data_column[0] = ['<?php _e('Registration Topics', 'ipt_rpm'); ?>', '<?php _e('Paid', 'ipt_rpm'); ?>', '<?php _e('Unpaid', 'ipt_rpm'); ?>'];

                    var data_area = new Array();
                    data_area[0] = ['<?php _e('Fees worth', 'ipt_rpm'); ?>', '<?php _e('Unpaid', 'ipt_rpm'); ?>', '<?php _e('Paid', 'ipt_rpm'); ?>'];

                    for(var reg in store_data[portal]) {
                        var fees = js_info.reg_data[reg];
                        var fees_paid = store_data[portal][reg]['paid'] * fees;
                        var fees_unpaid = store_data[portal][reg]['unpaid'] * fees;

                        //add to variable
                        subtotal['status']['paid'] += store_data[portal][reg]['paid'];
                        subtotal['status']['unpaid'] += store_data[portal][reg]['unpaid'];
                        subtotal['fees']['paid'] += fees_paid;
                        subtotal['fees']['unpaid'] += fees_unpaid;
                        total[reg]['paid'] += store_data[portal][reg]['paid'];
                        total[reg]['unpaid'] += store_data[portal][reg]['unpaid'];
                        //add to DOM
                        $('#ipt_rpm_stat_td_status_paid_reg_' + reg + '_portal_' + portal).html(store_data[portal][reg]['paid']);
                        $('#ipt_rpm_stat_td_status_unpaid_reg_' + reg + '_portal_' + portal).html(store_data[portal][reg]['unpaid']);

                        $('#ipt_rpm_stat_td_netfees_paid_reg_' + reg + '_portal_' + portal).html(currency + '&nbsp;' + fees_paid.formatMoney());
                        $('#ipt_rpm_stat_td_netfees_unpaid_reg_' + reg + '_portal_' + portal).html(currency + '&nbsp;' + fees_unpaid.formatMoney());

                        //add to visualization
                        data_column[data_column.length] = new Array(js_info.reg_name[reg], store_data[portal][reg]['paid'], store_data[portal][reg]['unpaid']);
                        data_area[data_area.length] = new Array(js_info.reg_name[reg], fees_unpaid, fees_paid);
                    }

                    //add the subtotal footer
                    $('#ipt_rpm_stat_subtotal_status_paid_portal_' + portal).html(subtotal['status']['paid']);
                    $('#ipt_rpm_stat_subtotal_status_unpaid_portal_' + portal).html(subtotal['status']['unpaid']);

                    $('#ipt_rpm_stat_subtotal_netfees_paid_portal_' + portal).html(currency + '&nbsp;' + subtotal['fees']['paid'].formatMoney());
                    $('#ipt_rpm_stat_subtotal_netfees_unpaid_portal_' + portal).html(currency + '&nbsp;' + subtotal['fees']['unpaid'].formatMoney());


                    //add the visualization
                    //alert(data_column);
                    if(typeof(google) == 'object' || typeof(google.visualization.ComboChart) == 'function') {
                        new google.visualization.ComboChart(document.getElementById('ipt_rpm_stat_combo_portal_' + portal)).draw(google.visualization.arrayToDataTable(data_column), {
                            title : '<?php _e('Per topic registrations', 'ipt_rpm'); ?>',
                            height : 150,
                            width : '100%',
                            chartArea : '100%',
                            hAxis : {title : '<?php _e('Registrations', 'ipt_rpm'); ?>'},
                            vAxis : {title : '<?php _e('Count', 'ipt_rpm'); ?>'},
                            seriesType : 'bars'
                        });
                    } else {
                        $('#ipt_rpm_stat_combo_portal_' + portal).html('<div class="p-message red"><p>Could not load Google Visualization API</p></div>');
                    }

                    if(typeof(google) == 'object' || typeof(google.visualization.AreaChart) == 'function') {
                        new google.visualization.AreaChart(document.getElementById('ipt_rpm_stat_area_portal_' + portal)).draw(google.visualization.arrayToDataTable(data_area), {
                            title : '<?php _e('Net Fees', 'ipt_rpm'); ?>',
                            height : 150,
                            width : '100%',
                            chartArea : '100%',
                            isStacked : true,
                            hAxis : {title : '<?php _e('Registrations', 'ipt_rpm'); ?>'},
                            vAxis : {title : '<?php _e('Fees', 'ipt_rpm'); ?>'}
                        });
                    } else {
                            $('#ipt_rpm_stat_area_portal_' + portal).html('<div class="p-message red"><p>Could not load Google Visualization API</p></div>');
                    }
                }

                var total_status_paid = 0;
                var total_status_unpaid = 0;
                var total_fees_paid = 0;
                var total_fees_unpaid = 0;
                //add the visulation
                var data_column = new Array();
                data_column[0] = ['<?php _e('Registration Topics', 'ipt_rpm'); ?>', '<?php _e('Paid', 'ipt_rpm'); ?>', '<?php _e('Unpaid', 'ipt_rpm'); ?>'];

                var data_area = new Array();
                data_area[0] = ['<?php _e('Fees worth', 'ipt_rpm'); ?>', '<?php _e('Unpaid', 'ipt_rpm'); ?>', '<?php _e('Paid', 'ipt_rpm'); ?>'];

                for(var reg in total) {
                    var fees = js_info.reg_data[reg];
                    var fees_paid = total[reg]['paid'] * fees;
                    var fees_unpaid = total[reg]['unpaid'] * fees;

                    //add to variable
                    total_status_paid += total[reg]['paid'];
                    total_status_unpaid += total[reg]['unpaid'];
                    total_fees_paid += fees_paid;
                    total_fees_unpaid += fees_unpaid;

                    //add to DOM
                    $('#ipt_rpm_stat_td_status_paid_reg_' + reg + '_total').html(total[reg]['paid']);
                    $('#ipt_rpm_stat_td_status_unpaid_reg_' + reg + '_total').html(total[reg]['unpaid']);

                    $('#ipt_rpm_stat_td_netfees_paid_reg_' + reg + '_total').html(currency + '&nbsp;' + fees_paid.formatMoney());
                    $('#ipt_rpm_stat_td_netfees_unpaid_reg_' + reg + '_total').html(currency + '&nbsp;' + fees_unpaid.formatMoney());

                    data_column[data_column.length] = new Array(js_info.reg_name[reg], total[reg]['paid'], total[reg]['unpaid']);
                    data_area[data_area.length] = new Array(js_info.reg_name[reg], fees_unpaid, fees_paid);
                }

                $('#ipt_rpm_stat_total_status_paid').html(total_status_paid);
                $('#ipt_rpm_stat_total_status_unpaid').html(total_status_unpaid);

                $('#ipt_rpm_stat_total_netfees_paid').html(currency + '&nbsp;' + total_fees_paid.formatMoney());
                $('#ipt_rpm_stat_total_netfees_unpaid').html(currency + '&nbsp;' + total_fees_unpaid.formatMoney());

                if(typeof(google) == 'object' || typeof(google.visualization.ComboChart) == 'function') {
                    new google.visualization.ComboChart(document.getElementById('ipt_rpm_stat_combo_total')).draw(google.visualization.arrayToDataTable(data_column), {
                        title : '<?php _e('Per topic registrations', 'ipt_rpm'); ?>',
                        height : 150,
                        width : '100%',
                        chartArea : '100%',
                        hAxis : {title : '<?php _e('Registrations', 'ipt_rpm'); ?>'},
                        vAxis : {title : '<?php _e('Count', 'ipt_rpm'); ?>'},
                        seriesType : 'bars'
                    });
                } else {
                    $('#ipt_rpm_stat_combo_total').html('<div class="p-message red"><p>Could not load Google Visualization API</p></div>');

                }

                if(typeof(google) == 'object' || typeof(google.visualization.AreaChart) == 'function') {
                    new google.visualization.AreaChart(document.getElementById('ipt_rpm_stat_area_total')).draw(google.visualization.arrayToDataTable(data_area), {
                        title : '<?php _e('Net Fees', 'ipt_rpm'); ?>',
                        height : 150,
                        width : '100%',
                        chartArea : '100%',
                        isStacked : true,
                        hAxis : {title : '<?php _e('Registrations', 'ipt_rpm'); ?>'},
                        vAxis : {title : '<?php _e('Fees', 'ipt_rpm'); ?>'}
                    });
                } else {
                    $('#ipt_rpm_stat_area_total').html('<div class="p-message red"><p>Could not load Google Visualization API</p></div>');
                }
            }
        }, 'json');
    };
    get_report(0);
});
</script>
<noscript><?php _e('You need to enabled JavaScript to view this page', 'ipt_rpm'); ?></noscript>
<div id="ipt_rpm_pgbar" style="height: 25px;position: relative">
    <div id="ipt_rpm_percent" style="font-weight:bold;text-align:center;position:absolute;left:50%;top:50%;width:50px;margin-left:-25px;height:25px;margin-top:-9px"></div>
</div>
<div class="p-message" id="ipt_rpm_stat_ajax">
    <p><img style="display: block; margin: 10px auto;" src="<?php echo $this->url['images'] . 'ajax.gif'; ?>" /></p>
</div>
<div id="ipt_rpm_stat_report" style="display: none">
    <?php foreach($js_info['portals'] as $portal) : ?>
    <h3><?php echo $this->reg_helper->portals_options[$portal]; ?></h3>

    <table class="widefat">
        <thead>
            <tr>
                <th rowspan="2" scope="col"><?php _e('Registrations', 'ipt_rpm'); ?></th>
                <th rowspan="2" scope="col"><?php _e('Fees', 'ipt_rpm'); ?></th>
                <th scope="col" colspan="2"><?php _e('Status', 'ipt_rpm'); ?></th>
                <th scope="col" colspan="2"><?php _e('Net Fees', 'ipt_rpm'); ?></th>
            </tr>
            <tr>
                <th scope="col"><?php _e('Paid', 'ipt_rpm'); ?></th>
                <th scope="col"><?php _e('Unpaid', 'ipt_rpm'); ?></th>
                <th scope="col"><?php _e('Paid', 'ipt_rpm'); ?></th>
                <th scope="col"><?php _e('Unpaid', 'ipt_rpm'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="2"><?php _e('Sub Total', 'ipt_rpm'); ?></th>
                <th id="ipt_rpm_stat_subtotal_status_paid_portal_<?php echo $portal; ?>"></th>
                <th id="ipt_rpm_stat_subtotal_status_unpaid_portal_<?php echo $portal; ?>"></th>
                <th id="ipt_rpm_stat_subtotal_netfees_paid_portal_<?php echo $portal; ?>"></th>
                <th id="ipt_rpm_stat_subtotal_netfees_unpaid_portal_<?php echo $portal; ?>"></th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach($this->reg_helper->regs as $reg_key => $reg) : ?>
            <?php if(!in_array($reg_key, $js_info['registrations'])) continue; ?>
            <tr>
                <th scope="row"><?php echo $reg['name']; ?></th>
                <td><?php echo $this->reg_helper->settings['currency'] . '&nbsp;' . number_format($reg['fee'], 2); ?></td>
                <td id="ipt_rpm_stat_td_status_paid_reg_<?php echo $reg_key; ?>_portal_<?php echo $portal; ?>"></td>
                <td id="ipt_rpm_stat_td_status_unpaid_reg_<?php echo $reg_key; ?>_portal_<?php echo $portal; ?>"></td>
                <td id="ipt_rpm_stat_td_netfees_paid_reg_<?php echo $reg_key; ?>_portal_<?php echo $portal; ?>"></td>
                <td id="ipt_rpm_stat_td_netfees_unpaid_reg_<?php echo $reg_key; ?>_portal_<?php echo $portal; ?>"></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="2">
                    <?php _e('Chart Presentation', 'ipt_rpm'); ?>
                </th>
                <td colspan="2">
                    <div id="ipt_rpm_stat_combo_portal_<?php echo $portal; ?>">
                        <img style="display: block; margin: 10px auto;" src="<?php echo $this->url['images'] . 'ajax.gif'; ?>" />
                    </div>
                </td>
                <td colspan="2">
                    <div id="ipt_rpm_stat_area_portal_<?php echo $portal; ?>">
                        <img style="display: block; margin: 10px auto;" src="<?php echo $this->url['images'] . 'ajax.gif'; ?>" />
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="border-color: #dfdfdf;" />
    <?php endforeach; ?>
    <h3><?php _e('Total Valuation', 'ipt_rpm'); ?></h3>
    <table class="widefat">
        <thead>
            <tr>
                <th rowspan="2" scope="col"><?php _e('Registrations', 'ipt_rpm'); ?></th>
                <th rowspan="2" scope="col"><?php _e('Fees', 'ipt_rpm'); ?></th>
                <th scope="col" colspan="2"><?php _e('Status', 'ipt_rpm'); ?></th>
                <th scope="col" colspan="2"><?php _e('Net Fees', 'ipt_rpm'); ?></th>
            </tr>
            <tr>
                <th scope="col"><?php _e('Paid', 'ipt_rpm'); ?></th>
                <th scope="col"><?php _e('Unpaid', 'ipt_rpm'); ?></th>
                <th scope="col"><?php _e('Paid', 'ipt_rpm'); ?></th>
                <th scope="col"><?php _e('Unpaid', 'ipt_rpm'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="2"><?php _e('Total', 'ipt_rpm'); ?></th>
                <th id="ipt_rpm_stat_total_status_paid"></th>
                <th id="ipt_rpm_stat_total_status_unpaid"></th>
                <th id="ipt_rpm_stat_total_netfees_paid"></th>
                <th id="ipt_rpm_stat_total_netfees_unpaid"></th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach($this->reg_helper->regs as $reg_key => $reg) : ?>
            <?php if(!in_array($reg_key, $js_info['registrations'])) continue; ?>
            <tr>
                <th scope="row"><?php echo $reg['name']; ?></th>
                <td><?php echo $this->reg_helper->settings['currency'] . '&nbsp;' . number_format($reg['fee'], 2); ?></td>
                <td id="ipt_rpm_stat_td_status_paid_reg_<?php echo $reg_key; ?>_total"></td>
                <td id="ipt_rpm_stat_td_status_unpaid_reg_<?php echo $reg_key; ?>_total"></td>
                <td id="ipt_rpm_stat_td_netfees_paid_reg_<?php echo $reg_key; ?>_total"></td>
                <td id="ipt_rpm_stat_td_netfees_unpaid_reg_<?php echo $reg_key; ?>_total"></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="2"><?php _e('Chart Presentation', 'ipt_rpm'); ?></th>
                <td colspan="2">
                    <div id="ipt_rpm_stat_combo_total">
                        <img style="display: block; margin: 10px auto;" src="<?php echo $this->url['images'] . 'ajax.gif'; ?>" />
                    </div>
                </td>
                <td colspan="2">
                    <div id="ipt_rpm_stat_area_total">
                        <img style="display: block; margin: 10px auto;" src="<?php echo $this->url['images'] . 'ajax.gif'; ?>" />
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
        <?php
    }

    public function show_form() {
        if(!count($this->reg_helper->portals)) {
            $this->print_p_error(__('You do not have access to any of the portals yet. Please ask your administrator to gain access.', 'ipt_rpm'));
        }
        $reg_option = array();
        $reg_checked = array();
        foreach($this->reg_helper->regs as $reg_key => $data) {
            $reg_option[] = array(
                'val' => $reg_key,
                'label' => $data['name'],
            );
            $reg_checked[] = $reg_key;
        }
        $portal_option = array();
        $portal_checked = array();
        foreach($this->reg_helper->portals_options as $p_key => $label) {
            $portal_option[] = array(
                'val' => $p_key,
                'label' => $label,
            );
            $portal_checked[] = $p_key;
        }
        ?>
<form method="get" action="">
    <?php foreach($_GET as $key => $val) : ?>
    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>" />
    <?php endforeach; ?>
    <table class="form-table">
        <tbody>
            <tr>
                <td colspan="3">
                    <fieldset class="widefat">
                        <legend>
                            <span class="help" title="<?php _e('Custom Date Range', 'ipt_rpm'); ?>">
                                <?php _e('Tick to enter custom date range for the report.', 'ipt_rpm'); ?>
                            </span>
                            <?php $this->print_checkbox('custom_date', 'true', false); ?>
                            <h4>
                                <label for="custom_date"><?php _e('Custom Date Range', 'ipt_rpm'); ?></label>
                            </h4>
                        </legend>
                        <div class="toggle" style="display: none">
                            <table class="form-table">
                                <tr>
                                    <th scope="col">
                                        <label for="survey_custom_date_start"><?php _e('Start Date:', 'ipt_rpm') ?></label>
                                    </th>
                                    <td>
                                        <?php $this->print_datetimepicker('start_date', ''); ?>
                                    </td>
                                    <td>
                                        <span class="help">
                                            <?php _e('Please select the start date and time, inclusive', 'ipt_rpm'); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="col">
                                        <label for="survey_custom_date_end"><?php _e('End Date:') ?></label>
                                    </th>
                                    <td>
                                        <?php $this->print_datetimepicker('end_date', ''); ?>
                                    </td>
                                    <td>
                                        <span class="help">
                                            <?php _e('Please select the end date and time, inclusive', 'ipt_rpm'); ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th><label for="registrations"><?php _e('Select Registrations', 'ipt_rpm'); ?></label></th>
                <td>
                    <?php $this->print_checkboxes('registrations[]', $reg_option, $reg_checked); ?>
                </td>
                <td>
                    <span class="help">
                        <?php _e('Select all the registration topics you wish to include in the report.', 'ipt_rpm'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th><label for="portals"><?php _e('Select Portals', 'ipt_rpm'); ?></label></th>
                <td>
                    <?php $this->print_checkboxes('portals[]', $portal_option, $portal_checked); ?>
                </td>
                <td>
                    <span class="help">
                        <?php _e('Select all the portals you wish to include in the report.', 'ipt_rpm'); ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Generate Report', 'ipt_rpm'); ?>" name="report_init" />
    </p>
</form>
        <?php
    }

    public function save_post() {
        parent::save_post();

        //save the action submit

        //redirect
        wp_redirect(add_query_arg(array('post_result' => 1), $_POST['_wp_http_referer']));
        die();
    }

    public function on_load_page() {
        parent::on_load_page();

        //add help
        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-overview',
            'title' => __('Overview', 'ipt_rpm'),
            'content' =>
                '<p>' . __('Generate reports of registrations.', 'ipt_rpm') . '</p>' .
                '<ul>' .
                        '<li>' . __('<strong>Custom Date Range</strong> Check this if you wish to generate report between specified date range. Please note that the date is stored when the actual entry is created and is set at the timezone your blog is currently in.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Select Registrations</strong> The registration topics you wish to include in the report.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Select Portals:</strong> Portals you wish to include in the report.', 'ipt_rpm') . '</li>' .
                '</ul>' .
                '<p>' . __('You can get more help by clicking the [?] icon beside every options.', 'ipt_rpm') . '</p>'
        ));

        get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Documentation</a>', 'ipt_rpm'), ipt_rpm_loader::$documentation) . '</p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Support Forums</a>', 'ipt_rpm'), ipt_rpm_loader::$support_forum) . '</p>'
	);
    }
}

class ipt_rpm_admin_settings extends ipt_rpm_admin_base {
    public function __construct($abbr = '') {
        $this->capability = 'ipt_rpm_settings';
        $this->action_nonce = 'ipt_rpm_settings_nonce';

        parent::__construct($abbr);

        $this->icon_url = $this->url['images'] . 'settings.png';
        $this->is_metabox = true;
        $this->metabox_col = 1;
    }

    public function admin_menu() {
        $this->pagehook = add_submenu_page('ipt_rpm_menu_dashboard', __('Settings', 'ipt_rpm'), __('Settings', 'ipt_rpm'), $this->capability, 'ipt_rpm_menu_settings', array(&$this, 'index'));
        parent::admin_menu();
    }

    public function index() {
        $this->index_head(__('Registration Portal Management &raquo; Settings', 'ipt_rpm'));
        ?>
<div id="dashboard-widgets">
    <div class="metabox-holder">
        <?php $this->print_metabox_containers('debug'); ?>
    </div>
    <div class="clear"></div>
</div>
        <?php
        $this->index_foot(true, __('Save Settings', 'ipt_rpm'));
    }

    public function meta_settings() {
        $settings = get_option('ipt_rpm_settings');
        $users = array_merge(get_users(array('role' => 'administrator', 'fields' => array('ID', 'display_name'))), get_users(array('role' => 'ipt_rpm_admin', 'fields' => array('ID', 'display_name'))), get_users(array('role' => 'ipt_rpm_registrar', 'fields' => array('ID', 'display_name'))));

        ?>
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row"><label for="settings_currency"><?php _e('Currency Symbol', 'ipt_rpm'); ?></label></th>
            <td>
                <?php $this->print_input_text('settings[currency]', $settings['currency'], 'small-text code'); ?>
            </td>
            <td>
                <span class="help">
                    <?php _e('Enter the currency symbol here. A few examples:', 'ipt_rpm') ?>
                    <ul>
                        <li>
                            <strong><?php _e('US Dollar:', 'ipt_rpm'); ?></strong> $
                        </li>
                        <li>
                            <strong><?php _e('UK Pound:', 'ipt_rpm'); ?></strong> £
                        </li>
                        <li>
                            <strong><?php _e('Indian Rupee:', 'ipt_rpm'); ?></strong> &#8377;
                        </li>
                    </ul>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="settings_email"><?php _e('Notification Email', 'ipt_rpm'); ?></label></th>
            <td>
                <?php $this->print_input_text('settings[email]', $settings['email'], 'regular-text code'); ?>
            </td>
            <td>
                <span class="help">
                    <?php _e('Enter the email address where you would like to send a notification when a registrar enters a new registrant.', 'ipt_rpm'); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="settings_titles_portal"><?php _e('Portal Title', 'ipt_rpm'); ?></label></th>
            <td>
                <?php $this->print_input_text('settings[titles][portal]', $settings['titles']['portal'], 'large-text'); ?>
            </td>
            <td>
                <span class="help">
                    <?php _e('Enter the nomenclature for Portals. It will be used on Add New Registration Page and on all print pages.', 'ipt_rpm'); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="settings_titles_registrant"><?php _e('Registrant\'s Information Title', 'ipt_rpm'); ?></label></th>
            <td>
                <?php $this->print_input_text('settings[titles][registrant]', $settings['titles']['registrant'], 'large-text'); ?>
            </td>
            <td>
                <span class="help">
                    <?php _e('Enter the nomenclature for Registrant\'s Information. It will be used on Add New Registration Page and on all print pages.', 'ipt_rpm'); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="settings_titles_registration_topic"><?php _e('Registration Topic Title', 'ipt_rpm'); ?></label></th>
            <td>
                <?php $this->print_input_text('settings[titles][registration_topic]', $settings['titles']['registration_topic'], 'large-text'); ?>
            </td>
            <td>
                <span class="help">
                    <?php _e('Enter the nomenclature for Registration Topic. It will be used on Add New Registration Page and on all print pages.', 'ipt_rpm'); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="settings_title_registrar"><?php _e('Registrar\'s Information Title', 'ipt_rpm'); ?></label></th>
            <td>
                <?php $this->print_input_text('settings[titles][registrar]', $settings['titles']['registrar'], 'large-text'); ?>
            </td>
            <td>
                <span class="help">
                    <?php _e('Enter the nomenclature for Registrar\'s Information. It will be used on Add New Registration Page and on all print pages.', 'ipt_rpm'); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th><?php _e('Portals/Registrars', 'ipt_rpm'); ?></th>
            <td>

                <div class="ipt-sortable">
                    <div class="ipt-sortable-head">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text"><?php _e('Name', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-checkboxes"><?php _e('Users', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-smalltext"><?php _e('Prefix', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                        <div class="clear"></div>
                    </div>

                    <div class="ipt-sortable-body">
                        <?php $count = 0; foreach($settings['portals'] as $key => $portal) : ?>
                        <div class="ipt-sortable-elem">
                            <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                            <div class="ipt-sortable-text">
                                <?php $this->print_input_text('settings[portals][' . $key . '][name]', $portal['name'], 'regular-text'); ?>
                            </div>
                            <div class="ipt-sortable-checkboxes">
                                <?php foreach($users as $user) : ?>
                                <label for="settings_portals_<?php echo $key; ?>_users_<?php echo $user->ID; ?>">
                                    <input type="checkbox" name="settings[portals][<?php echo $key; ?>][users][]" id="settings_portals_<?php echo $key; ?>_users_<?php echo $user->ID; ?>" value="<?php echo $user->ID; ?>"<?php if(in_array($user->ID, $portal['users'])) echo ' checked="checked"'; ?> /> <?php echo $user->display_name; ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="ipt-sortable-smalltext">
                                <?php $this->print_input_text('settings[portals][' . $key . '][prefix]', $portal['prefix'], 'small-text code'); ?>
                            </div>
                            <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                        </div>
                        <?php $count++; endforeach; $key = '__key__'; ?>
                    </div>

                    <div class="ipt-sortable-data">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text">
                            <?php $this->print_input_text('settings[portals][' . $key . '][name]', "", 'regular-text'); ?>
                        </div>
                        <div class="ipt-sortable-checkboxes">
                            <?php foreach($users as $user) : ?>
                            <label for="settings_portals_<?php echo $key; ?>_users_<?php echo $user->ID; ?>">
                                <input type="checkbox" name="settings[portals][<?php echo $key; ?>][users][]" id="settings_portals_<?php echo $key; ?>_users_<?php echo $user->ID; ?>" value="<?php echo $user->ID; ?>" /> <?php echo $user->display_name; ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="ipt-sortable-smalltext">
                            <?php $this->print_input_text('settings[portals][' . $key . '][prefix]', "", 'small-text code'); ?>
                        </div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-foot">
                        <input data-count="<?php echo $count; ?>" data-confirm-del="<?php _e('Are you sure you want to delete? This can not be undone.', 'ipt_rpm'); ?>" type="button" class="button-secondary ipt-sortable-button sort add del" value="<?php _e('Add New Portal'); ?>" />
                        <div class="clear"></div>
                    </div>
                </div>

            </td>
            <td>
                <span class="help">
                    <?php _e('Add, Edit, Reorder new registrars or portals here.', 'ipt_rpm'); ?>
                    <ul>
                        <li>
                            <strong><?php _e('Name:', 'ipt_rpm'); ?></strong> <?php _e('Name of the Portal. Used for Statistical references.', 'ipt_rpm'); ?>
                        </li>
                        <li>
                            <strong><?php _e('Users:', 'ipt_rpm'); ?></strong> <?php _e('Users allowed to access this portal.', 'ipt_rpm'); ?>
                        </li>
                        <li>
                            <strong><?php _e('Prefix:', 'ipt_rpm'); ?></strong> <?php _e('Automated prefix added to the registration code of all entries through the particular portal.', 'ipt_rpm'); ?>
                        </li>
                    </ul>
                </span>
            </td>
        </tr>
    </tbody>
</table>
        <?php
    }

    public function meta_reg() {
        $regs = get_option('ipt_rpm_reg');
        $q_types = array(
            'single' => __('MCQ - Single', 'ipt_rpm'),
            'multiple' => __('MCQ - Multiple', 'ipt_rpm'),
            'smalltext' => __('Small Text', 'ipt_rpm'),
            'largetext' => __('Large Text', 'ipt_rpm'),
            'checkbox' => __('Checkbox', 'ipt_rpm')
        );
        ?>
<div class="ipt-sortable">
    <div class="ipt-sortable-head">
        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
        <div class="ipt-sortable-30"><?php _e('Registration Topics', 'ipt_rpm'); ?></div>
        <div class="ipt-sortable-50"><?php _e('Related Questions', 'ipt_rpm'); ?></div>
        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
    </div>

    <div class="ipt-sortable-body">
        <?php $count = 0; foreach($regs as $key => $reg) : ?>
        <div class="ipt-sortable-elem">
            <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
            <div class="ipt-sortable-25">
                <ul>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_name"><?php _e('Name', 'ipt_rpm'); ?></label></strong>
                        <?php $this->print_input_text('reg[' . $key . '][name]', $reg['name'], 'regular-text'); ?>
                    </li>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_opt_in"><?php _e('Opt In Title', 'ipt_rpm'); ?></label></strong>
                        <?php $this->print_input_text('reg[' . $key . '][opt_in]', $reg['opt_in'], 'regular-text code'); ?>
                    </li>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_fee"><?php _e('Fee', 'ipt_rpm'); ?></label></strong>
                        <?php $this->print_input_text('reg[' . $key . '][fee]', $reg['fee'], 'small-text code'); ?>
                    </li>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_desc"><?php _e('Description', 'ipt_rpm'); ?></label></strong><br />
                        <?php $this->print_textarea('reg[' . $key . '][desc]', $reg['desc'], 'widefat', 4); ?>
                    </li>
                </ul>
            </div>
            <div class="ipt-sortable-60">

                <div class="ipt-sortable">
                    <div class="ipt-sortable-head">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text"><?php _e('Question', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-25"><?php _e('Options', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-15"><?php _e('Type', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-3"><?php _e('*', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-body">
                        <?php $p_data_count = 0; foreach($reg['pdata'] as $pkey => $data) : ?>
                        <div class="ipt-sortable-elem">
                            <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                            <div class="ipt-sortable-text">
                                <?php $this->print_input_text('reg[' . $key . '][pdata][' . $pkey . '][question]', $data['question']); ?>
                            </div>
                            <div class="ipt-sortable-25">
                                <?php $this->print_textarea('reg[' . $key . '][pdata][' . $pkey . '][options]', $data['options']); ?>
                            </div>
                            <div class="ipt-sortable-15">
                                <select name="reg[<?php echo $key; ?>][pdata][<?php echo $pkey; ?>][type]" class="widefat">
                                    <?php $this->print_select_op($q_types, $data['type'], true); ?>
                                </select>
                            </div>
                            <div class="ipt-sortable-3">
                                <?php $this->print_checkbox('reg[' . $key . '][pdata][' . $pkey . '][required]', '1', ($data['required'] == true)); ?>
                            </div>
                            <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                        </div>
                        <?php $p_data_count++; endforeach; $pkey = '__pkey__' ?>
                    </div>

                    <div class="ipt-sortable-data">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text">
                            <?php $this->print_input_text('reg[' . $key . '][pdata][' . $pkey . '][question]', ''); ?>
                        </div>
                        <div class="ipt-sortable-25">
                            <?php $this->print_textarea('reg[' . $key . '][pdata][' . $pkey . '][options]', ''); ?>
                        </div>
                        <div class="ipt-sortable-15">
                            <select name="reg[<?php echo $key; ?>][pdata][<?php echo $pkey; ?>][type]" class="widefat">
                                <?php $this->print_select_op($q_types, '', true); ?>
                            </select>
                        </div>
                        <div class="ipt-sortable-3">
                            <?php $this->print_checkbox('reg[' . $key . '][pdata][' . $pkey . '][required]', '1', false); ?>
                        </div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-foot">
                        <input data-count="<?php echo $p_data_count; ?>" data-key="__pkey__" data-confirm-del="<?php _e('Are you sure you want to delete? This can not be undone.', 'ipt_rpm'); ?>" type="button" class="button-secondary ipt-sortable-button sort add del" value="<?php _e('Add New Question'); ?>" />
                        <div class="clear"></div>
                    </div>
                </div>

            </div>
            <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
        </div>
        <?php $count++; endforeach; $key = '__key__'; $pkey = '__pkey__'; ?>
    </div>

    <div class="ipt-sortable-data">

            <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
            <div class="ipt-sortable-25">
                <ul>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_name"><?php _e('Name', 'ipt_rpm'); ?></label></strong>
                        <?php $this->print_input_text('reg[' . $key . '][name]', '', 'regular-text'); ?>
                    </li>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_opt_in"><?php _e('Opt In Title', 'ipt_rpm'); ?></label></strong>
                        <?php $this->print_input_text('reg[' . $key . '][opt_in]', __('Subscribe', 'ipt_rpm'), 'regular-text code'); ?>
                    </li>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_fee"><?php _e('Fee', 'ipt_rpm'); ?></label></strong>
                        <?php $this->print_input_text('reg[' . $key . '][fee]', '', 'small-text code'); ?>
                    </li>
                    <li>
                        <strong><label for="reg_<?php echo $key; ?>_desc"><?php _e('Description', 'ipt_rpm'); ?></label></strong><br />
                        <?php $this->print_textarea('reg[' . $key . '][desc]', '', 'widefat', 4); ?>
                    </li>
                </ul>
            </div>
            <div class="ipt-sortable-60">

                <div class="ipt-sortable">
                    <div class="ipt-sortable-head">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text"><?php _e('Question', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-25"><?php _e('Options', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-15"><?php _e('Type', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-3"><?php _e('*', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-body">

                    </div>

                    <div class="ipt-sortable-data">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text">
                            <?php $this->print_input_text('reg[' . $key . '][pdata][' . $pkey . '][question]', ''); ?>
                        </div>
                        <div class="ipt-sortable-25">
                            <?php $this->print_textarea('reg[' . $key . '][pdata][' . $pkey . '][options]', ''); ?>
                        </div>
                        <div class="ipt-sortable-15">
                            <select name="reg[<?php echo $key; ?>][pdata][<?php echo $pkey; ?>][type]" class="widefat">
                                <?php $this->print_select_op($q_types, '', true); ?>
                            </select>
                        </div>
                        <div class="ipt-sortable-3">
                            <?php $this->print_checkbox('reg[' . $key . '][pdata][' . $pkey . '][required]', '1', false); ?>
                        </div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-foot">
                        <input data-count="0" data-key="__pkey__" data-confirm-del="<?php _e('Are you sure you want to delete? This can not be undone.', 'ipt_rpm'); ?>" type="button" class="button-secondary ipt-sortable-button sort add del" value="<?php _e('Add New Question'); ?>" />
                        <div class="clear"></div>
                    </div>
                </div>

            </div>
            <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>

    </div>

    <div class="ipt-sortable-foot">
        <input data-count="<?php echo $count; ?>" data-confirm-del="<?php _e('Are you sure you want to delete? This can not be undone.', 'ipt_rpm'); ?>" type="button" class="button-secondary ipt-sortable-button sort add del" value="<?php _e('Add New Registration Topic'); ?>" />
        <div class="clear"></div>
    </div>
</div>
        <?php
    }

    public function meta_registrant() {
        $registrant = get_option('ipt_rpm_registrant_data');
        $predefined = array(
            'name' => __('Name', 'ipt_rpm'),
            'email' => __('Email', 'ipt_rpm'),
            'phone' => __('Phone', 'ipt_rpm'),
        );
        $q_types = array(
            'single' => __('MCQ - Single', 'ipt_rpm'),
            'multiple' => __('MCQ - Multiple', 'ipt_rpm'),
            'smalltext' => __('Small Text', 'ipt_rpm'),
            'largetext' => __('Large Text', 'ipt_rpm'),
            'checkbox' => __('Checkbox', 'ipt_rpm')
        );
        ?>
<table class="form-table">
    <tbody>
        <?php foreach($predefined as $key => $label) : ?>
        <tr>
            <th><?php echo $label; ?></th>
            <th>
                <label for="registrant_<?php echo $key; ?>_enabled">
                    <?php $this->print_checkbox('registrant[' . $key . '][enabled]', 1, $registrant[$key]['enabled']); ?>
                    <?php _e('Enabled?', 'ipt_rpm'); ?>
                </label><br />
                <label for="registrant_<?php echo $key; ?>_required">
                    <?php $this->print_checkbox('registrant[' . $key . '][required]', 1, $registrant[$key]['required']); ?>
                    <?php _e('Required?', 'ipt_rpm'); ?>
                </label>
            </th>
            <?php if($key == 'name') : ?>
            <td rowspan="3">
                <div class="ipt-sortable">
                    <div class="ipt-sortable-head">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text"><?php _e('Other Questions', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-25"><?php _e('Options', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-15"><?php _e('Type', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-3"><?php _e('*', 'ipt_rpm'); ?></div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-body">
                        <?php $p_data_count = 0; foreach($registrant['others'] as $pkey => $data) : ?>
                        <div class="ipt-sortable-elem">
                            <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                            <div class="ipt-sortable-text">
                                <?php $this->print_input_text('registrant[others][' . $pkey . '][question]', $data['question']); ?>
                            </div>
                            <div class="ipt-sortable-25">
                                <?php $this->print_textarea('registrant[others][' . $pkey . '][options]', $data['options']); ?>
                            </div>
                            <div class="ipt-sortable-15">
                                <select name="registrant[others][<?php echo $pkey; ?>][type]" class="widefat">
                                    <?php $this->print_select_op($q_types, $data['type'], true); ?>
                                </select>
                            </div>
                            <div class="ipt-sortable-3">
                                <?php $this->print_checkbox('registrant[others][' . $pkey . '][required]', '1', ($data['required'] == true)); ?>
                            </div>
                            <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                        </div>
                        <?php $p_data_count++; endforeach; $pkey = '__key__' ?>
                    </div>

                    <div class="ipt-sortable-data">
                        <div class="ipt-sortable-drag"><img title="<?php _e('Drag and Sort using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'move.png'; ?>" height="24" width="24" /></div>
                        <div class="ipt-sortable-text">
                            <?php $this->print_input_text('registrant[others][' . $pkey . '][question]', ''); ?>
                        </div>
                        <div class="ipt-sortable-25">
                            <?php $this->print_textarea('registrant[others][' . $pkey . '][options]', ''); ?>
                        </div>
                        <div class="ipt-sortable-15">
                            <select name="registrant[others][<?php echo $pkey; ?>][type]" class="widefat">
                                <?php $this->print_select_op($q_types, '', true); ?>
                            </select>
                        </div>
                        <div class="ipt-sortable-3">
                            <?php $this->print_checkbox('registrant[others][' . $pkey . '][required]', '1', false); ?>
                        </div>
                        <div class="ipt-sortable-del"><img title="<?php _e('Delete using this button', 'ipt_rpm'); ?>" src="<?php echo $this->url['images'] . 'cross_close.png'; ?>" height="24" width="24" /></div>
                    </div>

                    <div class="ipt-sortable-foot">
                        <input data-count="<?php echo $p_data_count; ?>" data-key="__key__" data-confirm-del="<?php _e('Are you sure you want to delete? This can not be undone.', 'ipt_rpm'); ?>" type="button" class="button-secondary ipt-sortable-button sort add del" value="<?php _e('Add New Question', 'ipt_rpm'); ?>" />
                        <div class="clear"></div>
                    </div>
                </div>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        <?php
    }

    public function save_post() {
        parent::save_post();

        //save the settings
        $psettings = (array) $this->post['settings'];

        $settings = array();
        //Copy simple data
        $settings['currency'] = htmlspecialchars_decode(trim($psettings['currency']));
        $settings['email'] = trim($psettings['email']);
        $settings['titles'] = array_map('strip_tags', array_map('trim', $psettings['titles']));
        $settings['portals'] = array();

        //Copy sorted portal data
        if(isset($psettings['portals']) && !empty($psettings['portals'])) {
            foreach($psettings['portals'] as $portal) {
                //Check simple data
                if(!isset($portal['users']))
                    $portal['users'] = array();
                $portal['name'] = strip_tags(trim($portal['name']));
                $portal['prefix'] = strip_tags(trim($portal['prefix']));

                $settings['portals'][] = $portal;
            }
        }

        //Update option
        update_option('ipt_rpm_settings', $settings);

        //save the registration
        $pregs = (array) $this->post['reg'];
        $regs = array();

        if(!empty($pregs)) {
            foreach($pregs as $reg) {

                //Check sorted pdata
                $pdata = array();
                if(isset($reg['pdata']) && !empty($reg['pdata'])) {
                    foreach((array) $reg['pdata'] as $prdata) {
                        //check simple data
                        $pdata[] = array(
                            'question' => strip_tags(trim($prdata['question'])),
                            'options' => $this->clean_option($prdata['options']),
                            'type' => $prdata['type'],
                            'required' => (isset($prdata['required']) ? true : false),
                        );
                    }
                }

                //check simple data and store
                $regs[] = array(
                    'name' => strip_tags($reg['name']),
                    'fee' => round(floatval(trim($reg['fee'])), 2),
                    'opt_in' => strip_tags(trim($reg['opt_in'])),
                    'desc' => strip_tags(trim($reg['desc'])),
                    'pdata' => $pdata,
                );
            }
        }
        update_option('ipt_rpm_reg', $regs);

        //save the registrant
        $pregistrant = (array) $this->post['registrant'];
        $registrant = array();
        foreach(array('name', 'email', 'phone') as $pdef) {
            $registrant[$pdef] = array(
                'enabled' => (isset($pregistrant[$pdef]['enabled']) ? true : false),
                'required' => (isset($pregistrant[$pdef]['required']) ? true : false),
            );
        }
        $registrant['others'] = array();
        if(!empty($pregistrant['others'])) {
            foreach((array) $pregistrant['others'] as $other) {
                $registrant['others'][] = array(
                    'question' => strip_tags(trim($other['question'])),
                    'options' => $this->clean_option($other['options']),
                    'type' => $other['type'],
                    'required' => (isset($other['required']) ? true : false),
                );
            }
        }
        update_option('ipt_rpm_registrant_data', $registrant);

        //redirect
        wp_redirect(add_query_arg(array('post_result' => 1), $_POST['_wp_http_referer']));
        die();
    }

    private function clean_option($option) {
        return trim(preg_replace("/\r\n\s*[\r\n]*/", "\r\n", $option), "\r\n");
    }

    public function on_load_page() {
        parent::on_load_page();

        //add metabox
        add_meta_box('meta_ipt_rpm_settings', __('Registration Portal Settings', 'ipt_rpm'), array(&$this, 'meta_settings'), $this->pagehook, 'debug', 'default');
        add_meta_box('meta_ipt_rpm_reg', __('Registration Topics &amp; Questions', 'ipt_rpm'), array(&$this, 'meta_reg'), $this->pagehook, 'debug', 'default');
        add_meta_box('meta_ipt_rpm_registrant', __('Registrant Questions', 'ipt_rpm'), array(&$this, 'meta_registrant'), $this->pagehook, 'debug', 'default');

        //add help
        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-registrant-overview',
            'title' => __('Overview', 'ipt_rpm'),
            'content' =>
                '<p>' . __('Control all the features of R.P. Manager Plugin from this page. Only admins have access to this page and it can be used to setup the registration form.', 'ipt_rpm') . '</p>' .
                '<p>' . __('Right now, we have three modes of customizations:', 'ipt_rpm') . '</p>' .
                '<ul>' .
                        '<li>' . __('Registration Portal Setting.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('Registration Topics &amp; Questions.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('Registrant Questions.', 'ipt_rpm') . '</li>' .
                '</ul>' .
                '<p>' . __('But before we understand all the functionalities, we need to look into the custom Users and Roles implemented with R.P. Manager Plugin.', 'ipt_rpm') . '</p>',
        ));

        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-user-roles',
            'title' => __('Users &amp; Roles', 'ipt_rpm'),
            'content' =>
                '<p>' . __('R.P. Manager integrates default site admins and additionally allows creating two new types of users:', 'ipt_rpm') . '</p>' .
                '<ul>' .
                        '<li><strong>' . __('Registration Portal Administrator', 'ipt_rpm') . ':</strong> ' . __('Can access the settings page, all of the portals and Add/Edit entries.', 'ipt_rpm') . '</li>' .
                        '<li><strong>' . __('Registration Portal Registrar', 'ipt_rpm') . ':</strong> ' . __('Can only access specified portals and can add/renew entries. Can not degrade and/or delete an entry.', 'ipt_rpm') . '</li>' .
                '</ul>' .
                '<p>' . __('The above mentioned users can only access the R.P. Manager admin menus. Whenever they log in, they automatically get redirected to the R.P. Manager Dashboard.', 'ipt_rpm') . '</p>',
        ));

        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-registration-portal',
            'title' => __('Registration Portals', 'ipt_rpm'),
            'content' =>
                '<p>' . __('From here add the portals for the registrars. This is like installing new registration slots and assigning users. The concept is as follows:', 'ipt_rpm') . '</p>' .
                '<ul>' .
                        '<li>' . __('<strong>Adding a portal:</strong> Simply click on the Add New Portal button. You can add as many portals you want and all portals can have the following settings.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Defining a Prefix:</strong> You should assign unique prefix different portals. While you can have same prefix for more than one portals, it is not recommended at all. It is good if you add prefixes like <code>A-</code> or <code>B-</code> and name the portals, <code>Portal A</code>, <code>Portal B</code> etc.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Assigning Users:</strong> Each portal can permit specified users to access and add registrations. This rule holds only for the Registrar type users, whereas admin type users have access to all portals by default.', 'ipt_rpm') . '</li>' .
                '</ul>' .
                '<p>' . __('You can get more help by clicking the [?] icon beside every options.', 'ipt_rpm') . '</p>'
        ));

        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-registration-topics',
            'title' => __('Registration Topics', 'ipt_rpm'),
            'content' =>
                '<p>' . __('From here add new registration topics which will be shown to the "Add New Registration" page.', 'ipt_rpm') . '</p>' .
                '<ul>' .
                        '<li>' . __('<strong>Adding a Topic:</strong> Simply click on the Add New Registration Topic button. You can add as many topics/events you want and all can have the following settings.', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Subscription Fee:</strong> Enter the amount of subscription fees..', 'ipt_rpm') . '</li>' .
                        '<li>' . __('<strong>Additional Questions:</strong> Each topic/event can have any number of questions. Simply click on the "Add New Question" button and you will understand.', 'ipt_rpm') . '</li>' .
                '</ul>'
        ));

        get_current_screen()->add_help_tab(array(
            'id' => 'ipt_rpm-registrant-questions',
            'title' => __('Registrant Questions', 'ipt_rpm'),
            'content' =>
                '<p>' . __('If you wish to add additional questions for the registrant, then you can do so from here.', 'ipt_rpm') . '</p>'
        ));

        get_current_screen()->set_help_sidebar(
	'<p><strong>' . __('For more information:') . '</strong></p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Documentation</a>', 'ipt_rpm'), ipt_rpm_loader::$documentation) . '</p>' .
	'<p>' . sprintf(__('<a href="%s" target="_blank">Support Forums</a>', 'ipt_rpm'), ipt_rpm_loader::$support_forum) . '</p>'
	);
    }
}

/**
 * The base admin class
 * @abstract
 */
abstract class ipt_rpm_admin_base {
    /**
     * Duplicates the $_POST content and properly process it
     * Holds the typecasted (converted int and floats properly and escaped html) value after the constructor has been called
     * @var array
     */
    var $post = array();

    /**
     * Holds the hook of this page
     * @var string Pagehook
     * Should be set during the construction
     */
    var $pagehook;

    /**
     * The nonce for admin-post.php
     * Should be set the by extending class
     * @var string
     */
    var $action_nonce;

    /**
     * The URL of the admin page icon
     * Should be set by the extending class
     * @var string
     */
    var $icon_url;

    /**
     * This gets passed directly to current_user_can
     * Used for security and should be set by the extending class
     * @var string
     */
    var $capability;

    /**
     * Holds the URL of the static directories
     * Just the /static/admin/ URL and sub directories under it
     * access it like $url['js'], ['images'], ['css'], ['root'] etc
     * @var array
     */
    var $url = array();

    /**
     * Set this to true if you are going to use the WordPress Metabox appearance
     * This will enqueue all the scripts and will also set the screenlayout option
     * @var bool False by default
     */
    var $is_metabox = false;

    /**
     * Default number of columns on metabox
     * @var int
     */
    var $metabox_col = 2;

    /**
     * Holds the post result message string
     * Each entry is an associative array with the following options
     *
     * $key : The code of the post_result value =>
     *
     *      'type' => 'update' : The class of the message div update | error
     *
     *      'msg' => '' : The message to be displayed
     *
     * @var array
     */
    var $post_result = array();

    /**
     * The action value to be used for admin-post.php
     * This is generated automatically by appending _post_action to the action_nonce variable
     * @var string
     */
    var $admin_post_action;

    /**
     * Whether or not to print form on the admin wrap page
     * Mainly for manually printing the form
     * @var bool
     */
    var $print_form;

    /**
     * Abbreviation of the plugin. Mainly used for locating the loader class.
     * @var string
     */
    var $abbr;

    /**
     * The constructor function
     * 1. Properly copies the $_POST to $this->post on POST request
     * 2. Calls the admin_menu() function
     * You should have parent::__construct() for all these to happen
     *
     * @param string $abbr Abbreviation of the plugin. Mainly used for locating the loader class.
     * @param boolean $gets_hooked Should be true if you wish to actually put this inside an admin menu. False otherwise
     * It basically hooks into admin_menu and admin_post_ if true
     *
     */
    public function __construct($abbr, $gets_hooked = true) {
        $this->abbr = $abbr;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //$this->post = $_POST;

            //we do not need to check on magic quotes
            //as wordpress always adds magic quotes
            //@link http://codex.wordpress.org/Function_Reference/stripslashes_deep
            $this->post = array_map('stripslashes_deep', $_POST);

//            if(get_magic_quotes_gpc())
//                array_walk_recursive ($this->post, array($this, 'stripslashes_gpc'));

            //trim it
            array_walk_recursive($this->post, 'trim');
            //convert html to special characters
            array_walk_recursive ($this->post, array($this, 'htmlspecialchar_ify'));
        }

        $loader_class_name = $this->abbr . '_loader';

        $plugin = $loader_class_name::$abs_file;

        $this->url = array(
            'root' => plugins_url('/static/admin/', $plugin),
            'js' => plugins_url('/static/admin/js/', $plugin),
            'images' => plugins_url('/static/admin/images/', $plugin),
            'css' => plugins_url('/static/admin/css/', $plugin),
        );

        //default messages
        //We violate the rules for translation but that is necessary
        $this->post_result = array(
            1 => array(
                'type' => 'update',
                'msg' => __('Successfully saved the options', $this->abbr),
            ),
            2 => array(
                'type' => 'error',
                'msg' => __('Either you have not changed anything or some error has occured. Please contact the developer', $this->abbr),
            ),
            3 => array(
                'type' => 'update',
                'msg' => __('The Master Reset was successful', $this->abbr),
            ),
        );

        $this->metabox_col = ($this->metabox_col > 2 || $this->metabox_col < 0) ? 2 : $this->metabox_col;

        $this->admin_post_action = $this->action_nonce . '_post_action';

        if($gets_hooked) {
            //register admin_menu hook
            add_action('admin_menu', array(&$this, 'admin_menu'));

            //register admin-post.php hook
            add_action('admin_post_' . $this->admin_post_action, array(&$this, 'save_post'));
        }
    }

    /*______________________________________SYSTEM METHODS______________________________________*/

    /**
     * Hook to the admin menu
     * Should be overriden and also the hook should be saved in the $this->pagehook
     * In the end, the parent::admin_menu() should be called for load to hooked properly
     */
    public function admin_menu() {
        add_action('load-' . $this->pagehook, array(&$this, 'on_load_page'));
        //$this->pagehook = add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
        //do the above or similar in the overriden callback function
    }

    /**
     * Use this to generate the admin page
     * always call parent::index() so the save post is called
     * also call $this->index_foot() after the generation of page (the last line of this function)
     * to give some compatibility (mainly with the metaboxes)
     * @access public
     */
    abstract public function index();

    protected function index_head($title = '', $print_form = true, $form_id = '') {
        $this->print_form = $print_form;
        if($form_id == '')
            $form_id = $this->abbr . '_form';
        ?>
<style type="text/css">
    <?php echo '#' . $this->pagehook; ?>-widgets .meta-box-sortables {
        margin: 0 8px;
    }
</style>
<div class="wrap" id="<?php echo $this->pagehook; ?>-widgets">
    <div class="icon32">
        <img src="<?php echo $this->icon_url; ?>" height="32" width="32" alt="icon" />
    </div>
    <h2><?php echo $title; ?></h2>
    <?php
        if(isset($_GET['post_result'])) {
            $msg = $this->post_result[(int) $_GET['post_result']];
            if(!empty($msg)) {
                if($msg['type'] == 'update' || $msg['type'] == 'updated')
                    $this->print_update($msg['msg']);
                else
                    $this->print_error($msg['msg']);
            }
        }
    ?>
    <?php if($this->print_form) : ?>
    <form method="post" action="admin-post.php" id="<?php echo $form_id; ?>">
        <input type="hidden" name="action" value="<?php echo $this->admin_post_action; ?>" />
        <?php wp_nonce_field($this->action_nonce, $this->action_nonce); ?>
        <?php if($this->is_metabox) : ?>
        <?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
        <?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
        <?php endif; ?>
    <?php endif; ?>
        <?php
    }

    /**
     * Include this to the end of index function so that metaboxes work
     */
    protected function index_foot($submit = true, $text = 'Save Changes') {
        ?>
    <?php if($this->print_form) : ?>
        <?php if(true == $submit) : ?>
        <div class="clear"></div>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e($text, 'wpadmrs'); ?>" name="submit" />&nbsp;
            <input type="reset" class="button-secondary" value="<?php _e('Reset', 'wpadmrs'); ?>" name="reset" />
        </p>
        <?php endif; ?>
    </form>
    <?php endif; ?>
    <div class="clear"></div>
</div>
<?php if($this->is_metabox) : ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
        // close postboxes that should be closed
        $('.if-js-closed').removeClass('if-js-closed').addClass('closed');
        // postboxes setup
        postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
});
//]]>
</script>
<?php endif; ?>
        <?php
    }

    /**
     * Override to manage the save_post
     * This should be written by all the classes extending this
     *
     *
     * * General Template
     *
     * //process here your on $_POST validation and / or option saving
     *
     * //lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
     * wp_redirect(add_query_arg(array(), $_POST['_wp_http_referer']));
     *
     *
     */
    public function save_post($check_referer = true) {
        //user permission check
        if (!current_user_can($this->capability))
            wp_die(__('Cheatin&#8217; uh?'));
        //check nonce
        if($check_referer) {
            if(!wp_verify_nonce($_POST[$this->action_nonce], $this->action_nonce))
                wp_die(__('Cheatin&#8217; uh?'));
        }

        //process here your on $_POST validation and / or option saving

        //lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
        //wp_redirect(add_query_arg(array(), $_POST['_wp_http_referer']));
        //The above should be done by the extending after calling parent::save_post and processing post
    }

    /**
     * Hook to the load plugin page
     * This should be overriden
     * Also call parent::on_load_page() for screenoptions
     * @uses add_meta_box
     */
    public function on_load_page() {
        if($this->is_metabox) {
            add_screen_option('layout_columns', array(
                'max' => ($this->metabox_col == 2 ? 2 : 1),
                'default' => $this->metabox_col,
            ));
            wp_enqueue_script('common');
            wp_enqueue_script('wp-lists');
            wp_enqueue_script('postbox');

            /**
             * MetaBox Tab like wp-stat
             * @link  http://developersmind.com/2011/04/05/wordpress-tabbed-metaboxes/
             */
            wp_enqueue_style('jf-metabox-tabs', $this->url['css'] . 'metabox-tabs.css');
            wp_enqueue_script('jf-metabox-tabs', $this->url['js'] . 'metabox-tabs.js', array( 'jquery' ) );
        }
    }

    /**
     * Get the pagehook of this class
     * @return string
     */
    public function get_pagehook() {
        return $this->pagehook;
    }

    /**
     * Prints the metaboxes of a custom context
     * Should atleast pass the $context, others are optional
     *
     * The screen defaults to the $this->pagehook so make sure it is set before using
     * This should be the return value given by add_admin_menu or similar function
     *
     * The function automatically checks the screen layout columns and prints the normal/side columns accordingly
     * If screen layout column is 1 then even if you pass with context side, it will be hidden
     * Also if screen layout is 1 and you pass with context normal, it will get full width
     *
     * @param string $context The context of the metaboxes. Depending on this HTML ids are generated. Valid options normal | side
     * @param string $container_classes (Optional) The HTML class attribute of the container
     * @param string $container_style (Optional) The RAW inline CSS style of the container
     */
    public function print_metabox_containers($context = 'normal', $container_classes = '', $container_style = '') {
        global $screen_layout_columns;
        $style = 'width: 50%;';

        //check to see if only one column has to be shown

        if(isset($screen_layout_columns) && $screen_layout_columns == 1) {
            //normal?
            if('normal' == $context) {
                $style = 'width: 100%;';
            } else if ('side' == $context) {
                $style = 'display: none;';
            }
        }

        //override for the special debug area (1 column)
        if('debug' == $context) {
            $style = 'width: 100%;';
            $container_classes .= ' debug-metabox';
        }
        $id = $context == 'normal' ? 'postbox-container-1' : $context == 'debug' ? 'postbox-container-debug' : 'postbox-container-debug';
        ?>
<div class="postbox-container <?php echo $container_classes; ?>" style="<?php echo $style . $container_style; ?>" id="<?php echo $id; ?>">
    <?php do_meta_boxes($this->pagehook, $context, ''); ?>
</div>
        <?php
    }


    /*______________________________________INTERNAL METHODS______________________________________*/

    /**
     * Prints error msg in WP style
     * @param string $msg
     */
    protected function print_error($msg = '', $echo = true) {
        $output = '<div class="error fade"><p>' . $msg . '</p></div>';
        if($echo)
            echo $output;
        else
            return $output;
    }

    protected function print_update($msg = '', $echo = true) {
        $output = '<div class="updated fade"><p>' . $msg . '</p></div>';
        if($echo)
            echo $output;
        else
            return $output;
    }

    protected function print_p_error($msg = '', $echo = true) {
        $output = '<div class="p-message red"><p>' . $msg . '</p></div>';
        if($echo)
            echo $output;
        return $output;
    }

    protected function print_p_update($msg = '', $echo = true) {
        $output = '<div class="p-message yellow"><p>' . $msg . '</p></div>';
        if($echo)
            echo $output;
        return $output;
    }

    protected function print_p_okay($msg = '', $echo = true) {
        $output = '<div class="p-message green"><p>' . $msg . '</p></div>';
        if($echo)
            echo $output;
        return $output;
    }

    /**
     * stripslashes gpc
     * Strips Slashes added by magic quotes gpc thingy
     * @access protected
     * @param string $value
     */
    protected function stripslashes_gpc(&$value) {
        $value = stripslashes($value);
    }

    protected function htmlspecialchar_ify(&$value) {
        $value = htmlspecialchars($value);
    }

    /*______________________________________SHORTCUT HTML METHODS______________________________________*/

    /**
     * Shortens a string to a specified character length.
     * Also removes incomplete last word, if any
     * @param string $text The main string
     * @param string $char Character length
     * @param string $cont Continue character(…)
     * @return string
     */
    public function shorten_string($text, $char, $cont = '…') {
        $text = strip_tags(strip_shortcodes($text));
        $text = substr($text, 0, $char); //First chop the string to the given character length
        if(substr($text, 0, strrpos($text, ' '))!='') $text = substr($text, 0, strrpos($text, ' ')); //If there exists any space just before the end of the chopped string take upto that portion only.
        //In this way we remove any incomplete word from the paragraph
        $text = $text.$cont; //Add continuation ... sign
        return $text; //Return the value
    }

    /**
     * Get the first image from a string
     * @param string $html
     * @return mixed string|bool The src value on success or boolean false if no src found
     */
    public function get_first_image($html) {
        $matches = array();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $html, $matches);
        if(!$output) {
            return false;
        }
        else {
            $src = $matches[1][0];
            return trim($src);
        }
    }

    /**
     * Wrap a RAW JS inside <script> tag
     * @param String $string The JS
     * @return String The wrapped JS to be used under HTMl document
     */
    public function js_wrap( $string ) {
            return "\n<script type='text/javascript'>\n" . $string . "\n</script>\n";
    }

    /**
     * Wrap a RAW CSS inside <style> tag
     * @param String $string The CSS
     * @return String The wrapped CSS to be used under HTMl document
     */
    public function css_wrap( $string ) {
            return "\n<style type='text/css'>\n" . $string . "\n</style>\n";
    }

    public function print_datetimepicker($name, $value, $dateonly = false) {
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        ?>
<input type="text" class="regular-text code <?php echo ($dateonly ? 'datepicker' : 'datetimepicker'); ?>" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>" />
        <?php
    }

    /**
     * Prints options of a selectbox
     *
     * @param array $ops Should pass either an array of string ('label1', 'label2') or associative array like array('val' => 'val1', 'label' => 'label1'),...
     * @param string $key The key in the haystack, if matched a selected="selected" will be printed
     */
    public function print_select_op($ops, $key, $inner = false) {
        foreach((array) $ops as $k => $op) : ?>
        <?php if(!is_array($op)) : if(!$inner) $op = array('val' => $op, 'label' => ucfirst ($op)); else $op = array('val' => $k, 'label' => $op); endif; ?>
<option value="<?php echo esc_attr($op['val']); ?>"<?php if($key == $op['val']) echo ' selected="selected"'; ?>><?php echo $op['label']; ?></option>
        <?php endforeach;
    }

    /**
     * Prints a set of checkboxes for a single HTML name
     *
     * @param string $name The HTML name of the checkboxes
     * @param array $items The associative array of items array('val' => 'value', 'label' => 'label'),...
     * @param array $checked The array of checked items. It matches with the 'val' of the haystack array
     * @param string $sep (Optional) The seperator, HTML non-breaking-space (&nbsp;) by default. Can be <br /> or anything
     */
    public function print_checkboxes($name, $items, $checked, $sep = '&nbsp;&nbsp;') {
        if(!is_array($checked))
            $checked = (array) $checked;
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        foreach((array) $items as $item) : ?>
<label for="<?php echo esc_attr($id . '_' . $item['val']); ?>">
    <input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id . '_' . $item['val']); ?>" value="<?php echo esc_attr($item['val']); ?>"<?php if(in_array($item['val'], $checked)) echo ' checked="checked"'; ?> /> <?php echo $item['label']; ?>
</label>
        <?php echo $sep;
        endforeach;
    }

    /**
     * Prints a set of radioboxes for a single HTML name
     *
     * @param string $name The HTML name of the checkboxes
     * @param array $items The associative array of items array('val' => 'value', 'label' => 'label'),...
     * @param string $checked The value of checked radiobox. It matches with the val of the haystack
     * @param string $sep (Optional) The seperator, two HTML non-breaking-space (&nbsp;) by default. Can be <br /> or anything
     */
    public function print_radioboxes($name, $items, $checked, $sep = '&nbsp;&nbsp;') {
        if(!is_string($checked))
            $checked = (string) $checked;
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        foreach((array) $items as $item) : ?>
<label for="<?php echo esc_attr($id . '_' . $item['val']); ?>">
    <input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id . '_' . $item['val']); ?>" value="<?php echo esc_attr($item['val']); ?>"<?php if($checked == $item['val']) echo ' checked="checked"'; ?> /> <?php echo $item['label']; ?>
</label>
        <?php echo $sep;
        endforeach;
    }

    /**
     * Print a single checkbox
     * Useful for printing a single checkbox like for enable/disable type
     *
     * @param string $name The HTML name
     * @param string $value The value attribute
     * @param mixed (string|bool) $checked Can be true or can be equal to the $value for adding checked attribute. Anything else and it will not be added.
     */
    public function print_checkbox($name, $value, $checked) {
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        ?>
<input type="checkbox" name="<?php echo esc_attr($name); ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr($value); ?>"<?php if($value == $checked || true == $checked) echo ' checked="checked"'; ?> />
        <?php
    }

    /**
     * Prints a input[type="text"]
     * All attributes are escaped except the value
     * @param string $name The HTML name attribute
     * @param string $value The value of the textbox
     * @param string $class (Optional) The css class defaults to regular-text
     */
    public function print_input_text($name, $value, $class = 'regular-text', $name_only = false) {
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        ?>
<input type="text" name="<?php echo esc_attr($name); ?>"<?php if(!$name_only) : ?> id="<?php echo esc_attr($id); ?>"<?php endif; ?> value="<?php echo $value; ?>" class="<?php echo esc_attr($class); ?>" />
        <?php
    }

    /**
     * Prints a <textarea> with custom attributes
     * All attributes are escaped except the value
     * @param string $name The HTML name attribute
     * @param string $value The value of the textbox
     * @param string $class (Optional) The css class defaults to regular-text
     * @param int $rows (Optional) The number of rows in the rows attribute
     * @param int $cols (Optional) The number of columns in the cols attribute
     */
    public function print_textarea($name, $value, $class = 'regular-text', $rows = 3, $cols = 20) {
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        ?>
<textarea name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" rows="<?php echo (int) $rows; ?>" cols="<?php echo (int) $cols; ?>"><?php echo $value; ?></textarea>
        <?php
    }


    /**
     * Displays a jQuery UI Slider to the page
     * @param string $name The HTML name of the input box
     * @param int $value The initial/saved value of the input box
     * @param int $max The maximum of the range
     * @param int $min The minimum of the range
     * @param int $step The step value
     */
    public function print_ui_slider($name, $value, $max = 100, $min = 0, $step = 1) {
        ?>
<div class="slider"></div>
<input type="text" class="small-text code slider-text" max="<?php echo $max; ?>" min="<?php echo $min; ?>" step="<?php echo $step; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
        <?php
    }

    /**
     * Prints a ColorPicker
     *
     * @param string $name The HTML name of the input box
     * @param string $value The HEX color code
     */
    public function print_cpicker($name, $value) {
        $value = ltrim($value, '#');
        ?>
<input type="text" class="small-text color-picker code" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
        <?php
    }

    /**
     * Prints a input box with an attached upload button
     *
     * @param string $name The HTML name of the input box
     * @param string $value The value of the input box
     */
    public function print_uploadbutton($name, $value) {
        ?>
<input type="text" class="regular-text code" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />&nbsp;
<input class="upload-button" type="button" value="<?php _e('Upload'); ?>" />
        <?php
    }
}


/** ____________ WP LIST TABLE ____________ **/

/**
 * Get the WP_List_Table for populating our table
 */
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ipt_rpm_view_all_table extends WP_List_Table {
    var $no_item;

    var $settings;
    var $regs;
    var $portals;
    var $portals_options;
    var $registrant_data;
    var $is_prepared = false;

    public function __construct() {
        parent::__construct(array(
            'singular' => 'ipt_rpm_view_all_item',
            'plural' => 'ipt_rpm_view_all_items',
            'ajax' => false,
        ));
        $this->no_item = __('No registration entry yet for the portals you have access to.', 'ipt_rpm');
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'ipt_rpm'),
            'code' => __('Code', 'ipt_rpm'),
            'email' => __('Email', 'ipt_rpm'),
            'phone' => __('Phone', 'ipt_rpm'),
            'reg' => __('Registered', 'ipt_rpm'),
            'portal' => __('Portal', 'ipt_rpm'),
            'status' => __('Status', 'ipt_rpm'),
            'fees' => __('Fees', 'ipt_rpm'),
            'dated' => __('Dated', 'ipt_rpm'),
        );
    }

    public function get_sortable_columns() {
        return array(
            'code' => array('code', true),
            'name' => array('name', true),
            'email' => array('email', true),
            'phone' => array('phone', true),
            'portal' => array('portal', true),
            'status' => array('status', false),
            'fees' => array('fees', false),
            'dated' => array('created', false),
        );
    }

    public function column_default($item, $column_name) {
        switch($column_name) {
            case 'code' :
                return '<code>' . $this->settings['portals'][$item['portal']]['prefix'] . $item['code'] . '</code>';
                break;
            case 'name' :
                $edit = __('Edit', 'ipt_rpm');
                if(!current_user_can('ipt_rpm_settings')) {
                    $edit = __('Renew', 'ipt_rpm');
                }
                $actions = array(
                    'view' => sprintf('<a class="thickbox" href="admin-ajax.php?action=ipt_rpm_view_registration&id=%d&width=640&height=500">%s</a>', $item['id'], __('View', 'ipt_rpm')),
                    'edit' => '<a class="edit" href="admin.php?page=ipt_rpm_menu_view_all&action=edit&item_id='. $item['id'] . '">' . $edit . '</a>',
                    'delete' => '<a class="delete" href="' . wp_nonce_url('?page=' . $_REQUEST['page'] . '&action=delete&reg_id=' . $item['id'], 'ipt_rpm_item_delete_' . $item['id']) . '">' . __('Delete', 'ipt_rpm') . '</a>',
                );
                if(!current_user_can('ipt_rpm_settings')) {
                    unset($actions['delete']);
                }
                if($item['name'] == '') {
                    $item['name'] = __('Anonymous Person', 'ipt_rpm');
                }
                return sprintf('<strong>%1$s</strong> %2$s', '<a href="admin.php?page=ipt_rpm_menu_view_reg&portal=' . $item['portal'] . '&code='. $item['code'] . '">' . $item['name'] . '</a>', $this->row_actions($actions));
                break;
            case 'email' :
                return '<a href="mailto:' . $item['email'] . '">' . $item['email'] . '</a>';
                break;
            case 'phone' :
                return $item['phone'];
                break;
            case 'reg' :
                $reg = maybe_unserialize($item['reg_data']);

                $list = '<ul class="ul-square">';
                foreach($reg as $rkey => $a) {
                    $list .= '<li><strong>' . $this->regs[$rkey]['name'] . '</strong> <code>' . $this->settings['currency'] . '&nbsp;' . number_format($this->regs[$rkey]['fee'], 2) . '</code></li>';
                }
                $list .= '</ul>';
                return $list;
            case 'portal' :
                return $this->portals_options[$item['portal']];
                break;
            case 'status' :
                $status = array(
                    0 => __('Unpaid', 'ipt_rpm'),
                    1 => __('Paid', 'ipt_rpm'),
                );
                return $status[$item['status']];
                break;
            case 'fees' :
                return $this->settings['currency'] . '&nbsp;' . number_format($item['fees'], 2);
                break;
            case 'dated' :
                return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($item['created']));
                break;
        }
    }

    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="registrations[]" value="%s" />', $item['id']);
    }

    public function get_bulk_actions() {
        $actions = array(
            'delete' => __('Delete', 'ipt_rpm'),
            'paid' => __('Mark Paid', 'ipt_rpm'),
            'unpaid' => __('Mark Unpaid', 'ipt_rpm'),
        );

        if(!current_user_can('ipt_rpm_settings'))
            unset($actions['delete']);

        return $actions;
    }

    /**
     *
     * @global wpdb $wpdb
     * @global array $ipt_rpm_info
     */
    public function prepare_items() {
        $this->prepare_variables();
        global $wpdb, $ipt_rpm_info;

        //prepare our query
        $query = "SELECT id, code, name, email, phone, reg_data, portal, status, fees, created FROM {$ipt_rpm_info['reg_table']}";
        $orderby = !empty($_GET['orderby']) ? $wpdb->escape($_GET['orderby']) : 'created';
        $order = !empty($_GET['order']) ? $wpdb->escape($_GET['order']) : 'desc';
        if($orderby == 'code')
            $orderby = 'portal ' . $order . ', code';

        //init the where
        $where = ' WHERE';

        //add the search
        if(isset($_GET['s']) && !empty($_GET['s'])) {
            $where .= sprintf(' (code LIKE \'%1$s\' OR name LIKE \'%1$s\' OR email LIKE \'%1$s\') AND', '%' . $wpdb->escape($_GET['s'] . '%'));
        }

        //add the portal where
        if(isset($_GET['portal_id']) && $_GET['portal_id'] != '') {
            if(in_array($_GET['portal_id'], $this->portals)) {
                $where .= ' portal = ' . (int) $_GET['portal_id'];
                $this->no_item = __('No registrations under the portal: ', 'ipt_rpm') . $this->settings['portals'][(int) $_GET['portal_id']]['name'];
            } else {
                //cheating. Do something that it will fail
                $where .= ' portal = -1';
                $this->no_item = __('Illegal access to the portal detected.', 'ipt_rpm');
            }

        } else {
            $where .= ' portal IN (' . implode(',', $this->portals) . ')';
        }

        //add the status where
        if(isset($_GET['status']) && $_GET['status'] != '') {
            $where .= ' AND status=' . (int) $_GET['status'];
        }



        //append the complete where
        $query .= $where;

        //pagination
        $totalitems = $wpdb->get_var("SELECT COUNT(id) FROM {$ipt_rpm_info['reg_table']}{$where}");
        $perpage = $this->get_items_per_page('iptrpm_per_page');
        $totalpages = ceil($totalitems/$perpage);

        $this->set_pagination_args(array(
            'total_items' => $totalitems,
            'total_pages' => $totalpages,
            'per_page' => $perpage,
        ));

        $current_page = $this->get_pagenum();

        //put paginatoin and order on the query
        $query .= ' ORDER BY ' . $orderby . ' ' . $order . ' LIMIT ' . (($current_page - 1) * $perpage) . ',' . (int) $perpage;

        //register the columns
        $this->_column_headers = $this->get_column_info();

        //fetch the items
        $this->items = $wpdb->get_results($query, ARRAY_A);
    }

    public function no_items() {
        echo $this->no_item;
    }

    public function extra_tablenav($which) {
        switch($which) {
            case 'top' :
                ?>
<div class="alignleft actions">
    <select name="portal_id">
        <option value=""<?php if(!isset($_GET['portal_id']) || ($_GET['portal_id'] == '')) echo ' selected="selected"'; ?>><?php _e('Show from all available portals', 'ipt_rpm'); ?></option>
        <?php foreach($this->portals_options as $pkey => $label) : ?>
        <option value="<?php echo $pkey; ?>"<?php if(isset($_GET['portal_id']) && $_GET['portal_id'] == ((string)$pkey)) echo ' selected="selected"'; ?>><?php echo $label; ?></option>
        <?php endforeach; ?>
    </select>

    <select name="status">
        <option value=""<?php if(!isset($_GET['status']) || ($_GET['status'] == '')) echo ' selected="selected"'; ?>><?php _e('Show for all Payment Status', 'ipt_rpm'); ?></option>
        <option value="0"<?php if(isset($_GET['status']) && $_GET['status'] == '0') echo ' selected="selected"'; ?>><?php _e('Show only Unpaid', 'ipt_rpm'); ?></option>
        <option value="1"<?php if(isset($_GET['status']) && $_GET['status'] == '1') echo ' selected="selected"'; ?>><?php _e('Show only Paid', 'ipt_rpm'); ?></option>
    </select>
    <?php submit_button(__('Filter'), 'secondary', false, false, array('id' => 'form-query-submit')); ?>
</div>
                <?php
                break;
            case 'bottom' :
                echo '<div class="alignleft"><p>';
                if(!empty($_GET['s'])) {
                    echo sprintf(__('Searching for: %s', 'ipt_rpm'), $_GET['s']);
                } else {
                    _e('You can also print a submission. Just select view from the list and click on the print button.', 'ipt_rpm');
                }
                echo '</p></div>';
                break;
        }
    }

    private function prepare_variables() {
        if($this->is_prepared)
            return;
        $this->settings = get_option('ipt_rpm_settings');
        $this->regs = get_option('ipt_rpm_reg');
        $this->registrant_data = get_option('ipt_rpm_registrant_data');

        $portals = array_keys($this->settings['portals']);
        $this->portals_options = array();
        if(!current_user_can('ipt_rpm_settings')) {
            $portals = array();

            foreach($this->settings['portals'] as $pkey => $portal) {
                if(in_array(get_current_user_id(), $portal['users'])) {
                    $portals[] = $pkey;
                }
            }
        }
        $this->portals = $portals;

        foreach($this->portals as $portal) {
            $this->portals_options[$portal] = $this->settings['portals'][$portal]['name'];
        }

        $this->is_prepared = true;
    }
}


/** ______ Registration Helper Class ______ **/
class ipt_rpm_registration_helper extends ipt_rpm_admin_base {
    var $data;
    var $settings;
    var $regs;
    var $portals;
    var $portals_options;
    var $selected_portal;
    var $registrant_data;
    public static function ajax_gen_code() {
        global $ipt_rpm_info, $wpdb;
        $portal = $_GET['portal'];
        $code = $wpdb->get_var($wpdb->prepare("SELECT code FROM {$ipt_rpm_info['reg_table']} WHERE portal = %d ORDER BY code DESC LIMIT 0,1", $portal));
        echo (++$code);
        die();
    }
    public static function ajax_validate() {
        global $ipt_rpm_info, $wpdb;
        $field_id = trim(stripslashes($_GET['fieldId']));
        $field_value = (int) trim(stripslashes($_GET['fieldValue']));

        if(!current_user_can('ipt_rpm_new_reg')) {
            echo json_encode(array($field_id, false, __('You do not have sufficient rights to add a new registration.', 'ipt_rpm')));
            die();
        }

        $code = (int) $field_value;
        $portal = (int) $_GET['ipt_rpm_portal_prefix_input'];

        $user_action = 'add';
        $id = null;

        if(!empty($_GET['data_id'])) {
            if(current_user_can('ipt_rpm_settings')) {
                $user_action = 'edit';
            } else {
                $user_action = 'renew';
            }
            $id = (int) $_GET['data_id'];
        }

        $validate = true;
        $msg = sprintf(__('The code %s is available.', 'ipt_rpm'), $code);

        if($field_value == 0) {
            $validate = false;
            $msg  = __('The code is empty.', 'ipt_rpm');
        } else { //now check
            if($user_action == 'add') { //new code
                if($wpdb->get_var($wpdb->prepare("SELECT id FROM {$ipt_rpm_info['reg_table']} WHERE code = %d AND portal = %d", $code, $portal))) {
                    $validate = false;
                    $msg = __('The code has already been taken for the specified portal.', 'ipt_rpm');
                }
            } else if($user_action == 'renew') { //same code
                if($code != $wpdb->get_var($wpdb->prepare("SELECT code FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id))) {
                    $validate = false;
                    $msg = __('You can not change the registration code during renewal.', 'ipt_rpm');
                }
            } else { //same or new code edit
                if($code != $wpdb->get_var($wpdb->prepare("SELECT code FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id)) || $portal != $wpdb->get_var($wpdb->prepare("SELECT portal FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id))) {
                    //new, hence verify
                    if($wpdb->get_var($wpdb->prepare("SELECT id FROM {$ipt_rpm_info['reg_table']} WHERE code = %d AND portal = %d", $code, $portal))) {
                        $validate = false;
                        $msg = __('The code has already been taken for the specified portal.', 'ipt_rpm');
                    }
                }
            }
        }

        $json = array($field_id, $validate, $msg);
        echo json_encode($json);
        die();
    }

    public function save($ajax = true) {
        global $wpdb, $ipt_rpm_info;

        if(!current_user_can('ipt_rpm_new_reg') || !$this->check_authenticity(false)) {
            if($ajax === true) {
                echo json_encode(array('data_code', false, __('You do not have rights to add a new registration', 'ipt_rpm')));
                die();
            } else {
                $this->print_p_error(__('You do not have rights to add a new registration.', 'ipt_rpm'));
                return false;
            }
        }

        $data = (array) $this->post['data'];
        $pdata = (array) $this->post['pdata'];
        $reg = (array) $this->post['reg'];
        $regdata = (array) $this->post['reg_data'];

        $settings = get_option('ipt_rpm_settings');
        $regs = get_option('ipt_rpm_reg');
        $registrant_data = get_option('ipt_rpm_registrant_data');

        $errors = array();

        $portal = (int) $data['portal'];
        $code = (int) $data['code'];

        $user_action = 'add';
        $id = null;

        if(!empty($this->post['data']['id'])) {
            if(current_user_can('ipt_rpm_settings')) {
                $user_action = 'edit';
            } else {
                $user_action = 'renew';
            }
            $id = (int) $this->post['data']['id'];
        }

        //check for portal
        if(!isset($settings['portals'][$portal])) {
            $errors[] = array(
                'data_portal', false, __('The selected portal does not exist.', 'ipt_rpm'),
            );
        } else if(!in_array($portal, $this->portals)) {
            $errors[] = array(
                'data_portal', false, __('You do not have permission to select the specified portal.', 'ipt_rpm'),
            );
        } else if($user_action == 'renew' && $portal != $wpdb->get_var($wpdb->prepare("SELECT portal FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id))) {
            $errors[] = array(
                'data_portal', false, __('You can not change portal on renewal.', 'ipt_rpm'),
            );
        }

        //check for zero code value
        if($code == 0) {
            $errors[] = array(
                'data_code', false, __('The code can not be zero.', 'ipt_rpm'),
            );
        }

        //check for pre existance of code
        if($user_action == 'add') { //new code
            if($wpdb->get_var($wpdb->prepare("SELECT id FROM {$ipt_rpm_info['reg_table']} WHERE code = %d AND portal = %d", $code, $portal))) {
                $errors[] = array(
                    'data_code', false, __('The code is already in use.', 'ipt_rpm'),
                );
            }
        } else if($user_action == 'renew') { //same code
            if($code != $wpdb->get_var($wpdb->prepare("SELECT code FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id))) {
                $errors[] = array(
                    'data_code', false, __('You can not change the registration code.', 'ipt_rpm'),
                );
            }
        } else { //same or new code edit
            if($code != $wpdb->get_var($wpdb->prepare("SELECT code FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id))) {
                //new, hence verify
                if($wpdb->get_var($wpdb->prepare("SELECT id FROM {$ipt_rpm_info['reg_table']} WHERE code = %d AND portal = %d", $code, $portal))) {
                    $errors[] = array(
                        'data_code', false, __('The code is already in use.', 'ipt_rpm'),
                    );
                }
            }
        }


        //check for name
        $name = $data['name'];
        if('' == $name && $registrant_data['name']['enabled'] == true && $registrant_data['name']['required'] == true) {
            $errors[] = array(
                'data_name', false, __('Please enter a name.', 'ipt_rpm'),
            );
        }

        //check for email
        $email = $data['email'];
        if('' == $email && $registrant_data['email']['enabled'] == true && $registrant_data['email']['required'] == true) {
            $errors[] = array(
                'data_email', false, __('Please enter an email.', 'ipt_rpm'),
            );
        }
        if($registrant_data['email']['enabled'] == true && $email != '' && !is_email($email)) {
            $errors[] = array(
                'data_email', false, __('Please enter a valid email.', 'ipt_rpm'),
            );
        }

        //check for phone
        $phone = $data['phone'];
        if('' == $phone && $registrant_data['phone']['enabled'] == true && $registrant_data['phone']['required'] == true) {
            $errors[] = array(
                'data_phone', false, __('Please enter a phone number.', 'ipt_rpm'),
            );
        }

        //prepare the reg_data array
        $fees = 0;
        $reg_data = array();

        if(empty($reg)) {
            $errors[] = array(
                'reg_data_0_subscribed', false, __('Please select at least one.', 'ipt_rpm'),
            );
        } else {
            if($user_action == 'renew') {
                //lets say someone is trying to hack?
                $old_reg_data = maybe_unserialize($wpdb->get_var($wpdb->prepare("SELECT reg_data FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id)));
                foreach($old_reg_data as $old_reg_key => $val) {
                    if(!in_array($old_reg_key, $reg)) {
                        $errors[] = array(
                            'reg_data_' . $old_reg_key . '_subscribed', false, __('You can not remove already registered data.', 'ipt_rpm'),
                        );
                    }
                }
            }
            foreach($reg as $r_key) {
                $reg_data[$r_key]['subscribed'] = true;
                $reg_data[$r_key]['pdata'] = array();
                $fees += $regs[$r_key]['fee'];

                foreach((array) $regs[$r_key]['pdata'] as $pkey => $question) {
                    $answer = $regdata[$r_key][$pkey];
                    if($question['required'] == true && empty($answer) && $answer != '0') {
                        $errors[] = array(
                            'reg_data_' . $r_key . '_' . $pkey, false, __('Please answer this question.', 'ipt_rpm'),
                        );
                    } else {
                        $reg_data[$r_key]['pdata'][$pkey] = $answer;
                    }
                }
            }
        }

        //prepare the pdata array
        $p_data = array();
        foreach($registrant_data['others'] as $pkey => $question) {
            if($question['required'] == true && empty($pdata[$pkey]) && $pdata[$pkey] != '0') {
                $errors[] = array(
                    'pdata_' . $pkey, false, __('Please answer this question.', 'ipt_rpm'),
                );
            } else {
                $p_data[$pkey] = $pdata[$pkey];
            }
        }

        //prepare the user
        $user = get_current_user_id();

        //prepare the status
        $status = $data['status'];

        if(!empty($errors)) {
            $return = array(
                'status' => false,
                'errors' => $errors,
            );
        } else {
            $return = array(
                'status' => true,
                'id' => '',
            );

            if($id == null) { //new
                $log = array(
                    0 => array(
                        'date' => current_time('timestamp'),
                        'user' => $user,
                        'portal' => $portal,
                        'code' => $code,
                        'type' => 'created',
                        'regs' => $reg,
                        'status' => $status,
                        'fees' => $fees,
                    ),
                );

                $wpdb->insert($ipt_rpm_info['reg_table'], array(
                    'code' => $code,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'reg_data' => maybe_serialize($reg_data),
                    'p_data' => maybe_serialize($p_data),
                    'log' => maybe_serialize($log),
                    'portal' => $portal,
                    'user' => $user,
                    'status' => $status,
                    'fees' => $fees,
                    'created' => current_time('mysql'),
                ), array(
                    '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s'
                ));

                $return['id'] = $wpdb->insert_id;
            } else { //update
                $log = (array) maybe_unserialize($wpdb->get_var($wpdb->prepare("SELECT log FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id)));
                $log[] = array(
                    'date' => current_time('timestamp'),
                    'user' => $user,
                    'portal' => $portal,
                    'code' => $code,
                    'type' => 'updated',
                    'regs' => $reg,
                    'status' => $status,
                    'fees' => $fees,
                );
                $wpdb->update($ipt_rpm_info['reg_table'], array(
                    'code' => $code,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'reg_data' => maybe_serialize($reg_data),
                    'p_data' => maybe_serialize($p_data),
                    'log' => maybe_serialize($log),
                    'portal' => $portal,
                    'user' => $user,
                    'status' => $status,
                    'fees' => $fees,
                    'created' => current_time('mysql'),
                ), array(
                    'id' => $id,
                ), array(
                    '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s'
                ), '%d');

                $return['id'] = $id;
            }
        }

        if($ajax == true) {
            echo json_encode($return);
            die();
            return;
        } else {
            return $return;
        }
    }

    public function __construct($id = null) {
        if($id == null) {
            $this->data = null;
        } else {
            global $ipt_rpm_info, $wpdb;
            $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$ipt_rpm_info['reg_table']} WHERE id = %d", $id));
            if($data !== null) {
                $data->reg_data = maybe_unserialize($data->reg_data);
                $data->p_data = maybe_unserialize($data->p_data);
                $data->log = maybe_unserialize($data->log);
                $this->data = $data;
            } else {
                $this->data = null;
            }
        }

        $this->settings = get_option('ipt_rpm_settings');
        $this->regs = get_option('ipt_rpm_reg');
        $this->registrant_data = get_option('ipt_rpm_registrant_data');

        if(get_option('ipt_rpm_info')) {
            if(!did_action('init')) {
                add_action('init', array(&$this, 'init'));
            } else {
                $this->init();
            }
        }

        parent::__construct('ipt_rpm', false);
    }

    public function init() {
        $portals = array_keys((array) $this->settings['portals']);
        $this->portals_options = array();
        if(!current_user_can('ipt_rpm_settings')) {
            $portals = array();

            foreach((array) $this->settings['portals'] as $pkey => $portal) {
                if(in_array(get_current_user_id(), $portal['users'])) {
                    $portals[] = $pkey;
                }
            }
        }
        $this->portals = $portals;

        $this->selected_portal = $this->data == null ? null : $this->data->portal;

        foreach($this->portals as $portal) {
            $this->portals_options[$portal] = $this->settings['portals'][$portal]['name'];
            if($this->selected_portal === null)
                $this->selected_portal = $portal;
        }

    }

    public function show_data() {
        if(!$this->check_authenticity(true, false)) {
            return false;
        }
        if($this->data == null) {
            $this->print_p_error(__('Invalid code and portal combination or ID provided.', 'ipt_rpm'));
            return false;
        }
        if(!current_user_can('ipt_rpm_view_reg')) {
            $this->print_p_error(__('You do not have sufficient right to view the registrations.', 'ipt_rpm'));
            return false;
        }

        $current_user = new WP_User($this->data->user);
        ?>
<div id="ipt_rpm_registration_data">
    <h3><?php echo $this->settings['titles']['registrant']; ?></h3>
    <table class="widefat">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col"><?php _e('Information', 'ipt_rpm'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"><?php _e('Registration Code', 'ipt_rpm'); ?></th>
                <td>
                    <code><?php echo $this->settings['portals'][$this->data->portal]['prefix'] . $this->data->code; ?></code>
                </td>
            </tr>
            <?php
            $predefined_p_data = array(
                'name' => __('Name', 'ipt_rpm'),
                'email' => __('Email', 'ipt_rpm'),
                'phone' => __('Phone', 'ipt_rpm'),
            );
            ?>
            <?php foreach($predefined_p_data as $pdk => $label) : ?>
            <?php if(true == $this->registrant_data[$pdk]['enabled']) : ?>
            <tr>
                <th><label for="data_<?php echo $pdk; ?>"><?php echo $label; ?></label></th>
                <td>
                    <?php echo $this->data->$pdk; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>

            <?php foreach($this->data->p_data as $okey => $answer) : ?>
            <tr>
                <?php $this->gen_question($this->registrant_data['others'][$okey], 'pdata[' . $okey . ']', $answer, true); ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3><?php echo $this->settings['titles']['registration_topic']; ?></h3>
    <?php $total = 0; ?>
    <table class="widefat">
        <thead>
            <tr>
                <th scope="col"></th>
                <th colspan="2" scope="col" style="width: 60%;"><?php _e('Information', 'ipt_rpm'); ?></th>
                <th style="text-align: right" scope="col"><?php _e('Fees', 'ipt_rpm'); ?> <?php echo $this->settings['currency']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->data->reg_data as $reg_key => $reg_data) : ?>
            <?php
            $rowspan = 2 + count($reg_data['pdata']);
            $total += $this->regs[$reg_key]['fee'];
            $reg = $this->regs[$reg_key];
            ?>
            <tr>
                <th scope="row" rowspan="<?php echo $rowspan; ?>"><?php echo $reg['name']; ?></th>
                <td colspan="2" style="width: 60%;">
                    <label for="reg_data_<?php echo $reg_key ?>_subscribed">
                        <input type="checkbox" checked="checked" value="<?php echo $reg_key; ?>" />
                        <?php echo $reg['opt_in']; ?>
                    </label>
                </td>
                <td style="text-align: right" rowspan="<?php echo $rowspan; ?>"><?php echo $this->settings['currency']; ?>&nbsp;<?php echo number_format($reg['fee'], 2); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="width: 60%;">
                    <?php echo wpautop(wptexturize($reg['desc'])); ?>
                </td>
            </tr>
            <?php foreach($reg_data['pdata'] as $pkey => $answer) : ?>
            <tr>
                <?php $this->gen_question($reg['pdata'][$pkey], 'reg_data[' . $reg_key . '][' . $pkey . ']', $answer, true); ?>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">
                    <?php _e('Status:', 'ipt_rpm'); ?>
                    <?php echo ($this->data->status == 0 ? __('Unpaid', 'ipt_rpm') : __('Paid', 'ipt_rpm')); ?>
                </th>
                <th><?php _e('Total:', 'ipt_rpm'); ?></th>
                <th style="text-align: right" class="total" data-currency="<?php echo $this->settings['currency']; ?>" data-total-fee="<?php echo $total; ?>"><?php echo $this->settings['currency']; ?>&nbsp;<?php echo number_format($total, 2); ?></th>
            </tr>
            <?php if($total != $this->data->fee) : ?>
            <tr>
                <th colspan="3">
                    <?php echo sprintf(__('Checked on %s at %s', 'ipt_rpm'), date_i18n(get_option('date_format'), strtotime($this->data->created)), date_i18n(get_option('time_format'), strtotime($this->data->created))); ?>
                </th>
                <th style="text-align: right"><?php echo $this->settings['currency'] . '&nbsp;' . number_format($this->data->fees, 2); ?></th>
            </tr>
            <?php endif; ?>
        </tfoot>
    </table>
    <h3><?php echo $this->settings['titles']['registrar']; ?></h3>
    <table class="widefat">
        <thead>
            <tr>
                <th></th>
                <th scope="col"><?php _e('Information', 'ipt_rpm'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"><?php _e('ID', 'ipt_rpm'); ?></th>
                <td><?php echo $current_user->ID; ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Name', 'ipt_rpm'); ?></th>
                <td><?php echo $current_user->display_name; ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Email', 'ipt_rpm'); ?></th>
                <td><?php echo $current_user->user_email; ?></td>
            </tr>
            <tr>
                <th scope="row"><?php echo $this->settings['titles']['portal']; ?></th>
                <td>
                    <strong><?php echo $this->settings['portals'][$this->portals[$this->selected_portal]]['name']; ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
    <?php if(current_user_can('ipt_rpm_settings')) : ?>
    <h3><?php _e('System Logs', 'ipt_rpm'); ?></h3>
    <table class="widefat">
        <thead>
            <tr>
                <th rowspan="2"><?php _e('Date & Time', 'ipt_rpm'); ?></th>
                <th colspan="7"><?php _e('Access Information', 'ipt_rpm'); ?></th>

            </tr>
            <tr>
                <th><?php _e('Code', 'ipt_rpm'); ?></th>
                <th><?php _e('Access Type', 'ipt_rpm'); ?></th>
                <th><?php _e('Portal', 'ipt_rpm'); ?></th>
                <th><?php _e('User', 'ipt_rpm'); ?></th>
                <th><?php _e('Registered', 'ipt_rpm'); ?></th>
                <th><?php _e('Status', 'ipt_rpm'); ?></th>
                <th style="text-align: right"><?php _e('Fees', 'ipt_rpm'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->data->log as $log) : ?>
            <?php $user = new WP_User($log['user']); ?>
            <tr>
                <th scope="row">
                    <?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $log['date']); ?>
                </th>
                <td>
                    <code><?php echo $this->settings['portals'][$log['portal']]['prefix'] . $log['code']; ?></code>
                </td>
                <td><?php echo ucfirst($log['type']); ?></td>
                <td><?php echo $this->settings['portals'][$log['portal']]['name']; ?></td>
                <td><?php echo $user->display_name; ?></td>
                <td>
                    <ul class="ul-square">
                        <?php foreach((array) $log['regs'] as $reg_key) : ?>
                        <li>
                            <strong><?php echo $this->regs[$reg_key]['name']; ?></strong> <code><?php echo $this->settings['currency'] ?> <?php echo number_format($this->regs[$reg_key]['fee'], 2); ?></code>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </td>
                <td>
                    <?php echo ($log['status'] == 0 ? __('Unpaid', 'ipt_rpm') : __('Paid', 'ipt_rpm')); ?>
                </td>
                <td style="text-align: right">
                    <?php echo $this->settings['currency'] ?> <?php echo number_format($log['fees'], 2); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
<p class="submit">
    <input id="print_button" type="button" class="button-primary" value="<?php _e('Print', 'ipt_rpm'); ?>" />
</p>
<script type="text/javascript">
    jQuery('#print_button').click(function() {
        jQuery('#ipt_rpm_registration_data').printElement({
            printMode : 'popup'
        });
    });
</script>
        <?php
    }

    public function show_form() {
        if(!$this->check_authenticity())
            return false;
        $current_user = wp_get_current_user();

        $user_action = 'add';
        if($this->data != null && !current_user_can('ipt_rpm_settings')) {
            $user_action = 'renew';
        } else if ($this->data != null && current_user_can('ipt_rpm_settings')) {
            $user_action = 'edit';
        }
        ?>
<?php if($this->data != null) : ?>
<input type="hidden" name="data[id]" id="data_id" value="<?php echo $this->data->id; ?>" />
<?php else : ?>
<input type="hidden" name="data[id]" id="data_id" value="" />
<?php endif; ?>
<input type="hidden" name="user_action" id="user_action" value="<?php echo $user_action; ?>" />
<h3><?php echo $this->settings['titles']['registrant']; ?></h3>
<table class="widefat">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col"><?php _e('Information', 'ipt_rpm'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><code style="float: right;" id="ipt_rpm_portal_prefix"><?php echo $this->settings['portals'][$this->selected_portal]['prefix']; ?></code><?php _e('Registration Code', 'ipt_rpm'); ?></th>
            <td>
                <input type="hidden" id="ipt_rpm_portal_prefix_input" name="ipt_rpm_portal_prefix_input" value="<?php echo $this->selected_portal; ?>" />
                <input type="text"<?php if($user_action == 'renew') echo ' readonly="readonly"'; ?> name="data[code]" value="<?php echo ($this->data == null ? '' : $this->data->code); ?>" class="regular-text code validate[onlyNumber<?php if($user_action != 'renew') : ?>,ajax[ipt_rpm_reg_code_ajax]<?php endif; ?>]" id="data_code" />
                <?php if($user_action == 'add') : ?>
                <input type="button" class="button-secondary" id="auto_button" value="<?php _e('Generate', 'ipt_rpm'); ?>" />
                <?php endif; ?>
            </td>
        </tr>
        <?php
        $predefined_p_data = array(
            'name' => __('Name', 'ipt_rpm'),
            'email' => __('Email', 'ipt_rpm'),
            'phone' => __('Phone', 'ipt_rpm'),
        );
        ?>
        <?php foreach($predefined_p_data as $pdk => $label) : ?>
        <?php if(true == $this->registrant_data[$pdk]['enabled']) : ?>
        <tr>
            <th><label for="data_<?php echo $pdk; ?>"><?php echo $label; ?></label></th>
            <td>
                <?php $this->print_input_text('data[' . $pdk . ']', ($this->data != null ? $this->data->$pdk : ''), $this->registrant_data[$pdk]['required'], 'regular-text', false, $pdk); ?>
            </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach($this->registrant_data['others'] as $okey => $oquestion) : ?>
        <tr>
            <?php $this->gen_question($oquestion, 'pdata[' . $okey . ']', ($this->data == null ? null : $this->data->p_data[$okey])); ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3><?php echo $this->settings['titles']['registration_topic']; ?></h3>
<?php $total = 0; ?>
<table class="widefat">
    <thead>
        <tr>
            <th scope="col"></th>
            <th colspan="2" scope="col" style="width: 60%;"><?php _e('Information', 'ipt_rpm'); ?></th>
            <th style="text-align: right" scope="col"><?php _e('Fees', 'ipt_rpm'); ?> <?php echo $this->settings['currency']; ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->regs as $reg_key => $reg) : ?>
        <?php

        $checked = $this->data == null ? false : isset($this->data->reg_data[$reg_key]['subscribed']) && $this->data->reg_data[$reg_key]['subscribed'] == true ? true : false;
        if($checked == true)
            $total += $reg['fee'];

        $rowspan = 2;
        if($checked) {
            $rowspan += count($reg['pdata']);
        }
        ?>
        <tr>
            <th scope="row" rowspan="<?php echo $rowspan; ?>"><?php echo $reg['name']; ?></th>
            <td colspan="2" style="width: 60%;">
                <label for="reg_data_<?php echo $reg_key ?>_subscribed">
                    <input<?php if($user_action == 'renew' && $checked) echo ' disabled="disabled"'; ?> type="checkbox" class="tr-toggle validate[minCheckbox[1]]" data-reg-fee="<?php echo floatval($reg['fee']); ?>" data-trs="tr-toggle-<?php echo $reg_key ?>" id="reg_data_<?php echo $reg_key ?>_subscribed" name="reg[]"<?php if($checked) echo ' checked="checked"'; ?> value="<?php echo $reg_key; ?>" />
                    <?php if($user_action == 'renew' && $checked) : ?>
                    <input type="hidden" name="reg[]" value="<?php echo $reg_key; ?>" />
                    <?php endif; ?>
                    <?php echo $reg['opt_in']; ?>
                </label>
            </td>
            <td style="text-align: right" rowspan="<?php echo $rowspan; ?>"><?php echo $this->settings['currency']; ?>&nbsp;<?php echo number_format($reg['fee'], 2); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="width: 60%;">
                <?php echo wpautop(wptexturize($reg['desc'])); ?>
            </td>
        </tr>
        <?php foreach($reg['pdata'] as $pkey => $question) : ?>
        <tr class="tr-toggle-<?php echo $reg_key; ?>"<?php if(!$checked) echo ' style="display: none"'; ?>>
            <?php $this->gen_question($question, 'reg_data[' . $reg_key . '][' . $pkey . ']', ($this->data == null ? null : $this->data->reg_data[$reg_key]['pdata'][$pkey])); ?>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">
                <label for="data_status"><?php _e('Status:', 'ipt_rpm'); ?></label>
                <select name="data[status]" id="data_status">
                    <?php $this->print_select_op(array(
                        0 => __('Unpaid', 'ipt_rpm'),
                        1 => __('Paid', 'ipt_rpm'),
                    ), ($this->data == null ? '1' : $this->data->status), true); ?>
                </select>
            </th>
            <th><?php _e('Total:', 'ipt_rpm'); ?></th>
            <th style="text-align: right" class="total" data-currency="<?php echo $this->settings['currency']; ?>" data-total-fee="<?php echo $total; ?>"><?php echo $this->settings['currency']; ?>&nbsp;<?php echo number_format($total, 2); ?></th>
        </tr>
    </tfoot>
</table>
<h3><?php echo $this->settings['titles']['registrar']; ?></h3>
<table class="widefat">
    <thead>
        <tr>
            <th></th>
            <th scope="col"><?php _e('Information', 'ipt_rpm'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><?php _e('ID', 'ipt_rpm'); ?></th>
            <td><?php echo $current_user->ID; ?></td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Name', 'ipt_rpm'); ?></th>
            <td><?php echo $current_user->display_name; ?></td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Email', 'ipt_rpm'); ?></th>
            <td><?php echo $current_user->user_email; ?></td>
        </tr>
        <tr>
            <th scope="row"><?php echo $this->settings['titles']['portal']; ?></th>
            <td>
                <?php if(count($this->portals) == 1) : ?>
                <input type="hidden" name="data[portal]" value="<?php echo $this->portals[0]; ?>" />
                <strong><?php echo $this->settings['portals'][$this->portals[0]]['name']; ?></strong>
                <?php elseif($user_action == 'renew') : ?>
                <input type="hidden" name="data[portal]" value="<?php echo $this->selected_portal; ?>" />
                <strong><?php echo $this->settings['portals'][$this->selected_portal]['name']; ?></strong>
                <?php else : ?>
                <select class="widefat" id="data_portal" name="data[portal]">
                    <?php foreach($this->portals_options as $key => $label) : ?>
                    <option value="<?php echo $key; ?>" data-prefix="<?php echo $this->settings['portals'][$key]['prefix'] ?>"<?php if($key == $this->selected_portal) echo ' selected="selected"' ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>
        <?php
    }

    public function gen_question($question, $name, $data = null, $read = false) {
        $id = str_replace(array('[', ']'), array('_', ''), $name);
        ?>
<th scope="row">
    <?php if($question['type'] == 'multiple' || $question['type'] == 'single') : ?>
    <span id="<?php echo $id; ?>"><?php echo $question['question']; ?></span>
    <?php else : ?>
    <label for="<?php echo $id; ?>"><?php echo $question['question']; ?></label>
    <?php endif; ?>
</th>
<td>
    <?php
    switch($question['type']) {
        case 'smalltext' :
            if($read) {
                echo $data;
            } else {
                $this->print_input_text($name, $data, $question['required']);
            }
            break;
        case 'largetext' :
            if($read) {
                echo wpautop(wptexturize($data));
            } else {
                $this->print_textarea($name, $data, $question['required']);
            }
            break;
        case 'checkbox' :
            echo '<input type="checkbox" name="' . $name . '" id="' . $id . '" value="1" ' . ($data == true ? 'checked="checked" ' : '') . '/>';
            break;
        case 'single' :
            $options = $this->split_options($question['options']);
            $class = '';
            if($question['required']) {
                $class = 'validate[required]';
            }
            echo '<ul style="list-style: none">';
            foreach($options as $okey => $oname) {
                ?>
    <li style="list-style: none"><label for="<?php echo $id . '_' . $okey; ?>">
        <input class="<?php echo $class; ?>" type="radio" value="<?php echo $okey; ?>" name="<?php echo $name; ?>"<?php if($data != null && $okey == $data) echo ' checked="checked"'; ?> id="<?php echo $id . '_' . $okey; ?>" /> <?php echo $oname; ?>
    </label></li>
                <?php
            }
            echo '</ul>';
            break;
        case 'multiple' :
            $options = $this->split_options($question['options']);
            $class = '';
            if($question['required']) {
                $class = 'validate[minCheckbox[1]]';
            }
            echo '<ul style="list-style: none">';
            foreach($options as $okey => $oname) {
                ?>
    <li style="list-style: none"><label for="<?php echo $id . '_' . $okey; ?>">
        <input class="<?php echo $class; ?>" type="checkbox" value="<?php echo $okey; ?>" name="<?php echo $name; ?>[]"<?php if($data != null && in_array($okey, (array) $data)) echo ' checked="checked"'; ?> id="<?php echo $id . '_' . $okey; ?>" /> <?php echo $oname; ?>
    </label></li>
                <?php
            }
            echo '</ul>';
            break;
    }
    ?>
</td>
        <?php
    }

    private function split_options($option) {
        $option = explode("\n", str_replace("\r", '', $option));
        $clean = array();
        array_walk($option, 'trim');
        foreach($option as $v) {
            if('' != $v)
                $clean[] = $v;
        }
        return $clean;
    }

    /**
     * @deprecated since 1.0.0
     * @param type $value
     */
    protected function clean_options(&$value) {
        $value = htmlspecialchars(trim(strip_tags(htmlspecialchars_decode($value))));
    }


    public function check_authenticity($echo = true, $verify_portal = true) {
        //safety check
        if($verify_portal) {
            if($this->data != null && !in_array($this->data->portal, $this->portals)) {
                $current_user = wp_get_current_user();
                $msg = sprintf(__("Howdy,\r\nJust thought you\'d like to get notified that there has been some illegal activity inside the Registration Portal Management System. Here is the summary.\r\n\r\nThe User named %s, ID %d, email %s tried to illegally access the registration entry ID %d.\r\n\r\nHope the information was useful.", 'ipt_rpm'), $current_user->display_name, $current_user->ID, $current_user->email, $data->id);
                $msg .= __("\r\n\r\n\r\nThis is an autogenerated email. You are receiving this because you are the admin of the website.", 'ipt_rpm');
                wp_mail(get_option('admin_email'), sprintf(__('[%s]Notification Illegal Activity on Registration Management System', 'ipt_rpm'), get_bloginfo('name')), $msg);
                $this->print_p_error(__('You do not have right to edit and/or view this submission. The administrator of this website has been notified about this mischievous activity.', 'ipt_rpm'), $echo);
                return false;
            }
            //portal availability
            if(count($this->portals) == 0) {
                $this->print_p_error(__('No portals have been assigned to you. Please ask your administrator to fix this.', 'ipt_rpm'), $echo);
                return false;
            }
        }


        //minimal right
        if(!current_user_can('ipt_rpm_view_dashboard')) {
            $this->print_p_error(__('You do not have minimal rights to access the registration system.', 'ipt_rpm'), $echo);
            return false;
        }
        return true;
    }

    public function index() {

    }

    public function print_textarea($name, $value, $required = true, $class = 'regular-text', $rows = 3) {
        if($required)
            $class .= ' validate[required]';
        $class .= ' widefat';
        parent::print_textarea($name, $value, $class, $rows);
    }

    public function print_input_text($name, $value, $required = true, $class = 'regular-text', $name_only = false, $type = '') {
        $validate = array();

        if($required)
            $validate[] = 'required';
        switch($type) {
            case 'email' :
                $validate[] = 'custom[email]';
                break;
            case 'phone' :
                $validate[] = 'custom[phone]';
                break;
            case 'url' :
                $validate[] = 'custom[url]';
                break;
            case 'date' :
                $validate[] = 'custom[date]';
        }

        if(!empty($validate)) {
            $class .= ' validate[' . implode(',', $validate) . ']';
        }
        parent::print_input_text($name, $value, $class, $name_only);
    }

    public function enqueue() {
        wp_enqueue_script('ipt_rpm_validation', $this->url['js'] . 'jquery.validationEngine.js', array('jquery'), ipt_rpm_loader::$version);
        wp_enqueue_script('ipt_rpm_validation_en', $this->url['js'] . 'jquery.validationEngine-en.js', array('jquery'), ipt_rpm_loader::$version);

        wp_enqueue_script('ipt_rpm_reg_form', $this->url['js'] . 'reg_form.js', array('jquery'), ipt_rpm_loader::$version);

        wp_enqueue_style('ipt_rpm_validation_css', $this->url['css'] . 'validationEngine.jquery.css', array(), ipt_rpm_loader::$version);
    }
}
