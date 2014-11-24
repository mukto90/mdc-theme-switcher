<?php 
function mdc_option_page(){
	add_menu_page('MDC Theme Switcher', 'Theme Switcher', 'administrator', 'mdc-theme-switcher', 'mdc_theme_swicher_option_page', plugins_url( 'images/icon.png' , __FILE__ ), 61);
	// add_submenu_page('mdc-theme-switcher', 'MedhabiDotCom', 'MedhabiDotCom', 'administrator', 'medhabidotcom', 'medhabidotcom', '');
}
add_action('admin_menu', 'mdc_option_page');
function mdc_theme_swicher_option_page(){
	?>
<div class="wrap">
	<h2>MDC Theme Switcher Settings</h2>
	<?php if($_POST){
	update_option('display_text', $_POST['display_text']);
	update_option('enable_sticky', $_POST['enable']);
	update_option('credit_link', $_POST['credit_link']);
	update_option('themes_array', $_POST['themes_array']);
	update_option('sticky_position', $_POST['sticky_position']);
	update_option('def_theme', $_POST['def_theme']);
	update_option('sticky_bar_bg', $_POST['sticky_bar_bg']);
	update_option('sticky_bar_txt', $_POST['sticky_bar_txt']);
	update_option('enable_shortcode', $_POST['enable_shortcode']);
	?>
	<div class="updated settings-error" id="setting-error-settings_updated"> 
		<p><strong>Settings saved.</strong></p>
	</div>
	<?php } ?>
	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="display_text">Display Text</label></th>
					<td><input type="text" class="regular-text" value="<?php echo get_option('display_text');?>" id="display_text" name="display_text" placeholder="Example: MDC Theme Switcher" /></td>
				</tr>
				<tr valign="top">
					<th scope="row">Enable Shortcode</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span>Enable Shortcode</span></legend>
							<label for="enable_shortcode">
								<input type="checkbox" value="1" id="enable_shortcode" name="enable_shortcode" <?php if(get_option('enable_shortcode') == 1){echo "checked";}?>>Shortcode <strong>[mdc_theme_swicher]</strong> can be used in post, page, custom post, widget or even in template file.
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Enable Sticky Bar</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span>Enable Sticky Bar</span></legend>
							<label for="enable">
								<input type="checkbox" value="1" id="enable" name="enable" <?php if(get_option('enable_sticky') == 1){echo "checked";}?>>Tick to show the sticky bar on front-end.
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Sticky Bar Background Color</th>
					<td>
						<input type="color" value="<?php if(get_option('sticky_bar_bg')){echo get_option('sticky_bar_bg');}?>" name="sticky_bar_bg" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Sticky Bar Text Color</th>
					<td>
						<input type="color" value="<?php if(get_option('sticky_bar_txt')){echo get_option('sticky_bar_txt');}?>" name="sticky_bar_txt" />
					</td>
				</tr>
					<th scope="row"><label for="sticky_position">Sticky Bar Position</label></th>
					<td>
						<select id="sticky_position" name="sticky_position">
							<option value="top" <?php if(get_option('sticky_position') == 'top'){echo "selected";}?>>Top</option>
							<option value="bottom" <?php if(get_option('sticky_position') == 'bottom'){echo "selected";}?>>Bottom</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Choose themes to select</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span>Choose themes to select</span></legend>
							<label for="themes">
							<?php
							$themes = get_themes();
							if(get_option('themes_array') == ''){
							foreach($themes as $theme):
							$stylesheet = strtolower(str_replace(" ","",$theme));
							?>
								<input type="checkbox" value="<?php echo $stylesheet;?>" id="enable" name="themes_array[]" /><?php echo $theme;?><br />
							<?php endforeach;
							}
							else{
							foreach($themes as $theme):
							$stylesheet = strtolower(str_replace(" ","",$theme));
							?>
								<input type="checkbox" value="<?php echo $stylesheet;?>" id="enable" name="themes_array[]" <?php if(in_array($stylesheet, get_option('themes_array'))){echo "checked";}?> /><?php echo $theme;?><br />
							<?php endforeach;
							}
							?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">Default Theme</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"></legend>
								<?php
								foreach($themes as $theme){
								$stylesheet = strtolower(str_replace(" ","",$theme));
								?>
								<label title="def_theme">
									<input type="radio" value="<?php echo $stylesheet;?>" name="def_theme" <?php if(get_option('def_theme') == $stylesheet){echo "checked";}?>> <span><?php echo $theme;?></span>
								</label><br>
								<?php }?>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">Credit Link</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"></legend>
								<label title="credit_yes">
									<input type="radio" value="yes" name="credit_link" <?php if(get_option('credit_link') == 'yes'){echo "checked";}?>> <span>Yes, keep a credit link</span>
								</label><br>
								<label title="credit_no">
									<input type="radio" value="no" name="credit_link" <?php if(get_option('credit_link') == 'no'){echo "checked";}?>> <span>No, don't keep a credit link!</span>
								</label><br>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
		</p>
	</form>
</div>
<div class="clear"></div>
	<?php
}

function medhabidotcom(){
	?>
	<div class="wrap">
		<h2>MedhabiDotCom</h2>
	</div>
	<?php

}
?>