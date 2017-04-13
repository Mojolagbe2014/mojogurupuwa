var dataTable, currentStatus;
$(document).ready(function(){
    $('#dateTime').datetimepicker({ dayOfWeekStart : 1, lang:'en' });
    
    $("form#CreateEvent").submit(function(e){ 
        e.stopPropagation();
        e.preventDefault();
        $(document).scrollTo('div.panel h3');
        var formDatas = new FormData($(this)[0]);
        formDatas.append('description', CKEDITOR.instances['description'].getData());
        var alertType = ["danger", "success", "danger", "error"];
        $.ajax({
            url: $(this).attr("action"),
            type: 'POST',
            data: formDatas,
            cache: false,
            contentType: false,
            async: false,
            success : function(data, status) {
                if(data.status != null && data.status !=1) { 
                    $("#messageBox, .messageBox").html('<div class="alert alert-'+alertType[data.status]+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+' </div>');
                }
                else if(data.status != null && data.status == 1) { 
                    $("#messageBox, .messageBox").html('<div class="alert alert-'+alertType[data.status]+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+'  </div>'); 
                    $("form#CreateEvent")[0].reset();
                    $('form #addNewEvent').val('addNewEvent');
                    $('form #multi-action-eventAddEdit').text('Add Event');
                    $('#multiHeader').html('Add Upcoming Event');
                    $('form #oldImage').val(''); $('form #oldImageSource').html('');
                    CKEDITOR.instances['description'].setData(''); $('form #oldImageComment').text('');
                }
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
    
    loadAllEvents();
    function loadAllEvents(){
        dataTable = $('#eventlist').DataTable( {
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
                url :"../REST/manage-events.php", 
                type: "post",  
                data: {fetchEvents:'true'},
                error: function(){  // error handling
                        $("#eventlist-error").html("");
                        $("#eventlist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#eventlist_processing").css("display","none");

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
    $('.multi-activate-event').click(function(){
        if(confirm("Are you sure you want to change event status for selected events?")) {
            if($('#multi-action-box').prop("checked") || $('#eventlist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#eventlist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#eventlist :checkbox:checked').each(function(){
                        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) === 1) currentStatus = "De-activate";
                        activateEvent($(this).attr('data-id'),$(this).attr('data-status'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    $('.multi-delete-event').click(function(){
        if(confirm("Are you sure you want to delete selected events?")) {
            if($('#multi-action-box').prop("checked") || $('#eventlist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#eventlist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#eventlist :checkbox:checked').each(function(){
                        deleteEvent($(this).attr('data-id'), $(this).attr('data-image'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    
    $(document).on('click', '.activate-event', function() {
        currentStatus = 'Activate'; if(parseInt($(this).attr('data-status')) === 1) currentStatus = "De-activate";
        if(confirm("Are you sure you want to "+currentStatus+" this event? Event Name: '"+$(this).attr('data-name')+"'")) activateEvent($(this).attr('data-id'),$(this).attr('data-status'));
    });
    $(document).on('click', '.delete-event', function() {
        if(confirm("Are you sure you want to delete this event? ("+$(this).attr('data-name')+")")) deleteEvent($(this).attr('data-id'), $(this).attr('data-image'));
    });
    $(document).on('click', '.edit-event', function() {
        if(confirm("Are you sure you want to edit this event?")) editEvent($(this).attr('data-id'), $(this).attr('data-name'), $(this).find('span#JQDTdescriptionholder').html(), $(this).attr('data-location'), $(this).attr('data-image'), $(this).attr('data-date-time'));
    });
    
    function activateEvent(id, status){
        $.ajax({
            url: "../REST/manage-events.php",
            type: 'GET',
            data: {activateEvent: 'true', id:id, status:status},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Event Successfully '+currentStatus+'d! ');
                }
                else if(data.status === 0 || data.status === 2 || data.status === 3 || data.status === 4){
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Event Activation Failed. '+data.msg+'</div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Event Activation Failed. '+data+'</div>');
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
    
    function deleteEvent(id, image){
        $.ajax({
            url: "../REST/manage-events.php",
            type: 'POST',
            data: {deleteThisEvent: 'true', id:id, image:image},
            cache: false,
            success : function(data, status) {
                if(data.status === 1){
                    $("#messageBox, .messageBox").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+' </div>');
                }
                else if(data.status === 0 || data.status === 2 || data.status === 3 || data.status === 4){
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data.msg+'</div>');
                }
                else {
                    $("#messageBox, .messageBox").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>'+data+'</div>');
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
    
    function editEvent(id, name, description, location, image, dateTime){//,
        $('form #addNewEvent').val('editEvent');
        $('form #multi-action-eventAddEdit').text('Update Event');
        $('#multiHeader').html('Edit "<i style="font-weight:normal;">'+name+'</i>"');
        var formVar = {id:id, name:name, location:location, dateTime:dateTime, image:image};
        $.each(formVar, function(key, value) { 
            if(key == 'image') { $('form #oldImage').val(value); $('form #oldImageSource').html('<img src="../media/event/'+value+'" style="width:80px;height:60px;" />'); $('form #oldImageComment').text(value).css('color','red');} 
            else $('form #'+key).val(value);   
        });
        CKEDITOR.instances['description'].setData(description);
        $(document).scrollTo('div#hiddenUpdateForm');
    }
});