<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
require_once dirname( __FILE__ ) . '/class.settings-api.php';

if ( ! class_exists('MDC_Theme_Switcher_Settings' ) ):
class MDC_Theme_Switcher_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->sections() );
        $this->settings_api->set_fields( $this->fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_menu_page('MDC Theme Switcher', 'Theme Switcher', 'administrator', 'mdc-theme-switcher', array($this, 'option_page'), plugins_url( '../assets/img/icon.png', __FILE__), '60.25');
    }

    function sections() {
        $sections = array(
            array(
                'id' => 'mdc_theme_switcher',
                'title' => 'Theme Switcher Settings',
            ),
            
        );
        return $sections;
    }


    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function fields() {
        $settings_fields = array(
			//------------------------------------------------
			//      Basic Settings
			//------------------------------------------------
            'mdc_theme_switcher' => array(
                array(
                    'name'              => 'enable_sticky_bar',
                    'label'             => 'Enable Sticky Bar?',
                    'desc'              => 'Check this to enable \'Theme Switcher\' Sticky Bar',
                    'type'              => 'checkbox',
                ),
                array(
                    'name'              => 'sticky_bar_position',
                    'label'             => 'Sticky Bar Position',
                    'desc'              => 'If enabled.',
                    'type'              => 'radio',
                    'default'           => 'top',
                    'options'           =>  array(
                        'top'       =>  'Top',
                        'bottom'    =>  'Bottom'
                        )
                ),
                array(
                    'name'              => 'hide_site_title',
                    'label'             => 'Hide Site Title?',
                    'desc'              => 'Check this to hide site title in \'Theme Switcher\' Sticky Bar',
                    'type'              => 'checkbox',
                ),
                array(
                    'name'              => 'available_themes',
                    'label'             => 'Themes',
                    'desc'              => 'Select themes that you want to show in \'Theme Switcher\'',
                    'type'              => 'multicheck',
                    'options'           => $this->themes_list()
                ),
                array(
                    'name'              => 'hide_credit',
                    'label'             => 'Remove Developer Credit?',
                    'desc'              => 'Check this to hide developer credit from \'Theme Switcher\' Sticky Bar :-( ',
                    'type'              => 'checkbox',
                ),
                array(
                    'name'              => 'enable_shortcode',
                    'label'             => 'Enable Shortcode?',
                    'desc'              => 'Check this if you want to enable shortcode <code>[mdc_theme_switcher]</code> that can be used in posts, pages, widgets or in template files.',
                    'type'              => 'checkbox',
                ),
                array(
                    'name'              => 'custom_css',
                    'label'             => 'Custom CSS',
                    'desc'              => 'Custom CSS',
                    'type'              => 'textarea',
                ),
            ),
           
        );

        return $settings_fields;
    }

    function option_page() {
        ?>
        <div class="wrap">
		
    		<div class="scroll-to-up-setting-page-title">
    			<h1><img class="close-icon" title="Close" src="<?php echo plugins_url( '../assets/img/icon.png', __FILE__ ); ?>"> MDC Theme Switcher</h1>
    			<!-- <p class="description">"Scroll To Up" Plugin Settings Page</p> -->
    		</div>

    		<div class="mdc-col-left">
    			<?php 
    			// $this->settings_api->show_navigation();
    			$this->settings_api->show_forms(); ?>
    		</div>

    		<div class="mdc-col-right">
				<h2>Plugins from <a href="http://medhabi.com" target="_blank">MedhabiDotCom</a></h2>
				<div>
                    <ul>
                        <li><span class="dashicons-before dashicons-admin-plugins"></span><a href="https://wordpress.org/plugins/mdc-youtube-downloader/" target="_blank">MDC YouTube Downloader</a> - <span class="description">Insert 'downloadable' videos into your posts and pages.</span></li>
                        <li><span class="dashicons-before dashicons-admin-plugins"></span><a href="https://wordpress.org/plugins/mdc-theme-switcher/" target="_blank">MDC Theme Switcher</a> - <span class="description">Change and preview among available themes from frontend.</span></li>
                        <li><span class="dashicons-before dashicons-admin-plugins"></span><a href="https://wordpress.org/plugins/mdc-comment-toolbar/" target="_blank">MDC Comment Toolbar</a> - <span class="description">Enable rich WYSIWYG editor with media uploader in comment field.</span></li>
                        <li><span class="dashicons-before dashicons-admin-plugins"></span><a href="https://wordpress.org/plugins/mdc-target-blank/" target="_blank">MDC Target Blank</a> - <span class="description">Force links in posts or pages to open in a new tab.</span></li>
                        <li><span class="dashicons-before dashicons-admin-plugins"></span><a href="https://wordpress.org/plugins/mdc-scroll-to-top/" target="_blank">MDC Scroll To Top</a> - <span class="description">Add a 'Scroll To Top' button to your WordPress site.</span></li>
                        <li><span class="dashicons-before dashicons-admin-plugins"></span><a href="https://profiles.wordpress.org/mukto90/#content-plugins" target="_blank">And many more..</a></li>
                    </ul>

                </div>
                <h2>Like us on facebook</h2>
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=821914824550046";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-like" data-href="https://www.facebook.com/MedhabiDotCom/" data-width="300" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
    		</div>

        </div>
        <style type="text/css">
        .mdc-col-left{
            float: left;
            max-width: 760px;
            margin-right: 14px;
        }
        .mdc-col-right{
            background: #fff none repeat scroll 0 0;
            border: 1px solid #ddd;
            border-radius: 2px;
            float: left;
            padding: 0 5px 5px;
            width: 320px;
        }
        </style>
    <?php
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

    function themes_list(){
        $arg = array(
            'errors' => false,
            'allowed' => null,
            'blog_id' => 0
        );
        return wp_get_themes($arg);
    }

}
endif;


/**
 * Get the value of a settings field
 *
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 * @return mixed
 */
function mdc_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}

new MDC_Theme_Switcher_Settings;