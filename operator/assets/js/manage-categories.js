var dataTable;
$(document).ready(function(){
    $("form#CreateCategory").submit(function(e){ 
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
                    $("form#CreateCategory")[0].reset();
                    $('form #addNewCategory').val('addNewCategory');
                    $('form #multi-action-catAddEdit').text('Add Category');
                    $('form #oldFile').val(''); $('form #oldFileComment').html('');
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
    
    loadAllCoursesCategories();
    function loadAllCoursesCategories(){
        dataTable = $('#coursecategorylist').DataTable( {
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   [0, 5]
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
                url :"../REST/manage-categories.php", //employee-grid-data.php",// json datasource
                type: "post",  // method  , by default get
                data: {fetchCategories:'true'},
                error: function(){  // error handling
                        $("#coursecategorylist-error").html("");
                        $("#coursecategorylist").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                        $("#coursecategorylist_processing").css("display","none");

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
    $('.multi-delete-admin').click(function(){
        if(confirm("Are you sure you want to delete selected admins?")) {
            if($('#multi-action-box').prop("checked") || $('#coursecategorylist :checkbox:checked').length > 0) {
                var atLeastOneIsChecked = $('#coursecategorylist :checkbox:checked').length > 0;
                if (atLeastOneIsChecked !== false) {
                    $('#coursecategorylist :checkbox:checked').each(function(){
                        deleteCourseCategory($(this).attr('data-id'), $(this).attr('data-image'));
                    });
                }
                else alert("No row selected. You must select atleast a row.");
            }
            else alert("No row selected. You must select atleast a row.");
        }
    });
    
    $(document).on('click', '.delete-category', function() {
        if(confirm("Are you sure you want to delete this category? Category Name: '"+$(this).attr('data-name')+"'")) deleteCourseCategory($(this).attr('data-id'),$(this).attr('data-image'));
    });
    $(document).on('click', '.edit-category', function() {
        if(confirm("Are you sure you want to edit this category? Category Name: '"+$(this).attr('data-name')+"'")) editCourseCategory($(this).attr('data-id'), $(this).attr('data-name'), $(this).attr('data-description'), $(this).attr('data-image'));
    });
    
    function deleteCourseCategory(id,image){
        $.ajax({
            url: "../REST/manage-categories.php",
            type: 'POST',
            data: {deleteThisCategory: 'true', id:id, image:image},
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
    
    function editCourseCategory(id, name, description, image){//,
        $('form #addNewCategory').val('editCourseCategory');
        $('form #multi-action-catAddEdit').text('Update Category');
        $(document).scrollTo('form#CreateCategory');
        var formVar = {id:id, name:name, description:description, image:image};
        $.each(formVar, function(key, value) { 
            if(key == 'image') { $('form #oldFile').val(value); $('form #oldFileComment').html('<img src="../media/category/'+value+'" style="width:50px;height:50px; margin:5px">');} 
            $('form #'+key).val(value); 
        });
    }
});