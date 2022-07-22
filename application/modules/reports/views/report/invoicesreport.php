<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<?php 
$cur = App::currencies(config_item('default_currency')); 
$start_date = date('F d, Y',strtotime($range[0]));
$end_date = date('F d, Y',strtotime($range[1]));
$report_by = (isset($report_by)) ? $report_by : 'InvoiceDate';
?>

<div class="content">
  <div class="page-header">
          <div class="row">
            <div class="col-md-6">
              <h4 class="page-title">Invoices Report</h4>
              <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ul>
            </div>
          <div class="col-md-6">
          <div class="float-right">
            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3)){ ?>
              <a href="<?=base_url()?>reports/invoicespdf/<?=strtotime($start_date)?>/<?=strtotime($end_date)?>/<?=$report_by?>" class="btn btn-primary mb-2"><i class="fa fa-file-pdf-o m-r-5"></i><?=lang('pdf')?>
              </a>
            <?php } ?>
             
           </div>
        </div>
     </div>
   </div>
   <div class="row">
<div class="col-12">
            <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="badge badge-success"><?=config_item('default_currency')?></span>
            </div>

</div>
<div class="col-12">
<div class="fill body reports-top rep-new-band">
<div class="criteria-container fill-container hidden-print">
  <div class="criteria-band p-0">
     <?php echo form_open(base_url().'reports/view/invoicesreport'); ?>
    <div class="row">
   
    
        <div class="col-md-3">
          <div class="form-group select-focus form-focus">
          <label class="control-label"><?=lang('report_by')?></label>
          <select class="form-control floating" name="report_by"><!---->
          <option value="InvoiceDate"><?=lang('invoice_date')?></option>
          <option value="InvoiceDueDate" <?=($report_by == 'InvoiceDueDate') ? 'selected="selected"': ''; ?>><?=lang('invoice')?> <?=lang('due_date')?></option>
</select>
        </div>
</div>      
<div class="col-md-4">
  <div class="form-group form-focus">
  <label class="control-label"><?=lang('date_range')?></label>
  <input type="text" name="range" id="reportrange" class="float-right form-control floating">
<!--     <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <b class="caret"></b>
 -->

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


</form>

<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><?=lang('invoices_report')?></h3>
  <h5><span>From</span>&nbsp;<?=$start_date?>&nbsp;<span>To</span>&nbsp;<?=$end_date?></h5>
</div>

  <div class="fill-container">
<table class="table custom-table table-striped zi-table table-hover norow-action"><thead>
  <tr>

         <th class="text-left <?=($report_by == 'InvoiceDate') ? 'text-primary': ''; ?>">
  <div class="float-left over-flow"> <?=lang('invoice_date')?></div>
  
</th>
         <th class="text-left <?=($report_by == 'InvoiceDueDate') ? 'text-primary': ''; ?>">
  <div class="float-left over-flow"> <?=lang('due_date')?></div>
</th>
         <th class="text-left">
  <div class="float-left over-flow"> <?=lang('invoice')?>#</div>
<!----></div>
</th>
         
         <th class="text-left">
  <div class="float-left over-flow"> <?=lang('client_name')?></div>
</th>
<th class="text-left">
  <div class="float-left over-flow"><?=lang('status')?></div>
</th>
         <th class="text-right">
  <div class=" over-flow"> <?=lang('invoice_amount')?></div>
</th>
         <th class="text-right">
  <div class=" over-flow"> <?=lang('balance_due')?></div>
</th>
  </tr>
</thead>

<tbody>

<?php 
$due_total = 0;
$invoice_total = 0;
foreach ($invoices as $key => $invoice) {
  $status = Invoice::payment_status($invoice->inv_id);
  $text_color = 'info';
  switch ($status) {
    case 'fully_paid':
      $text_color = 'success';
      break;
    case 'not_paid':
      $text_color = 'danger';
      break;
  }
  ?>
        <tr>
       
        <td><?=format_date($invoice->date_saved);?></td>
        <td><?=format_date($invoice->due_date);?></td>
        <td><a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>"><?=$invoice->reference_no?></a></td>
        <td><a href="<?=base_url()?>companies/view/<?=$invoice->client?>"><?=Client::view_by_id($invoice->client)->company_name?></a></td>
 <td><div class="text-<?=$text_color?>"><?=lang($status)?></div></td>
        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $payable = Applib::convert_currency($invoice->currency, Invoice::payable($invoice->inv_id));
          echo Applib::format_currency($cur->code,$payable);
          $invoice_total += $payable;
        }else{
          $invoice_total += Invoice::payable($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::payable($invoice->inv_id));
        }
        ?></td>
        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $due = Applib::convert_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id));
          $due_total += $due;
          echo Applib::format_currency($cur->code,$due);
          }else{
          $due_total += Invoice::get_invoice_due_amount($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($invoice->inv_id));
          }
          ?></td>
          
      </tr>
<?php } ?>

        <tr class="hover-muted">
          <td colspan="5"><?=lang('total')?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$invoice_total)?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$due_total)?></td>
        </tr>


<!----></tbody>
</table>  </div>
    

</div>


</div>






            </div>
            </div>
            </div>

<script type="text/javascript">
    $('#reportrange').daterangepicker({
      locale: {
            format: 'MMMM D, YYYY'
        },
        startDate: '<?=$start_date?>',
        endDate: '<?=$end_date?>',
        "opens": "right",
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
</script>