<?php 
session_start();
define("CONST_FILE_PATH", "includes/constants.php");
define("CURRENT_PAGE", "home");
require('classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$thisPage->dbObj = $dbObj;
$projectObj = new Project($dbObj);
$publicationObj = new Publication($dbObj);
$categoryObj = new PublicationCategory($dbObj);
$clientObj = new Sponsor($dbObj);
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
  
<?php include('includes/homepage-slider.php'); ?>

<section class="whychoos-us sec-padd2">
    <div class="container">
        
        <div class="section-title center">
            <h2>Welcome..</h2>
            <div class="text">
                <p><?php echo WELCOME_MESSAGE; ?></p>
            </div>
        </div>
            
        <div class="row clearfix">
            <?php 
            $siteWidgetTits = array('Our Experience', 'Group Members', 'Products &amp; Projects');
            $siteWidgetIcons = array('graphic2', 'people', 'computer');
            $siteWidgetItems = array('HOMEPAGE_TEXT_OUR_EXPERIENCE','HOMEPAGE_TEXT_GROUP_MEMBER','HOMEPAGE_TEXT_PRODUCT');
            $widgetCount = 0;
            foreach($siteWidgetItems as $siteWidgetItem){
            ?>
            <!--Featured Service -->
            <article class="column col-md-4 col-sm-6 col-xs-12">
                <div class="item">
                    <div class="icon_box center">
                        <span class="icon-<?php echo $siteWidgetIcons[$widgetCount]; ?>"></span>
                    </div>
                    <div class="center">
                        <a href="#" class="center"><h4><?php echo $siteWidgetTits[$widgetCount]; ?></h4></a>
                    </div>
                    <div class="text center">
                        <p><?php echo Setting::getValue($dbObj, $siteWidgetItem) ? trim(stripcslashes(strip_tags(Setting::getValue($dbObj, $siteWidgetItem)))) : ''; ?></p>
                    </div>
                    <div class="count">01</div>
                </div>
            </article>
            <?php $widgetCount++; } ?>
        </div>
            
    </div>
</section>

<section class="service sec-padd2">
    <div class="container">
        
        <div class="section-title center">
            <h2>Our Publications</h2>
        </div>
        
        <div class="service_carousel">
            <?php 
            foreach($publicationObj->fetchRaw("*", "status=1 AND featured = 1 ", " RAND() LIMIT 15")as $publication) { 
                $dateParam = explode('-', $publication['date_published']);
                $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                $thumb = new ThumbNail("media/publication-image/".$publication['image'], 260, 160); 
                $pubLink = SITE_URL."publication/". $publication['id']."/".StringManipulator::slugify($publication['name']);
            ?>
            <!--Featured Publication -->
            <article class="single-column">
                <div class="item">
                    <figure class="img-box">
                        <img src="<?php echo $thumb; ?>" style="width:260px; height: 160px;" alt="<?php echo $publication['name']; ?>">
                        <figcaption class="default-overlay-outer">
                            <div class="inner">
                                <div class="content-layer">
                                    <a href="<?php echo $pubLink; ?>/" class="thm-btn thm-tran-bg">read more</a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="content center">
                        <h5><?php echo $dateParam[0]."/".$dateParam[1]; ?></h5>
                        <a href="<?php echo $pubLink; ?>/"><h4 style="font-size: 14px; font-weight: 100;"><?php echo $publication['name']; ?></h4></a>
                        <div class="text">
                            <p><?php echo PublicationCategory::getName($dbObj, $publication['category']); ?></p>
                        </div>
                    </div>
                </div>
            </article>
            <?php } ?>
        </div>
            
    </div>
</section>

<section class="fact-counter sec-padd" style="background-image: url(<?php echo SITE_URL; ?>images/background/4.jpg);">
    <div class="container">
        <div class="row clearfix">
            <div class="counter-outer clearfix">
                <!--Column-->
                <article class="column counter-column col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-duration="0ms">
                    <div class="item">
                        <div class="count-outer"><span class="count-text" data-speed="3000" data-stop="<?php echo Member::getRawCount($dbObj, " graduated='0' "); ?>">0</span></div>
                        <h4 class="counter-title">Current Members</h4>
                        <div class="icon"><i class="icon-people3"></i></div>
                    </div>
                        
                </article>
                
                <!--Column-->
                <article class="column counter-column col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-duration="0ms">
                    <div class="item">
                        <div class="count-outer"><span class="count-text" data-speed="3000" data-stop="<?php echo Project::getRawCount($dbObj); ?>">0</span></div>
                        <h4 class="counter-title">Successful Projects</h4>
                        <div class="icon"><i class="icon-technology3"></i></div>
                    </div>
                </article>
                
                <!--Column-->
                <article class="column counter-column col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-duration="0ms">
                    <div class="item">
                        <div class="count-outer"><span class="count-text" data-speed="3000" data-stop="<?php echo Publication::getRawCount($dbObj); ?>">0</span></div>
                        <h4 class="counter-title">All Publications</h4>
                        <div class="icon"><i class="icon-sports"></i></div>
                    </div>
                </article>
                
                <!--Column-->
                <article class="column counter-column col-md-3 col-sm-6 col-xs-12 wow fadeIn" data-wow-duration="0ms">
                    <div class="item">
                        <div class="count-outer"><span class="count-text" data-speed="3000" data-stop="<?php echo Patent::getRawCount($dbObj); ?>">0</span></div>
                        <h4 class="counter-title">Patents</h4>
                        <div class="icon"><i class="icon-square2"></i></div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="feature-service sec-padd2">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12 column style-1">
                <div class="section-title">
                    <h3>About Experts</h3>
                </div>
                <figure class="img-box">
                    <a href="#"><img src="images/resource/1.jpg" alt=""></a>
                </figure>
                <div class="text">
                    <p>We have built an enviable reputation in the consumer goods, heavy industry, high-tech, manufacturing, medical, recreational vehicle, and our transportation sectors. multidisciplinary team of experts.</p>
                </div>
                <div class="link"><a href="#" class="thm-btn-tr style-2">Know More</a></div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 column style-2">
                <div class="section-title">
                    <h2>What We Do</h2>
                </div>
                <div class="content">
                    <div class="text">
                        <p>We are experts in this field with over 100 years <br>experience. What that means is you are going to <br>get right solution. please find our services.</p>
                    </div>
                    <ul class="list">
                        <li><a href="service-1.html"><i class="fa fa-check-circle-o"></i>Business Growth</a></li>
                        <li><a href="service-2.html"><i class="fa fa-check-circle-o"></i>Sustainability</a></li>
                        <li><a href="service-3.html"><i class="fa fa-check-circle-o"></i>Performance</a></li>
                        <li><a href="service-4.html"><i class="fa fa-check-circle-o"></i>Advanced Analytics</a></li>
                        <li><a href="service-5.html"><i class="fa fa-check-circle-o"></i>Customer Insights</a></li>
                        <li><a href="service-6.html"><i class="fa fa-check-circle-o"></i>Organization</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 column style-3">
                <div class="section-title">
                    <h2>Business Growth</h2>
                </div>
                <div class="graph">
                    <figure class="graph-img"><img src="images/resource/graph1.jpg" alt=""></figure>
                    <form action="#" class="default-form">
                        <div class="select-box">
                            <select class="text-capitalize selectpicker form-control required" name="form_subject" data-style="g-select" data-width="100%">
                                <option>Last 6 Months</option>
                                <option>Last 7 Months</option>
                                <option>Last 8 Months</option>
                                <option>Last 9 Months</option>
                            </select>
                        </div>
                    </form>
                        
                </div>
            </div>
            
        </div>
    </div>
</section>

<div class="container"><div class="border-bottom"></div></div>

<section class="testimonials-section sec-padd">
    <div class="container">
        <div class="section-title center">
            <h2>testimonials</h2>
        </div> 
        
        <!--Slider-->      
        <div class="testimonials-slider column-carousel three-column">
            
            <!--Slide-->
            <article class="slide-item">
                <div class="quote"><span class="icon-left"></span></div>
                <div class="author">
                    <div class="img-box">
                        <a href="#"><img src="images/resource/thumb1.png" alt=""></a>
                    </div>
                    <h4>Jenifer Hearly</h4>
                    <a href="#"><p>Newyork</p></a>
                    <div class="rating"><span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span></div>
                </div>
                
                <div class="slide-text">
                    <p>Fortune has helped us to just have a better handle on everything in our business – to actually make decisions and move forward to grow.</p>
                </div>
            </article>
            
            <!--Slide-->
            <article class="slide-item">
                <div class="quote"><span class="icon-left"></span></div>
                <div class="author">
                    <div class="img-box">
                        <a href="#"><img src="images/resource/thumb2.png" alt=""></a>
                    </div>
                    <h4>Mitchel Harward</h4>
                    <a href="#"><p>San Fransisco</p></a>
                    <div class="rating"><span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span></div>
                </div>
                
                <div class="slide-text">
                    <p>They bring a wealth of knowledge as well as a personal touch so often missing from other firms, helped us to just have better handle on everything.</p>
                </div>
            </article>
            
            <!--Slide-->
            <article class="slide-item">
                <div class="quote"><span class="icon-left"></span></div>
                <div class="author">
                    <div class="img-box">
                        <a href="#"><img src="images/resource/thumb3.png" alt=""></a>
                    </div>
                    <h4>Beally Russel</h4>
                    <a href="#"><p>Newyork</p></a>
                    <div class="rating"><span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span> <span class="fa fa-star"></span></div>
                </div>
                
                <div class="slide-text">
                    <p>It involves an examination of operations which allows their team discuss the art of the possible. They bring a wealth of knowledge, we believe fortune.</p>
                </div>
            </article>

     
        </div>
        
    </div>    
</section>

<section class="latest-project sec-padd">
    <div class="container">
        <div class="section-title">
            <h2>Latest Projects</h2>
        </div>
        <div class="latest-project-carousel">
            <div class="item">
                <div class="single-project">
                    <figure class="imghvr-shutter-in-out-horiz">
                        <img src="images/resource/4.jpg" alt="Awesome Image"/>
                        <figcaption>
                            <div class="content">
                                <a href="project-single.html"><h4>Latest Technology</h4></a>
                                <p>Consulting</p>
                            </div> 
                        </figcaption>
                    </figure>
                </div>
            </div>
            <div class="item">
                <div class="single-latest-project-carousel">
                    <div class="single-project">
                    <figure class="imghvr-shutter-in-out-horiz">
                        <img src="images/resource/5.jpg" alt="Awesome Image"/>
                        <figcaption>
                            <div class="content">
                                <a href="project-single.html"><h4>Audit & Assurance</h4></a>
                                <p>Financial</p>
                            </div>    
                        </figcaption>
                    </figure>
                </div>
                </div><!-- /.single-latest-project-carousel -->
            </div>
            <div class="item">
                <div class="single-latest-project-carousel">
                    <div class="single-project">
                    <figure class="imghvr-shutter-in-out-horiz">
                        <img src="images/resource/6.jpg" alt="Awesome Image"/>
                        <figcaption>
                            <div class="content">
                                <a href="project-single.html"><h4>Business Growth</h4></a>
                                <p>Growth</p>
                            </div> 
                        </figcaption>
                    </figure>
                </div>
                </div>
            </div>
            <div class="item">
                <div class="single-latest-project-carousel">
                    <div class="single-project">
                    <figure class="imghvr-shutter-in-out-horiz">
                        <img src="images/resource/7.jpg" alt="Awesome Image"/>
                        <figcaption>
                            <div class="content">
                                <a href="project-single.html"><h4>Transporation Service</h4></a>
                                <p>Marketing</p>
                            </div> 
                        </figcaption>
                    </figure>
                </div>
                </div>
            </div>            
        </div>
                
    </div>
</section>

<section class="blog-section sec-padd2">
    <div class="container">
        <div class="section-title center">
            <h2>latest news</h2>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="default-blog-news wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    <figure class="img-holder">
                        <a href="blog-details.html"><img src="images/blog/1.jpg" alt="News"></a>
                        <figcaption class="overlay">
                            <div class="box">
                                <div class="content">
                                    <a href="blog-details.html"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="lower-content">
                        <div class="date">21 <br>April</div>
                        <h4><a href="blog-details.html">Retail banks wake up to digital</a></h4>
                        <div class="post-meta">by fletcher  |  14 Comments</div>
                        <div class="text">
                            <p>know how to pursue pleasure rationally seds encounter consequences.</p>               
                        </div>
                        <div class="link">
                            <a href="blog-details.html" class="default_link">Read More <i class="fa fa-angle-right"></i></a>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="default-blog-news wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    <figure class="img-holder">
                        <a href="blog-details.html"><img src="images/blog/2.jpg" alt="News"></a>
                        <figcaption class="overlay">
                            <div class="box">
                                <div class="content">
                                    <a href="blog-details.html"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="lower-content">
                        <div class="date">17 <br>June</div>
                        <h4><a href="blog-details.html">Improve your business growth</a></h4>
                        <div class="post-meta">by Richards  |  22 Comments</div>
                        <div class="text">
                            <p>Great pleasure to take a trivial example, which of us undertakes laborious.</p>                            
                        </div>
                        <div class="link">
                            <a href="blog-details.html" class="default_link">Read More <i class="fa fa-angle-right"></i></a>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="default-blog-news wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    <figure class="img-holder">
                        <a href="blog-details.html"><img src="images/blog/3.jpg" alt="News"></a>
                        <figcaption class="overlay">
                            <div class="box">
                                <div class="content">
                                    <a href="blog-details.html"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="lower-content">
                        <div class="date">14 <br>Mar</div>
                        <h4><a href="blog-details.html">Save money for your future.</a></h4>
                        <div class="post-meta">by Vincent  |  16 Comments</div>
                        <div class="text">
                            <p>Pleasure and praising pain was born and I will give you a complete account.</p>                            
                        </div>
                        <div class="link">
                            <a href="blog-details.html" class="default_link">Read More <i class="fa fa-angle-right"></i></a>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</section>

<section class="consultations sec-padd" style="background-image: url(images/background/5.jpg);">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">   
                <div class="contact-info">
                    <div class="section-title">
                        <h3>Contact Details</h3>
                    </div>
                    <div class="text">
                        <p>Please find below contact details <br>and contact us today!</p>
                    </div>
                    <div class="widget-content">
                        <ul class="list-info">
                            <li><span class="fa fa-phone"></span>Phone: +321 456 78 901</li>
                            <li><span class="fa fa-envelope"></span>Email: Info@supportyou.com</li>
                            <li><span class="fa fa-clock-o"></span>Mon to Sat: 9.00am to 16.pm</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="section-title">
                    <h2>Request For Call Back</h2>
                </div>
                <div class="default-form-area">
                    <form id="contact_form" name="contact_form" class="default-form" action="inc/sendmail.php" method="post">
                        <div class="row-10 clearfix">
                            <div class="col-md-4 co-sm-6 col-xs-12 column">
                                <div class="form-group">
                                    <input type="text" name="form_name" class="form-control" value="" placeholder="Name *" required="">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="form_email" class="form-control required email" value="" placeholder="Email Address *" required="">
                                </div>
                                <div class="form-group">
                                    <div class="select-box">
                                        <select class="text-capitalize selectpicker form-control required" name="form_subject" data-style="g-select" data-width="100%">
                                            <option value="0" selected="">Enquiry About</option>
                                            <option value="1">Enquiry Team</option>
                                            <option value="2">Enquiry service</option>
                                        </select>
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="col-md-8 co-sm-6 col-xs-12 column">
                                <div class="form-group style-2">
                                    <textarea name="form_message" class="form-control textarea required" placeholder="Special Request..."></textarea>
                                    <input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value="">
                                    <button class="thm-btn thm-color" type="submit" data-loading-text="Please wait..."><i class="fa fa-paper-plane"></i></button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="clients-section sec-padd">
    <div class="container">
        <div class="section-title">
            <h2>our partners</h2>
        </div>
        <div class="client-carousel owl-carousel owl-theme">

            <div class="item tool_tip" title="media partner">
                <img src="images/clients/1.png" alt="Awesome Image">
            </div>
            <div class="item tool_tip" title="media partner">
                <img src="images/clients/2.png" alt="Awesome Image">
            </div>
            <div class="item tool_tip" title="media partner">
                <img src="images/clients/3.png" alt="Awesome Image">
            </div>
            <div class="item tool_tip" title="media partner">
                <img src="images/clients/4.png" alt="Awesome Image">
            </div>
            <div class="item tool_tip" title="media partner">
                <img src="images/clients/5.png" alt="Awesome Image">
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
    <script src="<?php echo SITE_URL; ?>js/custom.js"></script>

</div>
    
</body>
</html>