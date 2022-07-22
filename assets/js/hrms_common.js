var output = '';
var closest_hidden_grade = '';
var closest_hidden_gradeval = '';
var closest_hidden_keyprog = '';
var closest_hidden_keygrade = '';
var grade = '';
var key_res = '';
var grade_res = '';


function changeviews(e, view) {

    $('.viewby').removeClass('active');
    $(e).addClass('active');
    if (view == 'list') {
        $('.div-table').removeClass('grid-view');
        $('#employees_details').attr('data-view', 'list');
    } else {
        $('.div-table').addClass('grid-view');
        $('#employees_details').attr('data-view', 'grid');
    }
}

if (uri_page == 'employees') {

    var page = 1;
    var employee_id = $('#employee_id').val();
    var username = $('#username').val();
    var department_id = $('#department_id').val();
    var employee_email = $('#employee_email').val();

    initalloading_employee(page, employee_id, username, employee_email, department_id);
}

function changedesignation(designation, userid) {

    $.post(base_url + 'employees/changedesignation', { designation: designation, userid: userid }, function (datas) {
        if (datas) {
            filter_next_page(1);
            $('.message_notifcation').html('<p class="alert alert-success">Designation updated successfully...</div>');
            setTimeout(function () {
                $('.message_notifcation').html('');
            }, 5000);
        }
    });
}
$('.employee_search').keypress(function (e) {
 var key = e.which;
 if(key == 13)  // the enter key code
  {
   filter_next_page(1);
  }
});
$('.department_search').change(function (e) {
    filter_next_page(1);
}); 

function filter_next_page(page = 1) {

    var employee_id = $('#employee_id').val();
    var username = $('#username').val();
    var department_id = $('#department_id').val();
    var employee_email = $('#employee_email').val();

    initalloading_employee(page, employee_id, username, employee_email, department_id);
}

function initalloading_employee(page, employee_id, username, employee_email, department_id) {

    var viewby = $('#employees_details').attr('data-view');
  var read=$('#read').val();
  var write=$('#write').val();
  var delet=$('#delete').val();
    $.post(base_url + 'employees/employees_list', {
        page: page,
        employee_id: employee_id,
        username: username,
        employee_email: employee_email,
        department_id: department_id
    }, function (datas) {

        var htmlbody = '';
        datas = JSON.parse(datas);
        var records = datas.list;
        var current_page = datas.current_page;
        var total_page = datas.total_page;
        var recordscount = records.length;
        
        if (recordscount > 0) {
             htmlbody += '<tbody id="admin_leave_tbl">' ;
            for (var i = 0; i < recordscount; i++) {

                var record = records[i];
                var fullname = record.fullname;
                var designations = record.designations;
                var user_status;
                if(record.activated == 1)
                {
                    user_status = 'Active';
                    $class = "success";
                }else{
                    user_status = 'InActive';
                    $class = "danger";
                }

                var imgs;
                if(record.avatar != 'default_avatar.jpg'){
                    imgs = '<img class="avatar" src="'+ base_url+'assets/avatar/'+record.avatar +'">';
                    
                }else{
                    imgs = '<img class="avatar" src="'+ base_url+'assets/avatar/default_avatar.jpg">';
                }

				if(read==1)
				{
					var profile_url=base_url+'employees/profile_view/'+record.id;
				}
				else
				{
					var profile_url='#';
				}

                htmlbody += '<tr>'+
                '<td class="div-cell user-cell">' +
                    '<div class="user_det_list">' +
                   '<h2 class="table-avatar">' + '<a class="avatar" href="'+base_url+'employees/profile_view/'+record.id+'">'+imgs+ '</a>' +
                    '<a href="'+profile_url+'">' + fullname.toUpperCase()  +
                    ' <span class="userrole-info">' + record.designation + '</span>' + '</a>' + '</h2>' +
                    '</div>' +
                    '</td>' +
                    '<td class="div-cell user-identity">' +
                    '<p>' + record.department + '</p>' +
                    '</td>' +
                    '<td class="div-cell user-identity">' +
                    '<p>FT-00' + record.id + '</p>' +
                    '</td>' +
                    '<td class="div-cell user-mail-info">' +
                    '<p>' + record.email + '</p>' +
                    '</td>' +
                    '<td class="div-cell number-info">' +
                    '<p>' + record.phone + '</p>' +
                    '</td>' +
                    '<td class="div-cell create-date-info">' +
                    '<p>' + record.doj + '</p>' +
                    '</td>' +
                    '<td class="div-cell create-date-info">' +
                    '<p>' + record.last_login + '</p>' +
                    '</td>' ;
                    // '<div class="div-cell user-role-info">' +
                    // '<div class="dropdown">';

                // if (designations.length > 0) {
                //     htmlbody += '<a class="btn btn-white btn-sm rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' + record.designation + ' <i class="caret"></i></a>' +
                //         '<ul class="dropdown-menu">';
                //     $.each(designations, function (index, value) {
                //         htmlbody += '<li><a href="javascript:void(0)" onclick="changedesignation(' + value.id + ',' + record.id + ')">' + value.designation + '</a></li>';
                //     });


                //     htmlbody += '</ul>';
                // }
  
					
                    htmlbody +='<td class="div-cell user-action-info">' +
                    '<p class="badge bg-'+$class+' text-white">' + user_status + '</p>' +
                    '</td>' +
                    '<td class="div-cell user-action-info">' +
                    '<div class="text-right">' +
                    '<div class="dropdown">' +
                    '<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                    '<div class="dropdown-menu float-right">';
					
					if(write==1)
					{
					htmlbody +='<a class="dropdown-item" href="' + base_url + 'employees/profile_view/' + record.id + '"   title="Employee"><i class="fa fa-pencil m-r-5"></i>Edit</a>';
                    }
					if(delet==1)
					{
						htmlbody += '<a class="dropdown-item" href="' + base_url + 'employees/delete/' + record.id + '"  data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a>';
					}
                    if(record.activated == 2 ){
                        htmlbody += '<a class="dropdown-item" href="' + base_url + 'employees/change_inactive/'+record.id+ '" ><i class="fa fa-eye m-r-5"></i>Active</a>';
                    }else{
                        htmlbody += '<a class="dropdown-item" href="' + base_url + 'employees/change_inactive/'+record.id+ '" ><i class="fa fa-eye-slash m-r-5"></i>InActive</a>';
                    }
                    htmlbody += '</div>' +
                    '</div>' +
                    '</div>' +
                    '</td>' ;
					
                    htmlbody += '</tr>' ;
                    
            }
             htmlbody += '</tbody>' ;
        } else {
            htmlbody = '<tbody class="row filter-row"><div class="col-md-12">No records found.</div></tbody>';
        }
        var html = '<thead>'+
                    '<tr class="table_heading">' +
            '<th class="div-cell">' +
            '<p><b>Name</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Department</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Team Members ID</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Email</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Mobile</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Join Date</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Last Login</b></p>' +
            '</th>' +
            '<th class="div-cell">' +
            '<p><b>Status</b></p>' +
            '</th>' +
            '<th class="div-cell text-right">' +
            '<p><b>Action</b></p>' +
            '</th>' +
            '</tr>' +
            '</thead>';
        var classname = '';
        var classpage = '';
        var k = 1;
        if (viewby == 'grid') {
            classname = 'grid-view';
        }

        var pagination_html = '';
        total_page = parseInt(total_page);

        if (total_page > 1) {

            pagination_html = '<div class="row row-paginate">' +
                '<div class="col-sm-12">' +
                '<div class="dataTables_paginate paging_simple_numbers" id="table-projects_paginate">' +
                '<ul class="pagination">';

            total_page = parseInt(total_page);

            for (var k = 1; k <= total_page; k++) {
                if (current_page == k) {
                    classpage = 'active';
                } else { classpage = ''; }
                pagination_html += '<li class="paginate_button ' + classpage + '"><a href="javascript:void(0)" onclick="filter_next_page(' + k + ')">' + k + '</a></li>';
            }
            pagination_html += '</ul></div></div></div>';
        }
        var final_html = '<div class="row"><div class="col-md-12"><div class="table-responsive"><table id="employees" class="table table-striped custom-table m-b-0 AppendDataTables">' + html + htmlbody + '</table></div></div></div>';

        $('#employees_details').html(final_html);
        var employees = $('#employees').DataTable({
   'aoColumnDefs': [{
        'bSortable': false,
        'aTargets': [-1] /* 1st one, start by the right */
    }]
});

    });

}


if (uri_page == 'attendance') {
    var page = 1;
    var employee_id = $('#employee_id').val();
    var employee_name = $('#employee_name').val();
    var attendance_month = $('#attendance_month').val();
    var attendance_year = $('#attendance_year').val();
    //load_attendance_details(employee_name, attendance_month, attendance_year, page, employee_id);
}



function attendance_next_filter_page(page = 1) {
    var employee_name = $('#employee_name').val();
    var employee_id = $('#employee_id').val();
    var attendance_month = $('#attendance_month').val();
    var attendance_year = $('#attendance_year').val();
    //load_attendance_details(employee_name, attendance_month, attendance_year, page, employee_id);

}

function load_attendance_details(employee_name, attendance_month, attendance_year, page, employee_id) {

    $.post(base_url + 'attendance/attendance_list', {
        page: page,
        employee_id: employee_id,
        employee_name: employee_name,
        attendance_month: attendance_month,
        attendance_year: attendance_year
    }, function (datas) {

        var attendance_footer = '';
        var attendance_body = '';
        var attendance_head = '';

        datas = JSON.parse(datas);
        var last_day = datas.last_day;
        var current_page = datas.current_page;
        var total_page = datas.total_page;
        var attendance_list = datas.attendance_list;
        var recordscount = attendance_list.length;
        attendance_head = '<div class="table-responsive"><table class="table table-striped custom-table m-b-0"><thead><tr><th>Team Members</th>';
        for (var ik = 1; ik <= last_day; ik++) {
            attendance_head += '<th>' + ik + '</th>';
        }
        attendance_head += '</tr></thead>';
        attendance_body += '<tbody>';
        if (recordscount > 0) {
            for (var i = 0; i < recordscount; i++) {

                var record = attendance_list[i];

                var name = record.fullname;
                var attendance = record.attendance;

                attendance_body += '<tr><td><a href="'+base_url + 'attendance/details/'+record.user_id+'">' + name + '</a></td>';

               // console.log(attendance);

                var j=1;
                $.each(attendance, function (key, rec) {
                   // console.log(j);
                    var status = rec.day;
                    var punchin = rec.punch_in;
                    var punch_out = rec.punch_out;
                    if(punch_out != ''){
                        var start = moment.duration(punchin, "HH:mm");
                        var end = moment.duration(punch_out, "HH:mm");
                        var diff = end.subtract(start);
                        var hr =  diff.hours(); // return hours
                        var mins = diff.minutes(); // return minutes   
                    }
                    attendance_body += '<td >';

                    if (status == '0') {
                        if(punchin == '' && punch_out == ''){
                            attendance_body += '<i class="fa fa-close text-danger"></i>';
                        }
                    } else if (status == '1') {
                        if((punchin != ''  && punch_out != '')){
                            attendance_body += '<a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a>';
                        }else{
                            attendance_body += '<div class="half-day">'
                                                    +'<span class="first-off"><a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a></span>' 
                                                    +'<span class="first-off"><i class="fa fa-close text-danger"></i></span>'
                                                +'</div>';
                        }
                    } else if (status == '2') {
                        attendance_body += '<i class="text-success" data-toggle="tooltip" title="Worked Hours"></i>';
                    } else if (status == '0') {
                        attendance_body += '<i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" title="No Record for Punch in"></i>';
                    } else if (status == '') {
                        attendance_body += '-';
                    }
                    attendance_body += '</td>';

                    ++j;

                });
                attendance_body += '</tr>';
            }
        } else {
            attendance_body += '<tr><td></td></tr>';
        }
        attendance_body += '</tbody>';

        attendance_body += '</table></div>';

        total_page = parseInt(total_page);

        if (total_page > 1) {

            attendance_footer = '<div class="row"><div class="col-sm-12">' +
                '' +
                '<div class="dataTables_paginate paging_simple_numbers" id="table-projects_paginate">' +
                '<ul class="pagination m-r-15">';

            total_page = parseInt(total_page);

            for (var k = 1; k <= total_page; k++) {
                if (current_page == k) {
                    classpage = 'active';
                } else { classpage = ''; }
                attendance_footer += '<li class="paginate_button ' + classpage + '"><a href="javascript:void(0)" onclick="attendance_next_filter_page(' + k + ')">' + k + '</a></li>';
            }
            attendance_footer += '</ul></div></div></div>';
        }

        attendance_footer += '<div class="row"><div class="col-md-12"><div class="pagination"></div></div></div>';
        var attendance_html = attendance_head + attendance_body + attendance_footer;
        $('#attendance_table').html(attendance_html);

    });
}


$(document).ready(function(){

    $('#checkuser_email').change(function(){
        // alert($(this).val());
        var check_email = $(this).val();
        // alert(isEmail(check_email));
        if(isEmail(check_email))
        {
            $('#error_emailid').css('display','none');
            $('#register_btn').removeAttr('disabled');
            $.post(base_url+'employees/check_user_email/',{user_email:check_email},function(res){
                if(res == 'yes'){
                    $('#already_email').css('display','');
                    $('#register_btn').attr('disabled','disabled');
                }else{
                    $('#already_email').css('display','none');
                    $('#register_btn').removeAttr('disabled');

                }
            });
        }else{
            $('#error_emailid').css('display','');
            // alert('hi');
            $('#register_btn').attr('disabled','disabled');
        }
    });

    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }


    $('#check_username').change(function(){
        var check_username = $(this).val();
        $.post(base_url+'employees/check_username/',{check_username:check_username},function(res){
                if(res == 'yes'){
                    $('#already_username').css('display','');
                    $('#register_btn').attr('disabled','disabled');
                }else{
                    $('#already_username').css('display','none');
                    $('#register_btn').removeAttr('disabled');

                }
        });
    });
    $('#check_fullname').keydown(function(er){
        if(er.altKey)
            {
            er.preventDefault();
            }
            else
            {var key=er.keyCode;
            if(!((key==190)||(key==16)||(key==8)||(key==9)||(key==32)||(key==46)||(key>=65 && key<=90)))
                {
                     er.preventDefault();
                     // alert("please enter only alphabets")
                }
            }
    });
   function check_fullname(fullname) {
      var regex = /^[A-Za-z\s]{1,}[\.]{0,1}[A-Za-z\s]{0,}$/;
      return regex.test(fullname);
    }
      $('#email').change(function(){
        var check_email = $(this).val();
        $.post(base_url+'all_contacts/check_contact_email/',{check_email:check_email},function(res){
                if(res == 'yes'){
                    $('#already_contactname').css('display','');
                    $('#submit_contact_form').attr('disabled','disabled');
                }else{
                    $('#already_contactname').css('display','none');
                    $('#submit_contact_form').removeAttr('disabled');

                }
        });
    });
    $('.shift_name').keydown(function(){
        // alert($(this).val());
        var shift_name = $(this).val();
        // alert(isEmail(check_email));
        if(shift_name)
        {
            
            $('#submit_scheduling_add').removeAttr('disabled');
            $.post(base_url+'shift_scheduling/check_shift_name/',{shift_name:shift_name},function(res){
                if(res == 'yes'){
                    $('#check_shiftname').css('display','');
                    $('#submit_scheduling_add').attr('disabled','disabled');
                }else{
                    $('#check_shiftname').css('display','none');
                    $('#submit_scheduling_add').removeAttr('disabled');

                }
            });
        }else{
           
            $('#submit_scheduling_add').attr('disabled','disabled');
        }
    });
     $('.edit_shift_name').keydown(function(){
        // alert($(this).val());
        var shift_name = $(this).val();
        var id = $('#shift_id').val();
        // alert(isEmail(check_email));
        if(shift_name)
        {
            
            $('#submit_scheduling_add').removeAttr('disabled');
            $.post(base_url+'shift_scheduling/check_edit_shift_name/',{shift_name:shift_name,id:id},function(res){
                if(res == 'yes'){
                    $('#check_shiftname').css('display','');
                    $('#submit_scheduling_add').attr('disabled','disabled');
                }else{
                    $('#check_shiftname').css('display','none');
                    $('#submit_scheduling_add').removeAttr('disabled');

                }
            });
        }else{
           
            $('#submit_scheduling_add').attr('disabled','disabled');
        }
    });
    $('#holiday_name').keydown(function(){
        // alert($(this).val());
        var holiday_name = $(this).val();
        // alert(isEmail(check_email));
        if(holiday_name)
        {            
            $('#employee_create_holiday').removeAttr('disabled');
            $.post(base_url+'holidays/check_holiday_name/',{holiday_name:holiday_name},function(res){

                if(res == 'yes'){
                    $('#check_holiday_name').css('display','');
                    $('#employee_create_holiday').attr('disabled','disabled');
                }else{
                    $('#check_holiday_name').css('display','none');
                    $('#employee_create_holiday').removeAttr('disabled');

                }
            });
        }else{
           
            $('#employee_create_holiday').attr('disabled','disabled');
        }
    });
   
     $('#edit_holiday_name').keydown(function(){
        // alert($(this).val());
        var holiday_name = $(this).val();
        var id = $('#holiday_id').val();
        // alert(isEmail(check_email));
        if(holiday_name)
        {            
            $('#employee_edit_holiday').removeAttr('disabled');
            $.post(base_url+'holidays/check_edit_holiday_name/',{holiday_name:holiday_name,id:id},function(res){
               
                if(res == 'yes'){
                    $('#check_holiday_name').css('display','');
                    $('#employee_edit_holiday').attr('disabled','disabled');
                }else{
                    $('#check_holiday_name').css('display','none');
                    $('#employee_edit_holiday').removeAttr('disabled');

                }
            });
        }else{
           
            $('#employee_edit_holiday').attr('disabled','disabled');
        }
    });
     $('#project_name').keydown(function(){
        // alert($(this).val());
        var project_name = $(this).val();
        // alert(isEmail(check_email));
        if(project_name)
        {            
            $('#project_add_submit').removeAttr('disabled');
            $.post(base_url+'projects/check_project_name/',{project_name:project_name},function(res){

                if(res == 'yes'){
                    $('#check_project_name').css('display','');
                    $('#project_add_submit').attr('disabled','disabled');
                }else{
                    $('#check_project_name').css('display','none');
                    $('#project_add_submit').removeAttr('disabled');

                }
            });
        }else{
           
            $('#project_add_submit').attr('disabled','disabled');
        }
    });
     $('#edit_project_name').keydown(function(){
        // alert($(this).val());
        var project_name = $(this).val();
        var id = $('#project_id').val();
        // alert(isEmail(check_email));
        if(project_name)
        {            
            $('#project_edit_dashboard').removeAttr('disabled');
            $.post(base_url+'projects/check_edit_project_name/',{project_name:project_name,id:id},function(res){

                if(res == 'yes'){
                    $('#check_edit_project_name').css('display','');
                    $('#project_edit_dashboard').attr('disabled','disabled');
                }else{
                    $('#check_edit_project_name').css('display','none');
                    $('#project_edit_dashboard').removeAttr('disabled');

                }
            });
        }else{
           
            $('#project_edit_dashboard').attr('disabled','disabled');
        }
    });
      $('#contact_number').change(function(){
        var contact_number = $(this).val();
        $.post(base_url+'all_contacts/check_contact_number/',{contact_number:contact_number},function(res){
                if(res == 'yes'){
                    $('#already_contact_number').css('display','');
                    $('#submit_contact_form').attr('disabled','disabled');
                }else{
                    $('#already_contact_number').css('display','none');
                    $('#submit_contact_form').removeAttr('disabled');

                }
        });
    });

   

    /* Clients Module validations  */

    $('#client_search').click(function(){
        var clientname = $('#client_name').val();
        var client_email = $('#client_email').val();
        if(clientname == '' && client_email == '')
        {
            $('#client_name').focus();
            $('#client_name').css('border-color','#77A7DB');
            $('#client_email').css('border-color','#77A7DB');
            $('#client_name_error').removeClass('display-none').addClass('display-block');
            $('#client_email_error').removeClass('display-none').addClass('display-block');
            return false;
        }
        else
        {
            $('#client_name').css('border-color','#ccc');
            $('#client_email').css('border-color','#ccc');
            $('#client_name_error').removeClass('display-block').addClass('display-none');
            $('#client_email_error').removeClass('display-block').addClass('display-none');
        }
    });


    $(document).on('click',"#nextCreateGeneral",function() {
       if($('#create_company_name').val() != '' && $('#create_company_notes').val() != '' &&  /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#create_company_email').val()))
            $('#tabsClient a[href="#tab-client-contact"]').tab('show')
        else
        {
            console.log('general submit');
            $("#createClient").trigger( "click" );
        }
    });

    $(document).on('click',"#nextCreateContact",function() {
        if($('#create_company_name').val().trim() != '' && $('#create_company_notes').val().trim() != '' &&  /^(\+\d{1,3}[- ]?)?\d{10}$/.test($('#create_company_mobile').val()) && /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#create_company_email').val()))
            $('#tabsClient a[href="#tab-client-web"]').tab('show')
        else
        {
            console.log('contact submit');
            $("#createClient").trigger( "click" );
        }
    });

    $(document).on('click','#createClient',function(){
        clientValidateCreate();
    });


    /* Create Form Client  */

    function clientValidateCreate()
    {
        function isPhonePresent() {
            console.log($('#create_client_phone').val().length > 0);
            return $('#create_client_phone').val().length > 0;
        }

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid mobile number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

        $("#createClientForm").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                company_name: {
                    required: true
                },
                notes: {
                    required: true
                },
                company_email: {
                    required: true,
                    emailvalidation: 'emailvalidation'
                },
                company_mobile: {
                    required: true,
                    minlength: 10,
                    maxlength:12,
                    mobilevalidation: 'mobilevalidation'
                },
                company_phone: {
                    minlength: {
                        param:7,
                        depends: isPhonePresent
                    },
                    maxlength: {
                        param:15,
                        depends: isPhonePresent
                    },
                    phonevalidation: {
                        param:'phonevalidation',
                        depends: isPhonePresent
                    },
                }
            },
            messages: {
                company_name: {
                    required: "Company Name must not be empty"
                },
                notes: {
                    required: "Please fill notes field"
                },
                company_email: {
                    required: "Email Id is required",
                    emailvalidation: "Please enter a valid email Id"
                },
                company_mobile: {
                    required: "Mobile Number is required",
                    minlength: "Minimum Length Should be 10 digit",
                    maxlength: "Maximum Length Should be 12 digit",
                    mobilevalidation: "Number should be Valid Mobile Number"
                },
                company_phone: {
                    minlength: "Minimum Length Should be 7 digit",
                    maxlength: "Maximum Length Should be 15 digit",
                    phonevalidation: "Entered Number is Invalid"
                }
            },
            invalidHandler: function(e, validator){
                console.log(validator);
                if(validator.errorList.length)
                $('#tabsClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
           });
    }

    $(document).on('click',"#nextEditGeneral",function() {
        if($('#edit_company_name').val() != '' && $('#edit_company_notes').val() != '' &&  /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#edit_company_email').val()))
             $('#tabsUptClient a[href="#tab-client-contact"]').tab('show')
         else
         {
             console.log('general submit');
             $("#updateClient").trigger( "click" );
         }
     });
 
     $(document).on('click',"#nextEditContact",function() {
         if($('#edit_company_name').val().trim() != '' && $('#edit_company_notes').val().trim() != '' &&  /^(\+\d{1,3}[- ]?)?\d{10}$/.test($('#edit_company_mobile').val()) && /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#edit_company_email').val()))
             $('#tabsUptClient a[href="#tab-client-web"]').tab('show')
         else
         {
             console.log('contact submit');
             $("#updateClient").trigger( "click" );
         }
     });

     /* Update Form Client  */
 
     $(document).on('click','#updateClient',function(){

        function isUptPhonePresent() {
            console.log($('#edit_client_phone').val().length > 0);
            return $('#edit_client_phone').val().length > 0;
        }

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

        $("#editClientForm").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                company_name: {
                    required: true
                },
                notes: {
                    required: true
                },
                company_email: {
                    required: true,
                    emailvalidation: 'emailvalidation'
                },
                company_mobile: {
                    required: true,
                    minlength: 10,
                    maxlength:12,
                    mobilevalidation: 'mobilevalidation'
                },
                company_phone: {
                    minlength: {
                        param:7,
                        depends: isUptPhonePresent
                    },
                    maxlength: {
                        param:15,
                        depends: isUptPhonePresent
                    },
                    phonevalidation: {
                        param:'phonevalidation',
                        depends: isUptPhonePresent
                    },
                }
            },
            messages: {
                company_name: {
                    required: "Company Name must not be empty"
                },
                notes: {
                    required: "Please fill notes field"
                },
                company_email: {
                    required: "Email Id is required",
                    emailvalidation: "Please enter a valid email Id"
                },
                company_mobile: {
                    required: "Mobile Number is required",
                    minlength: "Minimum Length Should be 10 digit",
                    maxlength: "Maximum Length Should be 12 digit",
                    mobilevalidation: "Number should be Valid Mobile Number"
                },
                company_phone: {
                    minlength: "Minimum Length Should be 7 digit",
                    maxlength: "Maximum Length Should be 15 digit",
                    phonevalidation: "Entered Number is Invalid"
                }
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });


        /* Add Contact in Client Module */


        $(document).on('click','#addContactClient',function(){

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

            $("#addContactForm").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                       required: true,
                       emailvalidation: 'emailvalidation'
                    },
                    password: {
                        required: true,
                        minlength : 6
                    },
                    confirm_password: {
                        required: true,
                        minlength : 6,
					    equalTo : "#password"
                    },
                    phone: {
                        required: true,
                        minlength: 7,
                        maxlength:15,
                        phonevalidation: 'phonevalidation'
                    }
                },
                messages: {
                    fullname: {
                        required: "Full Name must not be empty"
                    },
                    username: {
                        required: "User Name must not be empty"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    password: {
                        required : "Password field must not be empty",
                        minlength : "Minimum Length Should be 5 letters"
                    },
                    confirm_password: {
                        required : "Confirm Password field must not be empty",
                        minlength : "Maximum Length Should be 5 letters",
					    equalTo : "Password and Confirm Password are Mismatched"
                    },
                    phone: {
                        required: "Phone Number is required",
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            });

        /* Update Contact in Client Module */

        function isUptContactMobilePresent() {
            console.log($('#edit_contact_mobile').val().length > 0);
            return $('#edit_contact_mobile').val().length > 0;
        }

        $(document).on('click','#updateContactClient',function(){

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

            $("#editContactForm").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    email: {
                       required: true,
                       emailvalidation: 'emailvalidation'
                    },
                    phone: {
                        required: true,
                        minlength: 7,
                        maxlength:15,
                        phonevalidation: 'phonevalidation'
                    },
                    mobile: {
                        minlength: {
                            param:7,
                            depends: isUptContactMobilePresent
                        },
                        maxlength: {
                            param:15,
                            depends: isUptContactMobilePresent
                        },
                        phonevalidation: {
                            param:'mobilevalidation',
                            depends: isUptContactMobilePresent
                        },
                    }
                },
                messages: {
                    fullname: {
                        required: "Full Name must not be empty"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    phone: {
                        required: "Phone Number is required",
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    },
                    mobile: {
                        minlength: "Minimum Length Should be 10 digit",
                        maxlength: "Maximum Length Should be 12 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            });


        /* Upload Files in Client Module */

        
        $(document).on('click','#client_file_upload',function(){

            $("#clientFilesForm").validate({
                ignore: [],
                rules: {
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    'clientfiles[]': { 
                        required: true, 
                        extension: "png|jpe?g|gif|doc|docx|pdf|txt|xls", 
                        filesize: 3133456
                    }
                },
                messages: {
                    title: {
                        required: "Title must not be empty"
                    },
                    description: {
                        required: "Description must not be empty"
                    },
                    'clientfiles[]': {
                        required: "Please upload a file on empty fields",
                        extension: "Please upload image and text or doc files",
                        filesize: "One of your uploaded filesize must not exceed 3MB"
                    }
                },
                submitHandler: function(form) {
                    //form.submit();
                }
                
               });
            });


            /* Client Comment Form */

            $(document).on('click','#create_client_comment',function(){

                var textareaValue = $('.foeditor-client-comment').summernote('code');
                console.log(textareaValue)
                if(textareaValue == '<p><br></p>' || textareaValue == '')
                {
                   $('#client_comment_error').removeClass('display-none').addClass('display-block');
                   $('.note-editable').trigger('focus');
                   return false;
                }
                else
                {
                    $('#client_comment_error').removeClass('display-block').addClass('display-none');
                                 
                }
                
            });
            

            // $('#employee_search_btn').click(function(){
            //     var employee_id = $('#employee_id :selected').val();
            //     var ser_leave_date_from = $('#search_from_date').val();
            //     var ser_leave_date_to = $('#search_to_date').val();
            //     if(employee_id =='' && ser_leave_date_from =='' && ser_leave_date_to ==''){
                   
            //         return false;
            //     }
            // });


        /* Employees Module  */

        /* All Employees */

        $(document).on('click','#employee_search_btn,#grid_employee_search_btn',function(){
            var employee_id = $('#employee_id').val();
            var username = $('#username').val();
            var email = $('#employee_email').val();
            var department_id = $('#department_id').val();

            

            if(employee_id == '' && username == '' && email == ''){
                $('#employee_id').focus();
                $('#employee_id').css('border-color','#77A7DB');
                $('#username').css('border-color','#77A7DB');
                $('#employee_email').css('border-color','#77A7DB');
                $('#employee_id_error').removeClass('display-none').addClass('display-block');
                $('#employee_name_error').removeClass('display-none').addClass('display-block');
                $('#employee_email_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#employee_id').css('border-color','#ccc');
                $('#username').css('border-color','#ccc');
                $('#employee_email').css('border-color','#ccc');
                $('#employee_id_error').removeClass('display-block').addClass('display-none');
                $('#employee_name_error').removeClass('display-block').addClass('display-none');
                $('#employee_email_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        
        $(document).on('click','#register_btn',function(){
            console.log('Add Employee');
            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            $.validator.addMethod("isPhoneValid",
                function(value, element) {
                        return /^[0-9 ()+]+$/.test(value);
                },
            "Please enter a valid Phone number."
            );

            function isLeaderPresent() {
                console.log($('#is_teamlead').prop('checked'));
                return $('#is_teamlead').prop('checked') == true;
            }

            $("#employeeAddForm").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                        required: true,
                        emailvalidation: 'emailvalidation'
                    },
                    gender: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength : 6
                    },
                    confirm_password: {
                        required: true,
                        minlength : 6,
					    equalTo : "#password"
                    },
                    phone: {
                        required: true,
                        // minlength: 5,
                        isPhoneValid: 'isPhoneValid'
                    },
                    department_name: {
                        required:true
                    },
                    designations: {
                        required:true
                    },
                    team_leaders_name: {
                        required:isLeaderPresent
                    },
                     emp_doj: {
                        required:true
                    }
                },
                messages: {
                    fullname: {
                        required: 'Fullname must not empty'
                    },
                    username: {
                        required: 'Username must not empty'
                    },
                    email: {
                        required: 'Email field is required',
                        emailvalidation: 'Entered email is invalid'
                    },
                    password: {
                        required: 'Password is required',
                        minlength : 'Password should be 6 characters minimum'
                    },
                    confirm_password: {
                        required: 'Confirm password is required',
                        minlength : 'Password should be 6 characters minimum',
					    equalTo : "Passwords are mismatched"
                    },
                    phone: {
                        required: 'Phone must not empty',
                        minlength: "Numbers minimum length should be 10 digits",
                        isPhoneValid: 'Entered Number is invalid'
                    },
                    department_name: {
                        required:'Please select a department',
                    },
                    designations: {
                        required:'Please select a designation'
                    },
                    team_leaders_name: {
                        required:'Please select a Team Leader'
                    },
                    emp_doj: {
                        required:'Please select a DOJ'
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });
        
        $(document).on('click','#employee_edit_user',function(){
            console.log('update Employee');
            
            $.validator.addMethod("mobilevalidation",
            function(value, element) {
                    return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
            },
            "Please enter a valid mobile number."
            );

            $.validator.addMethod("isPhoneValid",
                function(value, element) {
                        return /^[\s\+]?([\0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                },
            "Please enter a valid Phone number."
            );

            function isMobilePresent() {
                console.log($('#edit_employee_mobile').val().length);
                return $('#edit_employee_mobile').val().length > 0;
            }

            $("#employeeEditUser").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        isPhoneValid: 'isPhoneValid'
                    },
                    mobile: {
                        minlength: {
                            param:10,
                            depends: isMobilePresent
                        },
                        mobilevalidation: {
                            param:'mobilevalidation',
                            depends: isMobilePresent
                        }
                    },
                    department_id: {
                        required:true
                    },
                    designations: {
                        required:true
                    }
                },
                messages: {
                    fullname: {
                        required: 'Fullname must not empty'
                    },
                    mobile: {
                        minlength: 'Mobile number minimum length should be 10 digit',
                        mobilevalidation: 'Mobile Number is invalid'
                    },
                    phone: {
                        required: 'Phone must not empty',
                        minlength: "Phone number minimum length should be 10 digits",
                        isPhoneValid: 'Entered Number is invalid'
                    },
                    department_id: {
                        required:'Please select a department',
                    },
                    designations: {
                        required:'Please select a designation'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Leaves submodule */

        $(document).on('click','#admin_search_leave',function(){
            var leaveType = $('#ser_leave_type option:selected').val();
            var status = $('#ser_leave_sts option:selected').val();
            var username = $('#ser_leave_user_name').val();
            var dateFrom = $('#ser_leave_date_from').val();
            var dateTo = $('#ser_leave_date_to').val();

            console.log(leaveType);
            console.log(status);
            console.log(username);
            console.log(dateFrom);
            console.log(dateTo);

            if(leaveType == '' && status == '' && username == '' && dateFrom == '' && dateTo == ''){
                $('#ser_leave_user_name').focus();
                $('#ser_leave_user_name').css('border-color','#77A7DB');
                $('#ser_leave_date_from').css('border-color','#77A7DB');
                $('#ser_leave_date_to').css('border-color','#77A7DB');
                $('#ser_leave_type_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_sts_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_user_name_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_date_from_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#ser_leave_sts').css('border-color','#ccc');
                $('#ser_leave_user_name').css('border-color','#ccc');
                $('#ser_leave_date_from').css('border-color','#ccc');
                $('#ser_leave_date_to').css('border-color','#ccc');
                $('#ser_leave_type_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_user_name_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_sts_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_date_from_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_date_to_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        $(document).on('click','#employee_add_leave',function(){
            console.log('leave');
            $("#employeesAddLeave").validate({
                ignore: [],
                rules: {
                    req_leave_type: {
                        required: true
                    },
                    req_leave_date_from: {
                        required: true
                    },
                    req_leave_date_to: {
                        required: true
                    },
                    req_leave_reason: {
                        required: true
                    }
                    
                },
                messages: {
                    req_leave_type: {
                        required: 'Please select a leave type'
                    },
                    req_leave_date_from: {
                        required: 'From Date is required'
                    },
                    req_leave_date_to: {
                        required: 'To Date is required'
                    },
                    req_leave_reason: {
                        required: 'Leave reason is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        
        $(document).on('click','#employee_create_holiday',function(){
            console.log('leave');
            $("#employeeCreateHoliday").validate({
                ignore: [],
                rules: {
                    holiday_title: {
                        required: true
                    },
                    holiday_date: {
                        required: true
                    },
                    holiday_description: {
                        required: true
                    }                    
                },
                messages: {
                    holiday_title: {
                        required: 'Holiday Title is required'
                    },
                    holiday_date: {
                        required: 'Date of holiday is required'
                    },
                    holiday_description: {
                        required: 'Descritption is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#employee_edit_holiday',function(){
            
            $("#employeeEditHoliday").validate({
                ignore: [],
                rules: {
                    holiday_title: {
                        required: true
                    },
                    holiday_date: {
                        required: true
                    },
                    holiday_description: {
                        required: true
                    }                    
                },
                messages: {
                    holiday_title: {
                        required: 'Holiday Title is required'
                    },
                    holiday_date: {
                        required: 'Date of holiday is required'
                    },
                    holiday_description: {
                        required: 'Descritption is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });



        /* Projects Module  */

        $(document).on('click','#project_search_btn',function(){
            var title = $('#project_title').val();
            var name = $('#client_name').val();
           
            if(title == '' && name == ''){
                $('#project_title').focus();
                $('#client_name').css('border-color','#77A7DB');
                $('#project_title').css('border-color','#77A7DB');
                $('#project_title_error').removeClass('display-none').addClass('display-block');
                $('#client_name_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#client_name').css('border-color','#ccc');
                $('#project_title').css('border-color','#ccc');
                $('#project_title_error').removeClass('display-block').addClass('display-none');
                $('#client_name_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        $(document).on('click','#project_files_add',function(){

            $("#projectFilesAdd").validate({
                ignore: [],
                rules: {
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    'projectfiles[]': { 
                        required: true, 
                        extension: "png|jpe?g|gif|doc|docx|pdf|txt|xls", 
                        filesize: 3133456
                    }
                },
                messages: {
                    title: {
                        required: "Title must not be empty"
                    },
                    description: {
                        required: "Description must not be empty"
                    },
                    'projectfiles[]': {
                        required: "Please upload a file on empty fields",
                        extension: "Please upload image and text or doc files",
                        filesize: "One of your uploaded filesize must not exceed 3MB"
                    }
                },
                submitHandler: function(form) {
                    //form.submit();
                }
                
               });
            });

            $(document).on('click','#project_files_edit',function(){

                $("#projectFilesEdit").validate({
                    ignore: [],
                    rules: {
                        title: {
                            required: true
                        },
                        description: {
                            required: true
                        }
                    },
                    messages: {
                        title: {
                            required: "Title must not be empty"
                        },
                        description: {
                            required: "Description must not be empty"
                        }
                    },
                    submitHandler: function(form) {
                        //form.submit();
                    }
                    
                   });
                });

                $(document).on('click','#project_task_files',function(){

                    $("#projectTaskFiles").validate({
                        ignore: [],
                        rules: {
                            title: {
                                required: true
                            },
                            description: {
                                required: true
                            },
                            'taskfiles[]': { 
                                required: true, 
                                extension: "png|jpe?g|gif|doc|docx|pdf|txt|xls", 
                                filesize: 3133456
                            }
                        },
                        messages: {
                            title: {
                                required: "Title must not be empty"
                            },
                            description: {
                                required: "Description must not be empty"
                            },
                            'taskfiles[]': {
                                required: "Please upload a file on empty fields",
                                extension: "Please upload image and text or doc files",
                                filesize: "One of your uploaded filesize must not exceed 3MB"
                            }
                        },
                        submitHandler: function(form) {
                            //form.submit();
                        }
                        
                       });
                    });

                    $(document).on('click','#project_task_filesU',function(){

                        $("#projectTaskFilesU").validate({
                            ignore: [],
                            rules: {
                                title: {
                                    required: true
                                },
                                description: {
                                    required: true
                                }
                            },
                            messages: {
                                title: {
                                    required: "Title must not be empty"
                                },
                                description: {
                                    required: "Description must not be empty"
                                }
                            },
                            submitHandler: function(form) {
                                //form.submit();
                            }
                            
                           });
                        });

    // Task Comment

                    $(document).on('click','#task_view_comment',function(){

                        var textareaValue = $('.foeditor-taskview-comment').summernote('code').replace(/&nbsp;/g,'');
                        console.log(textareaValue)
                        if(textareaValue.replace(/ /g,'') == '<p></p>' || textareaValue.replace(/ /g,'') == '<p><br></p>' || textareaValue.replace(/ /g,'') == '')
                        {
                            $('#taskview_comment_error').removeClass('display-none').addClass('display-block');
                            $('.note-editable').trigger('focus');
                            return false;
                        }
                        else
                        {
                            $('#taskview_comment_error').removeClass('display-block').addClass('display-none');
                        }
                    });


        $(document).on('click','#project_add_submit',function(){

            function isRatePresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == false;
            }

            function isFixedPresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == true;
            }

            var textareaValue = $('.foeditor-project-add').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#addproject_description_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#addproject_description_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#projectAddForm").validate({
                ignore: [],
                rules: {
                    project_code: {
                        required: true
                    },
                    project_title: {
                        required: true
                    },
                    client: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    },
                    assign_lead: {
                        required: true
                    },
                    'assign_to[]': {
                        required: true
                    },
                    hourly_rate:{
                        required: isRatePresent,
                        number: true
                    },
                    fixed_price:{
                        required: isFixedPresent,
                        number: true
                    },
                    estimate_hours: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    project_code: {
                        required: 'Project is required'
                    },
                    project_title: {
                        required: 'Project is required'
                    },
                    client: {
                        required: 'Please select a client'
                    },
                    start_date: {
                        required: 'Start date is required'
                    },
                    due_date: {
                        required: 'Deadline is required'
                    },
                    assign_lead: {
                        required: 'Please select a lead'
                    },
                    'assign_to[]': {
                        required: 'Please choose a assignee'
                    },
                    hourly_rate:{
                        required: 'Please enter hourly rate'
                        
                    },
                    fixed_price:{
                        required: 'Please enter fixed price'
                    },
                    estimate_hours: {
                        required: 'Estimate hours is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });

        $(document).on('click','#project_edit_dashboard',function(){

            function isRatePresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == false;
            }

            function isFixedPresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == true;
            }

            var textareaValue = $('.foeditor-project-edit').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#project_description_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#project_description_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#projectEditForm").validate({
                ignore: [],
                rules: {
                    project_code: {
                        required: true
                    },
                    project_title: {
                        required: true
                    },
                    client: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    },
                    assign_lead: {
                        required: true
                    },
                    'assign_to[]': {
                        required: true
                    },
                    hourly_rate:{
                        required: isRatePresent,
                        number: true
                    },
                    fixed_price:{
                        required: isFixedPresent,
                        number: true
                    },
                    estimate_hours: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    project_code: {
                        required: 'Project is required'
                    },
                    project_title: {
                        required: 'Project is required'
                    },
                    client: {
                        required: 'Please select a client'
                    },
                    start_date: {
                        required: 'Start date is required'
                    },
                    due_date: {
                        required: 'Deadline is required'
                    },
                    assign_lead: {
                        required: 'Please select a lead'
                    },
                    'assign_to[]': {
                        required: 'Please choose a assignee'
                    },
                    hourly_rate:{
                        required: 'Please enter hourly rate'
                        
                    },
                    fixed_price:{
                        required: 'Please enter fixed price'
                    },
                    estimate_hours: {
                        required: 'Estimate hours is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });

        /* Project checklist */

        $(document).on('click','#project_create_checklist',function(){

            $("#createChecklistForm").validate({
                ignore: [],
                rules: {
                    todo_item: {
                        required: true
                    }
                },
                messages: {
                    todo_item: {
                        required: "Todo Item must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_update_checklist',function(){

            $("#editChecklistForm").validate({
                ignore: [],
                rules: {
                    todo_item: {
                        required: true
                    }
                },
                messages: {
                    todo_item: {
                        required: "Todo Item must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#project_add_task',function(){

            $("#projectAddTask").validate({
                ignore: [],
                rules: {
                    task_name_auto: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#add_task',function(){

            $("#AddTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    priority: {
                        required: true
                    },
                    // start_date: {
                    //     required: true
                    // },
                    due_date: {
                        required: true
                    },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    'assigned_to[]': {
                        required:true
                    }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    priority: {
                        required: "Please select a priority"
                    },
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    due_date: {
                        required: "Deadline must not empty"
                    },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_edit_task',function(){

            $("#projectEditTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_add_template',function(){

            $("#projectAddTemplateForm").validate({
                ignore: [],
                rules: {
                    template_id: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    template_id: {
                        required: "Please select a template"
                    },
                    start_date: {
                        required: "Start Date must not empty"
                    },
                    due_date: {
                        required: "Deadline must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        
        $(document).on('click','#project_add_milestone',function(){

            $("#projectAddMilestone").validate({
                ignore: [],
                rules: {
                    milestone_name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    milestone_name: {
                        required: "Milestone Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    },
                    start_date: {
                        required: "Start Date must not empty"
                    },
                    due_date: {
                        required: "Deadline must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_edit_milestone',function(){

            $("#projectEditMilestone").validate({
                ignore: [],
                rules: {
                    milestone_name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    milestone_name: {
                        required: "Milestone Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    },
                    start_date: {
                        required: "Start Date must not empty"
                    },
                    due_date: {
                        required: "Deadline must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#project_add_mile_task',function(){

            $("#projectAddMileTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_update_team',function(){

            $("#projectUpdateTeam").validate({
                ignore: [],
                rules: {
                    'assigned_to[]': {
                        required:true
                    }
                },
                messages: {
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* foeditor-project-discussion */
        $(document).on('click','#project_comment_discussion',function(){

            var textareaValue = $('.foeditor-project-discussion').summernote('code');
            console.log(textareaValue);
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
               $('#project_comment_error').removeClass('display-none').addClass('display-block');
               $('.note-editable').trigger('focus');
               return false;
            }
            else
            {
                $('#project_comment_error').removeClass('display-block').addClass('display-none');
                             
            }
            
        });

        
        $(document).on('click','#project_add_bug',function(){

            $("#projectAddBug").validate({
                ignore: [],
                rules: {
                    issue_ref: {
                        required: true
                    },
                    issue_title: {
                        required: true
                    },
                    bug_description: {
                        required: true
                    },
                    reproducibility: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    },
                    severity:{
                        required: true
                    },
                    estimate: {
                        required: true,
                        number: true
                    },
                    'assigned_to[]': {
                        required:true
                    }
                },
                messages: {
                    issue_ref: {
                        required: "Id must not empty"
                    },
                    issue_title: {
                        required: "Title must not empty"
                    },
                    bug_description: {
                        required: "Description must not empty"
                    },
                    reproducibility: {
                        required: "Reproducibility must not empty"
                    },
                    reporter: {
                        required: "Please select a reporter"
                    },
                    priority: {
                        required: "Please select a priority"
                    },
                    severity:{
                        required: "Please select a severity"
                    },
                    estimate: {
                        required: "Estimate must not empty"
                    },
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_edit_bug',function(){

            $("#projectEditBug").validate({
                ignore: [],
                rules: {
                    issue_ref: {
                        required: true
                    },
                    issue_title: {
                        required: true
                    },
                    bug_description: {
                        required: true
                    },
                    reproducibility: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    },
                    severity:{
                        required: true
                    },
                    estimate: {
                        required: true,
                        number: true
                    },
                    'assigned_to[]': {
                        required: true
                    }
                },
                messages: {
                    issue_ref: {
                        required: "Id must not empty"
                    },
                    issue_title: {
                        required: "Title must not empty"
                    },
                    bug_description: {
                        required: "Description must not empty"
                    },
                    reproducibility: {
                        required: "Reproducibility must not empty"
                    },
                    reporter: {
                        required: "Please select a reporter"
                    },
                    priority: {
                        required: "Please select a priority"
                    },
                    severity:{
                        required: "Please select a severity"
                    },
                    estimate: {
                        required: "Estimate must not empty"
                    },
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        
        $(document).on('click','#project_add_mile_task',function(){

            $("#projectAddMileTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* CRM add lead */

        $(document).on('click','#submit_lead_form',function(){
              $.validator.addMethod("phonevalidation",
                function(value, element) {
                    return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                      
                },
            "Please enter a valid phone number."
            );
    
            $.validator.addMethod("emailvalidation",
                    function(value, element) {
                            return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                    },
            "Please enter a valid email address."
            );
            
            $("#AddLeadForm").validate({
                ignore: [],
                rules: {
                    name: {
                        required: true
                    },
                    project_name: {
                        required: true
                    },
                    project_amount: {
                        required: true
                    },
                    email: {
                        required: true,
                        emailvalidation: 'emailvalidation'
                    },
                    phone_no: {
                        required: true,
                        phonevalidation: 'phonevalidation'
                    }
                },
                messages: {
                    name: {
                        required: 'Name is required'
                    },
                    project_name: {
                        required: 'Project name is required'
                    },
                    project_amount: {
                        required: 'Amount is required'
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    phone_no: {
                        required: 'Phone number is required',
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            
        });

         $(document).on('click','#submit_edit_lead_form',function(){
              $.validator.addMethod("phonevalidation",
                function(value, element) {
                    return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                      
                },
            "Please enter a valid phone number."
            );
    
            $.validator.addMethod("emailvalidation",
                    function(value, element) {
                            return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                    },
            "Please enter a valid email address."
            );
            
            $("#EditLeadForm").validate({
                ignore: [],
                rules: {
                    name: {
                        required: true
                    },
                    project_name: {
                        required: true
                    },
                    project_amount: {
                        required: true
                    },
                    email: {
                        required: true,
                        emailvalidation: 'emailvalidation'
                    },
                    phone_no: {
                        required: true,
                        phonevalidation: 'phonevalidation'
                    }
                },
                messages: {
                    name: {
                        required: 'Name is required'
                    },
                    project_name: {
                        required: 'Project name is required'
                    },
                    project_amount: {
                        required: 'Amount is required'
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    phone_no: {
                        required: 'Phone number is required',
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            
        });

        /* Shift Schedule Module */

        $(document).on('click','#submit_shift_scheduling_add',function(){

            
            $("#employeeScheduleAddForm").validate({
                ignore: [],
                rules: {
                    department: {
                        required: true
                    },
                    'employee[]': {
                        required: true
                    },
                    schedule_date: {
                        required: true
                    },
                    shift_id: {
                        required: true
                    },
                    color: {
                        required: true
                    }
                    // min_start_time: {
                    //     required: true
                    // },
                    // start_time: {
                    //     required: true
                    // },
                    // max_start_time: {
                    //     required: true
                    // },
                    // min_end_time: {
                    //     required: true
                    // },
                    // end_time: {
                    //     required: true
                    // },
                    // max_end_time: {
                    //     required: true
                    // },
                    // break_start: {
                    //     required: true
                    // },
                    // break_end: {
                    //     required: true
                    // }
                    // repeat_time:{
                    //     required: true
                    // }
                },
                messages: {
                    department: {
                        required: 'Department is required'
                    },
                    'employee[]': {
                        required: 'Employee is required'
                    },
                    schedule_date: {
                        required: 'Schedule date is required'
                    },
                    shift_id: {
                        required: 'Please select shift'
                    },
                     color: {
                        required: 'Please select color'
                    }
                    // min_start_time: {
                    //     required: 'Minimum Start time is required'
                    // },
                    // start_time: {
                    //     required: 'Start time is required'
                    // },
                    // max_start_time: {
                    //     required: 'Maximum Start time is required'
                    // },
                    // min_end_time: {
                    //     required: 'Minimum End time is required'
                    // },
                    // end_time: {
                    //     required: 'End time is required'
                    // },
                    // max_end_time: {
                    //     required: 'Maximum End time is required'
                    // },
                    // break_start: {
                    //     required: 'Break start time is required'
                    // },
                    //  break_end: {
                    //     required: 'Break end time is required'
                    // }
                    // repeat_time: {
                    //     required: 'Repeat is required'
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            
        });

        $(document).on('click','#submit_scheduling_add',function(){

            
            $("#scheduleAddForm").validate({
                ignore: [],
                rules: {
                    
                    shift_name: {
                        required: true
                    },

                    start_date: {
                        required: true
                    },
                    // min_start_time: {
                    //     required: true
                    // },
                    start_time: {
                        required: true
                    },
                    // max_start_time: {
                    //     required: true
                    // },
                    // min_end_time: {
                    //     required: true
                    // },
                    end_time: {
                        required: true
                    },
                    // max_end_time: {
                    //     required: true
                    // },
                     end_date: {
                         required :  function(element){
                        if($('#indefinite').is(":checked")){
                            return false;
                        }else{
                            return true;
                        }
                    }
                    }
                    // repeat_time:{
                    //     required: true
                    // }
                },
                messages: {
                   
                    shift_name: {
                        required: 'Shift name is required'
                    },
                    start_date: {
                        required: 'Start date is required'
                    },
                    min_start_time: {
                        required: 'Minimum Start time is required'
                    },
                    start_time: {
                        required: 'Start time is required'
                    },
                    max_start_time: {
                        required: 'Maximum Start time is required'
                    },
                    min_end_time: {
                        required: 'Minimum End time is required'
                    },
                    end_time: {
                        required: 'End time is required'
                    },
                    max_end_time: {
                        required: 'Maximum End time is required'
                    },
                     end_date: {
                        required: 'End date is required'
                    }
                    // break_time: {
                    //     required: 'Break time is required'
                    // }
                    // repeat_time: {
                    //     required: 'Repeat is required'
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            
        });
        
        $(document).ready(function() {
      $(".only-numeric").bind("keypress", function (e) {
          var keyCode = e.which ? e.which : e.keyCode
               
          if (!(keyCode >= 48 && keyCode <= 57)) {
            $(".error").css("display", "inline");
            return false;
          }else{
            $(".error").css("display", "none");
          }
      });
    });

        /* End Shift Schedule Module */

        /* Calendar Module */

        $(document).on('click','#calendar_add_event',function(){

            $("#calendarAddEvent").validate({
                ignore: [],
                rules: {
                    event_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    add_event_date_from: {
                        required: true
                    },
                    add_event_date_to: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    color: {
                        required:true
                    }
                },
                messages: {
                    event_name: {
                        required: "Event Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    add_event_date_from: {
                        required: "From Date must not empty"
                    },
                    add_event_date_to: {
                        required: "To Date must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    color: {
                        required: "Color must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#calendar_edit_event',function(){

            $("#calendarEditEvent").validate({
                ignore: [],
                rules: {
                    event_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    add_event_date_from: {
                        required: true
                    },
                    add_event_date_to: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    color: {
                        required:true
                    }
                },
                messages: {
                    event_name: {
                        required: "Event Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    add_event_date_from: {
                        required: "From Date must not empty"
                    },
                    add_event_date_to: {
                        required: "To Date must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    color: {
                        required: "Color must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


         /* Accounting Module  */

         /* Invoice Submodule  */

         $(document).on('click','#tableinvoices_btn',function(){
            var from = $('#invoice_date_from').val();
            var to = $('#invoice_date_to').val();
            var status =  $('#invoices_status option:selected').val();
           
            if(from == '' && to == '' && status == ''){
                $('#invoice_date_from').focus();
                $('#invoice_date_from').css('border-color','#77A7DB');
                $('#invoice_date_to').css('border-color','#77A7DB');
                $('#invoice_date_from_error').removeClass('display-none').addClass('display-block');
                $('#invoice_date_to_error').removeClass('display-none').addClass('display-block');
                $('#invoices_status_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#invoice_date_from').css('border-color','#ccc');
                $('#invoice_date_to').css('border-color','#ccc');
                $('#invoice_date_from_error').removeClass('display-block').addClass('display-none');
                $('#invoice_date_to_error').removeClass('display-block').addClass('display-none');
                $('#invoices_status_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        $(document).on('click','#invoice_email_template',function(){

            $("#invoiceEmailForm").validate({
                ignore: [],
                rules: {
                    subject: {
                        required: true
                    }
                },
                messages: {
                    subject: {
                        required: 'Subject is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });

        $(document).on('click','#inview_add_item',function(){

            $("#invoiceAddItem").validate({
                ignore: [],
                rules: {
                    item: {
                        required: true
                    }
                },
                messages: {
                    item: {
                        required: 'Item is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });

        $(document).on('click','#invoice_reminder_template',function(){

            $("#invoiceReminderForm").validate({
                ignore: [],
                rules: {
                    subject: {
                        required: true
                    }
                },
                messages: {
                    subject: {
                        required: 'Subject is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });


        $(document).on('click','#invoice_create_submit',function(){

            function isTax1Present() {
                console.log($('#invoice_create_tax1').val());
                var tax1 = $('#invoice_create_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#create_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax1').focus();
                    }
                }
                else{
                    $('#create_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#invoice_create_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#create_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax2').focus();
                    }
                }
                else{
                    $('#create_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#invoice_create_discount').val());
                var discount = $('#invoice_create_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#create_invoice_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_discount_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_discount').focus();
                    }
                }
                else{
                    $('#create_invoice_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isExtrafeePresent() {
                console.log($('#invoice_create_extrafee').val());
                var fee = $('#invoice_create_extrafee').val();
                if(fee != '')
                {
                    if(/^(\d*\.)?\d+$/.test(fee))
                    {
                        $('#create_invoice_fee_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_fee_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_extrafee').focus();
                    }
                }
                else{
                    $('#create_invoice_fee_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            var textareaValue = $('.foeditor-invoice-create').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#create_invoice_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#create_invoice_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#createInvoiceForm").validate({
                ignore: [],
                rules: {
                    client: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    client: {
                        required: 'Please select a client'
                    },
                    due_date: {
                        required: 'Deadline must not empty'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });

        $(document).on('click','#invoice_edit_submit',function(){

            function isTax1Present() {
                console.log($('#invoice_edit_tax1').val());
                var tax1 = $('#invoice_edit_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#edit_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax1').focus();
                    }
                }
                else{
                    $('#edit_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#invoice_edit_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#edit_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax2').focus();
                    }
                }
                else{
                    $('#edit_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#invoice_edit_discount').val());
                var discount = $('#invoice_edit_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#edit_invoice_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_discount_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_edit_discount').focus();
                    }
                }
                else{
                    $('#edit_invoice_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isExtrafeePresent() {
                console.log($('#invoice_edit_extrafee').val());
                var fee = $('#invoice_edit_extrafee').val();
                if(fee != '')
                {
                    if(/^(\d*\.)?\d+$/.test(fee))
                    {
                        $('#edit_invoice_fee_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_fee_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_extrafee').focus();
                    }
                }
                else{
                    $('#edit_invoice_fee_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            var textareaValue = $('.foeditor-invoice-edit').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#edit_invoice_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#edit_invoice_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#editInvoiceForm").validate({
                ignore: [],
                rules: {
                    client: {
                        required: true
                    },
                    due_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    client: {
                        required: 'Please select a client'
                    },
                    due_date: {
                        required: 'Deadline must not empty'
                    },
                    due_date: {
                        required: 'Deadline must not empty'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });


        /* Estimates Submodule  */

        $(document).on('click','#esview_add_item',function(){

            $("#estimateAddItem").validate({
                ignore: [],
                rules: {
                    item: {
                        required: true
                    },
                    quantity: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    item: {
                        required: 'Item is required'
                    },
                    quantity: {
                        required: 'Quantity is required'
                       
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });


        $(document).on('click','#search_estimates_btn',function(){

            var status = $('#estimates_status').val();
            var from = $('#estimates_from').val();
            var to = $('#estimates_to').val();

            if(from == '' && to == '' && status == ''){
                $('#estimates_from').focus();
                $('#estimates_from').css('border-color','#77A7DB');
                $('#estimates_to').css('border-color','#77A7DB');
                $('#estimates_from_error').removeClass('display-none').addClass('display-block');
                $('#estimates_to_error').removeClass('display-none').addClass('display-block');
                $('#estimates_status_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#estimates_from').css('border-color','#ccc');
                $('#estimates_to').css('border-color','#ccc');
                $('#estimates_from_error').removeClass('display-block').addClass('display-none');
                $('#estimates_to_error').removeClass('display-block').addClass('display-none');
                $('#estimates_status_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });


        $(document).on('click','#estimate_email_template',function(){

            $("#estimateEmailForm").validate({
                ignore: [],
                rules: {
                    subject: {
                        required: true
                    }
                },
                messages: {
                    subject: {
                        required: 'Subject is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });

        

        
        $(document).on('click','#createEstimate',function(){
            var notes = $('.foeditor-estimate-cnote').summernote('code');
            var client = $('#create_estimate_client').val();
            console.log(notes)
            console.log(client)
            //alert(notes);
            
            if(notes == '<p><br></p>' || notes == '')
            {
                $('#estimates_notes_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                
            }
            else
            {
                $('#estimates_notes_error').removeClass('display-block').addClass('display-none');
                                
            }

            if(client == '' || client == null)
            {
                $('#estimates_client_error').removeClass('display-none').addClass('display-block');
                $('#create_estimate_client').select2('open');
            }
            else
            {
                $('#estimates_client_error').removeClass('display-block').addClass('display-none');
                                
            }

            if((client == '' || client == null)  || (notes == '<p><br></p>' || notes == ''))
            {
                console.log('false');
                return false;
            }
            else
            {
                console.log('true');
            }

            function isTax1Present() {
                console.log($('#estimate_create_tax1').val());
                var tax1 = $('#estimate_create_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#create_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_estimate_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_create_tax1').focus();
                    }
                }
                else{
                    $('#create_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#estimate_create_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#create_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_estimate_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_create_tax2').focus();
                    }
                }
                else{
                    $('#create_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#estimate_create_discount').val());
                var discount = $('#estimate_create_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#create_estimate_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_estimate_discount_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_create_discount').focus();
                    }
                }
                else{
                    $('#create_estimate_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            
        });

        $(document).on('click','#editEstimate',function(){
            var notes = $('.foeditor-estimate-cnote').summernote('code');
            var client = $('#edit_estimate_client').val();
            console.log(notes)
            console.log(client)
            
            if(notes == '<p><br></p>' || notes == '')
            {
                $('#estimates_notes_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                
            }
            else
            {
                $('#estimates_notes_error').removeClass('display-block').addClass('display-none');
                                
            }

            if(client == '' || client == null)
            {
                $('#estimates_client_error').removeClass('display-none').addClass('display-block');
                $('#create_estimate_client').select2('open');
            }
            else
            {
                $('#estimates_client_error').removeClass('display-block').addClass('display-none');
                                
            }

            if((client == '' || client == null)  || (notes == '<p><br></p>' || notes == ''))
            {
                console.log('false');
                return false;
            }
            else
            {
                console.log('true');
            }

            function isTax1Present() {
                console.log($('#estimate_edit_tax1').val());
                var tax1 = $('#estimate_edit_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#edit_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_estimate_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_edit_tax1').focus();
                    }
                }
                else{
                    $('#edit_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#estimate_edit_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#edit_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_estimate_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_edit_tax2').focus();
                    }
                }
                else{
                    $('#edit_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#estimate_edit_discount').val());
                var discount = $('#estimate_edit_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#edit_estimate_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_estimate_discount_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_edit_discount').focus();
                    }
                }
                else{
                    $('#edit_estimate_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            
        });



        /* Expense Submodule  */

        $(document).on('click','#search_expenses_btn',function(){

            var category = $('#expenses_category option:selected').val();
            var from = $('#expenses_date_from').val();
            var to = $('#expenses_date_to').val();
            var project = $('#expenes_project').val();
            var client = $('#expenes_client').val();

            if(from == '' && to == '' && category == '' && project == '' && client == ''){
                $('#expenes_project').focus();
                $('#expenes_project').css('border-color','#77A7DB');
                $('#expenes_client').css('border-color','#77A7DB');
                $('#expenses_date_from').css('border-color','#77A7DB');
                $('#expenses_date_to').css('border-color','#77A7DB');
                $('#expenes_client_error').removeClass('display-none').addClass('display-block');
                $('#expenes_project_error').removeClass('display-none').addClass('display-block');
                $('#expenses_date_from_error').removeClass('display-none').addClass('display-block');
                $('#expenses_category_error').removeClass('display-none').addClass('display-block');
                $('#expenses_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#expenes_project').css('border-color','#ccc');
                $('#expenes_client').css('border-color','#ccc');
                $('#expenses_date_from').css('border-color','#ccc');
                $('#expenses_date_to').css('border-color','#ccc');
                $('#expenes_client_error').removeClass('display-block').addClass('display-none');
                $('#expenes_project_error').removeClass('display-block').addClass('display-none');
                $('#expenses_date_from_error').removeClass('display-block').addClass('display-none');
                $('#expenses_category_error').removeClass('display-block').addClass('display-none');
                $('#expenses_date_to_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });


        $(document).on('click','#accountsCreateExpense',function(){

            $("#createExpenseForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    notes: {
                        required: true
                    },
                    expense_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: "Amount must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    category: {
                        required: "Category must not empty"
                    },
                    notes: {
                        required: "Notes must not empty"
                    },
                    expense_date: {
                        required: "Expense Date must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

          $(document).on('click','#accountsCreateRevenue',function(){
           
            $("#createRevenueForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true
                    },
                    
                    category: {
                        required: true
                    },
                    notes: {
                        required: true
                    },
                    revenue_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: "Amount must not empty"
                    },
                    
                    category: {
                        required: "Category must not empty"
                    },
                    notes: {
                        required: "Notes must not empty"
                    },
                    revenue_date: {
                        required: "Expense Date must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });
        $(document).on('click','#accountsEditExpense',function(){

            $("#editExpenseForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true
                    },
                   category: {
                        required: true
                    },
                    notes: {
                        required: true
                    },
                    expense_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: "Amount must not empty"
                    },
                    category: {
                        required: "Category must not empty"
                    },
                    notes: {
                        required: "Notes must not empty"
                    },
                    expense_date: {
                        required: "Expense Date must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#accountsEditRevenue',function(){

            $("#editRevenueForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    notes: {
                        required: true
                    },
                    expense_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: "Amount must not empty"
                    },
                    category: {
                        required: "Category must not empty"
                    },
                    notes: {
                        required: "Notes must not empty"
                    },
                    expense_date: {
                        required: "Expense Date must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Payments  Submodule  */

        $(document).on('click','#payment_edit_submit',function(){

            $("#paymentEditForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true,
                        number:true
                    },
                    notes: {
                        required: true,
                        maxlength: 120
                    },
                    payment_method: {
                        required: true
                    },
                    currency: {
                        required: true
                    },
                    payment_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: 'Amount is required'
                    },
                    notes: {
                        required: 'Notes is required',
                        maxlength: "Notes shouldn't exceed 120 characters"
                    },
                    payment_method: {
                        required: 'Please select a payment method'
                    },
                    currency: {
                        required: 'Please select a currency'
                    },
                    payment_date: {
                        required: 'Payment Date is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

         /* TaxRate  Submodule  */

         function isTaxPercentage() {
            var tax = $('#create_taxrate_percent').val();
            console.log(tax);
            if(tax != '')
            {
                $('#create_taxrate_required').removeClass('display-block').addClass('display-none');
                if(/^(\d*\.)?\d+$/.test(tax) && ( tax <= 100 || tax == 0))
                {
                    console.log(true);
                    $('#create_taxrate_error').removeClass('display-block').addClass('display-none');
                    $('#create_taxrate_required').removeClass('display-block').addClass('display-none');
                    return true;
                }
                else
                {
                    $('#create_taxrate_error').removeClass('display-none').addClass('display-block');
                    $('#create_taxrate_percent').focus();
                }
            }
            else{
                $('#create_taxrate_required').removeClass('display-none').addClass('display-block');
                $('#create_taxrate_error').removeClass('display-block').addClass('display-none');
                return false;
            }
        }

        $(document).on('keyup','#create_taxrate_percent',function(){
            isTaxPercentage();
        });

         $(document).on('click','#taxrate_add_submit',function(){

            if(isTaxPercentage() == true){
               
            }
            else{
                return false;
            }

            $("#taxrateAddForm").validate({
                ignore: [],
                rules: {
                    tax_rate_name: {
                        required: true
                    }
                },
                messages: {
                    tax_rate_name: {
                        required: 'Tax Name is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

           
        });

        function isTaxPercentageEdit() {
            var tax = $('#edit_taxrate_percent').val();
            console.log(tax);
            if(tax != '')
            {
                $('#edit_taxrate_required').removeClass('display-block').addClass('display-none');
                if(/^(\d*\.)?\d+$/.test(tax) && (parseInt(tax) <= 100 || tax == 0))
                {
                    $('#edit_taxrate_error').removeClass('display-block').addClass('display-none');
                    $('#edit_taxrate_required').removeClass('display-block').addClass('display-none');
                    return true;
                }
                else
                {
                    $('#edit_taxrate_error').removeClass('display-none').addClass('display-block');
                    $('#edit_taxrate_percent').focus();
                }
            }
            else{
                $('#edit_taxrate_required').removeClass('display-none').addClass('display-block');
                $('#edit_taxrate_error').removeClass('display-block').addClass('display-none');
                return false;
            }
        }

        $(document).on('keyup','#edit_taxrate_percent',function(){
            isTaxPercentageEdit();
        });

        $(document).on('click','#taxrate_edit_submit',function(){

            if(isTaxPercentageEdit() == true){
                
                $("#taxrateEditForm").validate({
                    ignore: [],
                    rules: {
                        tax_rate_name: {
                            required: true
                        }
                    },
                    messages: {
                        tax_rate_name: {
                            required: 'Tax Name is required'
                        }
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                    
                   });
               
            }
            else{
                return false;
            }

           
        });

        /* Items Submodule  */

        $(document).on('click','#items_add_task',function(){

            $("#itemsAddTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    estimate: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    estimate: {
                        required: "Estimate Hours must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#items_edit_task',function(){

            $("#itemsEditTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    estimate: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    estimate: {
                        required: "Estimate Hours must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#items_add_item',function(){

            $("#itemsAddItem").validate({
                ignore: [],
                rules: {
                    item_name: {
                        required: true
                    },
                    item_desc: {
                        required: true,
                        maxlength: 120
                    },
                    quantity: {
                        required: true,
                        number: true
                    },
                    unit_cost: {
                        required: true,
                        number: true
                    },
                    item_tax_rate:{
                        required: true
                    }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    item_desc: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    quantity: {
                        required: 'Quantity is required'
                    },
                    unit_cost: {
                        required: 'Unit cost is required'
                    },
                    item_tax_rate:{
                        required: 'Please select a tax rate'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#items_edit_item',function(){

            $("#itemsEditItem").validate({
                ignore: [],
                rules: {
                    item_name: {
                        required: true
                    },
                    item_desc: {
                        required: true,
                        maxlength: 120
                    },
                    quantity: {
                        required: true,
                        number: true
                    },
                    unit_cost: {
                        required: true,
                        number: true
                    },
                    item_tax_rate:{
                        required: true
                    }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    item_desc: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    quantity: {
                        required: 'Quantity is required'
                    },
                    unit_cost: {
                        required: 'Unit cost is required'
                    },
                    item_tax_rate:{
                        required: 'Please select a tax rate'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* Message Module */

        $(document).on('click','#message_send_email',function(){
            
            var username = $('#username_select').val();
            console.log(username);
            if(username == null || username == '')
            {
               $('#username_message_error').removeClass('display-none').addClass('display-block');
               $('#username_select').focus();
               return false;
            }
            else
            {
                $('#username_message_error').removeClass('display-block').addClass('display-none');
            }



            var textareaValue = $('.foeditor-send-message').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
               $('#send_message_error').removeClass('display-none').addClass('display-block');
               $('.note-editable').trigger('focus');
               return false;
            }
            else
            {
                $('#send_message_error').removeClass('display-block').addClass('display-none');
            }

            
            
        });

        $(document).on('click','#send_message_conversation',function(){
            
            var textareaValue = $('.foeditor-send-conversation').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
               $('#send_conversation_error').removeClass('display-none').addClass('display-block');
               $('.note-editable').trigger('focus');
               return false;
            }
            else
            {
                $('#send_conversation_error').removeClass('display-block').addClass('display-none');
            }

            
            
        });

        /* payroll  */

        $(document).on('click','#payroll_salary_edit',function(){

            $("#payrollSalaryEdit").validate({
                ignore: [],
                rules: {
                    user_salary_amount: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    user_salary_amount: {
                        required: "Salary amount must not empty",
                        number: 'Please enter a valid amount'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#payroll_create_payslip',function(){

            $("#payrollPaySlip").validate({
                ignore: [],
                rules: {
                    payslip_ded_others: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_fund: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_welfare: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_prof: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_leave: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_pf: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_esi: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_tds: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_others: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_medical_allowance: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_allowance: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_conveyance: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_hra: {
                        required: true,
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_da: {
                        required: true,
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_basic: {
                        required: true,
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_month: {
                        required: true
                    },
                    payslip_year: {
                        required: true
                    }
                },
                messages: {
                    payslip_ded_others: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_ded_fund: {
                        number: 'Please enter a valid fund'
                    },
                    payslip_ded_welfare: {
                        number: 'Please enter a valid welfare'
                    },
                    payslip_ded_prof: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_ded_leave: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_ded_pf: {
                        number: 'Please enter a valid PF'
                    },
                    payslip_ded_esi: {
                        number: 'Please enter a valid ESI'
                    },
                    payslip_ded_tds: {
                        number: 'Please enter a valid TDS'
                    },
                    payslip_others: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_medical_allowance: {
                        number: 'Please enter a valid allowance'
                    },
                    payslip_allowance: {
                        number: 'Please enter a valid allowance'
                    },
                    payslip_conveyance: {
                        number: 'Please enter a valid conveyance'
                    },
                    payslip_hra: {
                        number: 'Please enter a valid HRA'
                    },
                    payslip_da: {
                        number: 'Please enter a valid DA'
                    },
                    payslip_basic: {
                        number: 'Please enter a valid basic'
                    },
                    payslip_month: {
                        required: 'Please select a month'
                    },
                    payslip_year: {
                        required: 'Please select a year'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Tickets Module  */

        $(document).on('click','#ticket_search_btn',function(){

            var priority = $('#ticked_priority option:selected').val();
            var from = $('#ticket_from').val();
            var to = $('#ticket_to').val();
            var status = $('#ticket_status option:selected').val();
            var name = $('#employee_name').val();

            if(from == '' && to == '' && priority == '' && status == '' && name == ''){
                $('#employee_name').focus();
                $('#employee_name').css('border-color','#77A7DB');
                $('#ticket_to').css('border-color','#77A7DB');
                $('#ticket_from').css('border-color','#77A7DB');
                $('#employee_name_error').removeClass('display-none').addClass('display-block');
                $('#ticked_priority_error').removeClass('display-none').addClass('display-block');
                $('#ticket_from_error').removeClass('display-none').addClass('display-block');
                $('#ticket_to_error').removeClass('display-none').addClass('display-block');
                $('#ticket_status_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#employee_name').css('border-color','#ccc');
                $('#ticket_to').css('border-color','#ccc');
                $('#ticket_from').css('border-color','#ccc');
                $('#employee_name_error').removeClass('display-block').addClass('display-none');
                $('#ticked_priority_error').removeClass('display-block').addClass('display-none');
                $('#ticket_from_error').removeClass('display-block').addClass('display-none');
                $('#ticket_to_error').removeClass('display-block').addClass('display-none');
                $('#ticket_status_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });

        $(document).on('click','#ticket_select_dept',function(){

            $("#ticketSelectDept").validate({
                ignore: [],
                rules: {
                    dept: {
                        required: true
                    }
                },
                messages: {
                    dept: {
                        required: "Please select a department"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#tickets_create_ticket',function(){

            var textareaValue = $('.foeditor-ticket-message').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
                $('#addticket_message_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                return false;
            }
            else
            {
                $('#addticket_message_error').removeClass('display-block').addClass('display-none');
                                
            }
                
            $("#ticketCreateForm").validate({
                ignore: [],
                rules: {
                    dept: {
                        required: true
                    },
                    ticket_code: {
                        required: true
                    },
                    subject: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    }
                },
                messages: {
                    dept: {
                        required: "Please select a department"
                    },
                    ticket_code: {
                        required: 'Ticket code is required'
                    },
                    subject: {
                        required: 'Subject must not empty'
                    },
                    reporter: {
                        required: 'Please select a reporter'
                    },
                    priority: {
                        required: 'Please select a priority'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#tickets_edit_ticket',function(){

            var textareaValue = $('.foeditor-ticket-messageU').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
                $('#editTicket_message_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                return false;
            }
            else
            {
                $('#editTicket_message_error').removeClass('display-block').addClass('display-none');
                                
            }
                
            $("#ticketEditForm").validate({
                ignore: [],
                rules: {
                    department: {
                        required: true
                    },
                    ticket_code: {
                        required: true
                    },
                    subject: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    }
                },
                messages: {
                    department: {
                        required: "Please select a department"
                    },
                    ticket_code: {
                        required: 'Ticket code is required'
                    },
                    subject: {
                        required: 'Subject must not empty'
                    },
                    reporter: {
                        required: 'Please select a reporter'
                    },
                    priority: {
                        required: 'Please select a priority'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on("click","#reply_ticket_btn",function() {  
            
           
            var textareaValue = $('.foeditor').summernote('code');
            //alert(textareaValue);
            if($.trim(textareaValue) !='')
            {
                //alert(1);
                $('#editTicket_message_error').removeClass('display-block').addClass('display-none');
            }
            else
            {
                //alert(3);
                $('#editTicket_message_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                return false;
                
                                
            }
            
        });



        /* Users Module  */

        $(document).on('click','#users_search_btn',function(){

            var role = $('#user_role option:selected').val();
            var client = $('#company option:selected').val();
            var name = $('#username').val();

            if(role == '' && client == '' && name == ''){
                $('#username').focus();
                $('#username').css('border-color','#77A7DB');
                $('#user_role_error').removeClass('display-none').addClass('display-block');
                $('#company_error').removeClass('display-none').addClass('display-block');
                $('#username_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#username').css('border-color','#ccc');
                $('#user_role_error').removeClass('display-block').addClass('display-none');
                $('#company_error').removeClass('display-block').addClass('display-none');
                $('#username_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });

        $(document).on('click','#user_add_new',function(){
    
            $.validator.addMethod("phonevalidation",
                function(value, element) {
                    return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                      
                },
            "Please enter a valid phone number."
            );
    
            $.validator.addMethod("emailvalidation",
                    function(value, element) {
                            return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                    },
            "Please enter a valid email address."
            );
    
            $("#addNewUser").validate({
                onsubmit: true,
                ignore: [] ,
                rules: {
                    fullname: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                        required: true,
                        emailvalidation: 'emailvalidation'
                    },
                    phone: {
                        required: true,
                        phonevalidation: 'phonevalidation'
                    },
                    password: {
                        required: true,
                        minlength : 6
                    },
                    confirm_password: {
                        required: true,
                        minlength : 6,
					    equalTo : "#password"
                    },
                    company:{
                        required: true
                    },
                    role: {
                        required:true
                    }
                },
                messages: {
                    fullname: {
                        required: "Name must not be empty"
                    },
                    username: {
                        required: "Username is required"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    phone: {
                        required: 'Phone number is required',
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    },
                    password: {
                        required: "Password is required"
                    },
                    confirm_password: {
                        required: "Password is required",
					    equalTo : "Passwords are mismatched"
                    },
                    company:{
                        required: "Please select a company"
                    },
                    role: {
                        required:"Please select a role"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            });
    
            $(document).on('click','#update_exist_user',function(){

                console.log('update');
    
                $.validator.addMethod("phonevalidation",
                    function(value, element) {
                        return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                          
                    },
                "Please enter a valid phone number."
                );
        
                $.validator.addMethod("mobilevalidation",
                function(value, element) {
                        return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
                },
                "Please enter a valid mobile number."
                );

                $("#editExistUser").validate({
                    onsubmit: true,
                    ignore: [] ,
                    rules: {
                        fullname: {
                            required: true
                        },
                        phone: {
                            required: true,
                            phonevalidation: 'phonevalidation'
                        },
                        company:{
                            required: true
                        },
                        hourly_rate: {
                            required: true,
                            number: true
                        },
                        mobile: {
                            required: true,
                            mobilevalidation: 'mobilevalidation'
                        }
                    },
                    messages: {
                        fullname: {
                            required: "Name must not be empty"
                        },
                        phone: {
                            required: 'Phone number is required',
                            minlength: "Minimum Length Should be 7 digit",
                            maxlength: "Maximum Length Should be 15 digit",
                            phonevalidation: "Entered Number is Invalid"
                        },
                        mobile: {
                            required: 'Mobile number is required',
                            mobilevalidation: "Entered Number is Invalid"
                        },
                        company:{
                            required: "Please select a company"
                        },
                        hourly_rate: {
                            required:"Please enter the rate"
                        }
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                    
                   });
        });

        $(document).on('click','#paypal_subscription',function(){

            $("#paypal_form").validate({
                ignore: [],
                rules: {
                    user_count: {
                        required: true,
                        number: true
                    },
                    sub_amount: {
                        required: true,
                        number: true
                    },
                    status: {
                        required:true
                    }
                },
                messages: {
                    user_count: {
                        required: "Please enter the count"
                    },
                    sub_amount: {
                        required: "Please enter the amount"
                    },
                    status: {
                        required:"Please select the payment type"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* settings module */

        $(document).on('click','#general_settings_save',function(){

            $.validator.addMethod("phonevalidation",
                    function(value, element) {
                        // return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                        return /^[0-9 ()+]+$/.test(value);   
                    },
                "Please enter a valid phone number."
            );

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            jQuery.validator.addMethod("zipcode", 
            function(value, element) {
                return this.optional(element) || /\d{4}$|^\d{6}$/.test(value)
              }, 
            "Please provide a valid zipcode.");

            console.log($('#general_settings_fax').val())

            $("#settingsGeneralForm").validate({
                ignore: [],
                rules: {
                    company_name: {
                        required: true
                    },
                    company_legal_name: {
                        required : true
                    },
                    contact_person: {
                        required: true
                    },
                    company_address: {
                        required: true
                    },
                    company_zip_code: {
                        required: true,
                        number: true,
                        zipcode: true,
                        maxlength:6,
                    },
                    company_city : {
                        required: true
                    },
                    company_state: {
                        required: true
                    },
                    company_country: {
                        required: true
                    },
                    company_email: {
                        required: true,
                        emailvalidation:'emailvalidation'
                    },
                    company_phone: {
                        required: true,
                        phonevalidation:'phonevalidation'
                    },
                    company_phone_2 : {
                        phonevalidation:'phonevalidation'
                        // number: {
                        //     param:true,
                        //     depends: $('#general_settings_phone2').val().length > 0
                        // },
                        // max: {
                        //     param:12,
                        //     depends: $('#general_settings_phone2').val().length > 0
                        // },
                        // min: {
                        //     param:10,
                        //     depends: $('#general_settings_phone2').val().length > 0
                        // }
                    },
                    company_mobile: {
                        required: true,
                        phonevalidation:'phonevalidation'
                    },
                    company_fax: {
                        number: {
                            param:true,
                            depends: $('#general_settings_fax').val().length > 0
                        },
                        max: {
                            param:12,
                            depends: $('#general_settings_fax').val().length > 0
                        },
                        min: {
                            param:10,
                            depends: $('#general_settings_fax').val().length > 0
                        }
                    },
                    company_vat: {
                        number: {
                            param:true,
                            depends: $('#general_settings_vat').val() != ''
                        },
                        max:{
                            param:100,
                            depends: $('#general_settings_vat').val() != ''
                        },
                        min:{
                            param:0,
                            depends: $('#general_settings_vat').val() != ''
                        }
                    },
                    company_domain: {
                        url:{
                            param:true,
                            depends: $('#general_settings_domain').val() != ''
                        }
                    }
                },
                messages: {
                    company_name: {
                        required: "Company name is required"
                    },
                    company_legal_name :{
                        required : "Company legal name is required"
                    },
                    contact_person:{
                        required: 'Contact person is required'
                    },
                    company_address: {
                        required: 'Address is required'
                    },
                    company_zip_code: {
                        required: 'Zipcode is required',
                        maxlength: "Please provide a valid zipcode."
                    },
                    company_city : {
                        required: 'City is required'
                    },
                    company_state: {
                        required: 'State is required'
                    },
                    company_country: {
                        required: 'Please select a country'
                    },
                    company_email: {
                        required: 'Email is required'
                    },
                    company_phone: {
                        required: 'Phone is required'
                    },
                    company_phone_2 : {
                        //required: true
                    },
                    company_mobile: {
                        required: 'Mobile is required'
                    },
                    company_fax: {
                        phonevalidation: 'Please enter a valid fax number'
                    },
                    company_domain: {
                        
                    },
                    company_vat: {
                        max:'Vat should less than 100',
                        min:'Vat should greater than 0'
                    }

                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#system_settings_save',function(){

            $("#settingsSystemForm").validate({
                ignore: [],
                rules: {
                    timezone: {
                        required: true
                    },
                    default_currency: {
                        required : true
                    },
                    default_currency_symbol: {
                        required: true
                    },
                    currency_position: {
                        required: true
                    },
                    currency_decimals: {
                        required: true
                    },
                    decimal_separator : {
                        required: true
                    },
                    thousand_separator: {
                        required: true
                    },
                    // default_tax: {
                    //     number: {
                    //         param:true,
                    //         depends: $('#system_settings_tax1').val().length > 0
                    //     },
                    //     max: {
                    //         param:12,
                    //         depends: $('#system_settings_tax1').val().length > 0
                    //     },
                    //     min: {
                    //         param:10,
                    //         depends: $('#system_settings_tax1').val().length > 0
                    //     }
                    // },
                    // default_tax2: {
                    //     number: {
                    //         param:true,
                    //         depends: $('#system_settings_tax2').val().length > 0
                    //     },
                    //     max: {
                    //         param:12,
                    //         depends: $('#system_settings_tax2').val().length > 0
                    //     },
                    //     min: {
                    //         param:10,
                    //         depends: $('#system_settings_tax2').val().length > 0
                    //     }
                    // },
                    tax_decimals: {
                        required: true
                    },
                    quantity_decimals : {
                        required: true
                    },
                    date_format: {
                        required: true
                    },
                    enable_languages: {
                        required: true
                    },
                    use_gravatar: {
                        required: true
                    },
                    allow_client_registration: {
                        required: true
                    },
                    file_max_size: {
                        required: true,
                        number:true
                    },
                    allowed_files: {
                        required: true
                    },
                    client_create_project: {
                        required: true
                    },
                    auto_close_ticket: {
                        required: true,
                        number:true
                    },
                    ticket_start_no: {
                        required: true,
                        number:true
                    },
                    ticket_default_department: {
                        required: true
                    }
                },
                messages: {
                    timezone: {
                        required: 'Timezone is required'
                    },
                    default_currency: {
                        required : 'Currency is required'
                    },
                    default_currency_symbol: {
                        required: 'Currency symbol is required'
                    },
                    currency_position: {
                        required: 'Currency position is required'
                    },
                    currency_decimals: {
                        required: 'Please select a decimal'
                    },
                    decimal_separator : {
                        required: 'Decimal seperator is required'
                    },
                    thousand_separator: {
                        required: 'Thousand seperator is required'
                    },
                    // default_tax: {
                    //     //required: true
                    // },
                    // default_tax2: {
                    //     //required: true
                    // },
                    tax_decimals: {
                        required: 'Please select a decimal'
                    },
                    quantity_decimals : {
                        required: 'Please select a decimal'
                    },
                    date_format: {
                        required: 'Please select a date format'
                    },
                    file_max_size: {
                        required: 'Please enter file size'
                    },
                    allowed_files: {
                        required: 'Allowed files is required'
                    },
                    auto_close_ticket: {
                        required: 'Close ticket is required'
                    },
                    ticket_start_no: {
                        required: 'Start number is required'
                    },
                    ticket_default_department: {
                        required: 'Please select a department'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#settings_add_category',function(){

            $("#settingsAddCategory").validate({
                ignore: [],
                rules: {
                    cat_name: {
                        required: true
                    },
                    module: {
                        required : true
                    }
                },
                messages: {
                    cat_name: {
                        required: 'Category name is required'
                    },
                    module: {
                        required : 'Module is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_edit_category',function(){

            $("#settingsEditCategory").validate({
                ignore: [],
                rules: {
                    cat_name: {
                        required: true
                    },
                    module: {
                        required : true
                    }
                },
                messages: {
                    cat_name: {
                        required: 'Category name is required'
                    },
                    module: {
                        required : 'Module is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#settings_system_slack',function(){

            $("#settingsSystemSlack").validate({
                ignore: [],
                rules: {
                    slack_username: {
                        required: true
                    },
                    slack_channel: {
                        required : true
                    },
                    slack_webhook: {
                        required: true,
                        url:true
                    }
                },
                messages: {
                    slack_username: {
                        required: 'Username is required'
                    },
                    slack_channel: {
                        required : 'Channel is required'
                    },
                    slack_webhook: {
                        required: 'Webhook is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_email_submit',function(){

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            function alternates() {
                return $('#use_alternate_emails').prop("checked") == true; // Has at least one letter
            }
            

            console.log($('#use_alternate_emails').prop("checked"))
            console.log($('#use_alternate_emails').val());

            $("#settingsEmailForm").validate({
                ignore: [],
                rules: {
                    company_email: {
                        required: true,
                        emailvalidation:'emailvalidation'
                    },
                    billing_email: {
                        required: {
                            param:true,
                            depends: alternates
                        },
                        emailvalidation: {
                            param:'emailvalidation',
                            depends: alternates
                        }
                    },
                    billing_email_name: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    support_email: {
                        required: {
                            param:true,
                            depends: alternates
                        },
                        emailvalidation: {
                            param:'emailvalidation',
                            depends: alternates
                        }
                    },
                    support_email_name: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    protocol : {
                        required: true
                    },
                    smtp_host: {
                        required: true
                    },
                    smtp_user: {
                        required: true
                    },
                    smtp_pass: {
                        required: true
                    },
                    smtp_port: {
                        required: true
                    }
                },
                messages: {
                    company_email: {
                        required: 'Email is required'
                    },
                    billing_email: {
                        required : 'Billing email is required'
                    },
                    billing_email_name: {
                        required: 'Billing name is required'
                    },
                    support_email: {
                        required: 'Support email is required'
                    },
                    support_email_name: {
                        required: 'Support name is required'
                    },
                    protocol : {
                        required: 'Email protocol is required'
                    },
                    smtp_host: {
                        required: 'SMTP HOST is required'
                    },
                    smtp_user: {
                        required: 'SMTP USER is required'
                    },
                    smtp_pass: {
                        required: 'SMTP PASSWORD is required'
                    },
                    smtp_port: {
                        required: 'SMTP PORT is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_email_piping',function(){

            console.log($('#check_mail_imap').val());

            function alternates() {
                return $('#check_mail_imap').prop("checked") == true; // Has at least one letter
            }

            $("#settingsEmailPipes").validate({
                ignore: [],
                rules: {
                    mail_imap_host: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_username: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_password: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_port: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_flags: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    }
                },
                messages: {
                    mail_imap_host: {
                        required: 'IMAP HOST is required'
                    },
                    mail_username: {
                        required : 'IMAP username is required'
                    },
                    mail_password: {
                       required: 'IMAP password is required'
                    },
                    mail_port: {
                        required: 'Mail port is required'
                    },
                    mail_flags: {
                        required: 'Mail flag is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#lead_reporter_add',function(){

            // var user_email = $('#reporter_email').val();
                    // $.post(base_url+'settings/check_reporter_mail/',{user_email:user_email},function(res){
                    //     // alert(res); return false;
                    //     if(res == 'exists'){
                    //         $('#reporter_email_error_exist').css('display','');
                    //         return false;
                    //     }
                    // });
                    // return false;

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            $("#leadReporterAdd").validate({
                ignore: [],
                rules: {
                    reporter_name: {
                        required:true
                    },
                    reporter_email: {
                        required:true,
                        emailvalidation:'emailvalidation',

                    }
                },
                messages: {
                    reporter_name: {
                        required: 'Reporter name is required'
                    },
                    reporter_email: {
                        required : 'Reporter email is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#lead_reporter_edit',function(){

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            $("#leadReporterEdit").validate({
                ignore: [],
                rules: {
                    reporter_name: {
                        required:true
                    },
                    reporter_email: {
                        required:true,
                        emailvalidation:'emailvalidation'
                    }
                },
                messages: {
                    reporter_name: {
                        required: 'Reporter name is required'
                    },
                    reporter_email: {
                        required : 'Reporter email is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_payment_submit',function(){

            $.validator.addMethod("emailvalidation",
            function(value, element) {
                    return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
            },
        "Please enter a valid email address."
        );

            $("#settingsPaymentForm").validate({
                ignore: [],
                rules: {
                    paypal_email: {
                        required: true,
                        emailvalidation:'emailvalidation'
                    },
                    stripe_private_key: {
                        required : true
                    },
                    stripe_public_key: {
                       required: true
                    }
                },
                messages: {
                    paypal_email: {
                        required: 'Paypal email is required'
                    },
                    stripe_private_key: {
                        required : 'Stripe private key is required'
                    },
                    stripe_public_key: {
                       required: 'Stripe public key is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_invoice_submit',function(){

            $("#settingsInvoiceForm").validate({
                ignore: [],
                rules: {
                    invoice_color: {
                        required: true
                    },
                    invoice_prefix: {
                        required : true
                    },
                    invoices_due_after: {
                       required: true,
                       number:true,
                       min:0
                    },
                    invoice_start_no: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    invoice_color: {
                        required: 'Invoice color is required'
                    },
                    invoice_prefix: {
                        required : 'Invoice prefix is required'
                    },
                    invoices_due_after: {
                       required: 'Invoice due is required'
                    },
                    invoice_start_no: {
                        required: 'Invoice start number is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#settings_estimates_submit',function(){

            $("#settingsEstimatesForm").validate({
                ignore: [],
                rules: {
                    estimate_color: {
                        required: true
                    },
                    estimate_prefix: {
                        required : true
                    },
                    estimate_start_no: {
                        required: true,
                        number:0
                    }
                },
                messages: {
                    estimate_color: {
                        required: 'Estimate color is required'
                    },
                    estimate_prefix: {
                        required : 'Estimate prefix is required'
                    },
                    estimate_start_no: {
                        required: 'Estimate start number is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_theme_submit',function(){

            $("#settingsThemeForm").validate({
                ignore: [],
                rules: {
                    website_name: {
                        required: true
                    },
                    login_title: {
                        required : true
                    }
                },
                messages: {
                    website_name: {
                        required: 'Sitename is required'
                    },
                    login_title: {
                        required : 'Login title is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_cron_submit',function(){

            $("#settingsCronForm").validate({
                ignore: [],
                rules: {
                    cron_key: {
                        required: true
                    }
                },
                messages: {
                    cron_key: {
                        required: 'Key is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_department_submit',function(){

            $("#settingsDepartmentForm").validate({
                ignore: [],
                rules: {
                    deptname: {
                        required: true
                    }
                },
                messages: {
                    deptname: {
                        required: 'Department is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_designation_submit',function(){

            $("#settingsDesignationForm").validate({
                ignore: [],
                rules: {
                    department_id: {
                        required: true
                    },
                    designation: {
                        required : true
                    }
                },
                messages: {
                    department_id: {
                        required: 'Department is required'
                    },
                    designation: {
                        required : 'Designation is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#add_leave_type',function(){

            $("#addLeaveType").validate({
                ignore: [],
                rules: {
                    leave_type: {
                        required: true
                    },
                    leave_days: {
                        required : true
                    }
                },
                messages: {
                    leave_type: {
                        required: 'Leave type is required'
                    },
                    leave_days: {
                        required : 'Leave days is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#ha_dra_submit',function(){

            $("#HaDraForm").validate({
                ignore: [],
                rules: {
                    salary_da: {
                        required: true,
                        max:55,
                        min:0
                    },
                    salary_hra: {
                        required : true,
                        max:25,
                        min:0
                    }
                },
                messages: {
                    salary_da: {
                        required: 'DA is required'
                    },
                    salary_hra: {
                        required : 'HRA is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });



        /* Activity Module  */

        $(document).on('click','#activity_search',function(){

            var from = $('#ser_activity_date_from').val();
            var to = $('#ser_activity_date_to').val();
            
            if(from == '' && to == ''){
                $('#ser_activity_date_from').focus();
                $('#ser_activity_date_from').css('border-color','#77A7DB');
                $('#ser_activity_date_to').css('border-color','#77A7DB');
                $('#ser_activity_date_from_error').removeClass('display-none').addClass('display-block');
                $('#ser_activity_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#ser_activity_date_from').css('border-color','#ccc');
                $('#ser_activity_date_to').css('border-color','#ccc');
                $('#ser_activity_date_from_error').removeClass('display-block').addClass('display-none');
                $('#ser_activity_date_to_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });


        /* Timesheets Module  */

        $(document).on('click','#timesheet_search_btn',function(){

            console.log('comes');

            var from = $('#timesheet_date_from').val();
            var to = $('#timesheet_date_to').val();
            var id = $('#employee_id option:selected').val();
            
            if(from == '' && to == '' && id == ''){
                $('#timesheet_date_from').focus();
                $('#timesheet_date_from').css('border-color','#77A7DB');
                $('#timesheet_date_to').css('border-color','#77A7DB');
                $('#timesheet_date_from_error').removeClass('display-none').addClass('display-block');
                $('#employee_id_error').removeClass('display-none').addClass('display-block');
                $('#timesheet_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#timesheet_date_to').css('border-color','#ccc');
                $('#timesheet_date_from').css('border-color','#ccc');
                $('#timesheet_date_from_error').removeClass('display-block').addClass('display-none');
                $('#timesheet_date_to_error').removeClass('display-block').addClass('display-none');
                $('#employee_id_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });        

        $(document).on('click','#timesheet_edit_submit',function(){
            console.log('hrms')
            $("#edit_timesheet").validate({
               rules: {
                    project_name: {
                        required: true
                    },
                    timeline_date: {
                        required: true
                    },
                    timeline_desc: {
                        required: true,
                        maxlength:120
                    },
                    timeline_hours: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    project_name: {
                        required: "Project name must not empty"
                    },
                    timeline_date: {
                        required: "Date is required"
                    },
                    timeline_hours: {
                        required: "Hours field is required"
                    },
                    timeline_desc: {
                        required: "Description must not empty"
                    }
                },
                submitHandler: function(form) {
                    console.log(form);
                    //form.submit();
                }
                
               });
        });



                       
});

$(document).ready(function(){
      $('.choosetype').click(function(){
        // alert($('.ChooseType:checked').val());
        var val_leave = $(this).attr('data-type');
        if(val_leave == 'personal'){
            $('#Personal_leaves').css('display','');
            $('#team_leaves').css('display','none');
             $(this).addClass('active');
            $('.team').removeClass('active');
        }
        if(val_leave == 'team'){
            $('#Personal_leaves').css('display','none');
            $('#team_leaves').css('display','');
             $(this).addClass('active');
            $('.personal').removeClass('active');
        }
     });
     $('.choosetype').click(function(){
        // alert($(this).attr('data-type'));
        var val_expense = $(this).attr('data-type');
        if(val_expense == 'personal'){
            $('#personal_expense').css('display','');
            $('#team_expense').css('display','none');
            $(this).addClass('active');
            $('.team').removeClass('active');
        }
        if(val_expense == 'team'){
            $('#personal_expense').css('display','none');
            $('#team_expense').css('display','');
            $(this).addClass('active');
            $('.personal').removeClass('active');
        }
     });
});

function check_email_reporter()
{
    var u_email = $('#reporter_email').val();
    if(u_email != '')
    {
         $.post(base_url+'settings/check_reporter_mail/',{user_email:u_email},function(res){
                        if(res == 'new'){
                            $('#reporter_email_error_exist').css('display','');
                            $('#lead_reporter_add').attr('disabled','disabled')
                            return false;
                        }else{
                            $('#reporter_email_error_exist').css('display','none');
                            $('#lead_reporter_add').removeAttr('disabled','')
                        }
                    });
    }

}

$('#Add_more_institution').click(function() {
    var add_div = '<div class="card-box MultipleInstitutions"><h3 class="card-title">Education Informations</h3> <a href="#" class="btn btn-sm btn-danger remove_div"><i class="fa fa-trash-o"></i></a><div class="row"><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" value="" class="form-control floating" name="institute[]"><label class="control-label">Institution</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" class="form-control floating" name="subject[]"><label class="control-label">Subject</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><div class="cal-icon"><input type="text" name="start_date[]" class="form-control floating datetimepicker"></div><label class="control-label">Starting Date</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><div class="cal-icon"><input type="text" name="end_date[]" class="form-control floating datetimepicker"></div><label class="control-label">Complete Date</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" name="degree[]" class="form-control floating"><label class="control-label">Degree</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" name="grade[]" class="form-control floating"><label class="control-label">Grade</label></div></div></div></div>';
    $('.MultipleInstitutions:last').after(add_div);
});
$('.AllInstitute').on('click','.remove_div',function() {
    $(this).parent().remove();
});

$('#Add_experience').click(function() {
    var add_div = '<hr><div class="MultipleExperience"><h3 class="card-title">Experience Informations </h3> <a href="#" class="btn bnt-sm btn-danger remove_exp_div"><i class="fa fa-trash-o"></i></a><div class="row"><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" value="" name="company_name[]"><label class="control-label">Company Name</label></div></div><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" value="" name="location[]"><label class="control-label">Location</label></div></div><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" value="" name="job_position[]"><label class="control-label">Job Position</label></div></div><div class="col-md-6"><div class="form-group form-focus"><div class="cal-icon"><input type="text" class="form-control floating datetimepicker" value="" name="period_from[]"></div><label class="control-label">Period From</label></div></div><div class="col-md-6"><div class="form-group form-focus"><div class="cal-icon"><input type="text" class="form-control floating datetimepicker" value="" name="period_to[]"></div><label class="control-label">Period To</label></div></div></div></div>';
    $('.MultipleExperience:last').after(add_div);
});
$('.AllExperience').on('click','.remove_exp_div',function() {
    $(this).parent().remove();
});


$('#add_more_family').click(function() {
    var add_div = '<div class="FamilyMembers"><h3 class="card-title">Family Member </h3> <a href="#" class="remove_family_div btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a><div class="row"><div class="col-md-6"><div class="form-group"><label>Name <span class="text-danger">*</span></label><input class="form-control" type="text" name="member_name[]" placeholder="Name"></div></div><div class="col-md-6"><div class="form-group"><label>Relationship <span class="text-danger">*</span></label><input class="form-control" type="text" name="member_relationship[]" placeholder ="Relationship"></div></div><div class="col-md-6"><div class="form-group"><label>Date of birth <span class="text-danger">*</span></label><input class="form-control ALlmembers" type="text" name="member_dob[]"></div></div><div class="col-md-6"><div class="form-group"><label>Phone <span class="text-danger">*</span></label><input class="form-control" type="text" name="member_phone[]" placeholder="Phone Number"></div></div></div></div>';
    $('.FamilyMembers:last').after(add_div);
});
$('.AllFamilyMembers').on('click','.remove_family_div',function() {
    $(this).parent().remove();
});






$('#tokbox_set_btn').click(function(){
    var apikey = $('#apikey_tokbox').val();
    var apikeysecret = $('#apisecret_tokbox').val();
    if(apikey == '')
    {
        toastr.error('Apikey Field is Required');
        return false;
    }
    if(apikeysecret == '')
    {
        toastr.error('ApikeySecret Field is Required');
        return false;
    }
});

$('#offer_approval_set_btn').click(function(){
    var offer_approvers = $('.offer_approvers').val();
    var offer_approvers_sim = $('.offer_approvers_sim').val();
    var default_offer_approval = $('input[name=default_offer_approval]:checked').val();

    if(default_offer_approval == '')
    {
        
        toastr.error('Default Offer Approval Field is Required');
        return false;
    } else {
        if(default_offer_approval == 'seq-approver'){
            if(offer_approvers == '')
            {
                
                toastr.error('Offer Approvers Field is Required');
                return false;
            }
        }else{
            if(offer_approvers_sim == '')
            {
                
                toastr.error('Offer Approvers Field is Required');
                return false;
            }
        }

    }

    
});


// $('#expense_approval_set_btn').click(function(){
//     var expense_approvers_dept = $('.expense_approvers_dept').val();
//     var expense_approvers = $('.expense_approvers').val();
//     var expense_approvers_sim = $('.expense_approvers_sim').val();
//     var default_expense_approval = $('input[name=default_expense_approval]:checked').val();

//     // if(default_expense_approval == '' || default_expense_approval == undefined)
//     // {
        
//     //     toastr.error('Default Expense Approval Field is Required');
//     //     return false;
//     // } else {
//         // if(default_expense_approval == 'seq-approver'){
//              if(expense_approvers_dept == '')
//             {
                
//                 toastr.error('Department Field is Required');
//                 return false;
//             }

//             if(expense_approvers == '' || expense_approvers == null )
//             {
                
//                 toastr.error('Expense Approvers Field is Required');
//                 return false;
//             }
//         // }else{
//         //     if(expense_approvers_sim == '')
//         //     {
                
//         //         toastr.error('Expense Approvers Field is Required');
//         //         return false;
//         //     }
//         // }

//     // }

    
// });
// $('#leave_approval_set_btn').click(function(){
//     var leave_approvers_dept = $('.leave_approvers_dept').val();
//     var leave_approvers = $('.leave_approvers').val();  
//     var leave_approvers_sim = $('.leave_approvers_sim').val();
//     var default_leave_approval = $('input[name=default_leave_approval]:checked').val();
    
//     // if(default_leave_approval == '' || default_leave_approval == undefined)
//     // {
        
//     //     toastr.error('Default Leave Approval Field is Required');
//     //     return false;
//     // } else {
//         // if(default_leave_approval == 'seq-approver'){
//             if(leave_approvers_dept == '')
//             {
                
//                 toastr.error('Department Field is Required');
//                 return false;
//             }
//             if(leave_approvers == '' || leave_approvers == null )
//             {
                
//                 toastr.error('Leave Approvers Field is Required');
//                 return false;
//             }
            
//         // }else{
//         //     if(leave_approvers_sim == '')
//         //     {
                
//         //         toastr.error('Leave Approvers Field is Required');
//         //         return false;
//         //     }
//         // }

//     // }

    
// });


$('#expense_approval_set_btn').click(function(){
    var expense_approvers = $('.expense_approvers').val();
    var expense_approvers_sim = $('.expense_approvers_sim').val();
    var default_expense_approval = $('input[name=default_expense_approval]:checked').val();

    if(default_expense_approval == '')
    {
        
        toastr.error('Default Expense Approval Field is Required');
        return false;
    } else {
        if(default_expense_approval == 'seq-approver'){
            if(expense_approvers == '')
            {
                
                toastr.error('Expense Approvers Field is Required');
                return false;
            }
        }else{
            if(expense_approvers_sim == '')
            {
                
                toastr.error('Expense Approvers Field is Required');
                return false;
            }
        }

    }

    
});
$('#leave_approval_set_btn').click(function(){
    var leave_approvers = $('.leave_approvers').val();
    var leave_approvers_sim = $('.leave_approvers_sim').val();
    var default_leave_approval = $('input[name=default_leave_approval]:checked').val();

    if(default_leave_approval == '')
    {
        
        toastr.error('Default Leave Approval Field is Required');
        return false;
    } else {
        if(default_leave_approval == 'seq-approver'){
            if(leave_approvers == '')
            {
                
                toastr.error('Leave Approvers Field is Required');
                return false;
            }
        }else{
            if(leave_approvers_sim == '')
            {
                
                toastr.error('Leave Approvers Field is Required');
                return false;
            }
        }

    }

    
});

$('#resignation_notice_set_btn').click(function(){
    var email_notification = $('.email_notification').val();
    var notice_days = $('.notice_days').val();

    if(email_notification == '')
    {        
        toastr.error('Please select the notification recievers');
        return false;
    }else if(notice_days ==''){
        toastr.error('Notice days Field is Required');
        return false;
    }

    
});

 $('#check_candidate').change(function(){
        var candidate = $(this).val();
        $.post(base_url+'offers/check_candidate/',{candidate:candidate},function(res){
                if(res == 'yes'){
                    $('#already_candidate').css('display','');
                    $('#create_offers_submit').attr('disabled','disabled');
                }else{
                    $('#already_candidate').css('display','none');
                    $('#create_offers_submit').removeAttr('disabled');

                }
        });
    });





$(document).ready(function(){
     $('.TaskType').change(function(){
        // alert($('.ChooseType:checked').val());
        var val_leave = $('.TaskType:checked').val();
        if(val_leave == 'all'){
            $('.AllTasks').css('display','');
            $('.PendingTasks').css('display','none');
            $('.CompleteTasks').css('display','none');
            $('.MyTasks').css('display','none');
        }
        if(val_leave == 'pending'){
            $('.PendingTasks').css('display','');
            $('.AllTasks').css('display','none');
            $('.CompleteTasks').css('display','none');
            $('.MyTasks').css('display','none');
        }
        if(val_leave == 'complete'){
            $('.CompleteTasks').css('display','');
            $('.AllTasks').css('display','none');
            $('.PendingTasks').css('display','none');
            $('.MyTasks').css('display','none');
        }
        if(val_leave == 'my_task'){
            $('.MyTasks').css('display','');
            $('.CompleteTasks').css('display','none');
            $('.AllTasks').css('display','none');
            $('.PendingTasks').css('display','none');
        }
     });
});

$(document).ready(function(){
    $('#purchase_date').datepicker();
    $('#dob_edit').datepicker();
    $('#passport_expiry').datepicker();
    $('#warranty_date').datepicker();
    $("#add_asset_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                asset_name: {
                    required: true
                },
                reference_id: {
                    required: true
                },
                purchase_date: {
                    required: true
                },
                purchase_from: {
                    required: true
                },
                manufacture: {
                    required: true
                },
                model: {
                    required: true
                },
                serial_number: {
                    required: true
                },
                supplier: {
                    required: true
                },
                asset_condition: {
                    required: true
                },
                warranty_date: {
                    required: true
                },
                assets_value: {
                    required: true
                },
                asset_user: {
                    required: true
                },
                description: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                asset_name: {
                    required: "Asset Name must not be empty"
                },
                reference_id: {
                    required: "Reference ID must not be empty"
                },
                purchase_date: {
                    required: "Purchase Date must not be empty"
                },
                purchase_from: {
                    required: "Purchase from must not be empty"
                },
                manufacture: {
                    required: "Manufacture must not be empty"
                },
                model: {
                    required: "Model must not be empty"
                },
                serial_number: {
                    required: "Serial Number must not be empty"
                },
                supplier: {
                    required: "Supplier must not be empty"
                },
                asset_condition: {
                    required: "Condition must not be empty"
                },
                warranty_date: {
                    required: "Warranty Date must not be empty"
                },
                assets_value: {
                    required: "Asset Value must not be empty"
                },
                asset_user: {
                    required: "Please choose any one User"
                },
                description: {
                    required: "Description must not be empty"
                },
                status: {
                    required: "Status must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#edit_assets_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                asset_name: {
                    required: true
                },
                reference_id: {
                    required: true
                },
                purchase_date: {
                    required: true
                },
                purchase_from: {
                    required: true
                },
                manufacture: {
                    required: true
                },
                model: {
                    required: true
                },
                serial_number: {
                    required: true
                },
                supplier: {
                    required: true
                },
                asset_condition: {
                    required: true
                },
                warranty_date: {
                    required: true
                },
                assets_value: {
                    required: true
                },
                asset_user: {
                    required: true
                },
                description: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                asset_name: {
                    required: "Asset Name must not be empty"
                },
                reference_id: {
                    required: "Reference ID must not be empty"
                },
                purchase_date: {
                    required: "Purchase Date must not be empty"
                },
                purchase_from: {
                    required: "Purchase from must not be empty"
                },
                manufacture: {
                    required: "Manufacture must not be empty"
                },
                model: {
                    required: "Model must not be empty"
                },
                serial_number: {
                    required: "Serial Number must not be empty"
                },
                supplier: {
                    required: "Supplier must not be empty"
                },
                asset_condition: {
                    required: "Condition must not be empty"
                },
                warranty_date: {
                    required: "Warranty Date must not be empty"
                },
                assets_value: {
                    required: "Asset Value must not be empty"
                },
                asset_user: {
                    required: "Please choose any one User"
                },
                description: {
                    required: "Description must not be empty"
                },
                status: {
                    required: "Status must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });

		$("#add_overtimes").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                ot_description: {
                    required: true
                },
                ot_date: {
                    required: true
                },
                ot_hours: {
                    required: true
                }
            },
            messages: {
                ot_description: {
                    required: "Description must not be empty"
                },
                ot_date: {
                    required: "Date must not be empty"
                },
                ot_hours: {
                    required: "Hours must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
	$("#edit_addtional_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                addtion_name: {
                    required: true
                },
                category_name: {
                    required: true
                },
                unit_amount: {
                    required: true
                }
            },
            messages: {
                addtion_name: {
                    required: "Name must not be empty"
                },
                category_name: {
                    required: "Category must not be empty"
                },
                unit_amount: {
                    required: "Amount must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
		   	$("#add_deduction_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                model_name: {
                    required: true
                },
                unit_amount: {
                    required: true
                }
            },
            messages: {
                model_name: {
                    required: "Name must not be empty"
                },
                unit_amount: {
                    required: "Amount must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
		   	$("#edit_deduction_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                model_name: {
                    required: true
                },
                unit_amount: {
                    required: true
                }
            },
            messages: {
                model_name: {
                    required: "Name must not be empty"
                },
                unit_amount: {
                    required: "Amount must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
		   
		  $("#addtional_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                addtion_name: {
                    required: true
                },
                category_name: {
                    required: true
                },
                unit_amount: {
                    required: true
                }
            },
            messages: {
                addtion_name: {
                    required: "Name must not be empty"
                },
                category_name: {
                    required: "Category must not be empty"
                },
                unit_amount: {
                    required: "Amount must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
    $("#basic_info_form1").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                full_name: {
                    required: true
                },
                dob: {
                    required: true
                },
                gender: {
                    required: true
                },
                address: {
                    required: true
                },
                state: {
                    required: true
                },
                country: {
                    required: true
                },
                pincode: {
                    required: true
                },
                phone: {
                    required: true
                },
                 designations: {
                    required: true
                },
                password: {                    
                    minlength : 6
                },
                confirm_password: {                    
                    minlength : 6,
                    equalTo : "#password"
                }
            },
            messages: {
                full_name: {
                    required: "Full Name must not be empty"
                },
                dob: {
                    required: "DOB must not be empty"
                },
                gender: {
                    required: "Gender must not be empty"
                },
                address: {
                    required: "Address must not be empty"
                },
                state: {
                    required: "State must not be empty"
                },
                country: {
                    required: "Country must not be empty"
                },
                pincode: {
                    required: "Pincode must not be empty"
                },
                phone: {
                    required: "Phone must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });



    $("#personal_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                passport_no: {
                    required: true
                },
                passport_expiry: {
                    required: true
                },
                tel_number: {
                    required: true
                },
                nationality: {
                    required: true
                },
                religion: {
                    required: true
                },
                marital_status: {
                    required: true
                }
            },
            messages: {
                passport_no: {
                    required: "Passport Number must not be empty"
                },
                passport_expiry: {
                    required: "Expiry must not be empty"
                },
                tel_number: {
                    required: "Tel Number must not be empty"
                },
                nationality: {
                    required: "Nationality must not be empty"
                },
                religion: {
                    required: "Full Name must not be empty"
                },
                marital_status: {
                    required: "Marital Status must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#emergency_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                contact_name1: {
                    required: true
                },
                relationship1: {
                    required: true
                },
                contact1_phone1: {
                    required: true
                },
                contact_name2: {
                    required: true
                },
                relationship2: {
                    required: true
                },
                contact2_phone1: {
                    required: true
                }
            },
            messages: {
                contact_name1: {
                    required: "Contact Name must not be empty"
                },
                relationship1: {
                    required: "Relationship must not be empty"
                },
                contact1_phone1: {
                    required: "Contact Phone Number must not be empty"
                },
                contact_name2: {
                    required: "Contact Name must not be empty"
                },
                relationship2: {
                    required: "Relationship must not be empty"
                },
                contact2_phone1: {
                    required: "Contact Phone Number must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#bank_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                bank_name: {
                    required: true
                },
                bank_ac_no: {
                    required: true
                },
                ifsc_code: {
                    required: true
                },
                pan_no: {
                    required: true
                }
            },
            messages: {
                bank_name: {
                    required: "Bank Name must not be empty"
                },
                bank_ac_no: {
                    required: "Account Number must not be empty"
                },
                ifsc_code: {
                    required: "IFSC Code must not be empty"
                },
                pan_no: {
                    required: "Pan Number must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#newtype_leave_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                leave_type_name: {
                    required: true
                },
                leave_days: {
                    required: true
                }
            },
            messages: {
                leave_type_name: {
                    required: "Leave Type Name must not be empty"
                },
                leave_days: {
                    required: "Leave Days must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
$(document).on('click',".ALlmembers",function() {
    $('.ALlmembers').datepicker({autoclose: true });
});
    $('.ALlmembers').datepicker({autoclose: true });
    $("#family_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                'member_name[]': {
                    required: true
                },
                'member_relationship[]': {
                    required: true
                },
                'member_dob[]': {
                    required: true
                },
                'member_phone[]': {
                    required: true
                }
            },
            messages: {
                'member_name[]': {
                    required: "Name must not be empty"
                },
                'member_relationship[]': {
                    required: "Relationship must not be empty"
                },
                'member_dob[]': {
                    required: "DOB must not be empty"
                },
                'member_phone[]': {
                    required: "Phone Number must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


$(document).on('click',".datetimepicker",function() {
    $('.datetimepicker').datepicker({autoclose: true});
});
    $('.datetimepicker').datepicker({autoclose: true});


    
    $("#education_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                'institute[]': {
                    required: true
                },
                'subject[]': {
                    required: true
                },
                'start_date[]': {
                    required: true
                },
                'end_date[]': {
                    required: true
                },
                'degree[]': {
                    required: true
                },
                'grade[]': {
                    required: true
                }
            },
            messages: {
                'institute[]': {
                    required: "Institute Name must not be empty"
                },
                'subject[]': {
                    required: "Subject must not be empty"
                },
                'start_date[]': {
                    required: "Start Date must not be empty"
                },
                'end_date[]': {
                    required: "End Date must not be empty"
                },
                'degree[]': {
                    required: "Degree must not be empty"
                },
                'grade[]': {
                    required: "Grade must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
           
           
           $(document).on('click',".datetimepicker",function() {
                $('.datetimepicker').datepicker();
            });
           $('.datetimepicker').datepicker();

          
    $("#experience_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                'company_name[]': {
                    required: true
                },
                'location[]': {
                    required: true
                },
                'job_position[]': {
                    required: true
                },
                'period_from[]': {
                    required: true
                },
                'period_to[]': {
                    required: true
                }
            },
            messages: {
                'company_name[]': {
                    required: "Company Name must not be empty"
                },
                'location[]': {
                    required: "Location must not be empty"
                },
                'job_position[]': {
                    required: "Job Position must not be empty"
                },
                'period_from[]': {
                    required: "Period From Date must not be empty"
                },
                'period_to[]': {
                    required: "Period To Date must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
});

$(document).on('click','#submit_contact_form',function(){
        $.validator.addMethod("phonevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );        
       

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

            $("#AddContacForm").validate({
                ignore: [],
                rules: {
                    roles: {
                        required: true
                    },
                     contact_name: {
                        required: true
                    },
                    email: {
                       required: true,
                       emailvalidation: 'emailvalidation'
                    },
                    contact_number: {
                        required: true,
                        minlength: 7,
                        maxlength:15,
                        phonevalidation: 'phonevalidation'
                    }
                   
                },
                messages: {
                    roles: {
                        required: "Role must not be empty"
                    },
                    contact_name: {
                        required: "Name must not be empty"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    contact_number: {
                        required: "Phone Number is required",
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    // form.submit();

                    var roles = $('#roles').val();
                    var contact_name = $('#contact_name').val();
                    var email = $('#email').val();
                    var contact_number = $('#contact_number').val();
                    var status =$('input[name=status]:checked').val(); 
                    var fd = new FormData();
                    var files = $('#file')[0].files[0];
                    fd.append('avatar',files);
                    fd.append('roles',roles);
                    fd.append('contact_name',contact_name);
                    fd.append('email',email);
                    fd.append('contact_number',contact_number);
                    fd.append('status',status);
                    console.log(fd);
                    $.ajax({
                        type: "POST",
                        url: base_url + 'all_contacts/create_contacts',
                        // data:  { roles:roles,contact_name:contact_name,email:email,contact_number:contact_number,status:status,fd:fd},
                        data:  fd,
                        contentType: false,
                        processData: false,
                        success: function (data) {                           
                            $("#add_contact").trigger("reset");
                             $("#add_contact").modal("hide");
                            toastr.success('Contact Created Successfully!');                                   
                           $('.contacts-list').html(data);
                          
                           
                        }
                    });
                }
            })
        });
$(document).on('click','#submit_edit_contact_form',function(){
        $.validator.addMethod("phonevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );        
       

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

            $("#EditContacForm").validate({
                ignore: [],
                rules: {
                    roles: {
                        required: true
                    },
                     contact_name: {
                        required: true
                    },
                    email: {
                       required: true,
                       emailvalidation: 'emailvalidation'
                    },
                    contact_number: {
                        required: true,
                        minlength: 7,
                        maxlength:15,
                        phonevalidation: 'phonevalidation'
                    }
                   
                },
                messages: {
                    roles: {
                        required: "Role must not be empty"
                    },
                    contact_name: {
                        required: "Name must not be empty"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    contact_number: {
                        required: "Phone Number is required",
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    // form.submit();

                    var roles = $('#roles_edit').val();
                    var contact_name = $('#contact_name_edit').val();
                    var email = $('#email_edit').val();
                    var contact_number = $('#contact_number_edit').val();
                    var status =$('input[name=status]:checked').val(); 
                    var image =  $('#image_edit').val();
                    var id =  $('#id').val();
                    var fd = new FormData();
                    var files = $('#file_edit')[0].files[0];
                    fd.append('avatar',files);
                    fd.append('roles',roles);
                    fd.append('contact_name',contact_name);
                    fd.append('email',email);
                    fd.append('contact_number',contact_number);
                    fd.append('status',status);
                    fd.append('status',status);
                    fd.append('image',image);
                    fd.append('id',id);
                    console.log(fd);
                    $.ajax({
                        type: "POST",
                        url: base_url + 'all_contacts/edit_contact',
                        // data:  { roles:roles,contact_name:contact_name,email:email,contact_number:contact_number,status:status,fd:fd},
                        data:  fd,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            $(".close").trigger("click");
                            toastr.success('Contact Edited Successfully!');                                   
                            $('.contacts-list').html(data);
                          
                           
                        }
                    });
                }
            })
        });
$(document).on('click','#submit_delete_contact',function(){
     var id = $('#contact_id').val();
    $.ajax({
        type: "POST",
        url: base_url + 'all_contacts/delete_contact',
        data:  { id:id},
       
        success: function (data) {
            $(".close").trigger("click");
            toastr.success('Contact Deleted Successfully!');                                   
            $('.contacts-list').html(data);
            return false;
          
           
        }
    });
});
$(document).on('click','.role_search',function(){
     var id = $(this).attr('data-id');
    $.ajax({
        type: "POST",
        url: base_url + 'all_contacts/search_contact_based_role',
        data:  { id:id},
       
        success: function (data) {                                        
            $('.contacts-list').html(data);          
           
        }
    });
});
$(document).on('click','.name_search',function(){
     var name = $(this).text();     
    $.ajax({
        type: "POST",
        url: base_url + 'all_contacts/search_contact_based_alpha',
        data:  { name:name},
       
        success: function (data) {                                        
            $('.contacts-list').html(data);          
           
        }
    });
});
function contact_search()
{
    var name =$('#contact_search').val();
    if(name!='')
    {
       $.ajax({
        type: "POST",
        url: base_url + 'all_contacts/contact_search',
        data:  { name:name},
       
        success: function (data) {                                        
            $('.contacts-list').html(data);          
           
        }
    });
    }
}
$(document).ready(function(){
    $.ajax({
                type: "GET",
                url: base_url + 'all_contacts/get_contacts',
                // data:  { roles:roles,contact_name:contact_name,email:email,contact_number:contact_number,status:status},
                success: function (data) {
                    // toastr.success('Contact Created Successfully!');                                   
                   $('.contacts-list').html(data);
                }
            });
    $('.BtnEdit').click(function(){
        $('.UpdateBtn').css('display','block');
        $('.EditBtn').css('display','none');
        $('.Daystext').removeAttr('disabled');
    });
    $('.CancelBtn').click(function(){
        $('.EditBtn').css('display','block');
        $('.UpdateBtn').css('display','none');
        $('.Daystext').attr('disabled','disabled');
    });
    $('.CarryFwd').change(function(){
        var carryfwd = $(this).val();
        if(carryfwd == 'yes')
        {
            $('#MaxDays').css('display','block');
        }else{
            $('#MaxDays').css('display','none');
        }
    });
    $('.EditMax').click(function(){
        $('.CarryFwd').removeAttr('disabled');
        $('#MaxDays').removeAttr('disabled');
        $('.UpdateMaxBtn').css('display','block');
        $('.EditMax').css('display','none');
    });
    $('.CancelMaxBtn').click(function(){
        $('.CarryFwd').attr('disabled','disabled');
        $('#MaxDays').attr('disabled','disabled');
        $('.UpdateMaxBtn').css('display','none');
        $('.EditMax').css('display','block');
    });
    

    $(document).on('click',"#annual",function() {
        var annual_leaves = $('#annual_leaves').val();
        if(annual_leaves == '')
        {
            toastr.error('Annual Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_annual_leaves/', { annual_leaves: annual_leaves}, function (datas) {
                // console.log(datas); return false;
               $('#annual_leaves').val(datas);
               toastr.success('Annual Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#sick",function() {
        var sick_leave = $('#sick_leave').val();
        if(sick_leave == '')
        {
            toastr.error('Sick Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_sick_leave/', { sick_leave: sick_leave}, function (datas) {
               $('#sick_leave').val(datas);
               toastr.success('Sick Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#hospitalisation",function() {
        var hospitalisation = $('#hospitalisation').val();
        if(hospitalisation == '')
        {
            toastr.error('Hospitalisation Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_hospitalisation_leave/', { hospitalisation: hospitalisation}, function (datas) {
               $('#hospitalisation').val(datas);
               toastr.success('Hospitalisation Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#maternity",function() {
        var maternity = $('#maternity_leaves').val();
        if(maternity == '')
        {
            toastr.error('Maternity Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_maternity_leave/', { maternity: maternity}, function (datas) {
               $('#maternity_leaves').val(datas);
               toastr.success('Maternity Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#paternity",function() {
        var paternity = $('#paternity_leaves').val();
        if(paternity == '')
        {
            toastr.error('Paternity Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_paternity_leave/', { paternity: paternity}, function (datas) {
               $('#paternity_leaves').val(datas);
               toastr.success('Paternity Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#carry_forward",function() {
        var carry_max = $('#carry_max').val();
        var leave_status = $('.CarryFwd:checked').val();
        // alert(leave_status);
        if(leave_status == 'yes')
        {
            if(carry_max == '')
            {
                toastr.error('Carry Forward Leaves Field is Required');
                return false;
            }
        }
                $.post(base_url + 'leave_settings/update_carry_forward_leave/', { carry_max: carry_max,leave_status:leave_status}, function (datas) {
                   $('#carry_max').val(datas);
                   toastr.success('Carry Forward Leaves Updated');
                   setTimeout(function () {
                        location.reload();
                    }, 1500);
                });
    });

      $(document).on('click',"#leave_weekend",function() {        

        var days = [];
        $('.weekend:checked').each(function(i){         

         days.push($(this).val());
        });
        $.post(base_url + 'settings/update_weekend/', { days: days}, function (datas) {
           
           toastr.success('Leave Weekend Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
        });
    });
    
    $(document).on('click',"#earned",function() {
        var earned_leaves = $('#earned_leaves').val();
        var leave_status = $('.earnLeaves:checked').val();
        if(leave_status == 'yes')
        {
            if(earned_leaves == '')
            {
                toastr.error('Carry Forward Leaves Field is Required');
                return false;
            }
        }
                $.post(base_url + 'leave_settings/update_earned_leave/', { earned_leaves: earned_leaves,leave_status:leave_status}, function (datas) {
                   $('#earned_leaves').val(datas);
                   toastr.success('Carry Forward Leaves Updated');
                   setTimeout(function () {
                        location.reload();
                    }, 1500);
                });
    });
    $(document).on('click',".PolicyID",function() {
        var policy_id = $(this).data('id');
        $('#policy_id').val(policy_id);
    });
    $(document).on('click',".PolicyBtn",function() {
        var policy_name = $('#policy_name').val();
        // alert(policy_name); 
        var policy_days = $('#policy_days').val();
        var policy_id = $('#policy_id').val();
        var options = $('#customleave_select_to option');
        var users = $.map(options ,function(option) {
            return option.value;
        });
        if(policy_name == '')
        {
            toastr.error('Policy Name is Required');
            return false;
        }
        if(policy_days == '')
        {
            toastr.error('Policy Days is Required');
            return false;
        }
        if(users.length == 0)
        {
            toastr.error('Please choose atleast one Employee');
            return false;   
        }
        console.log(users);

        $.post(base_url + 'leave_settings/add_custom_policy/', { policy_name: policy_name,policy_days:policy_days,users:users,policy_id:policy_id}, function (datas) {
            // console.log(datas); 
            toastr.success('Custom Policy Leaves Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
        });

    });
    $(document).on('click',".update_policy_user",function() {
        var policy_name = $('#policy_name').val();
        // alert(policy_name); 
        var policy_days = $('#policy_days').val();
        var policy_id = $(this).data('id');
        var options = $('.EditSelectUsers option');
        var users = $.map(options ,function(option) {
            return option.value;
        });
        if(policy_name == '')
        {
            toastr.error('Policy Name is Required');
            return false;
        }
        if(policy_days == '')
        {
            toastr.error('Policy Days is Required');
            return false;
        }
        if(users.length == 0)
        {
            toastr.error('Please choose atleast one Employee');
            return false;   
        }
        console.log(users);

        $.post(base_url + 'leave_settings/update_policy_user/', { policy_name: policy_name,policy_days:policy_days,users:users,policy_id:policy_id}, function (datas) {
            console.log(datas); return false;
            toastr.success('Custom Policy Leaves Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
        });

    });

    $(document).on('change','#annual_switch',function(){
        var policy_id = $(this).data('id');
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_sick',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_hospitalisation',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_maternity',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_paternity',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','.ALLExtraSwitch',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });
    $(document).on('change','#employee_pro_pics',function(e){
        var file_data = $('#employee_pro_pics').prop('files')[0];
        var user_id = $('#employee_user_id').val();
        var form_data = new FormData();                  
        form_data.append('file', file_data);
        form_data.append('user_id', user_id);

    $.ajax({  
                    url:base_url +'employees/employee_profile_upload/',
                    dataType: 'text',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                     success:function(data)  
                     {  
                          if(data == 'success')
                          {
                            toastr.success('Profile Image Updated');
                               setTimeout(function () {
                                    location.reload();
                                }, 1500);
                           }else{
                            toastr.error('Upload Failed');
                               setTimeout(function () {
                                    location.reload();
                                }, 1500);
                           }
                     }  
                });  
    });


    $('.leave-delete-btn').click(function(){
        var leave_id = $(this).data('id');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete LeaveType?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    console.log(leave_id);
                    $.post(base_url + 'leave_settings/delete_newleave_types/', {leave_id:leave_id}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
    });
});

$(document).ready(function(){

    
    $(document).on('click','.DeleteAddtion',function(){
        var arid = $(this).data('arid');
        var user_id = $(this).data('user');
        var keyid = $(this).data('keyid');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete PF Addtion?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    // console.log(leave_id);
                    $.post(base_url + 'employees/delete_pfaddtional/', {arid:arid,user_id:user_id,keyid:keyid}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
        
    });

$(document).on('click','.DeleteDeduction',function(){
        var arid = $(this).data('arid');
        var user_id = $(this).data('user');
        var keyid = $(this).data('keyid');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete PF Addtion?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    // console.log(leave_id);
                    $.post(base_url + 'employees/delete_pfdeduction/', {arid:arid,user_id:user_id,keyid:keyid}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
        
    });


    
    $('#pf_contribution').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $(".PFrecords").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".PFrecords").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#esi_contribution').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $(".ESIrecords").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".ESIrecords").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#pf_rates').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $('#pf_add_rates').val('');
            $('#pf_total_rate').val('');
            $(".EMprate").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".EMprate").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#pf_employer_contribution').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $('#employer_add_rates').val('');
            $('#employer_total_rates').val('');
            $(".EmprRate").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".EmprRate").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#esi_rate').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $('#esi_add_rate').val('');
            $('#esi_total_rate').val('');
            $(".ESIRates").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".ESIRates").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });
    $(document).on('keyup','#pf_add_rates',function(){
        // alert($(this).val());
        var pf_rate = $(this).val();
        var salary = $('#user_salary').val();
        var pf_amount = 0;
        if(pf_rate != '')
        {
            if(salary != '')
            {
                pf_amount = (salary * pf_rate / 100);
            }else{
                toastr.error('Salary field Required');
                $('#user_salary').focus();
            }
        }
        $('#pf_total_rate').val(pf_amount);
    });


    $(document).on('keyup','#employer_add_rates',function(){
        // alert($(this).val());
        var pf_rate = $(this).val();
        var salary = $('#user_salary').val();
        var pf_amount = 0;
        if(pf_rate != '')
        {
            if(salary != '')
            {
                pf_amount = (salary * pf_rate / 100);
            }else{
                toastr.error('Salary field Required');
                $('#user_salary').focus();
            }
        }
        $('#employer_total_rates').val(pf_amount);
    });


    $(document).on('keyup','#esi_add_rate',function(){
        // alert($(this).val());
        var pf_rate = $(this).val();
        var salary = $('#user_salary').val();
        var pf_amount = 0;
        if(pf_rate != '')
        {
            if(salary != '')
            {
                pf_amount = (salary * pf_rate / 100);
            }else{
                toastr.error('Salary field Required');
                $('#user_salary').focus();
            }
        }
        $('#esi_total_rate').val(pf_amount);
    });


     $("#user_salary,#pf_add_rates,#employer_add_rates,#esi_add_rate,#unit_amount").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });



     /*widget*/

    $(document).on('click','#widget_add_submit',function(){

        var role_id = $('#role_id').val();
        var user_id = $('#user_id').val();
        var projects = $('#projects').val();
        var project_status;
        var check_value = $('#projects').is(":checked");
        if(check_value == true)
        {
            project_status = 'yes';
        }else{
            project_status = 'no';
        }
        /*clients*/
        var clients = $('#clients').val();
        var clients_status;
        var check_values = $('#clients').is(":checked");

        if(check_values == true)
        {
            clients_status = 'yes';
        }else{
            clients_status = 'no';
        }



        /*tasks*/
        var tasks = $('#tasks').val();
        var tasks_status;
        var check_values_t = $('#tasks').is(":checked");


        if(check_values_t == true)
        {
            tasks_status = 'yes';
        }else{
            tasks_status = 'no';
        }

        /*employees*/

        var employees = $('#employees').val();
        var employees_status;
        var check_values_e = $('#employees').is(":checked");
        if(check_values_e == true)
        {
            employess_status = 'yes';
        }else{
            employess_status = 'no';
        }

        /*invoive status*/

        var invoices_status = $('#invoices_status').val();
        var invoices_status_status;
        var check_values_i_s = $('#invoices_status').is(":checked");
        if(check_values_i_s == true)
        {
            invoices_status_status = 'yes';
        }else{
            invoices_status_status = 'no';
        }

        /*overall status*/

        var overall_status = $('#overall_status').val();
        var overall_status_status;
        var check_values_o_s = $('#overall_status').is(":checked");
        if(check_values_o_s == true)
        {
            overall_status_status = 'yes';
        }else{
            overall_status_status = 'no';
        }

        /*invoices*/

        var invoices = $('#invoices').val();
        var invoices_statuss;
        var check_values_i = $('#invoices').is(":checked"); 

        if(check_values_i == true)
        {
            invoices_statuss = 'yes';
        }else{
            invoices_statuss = 'no';
        }

        /*recent rojects*/

        var recent_projects = $('#recent_projects').val();
        var recent_projects_status;
        var check_values_r = $('#recent_projects').is(":checked");

        if(check_values_r == true)
        {
            recent_projects_status = 'yes';
        }else{
            recent_projects_status = 'no';
        }


        


        $.post(base_url + 'welcome/widget_add/', {role_id:role_id,user_id:user_id,projects:projects,clients:clients,tasks:tasks,project_active:project_status,client_active:clients_status,tasks_active:tasks_status,employess_active:employess_status,employees:employees,invoices_status:invoices_status,invoices_status_active:invoices_status_status,overall_status:overall_status,overall_status_active:overall_status_status,invoices:invoices,invoices_active:invoices_statuss,recent_projects:recent_projects,recent_projects_active:recent_projects_status}, function (datas) {
            // console.log(datas); return false;
                        toastr.success('success');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
        
    });


    $(document).on('change','#main_category',function(){
        var category_id = $( "#main_category option:selected" ).val();
        $.post(base_url + 'budgets/check_subcategories/', {category_id:category_id}, function (datas) {
            var sub_categories = JSON.parse(datas);
            $('#sub_category').empty();
            $('#sub_category').append("<option value='' selected disabled='disabled'>Choose Sub-Category</option>");
            for(i=0; i<sub_categories.length; i++) {
                $('#sub_category').append("<option value="+sub_categories[i].cat_id+">"+sub_categories[i].sub_category+"</option>");                      
             }
        });
    });



    $('.add_more_revenue').click(function() {
        var add_div = '<div class="row AlLRevenues"><a class="remove_revenue_div" style="cursor: pointer;"><i class="fa fa-trash-o"></i></a><div class="col-sm-6"><div class="form-group"><label>Revenue Title <span class="text-danger">*</span></label><input type="text" class="form-control RevenuETitle" value="" placeholder="Revenue Title" name="revenue_title[]" autocomplete="off"></div></div><div class="col-sm-5"><div class="form-group"><label>Revenue Amount <span class="text-danger">*</span></label><input type="text" name="revenue_amount[]" placeholder="Amount" value="" class="form-control RevenuEAmount" autocomplete="off"></div></div></div>';
        $('.AlLRevenues:last').after(add_div);
    });
    $('.AllRevenuesClass').on('click','.remove_revenue_div',function() {
        $(this).parent().remove();
        var amount = 0;
        $('.RevenuEAmount').each(function() {
            var revenue_amount = $(this);
            amount += +revenue_amount.val(); 
        });
        $('#overall_revenues').val(amount);
        var ex_amount = 0;
        $('.EXpensesAmount').each(function() {
            var expenses_amount = $(this);
            ex_amount += +expenses_amount.val(); 
        });
        $('#overall_expenses').val(ex_amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        var total_amount = parseInt(overall_revenue) + parseInt(overall_expenses);
        // alert(total_amount);
        $('#expected_profit').val(total_amount);
    });


    $('.add_more_expenses').click(function() {
        var add_div = '<div class="row AlLExpenses"><a class="remove_expenses_div" style="cursor: pointer;"><i class="fa fa-trash-o"></i></a><div class="col-sm-6"><div class="form-group"><label>Expenses Title <span class="text-danger">*</span></label><input type="text" class="form-control EXpensesTItle" value="" placeholder="Expenses Title" name="expenses_title[]" autocomplete="off"></div></div><div class="col-sm-5"><div class="form-group"><label>Expenses Amount <span class="text-danger">*</span></label><input type="text" name="expenses_amount[]" placeholder="Amount" value="" class="form-control EXpensesAmount" autocomplete="off"></div></div></div>';
        $('.AlLExpenses:last').after(add_div);
    });
    $('.AllExpensesClass').on('click','.remove_expenses_div',function() {
        $(this).parent().remove();
        var amount = 0;
        $('.RevenuEAmount').each(function() {
            var revenue_amount = $(this);
            amount += +revenue_amount.val(); 
        });
        $('#overall_revenues').val(amount);
        var ex_amount = 0;
        $('.EXpensesAmount').each(function() {
            var expenses_amount = $(this);
            ex_amount += +expenses_amount.val(); 
        });
        $('#overall_expenses').val(ex_amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        var total_amount = parseInt(overall_revenue) - parseInt(overall_expenses);
        // alert(total_amount);
        $('#expected_profit').val(total_amount);
        $('#budget_amount').val(total_amount);
    });

    $(document).on("change", ".RevenuEAmount", function() {
        var amount = 0;
        $('.RevenuEAmount').each(function() {
            var revenue_amount = $(this);
            amount += +revenue_amount.val(); 
        });
        $('#overall_revenues').val(amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        if(overall_expenses != 0){
            var total_amount = parseInt(overall_revenue) - parseInt(overall_expenses);
            $('#expected_profit').val(total_amount);
            $('#budget_amount').val(total_amount);
        }
    });

    $(document).on("change", ".EXpensesAmount", function() {
        var ex_amount = 0;
        $('.EXpensesAmount').each(function() {
            var expenses_amount = $(this);
            ex_amount += +expenses_amount.val(); 
        });
        $('#overall_expenses').val(ex_amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        var total_amount = parseInt(overall_revenue) - parseInt(overall_expenses);
        // alert(total_amount);
        $('#expected_profit').val(total_amount);
        $('#budget_amount').val(total_amount);
    });

    $(document).on("keyup", "#tax_amount", function() {
        
        var expected_profit = $('#expected_profit').val();
        var tax_amount = $('#tax_amount').val();
        var total_amount = parseInt(expected_profit) - parseInt(tax_amount);
        $('#budget_amount').val(total_amount);
    });


    $("#add_budget_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                budget_title: {
                    required: true
                },
                budget_start_date: {
                    required: true
                },
                budget_end_date: {
                    required: true
                }
            },
            messages: {
                budget_title: {
                    required: "Budget Title is Required"
                },
                budget_start_date: {
                    required: "Budget Start Date is Required"
                },
                budget_end_date: {
                    required: "Budget Start Date is Required"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
           });


    $(".EXpensesAmount").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message     
               return false;
    }
   });

   $(".RevenuEAmount").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
               return false;
    }
   });

   $(document).on('click','.menu-icon',function(){
        $('#site-icon').iconpicker({hideOnSelect: true, placement: 'bottomLeft'});
            $('.menu-icon').iconpicker().on('iconpickerSelected',function(event){
                var role = $(this).attr('data-role');
                var target = $(this).attr('data-href');
                $(this).siblings('div.iconpicker-container').hide();
                $.ajax({
                    url: target,
                    type: 'POST',
                    data: { icon: event.iconpickerValue, access: role  },
                    success: function() {},
                    error: function(xhr) {}
                });
            });
    });
    $(document).on('change','#select_roles',function(){

            var role = $( "#select_roles option:selected" ).val();
            var role_name = role.replace("_", " ");
            $('.LoadGiFImg').css('display','block');
            // alert(role);
            $.post(base_url + 'settings/getMenu_role/', {role:role}, function (datas) {
                data = JSON.parse(datas);
                console.log(data);
                var htmlbody = '';
                var recordscount = data.length;
                htmlbody = '<div class=" fade in"><h2>'+role_name+'</h2><div class="table-responsive"><table id="menu-admin" class="table table-striped table-bordered custom-table m-b-0 sorted_table"><thead><tr><th></th><th class="col-2">Icon</th><th class="col-8">Menu</th><th class="col-2 text-center">Visible</th></tr></thead><tbody>';
                for (var i = 0; i < recordscount; i++) 
				{
                    var record = data[i];
                    var record_visible;
                    if(record.visible == 1)
                    {
                        record_visible = "success";
                    }
					else
					{
                        record_visible = "default";
                    }
                    htmlbody += '<tr class="sortable" data-module="'+record.module+'" data-access="1"><td class="drag-handle"><i class="fa fa-reorder"></i></td><td><div class="btn-group"><button class="btn btn-default iconpicker-component" type="button"><i class="fa '+record.icon+' fa-fw"></i></button><button data-toggle="dropdown" data-selected="'+record.icon+'" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="'+record.access+'" data-href="'+base_url+'settings/hook/icon/'+record.module+'"><span class="caret"></span></button><div class="dropdown-menu iconpicker-container"></div></div></td><td>'+record.name+'</td><td class="text-center"><a data-rel="tooltip" data-original-title="Toggle" class="menu-view-toggle btn btn-sm btn-'+record_visible+'" href="#" data-role="'+record.access+'" data-href="'+base_url+'settings/hook/visible/'+record.module+'"><i class="fa fa-eye"></i></a></td></tr>';
                 }

                $('.MenuListRole').html(htmlbody);
                 setTimeout(function () {
                        $('.LoadGiFImg').css('display','none');
                    }, 5000);
                // console.log(data); return false;
            });

            // $('.AllRoleMenu').css('display','none');

            // $('#'+role).css('display','block');

    });   

    if($('#role_menu_select').length > 0) {
        $('#role_menu_select').multiselect();
    }



    $(document).on("change", ".BudgetType", function() {
        // alert($(this).val());
        var budget_type = $(this).val();
        if(budget_type == 'project'){
            $('.ProjecTS').css('display','block');
            $('.CategorY').css('display','none');
            $('.SUbCategorY').css('display','none');
        }

        if(budget_type == 'category'){
            $('.ProjecTS').css('display','none');
            $('.CategorY').css('display','block');
            $('.SUbCategorY').css('display','block');
        }

    });

    $('.custom_excel').on('click',function(){
        var data_to_excel           =   $(this).attr('data-to-excel');
        excel_data                  =   $('#'+data_to_excel).html();
        
        if(typeof $(this).attr('data-excel-count')!='undefined'){
            count                   =   $(this).attr('data-excel-count');
        }else{
            count                   =   '0';
        }
        
        if(typeof $(this).attr('data-excel-title')!='undefined'){
            excel_title             =   $(this).attr('data-excel-title');
        }
        
        var dataString              =   {excel_data:excel_data,excel_title:excel_title,count:count};
        
        $.ajax({
            type: "POST",
            url: base_url+'balance_sheet/excel/html',
            data: dataString,
            complete: function(){
                window.open(base_url+"balance_sheet/excel");
            },
            success: function(data){
                //console.log('success');
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });
    });


});

//$(document).ready(function(){
  // Revenue add edit delete

  // Append table with add row form on add new button click
    
    $(document).on("keydown", ".num_val", function (event) {


        if (event.shiftKey == true) {
            event.preventDefault();
        }

        if ((event.keyCode >= 48 && event.keyCode <= 57) || 
            (event.keyCode >= 96 && event.keyCode <= 105) || 
            event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
            event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {

        } else {
            event.preventDefault();
        }

        if($(this).val().indexOf('.') !== -1 && (event.keyCode == 190 || event.keyCode == 110))
            event.preventDefault(); 
        //if a decimal has been added, disable the "."-button

    });
//  $(document).on("click", ".statement", function () {

//     $('#statement_2019').removeClass('active');
//     $('#tab_2019').removeClass('active');   
//     $('#tab_'+year).addClass('active');
//     $('#statement_'+year).addClass('active');   
//     alert();
// //});
// ));

 $(document).on('click','#create_offers_submit',function(){
        offerValidateCreate();
    });
 function offerValidateCreate()
    {
    
            // function isRatePresent() {
            //     console.log($('#fixed_rate').prop('checked'));
            //     return $('#fixed_rate').prop('checked') == false;
            // }

            // function isFixedPresent() {
            //     console.log($('#fixed_rate').prop('checked'));
            //     return $('#fixed_rate').prop('checked') == true;
            // }

            // var textareaValue = $('.foeditor-project-add').summernote('code');
            // console.log(textareaValue)
            // if(textareaValue == '<p><br></p>' || textareaValue == '')
            // {
            // $('#addproject_description_error').removeClass('display-none').addClass('display-block');
            // $('.note-editable').trigger('focus');
            // return false;
            // }
            // else
            // {
            // $('#addproject_description_error').removeClass('display-block').addClass('display-none');
            // console.log('comes');
            $("#create_offers").validate({
                ignore: [],
                rules: {
                    title: {
                        required: true
                    },
                     candidate: {
                        required: true
                    },
                    job_type: {
                        required: true
                    },
                    // salary: {
                    //     required: true
                    // },
                    annual_incentive_plan: {
                        required: true
                    },
                    vacation: {
                        required: true
                    },
                    // assign_lead: {
                    //     required: true
                    // },
                    reports_to: {
                        required: true
                    },
                    'offer_approvers[]': {
                        required: true
                    }
                    // default_offer_approval:{
                    //     required: isRatePresent,
                    //     number: true
                    // },
                    // offer_approvers:{
                    //     required: isFixedPresent,
                    //     number: true
                    // }
                    // message_to_approvers: {
                    //     required: true,
                    //     number: true
                    // }
                },
                messages: {
                    candidate: {
                        required: 'Candidate is required'
                    }, 
                    title: {
                        required: 'Title is required'
                    },
                    job_type: {
                        required: 'Please select a Job type'
                    },
                    // salary: {
                    //     required: 'Salary is required'
                    // },
                    start_date: {
                        required: 'Start date is required'
                    },
                    due_date: {
                        required: 'Deadline is required'
                    },
                    assign_lead: {
                        required: 'Please select a lead'
                    },
                    reports_to: {
                        required: 'Please choose a reporters'
                    },
                    annual_incentive_plan: {
                        required: 'Please select a plan'
                    },
                    vacation: {
                        required: 'Please select a vacation'
                    },
                    'offer_approvers[]': {
                        required: 'Please choose a Approvers'
                    },
                    // hourly_rate:{
                    //     required: 'Please enter hourly rate'
                        
                    // },
                    // fixed_price:{
                    //     required: 'Please enter fixed price'
                    // },
                    // estimate_hours: {
                    //     required: 'Estimate hours is required'
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }



            /* Recurition model script */
            /* job module validation */
  /* Update Form Client  */
 
     $(document).on('click','#savejobs',function(){

       
        $("#add_jobs,#edit_jobs").validate({
            onsubmit: true,
           ignore:[],
            rules: {
                job_title: {
                    required: true
                },
                department: {
                    required: true
                },
                position: {
                    required: true
                },
                country_id: {
                    required: true
                },
                job_location: {
                    required: true
                },
                  no_of_vacancy: {
                    required: true
                },
                  experience: {
                    required: true
                },
                  age: {
                    required: true
                },
                salary_from: {
                    required: true
                },
                salary_to: {
                    required: true
                },
                job_type: {
                    required: true
                },
               
                status: {
                    required: true
                },
                start_date:{
                    required:true
                },
                expired_date:{
                    required:true
                },
            },
            messages: {
                job_title: {
                    required: 'Job title is required'
                },
                department: {
                    required: 'Department is required'
                },
                position: {
                    required: 'Position is required'
                },
                  job_location: {
                    required: 'Add at least one job location'
                },
                  no_of_vacancy: {
                    required: 'No of vacancy is required'
                },
                  experience: {
                    required: 'Experience field is required'
                },
                  age: {
                    required: 'Enter the Age limit'
                },
                salary_from: {
                    required: 'Enter the salary starts from'
                },
                salary_to: {
                    required: 'Enter the salary to value'
                },
                job_type: {
                    required: 'Job type required'
                },
               
                status: {
                    required: "Select job status"
                },
                start_date:{
                    required:'Choose start date'
                },
                expired_date:{
                    required:'Choose expired date'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
     
/* job module validation end*/

// Job headder validation 

 $(document).on('click','#submit_job_header',function(){

            $("#job_header").validate({
                ignore: [],
                rules: {
                    
                    description: {
                        required: true
                    }
                },
                messages: {
                   
                    description: {
                        required: "Description must not be empty"
                    }
                   
                },
                submitHandler: function(form) {
                    //form.submit();
                }
                
               });
            });

/* profile setting page */

 $(document).on('click','#contact_save',function(){

      
        $("#contact_info").validate({
            onsubmit: true,
           ignore:[],
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                address: {
                    required: true
                },
                country: {
                    required: true
                },
                  state: {
                    required: true
                },
                  city: {
                    required: true
                },
                  pincode: {
                    required: true
                },
                phone_number: {
                    required: true
                },
                
                email: {
                    required: true
                },
               
                
            },
            messages: {
                first_name: {
                    required: 'First Name field is required'
                },
                last_name: {
                    required: 'Last Name field is required'
                },
                address: {
                    required: 'Address field is required'
                },
                country: {
                    required: 'country field is required'
                },
                  state: {
                    required: 'state field is required'
                },
                  city: {
                    required: 'city field is required'
                },
                  pincode: {
                    required: 'Pincode field is required'
                },
                phone_number: {
                    required: 'Phone Number field is required'
                },
                email: {
                    required: 'email field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
 
 /* candidate add page */
 $(document).on('click','#contact_add',function(){

    
        $("#contact_info_add").validate({
            onsubmit: true,
           ignore:[],
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                address: {
                    required: true
                },
                country: {
                    required: true
                },
                  state: {
                    required: true
                },
                  city: {
                    required: true
                },
                  pincode: {
                    required: true
                },
                phone_number: {
                    required: true,
                    number: true,
                     minlength:10,
                maxlength:10

                },
                
                email: {
                    required: true
                }, 

                'job_category[0]': {
                    required: true
                }, 
                'position_type[0]': {
                    required: true
                }, 
                resume_file: {
                     required:  function(element){
                            if($('#page_check').val()=="" || typeof($('#page_check').val()) == 'undefined'){
                                return true;
                            }
                          
                    } 
                },
               
                
            },
            messages: {
                first_name: {
                    required: 'First Name field is required'
                },
                last_name: {
                    required: 'Last Name field is required'
                },
                address: {
                    required: 'Address field is required'
                },
                country: {
                    required: 'country field is required'
                },
                  state: {
                    required: 'state field is required'
                },
                  city: {
                    required: 'city field is required'
                },
                  pincode: {
                    required: 'Pincode field is required'
                },
                phone_number: {
                    required: 'Phone Number field is required'
                },
                email: {
                    required: 'email field is required'
                },
                resume_file : {
                    required: 'Resume field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

         $(document).on('click','#education_save',function(){

        $("#education_form").validate({
            onsubmit: true,
           ignore:[],
            rules: {
                school_name: {
                    required: true
                },
                passed_out_year: {
                    required: true
                },
                major_subject: {
                    required: true
                },
                degree: {
                    required: true
                },
                gpa: {
                    required: true
                }
            },
            messages: {
                school_name: {
                    required: 'School Name field is required'
                },
                passed_out_year: {
                    required: 'Graduation year field is required'
                },
                major_subject: {
                    required: 'Major area of study field is required'
                },
                degree: {
                    required: 'Degree field is required'
                },
                gpa: {
                    required: 'GPA field is required'
                }
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

         $(document).on('click','#add_skill_btn',function(){
       

        $("#add_skill_form").validate({
            onsubmit: true,
            rules: {
                skills: {
                    required: true
                },
               
            },
            messages: {
                skills: {
                    required: 'add you skills and responsibility'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
          $(document).on('click','.submit_vacation',function(){
       

        $("#addVacation").validate({
            onsubmit: true,
            rules: {
                vocation: {
                    required: true
                },
               
            },
            messages: {
                vocation: {
                    required: 'Vacation field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
            $(document).on('click','.submit_AnnualIncentivePlans',function(){
       

        $("#addAnnualIncentivePlans").validate({
            onsubmit: true,
            rules: {
                plan: {
                    required: true
                },
               
            },
            messages: {
                plan: {
                    required: 'Plan field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
              $(document).on('click','.submit_JobTypes',function(){
       

        $("#addJobTypes").validate({
            onsubmit: true,
            rules: {
                job_type: {
                    required: true
                },
               
            },
            messages: {
                job_type: {
                    required: 'Job Type field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

           $(document).on('click','#account_save',function(){
          
       

        $("#account_setting").validate({
            onsubmit: true,
            rules: {
                email: {
                    required: true
                },
                password: {
                    required: true
                },
                c_password: {
                    //required: true,
                    equalTo: '[name="password"]'
                },

            },
            messages: {
                email: {
                    required: 'email field is required'
                },
                password: {
                    required: 'password field is required'
                },
                c_password: {
                    required: 'confirm password field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
           /*experience details*/

           $(document).on('click','#save_experience',function(){
          
       
        $("#experience_form").validate({
            onsubmit: true,
             ignore:[],
            rules: {
                company_name: {
                    required: true
                },
                location: {
                    required: true
                },
                job_position: {
                    required: true
                },
                period_from: {
                    required: true
                }, 
                period_to: {
                    required: true
                },

            },
            messages: {
               company_name: {
                    required: 'Company name field is required'
                },
                location: {
                    required: 'Location field is required'
                },
                job_position: {
                    required: 'Job Position field is required'
                },
                period_from: {
                    required: 'Period from field is required'
                }, 
                period_to: {
                    required: 'Period to field is required'
                },

            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

           $(document).on('click','#experience_level_btn',function(){
    

        $("#add_experience_level").validate({
            onsubmit: true,
            rules: {
                experience: {
                    required: true
                },status: {
                    required: true
                },
               
            },
            messages: {
                experience: {
                    required: 'Experience level is required'
                },
                status: {
                    required: 'Status field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });
           /* add question form validation */
           $(document).on('click','#save_question',function(){
    

        $("#add_questions_form").validate({
            onsubmit: true,
            rules: {
                department: {
                    required: true
                },
                job_id: {
                    required: true
                },
                category_id: {
                    required: true
                },
                 question: {
                    required: true
                }, 
                question_type: {
                    required: true
                },
                option_a: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                option_b: {
                     required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                option_c: {
                     required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                option_d: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                 correct_answer: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                answer_explanation:{
                    required:  function(element){
                            if($('#question_type').val()==2){
                                return true;
                            }
                          
                    } 
                },

                               
            },
            messages: {
                 department: {
                    required: 'Department field is required'
                },
                job_id: {
                    required: 'Job name field is required'
                },
                category_id: {
                    required: 'Category field is required'
                },
                 question: {
                    required: 'Question field is required'
                },
                option_a: {
                    required: 'Option A field is required'
                },
                option_b: {
                    required: 'Option B field is required'
                },
                option_c: {
                    required: 'Option C field is required'
                },
                option_d: {
                    required: 'Option D field is required'
                },
                correct_answer: {
                    required: 'Correct answer field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

$(document).on('click','#edit_question',function(){
    

        $("#edit_question_form").validate({
            onsubmit: true,
            rules: {
                department: {
                    required: true
                },
                job_id: {
                    required: true
                },
                category_id: {
                    required: true
                },
                 question: {
                    required: true
                },
                question_type: {
                    required: true
                },
                option_a: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                option_b: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                option_c: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                option_d: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                 correct_answer: {
                    required: function(element){
                            if($('#question_type').val()==1){
                                return true;
                            }
                          
                    } 
                },
                answer_explanation:{
                    required:  function(element){
                            if($('#question_type').val()==2){
                                return true;
                            }
                          
                    } 
                },

                               
            },
            messages: {
                 department: {
                    required: 'Department field is required'
                },
                job_id: {
                    required: 'Job name field is required'
                },
                category_id: {
                    required: 'Category field is required'
                },
                 question: {
                    required: 'Question field is required'
                },
                option_a: {
                    required: 'Option A field is required'
                },
                option_b: {
                    required: 'Option B field is required'
                },
                option_c: {
                    required: 'Option C field is required'
                },
                option_d: {
                    required: 'Option D field is required'
                },
                correct_answer: {
                    required: 'Correct answer field is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

    /*user select time*/

    $(document).on('click','#user_schedule_btn',function(){
    
       
        $("#user_schedule_form").validate({
            onsubmit: true,
          
            rules: {
                'user_date[0]': {
                    required: true
                },
                'user_date[1]': {
                    required: true
                },
                'user_date[2]': {
                    required: true
                },
               
            },
            messages: {
                'user_date[0]': {
                    required: 'Please Select day 1 timing'
                },
                'user_date[1]': {
                    required: 'Please Select day 2 timing'
                },
                'user_date[2]': {
                    required: 'Please Select day 3 timing'
                },
               
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });




    function selectjobs(department){
        

     if(department!=""){
         $.post(base_url+'interview_questions/jobs_list',{department:department},function(data){
           
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>select job</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.id+'">'+val.job_title+'</option>';
                    });
                     $('#job_id').html(html);
                }else{
                    $('#job_id').html('<option value="">No job found</option>'); 
                }
               
            }
        });
     }else{
        $('#job_id').html('<option value="">No job found</option>');
     }
 
    }

    function select_category(){
        var department = $('#department').val();
        var job_id = $('#job_id').val();
         if(department!="" && job_id!=""){
         $.post(base_url+'interview_questions/category_list',{department:department,job_id:job_id},function(data){
           
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>select category</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.id+'">'+val.category_name+'</option>';
                    });
                    $('#category_id').html(html);
                }else{
                    $('#category_id').html('<option value="">No category found</option>');
                }
                
            }
        });
     }else{
        $('#category_id').html('<option value="">No category found</option>');
     }
    }

    /*question category form*/
    $(document).on('click','#save_category_btn',function(){
    

        $("#category_form_id").validate({
            onsubmit: true,
            rules: {
                department: {
                    required: true
                },
                job_id: {
                    required: true
                },
                category_name: {
                    required: true
                },
               
            },
            messages: {
                department: {
                    required: 'Department is required'
                },
                job_id: {
                    required: 'Job name is required'
                },
                category_name: {
                    required: 'Category name is required'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

    /*add schedule timing  form*/
    $(document).on('click','#add_schedule_timing_btn',function(){
    

        $("#add_schedule_timing").validate({
            onsubmit: true,
            rules: {
                'schedule_date[0]': {
                    required: true
                },
                'schedule_time[0]': {
                    required: true
                },
                 'schedule_date[1]': {
                    required: true
                },
                'schedule_time[1]': {
                    required: true
                },
                 'schedule_date[2]': {
                    required: true
                },
                'schedule_time[2]': {
                    required: true
                },
               
            },
            messages: {
                'schedule_date[0]': {
                    required: 'Please select date 1'
                },
                'schedule_time[0]': {
                    required: 'Please Select time'
                },
                 'schedule_date[1]': {
                    required: 'Please select date 2'
                },
                'schedule_time[1]': {
                    required: 'Please Select time'
                },
                 'schedule_date[2]': {
                    required:'Please Select date 3'
                },
                'schedule_time[2]': {
                    required: 'Please Select time'
                },
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });

    function delete_category(id){
        $('#delete_category_id').val(id);
    }
    function delete_question(id){
        $('#delete_quest_id').val(id);   
    }

    function get_position(value){
       

var categories = [];  
categories.push(value);
   
     if(categories.length!=0){
         $.post(base_url+'candidates/position_types',{categories:categories},function(data){
           
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>Select Position</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.id+'">'+val.designation+'</option>';
                    });
                }
                $('#positions').html(html);
            }
        });
     }else{
        $('#positions').html('<option value="">No results</option>');
     }
 }



    $(document).ready(function() {

        $('#schedule_date').datepicker({autoclose: true});
      
    });
 $(document).ready(function(event) {
        
        if(typeof enable_result_model !== 'undefined' && enable_result_model==true){
        $('#free_question_modal').modal('show');
        $('#close_btn').removeAttr( 'data-dismiss' );

$('#free_question_modal').modal({
backdrop: 'static',
keyboard: false  // to prevent closing with Esc button (if you want this too)
});


        }
    });
            /* Recurition model script  end*/


         $(document).ready(function() {
            $(document).on("click","#add_another_objective",function() {               
                var count = $("#count").val();
                var dynamic_div = addObjectiveContent(count);
                $(".add-another-obj").append(dynamic_div);
            });
            
            $("body").on("click", ".objective_remove", function () {
                $(this).closest(".performance-box").remove();
                var count = $("#count").val();
                var taskkcount = parseInt(count) - 1;
                for (var i = 0; i < taskkcount; i++) {
                    var goalCnt = parseInt(i) + 1;
                    $(".goalCount:eq( " + i + " )").html("Objective " +  goalCnt);
                }
            });
             
            function addObjectiveContent(count) {
                var goal_count = 'Objective '+count;
                var count_incre = parseInt(count)+1;
                var array_count = parseInt(count)-1;
                $("#count").val(count_incre);
                
                var taskcount = $("#task_count").val();
                var task_count = parseInt(taskcount) + 1;
                $("#task_count").val(task_count);
                if( ratings_value !=''){ 
                     var option = '';
                    var a= 1;
                    for (var i=0; i < ratings_value.length ; i++) {
                        if(ratings_value[i] !=''){ 
                     
                    option += ' <option value="'+ ratings_value[i]+'">'+ratings_value[i]+'</option>';
                 } } } else { 
                       option +=  '<option value="">Ratings Not Found</option>';
                 }


                return '<form action="performance/add_goals" method="post"><div class="row">' + '<div class="col-md-12">' +              
                '<div class="performance-box m-t-15">' +
                '<a href="javascript:void(0);" class="goal_remove objective_remove" title="Remove"><i class="fa fa-times"></i></a>' +
                '<div class="table-responsive">' +
                    '<table class="table performance-table">' +
                        '<thead>' +
                            '<tr>' +
                                '<th class="okr-show-obj"></th>' +
                                '<th class="text-center" style="min-width:120px;">Status</th>' +
                                '<th class="text-center" style="min-width:100px;">Progress</th>' +
                                '<th class="text-center" style="min-width:170px;">Grading</th>' +
                                '<th class="text-center" style="width:110px">Feedback</th>' +
                               
                            '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                            '<tr>' +
                            '<td>' +
                                '<div class="label-input">' +
                                    '<label class="goalCount">'+goal_count+'</label>' +
                                    '<input type="text" class="form-control" name="objective[]">' +
                                '</div>' +
                                '</td>' +
                                '<td class="text-center">' +
                                    '<button style="min-width:100px; padding:10px;" class="btn btn-info">Pending</span>' +
                                    ' <input type="hidden" class="okr_status" name="okr_status[]" value="Pending">' +
                                '</td>' +
                                '<td class="text-center">' +
                                    '<button style="min-width:70px;padding:10px;" class="btn btn-warning demo" type="button" id="demo" data-title="Key Result Progress" data-value="0" onclick="show_progress_bar(this)" name="progress[]">0%</button>' +
                                    '<input type="hidden" class="progress_value" name="progress_value[]" value="">' + 
                                '</td>' +                                
                                
                                '<td class="text-center">' +
                                    // '<strong class="grade" name="grade">0</strong>' +
                                    // '<input type="hidden" class="grade_val" name="grade_value[]" value="">' +
                                    '<select class="form-control select" name="grade_value[]" disabled>' +
                                     option +
                                    '</select>' +
                                '</td>' +
                                '<td class="text-center">'+
                                    '<button style="min-width:50px;padding:10px;font-size:16px;"  class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>'+
                                '</td>'+
                                
                            '</tr>' +
                        '</tbody>' +
                        '<tbody class="key_result_container">' +
                            '<tr>' +
                                '<td>' +
                                    '<div class="label-input">' +
                                        '<label>Key Result 1</label>' +
                                        '<input type="text" class="form-control" name="key_result[]">' +
                                         '<button type="button" class="btn btn-white add_key_result" data-arrayval="'+array_count+'" data-toggle="tooltip" data-original-title="Add Key Result"><i class="fa fa-plus-circle"></i></button>' +
                                    '</div>' +
                                   ' <input type="hidden" id="key_result_'+array_count+'" value="2">' +
                                '</td>' +
                                '<td class="text-center">' +
                                    '<button style="min-width:100px; padding:10px;" class="btn btn-info">Pending</button>' +
                                      ' <input type="hidden" class="key_status" name="key_status[]" value="Pending">' +
                                '</td>' +
                                '<td class="text-center">' +
                                    '<button style="min-width:70px;padding:10px;" class="btn btn-warning keyres_progress" type="button" id="keyres_progress" onclick="show_keyprogress_bar(this)" data-value="0" name="key_progress[]">0%</button>' +
                                '<input type="hidden" class="keyres_value" name="keyres_value[]" value="">' + 
                                '</td>' +
                                '<td class="text-center">' +
                                    // '<strong class="key_grade">0</strong>' +
                                    // '<input type="hidden" class="key_gradeval" name="key_gradeval[]" value="">' +
                                    '<select class="form-control select" name="key_gradeval[]" disabled>' +
                                            option +
                                    '</select>' +
                                '</td>' +
                                '<td class="text-center">'+
                                    '<button style="min-width:50px;padding:10px;font-size:16px;" class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>'+
                                '</td>'+
                                
                            '</tr>' +
                        '</tbody>' +
                    '</table>' +
                    '<div class="m-t-20 m-b-10 text-center">'+
                        '<button class="btn btn-primary" type="submit" id="create_offers_submit">Create OKR Performance</button>'+
                    '</div>'+
                '</div>' +
            '</div>'+
            '</div>'+
            '</div>'+
            '</form>'
            }
        });
        
      
        $(document).ready(function() {
            $(document).on("click",".add_key_result",function() {
                
                var array_count = $(this).data('arrayval');
                var count = $("#key_result_"+array_count).val();
                var count_incre = parseInt(count)+1;

                // $("#result_count").val(count_incre);
                $("#key_result_"+array_count).val(count_incre);
                var div = $("<tr />");
                div.html(KeyResultContent(count,array_count));
                $(this).closest("tbody").append(div);

            });
            $("body").on("click", ".key-remove", function () {
                $(this).closest("tr").remove();
                var array_count = $(this).data('arrayval');
                var count = $("#key_result_"+array_count).val();
                var taskkcount = parseInt(count) - 1;
                $("#key_result_"+array_count).val(taskkcount);
                var keyCountRem = parseInt(taskkcount) - 1;
                for (var j = 0; j < keyCountRem; j++) {
                    var goalCnt = parseInt(j) + 2;
                    $(".key_result_count_"+array_count+":eq( " + j + " )").html("Key Result " +  goalCnt);
                }
            });
                
            function KeyResultContent(count,array_count) {

                 var goal_count = 'Key Result '+count;
               
                
                // var taskcount = $("#result_task_count").val();
                // var task_count = parseInt(taskcount) + 1;
                // $("#result_task_count").val(task_count);
                 if( ratings_value !=''){ 
                     var option = '';
                    var a= 1;
                    for (var i=0; i < ratings_value.length ; i++) {
                        if(ratings_value[i] !=''){ 
                     
                    option += ' <option value="'+ ratings_value[i]+'">'+ratings_value[i]+'</option>';
                 } } } else { 
                       option +=  '<option value="">Ratings Not Found</option>';
                 }


                return '<td>' +
                    '<div class="label-input">' +
                    '<label class="key_result_count_'+array_count+'">'+goal_count+'</label>' +
                    '<input type="text" class="form-control" name="key_result[]">' +
                    '<button class="btn btn-white text-danger key-remove" data-arrayval="'+array_count+'" title="Remove"><i class="fa fa-times"></i></button>' +
                    '</div>' + 
                    '</td>' + 
                '<td class="text-center"><span style="min-width:100px;padding:10px;" class="btn btn-info">Pending</span>' + 
                '<input type="hidden" class="key_status" name="key_status[]" value="Pending">' +
                '</td>' + 
                '<td class="text-center"><button style="min-width:70px;padding:10px;" class="btn btn-warning keyres_progress" type="button" onclick="show_keyprogress_bar(this)" data-value="0" name="key_progress[]">0%</button>' +
                '<input type="hidden" class="keyres_value" name="keyres_value[]" value="">' + '</td>' +
               
                '<td class="text-center">' +
                                    // '<strong class="key_grade">0</strong>' +
                                    // '<input type="hidden" class="key_gradeval" name="key_gradeval[]" value="">' +
                                    '<select class="form-control select" name="key_gradeval[]" disabled>' +
                                            option +
                                    '</select>' +
                                '</td>' +

                '</td>' + 
                '<td class="text-center"><button style="min-width:50px;padding:10px;font-size:16px;" class="btn btn-white" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button></td>'
            }



    // Smart Goal create

      // $(document).on('click','.create_goal_configuration_submit',function(){
      //    alert();
            

      //       $("#create_goal_configuration").validate({
      //           ignore: [],
      //           rules: {
      //               rating_scale: {
      //                   required: true
      //               },
      //               'rating_value[]': {
      //                   required: true
      //               },
      //               'definition[]': {
      //                  required: true,
      //               }
      //           },
      //           messages: {
      //               rating_scale: {
      //                   required: "Rating Scale must not be empty"
      //               },
      //               'rating_value[]': {
      //                   required: "Rating value must not be empty"
      //               },
      //               'definition[]': {
      //                   required: "Definition  date must not be empty"
      //               }
      //           },
      //           submitHandler: function(form) {
      //               form.submit();
      //           }
                
      //          });
      //       }); 

    // Smart Goal End
 
        });


        //Progress bar for objective

         var slider = document.getElementById("myRange");

       
		if(slider) {
			 $('.demo').append(slider.value + '%');
			 $('.grade').append(parseFloat(this.slider.value) / 100.0);


			slider.oninput = function() 
			{          
				 output.innerHTML = this.value + '%';
				 // $(output).val(this.value);   
				 $(output).next('input').val(this.value);
				 $(grade).html(parseFloat(this.value) / 100.0);
				 $(grade).next('input').val(parseFloat(this.value) / 100.0);
				 $(closest_hidden_grade).val(output.innerHTML); 
				 $(closest_hidden_gradeval).val(parseFloat(this.value) / 100.0); 
				 console.log($(output).html());
				
				$('.setyear').text(this.value+ '%');
				var titlepos = (parseInt(5 * this.value));
				$('.setyear').css({'left': titlepos});
				$('.okr_progress').val(this.value);
				 
				this.style.background = 'linear-gradient(to right, #e9ab2e 0%, #e9ab2e '+this.value +'%, #fff ' + this.value + '%, white 100%)'
			};

		}

        function show_progress_bar(e)
        {
            $('.okr_progress').val(0);
            $('.okr_progress').removeAttr("style");  
            var value = $(e).data('value');
            output = e;             
            closest_hidden_grade = $(output).closest('.progress_value').val();               
            grade  = $(e).parent().next('td').find('.grade');    
            closest_hidden_gradeval = $(grade).closest('.grade_val').html();   
            $('.okr_progress').css('background','linear-gradient(to right, #e9ab2e 0%, #e9ab2e '+value +'%, #fff ' +value + '%, white 100%)');
           
            $('.setyear').text(value+ '%');
             var titlepos = (parseInt(value)*5);
              $('.setyear').css({'left': titlepos});
            $('.okr_progress').val(value);
            $('#progress_bar').modal('show');
        }

        // Progress bar for key result


         var keyresult_progress = document.getElementById("key_range");

       
		if(keyresult_progress) {
         $('.keyres_progress').append(keyresult_progress.value + '%');
         $('.key_grade').append(parseFloat(this.keyresult_progress.value) / 100.0);


        keyresult_progress.oninput = function() 
        {          
             key_res.innerHTML = this.value + '%';
             $(key_res).next('input').val(this.value);
             $(key_grade).html(parseFloat(this.value) / 100.0);
             $(key_grade).next('input').val(parseFloat(this.value) / 100.0);
             $(closest_hidden_keyprog).val(key_res.innerHTML); 
             $(closest_hidden_keygrade).val(parseFloat(this.value) / 100.0); 
             console.log($(key_res).html());
             // this.title=this.value;
             $('.setyearkey').text(this.value+ '%');
            var titlepos = (parseInt(5 * this.value));
            $('.setyearkey').css({'left': titlepos});
            $('.key_progress').val(this.value);
            this.style.background = 'linear-gradient(to right, #55ce63 0%, #55ce63 '+this.value +'%, #fff ' + this.value + '%, white 100%)'
        };
		}

        function show_keyprogress_bar(e)
        {
            $('.okr_key').val(0);
            $('.okr_key').removeAttr("style");
            
            var value = $(e).data('value');
           
            key_res = e;
            closest_hidden_keyprog = $(key_res).closest('.keyres_value').html();
            key_grade  = $(e).parent().next('td').find('.key_grade');
            closest_hidden_keygrade = $(key_grade).closest('.key_gradeval').html();   

            $('.key_progress').css('background','linear-gradient(to right, #55ce63 0%, #55ce63 '+value +'%, #fff ' +value + '%, white 100%)');
            $('.setyearkey').text(value+ '%');
            var titlepos = (parseInt(value)*5);
            $('.setyearkey').css({'left': titlepos});
            $('.key_progress').val(value);
            $('#key_progress').modal('show');
        }

    
        function objective_feedback(data)
        {
                     
            $('#myModal').modal('show');
            $('#feedback_obj').val($('#fb_'+data).val());
        }

        // function key_feedback(id)
        // {
        //     $('#myModal').modal('show');
        //     $('.objec_feedback').css('display','none');
        //     $('.key_feedback_textarea').css('display','none');
        //     $('#key_feedback_'+id).css('display','block');
        //     $('#key_feedback_'+id).val($(this).data('id'));
        // }

        function goal_feedback(id)
        {
            $('#goalfbk').modal('show');
            $('.goal_feedback').css('display','none');
            $('#feedback_'+id).css('display','block');
        }
        
        function accept_expense(id)
        {
            expense_ajax(id,1);
        }

        function decline_expense(id)
        {
           

            expense_ajax(id,2);
        }

        function expense_ajax(id,status)
        {

            $.ajax({  
                url:base_url +'expenses/update_expense_status',   
                dataType: 'json',                       
                data: {id:id,status:status},                         
                type: 'post',
                 success:function(data)  
                 {  
                    if(parseInt(data.updated)>0)
                    {      

                        // if($('#row_id_'+id).find('span').length>0)
                        // {                         
                        //     if($('#row_id_'+id).find('span').hasClass("label-warning"))
                        //     {
                        //         $('#row_id_'+id).find('span').removeClass("label-warning");
                        //     }                        
                        //     if($('#row_id_'+id).find('span').hasClass("label-success"))
                        //     {
                        //         $('#row_id_'+id).find('span').removeClass("label-success");
                        //     }                        
                        //     if($('#row_id_'+id).find('span').hasClass("label-danger"))
                        //     {
                        //         $('#row_id_'+id).find('span').removeClass("label-danger");
                        //     }                      

                            if(parseInt(status)==1)
                            {                             
                                $('#row_id_'+id).find('span').addClass("label-success");
                                $('#row_id_'+id).find('span').html('');
                                $('#row_id_'+id).find('span').html('Approved');
                                toastr.success('Approved Successfully');  
                                // window.location.replace(base_url + 'expenses') ;
                                location.reload();
                            }
                            if(parseInt(status)==2)
                            {                      
                                $('#row_id_'+id).find('span').addClass("label-danger");
                                $('#row_id_'+id).find('span').html('');
                                $('#row_id_'+id).find('span').html('Rejected');
                                toastr.success('Rejected Successfully');                                    
                                // window.location.replace(base_url + 'expenses') ;
                                location.reload();
                            }
                            else 
                            {                             
                                $('#row_id_'+id).find('span').addClass("label-warning");
                                $('#row_id_'+id).find('span').html('');
                                $('#row_id_'+id).find('span').html('Pending');
                                location.reload();
                            }
                        // }                    
                    }else{
						alert("You dont have access for this action");
					}						
                 }  
            });
        }


 /*indicators validation*/
function indicator_form(){
    $("#add_indicator").validate({
                ignore: [],
                rules: {
                    designation: {
                        required: true
                    },
                    
                    status: {
                        required: true
                    },
                    
                },
                messages: {
                   
                      designation: {
                        required: 'Designation is required'
                    },
                    
                    status: {
                        required: 'Status is required'
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
      $("#edit_indicator").validate({
                ignore: [],
                rules: {
                    designation: {
                        required: true
                    },
                    
                    status: {
                        required: true
                    },
                    
                },
                messages: {
                   
                      designation: {
                        required: 'Designation is required'
                    },
                    
                    status: {
                        required: 'Status is required'
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

}
    
        function appraisal_from()
        {
             $("#add_appraisal").validate({
                ignore: [],
                rules: {
                    employee_id: {
                        required: true
                    },
                    appraisal_date: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                    
                },
                messages: {
                   
                     employee_id: {
                        required: 'Please select the employee'
                    },
                    appraisal_date: {
                        required: 'Please select appraisal date'
                    },
                    status: {
                        required: 'Please select status'
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
              $("#edit_appraisal").validate({
                ignore: [],
                rules: {
                    employee_id: {
                        required: true
                    },
                    appraisal_date: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                    
                },
                messages: {
                   
                     employee_id: {
                        required: 'employee field is required'
                    },
                    appraisal_date: {
                        required: 'Please select appraisal date'
                    },
                    status: {
                        required: 'Please select status'
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

        }
            
            
           

            
    
function select_indicators(value){
  
     $.post(base_url+'appraisal/indicators_list/',{designation:value},function(datas){

         data = JSON.parse(datas);
               if(data == null){
                for (var i = 1; i < 15; i++) {
                    var record ='';
                   // alert(record);
                   var j =i;
                    $('#level_'+j).text('');
                    
                    }
                $('#add_appraisal_btn').attr('disabled',true);
                $('#edit_appraisal_btn').attr('disabled',true);
               }else{
                $('#add_appraisal_btn').removeAttr('disabled');
                $('#edit_appraisal_btn').removeAttr('disabled');
               }
                for (var i = 1; i < 15; i++) {
                    var record = data[i];
                   // alert(record);
                   var j =i;
                    $('#level_'+j).text(record);
                    
                    }

             
        });
}
/*performance*/
$(document).ready(function() {

    $('#rating_scale_select input[name="rating_scale_smart_goal"]').on('click', function() {
    if ($(this).val() == 'rating_01_010') {
        $('#01ratings_cont').show();
        $('#5ratings_cont').hide();
        $('#10ratings_cont').hide();
        $('#custom_rating_cont').hide();
    }   
    if ($(this).val() == 'rating_1_5') {
        $('#5ratings_cont').show();
        $('#10ratings_cont').hide();
        $('#custom_rating_cont').hide();
        $('#01ratings_cont').hide();
    }
    if ($(this).val() == 'rating_1_10') {
        $('#10ratings_cont').show();
        $('#5ratings_cont').hide();
        $('#custom_rating_cont').hide();
        $('#01ratings_cont').hide();
    }
    if ($(this).val() == 'custom_rating') {
        $('#custom_rating_cont').show();
        $('#5ratings_cont').hide();
        $('#10ratings_cont').hide();
        $('#01ratings_cont').hide();
    }
    else {
    }
});
    
    $('#rating_scale_select_okr input[name="rating_scale"]').on('click', function() {
    if ($(this).val() == 'rating_01_010') {
        $('#01ratings_cont_okr').show();
        $('#5ratings_cont_okr').hide();
        $('#10ratings_cont_okr').hide();
        $('#custom_rating_cont_okr').hide();
    }   
    if ($(this).val() == 'rating_1_5') {
        $('#5ratings_cont_okr').show();
        $('#10ratings_cont_okr').hide();
        $('#custom_rating_cont_okr').hide();
        $('#01ratings_cont_okr').hide();
    }
    if ($(this).val() == 'rating_1_10') {
        $('#10ratings_cont_okr').show();
        $('#5ratings_cont_okr').hide();
        $('#custom_rating_cont_okr').hide();
        $('#01ratings_cont_okr').hide();
    }
    if ($(this).val() == 'custom_rating') {
        $('#custom_rating_cont_okr').show();
        $('#5ratings_cont_okr').hide();
        $('#10ratings_cont_okr').hide();
        $('#01ratings_cont_okr').hide();
    }
    else {
    }
});
    $('#rating_scale_select_competency input[name="rating_scale_competency"]').on('click', function() {
    if ($(this).val() == 'rating_01_010') {
        $('#01ratings_cont_competency').show();
        $('#5ratings_cont_competency').hide();
        $('#10ratings_cont_competency').hide();
        $('#custom_rating_cont_competency').hide();
    }
    if ($(this).val() == 'rating_1_5') {
        $('#5ratings_cont_competency').show();
        $('#10ratings_cont_competency').hide();
        $('#custom_rating_cont_competency').hide();
        $('#01ratings_cont_competency').hide();
    }
    if ($(this).val() == 'rating_1_10') {
        $('#10ratings_cont_competency').show();
        $('#5ratings_cont_competency').hide();
        $('#custom_rating_cont_competency').hide();
        $('#01ratings_cont_competency').hide();
    }
    if ($(this).val() == 'custom_rating') {
        $('#custom_rating_cont_competency').show();
        $('#5ratings_cont_competency').hide();
        $('#10ratings_cont_competency').hide();
        $('#01ratings_cont_competency').hide();
    }
    else {
    }
});
    $(".custom_rating_input").keyup(function () {
        var value = $(this).val();              
        var type = $(this).attr("data-type");   
        $(".custom-value").text(value); 
        
        var custom_input_html = ''; 
        for (var i = 1; i <= value; i++) {
          custom_input_html += '<tr>' +
            '<td style="width:50px;"> '+ i +' </td>' +
            '<td style="width:300px">' +
                '<input type="hidden" class="form-control" name="rating_no[]" value="'+i+'">' +
                '<input type="text" class="form-control" name="rating_value_custom[]" placeholder="Short word to describe rating of '+i+'" required>' +
            '</td>' +
            '<td>' +
                '<textarea rows="3" class="form-control" name="definition_custom[]" placeholder="Descriptive Rating Definition" required></textarea>' +
            '</td>' +
        '</tr>'; 
        }
        $('.custom-value_'+type).html(custom_input_html);
        
    });
});

// projects,tasks

$(document).ready(function(){
        $('.open_upso').click(function(){
            $('#file_upload').trigger('click'); 
            });
        $('.open_upso_load').click(function(){
            var zid = $(this).data('zid');
            $('#file_upload_'+zid).trigger('click'); 
            });
        $('#OpenImgUpload').click(function()
        { 
            $('#file_upload').trigger('click'); 
        });
//        $('#file_upload').change(function(){ $('#post_comments').submit(); });

    
    
       /* $('#post_comments').submit(function(){
console.log('asfsdgfdgh');
            var fileupload=$('#fileupload').val();
            var comments=$('#comments').val();

            if(fileupload=='' && comments=='')
            {
                return false;
            }
            else
            {
                $('#post_comments').submit();
            }

        });*/
         $('#file_upload').on('change',function(e){
               e.preventDefault();
                var file_data = $('#file_upload').prop('files')[0];   
                var project = $('#project_upl').val();   
                var task = $('#task_k').val();   
                var form_data = new FormData();      
                     
                form_data.append('projectfiles', file_data);
                 
            
                $.ajax({
                    url: base_url + 'all_tasks/post_task_comments/'+task+'/'+project,
                    dataType: 'json',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'POST',
                    success: function(data){
                //      console.log('data'); // display response from the PHP script, if any
                         call_comments(task);
                    }
                 });
            });
            
         $('._file_updata').on('change',function(e){
             
               e.preventDefault();
               var data_id =$(this).data('fid');
                var file_data = $('#file_upload_'+data_id).prop('files')[0];   
                var project = $('#project_upl_'+data_id).val();   
                var task = $('#task_k_'+data_id).val();   
                var form_data = new FormData();      
                     
                form_data.append('projectfiles', file_data);
                 
            
                $.ajax({
                    url: base_url + 'all_tasks/post_task_comments/'+task+'/'+project,
                    dataType: 'json',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'POST',
                    success: function(data){
                //      console.log('data'); // display response from the PHP script, if any
                         call_comments(task);
                    }
                 });
            });
            
            $('._comment_upload').on('click',function(){
                
                
                var data_id =$(this).data('taskid');
                
                    var comment_urls= base_url + 'all_tasks/post_task_comments';   
                    
                    var fileupload=$('#fileupload').val();
                    
                        var project = $('#project_upl_'+data_id).val();   
                        var task = $('#task_k_'+data_id).val();
                        var description = $('#comments_'+data_id).val();
                           
                    var comments={'task':task,'project':project,'description':description};          
                     
                      if(description !='' ){
                      $.ajax({
                            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                            url         : comment_urls, // the url where we want to POST
                            data        : comments, // our data object
                            dataType    : 'json', // what type of data do we expect back from the server
                            encode          : true
                        })
                    // using the done promise callback
                    .done(function(data) {
                        $('#comments_'+task).val('');
                         call_comments(task);
                        });
                    }
                        
                });
          $('#_comment_upload').on('click', function() {
            
            var comment_urls= base_url + 'all_tasks/post_task_comments';   
            
            var fileupload=$('#fileupload').val();
            
                var project = $('#project_upl').val();   
                var task = $('#task_k').val();
                var description = $('#comments').val();
                   
            var comments={'task':task,'project':project,'description':description}; 
            if(description !='' ){       
              $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : comment_urls, // the url where we want to POST
                    data        : comments, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {
                 
                 call_comments(task);
                
                });
                
                }
        });
        
    });
    
    function call_comments(data)
    {
        
        var comment_urls= base_url + 'all_tasks/ajax_comments_update';   
        $.ajax({
                type : 'POST',
                url : comment_urls,
                data        : {'id':data}, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                encode          : true
            }) .done(function(data) {
                  
var content_er='';
                   $.each(data, function(i, item) {
 
var pro_pic_coms='';
                    if(item.avatar== '' )
                 {
                        pro_pic_coms = base_url + 'assets/avatar/default_avatar.jpg';
                   }else{
                            pro_pic_coms = base_url + 'assets/avatar/'+item.avatar;
                        }
 
                        if(item.activites)
                        {
                    content_er =        '<div class="task-information">'+
                            '<span class="task-info-line"><a class="task-user" href="#">'+item.fullname+'</a> <span class="task-info-subject">'+item.activites+'</span></span>'+'<div class="task-time">'+item.date_posted+'</div></div>';                          
                        }
                        else
                        {
                            content_er ='<div class="chat chat-left">'
                                        +'<div class="chat-avatar">'
                                        +'<a  title="'+item.fullname+'" data-placement="right" data-toggle="tooltip" class="avatar">'
                                        +'<img alt="'+item.fullname+'" src="'+pro_pic_coms+'" class="img-responsive img-circle"></a></div>'
                                        +'<div class="chat-body">'
                                        +'<div class="chat-bubble">'
                                        +'<div class="chat-content">'
                                        +'<span class="task-chat-user">'+' '+item.fullname+' '+'</span>';
                            
                            if(item.file_name){
                                
                            content_er = content_er + '<span class="file-attached">'
                            +'attached file <i class="fa fa-paperclip">'
                            +'</i></span> <span class="chat-time">'
                            +' few seconds ago '
                            +'</span>'
                            +'<ul class="attach-list">';
                                
                            
                            
                            if(item.file_ext== '.png'|| item.file_ext== '.jpg'|| item.file_ext== '.jpeg'|| item.file_ext== '.PNG'|| item.file_ext== '.JPG'|| item.file_ext== '.JPEG')
                                {
                                
                                content_er = content_er+'<li class="img-file">'+
                                    '<div class="attach-img-download"><a href="'+base_url + 'all_tasks/download/'
                                    +item.path+'/'+item.file_name+'">'+item.file_name+'</a></div>'
                                    +'<div class="task-attach-img"><img src="'+base_url + 'assets/project-files/'+item.path+'/'+item.file_name+'" alt=""></div></li>';
                                                        
                                }
                                else if (item.ext=='.pdf')
                                {
                                    content_er = content_er + '<ul class="attach-list">'
                                    +'<li class="pdf-file">'
                                    +'<i class="fa fa-file-pdf-o"></i> '
                                    +'<a href="'+base_url + 'all_tasks/download/'+item.path+'/'+item.file_name+'">'+item.file_name+'</a></li></ul>';
                                }
                                else
                                {
                                    content_er = content_er + '<ul class="attach-list">'
                                    +'<li><i class="fa fa-file">'
                                    +'</i> <a href="'+base_url + 'all_tasks/download/'+item.path+'/'+item.file_name+'">'+item.file_name+'</a></li></ul>';
                                }
                            }
                            if (item.message)
                            {
                                
                                //~ content_er = content_er + '<span class="chat-time">'+' '+item.date_posted 
                                content_er = content_er + '<span class="chat-time">'+' few seconds ago </span>'
                                +'<p>'+ item.message +'</p>';
                            }
                            content_er = content_er + '</div></div></div></div>';   
                        
                        }
                    
                   });
                   
                   
                   $('.chats').append(content_er);
                   $('#comments').val('');
                   
                  
                });
    }
 $(document).ready(function(){


        // Shows panel for entering new tasks
        $('.add-task-btn').click(function(){
            var newTaskWrapperOffset = $('.new-task-wrapper').offset().top;
            $(this).toggleClass('visible');
            $('.new-task-wrapper').toggleClass('visible');
            $('.new-task-wrapper').addClass('visible');
             $('.new-task-wrapper').show();
            // Focus on the text area for typing in new task
            $('#new_task').focus();
            // Smoothly scroll to the text area to bring the text are in view
            $('body').animate({ scrollTop: newTaskWrapperOffset}, 1000);
        });

        // Shows panel for entering new tasks

        $('#new_task').blur(function(){
            var task=$(this).val();
            var project_id=$('#project_id').val();
            if(task!='')
            {

                var formData = {
            'task_name'         : task,
            'project'   :project_id,
            };
            $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : base_url + 'all_tasks/add_tasks', // the url where we want to POST
                    data        : formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<li class="task">'
                                    +'<div class="task-container">'
                                        +'<span class="task-action-btn task-check">'
                                            +'<span class="action-circle large complete-btn task_completes"  data-id="'+data.task_id+'" title="Mark Complete">'
                                                +'<i class="material-icons">check</i>'
                                            +'</span>'
                                        +'</span>'
                                        +'<a href="'+base_url + 'all_tasks/task_view/'+project_id+'/'+data.task_id+'"><span class="task-label" contenteditable="true">'+task+' </span></a>'
                                    +'</div>'
                                +'</li>';
                        
                        $('#task-list').append(html);
                        $('#new_task').val('');
                        $('.new-task-wrapper').hide();
                    }

                // here we will handle errors and validation messages
            });

            }

        });


        $('#new_task').keypress(function(e) {
             if (e.keyCode == 13 && !e.shiftKey) {
              e.preventDefault();
            var task=$(this).val();
            var project_id=$('#project_id').val();
            if(task!='')
            {

                var formData = {
            'task_name'         : task,
            'project'   :project_id,
            };
            $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : base_url + 'all_tasks/add_tasks', // the url where we want to POST
                    data        : formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<li class="task">'
                                    +'<div class="task-container">'
                                        +'<span class="task-action-btn task-check">'
                                            +'<span class="action-circle large complete-btn task_completes"  data-id="'+data.task_id+'" title="Mark Complete">'
                                                +'<i class="material-icons">check</i>'
                                            +'</span>'
                                        +'</span>'
                                        +'<a href="'+base_url + 'all_tasks/task_view/'+project_id+'/'+data.task_id+'"><span class="task-label" contenteditable="true">'+task+' </span></a>'
                                    +'</div>'
                                +'</li>';
                        
                        $('#task-list').append(html);
                        $('#new_task').val('');
                        $('.new-task-wrapper').hide();
                    }

                // here we will handle errors and validation messages
            });

            }
        }

        });

        $('#project_assign_tasks').click(function(){
            alert();
            
        });

        $('.task_completes').click(function() {

        var task_id = $(this).data().id;
        var task_complete = 'true';

        var formData = {
                'task_id'         : task_id,
                'task_complete'   : task_complete
            };
        $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : base_url + 'projects/tasks/progress', // the url where we want to POST
                data        : formData, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                encode          : true
            })
                // using the done promise callback
                .done(function(data) {

                     if ( ! data.success) {
                            alert('There was a problem with AJAX');
                        }else{
                            location.reload();
                        }

                    // here we will handle errors and validation messages
                });

      });


    $('.task_uncompletes').click(function() {

        var task_id = $(this).data().id;
        var task_complete = '';

        var formData = {
                'task_id'         : task_id,
                'task_complete'   : task_complete
            };
        $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : base_url + 'projects/tasks/progress', // the url where we want to POST
                data        : formData, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                encode          : true
            })
                // using the done promise callback
                .done(function(data) {

                     if ( ! data.success) {
                            alert('There was a problem with AJAX');
                        }else{
                            location.reload();
                        }

                    // here we will handle errors and validation messages
                });

      });   

    });


function assign_form_submit()
{
        
            var assigned_to=[];

            $.each($(".AssigneDTo option:selected"), function(){            
                assigned_to.push($(this).val());
            });
            var project=$('#project').val();
            var task=$('#task').val();
            var type=$('#type').val();
            var due_date=$('#add_task_date_due').val();

            //~ console.log(assigned_to);

            if(assigned_to==null &&  due_date==null)
            {
                return false;
            }
            else
            {
           

                var formData = {
            'assigned_to'         : assigned_to,
            'project'   :project,
            'task'         : task,
            'type'   :type,
            'due_date'         : due_date,
           
            };//console.log(formData);
            $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : base_url + 'all_tasks/assign_user', // the url where we want to POST
                    data        : formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {


                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{


                        if(type=='Assign')
                        {
                        
                        
                        $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : base_url + 'all_tasks/team_datajax_load', // the url where we want to POST
                    data        : {'assigned_to':assigned_to}, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) { 
                
           var start_html='';
           var default_img = base_url + 'assets/avatar/default_avatar.jpg';
           var heading_ing =' <div class="pro-teams"><div class="pro-team-members">'+'<div class="task-head-title">Assign to</div><div class="avatar-group">';              
                  var contents='';
                  var new_html='';
                  var hidden_user=0;
                        $.each(data, function(i, item) {
    
                    var prof_img='';
                if(item.avatar)
                {
                    prof_img = base_url + 'assets/avatar/'+item.avatar;
                }
                else
                {
                    prof_img= default_img;
                }
                if(i<5) //to show only 5 users list to avoid users icon overflow
                {
                    contents += '<div class="avatar">'
                 +'<img class="avatar-imgrounded-circle border border-white" alt="UserImage" title="'+item.fullname+'"'+
                 'src = "'+prof_img+'"></div> ';
                }console.log(i);
                
            /*  if(i>4 && $('#assign_tasks'+task).hasClass('remove_hidden_user'))
                {
                    contents += '<div class="avatar">'
                 +'<img class="avatar-imgrounded-circle border border-white" alt="UserImage" title="'+item.fullname+'"'+
                 'src = "'+prof_img+'"></div> ';
                }*/
                
                hidden_user=i;
                });//console.log(hidden_user);
            new_html = contents+' <div class="avatar"><a class="avatar-title rounded-circle border border-white"  data-toggle="ajaxModal" href="'+base_url + 'all_tasks/assign_user/'+project+'/'+task+'/Assign"><i class="fa fa-plus"></i></a></div>';
            hidden_user = hidden_user-4; // i in each loop has starts with 0. so here 4 is used instead of 5
            var end_html='</div></div>  </div></div>';
            if(hidden_user>0)// &&  $('#assign_tasks'+task).hasClass('remove_hidden_user')==false)
            {
                 start_html= '<div class="avatar"><span title="Additional users" class="badge bg-purple">+'+hidden_user+'</span></div>';
            }
            
            $('.assignee-info').html(heading_ing+ start_html+ new_html+end_html);
            $('#assign_tasks'+task).html(heading_ing+ start_html+new_html+end_html);
            //~ $('.task-head-title').html('');
            
});
                        
                      
                      
                         /*   var html='<div class="avatar">'
                                    +'<img src="'+data.profile_img+'" alt="">'
                                 +'</div>'
                                +'<div class="assigned-info">'
                                    +'<div class="task-head-title">Assigned To</div>'
                                    +'<div class="task-assignee">'+data.profiler_name+'</div>'
                                +'</div>'
                                +'<span onclick="delete_assigne('+project+','+task+')" class="remove-icon">'
                                    +'<i class="fa fa-close"></i>'
                                +'</span>';

                            $('.assignee-info').html(html);
                            var htmls='<div class="task-information"><span class="task-info-line"><a class="task-user" href="#">'+data.activity_username+'</a> <span class="task-info-subject">'+data.activity+'</span></span><div class="task-time">'+data.activity_date+'</div></div>'
                            $('.chats').append(htmls);

                            var htmlss='<span class="action-circle large" title="'+data.profiler_name+'">'
                                        +'<div class="avatar">'
                                        +'<img src="'+data.profile_img+'" alt="">'
                                       +'</div>'
                                       +'</span>'

                                      $('#assign_tasks'+task).html(htmlss);*/
    
                            $('#assign_modl').modal('hide');
                            //~ window.location.reload();
                        }

                        if(type=='Due')
                        {
                            var html='<div class="due-icon">'
                                        +'<span>'
                                                +'<i class="material-icons">date_range</i>'
                                        +'</span>'
                                        +'</div>'
                                        +'<div class="due-info">'
                                            +'<div class="task-head-title">Due Date </div>'
                                            +'<div class="due-date">'+data.date+'</div>'
                                        +'</div>'
                                        +'<span onclick="delete_due_date('+project+','+task+')" class="remove-icon">'
                                            +'<i class="fa fa-close"></i>'
                                        +'</span>';
                        
                            $('.task-due-date').html(html);
                            var htmls='<div class="task-information"><span class="task-info-line"><a class="task-user" href="#">'+data.activity_username+'</a> <span class="task-info-subject">'+data.activity+'</span></span><div class="task-time">'+data.activity_date+'</div></div>'
                            $('.chats').append(htmls);  
                            $('#assign_modl').modal('hide');
                        }

                        
                        
                    }

                // here we will handle errors and validation messages
            });

        }
           


           

}

function delete_assigne(project_id,task_id)
{
    $.post(base_url + 'all_tasks/delete_assigne',{'task_id':task_id},function(data){

                    var obj = jQuery.parseJSON(data);

                         if (! obj.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<a data-toggle="ajaxModal" href="'+base_url + 'all_tasks/assign_user/'+project_id+'/'+task_id+'/Assign">'
                                    +'<div class="avatar">'
                                        +'<img src="'+base_url + 'assets/avatar/default_avatar.jpg" alt="">'
                                    +'</div>'
                                    +'<div class="assigned-info">'
                                        +'<div class="task-head-title">Unassigned</div>'
                                        +'<div class="task-assignee"></div>'
                                    +'</div>'
                                   +'</a>';
                        
                            $('.assignee-info').html(html);


                            var htmls='<span class="action-circle large" title="Assign">'
                                        +'<a data-toggle="ajaxModal" href="'+base_url + 'all_tasks/assign_user/'+project_id+'/'+task_id+'/Assign">'
                                        +'<div class="avatar">'
                                        +'<img src="'+base_url + 'assets/avatar/default_avatar.jpg" alt="">'
                                       +'</div>'
                                       +'</a>'
                                      +'</span>'

                                      $('#assign_tasks'+task_id).html(htmls);
                            

                    }


    });
}
function delete_due_date(project_id,task_id)
{
    $.post(base_url + 'all_tasks/delete_due_date',{'task_id':task_id},function(data){

                    var obj = jQuery.parseJSON(data);

                         if (! obj.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<a data-toggle="ajaxModal" href="'+base_url + 'all_tasks/assign_user/'+project_id+'/'+task_id+'/Due">'
                                    +'<div class="due-icon">'
                                        +'<span>'
                                            +'<i class="material-icons">date_range</i>'
                                        +'</span>'
                                    +'</div>'
                                    +'<div class="due-info">'
                                        +'<div class="task-head-title">Due Date </div>'
                                        +'<div class="due-date"></div>'
                                    +'</div>'
                                   +'</a>';
                        
                            $('.task-due-date').html(html);
                            

                    }


    });
}
function delete_task(task_id)
{
    $('#delete_modal').modal('show');
    $('#delete_task_id').val(task_id);
}

function task_description(task_id)
{
    var description =$('#task_description').val();
    if(description!='')
    {
        $.post(base_url + 'all_tasks/description_update',{'description':description,'task_id':task_id},function(data){

        });
    }
}
function task_name(task_id)
{
    var task_name =$('#task_name').val();
    if(task_name!='')
    {
        $.post(base_url + 'all_tasks/task_name_update',{'task_name':task_name,'task_id':task_id},function(data){

        });
    }
}

$(document).on('change','#department_name,#editdepartment_name',function(){
     var department = $(this).val();
     if(department!=''){
         $.post(base_url+'employees/designations',{department:department},function(data){
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>Position</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.id+'">'+val.designation+'</option>';
                    });
                }
                $('#designations,#editdesignations').html(html);
            }
        });
     }else{
        $('#designations').html('<option value="">No results</option>');
     }
     
  })



  $(document).on('change','#company_name',function(){
     var company = $(this).val();
    
     if(company!=''){
         $.post(base_url + 'reports/employees',{company:company},function(data){
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>Employee</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.user_id+'">'+val.fullname+'</option>';
                    });
                }
                $('#employee_name').html(html);  
            }
        });
     }else{
        $('#employee_name').html('<option value="">No results</option>');
     }
     
  })




  $(document).on('change','#designations',function(){
  // $('#designations').change(function(e){
        var dept_id = $('#department_name').val();
        var des_id = $(this).val();
        $.post(base_url+'employees/teamlead_options/',{des_id:des_id,dept_id:dept_id},function(res){
            // console.log(res); return false;
            var leads_name = JSON.parse(res);
            $('#reporting_to').empty();
            $('#reporting_to').append("<option value='' selected disabled='disabled'>Reporter's Name</option>");
            for(i=0; i<leads_name.length; i++) {
                $('#reporting_to').append("<option value="+leads_name[i].id+">"+leads_name[i].username+"</option>");                      
             }
            });
      });
 //Forms
$('#search_forms').keyup(function(){
                
    var input_data = $('#search_forms').val();
    $.ajax({
    type: "POST",
    url:"forms/auto_search",
    data:{search_data:input_data},
    success: function(data) {
        $('#search_content').html(data);

    }
    });
});
 $(document).on("click", ".image_remove", function () {
    var id = $(this).attr("data-id");
    $(".form_image"+id).hide();
    $("#form_file"+id).val('');
});



//Knowledgebase Module Form Submit Validation by Thamayanthi.V
$(document).on('click', '#submit_knowledge', function() {
    $('#knowledge_submit').validate({
        onsubmit: true,
        ignore: [] ,
        rules: {
            category: {
                required: true
            },
            title: {
                required: true
            },
            'topic[]': {
                required: true
            },
            description: {
                required: true
            }
        },
        messages: {
            category: {
                required: 'Category is required'
            },
            title: {
                required: 'Title is required'
            },
            'topic[]': {
                required: 'Topic is required'
            },
            description: {
                required: 'Description is required'
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

//Knowledgebase- Category Module Form Submit Validation by Thamayanthi.V
$(document).on('click', '#submit_category', function() {
    $('#category_submit').validate({
        onsubmit: true,
        ignore: [] ,
        rules: {
            category_name: {
                required: true
            }
        },
        messages: {
            category_name: {
                required: 'Category Name is required'
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

//Knowledgebase- Edit Category Module Form Submit Validation by Thamayanthi.V
$(document).on('click', '#submit_edit_category', function() {
    $('#category_edit_submit').validate({
        onsubmit: true,
        ignore: [] ,
        rules: {
            category_name: {
                required: true
            }
        },
        messages: {
            category_name: {
                required: 'Category Name is required'
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

$('#category_name').on('keyup',function(){ 
    if($('#new_submit_category').attr('disabled', 'disabled')) {
        $('#already_category_name').css('display', 'none');
        $('#new_submit_category').removeAttr('disabled');
    }
});

$('#new_submit_category').on('click', function() {
    var category_name = $('#category_name').val();
    if(category_name != '') {
        $.ajax({
        url: base_url+'Ad_knowledge/newCategory',
        type: 'POST',
        data: {category_name: category_name},
        dataType: 'json',
        success: function(response) {
            console.log(response.success);
            if(response.success == 'exists') {
                $('#already_category_name').css('display', '');
                $('#new_submit_category').attr('disabled', 'disabled');
            } else if(response.success == true) {
                $("#category").append('<option value=' + response.category_id + '>' + response.category_name + '</option>');
                $('#category_name').val('');
                $('#myModal2').modal('hide');
            } else {
                $('#already_category_name').css('display', 'none');
                $('#new_submit_category').removeAttr('disabled');
            }
        },
        error: function(xhr) {
            alert('Errorr: '+JSON.stringify(xhr));
        }
    });
    } else {
        alert('Plese enter category name');
    }
});

