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
    <title>Add New Presentation  - Prof. Vladimir Okhmatovski's Research Group</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
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
                    <div class="col-md-12">
                        <div class="messageBox"></div>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3>Add New Presentation</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" id="CreatePresentation" name="CreatePresentation" action="../REST/add-presentation.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Presentation Title:</label>
                                        <div class="controls">
                                            <input type="text" id="name" name="name" placeholder="presentation title" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="organizer">Organizer:</label>
                                        <div class="controls">
                                            <input data-title="organizer" type="text" placeholder="organizer" id="organizer" name="organizer" data-original-title="organizer" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="location">Location/Venue:</label>
                                        <div class="controls">
                                            <input data-title="location" type="text" placeholder="location/venue" id="location" name="location" data-original-title="location" class="form-control" required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="datePresented">Presentation Date:</label>
                                        <div class="controls">
                                            <input data-title="Date Presented" type="text" placeholder="YYYY/MM/DD" id="datePresented" name="datePresented" data-original-title="Date Presented" class="form-control" required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="description">Description:</label>
                                        <div class="controls">
                                            <textarea class="span5" id="description" name="description" class="form-control" required="required"></textarea>
                                            <script>
                                                CKEDITOR.replace('description');
                                            </script>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="image">Presentation Image <span class="text-danger"><em>(Recommended Size: width=400px, height=400px)</em></span>:</label>
                                        <div class="controls">
                                            <input data-title="presentation image" type="file" placeholder="presentation image" id="image" name="image" data-original-title="Presentation image" class="form-control" required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="file">Additional Material (Optional):</label>
                                        <div class="controls">
                                            <input data-title="presentation media" type="file" placeholder="presentation media" id="file" name="file" data-original-title="Presentation media" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="hidden" name="addNewPresentation" id="addNewPresentation" value="addNewPresentation"/>
                                            <button type="submit" name="addPresentation" id="addPresentation" class="btn btn-danger">Add Presentation</button> &nbsp; &nbsp;
                                            <button type="reset" class="btn btn-info">Reset Form</button>
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
    <script src="../js/jquery-ui.1.11.4.js" type="text/javascript"></script>
    <script src="assets/js/common-handler.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js" type="text/javascript"></script>
    <script src="assets/js/gritter/js/jquery.gritter.min.js" type="text/javascript"></script>
    <script src="assets/js/add-presentation.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
