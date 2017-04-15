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
    <title>Add Group Member - Prof. Vladimir Okhmatovski's Research Group</title>
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
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3><i class="fa fa-user"></i> Add Group Member</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" id="CreateMember" name="CreateMember" action="../REST/add-member.php" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Full Name:</label>
                                        <div class="controls">
                                            <input type="text" id="name" name="name" placeholder="Member full name" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="program">Program:</label>
                                        <div class="controls">
                                            <input data-title="Program" type="text" placeholder="Program" id="program" name="program" data-original-title="Program" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="field">Specialization:</label>
                                        <div class="controls">
                                            <textarea id="field" name="field" class="form-control" placeholder="Specialization" required="required"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="bio">Bio:</label>
                                        <div class="controls">
                                            <textarea class="span5" id="bio" name="bio" class="form-control" required="required"></textarea>
                                            <script>
                                                CKEDITOR.replace('bio');
                                            </script>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="picture">Picture:</label>
                                        <div class="controls">
                                            <input data-title="member picture" type="file" placeholder="member picture" id="picture" name="picture" data-original-title="Member picture" class="form-control" required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email:</label>
                                        <div class="controls">
                                            <input data-title="member's email" type="email" placeholder="member's email" id="email" name="email" data-original-title="Member's email" class="form-control" required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="website">Website:</label>
                                        <div class="controls">
                                            <input data-title="website" type="url" placeholder="website" id="website" name="website" data-original-title="website" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="hidden" name="addNewMember" id="addNewMember" value="addNewMember"/>
                                            <button type="submit" name="addMember" id="addMember" class="btn btn-danger">Add Member</button> &nbsp; &nbsp;
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
    <script src="assets/js/add-member.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>