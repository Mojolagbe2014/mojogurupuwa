var dataTable;var currentStatus, featuredStatus;
$(document).ready(function(){
    $( "#startDate" ).datepicker({ 
        dateFormat: "yy-mm-dd",appendText: "(yyyy-mm-dd)", changeMonth: true, changeYear: true,
        onClose: function(){ $('#endDate').datepicker( "option", "minDate", new Date($(this).datepicker( "getDate" )) ); }
    });
    $( "#endDate" ).datepicker({ 
        dateFormat: "yy-mm-dd",appendText: "(yyyy-mm-dd)", changeMonth: true, minDate:new Date($('#startDate').val()), changeYear: true
    });
    
    $.ajax({
        url: "../REST/fetch-categories.php",
        type: 'POST',
        cache: false,
        success : function(data, status) {
            $('#category').empty();
            if(data.status === 0 ){ 
                $("#messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Category loading error. '+data.msg+'</div>');
            }
            if(data.status === 2 ){ 
                $("#messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>No category available</div>');
                 $('#category').append('<option value="">-- No category available --</option>');
            }
            else if(data.status ===1 && data.info.length > 0){
                $('#category').append('<option value="">-- Select a category.. --</option>');
                $.each(data.info, function(i, item) {
                    $('#category').append('<option value="'+item.id+'">'+item.name+'</option>');
                });
            } 

        }
    });
    //Fetch all currencies
    $.ajax({
        url: "common-currencies.json",
        type: 'POST',
        cache: false,
        success : function(data, status) {
            $.each(data, function(i, item) {
                $('#currency').append('<option value="'+item.code+'" title="'+item.name+'">'+item.code+' ('+item.symbol+')</option>');
            });
        }
    });
    
    loadAllRegisteredCourses();
    function loadAllRegisteredCourses(){
        dataTable = $('#courseslist').DataTable( {
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   [0, 1]
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "ajax":{
                url :"../REST/manage-courses.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchCourses:'true'},
                error: function(){  // error handling
                        $("#courseslist-error").html("");
                        $("#courseslist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#courseslist_processing").css("display","none");

                }
            }
        } );
        
    }
    
    //Select Multiple Values
    $("#multi-action-box").click(function () {
        var checkAll = $("#multi-action-box").prop('checked');
        if (checkAll) {
            $(".multi-action-box").prop("checked", true);
        } else {
            $(".multi-action-box").prop("checked", false);
        }
    });
    //Handler for multiple selection
    $('.multi-activate-course').click(function(){
        if(confirm("Are you sure you want to change course status for selected courses?")) {
            if($('#multi-action-box').prop("checked") || $('#courseslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#courseslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#courseslist :checkbox:checked').each(function(){
                        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
                        activeCourse($(this).attr('data-id'), $(this).attr('data-status'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-delete-course').click(function(){
        if(confirm("Are you sure you want to delete selected courses?")) {
            if($('#multi-action-box').prop("checked") || $('#courseslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#courseslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#courseslist :checkbox:checked').each(function(){
                        deleteCourse($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-featured-course').click(function(){
        if(confirm("Are you sure you want to change course classes of selected courses?")) {
            if($('#multi-action-box').prop("checked") || $('#courseslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#courseslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#courseslist :checkbox:checked').each(function(){
                        makeFeaturedCourse($(this).attr('data-id'), $(this).attr('data-featured'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });    
    
    $(document).on('click', '.activate-course', function() {
        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
        if(confirm("Are you sure you want to "+currentStatus+" this course? Course Name: '"+$(this).attr('data-name')+"'")) activeCourse($(this).attr('data-id'),$(this).attr('data-status'));
    });
    $(document).on('click', '.delete-course', function() {
        if(confirm("Are you sure you want to delete this course ["+$(this).attr('data-name')+"]? Course media ['"+$(this).attr('data-media')+"'] will be deleted too.")) deleteCourse($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
    });
    $(document).on('click', '.edit-course', function() {
        if(confirm("Are you sure you want to edit this course ["+$(this).attr('data-name')+"] details?")) editCourse($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-short-name'), $(this).attr('data-category'), $(this).attr('data-start-date'), $(this).attr('data-end-date'), $(this).attr('data-code'), $(this).find('span#JQDTdescriptionholder').html(), $(this).attr('data-media'), $(this).attr('data-amount'), $(this).attr('data-image'), $(this).attr('data-currency'));
    });
    $(document).on('click', '.make-featured-course', function() {
        featuredStatus = 'Made Private Sector Course'; if(parseInt($(this).attr('data-featured')) == 1) featuredStatus = "Made Public Sector Course";
        if(confirm("Are you sure you want to make this course ["+$(this).attr('data-name')+"] "+featuredStatus.replace('Made', '')+"?")) makeFeaturedCourse($(this).attr('data-id'), $(this).attr('data-featured'));
    });
    
    function deleteCourse(id, media, image){
        $.ajax({
            url: "../REST/manage-courses.php",
            type: 'POST',
            data: {deleteThisCourse: 'true', id:id, media: media, image:image},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+' </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+'</div>');
                }
                dataTable.ajax.reload();
                $.gritter.add({
                    title: 'Notification!',
                    text: data.msg ? data.msg : data
                });
            },
            error : function(xhr, status) {
                erroMsg = '';
                if(xhr.status===0){ erroMsg = 'There is a problem connecting to internet. Please review your internet connection.'; }
                else if(xhr.status===404){ erroMsg = 'Requested page not found.'; }
                else if(xhr.status===500){ erroMsg = 'Internal Server Error.';}
                else if(status==='parsererror'){ erroMsg = 'Error. Parsing JSON Request failed.'; }
                else if(status==='timeout'){  erroMsg = 'Request Time out.';}
                else { erroMsg = 'Unknow Error.\n'+xhr.responseText;}          
                $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Admin details update failed. '+erroMsg+'</div>');

                $.gritter.add({
                    title: 'Notification!',
                    text: erroMsg
                });
            }
        });
    }
    
    function activeCourse(id, status){
        $.ajax({
            url: "../REST/manage-courses.php",
            type: 'GET',
            data: {activeCourse: 'true', id:id, status:status},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Course Successfully '+currentStatus+'d! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Course Activation Failed. '+data.msg+'</div>');
                }
                dataTable.ajax.reload();
                $.gritter.add({
                    title: 'Notification!',
                    text: data.msg ? data.msg : data
                });
            },
            error : function(xhr, status) {
                erroMsg = '';
                if(xhr.status===0){ erroMsg = 'There is a problem connecting to internet. Please review your internet connection.'; }
                else if(xhr.status===404){ erroMsg = 'Requested page not found.'; }
                else if(xhr.status===500){ erroMsg = 'Internal Server Error.';}
                else if(status==='parsererror'){ erroMsg = 'Error. Parsing JSON Request failed.'; }
                else if(status==='timeout'){  erroMsg = 'Request Time out.';}
                else { erroMsg = 'Unknow Error.\n'+xhr.responseText;}          
                $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Admin details update failed. '+erroMsg+'</div>');

                $.gritter.add({
                    title: 'Notification!',
                    text: erroMsg
                });
            }
        });
    }
    
    function makeFeaturedCourse(id, featured){
        $.ajax({
            url: "../REST/manage-courses.php",
            type: 'GET',
            data: {makeFeaturedCourse: 'true', id:id, featured:featured},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Course Successfully '+featuredStatus+'! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Course Featuring Failed. '+data.msg+'</div>');
                }
                dataTable.ajax.reload();
                $.gritter.add({
                    title: 'Notification!',
                    text: data.msg ? data.msg : data
                });
            },
            error : function(xhr, status) {
                erroMsg = '';
                if(xhr.status===0){ erroMsg = 'There is a problem connecting to internet. Please review your internet connection.'; }
                else if(xhr.status===404){ erroMsg = 'Requested page not found.'; }
                else if(xhr.status===500){ erroMsg = 'Internal Server Error.';}
                else if(status==='parsererror'){ erroMsg = 'Error. Parsing JSON Request failed.'; }
                else if(status==='timeout'){  erroMsg = 'Request Time out.';}
                else { erroMsg = 'Unknow Error.\n'+xhr.responseText;}          
                $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Admin details update failed. '+erroMsg+'</div>');

                $.gritter.add({
                    title: 'Notification!',
                    text: erroMsg
                });
            }
        });
    }
    
    function editCourse(id, name, shortName, category, startDate, endDate, code, description, media, amount, image, currency){//,
        var formVar = {id:id, name:name, shortName:shortName, category:category, startDate:startDate, endDate:endDate, code:code, description:description, media:media, amount:amount, image:image, currency:currency };
        $.each(formVar, function(key, value) { 
            if(key == 'media') { $('form #oldFile').val(value); $('form #oldFileComment').text(value).css('color','red');} 
            else if(key == 'image') { $('form #oldImage').val(value); $('form #oldImageComment').html('<img src="../media/course-image/'+value+'" style="width:50px;height:50px; margin:5px">');}
            else $('form #'+key).val(value);  
        });
        $('#hiddenUpdateForm').removeClass('hidden');
        $(document).scrollTo('div#hiddenUpdateForm');
        CKEDITOR.instances['description'].setData(description);
        
        $('#cancelEdit').click(function(){ $("#hiddenUpdateForm").addClass('hidden'); });
    }
    $("form#UpdateCourse").submit(function(e){ 
            e.stopPropagation(); 
            e.preventDefault();
            $(document).scrollTo('div.panel h3');
            var formData = new FormData($(this)[0]);
            formData.append('description', CKEDITOR.instances['description'].getData());
            var alertType = ["danger", "success", "danger", "error"];
            $.ajax({
            url: $(this).attr("action"),
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            async: false,
            success : function(data, status) {
                $("#hiddenUpdateForm").addClass('hidden');
                if(data.status === 1) {
                    $("#messageBox, .messageBox").html('<div class="alert alert-'+alertType[data.status]+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+' </div>');
                    $("form#UpdateCourse")[0].reset();
                }
                else if(data.status === 2 || data.status === 3 || data.status ===0 ) $("#messageBox, .messageBox").html('<div class="alert alert-'+alertType[data.status]+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+'</div>');
                else $("#messageBox, .messageBox").html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+'</div>');
                dataTable.ajax.reload();
                $.gritter.add({
                    title: 'Notification!',
                    text: data.msg ? data.msg : data
                });
            },
            error : function(xhr, status) {
                erroMsg = '';
                if(xhr.status===0){ erroMsg = 'There is a problem connecting to internet. Please review your internet connection.'; }
                else if(xhr.status===404){ erroMsg = 'Requested page not found.'; }
                else if(xhr.status===500){ erroMsg = 'Internal Server Error.';}
                else if(status==='parsererror'){ erroMsg = 'Error. Parsing JSON Request failed.'; }
                else if(status==='timeout'){  erroMsg = 'Request Time out.';}
                else { erroMsg = 'Unknow Error.\n'+xhr.responseText;}          
                $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Admin details update failed. '+erroMsg+'</div>');

                $.gritter.add({
                    title: 'Notification!',
                    text: erroMsg
                });
            },
            processData: false
        });
            return false;
        });
});