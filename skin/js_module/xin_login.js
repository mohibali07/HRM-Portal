$(document).ready(function () {
	// Password toggle
	$("#toggle_password").click(function () {
		var input = $("#ipassword");
		var icon = $("#eye_icon");
		if (input.attr("type") === "password") {
			input.attr("type", "text");
			icon.removeClass("fa-eye-slash").addClass("fa-eye");
		} else {
			input.attr("type", "password");
			icon.removeClass("fa-eye").addClass("fa-eye-slash");
		}
	});

	$("#hrm-form").submit(function (e) {
		$(".save").prop("disabled", true);
		$(".icon-spinner3").show();
		/*Form Submit*/
		e.preventDefault();
		var obj = $(this),
			action = obj.attr("name"),
			redirect_url = obj.data("redirect"),
			form_table = obj.data("form-table"),
			is_redirect = obj.data("is-redirect");
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize() + "&is_ajax=1&form=" + form_table,
			cache: false,
			success: function (JSON) {
				if (JSON.error != "") {
					toastr.error(JSON.error);
					$(".save").prop("disabled", false);
					$(".icon-spinner3").hide();
				} else {
					toastr.success(JSON.result);
					$(".save").prop("disabled", false);
					$(".icon-spinner3").hide();
					if (is_redirect == 1) {
						window.location = redirect_url;
					}
				}
			},
		});
	});
});
