<?php $session = $this->session->userdata('username'); ?>
<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><strong><?php echo $this->lang->line('xin_zkteco_report'); ?></strong></h2>
            <div class="row">
                <div class="col-md-12">
                    <form class="m-b-1" action="<?php echo site_url("zkteco/generate_report"); ?>" method="post"
                        name="zkteco_report" id="zkteco_report">
                        <div class="row">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ip_address">Device IP</label>
                                    <input type="text" class="form-control" name="ip_address" id="ip_address"
                                        value="192.168.0.194">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-block" id="btn_load_users">Load
                                        Users</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="employee_id">Employee</label>
                                    <select class="form-control" name="employee_id" id="employee_id">
                                        <option value="All">All Employees</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <select class="form-control" name="year" id="year">
                                        <?php
                                        $current_year = date('Y');
                                        for ($i = $current_year; $i >= $current_year - 5; $i--) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <select class="form-control" name="month" id="month">
                                        <?php
                                        for ($m = 1; $m <= 12; $m++) {
                                            $month_name = date('F', mktime(0, 0, 0, $m, 10));
                                            $selected = ($m == date('n')) ? 'selected' : '';
                                            echo '<option value="' . $m . '" ' . $selected . '>' . $month_name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" id="btn_generate">Generate Report</button>
                                <button type="submit" class="btn btn-info"
                                    formaction="<?php echo site_url("zkteco/export_report"); ?>">Export to
                                    Excel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row m-b-1">
    <div class="col-md-12">
        <div class="box box-block bg-white">
            <h2><strong>Report Results</strong></h2>
            <div class="table-responsive" data-pattern="priority-columns">
                <table class="table table-striped table-bordered dataTable" id="xin_table_report">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>All Punches</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="report_body">
                        <!-- Data will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>