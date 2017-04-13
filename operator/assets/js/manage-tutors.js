var dataTable, currentStatus;
$(document).ready(function(){ 
    loadAllRegisteredTutors();
    function loadAllRegisteredTutors(){
        dataTable = $('#tutorslist').DataTable( {
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   [0, 2]
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]],
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "ajax":{
                url :"../REST/manage-tutors.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchTutors:'true'},
                error: function(){  // error handling
                        $("#tutorslist-error").html("");
                        $("#tutorslist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#tutorslist_processing").css("display","none");

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
    $('.multi-activate-tutor').click(function(){
        if(confirm("Are you sure you want to change tutor status for selected tutor(s)?")) {
            if($('#multi-action-box').prop("checked") || $('#tutorslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#tutorslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#tutorslist :checkbox:checked').each(function(){
                        currentStatus = 'Activate'; if(parseInt($(this).attr('data-visible')) === 1) currentStatus = "De-activate";
                        activateTutor($(this).attr('data-id'),$(this).attr('data-visible'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-delete-tutor').click(function(){
        if(confirm("Are you sure you want to delete selected tutors?")) {
            if($('#multi-action-box').prop("checked") || $('#tutorslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#tutorslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#tutorslist :checkbox:checked').each(function(){
                        deleteTutor($(this).attr('data-id'),$(this).attr('data-picture'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    
    $(document).on('click', '.activate-tutor', function() {
        currentStatus = 'Activate'; if(parseInt($(this).attr('data-visible')) === 1) currentStatus = "De-activate";
        if(confirm("Are you sure you want to "+currentStatus+" this tutor? Tutor Name: '"+$(this).attr('data-name')+"'")) activateTutor($(this).attr('data-id'),$(this).attr('data-visible'));
    });
    $(document).on('click', '.delete-tutor', function() {
        if(confirm("Are you sure you want to delete this tutor ["+$(this).attr('data-name')+"]? Tutor picture ['"+$(this).attr('data-picture')+"'] will be deleted too.")) deleteTutor($(this).attr('data-id'),$(this).attr('data-picture'));
    });
    $(document).on('click', '.edit-tutor', function() {
        if(confirm("Are you sure you want to edit this tutor ["+$(this).attr('data-name')+"] details?")) editTutor($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-qualification'), $(this).attr('data-field'), $(this).find('span#JQDTbioholder').html(), $(this).attr('data-picture'), $(this).attr('data-email'), $(this).attr('data-username'), $(this).attr('data-website'));
    });
    
    function deleteTutor(id, picture){
        $.ajax({
            url: "../REST/manage-tutors.php",
            type: 'POST',
            data: {deleteThisTutor: 'true', id:id, picture: picture},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+' </div>');
                }
                else if(data.status === 0 || data.status === 2 || data.status === 3 || data.status === 4){
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
    
    function activateTutor(id, status){
        $.ajax({
            url: "../REST/manage-tutors.php",
            type: 'GET',
            data: {activateTutor: 'true', id:id, visible:status},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Tutor Successfully '+currentStatus+'d! </div>');
                }
                else if(data.status === 0 || data.status === 2 || data.status === 3 || data.status === 4){
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Tutor Activation Failed. '+data.msg+'</div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Tutor Activation Failed. '+data+'</div>');
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
    
    function editTutor(id, name, qualification, field, bio, picture, email, userName, website){//,
        var formVar = {id:id, name:name, qualification:qualification, field:field, bio:bio, picture:picture, email:email, userName:userName, website:website};
        $.each(formVar, function(key, value) { 
            if(key == 'picture') { $('form #oldPicture').val(value); $('form #oldPictureComment').text(value).css('color','red');} 
            else $('form #'+key).val(value);  
        });
        $('#hiddenUpdateForm').removeClass('hidden');
        $(document).scrollTo('div#hiddenUpdateForm');
        CKEDITOR.instances['bio'].setData(bio);
        
        $('#cancelEdit').click(function(){ $("#hiddenUpdateForm").addClass('hidden'); });
    }
    $("form#UpdateTutor").submit(function(e){ 
        e.stopPropagation(); 
        e.preventDefault();
        $(document).scrollTo('div.panel h3');
        var formData = new FormData($(this)[0]);
        formData.append('bio', CKEDITOR.instances['bio'].getData());
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
                }
                else if(data.status === 2 || data.status === 3 || data.status ===0 ) $("#messageBox").html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+'</div>');
                else $("#messageBox, .messageBox").html('<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data+'</div>');
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