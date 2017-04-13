<?php session_start(); ?>
<?php
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$adminObj = new Admin($dbObj); // Create an object of Admin class
$errorArr = array(); //Array of errors
?>
﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Upcoming Events  - Impact Training &amp; Management Consulting</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="images/icons/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <script src="../ckeditor/ckeditor.js" type="text/javascript"></script>
    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="../css/jquery.datetimepicker.css" rel="stylesheet" type="text/css"/>
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
                                <h3>All Upcoming Events</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="eventlist" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="select-checkbox" id="multi-action-box" /></th>
                                                <th>ID</th>
                                                <th>Actions &nbsp; 
                                                    <div style="white-space:nowrap">
                                                    <button  class="btn btn-success btn-sm multi-activate-event multi-select" title="Change selected event status"><i class="btn-icon-only icon-check"> </i></button> 
                                                    <button class="btn btn-danger btn-sm multi-delete-event multi-select" title="Delete Selected"><i class="btn-icon-only icon-trash"> </i></button>
                                                    </div>
                                                </th>
                                                <th>Event</th>
                                                <th>Image</th>
                                                <th>Description</th>
                                                <th>Date/Time</th>
                                                <th>Location</th>
                                                <th>Date Added</th>
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
                                <h3 id="multiHeader">Add Upcoming Event</h3>
                            </div>
                            <div class="panel-body">
                                <form role="form" method="POST" id="CreateEvent" name="CreateEvent" action="../REST/manage-events.php"  enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Event Name:</label>
                                        <div class="controls">
                                            <input type="hidden" id="id" name="id"> 
                                            <textarea id="name" name="name" placeholder="Event/Partner Name" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="image">Event Image:</label>
                                        <div class="controls">
                                            <input type="hidden" name="oldImage" id="oldImage" />
                                            <input data-title="" type="file" placeholder="" id="image" name="image" class="form-control">
                                            <br/><span id="oldImageComment"></span>
                                            <div id="oldImageSource"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="location">Location/Venue:</label>
                                        <div class="controls">
                                            <input type="text" id="location" name="location" placeholder="Event venue and/or location" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="description">Description:</label>
                                        <div class="controls">
                                            <textarea id="description" name="description" class="form-control"></textarea>
                                            <script> CKEDITOR.replace('description');</script>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label" for="dateTime">Event Date/Time:</label>
                                        <div class="controls">
                                            <input type="text" id="dateTime" name="dateTime" placeholder="Date and Time" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="controls">
                                            <input type="hidden" name="addNewEvent" id="addNewEvent" value="addNewEvent"/>
                                            <button type="submit" class="btn btn-success" id="multi-action-eventAddEdit">Add Event</button> &nbsp; &nbsp;
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
    <script src="assets/js/jquery-ui-1.10.1.custom.min.js"></script>
    <script src="assets/js/jquery.datetimepicker.full.min.js"></script>
    <script src="assets/js/common-handler.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js" type="text/javascript"></script>
    <script src="assets/js/gritter/js/jquery.gritter.min.js" type="text/javascript"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/manage-events.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
