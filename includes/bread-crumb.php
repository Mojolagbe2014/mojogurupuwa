<?php 
@$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : SITE_URL;
$refNameArr = explode('/', str_replace(SITE_URL, '', $referer)); 
$refName = $refNameArr[0] ? $refNameArr[0] : 'home';
$refName = strpos($refName,'index') ? 'home' : $refName;
$titl = explode(" - ", $thisPage->title);
?>
<div class="inner-banner text-center">
    <div class="container">
        <div class="box">
            <h3><?php echo $titl[0]; ?></h3>
        </div><!-- /.box -->
        <div class="breadcumb-wrapper">
            <div class="clearfix">
                <div class="pull-left">
                    <ul class="list-inline link-list">
                        <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                        <?php if($refName!='home'){ ?><li><a class="breadcrumbs_item home" href="<?php echo $referer; ?>"><?php echo strip_tags(WebPage::getSingleByName($dbObj, 'title', $refName)); ?></a><span class="breadcrumbs_delimiter"></span><?php } ?>
                        <li><?php echo StringManipulator::trimStringToFullWord(20, $titl[0]); ?></li>
                    </ul>
                </div>
                <div class="pull-right">
<!--                    <a href="#" class="get-qoute"><i class="fa fa-share-alt"></i>share</a>-->
                </div><!-- /.pull-right -->
            </div><!-- /.container -->
        </div>
    </div><!-- /.container -->
</div>             <!-- /.page_top_breadcrumbs -->