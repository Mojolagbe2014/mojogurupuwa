<?php 
@$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : SITE_URL;
$refNameArr = explode('/', str_replace(SITE_URL, '', $referer)); 
$refName = $refNameArr[0] ? $refNameArr[0] : 'home';
$refName = strpos($refName,'index') ? 'home' : $refName;
?>
<div class="page_top_wrap page_top_title page_top_breadcrumbs" style="background-image: url(<?php echo SITE_URL; ?>themes/education/skins/education/images/bg_over.png); background-repeat: repeat-x; background-position: center top; background-color:#1eaace;">
                <div class="content_wrap">
                    <div class="breadcrumbs">
                        <a class="breadcrumbs_item home" href="<?php echo SITE_URL; ?>">Home</a><span class="breadcrumbs_delimiter"></span>
                        <?php if($refName!='home'){ ?><a class="breadcrumbs_item home" href="<?php echo $referer; ?>"><?php echo strip_tags(WebPage::getSingleByName($dbObj, 'title', $refName)); ?></a><span class="breadcrumbs_delimiter"></span><?php } ?>
                        <?php $titl = explode(" - ", $thisPage->title); ?>
                        <span class="breadcrumbs_item current"><?php echo StringManipulator::trimStringToFullWord(20, $titl[0]); ?></span>							
                    </div>
                    <h2 class="page_title"><?php echo $titl[0]; ?></h2>
                </div>
            </div>              <!-- /.page_top_breadcrumbs -->