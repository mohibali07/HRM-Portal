<div class="box box-block bg-white">
    <h2>ZKTeco Integration</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Device IP</th>
                        <td><?php echo $settings->device_ip; ?></td>
                    </tr>
                    <tr>
                        <th>Device Port</th>
                        <td><?php echo $settings->device_port; ?></td>
                    </tr>
                    <tr>
                        <th>Device Status</th>
                        <td><span id="device_status" class="tag tag-warning">Checking...</span></td>
                    </tr>
                    <tr>
                        <th>Last Sync</th>
                        <td><?php echo $settings->last_sync ? $settings->last_sync : 'Never'; ?></td>
                    </tr>
                </table>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_settings">Edit
                    Settings</button>
                <button type="button" class="btn btn-success" id="btn_sync">Sync Attendance</button>
                <button type="button" class="btn btn-info" id="btn_test">Test Connection</button>
            </div>
        </div>
    </div>
    <div id="sync_result" style="margin-top: 20px;"></div>
</div>

<!-- Modal for Settings -->
<div class="modal fade" id="edit_settings" tabindex="-1" role="dialog" aria-labelledby="edit_settingsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit_settingsLabel">Edit ZKTeco Settings</h4>
            </div>
            <?php echo form_open('zkteco/save_settings'); ?>
                <div class="modal-body">
                    <input type="hidden" name="save_type" value="edit">
                    <input type="hidden" name="id" value="<?php echo $settings->id; ?>">
                    <div class="form-group">
                        <label for="device_ip">Device IP</label>
                        <input type="text" class="form-control" name="device_ip"
                            value="<?php echo $settings->device_ip; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="device_port">Device Port</label>
                        <input type="number" class="form-control" name="device_port"
                            value="<?php echo $settings->device_port; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    var zkteco_urls = {
        test_connection: '<?php echo site_url("zkteco/test_connection"); ?>',
        sync_attendance: '<?php echo site_url("zkteco/sync_attendance"); ?>'
    };
    var zkteco_csrf = {
        name: '<?php echo $this->security->get_csrf_token_name(); ?>',
        hash: '<?php echo $this->security->get_csrf_hash(); ?>'
    };
</script>