<?php $session = $this->session->userdata('username'); ?>
<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><strong>ZKTeco Device Settings</strong></h2>
            <hr>

            <div class="row">
                <!-- Device Configuration Column -->
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Device Configuration</h4>
                        </div>
                        <div class="panel-body">
                            <form id="device_settings_form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="device_ip">Device IP Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="device_ip" id="device_ip"
                                                value="<?php echo isset($settings) ? $settings->device_ip : ''; ?>"
                                                placeholder="192.168.1.201" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="device_port">Port</label>
                                            <input type="number" class="form-control" name="device_port"
                                                id="device_port"
                                                value="<?php echo isset($settings) ? $settings->device_port : '4370'; ?>"
                                                placeholder="4370">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-info" id="btn_test_connection">
                                            <i class="fa fa-plug"></i> Test Connection
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save Settings
                                        </button>
                                    </div>
                                </div>
                                <?php if (isset($settings) && $settings->last_sync): ?>
                                    <div class="row m-t-1">
                                        <div class="col-md-12">
                                            <small class="text-muted">
                                                Last Sync:
                                                <?php echo date('d M Y H:i:s', strtotime($settings->last_sync)); ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sync Attendance Column -->
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Sync Attendance</h4>
                        </div>
                        <div class="panel-body">
                            <form id="sync_form">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sync_start_date">Start Date (Optional)</label>
                                            <input type="date" class="form-control" name="start_date"
                                                id="sync_start_date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sync_end_date">End Date (Optional)</label>
                                            <input type="date" class="form-control" name="end_date" id="sync_end_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-success" id="btn_sync_attendance">
                                            <i class="fa fa-refresh"></i> Sync Attendance
                                        </button>
                                        <button type="button" class="btn btn-info" id="btn_export_zk_csv">
                                            <i class="fa fa-file-excel-o"></i> Export CSV
                                        </button>
                                    </div>
                                </div>
                                <div id="sync_result" class="m-t-1"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- ZK Attendance Data -->
            <div class="row m-b-1">
                <div class="col-md-12">
                    <h4>ZK Device Attendance Data</h4>
                    <p class="text-muted">View synced attendance data from ZK device (separate from HRM system)</p>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dataTable" id="zk_attendance_table">
                            <thead>
                                <tr>
                                    <th>ZK User ID</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>All Punches</th>
                                    <th>Status</th>
                                    <th>Sync Date</th>
                                </tr>
                            </thead>
                            <tbody id="zk_attendance_body">
                                <?php if (!empty($zk_attendance)): ?>
                                    <?php foreach ($zk_attendance as $att): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($att->zk_user_id); ?></td>
                                            <td><?php echo htmlspecialchars($att->zk_name ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($att->attendance_date); ?></td>
                                            <td><?php echo htmlspecialchars($att->clock_in ?: '-'); ?></td>
                                            <td><?php echo htmlspecialchars($att->clock_out ?: '-'); ?></td>
                                            <td><?php echo htmlspecialchars($att->all_punches ?: '-'); ?></td>
                                            <td><span
                                                    class="label label-<?php echo $att->status == 'Present' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($att->status); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($att->sync_date); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No attendance data found. Click "Sync
                                            Attendance" to load data.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Sync Logs -->
            <div class="row">
                <div class="col-md-12">
                    <h4>Sync Logs</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered dataTable" id="sync_logs_table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Message</th>
                                    <th>Records Synced</th>
                                </tr>
                            </thead>
                            <tbody id="sync_logs_body">
                                <tr>
                                    <td colspan="4" class="text-center">Loading logs...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ZKTeco Settings Script Variables - Will be used by script loaded in footer -->
<script>
    // Set global variables for zkteco_settings.js (loaded in footer)
    window.zkteco_site_url = "<?php echo site_url(); ?>";
    window.zkteco_csrfName = "<?php echo $this->security->get_csrf_token_name(); ?>";
    window.zkteco_csrfHash = "<?php echo $this->security->get_csrf_hash(); ?>";
</script>
<!-- Script will be loaded in footer via path_url mechanism -->