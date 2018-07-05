<?php
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
// If the user is on a mobile device, redirect them
if(isMobile()){ 
    /*header("Location: http://m.yoursite.com/");*/
    
     echo "<script> window.onload = function() { 
                            $('#myModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show : true
                            }); };</script>";
}
?>
<div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Download App</h4>
            </div>
            <div class="modal-body">
              <p>Down load our app to continue reading in your device</p>
            <button type="submit" onclick="window.location.href='https://play.google.com/store/apps/details?id=com.the14mag.android&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'"  style="color: #fff; background-color: #d9534f; border-color: #d43f3a; padding: 10px;">Download Our App</button><br/>
                <a href='intent://scan/#Intent;scheme=14Mag;package=com.the14mag.android;end'> Open 14Mag </a>
            </div>
            <div class="modal-footer">

            </div>
          </div>

        </div>
  </div>
<div class="footer">
    <div class="container">
        <div class="col-md-4">
            <div class="widget">
                <h2 class="widget-title">14Mag
                    <p style="font-size:0.5em;color:#c90000">Android Applicaiton</p>
                </h2>
                <p style="color:#fff;">Download our Android Application from Playstore.</p>
                <p><a target="_blank" href='https://play.google.com/store/apps/details?id=com.the14mag.android&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img class="img-responsive" style="width:60%;border-width:0px !important;" alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'/></a></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget">
                <h2 class="widget-title">Latest Articles</h2>
                <ul class="widget-latest-posts">
                        <?php $query_getLatArticls = "SELECT * FROM `article` WHERE status = '1' ORDER By  article_id DESC LIMIT 3";
				  $getLatArticls = mysqli_query($dbConn, $query_getLatArticls) or die(mysqli_error($dbConn));
                        if($getLatArticls){
                            while($row_getLatArticls = mysqli_fetch_assoc($getLatArticls)){ ?>
                    <li>
                        <span class="widget-latest-posts-cover">
                            <?php $val = $row_getLatArticls['article_short_title'];
                                                             $val =str_replace(" ","-",$val);
                            ?>
                                <a href="<?php echo $rootURL; ?>article/<?php echo $row_getLatArticls['created_on']; ?>/<?php echo $val; ?>">
                                    <img src="<?php echo $row_getLatArticls['article_image']; ?>" class="img-thumbnail" alt="article title">
                                </a>
                            </span>
                        <h4>
                            <!--Article Title-->
                            <a href="<?php echo $rootURL; ?>article/<?php echo $row_getLatArticls['created_on']; ?>/<?php echo $val; ?>">
                                <?php   $in = $row_getLatArticls['article_title'];
									$out = strlen($in) > 50 ? substr($in,0,50)."..." : $in;
									echo $out; ?>
                            </a>
                            <!--/Article Title-->
                        </h4>
                        <!--Article Posted Date-->
                        <span class="widget-latest-posts-time"><?php if($row_getLatArticls['premium'] == 1){ ?><img src="<?php echo $rootURL; ?>img/premiun.png" style="display: inline; border: 0px; width: 5%;" /><?php } ?>
                            Posted : <?php $dt = strtotime($row_getLatArticls['created_on']);
																							 $pt = date('d F, Y', $dt); 
																							 echo $pt; ?>
                        </span>
                        <!--Article Posted Date-->
                    </li>
                    <?php }
                        } ?>
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <div class="widget">
                <h2 class="widget-title">Follow us</h2>
                <ul class="widget-follow-us">
                    <li><a class="widget-follow-us-facebook" target="_blank" href="https://www.facebook.com/bs.aptinova/">Facebook</a></li>
                    <li><a class="widget-follow-us-linkedin" target="_blank" href="https://www.linkedin.com/company-beta/13252157/">Linkedin</a></li>
                    <li><a class="widget-follow-us-google" target="_blank" href="https://plus.google.com/u/0/104395130386360355416">Google+</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        Copyright ©<?php echo date('Y'); ?> <span>Aptinova Group</span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Designed by <a href="http://www.aptinova.com" target="_blank">Aptinova Business Service Pvt Ltd</a>
    </div>
</div>
