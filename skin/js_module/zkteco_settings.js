$(document).ready(function () {
	console.log("ZKTeco Settings JS Loaded");

	var csrfName =
		window.zkteco_csrfName || $("input[name*='csrf_test_name']").attr("name");
	var csrfHash =
		window.zkteco_csrfHash || $("input[name*='csrf_test_name']").val();
	var site_url = window.zkteco_site_url || window.site_url || "";

	if (!site_url) {
		console.error("Site URL not defined!");
	}

	// Save Device Settings
	$("#device_settings_form").on("submit", function (e) {
		e.preventDefault();
		var form = $(this);
		var btn = form.find("button[type='submit']");
		var originalText = btn.html();

		btn
			.prop("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i> Saving...');

		$.ajax({
			url: site_url + "zkteco/save_settings",
			type: "POST",
			data: form.serialize(),
			dataType: "JSON",
			success: function (response) {
				if (response.status == "success") {
					toastr.success(response.message);
					setTimeout(function () {
						location.reload();
					}, 1500);
				} else {
					toastr.error(response.message);
					btn.prop("disabled", false).html(originalText);
				}
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
				toastr.error("Failed to save settings");
				btn.prop("disabled", false).html(originalText);
			},
		});
	});

	// Test Connection
	$("#btn_test_connection").click(function () {
		var btn = $(this);
		var originalText = btn.html();
		var ip = $("#device_ip").val();
		var port = $("#device_port").val() || 4370;

		if (!ip) {
			toastr.error("Please enter device IP address");
			return;
		}

		btn
			.prop("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i> Testing...');

		var data = {
			device_ip: ip,
			device_port: port,
		};
		data[csrfName] = csrfHash;

		$.ajax({
			url: site_url + "zkteco/test_connection",
			type: "POST",
			data: data,
			dataType: "JSON",
			success: function (response) {
				if (response.status == "success") {
					toastr.success(response.message);
				} else {
					toastr.error(response.message);
				}
				btn.prop("disabled", false).html(originalText);
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
				toastr.error("Connection test failed");
				btn.prop("disabled", false).html(originalText);
			},
		});
	});

	// Sync Attendance
	$("#btn_sync_attendance").click(function () {
		var btn = $(this);
		var originalText = btn.html();
		var resultDiv = $("#sync_result");

		if (!confirm("This will sync attendance data from ZK device. Continue?")) {
			return;
		}

		btn
			.prop("disabled", true)
			.html('<i class="fa fa-spinner fa-spin"></i> Syncing...');
		resultDiv.html(
			'<div class="progress" style="height: 30px; margin-top: 10px;">' +
				'<div class="progress-bar progress-bar-striped active" role="progressbar" style="width: 100%; line-height: 30px;">' +
				"Syncing attendance data... This may take a few minutes" +
				"</div></div>"
		);

		var data = $("#sync_form").serialize();

		$.ajax({
			url: site_url + "zkteco/sync_attendance",
			type: "POST",
			data: data,
			dataType: "JSON",
			success: function (response) {
				var alertClass = "alert-success";
				var icon = '<i class="fa fa-check-circle"></i>';

				if (response.status == "success" || response.status == "warning") {
					var message = response.message;

					if (response.total_from_device !== undefined) {
						message += "<br><br><strong>Details:</strong><br>";
						message +=
							"• Total records from device: " +
							response.total_from_device +
							"<br>";
						message += "• Synced: " + (response.synced || 0) + "<br>";
						message += "• Skipped: " + (response.skipped || 0);

						if (response.unmapped > 0) {
							message +=
								"<br>• <span style='color: orange;'>Unmapped users: " +
								response.unmapped +
								" (add mappings to sync these)</span>";
						}
						if (response.errors > 0) {
							message +=
								"<br>• <span style='color: red;'>Errors: " +
								response.errors +
								"</span>";
						}
					}

					if (response.status == "warning") {
						alertClass = "alert-warning";
						icon = '<i class="fa fa-exclamation-triangle"></i>';
					}

					resultDiv.html(
						'<div class="alert ' +
							alertClass +
							'">' +
							icon +
							" " +
							message +
							"</div>"
					);

					// Show debug info if available
					if (response.debug && response.debug.length > 0) {
						var debugHtml =
							'<details style="margin-top: 10px;"><summary>Debug Information</summary><pre style="max-height: 200px; overflow-y: auto; background: #f5f5f5; padding: 10px; margin-top: 10px;">' +
							JSON.stringify(response.debug, null, 2) +
							"</pre></details>";
						resultDiv.append(debugHtml);
					}

					// Reload sync logs and attendance data
					loadSyncLogs();
					loadZKAttendance();

					if (response.status == "success") {
						toastr.success("Sync completed");
					} else {
						toastr.warning(response.message);
					}
				} else {
					resultDiv.html(
						'<div class="alert alert-danger">' +
							'<i class="fa fa-exclamation-circle"></i> ' +
							response.message +
							"</div>"
					);

					if (response.debug && response.debug.length > 0) {
						var debugHtml =
							'<details style="margin-top: 10px;"><summary>Debug Information</summary><pre style="max-height: 200px; overflow-y: auto; background: #f5f5f5; padding: 10px; margin-top: 10px;">' +
							JSON.stringify(response.debug, null, 2) +
							"</pre></details>";
						resultDiv.append(debugHtml);
					}

					toastr.error(response.message);
				}
				btn.prop("disabled", false).html(originalText);
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
				resultDiv.html(
					'<div class="alert alert-danger">Sync request failed. Please try again.</div>'
				);
				toastr.error("Sync failed");
				btn.prop("disabled", false).html(originalText);
			},
		});
	});

	// Load Sync Logs
	function loadSyncLogs() {
		$.ajax({
			url: site_url + "zkteco/get_sync_logs",
			type: "GET",
			dataType: "JSON",
			success: function (response) {
				if (response.status == "success") {
					var tbody = $("#sync_logs_body");
					tbody.empty();

					if (response.data.length > 0) {
						$.each(response.data, function (i, log) {
							var statusClass =
								log.status == "success"
									? "success"
									: log.status == "error"
									? "danger"
									: "warning";
							var row =
								"<tr>" +
								"<td>" +
								log.sync_date +
								"</td>" +
								'<td><span class="label label-' +
								statusClass +
								'">' +
								log.status +
								"</span></td>" +
								"<td>" +
								log.message +
								"</td>" +
								"<td>" +
								log.records_synced +
								"</td>" +
								"</tr>";
							tbody.append(row);
						});
					} else {
						tbody.html(
							'<tr><td colspan="4" class="text-center">No sync logs found</td></tr>'
						);
					}
				}
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
				$("#sync_logs_body").html(
					'<tr><td colspan="4" class="text-center text-danger">Failed to load logs</td></tr>'
				);
			},
		});
	}

	// Load ZK Attendance Data
	function loadZKAttendance() {
		$.ajax({
			url: site_url + "zkteco/get_zk_attendance",
			type: "GET",
			dataType: "JSON",
			success: function (response) {
				if (response.status == "success") {
					var tbody = $("#zk_attendance_body");
					tbody.empty();

					if (response.data.length > 0) {
						$.each(response.data, function (i, record) {
							var row =
								"<tr>" +
								"<td>" +
								(record.zk_user_id || "N/A") +
								"</td>" +
								"<td>" +
								(record.zk_name || "N/A") +
								"</td>" +
								"<td>" +
								(record.attendance_date || "N/A") +
								"</td>" +
								"<td>" +
								(record.clock_in || "-") +
								"</td>" +
								"<td>" +
								(record.clock_out || "-") +
								"</td>" +
								"<td>" +
								(record.all_punches || "-") +
								"</td>" +
								'<td><span class="label label-' +
								(record.status == "Present" ? "success" : "warning") +
								'">' +
								(record.status || "N/A") +
								"</span></td>" +
								"<td>" +
								(record.sync_date || "N/A") +
								"</td>" +
								"</tr>";
							tbody.append(row);
						});
					} else {
						tbody.html(
							'<tr><td colspan="8" class="text-center">No attendance data found. Sync attendance to load data.</td></tr>'
						);
					}
				}
			},
			error: function (xhr, status, error) {
				console.log(xhr.responseText);
			},
		});
	}

	// Export ZK Attendance to CSV
	$("#btn_export_zk_csv").click(function () {
		var startDate = $("#sync_start_date").val();
		var endDate = $("#sync_end_date").val();

		var url = site_url + "zkteco/export_zk_attendance";
		var params = [];
		if (startDate) params.push("start_date=" + encodeURIComponent(startDate));
		if (endDate) params.push("end_date=" + encodeURIComponent(endDate));
		if (params.length > 0) {
			url += "?" + params.join("&");
		}
		window.location.href = url;
	});

	// Load logs and attendance on page load
	loadSyncLogs();
	loadZKAttendance();
});
