<?php 
session_start();
define("CONST_FILE_PATH", "includes/constants.php");
define("CURRENT_PAGE", "publications");
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

<section class="service style-2 sec-padd2">
    <div class="container"> 
        <div class="row">
            <div class="col-lg-12 col-md-8 col-sm-12">
                <div class="row">
                    <article class="column col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="img-box">
                                <img src="images/service/1.jpg" alt="">
                                <figcaption class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="service-1.html" class="thm-btn thm-tran-bg">read more</a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                            <div class="content center">
                                <h5>Service #1</h5>
                                <a href="service-1.html"><h4>Business Growth</h4></a>
                                <div class="text">
                                    <p>The process of improving some of <br>our an enterprise's success. Business <br>growth can be a achieved.</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="column col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="img-box">
                                <img src="images/service/2.jpg" alt="">
                                <figcaption class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="service-2.html" class="thm-btn thm-tran-bg">read more</a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                            <div class="content center">
                                <h5>Service #2</h5>
                                <a href="service-2.html"><h4>Sustainability</h4></a>
                                <div class="text">
                                    <p>When it comes to sustainability & <br>corporate responsibility, we believe <br>thenormal rules of business.</p>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                    <article class="column col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="img-box">
                                <img src="images/service/3.jpg" alt="">
                                <figcaption class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="service-3.html" class="thm-btn thm-tran-bg">read more</a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                            <div class="content center">
                                <h5>Service #3</h5>
                                <a href="service-3.html"><h4>Performance</h4></a>
                                <div class="text">
                                    <p>In a contract, performance deemed <br> to be the fulfillment of an obligation <br>in a manner that releases.</p>
                                </div>
                            </div>
                        </div>
                    </article>
                    
                    <article class="column col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="img-box">
                                <img src="images/service/4.jpg" alt="">
                                <figcaption class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="service-4.html" class="thm-btn thm-tran-bg">read more</a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                            <div class="content center">
                                <h5>Service #4</h5>
                                <a href="service-5.html"><h4>Organization</h4></a>
                                <div class="text">
                                    <p>We help business improve financial <br>performaance by ensuring the entire <br>organization system is aligned.</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="column col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="img-box">
                                <img src="images/service/5.jpg" alt="">
                                <figcaption class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="service-4.html" class="thm-btn thm-tran-bg">read more</a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                            <div class="content center">
                                <h5>Service #5</h5>
                                <a href="service-4.html"><h4>Advanced Analytics</h4></a>
                                <div class="text">
                                    <p>Advanced Analytics is an unique  <br>add on service offer to The Experts <br> which enables you to discover.</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <article class="column col-md-4 col-sm-6 col-xs-12">
                        <div class="item">
                            <figure class="img-box">
                                <img src="images/service/6.jpg" alt="">
                                <figcaption class="default-overlay-outer">
                                    <div class="inner">
                                        <div class="content-layer">
                                            <a href="service-4.html" class="thm-btn thm-tran-bg">read more</a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                            <div class="content center">
                                <h5>Service #6</h5>
                                <a href="service-6.html"><h4>Customer Insights</h4></a>
                                <div class="text">
                                    <p>Interpretation of trends in human <br> behavors which aims to increase the <br>effectiveness of a product.</p>
                                </div>
                            </div>
                        </div>
                    </article>
                        
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