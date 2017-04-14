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
    <title>Manage Presentations  - Prof. Vladimir Okhmatovski's Research Group</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="images/icons/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <script src="../ckeditor/ckeditor.js" type="text/javascript"></script>
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="assets/js/gritter/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>
    <style>
        th, td { white-space: nowrap; }
    </style>
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
                    <div class="col-md-12">
                        <div class="messageBox"></div>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3>All Available Presentations</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="presentationslist" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="select-checkbox" id="multi-action-box" /></th>
                                                <th>Actions &nbsp; 
                                                    <button  class="btn btn-success btn-sm multi-activate-presentation multi-select" title="Change selected presentation status"><i class="btn-icon-only icon-check"> </i></button> 
                                                    <button class="btn btn-danger btn-sm multi-delete-presentation multi-select" title="Delete Selected"><i class="btn-icon-only icon-trash"> </i></button>
                                                    <button class="btn btn-primary btn-sm multi-featured-presentation multi-select" title="Change Presentation Classes"><i class="btn-icon-only icon-eye-open"> </i></button>
                                                </th>
                                                <th>ID</th>
                                                <th>Presentation Title</th>
                                                <th>Organizer</th>
                                                <th>Location/Venue</th>
                                                <th>Date Presented</th>
                                                <th>Description</th>
                                                <th>Media</th>
                                                <th>Presentation Image</th>
                                                <th>Date Registered</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-success hidden" id="hiddenUpdateForm">
                            <div class="panel-heading">
                                <h3>Edit Presentation Details</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" id="UpdatePresentation" name="UpdatePresentation" action="../REST/manage-presentations.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Presentation Title:</label>
                                        <div class="controls">
                                            <input type="hidden" id="id" name="id" value=""/> <input type="hidden" id="oldFile" name="oldFile" value=""/>
                                            
                                            <input type="text" id="name" name="name" placeholder="admin full name" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="organizer">Organizer:</label>
                                        <div class="controls">
                                            <input data-title="organizer" type="text" placeholder="organizer" id="organizer" name="organizer" data-original-title="organizer" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="location">Location/Venue:</label>
                                        <div class="controls">
                                            <input data-title="location/venue" type="text" placeholder="location/venue" id="location" name="location" data-original-title="location/venue" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="datePresented">Date Presented:</label>
                                        <div class="controls">
                                            <input data-title="Presentation Date" type="text" placeholder="YYYY/MM/DD" id="datePresented" name="datePresented" data-original-title="Presentation Date" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="description">Description:</label>
                                        <div class="controls">
                                            <textarea class="span5" id="description" name="description" class="form-control"></textarea>
                                            <script>
                                                var ckeditor = CKEDITOR.replace('description');
                                            </script>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="image">Presentation Image <span class="text-danger"><em>(Recommended Size: width=400px, height=400px)</em></span>:</label> <span><strong id="oldImageComment"></strong></span>
                                        <div class="controls">
                                            <input data-title="presentation image" type="file" placeholder="presentation image" value="" id="image" name="image" data-original-title="Presentation image" class="form-control">
                                            <input type="hidden" id="oldImage" name="oldImage" value=""/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="file">Presentation Media (PDF, MS-Word):</label>
                                        <div class="controls">
                                            <input data-title="presentation media" type="file" placeholder="presentation media" value="" id="file" name="file" data-original-title="Presentation media" class="form-control">
                                            <span>Old media: <strong id="oldFileComment"></strong></span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="hidden" name="updateThisPresentation" id="updateThisPresentation" value="updateThisPresentation"/>
                                            <button type="submit" name="submitUpdatePresentation" id="submitUpdatePresentation" class="btn btn-danger">Update Presentation</button> &nbsp; &nbsp;
                                            <button type="button" class="btn btn-info" id="cancelEdit">Cancel</button>
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
    <script src="assets/js/manage-presentations.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>