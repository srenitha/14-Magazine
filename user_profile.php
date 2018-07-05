<?php require_once('Connections/dbConn.php');
if(isset($_SESSION['uid'])){
    $uid = $_SESSION['uid'];
}else{
    echo "<script>location.href='".$rootURL."'; </script>";
}

$getUserDetailsQ= "SELECT * FROM users WHERE user_id='$uid' AND status='1'";
$getUserDetailsR = mysqli_query($dbConn, $getUserDetailsQ);
if($getUserDetailsR){
    if(mysqli_num_rows($getUserDetailsR) > 0){
        $userDetails = mysqli_fetch_assoc($getUserDetailsR);
    }
}
if(isset($_POST['user_f_name'])){
    $user_f_name = $_POST['user_f_name'];
    $user_l_name = $_POST['user_l_name'];
    $user_phone = $_POST['user_phone'];
    $user_description = $_POST['user_description'];
    $user_name = $_POST['user_name'];
    $user_address = $_POST['user_address'];
    $query_getUserName = "SELECT * FROM `users` WHERE user_id= '$uid' AND status = '1'";
    $getUserName = mysqli_query($dbConn, $query_getUserName) or die(mysqli_error($dbConn));
        if($getUserName){
            $row_getUserName = mysqli_fetch_assoc($getUserName);
            if($row_getUserName['user_name'] == $user_name){
                $un = 0;
            }
            else{
                $un = 1;
            }
        }
    $query_updUser = "UPDATE `users` SET `user_f_name` = '$user_f_name', user_l_name = '$user_l_name', user_phone = '$user_phone', user_description = '$user_description', user_address= '$user_address' where user_id = '$uid'";
	$updUser = mysqli_query($dbConn, $query_updUser) or die(mysql_error());
    if($un == 1){
        $query_checkUserName = "SELECT * FROM `users` WHERE user_name= '$user_name'";
        $checkUserName = mysqli_query($dbConn, $query_checkUserName) or die(mysqli_error($dbConn));
            if($checkUserName){
                $row_checkUserName = mysqli_fetch_assoc($checkUserName);
                $totalRows_checkUserName = mysqli_num_rows($checkUserName);
                if($totalRows_checkUserName > 0){
                    echo "<script> alert('Your User Name is already existing with another user, Please try with another User Name.'); location.href='".$rootURL."user'; </script>";
                }
                else{
                    $query_updUserName = "UPDATE `users` SET `user_name` = '$user_name' where user_id = '$uid'";
                    $updUserName = mysqli_query($dbConn, $query_updUserName) or die(mysql_error());
                }
            }
    }
    
     echo "<script> alert('Your Profile is updated Successfully'); location.href='".$rootURL."user'; </script>";
}
if(isset($_POST['author_message'])){
    $author_message = $_POST['author_message'];
    $uid = $_SESSION['uid'];
    $date = date('Y-m-d');
	/*$query_updArtcl = "UPDATE `users` SET `password` = '$password' where user_id = '$uid'";
	$updArtcl = mysqli_query($dbConn, $query_updArtcl) or die(mysql_error());*/
    $query_becomAuther = "INSERT INTO `author_request` (`user_id`, `request_message`, `status`, `created_by`, `created_on`, `updated_by`) VALUES ('$uid','$author_message','2','$uid','$date','$uid');";
    $becomAuther = mysqli_query($dbConn, $query_becomAuther) or die(mysql_error());
     echo "<script> alert('Your Auther Request send to admin.'); location.href='".$rootURL."user'; </script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>User Profile</title>
    <meta name="description" content="Here goes description" />
    <meta name="author" content="author name" />
    <link rel="shortcut icon" href="<?php echo $rootURL; ?>images/favicon.ico" type="image/x-icon" />
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
    <style>
        .sactive a {
            color: #c90000 !important;
        }

    </style>
</head>

<body>
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
            <div class="row">

                <div class="col-md-4">
                    <div class="sidebar">
                        <div class="widget">
                            <h2 class="widget-title">Account Settings</h2>
                            <ul>
                                <li class="sactive">
                                    <a href="#" id="general_settings">
                                        General
                                    </a>
                                </li>
                                <li>
                                    <a href="#" id="security_settings">
                                        Security &amp; Passwords
                                    </a>
                                </li>
                                <li>
                                    <a href="#" id="other_settings">
                                        Other
                                    </a>
                                </li>

                            </ul>
                        </div>

                    </div>
                </div>

                <div class="col-md-8">
                    <div id="general">
                        <div class="site-title" style="margin-bottom: 20px !important;">
                            General Settings
                        </div>
                        <div id="general_show">
                            <div class="col-md-12">
                                <!--<div class="col-md-4" align="center">
                                    <img id="profile_picture" name="profile_picture" src="images/elements/ads.png" class="img-responsive" />
                                </div>-->
                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <h3 id="user_full_name">
                                            <?php echo $userDetails['user_f_name'].' '.$userDetails['user_l_name']; ?>
                                        </h3>
                                        <p id="user_name">User Name : <span style="color:#333"><?php echo $userDetails['user_name']; ?> </span></p>
                                    </div>
                                    <div class="col-md-2" align="right">
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#user_details" id="edituser"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></div>
                                    <span>&nbsp;</span>
                                    <div class="col-md-12" style="padding-left:0px !important;">
                                        <div class="col-md-6" style="padding-left:0px !important;">
                                            <p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php echo $userDetails['user_phone']; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6" style="padding-left:0px !important;">
                                            <p>
                                                <i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php echo $userDetails['user_email']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <span>&nbsp;</span>
                                    <?php if($userDetails['user_description'] != '' & $userDetails['user_description'] != ' ' ){ ?>
                                    <div class="col-md-12" style="padding-left:0px !important;">
                                        <p style="font-size:1.2em">About</p>
                                        <p id="user_bio">
                                            <?php echo $userDetails['user_description']; ?>
                                        </p>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="security" hidden="hidden">
                        <div class="site-title" style="margin-bottom: 20px !important;">
                            Security and Password Settings
                        </div>
                        <div id="security_show">
                            <div class="col-md-12">
                                <div class="contact-form-box" style="border-width:0px !important;padding-top: 0px !important;">
                                    <form class="contact-form" id="change_password_form">
                                        <input pattern=".{5,}" type="password" name="new_password" id="new_password" class="contact-line" required>
                                        <span>New Password</span>
                                        <input pattern=".{5,}" type="password" name="new_password_repeat" id="new_password_repeat" class="contact-line" required>
                                        <span>Repeat Password</span>
                                        <div class="clear"></div>
                                        <input type="submit" id="change_password" value="Change Password" class="contact-button">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="other" hidden="hidden">
                        <div class="site-title" style="margin-bottom: 20px !important;">
                            Other Settings
                        </div>
                        <div id="other_show">
                            <div class="contact-form-box" style="border-width:0px !important;padding-top: 0px !important;">
                                <?php if($_SESSION['urole'] == 6){ 
                                        $uid = $_SESSION['uid'];
                                        $query_getRequest = "SELECT * FROM `author_request` WHERE user_id = '$uid' AND status = '2'";
                                        $getRequest = mysqli_query($dbConn, $query_getRequest) or die(mysql_error());
                                        if($getRequest){
                                          $totalRows_getRequest = mysqli_num_rows($getRequest); 
                                            if($totalRows_getRequest == 0){ ?>
                                                <span class="contact-form-write">Become an author</span>
                                                <form class="contact-form" id="author_request_form" method="post">
                                                    <textarea name="author_message" class="contact-area" required></textarea>
                                                    <span style="width:165px;">Author Request Message</span>
                                                    <div class="clear"></div>
                                                    <input type="submit" id="author_request" value="Send Request" class="contact-button">
                                                </form> <?php
                                            }
                                            else{
                                                echo "<p>You have alredy send an Auther request waiting for Admin Responce</p>";
                                            }
                                        } 
                                    } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================================================================
                                    END CONTENT
    ======================================================================= -->

    <!--User Details Editor-->
    <div class="modal fade" id="user_details" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <?php echo $userDetails['user_name']; ?>
                    </h4>
                </div>
                <div class="modal-body" id="user_details_contents">
                    <p>Some text in the modal.</p>
                </div>
            </div>
        </div>
    </div>
    <!--User Details Editor-->
    <!-- =====================================================================
                                         START FOOTER
    ====================================================================== -->
    <?php include("footer.php"); ?>
    <!-- =====================================================================
                                         END FOOTER
    ====================================================================== -->

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
    <script src="<?php echo $rootURL; ?>js/md5.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script>
        $(document).ready(function() {
            var general = $("#general");
            var security = $("#security");
            var other = $("#other");

            var general_nav = $("#general_settings");
            var secuity_nav = $("#security_settings");
            var other_nav = $("#other_settings");
            general_nav.click(function() {
                console.log("HI");
                general.show();
                security.hide();
                other.hide();

                general_nav.parent().removeClass("sactive");
                secuity_nav.parent().removeClass("sactive");
                other_nav.parent().removeClass("sactive");
                general_nav.parent().addClass("sactive");
            });

            secuity_nav.click(function() {
                general.hide();
                security.show();
                other.hide();

                general_nav.parent().removeClass("sactive");
                secuity_nav.parent().removeClass("sactive");
                other_nav.parent().removeClass("sactive");
                secuity_nav.parent().addClass("sactive");
            });

            other_nav.click(function() {
                general.hide();
                security.hide();
                other.show();

                general_nav.parent().removeClass("sactive");
                secuity_nav.parent().removeClass("sactive");
                other_nav.parent().removeClass("sactive");
                other_nav.parent().addClass("sactive");
            });

            $("#new_password").change(function() {
                if ($(this).val().length < 5) {
                    this.setCustomValidity('Password Must be more than 5 letters');
                } else {
                    this.setCustomValidity('');
                }
            });
            $("#new_password_repeat").change(function() {
                if ($(this).val().length < 5) {
                    this.setCustomValidity('Password Must be more than 5 letters');
                } else {
                    this.setCustomValidity('');
                }
            });

            $("#change_password_form").submit(function(event) {
                $('#new_password').val(md5($('#new_password').val()));
                event.preventDefault();
                $("body").css("cursor", "progress");
                $.post('<?php echo $rootURL.'content_updaters/'; ?>change_password.php', {
                            'new_password': $('#new_password').val()
                        })
                    .done(function(data) {
                        $("body").css("cursor", "default");
                        if (data == "success") {
                            alert("Your Password changed Successfully!");
                            location.reload();
                        } else {
                            alert("An error occured while changing password!");
                        }
                    })
                    .fail(function(data) {
                        alert("An error occured while changing password!");
                    });
            });
            $("#edituser").click(function(){
                var user_id = <?php echo $_SESSION['uid']; ?>;
                var dataString = {
                    "user_id": user_id
                };
                $.ajax({
                    type: "POST",
                    url: "<?php echo $rootURL; ?>content_providers/getUesrDetails.php",
                    cache: false,
                    data: dataString,
                    success: function(html) {
                        $("#user_details_contents").html(html);
                    }
                });
            });
            

        });

    </script>
    <!-- ======================================================================
                                    END SCRIPTS
    ======================================================================= -->
</body>

</html>
