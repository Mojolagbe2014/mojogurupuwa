var dataTable;var currentStatus, featuredStatus;
$(document).ready(function(){
    $( "#datePresented" ).datepicker({ 
        dateFormat: "yy-mm-dd",appendText: "(yyyy-mm-dd)", changeMonth: true, changeYear: true,
        //onClose: function(){ $('#endDate').datepicker( "option", "minDate", new Date($(this).datepicker( "getDate" )) ); }
    });
    
    loadAllRegisteredPresentations();
    function loadAllRegisteredPresentations(){
        dataTable = $('#presentationslist').DataTable( {
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
                url :"../REST/manage-presentations.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchPresentations:'true'},
                error: function(){  // error handling
                        $("#presentationslist-error").html("");
                        $("#presentationslist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#presentationslist_processing").css("display","none");

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
    $('.multi-activate-presentation').click(function(){
        if(confirm("Are you sure you want to change presentation status for selected presentations?")) {
            if($('#multi-action-box').prop("checked") || $('#presentationslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#presentationslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#presentationslist :checkbox:checked').each(function(){
                        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
                        activePresentation($(this).attr('data-id'), $(this).attr('data-status'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-delete-presentation').click(function(){
        if(confirm("Are you sure you want to delete selected presentations?")) {
            if($('#multi-action-box').prop("checked") || $('#presentationslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#presentationslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#presentationslist :checkbox:checked').each(function(){
                        deletePresentation($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-featured-presentation').click(function(){
        if(confirm("Are you sure you want to change presentation classes of selected presentations?")) {
            if($('#multi-action-box').prop("checked") || $('#presentationslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#presentationslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#presentationslist :checkbox:checked').each(function(){
                        makeFeaturedPresentation($(this).attr('data-id'), $(this).attr('data-featured'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });    
    
    $(document).on('click', '.activate-presentation', function() {
        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
        if(confirm("Are you sure you want to "+currentStatus+" this presentation? Presentation Name: '"+$(this).attr('data-name')+"'")) activePresentation($(this).attr('data-id'),$(this).attr('data-status'));
    });
    $(document).on('click', '.delete-presentation', function() {
        if(confirm("Are you sure you want to delete this presentation ["+$(this).attr('data-name')+"]? Presentation media ['"+$(this).attr('data-media')+"'] will be deleted too.")) deletePresentation($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
    });
    $(document).on('click', '.edit-presentation', function() {
        if(confirm("Are you sure you want to edit this presentation ["+$(this).attr('data-name')+"] details?")) editPresentation($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-organizer'), $(this).attr('data-location'), $(this).attr('data-date-presented'), $(this).find('span#JQDTdescriptionholder').html(), $(this).attr('data-media'), $(this).attr('data-image'));
    });
    $(document).on('click', '.make-featured-presentation', function() {
        featuredStatus = 'Made Featured Presentation'; if(parseInt($(this).attr('data-featured')) == 1) featuredStatus = "Removed Featured Presentation";
        if(confirm("Are you sure you want to make this presentation ["+$(this).attr('data-name')+"] "+featuredStatus.replace('Made', '')+"?")) makeFeaturedPresentation($(this).attr('data-id'), $(this).attr('data-featured'));
    });
    
    function deletePresentation(id, media, image){
        $.ajax({
            url: "../REST/manage-presentations.php",
            type: 'POST',
            data: {deleteThisPresentation: 'true', id:id, media: media, image:image},
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
    
    function activePresentation(id, status){
        $.ajax({
            url: "../REST/manage-presentations.php",
            type: 'GET',
            data: {activePresentation: 'true', id:id, status:status},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Presentation Successfully '+currentStatus+'d! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Presentation Activation Failed. '+data.msg+'</div>');
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
    
    function makeFeaturedPresentation(id, featured){
        $.ajax({
            url: "../REST/manage-presentations.php",
            type: 'GET',
            data: {makeFeaturedPresentation: 'true', id:id, featured:featured},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Presentation Successfully '+featuredStatus+'! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Presentation Featuring Failed. '+data.msg+'</div>');
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
    
    function editPresentation(id, name, organizer, location, datePresented, description, media, image){//,
        var formVar = {id:id, name:name, organizer:organizer, location:location, datePresented:datePresented, description:description, media:media, image:image };
        $.each(formVar, function(key, value) { 
            if(key == 'media') { $('form #oldFile').val(value); $('form #oldFileComment').text(value).css('color','red');} 
            else if(key == 'image') { $('form #oldImage').val(value); $('form #oldImageComment').html('<img src="../media/presentation-image/'+value+'" style="width:50px;height:50px; margin:5px">');}
            else $('form #'+key).val(value);  
        });
        $('#hiddenUpdateForm').removeClass('hidden');
        $(document).scrollTo('div#hiddenUpdateForm');
        CKEDITOR.instances['description'].setData(description);
        
        $('#cancelEdit').click(function(){ $("#hiddenUpdateForm").addClass('hidden'); });
    }
    $("form#UpdatePresentation").submit(function(e){ 
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
                    $("form#UpdatePresentation")[0].reset();
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