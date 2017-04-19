<?php 
session_start();
define("CONST_FILE_PATH", "includes/constants.php");
define("CURRENT_PAGE", "publication-detail");
require('classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$thisPage->dbObj = $dbObj;
$projectObj = new Project($dbObj);
$publicationObj = new Publication($dbObj);
$categoryObj = new PublicationCategory($dbObj);
$sponsorObj = new Sponsor($dbObj);
$memberObj = new Member($dbObj);
$quoteObj = new Quote($dbObj);
$calendar = new Calendar($dbObj);
$videoObj = new Video($dbObj);

include('includes/other-settings.php');
require('includes/page-properties.php');

$errorArr = array(); //Array of errors
$msg = ''; $msgStatus = '';

//get the publication id; if failed redirect to 404 page
$thisPubId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : $thisPage->redirectTo(SITE_URL.'404/');
foreach ($publicationObj->fetchRaw("*", " status = 1 AND id = $thisPubId ") as $publication) {
    $publicationData = array('id' => 'id', 'name' => 'name', 'image' => 'image', 'media' => 'media', 'category' => 'category', 'datePublished' => 'date_published', 'description' => 'description', 'status' => 'status', 'featured' => 'featured');
    foreach ($publicationData as $key => $value){
        switch ($key) { 
            case 'image': $publicationObj->$key = MEDIA_FILES_PATH1.'publication-image/'.$publication[$value];break;
            case 'media': $publicationObj->$key = SITE_URL.'publication/'.$publication[$value];break;
            case 'datePublished': $dateParam = explode('-', $publication[$value]);
                              $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                              $publicationObj->$key = $dateParam[2].' '.$dateObj->format('F').', '.$dateParam[0].'.';
                              break;
            default     :   $publicationObj->$key = $publication[$value]; break; 
        }
    }
}
//Override page-properties
$thisPage->title = StringManipulator::trimStringToFullWord(62, stripslashes(strip_tags($publicationObj->name." - ". WEBSITE_AUTHOR)));
$thisPage->description = StringManipulator::trimStringToFullWord(150, trim(stripslashes(strip_tags($publicationObj->description))));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/meta-tags.php'); ?>
    
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/responsive.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>sweet-alert/sweetalert.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>sweet-alert/twitter.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/additional-style.css">
</head>
<body>

<div class="boxed_wrapper">

<?php include('includes/header.php'); ?>

<?php include('includes/bread-crumb.php'); ?>

<section class="service style-2 sec-padd  four-column">
    <div class="container"> 
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="outer-box">
                    <div class="section-title">
                        <h3>Service Overview</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            
                            <h4 class="title-2">We should provide more flexibility, based on insight, to better meet your needs.</h4><br>
                            <div class="text">
                                <p>To deliver sustainable growth and profitability in a digitally disrupted world, organizations need to enhance their business value. Our services help to integrate sustainable organizations' strategies, operating models, processes and technologies.</p><br>
                                <p>Creating sustainable value and outperforming the has never been more challenging. Empowered customers are defining business on their own terms. Regulatory and workforce changes are further influencingmarkets..</p>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="img-box"><a href="#"><img src="<?php echo SITE_URL; ?>images/service/11.jpg" alt=""></a></div>
                        </div>
                    </div><br><br>
                    <div class="border-bottom"></div>
                    <br><br>
                    <div class="section-title">
                        <h3>Research Analysis</h3>
                    </div>
                    <div class="text">
                        <p>Performing the competition has never been more challenging. Empowered customers are defining business on their own terms. Regulatory and workforce changes are further influencing volatile markets. Pressures from supply chain risk and shifting markets are increasing Creating sustainable value and outperforming the competition.</p>
                    </div>
                    <div class="analysis-chart">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-6">    
                                <div class="single-item center">
                                    <img src="images/service/1.png" alt="">
                                    <h4>Analysis One</h4>
                                </div>
                                
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">    
                                <div class="single-item center">
                                    <img src="images/service/2.png" alt="">
                                    <h4>Analysis Two</h4>
                                </div>
                                
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-6">    
                                <div class="single-item center">
                                    <img src="images/service/3.png" alt="">
                                    <h4>Analysis Three</h4>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                        
                    <div class="analysis-result">
                        <h4>The Result:</h4>
                        <p>To deliver sustainable growth and profitability in a digitally disrupted world, organizations need to enhance their business value. Our services help to integrate sustainable approaches into organizations' strategies, operating models, processes and technologies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    
<?php include('includes/footer.php'); ?>
    <!-- jQuery js -->
    <script src="<?php echo SITE_URL; ?>js/jquery.js"></script>
    <!-- bootstrap js -->
    <script src="<?php echo SITE_URL; ?>js/bootstrap.min.js"></script>
    <!-- jQuery ui js -->
    <script src="<?php echo SITE_URL; ?>js/jquery-ui.js"></script>
    <!-- owl carousel js -->
    <script src="<?php echo SITE_URL; ?>js/owl.carousel.min.js"></script>
    <!-- jQuery validation -->
    <script src="<?php echo SITE_URL; ?>js/jquery.validate.min.js"></script>

    <!-- mixit up -->
    <script src="<?php echo SITE_URL; ?>js/wow.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.mixitup.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.fitvids.js"></script>
    <script src="<?php echo SITE_URL; ?>js/bootstrap-select.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/menuzord.js"></script>

    <!-- revolution slider js -->
    <script src="<?php echo SITE_URL; ?>js/jquery.themepunch.tools.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.themepunch.revolution.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.actions.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.carousel.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.kenburn.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.layeranimation.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.migration.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.navigation.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.parallax.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.slideanims.min.js"></script>
    <script src="<?php echo SITE_URL; ?>js/revolution.extension.video.min.js"></script>

    <!-- fancy box -->
    <script src="<?php echo SITE_URL; ?>js/jquery.fancybox.pack.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.polyglot.language.switcher.js"></script>
    <script src="<?php echo SITE_URL; ?>js/nouislider.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.bootstrap-touchspin.js"></script>
    <script src="<?php echo SITE_URL; ?>js/SmoothScroll.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.appear.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.countTo.js"></script>
    <script src="<?php echo SITE_URL; ?>js/jquery.flexslider.js"></script>
    <script src="<?php echo SITE_URL; ?>js/imagezoom.js"></script> 
    <script id="map-script" src="<?php echo SITE_URL; ?>js/default-map.js"></script>
    <script src="<?php echo SITE_URL; ?>sweet-alert/sweetalert.min.js" type="text/javascript"></script>
    <script src="<?php echo SITE_URL; ?>js/custom.js"></script>

</div>
    
</body>
</html>