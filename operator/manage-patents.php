<?php session_start(); ?>
<?php
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$adminObj = new Admin($dbObj); // Create an object of Admin class
$errorArr = array(); //Array of errors
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Patents - Prof. Vladimir Okhmatovski's Research Group</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="images/icons/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <script src="../ckeditor/ckeditor.js" type="text/javascript"></script>
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="assets/js/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div id="wrapper">
        <?php include('includes/top-bar.php'); ?> 
        <!-- /. NAV TOP  -->
        <?php include('includes/side-bar.php'); ?> 
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="messageBox"></div>
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3>All Patents</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="patentlist" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="select-checkbox" id="multi-action-box" /></th>
                                                <th>ID</th>
                                                <th>Patent Title</th>
                                                <th>Application Details</th>
                                                <th>Issuance Date</th>
                                                <th>Patent Image</th>
                                                <th>Actions &nbsp; 
                                                    <button class="btn btn-danger btn-sm multi-delete-admin multi-select" title="Delete Selected"><i class="btn-icon-only icon-trash"> </i></button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="messageBox"></div>
                        <div class="panel panel-info" id="hiddenUpdateForm">
                            <div class="panel-heading">
                                <h3>Add/Edit Patent</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" id="CreatePatent" name="CreatePatent" action="../REST/manage-patents.php"  enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Patent Name:</label>
                                        <div class="controls">
                                            <input type="hidden" id="id" name="id"> <input type="hidden" id="oldFile" name="oldFile" value=""/>
                                            <input type="text" id="name" name="name" placeholder="patent name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="description">Brief Description:</label>
                                        <div class="controls">
                                            <textarea id="description" name="description" placeholder="patent's brief description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="issuanceDate">Issuance Date:</label>
                                        <div class="controls">
                                            <input data-title="issuance date" type="text" placeholder="YYYY/MM/DD" id="issuanceDate" name="issuanceDate" data-original-title="Issuance Date" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="image">Patent Image:</label> <span><strong id="oldFileComment"></strong></span>
                                        <div class="controls">
                                            <input type="file" id="image" name="image"class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="hidden" name="addNewPatent" id="addNewPatent" value="addNewPatent"/>
                                            <button type="submit" class="btn btn-success" id="multi-action-catAddEdit">Add Patent</button> &nbsp; &nbsp;
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="messageBox"></div>
            </div>
             <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="../js/jquery-ui.1.11.4.js"></script>
    <script src="assets/js/common-handler.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js" type="text/javascript"></script>
    <script src="assets/js/gritter/js/jquery.gritter.min.js" type="text/javascript"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/manage-patents.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>