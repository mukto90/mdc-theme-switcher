$ = new jQuery.noConflict();
$(document).ready(function(){
	$(".mdc_choose_theme").change(function(){
		var new_theme = $(this).val();
		var redirect_to = $(".mdc_redirect_to").val();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { "action": "mdc_change_theme", "new_theme" : new_theme, "redirect_to" : redirect_to },
			success: function(ret){
				// alert("Good");
				window.location.replace(ret);
			}
		});

	})
})