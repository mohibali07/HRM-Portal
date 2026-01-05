<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><strong>Attendance App</strong></h2>
            <p class="text-muted">Connect to your ZKTeco device and generate attendance reports.</p>

            <!-- Step 1: Connection -->
            <div id="connection-section">
                <div class="form-group row">
                    <label for="device_ip" class="col-sm-2 col-form-label">Device IP Address</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="device_ip" name="device_ip"
                            placeholder="e.g., 192.168.1.201" value="192.168.0.194">
                    </div>
                    <div class="col-sm-4">
                        <button type="button" id="btn-connect" class="btn btn-primary">
                            <i class="fa fa-link"></i> Connect & Sync
                        </button>
                    </div>
                </div>
                <div id="connection-status" class="m-t-1"></div>
            </div>

            <!-- Step 2: Date Selection (Hidden initially) -->
            <div id="date-section"
                style="display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                <h4>Generate Report</h4>
                <form action="<?php echo site_url('ZkAttendance/export'); ?>" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_id">Select User</label>
                                <select class="form-control" id="user_id" name="user_id">
                                    <option value="">All Users</option>
                                    <option disabled>Loading users...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-download"></i> Download Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('btn-connect').addEventListener('click', function () {
            var ip = document.getElementById('device_ip').value;
            var statusDiv = document.getElementById('connection-status');
            var btn = document.getElementById('btn-connect');
            var userSelect = document.getElementById('user_id');

            if (!ip) {
                statusDiv.innerHTML = '<div class="alert alert-danger">Please enter an IP address.</div>';
                return;
            }

            // UI Loading State
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Connecting...';
            statusDiv.innerHTML = '<div class="alert alert-info">Connecting to device and downloading logs... This may take a while.</div>';

            // AJAX Request
            fetch('<?php echo site_url("ZkAttendance/connect"); ?>?ip=' + ip)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        statusDiv.innerHTML = '<div class="alert alert-success">✅ ' + data.message + ' Loading users...</div>';

                        // Fetch Users from Cache
                        fetch('<?php echo site_url("ZkAttendance/get_users"); ?>')
                            .then(response => {
                                return response.text().then(text => {
                                    try {
                                        return JSON.parse(text);
                                    } catch (e) {
                                        throw new Error('Invalid JSON: ' + text.substring(0, 100) + '...');
                                    }
                                });
                            })
                            .then(userData => {
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa fa-link"></i> Connect & Sync';

                                if (userData.success) {
                                    // Populate Dropdown
                                    userSelect.innerHTML = '<option value="">All Users</option>';
                                    // Handle both array and object formats for users
                                    const usersList = Array.isArray(userData.users) ? userData.users : Object.values(userData.users);

                                    usersList.forEach(user => {
                                        var option = document.createElement('option');
                                        option.value = user.userid; // Use userid (string) as value
                                        option.text = user.name + ' (ID: ' + user.userid + ')';
                                        userSelect.appendChild(option);
                                    });

                                    statusDiv.innerHTML = '<div class="alert alert-success">✅ Data Synced & Ready!</div>';
                                } else {
                                    statusDiv.innerHTML = '<div class="alert alert-warning">⚠️ Synced, but failed to load users: ' + userData.message + '</div>';
                                }
                                // ALWAYS Show Date Section if sync was successful
                                document.getElementById('date-section').style.display = 'block';
                            })
                            .catch(error => {
                                console.error('Error loading users:', error);
                                btn.disabled = false;
                                btn.innerHTML = '<i class="fa fa-link"></i> Connect & Sync';
                                statusDiv.innerHTML = '<div class="alert alert-danger">❌ Error loading users: ' + error.message + '</div>';
                                // ALWAYS Show Date Section if sync was successful
                                document.getElementById('date-section').style.display = 'block';
                            });
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa fa-link"></i> Connect & Sync';
                        statusDiv.innerHTML = '<div class="alert alert-danger">❌ ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-link"></i> Connect & Sync';
                    statusDiv.innerHTML = '<div class="alert alert-danger">❌ Error: ' + error + '</div>';
                });
        });
    });
</script>