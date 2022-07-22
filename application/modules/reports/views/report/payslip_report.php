<?php 
$cur = App::currencies(config_item('default_currency')); 
$task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
$p_month = (isset($month)) ? $month : '';
$p_year = (isset($year)) ? $year : '';
if((isset($user_id)) ? $user_id : '' != '')
{
$user_id = (isset($user_id)) ? $user_id : '';
}
else
{
  $user_id = '0';
}
$company_id = (isset($company_id)) ? $company_id : '';

$s_year = '2019';
        $select_y = date('Y');

      $s_month = date('m');
        $e_year = date('Y');
// print_r($payslip);exit;
?>

<div class="content">
   <div class="page-header">
          <div class="row">

            <div class="col-md-6">

              <h4 class="page-title">Payslip Report</h4>
              <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                        <li class="breadcrumb-item active">Report</li>
                    </ul>
            </div>
 <div class="col-md-6">
  <div class="float-right">
             <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($payslip)> 0 ){ ?>
              <a href="<?=base_url()?>reports/payslippdf/<?=$company_id;?>/<?=$user_id;?>/<?=$p_month;?>/<?=$p_year;?>" class="btn btn-primary float-right ml-2"><i class="fa fa-file-pdf-o m-r-5"></i><?=lang('pdf')?>
              </a>              
            <?php } ?>
             
            </div>
</div>
          </div>
        </div>
  <section class="row">
            
  

    <div class="col-md-12">

            <!-- <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
            </div> -->

      <div class="fill body reports-top rep-new-band">
        <div class="criteria-container fill-container hidden-print">
          <div class="criteria-band p-0">
              <?php echo form_open(base_url().'reports/view/payslip_report'); ?>
            <div class="row">
          

          
             <!--  <div class="col-md-3">
                <label><?=lang('company')?> </label>
                <select class="select2-option form-control" name="company_id" id="company_name">
                    <optgroup label="<?=lang('company')?>">
                      <option value="0">Company</option>
                        <?php 
                        $company = $this->db->get_where('companies')->result();

                        foreach ($company as $c): ?>
                            <option value="<?=$c->co_id?>" <?=($company_id == $c->co_id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->company_name)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>
 -->

                <?php  $where = array('US.activated'=>1,'US.role_id'=>3);
        $employees  =  $this->db->select('*')
                 ->from('dgt_users US')
                 ->join('dgt_account_details AD','US.id = AD.user_id','left')
                 ->where($where)
                 ->order_by('fullname','ASC')
                 ->get()->result_array();?>
              <div class="col-md-3">
                 <div class="form-group form-focus select-focus">
                <label class="control-label"><?=lang('employees')?> </label>
                <select class="form-control floating" style="width:100%;" name="user_id">
                    <option value="0" selected disabled>Employee</option>
                  <?php foreach ($employees   as $employee) { ?>
                    <option value="<?php echo $employee['user_id'];?>" <?php echo ($employee['user_id'] == $user_id)?"selected":"";?>><?php echo ucfirst($employee['fullname']);?></option>
                  <?php } ?>
                
                </select>
              </div>
</div>
              <div class="col-md-2">

 <div class="form-group form-focus select-focus">
                 <label class="control-label"><?=lang('month')?> </label>
               <select class="select floating form-control" id="attendance_month" name="month"> 
              <?php 
              for ($ji=1; $ji <=12 ; $ji++) {  
              $sele1='';

              if(isset($month) && !empty($month))
              {
                if($month==$ji)
                {
                 $sele1='selected';
                }
              }else{
                if($ji ==date('m')){
                  $sele1='selected';
                }
              }
              ?>
              <option value="<?php echo $ji; ?>" <?php echo $sele1;?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>    
              <?php } ?>
            </select>
              </div>
</div>
               <div class="col-md-2">
                 <div class="form-group form-focus select-focus">
                <label class="control-label"><?=lang('year')?> </label>
               <select class="select floating form-control" id="attendance_year" name="year"> 
              <?php for($k =$e_year;$k>=$s_year;$k--){ 
              $sele2='';
              if(isset($year) && !empty($year))
              {
                if($year==$k)
                {
                 $sele2='selected';
                }
              }else{
                if($k ==date('Y')){
                  $sele2='selected';
                }
              }

              ?>
              <option value="<?php echo $k; ?>" <?php echo $sele2;?> ><?php echo $k; ?></option>
              <?php } ?>
            </select>
              </div>
 
</div>


              <div class="col-md-2">  
                <button class="btn btn-success mt-0" type="submit">
                  <?=lang('run_report')?>
                </button>
              </div>



            </div>
          </div>
        </div>


        <?php  form_close(); ?>

        <div class="rep-container">
          <div class="page-header text-center">
            <!-- <h3 class="reports-headerspacing"><?=lang('payslip_report')?></h3> -->
            <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?>
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <th style="width:5px; display:none;"></th>
                    <th><b><?=lang('employee_name')?></b></th>  
                    <th><b><?=lang('paid_amount')?></b></th>
                    <th><b><?=lang('payment_month')?></b></th>
                    <th><b><?=lang('payment_year')?></b></th> 
                   
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($payslip as $key => $p) { 


                     $name = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array(); 
                     $users = $this->db->get_where('users',array('id'=>$p['user_id']))->row_array();
                     $payment_amount = $this->db->get_where('bank_statutory',array('user_id'=>$p['user_id']))->row_array();

                     if($p['p_month'] == 1)
                    {
                      $month = 'January';
                      
                    }elseif($p['p_month'] == 2){
                     $month = 'Febrary';
                      
                    }
                    elseif($p['p_month'] == 3){
                     $month = 'March';
                      
                    }
                    elseif($p['p_month'] == 4){
                     $month = 'April';
                      
                    }
                    elseif($p['p_month'] == 5){
                     $month = 'May';
                      
                    }
                    elseif($p['p_month'] == 6){
                     $month = 'June';
                      
                    }
                    elseif($p['p_month'] == 7){
                     $month = 'July';
                      
                    }
                    elseif($p['p_month'] == 8){
                     $month = 'August';
                      
                    }
                    elseif($p['p_month'] == 9){
                     $month = 'September';
                      
                    }
                    elseif($p['p_month'] == 10){
                     $month = 'October';
                      
                    }
                    elseif($p['p_month'] == 11){
                     $month = 'November';
                      
                    }
                    elseif($p['p_month'] == 12){
                     $month = 'December';
                      
                    }

                                                            
                  ?> 
                  <tr >
                    
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['user_id']?>">
                        <?=$name['fullname']?>
                      </a>
                    
                    </td>
                    <td><?php echo $payment_amount['salary']?></td>
                    <td><?php echo $month?></td>
                    <td><?php echo $p['p_year']?></td>
                    

                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>    