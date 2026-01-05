<?php
/* Constants view
 */
?>
<?php $session = $this->session->userdata('username'); ?>

<div class="row m-b-1">
  <div class="col-md-3">
    <div class="box bg-white">
      <ul class="nav nav-4">
        <li class="nav-item nav-item-link active-link" id="config_8"> <a class="nav-link nav-tabs-link" href="#contract"
            data-config="8" data-config-block="contract" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-pencil"></i> <?php echo $this->lang->line('xin_e_details_contract_type'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_7"> <a class="nav-link nav-tabs-link" href="#qualification"
            data-config="7" data-config-block="qualification" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-graduation-cap"></i> <?php echo $this->lang->line('xin_e_details_edu_level'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_20"> <a class="nav-link nav-tabs-link" href="#language"
            data-config="20" data-config-block="language" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-language"></i> <?php echo $this->lang->line('xin_e_details_language'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_21"> <a class="nav-link nav-tabs-link" href="#skill"
            data-config="21" data-config-block="skill" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-lightbulb-o"></i> <?php echo $this->lang->line('xin_skill'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_9"> <a class="nav-link nav-tabs-link" href="#document_type"
            data-config="9" data-config-block="document_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-file"></i> <?php echo $this->lang->line('xin_e_details_dtype'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_10"> <a class="nav-link nav-tabs-link" href="#award_type"
            data-config="10" data-config-block="award_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-trophy"></i> <?php echo $this->lang->line('xin_award_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_11"> <a class="nav-link nav-tabs-link" href="#leave_type"
            data-config="11" data-config-block="leave_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-plane"></i> <?php echo $this->lang->line('xin_leave_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_12"> <a class="nav-link nav-tabs-link" href="#warning_type"
            data-config="12" data-config-block="warning_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-exclamation-triangle"></i> <?php echo $this->lang->line('xin_warning_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_13"> <a class="nav-link nav-tabs-link" href="#termination_type"
            data-config="13" data-config-block="termination_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-remove"></i> <?php echo $this->lang->line('xin_termination_type'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_17"> <a class="nav-link nav-tabs-link" href="#expense_type"
            data-config="17" data-config-block="expense_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-bar-chart"></i> <?php echo $this->lang->line('xin_expense_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_14"> <a class="nav-link nav-tabs-link" href="#job_type"
            data-config="14" data-config-block="job_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-file-text-o"></i> <?php echo $this->lang->line('xin_job_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_15"> <a class="nav-link nav-tabs-link" href="#exit_type"
            data-config="15" data-config-block="exit_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-sign-out"></i> <?php echo $this->lang->line('xin_employee_exit_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_18"> <a class="nav-link nav-tabs-link" href="#travel_arr_type"
            data-config="18" data-config-block="travel_arr_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-car"></i> <?php echo $this->lang->line('xin_travel_arrangement_type'); ?></a> </li>
        <li class="nav-item nav-item-link" id="config_16"> <a class="nav-link nav-tabs-link" href="#payment_method"
            data-config="16" data-config-block="payment_method" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-money"></i> <?php echo $this->lang->line('xin_payment_methods'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_19"> <a class="nav-link nav-tabs-link" href="#currency_type"
            data-config="19" data-config-block="currency_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-dollar"></i> <?php echo $this->lang->line('xin_currency_type'); ?> </a> </li>
        <li class="nav-item nav-item-link" id="config_22"> <a class="nav-link nav-tabs-link" href="#company_type"
            data-config="22" data-config-block="company_type" data-toggle="tab" aria-expanded="true"> <i
              class="fa fa-building"></i> <?php echo $this->lang->line('xin_company_type'); ?> </a> </li>
      </ul>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="contract">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_contract_type'); ?></h2>
          <form class="m-b-1 add" id="contract_type_info" action="<?php echo site_url("settings/contract_type_info") ?>"
            name="contract_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_e_details_contract_type'); ?></label>
              <input type="text" class="form-control" name="contract_type"
                placeholder="<?php echo $this->lang->line('xin_e_details_contract_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_contract_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_contract_type"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_e_details_contract_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="document_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_dtype'); ?></h2>
          <form class="m-b-1 add" id="document_type_info" action="<?php echo site_url("settings/document_type_info") ?>"
            name="document_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_e_details_dtype'); ?></label>
              <input type="text" class="form-control" name="document_type"
                placeholder="<?php echo $this->lang->line('xin_e_details_dtype'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_dtype'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_document_type"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_e_details_dtype'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="qualification" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_edu_level'); ?></h2>
          <form class="m-b-1 add" id="edu_level_info" action="<?php echo site_url("settings/edu_level_info") ?>"
            name="edu_level_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_e_details_edu_level'); ?></label>
              <input type="text" class="form-control" name="name"
                placeholder="<?php echo $this->lang->line('xin_e_details_edu_level'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_edu_level'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_education_level"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_e_details_edu_level'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="language" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_language'); ?></h2>
          <form class="m-b-1 add" id="edu_language_info" action="<?php echo site_url("settings/edu_language_info") ?>"
            name="edu_language_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_e_details_language'); ?></label>
              <input type="text" class="form-control" name="name"
                placeholder="<?php echo $this->lang->line('xin_e_details_language'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_e_details_language'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_qualification_language"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_e_details_language'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="skill" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_skill'); ?></h2>
          <form class="m-b-1 add" id="edu_skill_info" action="<?php echo site_url("settings/edu_skill_info") ?>"
            name="edu_skill_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_skill'); ?></label>
              <input type="text" class="form-control" name="name"
                placeholder="<?php echo $this->lang->line('xin_skill'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_skill'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_qualification_skill"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_skill'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="payment_method" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_payment_method'); ?></h2>
          <form class="m-b-1 add" id="payment_method_info"
            action="<?php echo site_url("settings/payment_method_info") ?>" name="payment_method_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_payment_method'); ?></label>
              <input type="text" class="form-control" name="payment_method"
                placeholder="<?php echo $this->lang->line('xin_payment_method'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_payment_method'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_payment_method"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_payment_method'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="award_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_award_type'); ?></h2>
          <form class="m-b-1 add" id="award_type_info" action="<?php echo site_url("settings/award_type_info") ?>"
            name="award_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_award_type'); ?></label>
              <input type="text" class="form-control" name="award_type"
                placeholder="<?php echo $this->lang->line('xin_award_type'); ?>xin_award_type">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_award_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_award_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_award_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="leave_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_leave_type'); ?></h2>
          <form class="m-b-1 add" id="leave_type_info" action="<?php echo site_url("settings/leave_type_info") ?>"
            name="leave_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_leave_type'); ?></label>
              <input type="text" class="form-control" name="leave_type"
                placeholder="<?php echo $this->lang->line('xin_leave_type'); ?>">
            </div>
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_days_per_year'); ?></label>
              <input type="text" class="form-control" name="days_per_year"
                placeholder="<?php echo $this->lang->line('xin_days_per_year'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_leave_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_leave_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_leave_type'); ?></th>
                  <th><?php echo $this->lang->line('xin_days_per_year'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="warning_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_warning_type'); ?></h2>
          <form class="m-b-1 add" id="warning_type_info" action="<?php echo site_url("settings/warning_type_info") ?>"
            name="warning_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_warning_type'); ?></label>
              <input type="text" class="form-control" name="warning_type"
                placeholder="<?php echo $this->lang->line('xin_warning_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_warning_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_warning_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_warning_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="termination_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_termination_type'); ?></h2>
          <form class="m-b-1 add" id="termination_type_info"
            action="<?php echo site_url("settings/termination_type_info") ?>" name="termination_type_info"
            method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_termination_type'); ?></label>
              <input type="text" class="form-control" name="termination_type"
                placeholder="<?php echo $this->lang->line('xin_termination_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_termination_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_termination_type"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_termination_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="expense_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_expense_type'); ?></h2>
          <form class="m-b-1 add" id="expense_type_info" action="<?php echo site_url("settings/expense_type_info") ?>"
            name="expense_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_expense_type'); ?></label>
              <input type="text" class="form-control" name="expense_type"
                placeholder="<?php echo $this->lang->line('xin_expense_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_expense_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_expense_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_expense_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="job_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_job_type'); ?></h2>
          <form class="m-b-1 add" id="job_type_info" action="<?php echo site_url("settings/job_type_info") ?>"
            name="job_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_job_type'); ?></label>
              <input type="text" class="form-control" name="job_type"
                placeholder="<?php echo $this->lang->line('xin_job_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_job_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_job_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_job_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="exit_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_employee_exit_type'); ?></h2>
          <form class="m-b-1 add" id="exit_type_info" action="<?php echo site_url("settings/exit_type_info") ?>"
            name="exit_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_employee_exit_type'); ?></label>
              <input type="text" class="form-control" name="exit_type"
                placeholder="<?php echo $this->lang->line('xin_employee_exit_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_employee_exit_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_exit_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_employee_exit_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="travel_arr_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_travel_arrangement_type'); ?></h2>
          <form class="m-b-1 add" id="travel_arr_type_info"
            action="<?php echo site_url("settings/travel_arr_type_info") ?>" name="travel_arr_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_travel_arrangement_type'); ?></label>
              <input type="text" class="form-control" name="travel_arr_type"
                placeholder="<?php echo $this->lang->line('xin_travel_arrangement_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_travel_arrangement_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_travel_arr_type"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_travel_arrangement_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="currency_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_currency_type'); ?></h2>
          <form class="m-b-1 add" id="currency_type_info" action="<?php echo site_url("settings/currency_type_info") ?>"
            name="currency_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_currency_name'); ?></label>
              <input type="text" class="form-control" name="name"
                placeholder="<?php echo $this->lang->line('xin_currency_name'); ?>">
            </div>
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_currency_code'); ?></label>
              <input type="text" class="form-control" name="code"
                placeholder="<?php echo $this->lang->line('xin_currency_code'); ?>">
            </div>
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_currency_symbol'); ?></label>
              <input type="text" class="form-control" name="symbol"
                placeholder="<?php echo $this->lang->line('xin_currency_symbol'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_currencies'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_currency_type"
              style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_name'); ?></th>
                  <th><?php echo $this->lang->line('xin_code'); ?></th>
                  <th><?php echo $this->lang->line('xin_symbol'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-9 current-tab animated fadeInRight" id="company_type" style="display:none;">
    <div class="row">
      <div class="col-md-4">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_add_new'); ?></strong>
            <?php echo $this->lang->line('xin_company_type'); ?></h2>
          <form class="m-b-1 add" id="company_type_info" action="<?php echo site_url("settings/company_type_info") ?>"
            name="company_type_info" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
              value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="type" value="company_type_info">
            <div class="form-group">
              <label for="name"><?php echo $this->lang->line('xin_company_type'); ?></label>
              <input type="text" class="form-control" name="name"
                placeholder="<?php echo $this->lang->line('xin_company_type'); ?>">
            </div>
            <button type="submit" class="btn btn-primary save"><?php echo $this->lang->line('xin_save'); ?></button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-block bg-white">
          <h2><strong><?php echo $this->lang->line('xin_list_all'); ?></strong>
            <?php echo $this->lang->line('xin_company_type'); ?></h2>
          <div class="table-responsive" data-pattern="priority-columns">
            <table class="table table-striped table-bordered dataTable" id="xin_table_company_type" style="width:100%;">
              <thead>
                <tr>
                  <th><?php echo $this->lang->line('xin_action'); ?></th>
                  <th><?php echo $this->lang->line('xin_company_type'); ?></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade edit_setting_datail" id="edit_setting_datail" tabindex="-1" role="dialog"
  aria-labelledby="edit-modal-data" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="ajax_setting_info"></div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
      $('[data-plugin="select_hrm"]').select2({
        width: '100%'
      });
      $('[data-toggle="tooltip"]').tooltip();

      // Generic function to initialize DataTable and Form
      function init_constant_tab(table_id, form_id, ajax_url, delete_token_type) {
        var table = $('#' + table_id).dataTable({
          "bDestroy": true,
          "ajax": {
            url: "<?php echo site_url("settings/") ?>" + ajax_url,
            type: 'GET'
          },
          "fnDrawCallback": function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
          }
        });

        $("#" + form_id).submit(function (e) {
          e.preventDefault();
          var obj = $(this),
            action = obj.attr('name');
          $('.save').prop('disabled', true);
          $.ajax({
            type: "POST",
            url: e.target.action,
            data: obj.serialize() + "&is_ajax=1&type=" + action + "&form=" + action,
            cache: false,
            success: function (JSON) {
              if (JSON.error != '') {
                toastr.error(JSON.error);
                $('.save').prop('disabled', false);
              } else {
                table.api().ajax.reload(function () {
                  toastr.success(JSON.result);
                }, true);
                $('.save').prop('disabled', false);
                $('form#' + form_id)[0].reset();
              }
            }
          });
        });
      }

      // Initialize all tabs
      init_constant_tab('xin_table_contract_type', 'contract_type_info', 'contract_type_list', 'contract_type');
      init_constant_tab('xin_table_document_type', 'document_type_info', 'document_type_list', 'document_type');
      init_constant_tab('xin_table_education_level', 'edu_level_info', 'education_level_list', 'education_level');
      init_constant_tab('xin_table_qualification_language', 'edu_language_info', 'qualification_language_list', 'qualification_language');
      init_constant_tab('xin_table_qualification_skill', 'edu_skill_info', 'qualification_skill_list', 'qualification_skill');
      init_constant_tab('xin_table_payment_method', 'payment_method_info', 'payment_method_list', 'payment_method');
      init_constant_tab('xin_table_award_type', 'award_type_info', 'award_type_list', 'award_type');
      init_constant_tab('xin_table_leave_type', 'leave_type_info', 'leave_type_list', 'leave_type');
      init_constant_tab('xin_table_warning_type', 'warning_type_info', 'warning_type_list', 'warning_type');
      init_constant_tab('xin_table_termination_type', 'termination_type_info', 'termination_type_list', 'termination_type');
      init_constant_tab('xin_table_expense_type', 'expense_type_info', 'expense_type_list', 'expense_type');
      init_constant_tab('xin_table_job_type', 'job_type_info', 'job_type_list', 'job_type');
      init_constant_tab('xin_table_exit_type', 'exit_type_info', 'exit_type_list', 'exit_type');
      init_constant_tab('xin_table_travel_arr_type', 'travel_arr_type_info', 'travel_arr_type_list', 'travel_arr_type');
      init_constant_tab('xin_table_currency_type', 'currency_type_info', 'currency_type_list', 'currency_type');
      init_constant_tab('xin_table_company_type', 'company_type_info', 'company_type_list', 'company_type');

      /* Delete data */
      $(document).on("click", ".delete", function () {
        $('input[name=_token]').val($(this).data('record-id'));
        $('input[name=token_type]').val($(this).data('token_type'));
        $('#delete_record').attr('action', "<?php echo site_url("settings/delete_company_type") ?>");
      });

      $('.delete-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var record_id = button.data('record-id');
        var token_type = button.data('token_type');
        var modal = $(this);

        var delete_url = "";
        if (token_type == 'contract_type') delete_url = "<?php echo site_url("settings/delete_contract_type") ?>";
        else if (token_type == 'document_type') delete_url = "<?php echo site_url("settings/delete_document_type") ?>";
        else if (token_type == 'education_level') delete_url = "<?php echo site_url("settings/delete_education_level") ?>";
        else if (token_type == 'qualification_language') delete_url = "<?php echo site_url("settings/delete_qualification_language") ?>";
        else if (token_type == 'qualification_skill') delete_url = "<?php echo site_url("settings/delete_qualification_skill") ?>";
        else if (token_type == 'payment_method') delete_url = "<?php echo site_url("settings/delete_payment_method") ?>";
        else if (token_type == 'award_type') delete_url = "<?php echo site_url("settings/delete_award_type") ?>";
        else if (token_type == 'leave_type') delete_url = "<?php echo site_url("settings/delete_leave_type") ?>";
        else if (token_type == 'warning_type') delete_url = "<?php echo site_url("settings/delete_warning_type") ?>";
        else if (token_type == 'termination_type') delete_url = "<?php echo site_url("settings/delete_termination_type") ?>";
        else if (token_type == 'expense_type') delete_url = "<?php echo site_url("settings/delete_expense_type") ?>";
        else if (token_type == 'job_type') delete_url = "<?php echo site_url("settings/delete_job_type") ?>";
        else if (token_type == 'exit_type') delete_url = "<?php echo site_url("settings/delete_exit_type") ?>";
        else if (token_type == 'travel_arr_type') delete_url = "<?php echo site_url("settings/delete_travel_arr_type") ?>";
        else if (token_type == 'currency_type') delete_url = "<?php echo site_url("settings/delete_currency_type") ?>";
        else if (token_type == 'company_type') delete_url = "<?php echo site_url("settings/delete_company_type") ?>";

        $('input[name=_token]').val(record_id);
        $('input[name=token_type]').val(token_type);
        $('#delete_record').attr('action', delete_url);
      });

    });
  </script>