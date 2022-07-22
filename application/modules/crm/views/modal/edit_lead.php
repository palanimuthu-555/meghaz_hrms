
<?php $leads = $this->db->get_where('business_proposals',array('id'=>$id))->row_array();
$roles = $this->db->get('roles')->result();
?>

<!-- <div class="modal custom-modal fade" id="edit_contact" role="dialog"> -->
                    <div class="modal-dialog modal-dialog-centered" role="document" id="edit_contact">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Lead</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="<?php echo base_url(); ?>crm/edit_lead" id="EditLeadForm" enctype="multipart/form-data">
                                    
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" id="name_edit" value="<?php echo $leads['name'];?>">
                                        <input class="form-control" type="hidden" name="id" id="id" value="<?php echo $leads['id'];?>">
                                        <input class="form-control" type="hidden" name="lead_status" id="id" value="<?php echo $leads['lead_status'];?>">
                                        <input class="form-control" type="hidden" name="url" id="url" value="<?php echo $this->uri->segment(2);?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Project Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="project_name" id="project_name_edit" value="<?php echo $leads['project_name'];?>">
                                        
                                    </div>
                                    <div class="form-group">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="project_amount" id="project_amount_edit" value="<?php echo $leads['project_amount'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" id="email_edit" value="<?php echo $leads['email'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Number <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="phone_no" id="phone_no_edit" value="<?php echo $leads['phone_no'];?>">
                                    </div>
                                     <div class="form-group">
                                        <label>Image <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="avatar" id="file_edit" >
                                        <input class="form-control" type="hidden" name="image"  id= "image_edit" value="<?php echo $leads['avatar'];?>">
                                        <?php  if(!empty($leads['avatar'])){ ?>
                                        <img class="rounded-circle" width="60%" alt="" src="<?php echo base_url()?>assets/uploads/<?php echo $leads['avatar'];?>">
                                        <?php  } ?>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block">Status</label>
                                        <div class="status-toggle">
                                            <input type="checkbox" id="lead_status_edit" name="status" class="check" <?php echo ($leads['status'] == 1)?"checked":"";?> value="1">
                                            <label for="lead_status_edit" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" id="submit_edit_lead_form" >Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <!-- </div> -->