$(document).ready(function () {
	// Get CSRF token
	var csrfName = $("input[name*='csrf_test_name']").attr("name");
	var csrfHash = $("input[name*='csrf_test_name']").val();

	// Load Users
	$("#btn_load_users").click(function () {
		var ip = $("#ip_address").val();
		var btn = $(this);
		btn.text("Loading...").prop("disabled", true);

		var data = { ip_address: ip };
		data[csrfName] = csrfHash;

		$.ajax({
			url: site_url + "zkteco/fetch_users",
			type: "POST",
			data: data,
			dataType: "JSON",
			success: function (response) {
				if (response.status == "success") {
					var options = '<option value="All">All Employees</option>';
					$.each(response.data, function (i, user) {
						options +=
							'<option value="' +
							user.user_id +
							'">' +
							user.user_id +
							" - " +
							user.name +
							"</option>";
					});
					$("#employee_id").html(options);
					toastr.success(response.message);
				} else {
					toastr.error(response.message);
				}
				btn.text("Load Users").prop("disabled", false);
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
				toastr.error("Failed to connect or parse response.");
				btn.text("Load Users").prop("disabled", false);
			},
		});
	});

	// Generate Report
	$("#btn_generate").click(function (e) {
		e.preventDefault();
		var form = $("#zkteco_report");
		var btn = $(this);
		btn.text("Generating...").prop("disabled", true);

		$.ajax({
			url: site_url + "zkteco/generate_report",
			type: "POST",
			data: form.serialize(),
			dataType: "JSON",
			success: function (response) {
				if (response.debug) {
					console.log("ZKTeco Debug:", response.debug);
				}
				if (response.status == "success") {
					var rows = "";
					$.each(response.data, function (i, row) {
						rows += "<tr>";
						rows += "<td>" + row.user_id + "</td>";
						rows += "<td>" + row.name + "</td>";
						rows += "<td>" + row.date + "</td>";
						rows += "<td>" + row.day + "</td>";
						rows += "<td>" + row.check_in + "</td>";
						rows += "<td>" + row.check_out + "</td>";
						rows += "<td>" + row.all_punches + "</td>";
						rows += "<td>" + row.status + "</td>";
						rows += "</tr>";
					});
					$("#report_body").html(rows);
					toastr.success("Report Generated!");
				} else {
					toastr.error(response.message);
				}
				btn.text("Generate Report").prop("disabled", false);
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
				toastr.error("An error occurred.");
				btn.text("Generate Report").prop("disabled", false);
			},
		});
	});
});
