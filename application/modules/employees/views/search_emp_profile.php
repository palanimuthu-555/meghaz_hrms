<?php

$report_user = $this->db->select('*')->get_where('account_details', array('user_id'=>$Employee[0]->teamlead_id))->row();
 if($Employee[0]->avatar != ''){
    $pro_pict = $Employee[0]->avatar;
}else{
    $pro_pict = 'default_avatar.jpg';
}
if($report_user->avatar != ''){
    $report_pict = $report_user->avatar;
}else{
    $report_pict = 'default_avatar.jpg';
}
?>
<div class="content container-fluid">
    <!-- Page Header -->
    
    <!-- /Page Header -->
    <div class="row">
        <div class="col-md-9">
            <div class="card mb-0">
                <div class="card-body">   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">                                
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="profile-img-wrap">
                                                <div class="profile-img">
                                                    <a href="#"><img src="<?php echo base_url().'assets/avatar/'.$pro_pict; ?>"></a>
                                                </div>
                                            </div>
                                            <div class="profile-info-left mb-4 mt-2" style="margin-left: 200px;border: 0px;">
                                                <h3 class="user-name m-t-0 mb-0"><?php echo ucfirst($Employee[0]->username); ?></h3>
                                                <small class="text-muted"><?php echo ucfirst($Employee[0]->position_name); ?></small>
                                                <!-- <div class="staff-id">Employee ID :<?php echo $Employee[0]->employee_id; ?></div> -->
                                                <div class="small doj text-muted">Date of Join : <?php echo date('d M Y', strtotime($Employee[0]->doj)); ?></div>
                                                <!-- <div class="staff-id">Department :<?php echo $Employee[0]->department_name; ?></div>
                                                <div class="small doj text-muted">Contract Type: Full Time</div> -->
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <ul class="personal-info" style="margin-top: 50px;">
                                                <li>
                                                    <div class="title" style="width: 31%">Phone:</div>
                                                    <div class="text"><a href="javascript:void(0);"><?php echo $Employee[0]->phone;?></a></div>
                                                </li>
                                                <li>
                                                    <div class="title" style="width: 31%">Email:</div>
                                                    <div class="text"><a href="javascript:void(0);"><?php echo $Employee[0]->email; ?></a></div>
                                                </li>
                                                <li>
                                                    <div class="title" style="width: 31%">Department:</div>
                                                    <div class="text"><?php echo ($Employee[0]->deptname) ? $Employee[0]->deptname : '----'; ?></div>
                                                </li>
                                               
                                                <li>
                                                    <div class="title" style="width: 31%">Gender:</div>
                                                    <div class="text"><?php echo ($Employee[0]->gender) ? $Employee[0]->gender : '----'; ?></div>
                                                </li>
                                                <li>
                                                    <div class="title" style="width: 31%">Reports to:</div>
                                                    <?php if(!empty($report_user)){?>
                                                    <div class="text">
                                                        <div class="avatar-box">
                                                            <div class="avatar avatar-xs">
                                                                <img src="<?php echo base_url().'assets/avatar/'.$report_pict; ?>">
                                                            </div>
                                                        </div>
                                                        <a href="<?php echo base_url();?>Profile_view/<?php echo $Employee[0]->teamlead_id; ?>">
                                                            <?php echo user::displayName($report_user->user_id); ?>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                </li>
                                            </ul>                                    
                                            <div class="staff-msg text-center"><a class="btn btn-custom" href="<?php echo base_url();?>inbox">Send Message</a></div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
