<?Php 
require_once("Connections/dbConn.php");

$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';

if(isset($_GET['ymd'])){
    $ymd = date($_GET['ymd']);
    $title = $_GET['title'];
    $titless = str_replace("-"," ",$title);
    $query_getArticle = "SELECT * FROM `article` WHERE status = '1' AND article_short_title = '$titless' AND created_on = '$ymd'";
    $getArticle = mysqli_query($dbConn, $query_getArticle) or die(mysqli_error($dbConn));
    if($getArticle){
        $totalRows_getArticle = mysqli_num_rows($getArticle);
        $row_getArticle = mysqli_fetch_assoc($getArticle);
        if($totalRows_getArticle > 0){
		$premium = $row_getArticle['premium'];
            if($premium == 0){
                $user = "ok";
            }
            else{
                if(isset($_SESSION['uid'])){
                    $daet = date('Y-m-d');
                    $uid = $_SESSION['uid'];
                    $urole = $_SESSION['urole'];
                    if($urole == 1){
                        $user = "ok";
                    }
                    else{
                    $query_getSubscrUser = "SELECT * FROM `subscription` WHERE status = '1' AND user_id = '$uid' AND subscription_from_month <= '$daet' AND subscription_to_month >= '$daet'";
                    $getSubscrUser = mysqli_query($dbConn, $query_getSubscrUser) or die(mysqli_error($dbConn));
                    if($getSubscrUser){
                        $totalRows_getSubscrUser = mysqli_num_rows($getSubscrUser);
                        if($totalRows_getSubscrUser > 0){
                            $user = "ok";
                        }
                        else{
                            ?>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,600,700,800" rel="stylesheet" type="text/css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo $rootURL; ?>css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo $rootURL; ?>css/style.css" />
<?php
                            echo "<script> window.onload = function() { 
                            $('#myModalsubscribe').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show : true
                            }); };</script>";
                        }
                    }
                }
            }
                else{
                    ?>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $rootURL; ?>css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo $rootURL; ?>css/style.css" />
    <?php
                     echo "<script> window.onload = function() { 
                            $('#myModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show : true
                            }); };</script>";
                        }
            }
            if($user == "ok"){
                $article_view_count = $row_getArticle['article_view_count'] + 1;
            	$artid = $row_getArticle['article_id'];
            	$query_updUser = "UPDATE `article` SET `article_view_count` = '$article_view_count' where article_id = '$artid'";
                $updUser = mysqli_query($dbConn, $query_updUser) or die(mysql_error());
                if(isset($_SESSION['uid'])){
                    if($_SESSION['urole'] == 6 || $_SESSION['urole'] == 5){
                         /* Ip Address of user */
                        function getUserIP()
                        {
                            $client  = @$_SERVER['HTTP_CLIENT_IP'];
                            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
                            $remote  = $_SERVER['REMOTE_ADDR'];

                            if(filter_var($client, FILTER_VALIDATE_IP))
                            {
                                $ip = $client;
                            }
                            elseif(filter_var($forward, FILTER_VALIDATE_IP))
                            {
                                $ip = $forward;
                            }
                            else
                            {
                                $ip = $remote;
                            }

                            return $ip;
                        }
                        $user_ip = getUserIP();
                        $uid = $_SESSION['uid'];
                        $daet = date('Y-m-d');
                        $query_artcount= "INSERT INTO `article_view_log` (`article_id`, `user_id`, `login_ip`, `status`, `created_by`, `created_on`, `updated_by`) VALUES ('$artid','$uid','$user_ip','1','$uid','$daet','$uid');";
                        $artcount = mysqli_query($dbConn, $query_artcount) or die(mysqli_error($dbConn));
                    }
                }
                if(isset($_POST['comment'])){
                    $comment = $_POST['comment'];
                    $artid = $row_getArticle['article_id'];
                    $crtdt = $row_getArticle['created_on'];
                    $stitl = str_replace(" ","-",$row_getArticle['article_short_title']);
                    $uid = $_SESSION['uid'];
                    $daet = date('Y-m-d');
                      $query_entrView = "INSERT INTO `comment` (`comment_message`,`article_id`, `user_id`,`sub_comment_id`, `status`, `created_by`, `created_on`, `updated_by`) VALUES ('$comment','$artid','$uid','','1','$uid','$daet','$uid');";
                        $entrView = mysqli_query($dbConn, $query_entrView) or die(mysqli_error($dbConn));
                       echo "<script>location.href='".$rootURL."article/".$crtdt."/".$stitl."'; </script>";
                }
                else if(isset($_POST['subcomment'])){
                    $subcomment = $_POST['subcomment'];
                    $rplycid = $_POST['rplycid'];
                    $artid = $row_getArticle['article_id'];
                    $crtdt = $row_getArticle['created_on'];
                    $stitl = str_replace(" ","-",$row_getArticle['article_short_title']);
                    $uid = $_SESSION['uid'];
                    $daet = date('Y-m-d');
                      $query_entrView = "INSERT INTO `comment` (`comment_message`,`article_id`, `user_id`,`sub_comment_id`, `status`, `created_by`, `created_on`, `updated_by`) VALUES ('$subcomment','$artid','$uid','$rplycid','1','$uid','$daet','$uid');";
                        $entrView = mysqli_query($dbConn, $query_entrView) or die(mysqli_error($dbConn));
                       echo "<script>location.href='".$rootURL."article/".$crtdt."/".$stitl."'; </script>";
                        /*echo "<script>alert('".$subcomment."')</script>";*/
                }
       
   
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <title>
                <?php echo $row_getArticle['article_short_title']; ?>
            </title>
            <meta name="description" content="<?php echo $row_getArticle['article_short_description']; ?>" />
            <meta name="author" content="<?php echo $pagtitle; ?>" />
            <link rel="shortcut icon" href="<?php echo $rootURL; ?>images/favicon.ico" type="image/x-icon" />
            
            <!---------------- facebook-------------------------->
            
            <!-- ======================================================================
                                Mobile Specific Meta
    ======================================================================= -->
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
            <!-- ======================================================================
                                Style CSS + Google Fonts
    ======================================================================= -->
            <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,600,700,800" rel="stylesheet" type="text/css">
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="<?php echo $rootURL; ?>css/bootstrap.css" />
            <link rel="stylesheet" href="<?php echo $rootURL; ?>css/style.css" />
        </head>
            <style type="text/css" media="print">
                * { display: none; }
            </style>

        <body id="mabody">
            <!-- ======================================================================
                                        START HEADER
    ======================================================================= -->
            <div class="header">
                <?php include('header.php');
        include('menu.php');?>
            </div>
            <!-- ======================================================================
                                        END HEADER
    ======================================================================= -->

            <!-- ======================================================================
                                        START CONTENT
    ======================================================================= -->
            <div class="content">
                <div class="container">
                    <!--category name-->
                    <?php $cid = $row_getArticle['category_id'];
                  $query_getCatgry = "SELECT * FROM `category` WHERE status = '1' AND category_id = '$cid'";
				  $getCatgry = mysqli_query($dbConn, $query_getCatgry) or die(mysqli_error($dbConn));
                        if($getCatgry){
                            $totalRows_getCatgry = mysqli_num_rows($getCatgry);
                            if($totalRows_getCatgry > 0){ 
                                $row_getCatgry = mysqli_fetch_assoc($getCatgry);
            ?>
                    <h4 class="category-page site-text-color" id="article_catagory">
                        <?php echo $row_getCatgry['category_name']; ?>
                    </h4>
                    <?php            } 
                        }?>
                    <!--category name-->

                    <div class="row">
                        <div class="col-md-8">
                            <div class="blog-entry">
                                <style>
                                    .blog-entry .entry-header h1 {
                                        font-size: 35px;
                                    }
                                </style>
                                <div class="entry-header">
                                    <h1>
                                        <?php echo $row_getArticle['article_title']; ?>
                                    </h1>
                                </div>
                                <div class="entry-cover">
                                    <img src="<?php echo $row_getArticle['article_image']; ?>" alt="article name" style="width:100%;">
                                </div>
                                <div class="entry-content-details">
                                    <div class="share-it">
                                        <span>Share</span>
                                        <div>
                                            <ul class="socials">
                                                <!--Insert Article URL-->
                                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $rootURL; ?>article/<?php echo $row_getArticle['created_on'].'/'.str_replace(" ","-",$row_getArticle['article_short_title']); ?>" target="_blank"><img src="<?php echo $rootURL; ?>images/elements/socials/facebook.png" alt="facebook" /></a></li>
                                                <!--Insert Article URL-->
                                                <li><a href="https://plus.google.com/share?url=<?php echo $rootURL; ?>article/<?php echo $row_getArticle['created_on'].'/'.str_replace(" ","-",$row_getArticle['article_short_title']); ?>" target="_blank"><img src="<?php echo $rootURL; ?>images/elements/socials/googleplus.png" alt="google" /></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <span>Posted : </span>
                                    <?php $dt = strtotime($row_getArticle['created_on']);
																							 $pt = date('d F, Y', $dt); 
																							 echo $pt; ?> &nbsp; / &nbsp; by
                                    <?php /* admin name*/ $auid=$row_getArticle['auther_id'];
            $query_getAuther = "SELECT * FROM users WHERE user_id = '$auid'";
            $getAuther = mysqli_query($dbConn, $query_getAuther) or die(mysqli_error($dbConn));
            if($getAuther){
                 $totalRows_getAuther = mysqli_num_rows($getAuther); 
                 $row_getAuther = mysqli_fetch_assoc($getAuther);
                echo $row_getAuther['user_f_name'].' '.$row_getAuther['user_l_name'];} ?> &nbsp; / &nbsp; <a href="#comments-area">comments <?php $atid=$row_getArticle['article_id'];
            $query_getComment = "SELECT * FROM comment WHERE article_id = '$atid' AND status = '1' AND user_id > '0'";
            $getComment = mysqli_query($dbConn, $query_getComment) or die(mysqli_error($dbConn));
            if($getComment){
                 $totalRows_getComment = mysqli_num_rows($getComment); 
                echo "(".$totalRows_getComment.")";} ?></a>
                                </div>
                                <style>
                                    .blog-entry .entry-content h4 {
                                        font-size: 20px;
                                    }
                                    
                                    .blog-entry .entry-content h2 {
                                        font-size: 20px;
                                        font-weight: bold;
                                    }
                                    
                                    .blog-entry .entry-content p {
                                        line-height: 25px;
                                    }
                                    
                                    .blog-entry .entry-content ul {
                                        list-style-type: disc;
                                        padding: 0px 25px;
                                    }
                                    
                                    .blog-entry .entry-content ol {
                                        list-style-type: decimal;
                                        padding: 0px 25px;
                                    }
                                </style>
                                <div class="entry-content" style="font-size: 17px;">
                                    <?php echo $row_getArticle['article_description']; ?>
                                </div>

                                <div class="comments-area" id="comments-area">


                                    <div id="respond">
                                        <ul class="commentlist">
                                            <?php
            $atid=$row_getArticle['article_id'];
            $query_getComment = "SELECT * FROM comment WHERE article_id = '$atid' AND status = '1' AND user_id > '0' AND (sub_comment_id IS NULL OR sub_comment_id = '0')";
            $getComment = mysqli_query($dbConn, $query_getComment) or die(mysqli_error($dbConn));
            if($getComment){
                 $totalRows_getComment = mysqli_num_rows($getComment); 
                if($totalRows_getComment > 0){
                    while($row_getComment = mysqli_fetch_assoc($getComment)){ ?>
                                                <li>
                                                    <?php $cuser = $row_getComment['user_id'];
                                              $query_getComntUser = "SELECT * FROM users WHERE user_id = '$cuser'";
                                              $getComntUser = mysqli_query($dbConn, $query_getComntUser) or die(mysqli_error($dbConn));
                                              if($getComntUser){
                                                  $row_getComntUser = mysqli_fetch_assoc($getComntUser); ?>


                                                    <div class="comment">
                                                        <span class="avatar"><img src="<?php echo $row_getComntUser['user_profile_pick']; ?>" alt="comment avatar"></span>
                                                        <span class="comment-info"><?php $cdc = strtotime($row_getComment['created_on']);
																	$ctf = date('d F, Y', $cdc); 
																	echo $ctf;?> <span><?php echo $row_getComntUser['user_f_name'].' '.$row_getComntUser['user_l_name']; ?></span> </span>
                                                        <p>
                                                            <?php echo $row_getComment['comment_message']; ?>
                                                        </p>
                                                        <a class="comment-reply" id="rply<?php echo $row_getComment['comment_id'];?>" onclick="document.getElementById('subrply<?php echo $row_getComment['comment_id'];?>').style.display='block'; document.getElementById('rply<?php echo $row_getComment['comment_id'];?>').style.display='none';">reply</a>
                                                    </div>
                                                    <div class="perfect-form-box" id="subrply<?php echo $row_getComment['comment_id'];?>" style="display:none;">
                                                        <?php if(isset($_SESSION['uid'])){ ?>
                                                        <form class="perfect-form" method="post">
                                                            <textarea name="subcomment" id="subcomment" class="perfect-area" required></textarea>
                                                            <span>Comment</span>
                                                            <input type="hidden" name="rplycid" value="<?php echo $row_getComment['comment_id'];?>">
                                                            <div class="clear"></div>
                                                            <input type="submit" value="Reply" class="perfect-button">
                                                            <input type="button" value="Close" class="perfect-button" onclick="document.getElementById('subrply<?php echo $row_getComment['comment_id'];?>').style.display='none'; document.getElementById('rply<?php echo $row_getComment['comment_id'];?>').style.display='inline-block';">
                                                        </form>
                                                        <?php } ?>
                                                    </div>
                                                    <?php }
            $comentid=$row_getComment['comment_id'];
            $query_getSubcomment = "SELECT * FROM comment WHERE article_id = '$atid' AND status = '1' AND user_id > '0' AND sub_comment_id ='$comentid'";
            $getSubcomment = mysqli_query($dbConn, $query_getSubcomment) or die(mysqli_error($dbConn));
            if($getSubcomment){
                 $totalRows_getSubcomment = mysqli_num_rows($getSubcomment); 
                if($totalRows_getSubcomment > 0){ ?>
                                                    <ul class="children">
                                                        <?php               while($row_getSubcomment = mysqli_fetch_assoc($getSubcomment)){ ?>
                                                        <li>
                                                            <?php $cuser = $row_getSubcomment['user_id'];
                                              $query_getComntUser = "SELECT * FROM users WHERE user_id = '$cuser'";
                                              $getComntUser = mysqli_query($dbConn, $query_getComntUser) or die(mysqli_error($dbConn));
                                              if($getComntUser){
                                                  $row_getComntUser = mysqli_fetch_assoc($getComntUser); ?>
                                                            <div class="comment">
                                                                <span class="avatar"><img src="<?php echo $row_getComntUser['user_profile_pick']; ?>" alt="comment avatar"></span>
                                                                <span class="comment-info"><?php $dca = strtotime($row_getSubcomment['created_on']);
																	$pca = date('d F, Y', $dca); 
																	echo $pca;?> <span><?php echo $row_getComntUser['user_f_name'].' '.$row_getComntUser['user_l_name']; ?></span> </span>
                                                                <p>
                                                                    <?php echo $row_getSubcomment['comment_message']; ?>
                                                                </p>
                                                                <!--<a class="comment-reply" id="rply<?php echo $row_getSubcomment['comment_id'];?>" onclick="document.getElementById('subrply<?php echo $row_getSubcomment['comment_id'];?>').style.display='block'; document.getElementById('rply<?php echo $row_getSubcomment['comment_id'];?>').style.display='none';">reply</a>-->
                                                            </div>
                                                            <div class="perfect-form-box" id="subrply<?php echo $row_getSubcomment['comment_id'];?>" style="display:none;">
                                                                <?php if(isset($_SESSION['uid'])){ ?>
                                                                <form class="perfect-form" method="post">
                                                                    <textarea name="subcomment" id="subcomment" class="perfect-area" required></textarea>
                                                                    <span>Sub Comment</span>
                                                                    <input type="hidden" name="rplycid" value="<?php echo $row_getSubcomment['comment_id'];?>">
                                                                    <div class="clear"></div>
                                                                    <input type="submit" value="Reply" class="perfect-button">
                                                                    <input type="button" value="Close" class="perfect-button" onclick="document.getElementById('subrply<?php echo $row_getSubcomment['comment_id'];?>').style.display='none'; document.getElementById('rply<?php echo $row_getSubcomment['comment_id'];?>').style.display='inline-block';">
                                                                </form>
                                                                <?php } ?>
                                                            </div>
                                                            <?php }
            $subcomentid=$row_getSubcomment['comment_id'];
            $query_getSubSubcomment = "SELECT * FROM comment WHERE article_id = '$atid' AND status = '1' AND user_id > '0' AND sub_comment_id ='$subcomentid'";
            $getSubSubcomment = mysqli_query($dbConn, $query_getSubSubcomment) or die(mysqli_error($dbConn));
            if($getSubSubcomment){
                 $totalRows_getSubSubcomment = mysqli_num_rows($getSubSubcomment); 
                if($totalRows_getSubSubcomment > 0){ ?>
                                                            <ul class="children">
                                                                <?php               while($row_getSubSubcomment = mysqli_fetch_assoc($getSubSubcomment)){ ?>
                                                                <li>
                                                                    <?php $cuser = $row_getSubSubcomment['user_id'];
                                              $query_getComntUser = "SELECT * FROM users WHERE user_id = '$cuser'";
                                              $getComntUser = mysqli_query($dbConn, $query_getComntUser) or die(mysqli_error($dbConn));
                                              if($getComntUser){
                                                  $row_getComntUser = mysqli_fetch_assoc($getComntUser); ?>
                                                                    <div class="comment">
                                                                        <span class="avatar"><img src="<?php echo $row_getComntUser['user_profile_pick']; ?>" alt="comment avatar"></span>
                                                                        <span class="comment-info"><?php $dca = strtotime($row_getSubSubcomment['created_on']);
																	$pca = date('d F, Y', $dca); 
																	echo $pca;?> <span><?php echo $row_getComntUser['user_f_name'].' '.$row_getComntUser['user_l_name']; ?></span> </span>
                                                                        <p>
                                                                            <?php echo $row_getSubSubcomment['comment_message']; ?>
                                                                        </p>
                                                                        <!--<a class="comment-reply" id="rply<?php echo $row_getSubSubcomment['comment_id'];?>" onclick="document.getElementById('subrply<?php echo $row_getSubSubcomment['comment_id'];?>').style.display='block'; document.getElementById('rply<?php echo $row_getSubSubcomment['comment_id'];?>').style.display='none';">reply</a>-->
                                                                    </div>
                                                                    <div class="perfect-form-box" id="subrply<?php echo $row_getSubSubcomment['comment_id'];?>" style="display:none;">
                                                                        <?php if(isset($_SESSION['uid'])){ ?>
                                                                        <form class="perfect-form" method="post">
                                                                            <textarea name="subcomment" id="subcomment" class="perfect-area" required></textarea>
                                                                            <span>Sub Comment</span>
                                                                            <input type="hidden" name="rplycid" value="<?php echo $row_getSubSubcomment['comment_id'];?>">
                                                                            <div class="clear"></div>
                                                                            <input type="submit" value="Reply" class="perfect-button">
                                                                            <input type="button" value="Close" class="perfect-button" onclick="document.getElementById('subrply<?php echo $row_getSubSubcomment['comment_id'];?>').style.display='none'; document.getElementById('rply<?php echo $row_getSubSubcomment['comment_id'];?>').style.display='inline-block';">
                                                                        </form>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <?php } ?>

                                                                </li>

                                                                <?php }
                 ?>
                                                            </ul>
                                                            <?php
                }
            }
                /*$row_getSubSubcomment = mysqli_fetch_assoc($getSubSubcomment);*/
               
                                        ?>

                                                        </li>

                                                        <?php }
                 ?>
                                                    </ul>
                                                    <?php
                }
            }
                /*$row_getSubcomment = mysqli_fetch_assoc($getSubcomment);*/
               
                                        ?>
                                                </li>

                                                <?php }
                }
            }
                /*$row_getComment = mysqli_fetch_assoc($getComment);*/
               
                                        ?>
                                        </ul>
                                    </div>
                                    <div class="perfect-form-box">
                                        <h2 class="perfect-form-title">Comments <span><?php $atid=$row_getArticle['article_id'];
            $query_getComment = "SELECT * FROM comment WHERE article_id = '$atid' AND status = '1' AND user_id > '0'";
            $getComment = mysqli_query($dbConn, $query_getComment) or die(mysqli_error($dbConn));
            if($getComment){
                 $totalRows_getComment = mysqli_num_rows($getComment); 
                echo "(".$totalRows_getComment.")";} ?></span></h2>
                                        <?php if(isset($_SESSION['uid'])){ ?>
                                        <span class="perfect-form-write" id="comentbox">write a comment</span>
                                        <form class="perfect-form" method="post">
                                            <textarea name="comment" id="comment" class="perfect-area"></textarea>
                                            <span>Comment</span>
                                            <div class="clear"></div>
                                            <input type="submit" value="Write" class="perfect-button">
                                        </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="sidebar">

                                <?php include("widgets/archives.php"); ?>

                                <?php include("widgets/new-posts.php"); ?>

                                <?php include("widgets/tag-cloud.php"); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ======================================================================
                                        END CONTENT
    ======================================================================= -->

            <!-- =====================================================================
                                         START FOOTER
    ====================================================================== -->
            <?php include("footer.php"); ?>
            <!-- =====================================================================
                                         END FOOTER
    ====================================================================== -->


            <?php 
        }
        }
        else{
            echo "Total";
        }
    }
    else{
        echo "No record";
    }
}
?>
<script>
    window.oncontextmenu = function () {
        return false;
    }
 function disableselect(e) {
    return false;
}

function reEnable() {
    return true;
}

document.onselectstart = new Function("return false");

if (window.sidebar) {
    document.onmousedown = disableselect;
    document.onclick = reEnable;
}
    
</script>


            <!-- ======================================================================
                                    START SCRIPTS
    ======================================================================= -->
            <script src="<?php echo $rootURL; ?>js/jquery.min.js"></script>
            <script src="<?php echo $rootURL; ?>js/bootstrap.min.js"></script>
            <script src="<?php echo $rootURL; ?>js/jquery-ui.min.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/imagesloaded.pkgd.min.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/jquery.swipebox.min.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/modernizr.custom.63321.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/placeholder.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/masonry.pkgd.min.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/farbtastic/farbtastic.js" type="text/javascript"></script>

            <script src="<?php echo $rootURL; ?>js/options.min.js" type="text/javascript"></script>
            <script src="<?php echo $rootURL; ?>js/plugins.min.js" type="text/javascript"></script>
            <script>
            
                $(document).ready(function() {
                   var cat = $("#article_catagory").html();
                    var ctegor = $.trim(cat);
                    var nid = "#"+ctegor+"_category_title";
                    $(nid).parent().css("background", "#ab0000");
                    $(nid).parent().parent().addClass('hactive');
                });
            </script>
            <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
            <!-- ======================================================================
                                    END SCRIPTS
    ======================================================================= -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                            <h4 class="modal-title">This is a Premium Articles</h4>
                        </div>
                        <div class="modal-body">
                            <p>Please Login to read this article</p>
                            <form method="post" action="<?php echo $rootURL; ?>signin">
                                <input type="hidden" name="roturl" id="roturl" value="<?php echo substr($_SERVER['REQUEST_URI'], 1); ?>" />
                                <button type="submit" style="color: #fff; background-color: #d9534f; border-color: #d43f3a; padding: 10px;">Signin Now</button>
                            </form>
                            
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="myModalsubscribe" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                            <h4 class="modal-title">This is a Premium Articles</h4>
                        </div>
                        <div class="modal-body">
                            <p>You Need to subscribe for this article</p>
                            <div class="col-md-7">
                                <div class="contact-form-box" style="border-top:0px;">

                                    <form class="contact-form" id="signup_form" name="signupp" method="post" action="<?php echo $rootURL; ?>subscribe_now.php">

                                        <input type="tel" name="mobile_number" id="mobile_number" class="contact-line" required>
                                        <span style="width:105px;">Mobile Number</span>
                                        <div class="clear"></div>
                                        <input type="submit" name="sign_up" value="Subscribe Now" class="contact-button"><br/><br/><br/>
                                        <input type="button" name="sign" value="Go To Home" class="contact-button" onclick="window.location.href='<?php echo $rootURL; ?>'">
                                    </form>
                                </div>
                            </div>
                            <!--<button type="submit" onclick="window.location.href='<?php echo $rootURL; ?>signin'"  style="color: #fff; background-color: #d9534f; border-color: #d43f3a; padding: 10px;">Subscribe Now</button>-->
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>

                </div>
            </div>
        </body>

        </html>