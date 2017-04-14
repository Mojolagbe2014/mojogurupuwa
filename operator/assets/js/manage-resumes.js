var dataTable;
$(document).ready(function(){
    $("form#CreateResume").submit(function(e){ 
        e.stopPropagation();
        e.preventDefault();
        $(document).scrollTo('div.panel h3');
        //var formDatas = $(this).serialize();
        var formDatas = new FormData($(this)[0]);
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
                    $("form#CreateResume")[0].reset();
                    $('form#CreateResume #addNewResume').val('addNewResume');
                    $('form#CreateResume #multi-action-catAddEdit').text('Add Resume');
                    $('form#CreateResume #oldFile').val(''); $('form #oldFileComment').html('');
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
    
    loadAllResumes();
    function loadAllResumes(){
        dataTable = $('#resumelist').DataTable( {
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   [0, 4]
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
                url :"../REST/manage-resumes.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchResumes:'true'},
                error: function(){  // error handling
                        $("#resumelist-error").html("");
                        $("#resumelist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#resumelist_processing").css("display","none");

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
    $('.multi-delete-resume').click(function(){
        if(confirm("Are you sure you want to delete selected resume(s)?")) {
            if($('#multi-action-box').prop("checked") || $('#resumelist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#resumelist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#resumelist :checkbox:checked').each(function(){
                        deleteResume($(this).attr('data-id'),$(this).attr('data-document'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    
    $(document).on('click', '.delete-resume', function() {
        if(confirm("Are you sure you want to delete this resume? Resume Name: '"+$(this).attr('data-name')+"'")) deleteResume($(this).attr('data-id'),$(this).attr('data-document'));
    });
    $(document).on('click', '.edit-resume', function() {
        if(confirm("Are you sure you want to edit this resume? Resume Name: '"+$(this).attr('data-name')+"'")) editResume($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-document'));
    });
    
    function deleteResume(id,document){
        $.ajax({
            url: "../REST/manage-resumes.php",
            type: 'POST',
            data: {deleteThisResume: 'true', id:id, document:document},
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
    
    function editResume(id, name, documents){//,
        $(document).scrollTo('div#hiddenUpdateForm');
        $('form #addNewResume').val('editResume');
        $('form #multi-action-catAddEdit').text('Update Resume');
        var formVar = {id:id, name:name, document:documents};
        $.each(formVar, function(key, value) { 
            if(key == 'document') { $('form #oldFile').val(value); $('form #oldFileComment').html('<a href="../media/resume/'+value+'">Download Current Resume</a>');} 
            $('form #'+key).val(value); 
        });
        
    }
});