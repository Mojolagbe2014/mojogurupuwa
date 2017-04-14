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
        url: "../REST/fetch-sponsors.php",
        type: 'POST',
        cache: false,
        success : function(data, status) {
            $('#sponsor').empty();
            if(data.status === 0 ){ 
                $("#messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Sponsor loading error. '+data.msg+'</div>');
            }
            if(data.status === 2 ){ 
                $("#messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>No sponsor available</div>');
                 $('#sponsor').append('<option value="">-- No sponsor available --</option>');
            }
            else if(data.status ===1 && data.info.length > 0){
                $('#sponsor').append('<option value="">-- Select a sponsor.. --</option>');
                $.each(data.info, function(i, item) {
                    $('#sponsor').append('<option value="'+item.id+'">'+item.name+'</option>');
                });
            } 

        }
    });
    
    loadAllRegisteredProjects();
    function loadAllRegisteredProjects(){
        dataTable = $('#projectslist').DataTable( {
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
                url :"../REST/manage-projects.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchProjects:'true'},
                error: function(){  // error handling
                        $("#projectslist-error").html("");
                        $("#projectslist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#projectslist_processing").css("display","none");

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
    $('.multi-activate-project').click(function(){
        if(confirm("Are you sure you want to change project status for selected projects?")) {
            if($('#multi-action-box').prop("checked") || $('#projectslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#projectslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#projectslist :checkbox:checked').each(function(){
                        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
                        activeProject($(this).attr('data-id'), $(this).attr('data-status'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-delete-project').click(function(){
        if(confirm("Are you sure you want to delete selected projects?")) {
            if($('#multi-action-box').prop("checked") || $('#projectslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#projectslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#projectslist :checkbox:checked').each(function(){
                        deleteProject($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-featured-project').click(function(){
        if(confirm("Are you sure you want to change project homepage visibility of selected projects?")) {
            if($('#multi-action-box').prop("checked") || $('#projectslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#projectslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#projectslist :checkbox:checked').each(function(){
                        makeFeaturedProject($(this).attr('data-id'), $(this).attr('data-featured'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });    
    
    $(document).on('click', '.activate-project', function() {
        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
        if(confirm("Are you sure you want to "+currentStatus+" this project? Project Name: '"+$(this).attr('data-name')+"'")) activeProject($(this).attr('data-id'),$(this).attr('data-status'));
    });
    $(document).on('click', '.delete-project', function() {
        if(confirm("Are you sure you want to delete this project ["+$(this).attr('data-name')+"]? Project media ['"+$(this).attr('data-media')+"'] will be deleted too.")) deleteProject($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
    });
    $(document).on('click', '.edit-project', function() {
        if(confirm("Are you sure you want to edit this project ["+$(this).attr('data-name')+"] details?")) editProject($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-is-completed'), $(this).attr('data-sponsor'), $(this).attr('data-start-date'), $(this).attr('data-end-date'), $(this).find('span#JQDTdescriptionholder').html(), $(this).attr('data-media'), $(this).attr('data-image'));
    });
    $(document).on('click', '.make-featured-project', function() {
        featuredStatus = 'Made Featured Project'; if(parseInt($(this).attr('data-featured')) == 1) featuredStatus = "Remove Featured Project";
        if(confirm("Are you sure you want to make this project ["+$(this).attr('data-name')+"] "+featuredStatus.replace('Made', '')+"?")) makeFeaturedProject($(this).attr('data-id'), $(this).attr('data-featured'));
    });
    
    function deleteProject(id, media, image){
        $.ajax({
            url: "../REST/manage-projects.php",
            type: 'POST',
            data: {deleteThisProject: 'true', id:id, media: media, image:image},
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
    
    function activeProject(id, status){
        $.ajax({
            url: "../REST/manage-projects.php",
            type: 'GET',
            data: {activeProject: 'true', id:id, status:status},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Project Successfully '+currentStatus+'d! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Project Activation Failed. '+data.msg+'</div>');
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
    
    function makeFeaturedProject(id, featured){
        $.ajax({
            url: "../REST/manage-projects.php",
            type: 'GET',
            data: {makeFeaturedProject: 'true', id:id, featured:featured},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Project Successfully '+featuredStatus+'! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Project Featuring Failed. '+data.msg+'</div>');
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
    
    function editProject(id, name, isCompleted, sponsor, startDate, endDate, description, media, image){//,
        var formVar = {id:id, name:name, isCompleted:isCompleted, sponsor:sponsor, startDate:startDate, endDate:endDate, description:description, media:media, image:image };
        $.each(formVar, function(key, value) { 
            if(key == 'media') { $('form #oldFile').val(value); $('form #oldFileComment').text(value).css('color','red');} 
            else if(key == 'image') { $('form #oldImage').val(value); $('form #oldImageComment').html('<img src="../media/project-image/'+value+'" style="width:50px;height:50px; margin:5px">');}
            else $('form #'+key).val(value);  
        });
        $('#hiddenUpdateForm').removeClass('hidden');
        $(document).scrollTo('div#hiddenUpdateForm');
        CKEDITOR.instances['description'].setData(description);
        
        $('#cancelEdit').click(function(){ $("#hiddenUpdateForm").addClass('hidden'); });
    }
    $("form#UpdateProject").submit(function(e){ 
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
                    $("form#UpdateProject")[0].reset();
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