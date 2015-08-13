<?php
/****
	* Plugin Name: MDC Theme Switcher
	* Plugin URI: http://medhabi.com/items/mdc-theme-switcher/
	* Description: MDC Theme Switcher allows to change and preview among available themes of a WordPress from front-end.
	* Author: Nazmul Ahsan
	* Version: 2.0.1
	* Author URI: http://medhabi.com
	* Stable tag: 2.0.1
	* License: GPL2+
	* Text Domain: MedhabiDotCom
****/

require_once('includes/mdc-option-page.php');
class MDC_Theme_Switcher{
	
	public function __construct(){
		if (get_option('mdc_show_sticky_bar') == 1) {
			add_action('wp_head', array($this, 'mdc_show_sticky_bar'));
		}
		add_action('wp_head', array($this, 'mdc_declare_admin_ajax_url'));
		if (get_option('mdc_show_sticky_bar') == 1) {
			add_action('wp_head', array($this, 'mdc_custom_css'));
		}
		add_action('wp_enqueue_scripts', array($this, 'mdc_enqueue_scripts'));
		add_action('wp_ajax_mdc_change_theme', array($this, 'mdc_process_ajax_request'));
		add_action('wp_ajax_nopriv_mdc_change_theme', array($this, 'mdc_process_ajax_request'));
		if(get_option('mdc_ts_shortcode_enable') == 1){
			add_action('widget_text', 'do_shortcode');
			add_shortcode('mdc_theme_switcher', array($this, 'mdc_themes_form'));
		}
	}

	public function mdc_enqueue_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('mdc-theme-switcher', plugins_url('js/custom.js', __FILE__));
		wp_enqueue_style('mdc-theme-switcher', plugins_url('css/custom.css', __FILE__));
	}

	public function mdc_custom_css(){
		if (is_user_logged_in()) {
			$position = (get_option('mdc_sticky_bar_position') == "top") ? "top : 32px" : "bottom : 0px";
		}
		else{
			$position = (get_option('mdc_sticky_bar_position') == "top") ? "top : 0px" : "bottom : 0px";
		}
		$css = "
<style type='text/css'>
/* body { padding-top: 38px; } */
.mdc_sticky_bar { $position; }\n\r";
$css .= get_option('mdc_theme_switcher_custom_css')."\n\r";
$css .= "</style>";

		echo $css;
	}

	public function mdc_declare_admin_ajax_url(){ ?>
		<script type="text/javascript"> //<![CDATA[
			ajaxurl = '<?php echo admin_url( 'admin-ajax.php'); ?>';
		//]]> </script>
	<?php }

	public function isSSL() {
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
	}
	public function mdc_themes_form(){
		$selected_themes = get_option('mdc_themes_to_show');
		// echo "<pre>"; print_r($themes); echo "</pre>";
		if($this->isSSL()){
			$protocol = "https://";
		}
		else{
			$protocol = "http://";
		}
		$active_theme = get_option('stylesheet');
		$current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$list_theme ='<form>
			<input type="hidden" name="redirect_to" class="mdc_redirect_to" value="'.$current_url.'">
			<select class="mdc_choose_theme">';
		$list_theme .='<option disabled>Select a Theme</option>';
		if(is_array($selected_themes)){
			foreach ($selected_themes as $stylesheet=>$name) {
				if($stylesheet == $active_theme){ $selected = " selected";}
				$list_theme .= '<option'.$selected.' value="'.$stylesheet.'">'.$name.'</option>';
				$selected = '';
			}
		}
		$list_theme .= '</select>
			</form>';

		return $list_theme;
	}

	public function mdc_show_sticky_bar(){
		$bar_html = '
			<div class="mdc_sticky_bar">
				<div class="mdc_sticky_bar_left">
					<label>'.get_bloginfo().'</label>
				</div>

				<div class="mdc_sticky_bar_center">
			';
		$bar_html .= $this->mdc_themes_form();
		$bar_html .= '
				</div>

				<div class="mdc_sticky_bar_right">';
		if(get_option('mdc_ts_remove_credit') != 1) {
			$bar_html .= '<p>Powered by <a href="https://wordpress.org/plugins/mdc-theme-switcher/" target="_blank">MDC Theme Switcher</a></p>';
		}
		$bar_html .= '</div>
			</div>
			<div class="mdc_clear"></div>
			';

		echo $bar_html;
		// echo "<pre>"; var_dump($themes); echo "</pre>";
	}

	public function mdc_process_ajax_request(){
		$new_theme = $_POST['new_theme'];
		$redirect_to = $_POST['redirect_to'];
		switch_theme($new_theme);
		setcookie('mdc_active_theme', $new_theme, time()+300);
		echo $redirect_to;
		die();
		// wp_redirect(get_permalink());
	}

}
$mdc_theme_switcher = new MDC_Theme_Switcher();