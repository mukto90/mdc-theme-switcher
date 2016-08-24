$ = new jQuery.noConflict();
$(document).ready(function(){
	$(".mdc-choose-theme").change(function(){
		var new_theme = $(this).val();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: { "action": "mdc_change_theme", "new_theme" : new_theme },
			success: function(ret){
				window.location.href="";
			}
		});

	})

	$(".close-icon").click(function(){
		$(".mdc-theme-switcher").toggle();

		if($(".mdc-theme-switcher").hasClass("mdc-position-top")){
			if($(".mdc-theme-switcher").is(":visible")){
				$("html").css('margin-top', function (index, curValue) {
				    return parseInt(curValue, 10) + 32 + 'px';
				});
				$("body").css('margin-top', function (index, curValue) {
				    return parseInt(curValue, 10) + 32 + 'px';
				});
			}
			else{
				$("html").css('margin-top', function (index, curValue) {
				    return parseInt(curValue, 10) - 32 + 'px';
				});
				$("body").css('margin-top', function (index, curValue) {
				    return parseInt(curValue, 10) - 32 + 'px';
				});
			}
		}
	})

})