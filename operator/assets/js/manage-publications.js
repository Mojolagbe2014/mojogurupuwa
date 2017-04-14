var dataTable;var currentStatus, featuredStatus;
$(document).ready(function(){
    $( "#datePublished" ).datepicker({ 
        dateFormat: "yy-mm-dd",appendText: "(yyyy-mm-dd)", changeMonth: true, changeYear: true,
       // onClose: function(){ $('#endDate').datepicker( "option", "minDate", new Date($(this).datepicker( "getDate" )) ); }
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
    
    loadAllRegisteredPublications();
    function loadAllRegisteredPublications(){
        dataTable = $('#publicationslist').DataTable( {
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
                url :"../REST/manage-publications.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchPublications:'true'},
                error: function(){  // error handling
                        $("#publicationslist-error").html("");
                        $("#publicationslist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#publicationslist_processing").css("display","none");

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
    $('.multi-activate-publication').click(function(){
        if(confirm("Are you sure you want to change publication status for selected publications?")) {
            if($('#multi-action-box').prop("checked") || $('#publicationslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#publicationslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#publicationslist :checkbox:checked').each(function(){
                        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
                        activePublication($(this).attr('data-id'), $(this).attr('data-status'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-delete-publication').click(function(){
        if(confirm("Are you sure you want to delete selected publications?")) {
            if($('#multi-action-box').prop("checked") || $('#publicationslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#publicationslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#publicationslist :checkbox:checked').each(function(){
                        deletePublication($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-featured-publication').click(function(){
        if(confirm("Are you sure you want to change publication classes of selected publications?")) {
            if($('#multi-action-box').prop("checked") || $('#publicationslist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#publicationslist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#publicationslist :checkbox:checked').each(function(){
                        makeFeaturedPublication($(this).attr('data-id'), $(this).attr('data-featured'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });    
    
    $(document).on('click', '.activate-publication', function() {
        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) == 1) currentStatus = "De-activate";
        if(confirm("Are you sure you want to "+currentStatus+" this publication? Publication Title: '"+$(this).attr('data-name')+"'")) activePublication($(this).attr('data-id'),$(this).attr('data-status'));
    });
    $(document).on('click', '.delete-publication', function() {
        if(confirm("Are you sure you want to delete this publication ["+$(this).attr('data-name')+"]? Publication media ['"+$(this).attr('data-media')+"'] will be deleted too.")) deletePublication($(this).attr('data-id'),$(this).attr('data-media'),$(this).attr('data-image'));
    });
    $(document).on('click', '.edit-publication', function() {
        if(confirm("Are you sure you want to edit this publication ["+$(this).attr('data-name')+"] details?")) editPublication($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-category'), $(this).attr('data-date-published'), $(this).find('span#JQDTdescriptionholder').html(), $(this).attr('data-media'), $(this).attr('data-image'));
    });
    $(document).on('click', '.make-featured-publication', function() {
        featuredStatus = 'Made Featured Publication'; if(parseInt($(this).attr('data-featured')) == 1) featuredStatus = "Remove Featured Publication";
        if(confirm("Are you sure you want to make this publication ["+$(this).attr('data-name')+"] "+featuredStatus.replace('Made', '')+"?")) makeFeaturedPublication($(this).attr('data-id'), $(this).attr('data-featured'));
    });
    
    function deletePublication(id, media, image){
        $.ajax({
            url: "../REST/manage-publications.php",
            type: 'POST',
            data: {deleteThisPublication: 'true', id:id, media: media, image:image},
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
    
    function activePublication(id, status){
        $.ajax({
            url: "../REST/manage-publications.php",
            type: 'GET',
            data: {activePublication: 'true', id:id, status:status},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Publication Successfully '+currentStatus+'d! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Publication Activation Failed. '+data.msg+'</div>');
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
    
    function makeFeaturedPublication(id, featured){
        $.ajax({
            url: "../REST/manage-publications.php",
            type: 'GET',
            data: {makeFeaturedPublication: 'true', id:id, featured:featured},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Publication Successfully '+featuredStatus+'! </div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Publication Featuring Failed. '+data.msg+'</div>');
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
    
    function editPublication(id, name, category, datePublished, description, media, image){//,
        var formVar = {id:id, name:name, category:category, datePublished:datePublished, description:description, media:media, image:image };
        $.each(formVar, function(key, value) { 
            if(key == 'media') { $('form #oldFile').val(value); $('form #oldFileComment').text(value).css('color','red');} 
            else if(key == 'image') { $('form #oldImage').val(value); $('form #oldImageComment').html('<img src="../media/publication-image/'+value+'" style="width:50px;height:50px; margin:5px">');}
            else $('form #'+key).val(value);  
        });
        $('#hiddenUpdateForm').removeClass('hidden');
        $(document).scrollTo('div#hiddenUpdateForm');
        CKEDITOR.instances['description'].setData(description);
        
        $('#cancelEdit').click(function(){ $("#hiddenUpdateForm").addClass('hidden'); });
    }
    $("form#UpdatePublication").submit(function(e){ 
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
                    $("form#UpdatePublication")[0].reset();
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