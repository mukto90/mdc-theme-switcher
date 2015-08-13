<?php 
class MDC_Theme_Switcher_option_page{

	public function __construct(){
		add_action( 'admin_menu', array($this, 'mdc_option_page') );
		add_action( 'admin_enqueue_scripts', array($this, 'mdc_admin_enqueue_scripts') );
	}

	public function mdc_admin_enqueue_scripts(){
		wp_enqueue_style( 'mdc_admin_custom', plugins_url('../css/admin.css', __FILE__) );
		wp_enqueue_script( 'mdc_admin_custom', plugins_url('../js/admin.js', __FILE__) );
	}

	public function mdc_option_page(){
		add_menu_page('MDC Theme Switcher', 'Theme Switcher', 'administrator', 'mdc-theme-switcher', array($this, 'mdc_theme_switcher_options'), plugins_url( '../images/icon.png', __FILE__), '60.25');
	}

	public function mdc_theme_switcher_options(){ ?>
		<div class="wrap">
			<h2><img src="<?php echo plugins_url( '../images/icon.png', __FILE__); ?>"> MDC Theme Switcher</h2>
			<div style="clear: left"></div>
			<div class="postbox-container" style="width: 100%">
				<div id="poststuff" class="metabox-holder">
					<div id="normal-sortables" class="meta-box-sortables">
						<div id="mdc_ts_opt_page" class="postbox ">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle"><span>Default Settings</span></h3>
							<div class="inside">
								<div class="option_page_right">
									<div class="mdc_ts_dl_pro">
										<h3 class="mdc_ts_dl_pro_ttl">WANT TO DOWNLOAD VIDEOS FROM POSTS AND PAGES?</h3>
										<div class="pro_logo">
											<a href="http://medhabi.com/items/mdc-youtube-downloader-pro/" target="_blank"><img src="<?php echo plugins_url('../images/pro-logo.png', __FILE__);?>"></a>
										</div>
										<h3 class="upgrade_today">MDC YouTube Downloader Pro</h3>
										<div class="get_pro_div">
											<a href="http://medhabi.com/items/mdc-youtube-downloader-pro/" target="_blank"><button class="get_pro_btn">Get Now</button></a>
											<hr />
											<a href="http://www.medhabi.com/" target="_blank"><img alt="MedhabiDotCom - One Stop Tech Solution" class="mdc_logo" src="http://www.medhabi.com/wp-content/uploads/2014/12/medhabidotcom.png">
											<i>www.medhabi.com</i></a>
										</div>
									</div>
								</div>
								<div class="option_page_left">
									<?php
									if(isset($_POST['mdc_update'])){
										update_option('mdc_show_sticky_bar', $_POST['mdc_show_sticky_bar']);
										update_option('mdc_sticky_bar_position', $_POST['mdc_sticky_bar_position']);
										update_option('mdc_themes_to_show', $_POST['mdc_themes_to_show']);
										update_option('mdc_ts_shortcode_enable', $_POST['mdc_ts_shortcode_enable']);
										update_option('mdc_theme_switcher_custom_css', $_POST['mdc_theme_switcher_custom_css']);
										update_option('mdc_ts_remove_credit', $_POST['mdc_ts_remove_credit']);
									?>
									<div class="updated settings-error" id="setting-error-settings_updated"> 
										<p><strong>Settings saved.</strong></p>
									</div>
									<?php } ?>
									<form action="" method="post">
										<input type="hidden" name="mdc_update" />
										<table class="form-table">
											<tbody>
												<tr valign="top">
													<th scope="row"><label for="mdc_show_sticky_bar">Show Sticky Bar</label></th>
													<td><input type="checkbox" value="1" id="mdc_show_sticky_bar" name="mdc_show_sticky_bar" <?php if(get_option('mdc_show_sticky_bar') == 1){echo "checked";}?> /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(If you want to show Sticky Bar, check this.)</small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_sticky_bar_position">Sticky Bar Position (if shown)</label></th>
													<td>
														<input type="radio" value="top" id="mdc_sticky_bar_position_top" name="mdc_sticky_bar_position" <?php if(get_option('mdc_sticky_bar_position') == "top"){echo "checked";}?> /><label for="mdc_sticky_bar_position_top">Top</label>
														<input type="radio" value="bottom" id="mdc_sticky_bar_position_bottom" name="mdc_sticky_bar_position" <?php if(get_option('mdc_sticky_bar_position') == "bottom"){echo "checked";}?> /><label for="mdc_sticky_bar_position_bottom">Bottom</label>
														<span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(Showing Sticky Bar at the Top may conflict with some themes. In that case, it is recommended to keep it at the bottom.)</small>
													</td>
												</tr>
												<tr valign="top">
												<?php
												$arg = array(
													'errors' => false,
													'allowed' => null,
													'blog_id' => 0
												);
												$themes = wp_get_themes($arg);
												$selected_themes = get_option('mdc_themes_to_show');
												?>
													<th scope="row"><label for="mdc_themes_to_show">Themes to show</label></th>
													<td>
													<?php foreach($themes as $theme):
													if(is_array($selected_themes) && array_key_exists($theme->stylesheet, $selected_themes)){ $checked = ' checked=""';} else { $checked = '';}
														echo '<p><input type="checkbox" '.$checked.' value="'.$theme->Name.'" id="'.$theme->stylesheet.'" name="mdc_themes_to_show['.$theme->stylesheet.']"/><label for="'.$theme->stylesheet.'">'.$theme->Name.'</label></p>';
													
													endforeach; ?>
													</td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_ts_shortcode_enable">Enable Shortcode</label></th>
													<td><input type="checkbox" value="1" id="mdc_ts_shortcode_enable" name="mdc_ts_shortcode_enable" <?php if(get_option('mdc_ts_shortcode_enable') == 1){echo "checked";}?> /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(If you want to enable shortcode [mdc_theme_switcher], check this.)</small></td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_theme_switcher_custom_css">Custom CSS</label></th>
													<td>
														<textarea type="text" id="editor" class="css" name="mdc_theme_switcher_custom_css" style="height: 200px; width: 340px;"><?php if(get_option('mdc_theme_switcher_custom_css')){ echo get_option('mdc_theme_switcher_custom_css');}?></textarea>
														<span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(If you want to add your own CSS.)</small>
													</td>
												</tr>
												<tr valign="top">
													<th scope="row"><label for="mdc_ts_remove_credit">Remove Credit Link</label></th>
													<td><input type="checkbox" value="1" id="mdc_ts_remove_credit" name="mdc_ts_remove_credit" <?php if(get_option('mdc_ts_remove_credit') == 1){echo "checked";}?> /><span class="mdc_help_icon dashicons dashicons-editor-help" title="Help?"></span><br /><small class="hidden mdc_help">(We appreciate you keep the credit.)</small></td>
												</tr>
											</tbody>
										</table>
										<p class="submit">
											<input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
										</p>
										<div class="clear"></div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
<?php }
}
$mdc_theme_switcher_option_page = new MDC_Theme_Switcher_option_page;