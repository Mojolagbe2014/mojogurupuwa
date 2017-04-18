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
                
                if($publication['image']=="") { 
                    $publication['image'] = PublicationCategory::getSingle($dbObj, "image", $publication['category']);
                    $thumb = new ThumbNail("media/category/".$publication['image'], 260, 160); 
                }else{
                    $thumb = new ThumbNail("media/publication-image/".$publication['image'], 260, 160); 
                }
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
                        <a href="<?php echo $pubLink; ?>/" title="<?php echo $publication['name']; ?>"><h4 style="font-size: 14px; font-weight: 100;"><?php echo StringManipulator::trimStringToFullWord(80,$publication['name']); ?>..</h4></a>
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

<section class="latest-project sec-padd">
    <div class="container">
        <div class="section-title center">
            <h2>Group Members</h2>
        </div>
        <div class="latest-project-carousel">
            <?php 
            foreach($memberObj->fetchRaw("*", " visible=1 ", " RAND() LIMIT 15")as $member) {
                $thumb = new ThumbNail("media/member/".$member['picture'], 270, 220); 
                $memLink = SITE_URL."member/". $member['id']."/".StringManipulator::slugify($member['name']);
            ?>
            <div class="item">
                <div class="single-project">
                    <figure class="imghvr-shutter-in-out-horiz">
                        <img src="<?php echo $thumb; ?>" style="width:270px; height: 220px;" alt="<?php echo $member['name']; ?>"/>
                        <figcaption>
                            <div class="content">
                                <a href="<?php echo $memLink; ?>"><h4><?php echo $member['name']; ?></h4></a>
                                <p><?php echo $member['program']; ?> <br/> <?php echo $member['graduated'] == '0' ? "Current Student" : "Graduated"; ?></p>
                            </div> 
                        </figcaption>
                    </figure>
                </div>
            </div>      
            <?php } ?>
        </div>
                
    </div>
</section>

<div class="container"><div class="border-bottom"></div></div>

<section class="testimonials-section sec-padd" style="padding: 25px 0 40px;">
    <div class="container">
        <div class="section-title center">
            <h2>Quotes</h2>
        </div> 
        <!--Slider-->      
        <div class="testimonials-slider column-carousel three-column">
            
            <?php foreach($quoteObj->fetchRaw("*", " 1=1 ", " RAND() LIMIT 10") as $quote) { 
                $thumb = new ThumbNail("media/quote/".$quote['image'], 80, 80); 
                $quoteLink = SITE_URL."quote/". $quote['id']."/".StringManipulator::slugify($quote['author']);
            ?>
            <!--Slide-->
            <article class="slide-item">
                <div class="quote"><span class="icon-left"></span></div>
                <div class="author">
                    <div class="img-box">
                        <a href="#"><img src="<?php echo $thumb; ?>" style="width:80px; height: 80px;" alt=""></a>
                    </div>
                    <h4><?php echo $quote['author']; ?></h4>
                    <a href="#"><p></p></a>
                </div>
                
                <div class="slide-text center">
                    <p><?php echo StringManipulator::trimStringToFullWord(160, strip_tags($quote['content'])); ?></p>
                </div>
            </article>
            <?php } ?>
        </div>
        
    </div>    
</section>

<section class="blog-section sec-padd2">
    <div class="container">
        <div class="section-title center">
            <h2>Latest Projects</h2>
        </div>
        <div class="row">
            <?php 
            foreach($projectObj->fetchRaw("*", "status=1 AND featured = 1 ", " RAND() LIMIT 3")as $project) { 
                $dateParam = explode('-', $project['start_date']);
                $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                $thumb = new ThumbNail("media/project-image/".$project['image'], 370, 200); 
                $pubLink = SITE_URL."project/". $project['id']."/".StringManipulator::slugify($project['name']);
            ?>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="default-blog-news wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    <figure class="img-holder">
                        <a href="<?php echo $pubLink; ?>/"><img src="<?php echo $thumb; ?>" style="width:370px; height: 200px;" alt="<?php echo $project['name']; ?>"></a>
                        <figcaption class="overlay">
                            <div class="box">
                                <div class="content">
                                    <a href="<?php echo $pubLink; ?>/"><i class="fa fa-link" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="lower-content">
                        <div class="date"><?php echo substr($dateObj->format('F'), 0, 3).'<br>'.$dateParam[0]; ?></div>
                        <h4><a href="<?php echo $pubLink; ?>/" title="<?php echo $project['name']; ?>"><?php echo StringManipulator::trimStringToFullWord(80, trim(stripcslashes(strip_tags($project['name'])))); ?>..</a></h4>
                        <div class="post-meta"></div>
                        <div class="text">
                            <p class="text-justify"><?php echo StringManipulator::trimStringToFullWord(100, trim(stripcslashes(strip_tags($project['description'])))); ?>..</p>               
                        </div>
                        <div class="link">
                            <a href="<?php echo $pubLink; ?>/" class="default_link">Read More <i class="fa fa-angle-right"></i></a>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="clients-section sec-padd">
    <div class="container">
        <div class="section-title center">
            <h2>our sponsors / partners</h2>
        </div>
        <div class="client-carousel owl-carousel owl-theme">
            <?php 
            $num =1; $addStyle = '';
            foreach ($sponsorObj->fetchRaw("*", " status = 1 ", " RAND() ") as $partner) {
                $partnerData = array('id' => 'id', 'name' => 'name', 'logo' => 'logo', 'product' => 'product', 'website' => 'website', 'image' => 'image', 'dateAdded' => 'date_added', 'description' => 'description');
                foreach ($partnerData as $key => $value){
                    switch ($key) { 
                        case 'logo': $sponsorObj->$key = 'media/sponsor/'.$partner[$value];break;//
                        case 'image': $sponsorObj->$key = MEDIA_FILES_PATH1.'sponsor-image/'.$partner[$value];break;
                        default     :   $sponsorObj->$key = $partner[$value]; break; 
                    }
                }
                @$sponsorObj->logo = new ThumbNail($sponsorObj->logo, 210, 120);
                $sponLink = SITE_URL."sponsor/". $sponsorObj->id."/".StringManipulator::slugify($sponsorObj->name)."/";
            ?>
            <div class="item tool_tip" title="<?php echo $sponsorObj->name; ?>">
                <a href="<?php echo $sponsorObj->website; ?>" target="_blank" rel="nofollow"><img src="<?php echo $sponsorObj->logo; ?>" style="width:218px; height: 125px;" alt="<?php echo $sponsorObj->name; ?>"></a>
            </div>
            <?php $num++; } ?>
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