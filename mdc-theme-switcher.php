<?php
/**
Plugin Name: MDC Theme Switcher
Plugin URI: http://medhabi.com/items/mdc-theme-switcher/
Description: MDC Theme Switcher allows to change and preview among available themes of a WordPress from front-end.
Author: Nazmul Ahsan
Version: 3.1.0
Author URI: http://nazmulahsan.me
Stable tag: 3.1.0
License: GPL2+
Text Domain: mdc-theme-switcher
*/

/**
 * Include plugin option page
 */
require_once dirname( __FILE__ ) . '/admin/mdc-theme-switcher-settings.php';

class MDC_Theme_Switcher{

	public $default_theme;
	public $enable_sticky_bar;
	public $sticky_bar_position;
	public $hide_site_title;
	public $available_themes;
	public $hide_credit;
	public $enable_shortcode;
	public $custom_css;
	
	public function __construct(){
		self::init();

		add_action( 'after_switch_theme', array( $this, 'new_theme_from_admin' ) );
		add_action( 'plugins_loaded', array( $this, 'cookie_theme_switch' ) );
		add_action( 'wp_head', array( $this, 'ajax_url' ) );

		if ( $this->enable_sticky_bar == 'on' ) {
			add_action( 'wp_footer', array( $this, 'sticky_bar' ) );
			add_action( 'wp_head', array( $this, 'custom_css' ), 99 );
		}
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_mdc_change_theme', array( $this, 'ajax_theme_switch' ) );
		add_action( 'wp_ajax_nopriv_mdc_change_theme', array( $this, 'ajax_theme_switch' ) );
		if( $this->enable_shortcode == 'on' ){
			add_action( 'widget_text', 'do_shortcode' );
			add_shortcode( 'mdc_theme_switcher', array( $this, 'themes_list' ) );
		}
	}

	public function init(){
		$this->default_theme = get_option( 'stylesheet' );
		$this->enable_sticky_bar = mdc_get_option( 'enable_sticky_bar', 'mdc_theme_switcher', 'off' );
		$this->sticky_bar_position = mdc_get_option( 'sticky_bar_position', 'mdc_theme_switcher', 'top' );
		$this->hide_site_title = mdc_get_option( 'hide_site_title', 'mdc_theme_switcher', 'off' );
		$this->available_themes = mdc_get_option( 'available_themes', 'mdc_theme_switcher', $this->themes_list() );
		$this->hide_credit = mdc_get_option( 'hide_credit', 'mdc_theme_switcher', 'off' );
		$this->enable_shortcode = mdc_get_option( 'enable_shortcode', 'mdc_theme_switcher', 'off' );
		$this->custom_css = mdc_get_option( 'custom_css', 'mdc_theme_switcher', '' );
	}

	public function enqueue_scripts(){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'mdc-theme-switcher', plugins_url( 'assets/css/style.css', __FILE__ ) );
		wp_enqueue_script( 'mdc-theme-switcher', plugins_url( 'assets/js/script.js', __FILE__ ) );
	}

	public function custom_css(){
		$css = "<style type='text/css'>";
		if ( $this->sticky_bar_position == "top" ){
			if( ! is_admin_bar_showing() ){
				$css .= "
				.mdc-theme-switcher{top : 0px}
				html { margin-top: 32px !important; }
				* html body { margin-top: 32px !important; }
				";
			}
			else{
				$css .= "
				html { margin-top: 64px !important; }
				* html body { margin-top: 64px !important; }
				@media screen and ( max-width: 782px ) {
					html { margin-top: 78px !important; }
					* html body { margin-top: 78px !important; }
				}";
			}
		}
		else{
			$css .= ".mdc-theme-switcher{bottom : 0px}";
		}

		$css .= $this->custom_css."\n";
		$css .= "</style>";

		echo $css;
	}

	public function ajax_url(){ ?>
		<script type="text/javascript"> //<![CDATA[
			ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		//]]> 
		</script>
	<?php }

	public function themes_list(){
		$t_list ='<select class="mdc-choose-theme">';
		if( is_array( $this->available_themes ) ){
			$t_list .='<option disabled >Select a Theme</option>';
			foreach ( $this->available_themes as $stylesheet=>$name ) {
				$t_list .= '<option value="'.$stylesheet.'" '.selected( get_option( 'stylesheet' ), $stylesheet, false ).'>'.$name.'</option>';
			}
		}
		else{
			$t_list .='<option disabled selected >No themes to choose</option>';
		}
		$t_list .= '</select>';

		return $t_list;
	}

	public function sticky_bar(){ ?>
		<div class="mdc-theme-switcher mdc-position-<?php echo $this->sticky_bar_position; ?>">
			<div class="mdc-row">
				<?php if( $this->hide_site_title != 'on' ): ?>
				<div class="mdc-site-title">
					<a href="<?php echo get_bloginfo( 'url' ); ?>"><?php echo get_bloginfo(); ?></a>
				</div>
			<?php endif; ?>

				<div class="mdc-themes-list">
					<?php echo $this->themes_list(); ?>
				</div>

				<?php do_action( 'mdc_theme_switcher_sticky_bar' ); ?>

				<?php if( $this->hide_credit != 'on' ): ?>
				<div class="mdc-dev-credit">
					<a href="http://medhabi.com" target="_blank">Powered by MedhabiDotCom</a>
				</div>
			<?php endif; ?>

			</div>
		</div>
		<div class="toggle-theme-switcher mdc-position-<?php echo $this->sticky_bar_position; ?>">
			<img class="close-icon" title="Close" src="<?php echo plugins_url( 'assets/img/icon.png', __FILE__ ); ?>">
		</div>
	<?php }

	public function ajax_theme_switch(){
		$new_theme = $_POST['new_theme'];
		setcookie('mdc_active_theme', $new_theme, time()+2592000, COOKIEPATH, COOKIE_DOMAIN);
		if ( ! is_admin() ) {
			switch_theme($new_theme);
		}
		die();
	}

	/**
	 * @since 3.0.0
	 */
	public function cookie_theme_switch(){
		if(isset($_COOKIE['mdc_active_theme'])){
			/**
			 * check either from backend
			 * @since 3.1.0
			 */
			if ( ! is_admin() ) {
				if( get_option( 'mdc_new_admin_theme' ) == 1 ){
					switch_theme( $this->default_theme );
					delete_option( 'mdc_new_admin_theme' );
					setcookie( 'mdc_active_theme', $this->default_theme, time()+2592000, COOKIEPATH, COOKIE_DOMAIN );
				}
				else{
					switch_theme( $_COOKIE['mdc_active_theme'] );
				}
			}
		}
		else{
			setcookie( 'mdc_active_theme', $this->default_theme, time()+2592000, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * @since 3.1.0
	 */
	public function new_theme_from_admin(){
		if ( is_admin() ) {
			update_option('mdc_new_admin_theme', 1);
		}
	}

}
new MDC_Theme_Switcher();