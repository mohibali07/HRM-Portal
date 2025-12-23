$(document).ready(function () {
	$("#btn_test").click(function () {
		var ip = $('input[name="device_ip"]').val();
		var port = $('input[name="device_port"]').val();
		var $btn = $(this);
		$btn.prop("disabled", true).text("Testing...");

		var data = { device_ip: ip, device_port: port };
		// Add CSRF token if available
		if (typeof zkteco_csrf !== "undefined") {
			data[zkteco_csrf.name] = zkteco_csrf.hash;
		}

		$.post(zkteco_urls.test_connection, data, function (data) {
			var res = JSON.parse(data);
			alert(res.msg);
			$btn.prop("disabled", false).text("Test Connection");
		}).fail(function () {
			alert("Request failed");
			$btn.prop("disabled", false).text("Test Connection");
		});
	});

	$("#btn_sync").click(function () {
		var $btn = $(this);
		$btn.prop("disabled", true).text("Syncing...");
		$("#sync_result").html(
			'<div class="progress" style="height: 25px;">' +
				'<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%; line-height: 25px;">' +
				"Syncing Data... Please wait (This may take a few minutes)" +
				"</div></div>"
		);

		$.get(zkteco_urls.sync_attendance, function (data) {
			var res = JSON.parse(data);
			$("#sync_result").html(
				'<div class="alert alert-' +
					(res.status == "success" ? "success" : "danger") +
					'">' +
					res.msg +
					"</div>"
			);
			$btn.prop("disabled", false).text("Sync Attendance");
			if (res.status == "success") {
				setTimeout(function () {
					location.reload();
				}, 2000);
			}
		}).fail(function () {
			$("#sync_result").html(
				'<div class="alert alert-danger">Sync request failed</div>'
			);
			$btn.prop("disabled", false).text("Sync Attendance");
		});
	});

	// Auto-check status on load
	checkDeviceStatus();

	function checkDeviceStatus() {
		var $status = $("#device_status");
		var ip = $('input[name="device_ip"]').val();
		var port = $('input[name="device_port"]').val();

		var data = { device_ip: ip, device_port: port };
		if (typeof zkteco_csrf !== "undefined") {
			data[zkteco_csrf.name] = zkteco_csrf.hash;
		}

		$.post(zkteco_urls.test_connection, data, function (data) {
			var res = JSON.parse(data);
			if (res.status == "success") {
				$status
					.removeClass("tag-warning tag-danger")
					.addClass("tag-success")
					.text("Connected");
			} else {
				$status
					.removeClass("tag-warning tag-success")
					.addClass("tag-danger")
					.text("Disconnected");
			}
		}).fail(function () {
			$status
				.removeClass("tag-warning tag-success")
				.addClass("tag-danger")
				.text("Error");
		});
	}
	$("#btn_get_data").click(function () {
		var start_date = $("#attendance_date").val();
		var end_date = $("#attendance_end_date").val();

		if (start_date == "") {
			alert("Please select a start date");
			return;
		}
		if (end_date == "") {
			alert("Please select an end date");
			return;
		}

		var $btn = $(this);
		$btn.prop("disabled", true).text("Generating...");

		// Show loader, hide result
		$("#attendance_loader").show();
		$("#attendance_result").hide();
		$("#report_body").empty();

		var data = { start_date: start_date, end_date: end_date };
		if (typeof zkteco_csrf !== "undefined") {
			data[zkteco_csrf.name] = zkteco_csrf.hash;
		}

		$.post(zkteco_urls.generate_attendance_report, data, function (data) {
			var res = JSON.parse(data);

			$("#attendance_loader").hide();
			$btn.prop("disabled", false).text("Get Data");

			if (res.status == "success") {
				$("#attendance_result").show();
				var row =
					"<tr>" +
					"<td>" +
					start_date +
					" to " +
					end_date +
					"</td>" +
					"<td>" +
					res.count +
					"</td>" +
					'<td><a href="' +
					res.file_url +
					'" class="btn btn-success" target="_blank"><i class="fa fa-download"></i> Download CSV</a></td>' +
					"</tr>";
				$("#report_body").html(row);

				// Optional: Show success message
				// toastr.success(res.msg);
			} else {
				alert(res.msg);
			}
		}).fail(function () {
			$("#attendance_loader").hide();
			$btn.prop("disabled", false).text("Get Data");
			alert("Request failed. Please try again.");
		});
	});
});
