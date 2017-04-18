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

//PAGING INFORMATION
$recordPerPage = Setting::getValue($dbObj, 'TOTAL_DISPLAYABLE_PUBLICATIONS') ? trim(strip_tags(Setting::getValue($dbObj, 'TOTAL_DISPLAYABLE_PUBLICATIONS'))) : 100;
$pageNum = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ? filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) : 1;

$condParam = " status=1 ";
$offset = ($pageNum - 1) * $recordPerPage; 
$transactTotal = Publication::getRawCount($dbObj, " $condParam ");//NUM_ROWS($transactQuery)
$totalPages = intval($transactTotal/$recordPerPage);
if(($transactTotal%$recordPerPage)>0){$totalPages +=1;}
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

<section class="service style-2 sec-padd2 four-column">
    <div class="container"> 
        <div class="row">
            <?php 
            foreach($publicationObj->fetchRaw("*", " $condParam ", " date_published DESC LIMIT $recordPerPage OFFSET $offset ")as $publication) { 
                $dateParam = explode('-', $publication['date_published']);
                $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);

                if($publication['image']=="") { 
                    $publication['image'] = PublicationCategory::getSingle($dbObj, "image", $publication['category']);
                    $thumb = new ThumbNail("media/category/".$publication['image'], 260, 160); 
                }else{
                    $thumb = new ThumbNail("media/publication-image/".$publication['image'], 260, 160); 
                }
                $pubLink = SITE_URL."publication/". $publication['id']."/".StringManipulator::slugify($publication['name'])."/";
            ?>
            <article class="column col-md-3 col-sm-6 col-xs-12">
                <div class="item">
                    <figure class="img-box">
                        <img src="<?php echo SITE_URL.$thumb; ?>" style="width:260px; height: 160px;" alt="<?php echo $publication['name']; ?>">
                        <figcaption class="default-overlay-outer">
                            <div class="inner">
                                <div class="content-layer">
                                    <a href="<?php echo $pubLink; ?>" class="thm-btn thm-tran-bg">read more</a>
                                </div>
                            </div>
                        </figcaption>
                    </figure>
                    <div class="content center">
                        <h5><?php echo PublicationCategory::getName($dbObj, $publication['category']); ?></h5>
                        <a href="<?php echo $pubLink; ?>"  title="<?php echo $publication['name']; ?>"><h4><?php echo StringManipulator::trimStringToFullWord(80,$publication['name']); ?>..</h4></a>
                        <div class="text">
                            <p><?php echo StringManipulator::trimStringToFullWord(120, trim(strip_tags($publication['description']))); ?> . . .</p>
                        </div>
                    </div>
                </div>
            </article>
            <?php } ?>
        </div>
        
        <ul class="page_pagination center">
            <li><a href="<?php echo ($pageNum>1) ? SITE_URL.'publications/page/'.($pageNum-1).'/' : 'javascript:;'; ?>" class="tran3s <?php echo ($pageNum>1) ? '' : 'inactive'; ?>"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>
            <li><a href="<?php echo SITE_URL.'publications/page/'.($pageNum); ?>" class="active tran3s"><?php echo ($pageNum); ?></a></li>
            <li><a href="<?php echo SITE_URL.'publications/page/'.($pageNum+1); ?>" class="tran3s <?php echo ($pageNum+1 <= $totalPages) ? '' : 'inactive'; ?>"><?php echo ($pageNum+1); ?></a></li>
            <li><a href="<?php echo ($pageNum < $totalPages) ? SITE_URL.'publications/page/'.($pageNum+1).'/' : 'javascript:;'; ?>" class="tran3s <?php echo ($pageNum < $totalPages) ? '' : 'inactive'; ?>"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
        </ul>
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