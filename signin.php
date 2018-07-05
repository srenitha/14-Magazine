<?php require_once("Connections/dbConn.php");
include_once 'gapi/gpConfig.php';
include_once 'gapi/User.php';
$authUrl = $gClient->createAuthUrl(); 
if(isset($_SESSION['uid'])){
    //header("Location: /home");
    echo "<script>location.href='".$rootURL."'; </script>";
    
}
if(isset($_POST['roturl'])){
    $roturl = $_POST['roturl'];
    if($roturl != ''){
        $roturl = $roturl;
    }
    else{
        $roturl = "home";
    }
    /*echo "<script>alert('".$roturl."');</script>";*/
}
else{
    $roturl = "home";
}
if(isset($_POST['sign_in_email'])){
    $logemail = $_POST['sign_in_email'];
    $logpassword = $_POST['sign_in_password'];
    $roturl = $_POST['roturl'];
    $pass = md5($logpassword);
    if($logemail != "" && $logpassword != ""){
        $query_checkEmail = "SELECT * FROM `users` WHERE user_email = '$logemail'";
        $checkEmail = mysqli_query($dbConn, $query_checkEmail) or die(mysqli_error($dbConn));
        if($checkEmail){
            $totalRows_checkEmail = mysqli_num_rows($checkEmail);
            if($totalRows_checkEmail > 0){ 
                $row_checkEmail = mysqli_fetch_assoc($checkEmail);
                $user_id = $row_checkEmail['user_id'];
                $status = $row_checkEmail['status'];
                $password = $row_checkEmail['password'];
                $role_id = $row_checkEmail['role_id'];
                $user_f_name = $row_checkEmail['user_f_name'];
                $user_l_name = $row_checkEmail['user_l_name'];
                $date = date('Y-m-d');
               
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
                
                
                
                /* Generating Token Number */
                function generateRandomString($length = 10) {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                        for ($i = 0; $i < $length; $i++) {
                            $randomString .= $characters[rand(0, $charactersLength - 1)];
                        }
                    return $randomString;
                    }
                    $tok = generateRandomString();
                if($status == 1){
                if($password == $pass){
                     /*Checking Suscription status */
                     if($role_id == 5){
                        $query_getSubscr = "SELECT * FROM  `subscription` WHERE  `status` =  '1' AND  `user_id` =  '$user_id' AND  `subscription_from_month` <=  '$date' AND  `subscription_to_month` >=  '$date'";
                        $getSubscr = mysqli_query($dbConn, $query_getSubscr) or die(mysqli_error($dbConn));
                        if($getSubscr){
                            $totalRows_getSubscr = mysqli_num_rows($getSubscr);
                            if($totalRows_getSubscr > 0){ 
                                $role_id = 5;
                            }
                            else{
                                $query_updUser = "UPDATE `users` SET `role_id` = '6' where user_id = '$user_id'";
                                $updUser = mysqli_query($dbConn, $query_updUser) or die(mysqli_error($dbConn));
                                
                                $query_updAUth = "UPDATE `authors` SET `status` = '3', `suspention_reason` = 'Subscription Expired' where user_id = '$user_id'";
                                $updAUth = mysqli_query($dbConn, $query_updAUth) or die(mysqli_error($dbConn));
                                $role_id = 6;
                            }
                            /*$row_getSubscription = mysqli_fetch_assoc($getSubscription);*/
                        }
                    }
                    
                    $query_getLogin = "SELECT * FROM `login` WHERE user_id = '$user_id'";
                    $getLogin = mysqli_query($dbConn, $query_getLogin) or die(mysql_error());
                    if($getLogin){
                        $totalRows_getLogin = mysqli_num_rows($getLogin);
                        if($totalRows_getLogin > 0){
                            $row_getLogin = mysqli_fetch_assoc($getLogin);
                            /*Getting Last login session id */
                            session_id($row_getLogin['session_id']);
                            session_start();
                            session_destroy();
                            session_start();
                            /*getting New session id*/
                            $sid = session_id();
                            $_SESSION['uid'] = $user_id;
                            $_SESSION['urole'] = $role_id;
                            $_SESSION['ufname'] = $user_f_name;
                            $_SESSION['ulname'] = $user_l_name;
                            $lgid=$row_getLogin['login_id'];
                            $query_updLog = "UPDATE `login` SET `session_id` = '$sid', login_ip='$user_ip' where login_id = '$lgid'";
                            $updLog = mysqli_query($dbConn, $query_updLog) or die(mysql_error());
                            $di = $_SESSION['uid'];
                            $query_updArtcl = "UPDATE `users` SET `token` = '$tok' where user_id = '$di'";
                            $updArtcl = mysqli_query($dbConn, $query_updArtcl) or die(mysql_error());
                            if($_SESSION['urole'] != 1){
                               echo "<script>location.href='".$rootURL.$roturl."'; </script>"; 
                            }
                            else{
                                echo "<script>location.href='".$rootURL."admin'; </script>";
                            }
                            
                        }
                        else{
                            session_start();
                            /*getting New session id*/
                            $sid = session_id();
                            $_SESSION['uid'] = $user_id;
                            $_SESSION['urole'] = $role_id;
                            $_SESSION['ufname'] = $user_f_name;
                            $_SESSION['ulname'] = $user_l_name;
                            $query_entrLog = "INSERT INTO `login` (`user_id`, `session_id`, `login_ip`) VALUES ('$user_id','$sid','$user_ip');";
                            $entrLog = mysqli_query($dbConn, $query_entrLog) or die(mysql_error());

                            $di = $_SESSION['uid'];
                            $query_updArtcl = "UPDATE `users` SET `token` = '$tok' where user_id = '$di'";
                            $updArtcl = mysqli_query($dbConn, $query_updArtcl) or die(mysql_error());
                            if($_SESSION['urole'] != 1){
                            echo "<script>location.href='".$rootURL.$roturl."'; </script>"; 
                            }
                            else{
                                echo "<script>location.href='".$rootURL."admin'; </script>";
                            }
                        }
                    }
                    
                }
                    else{ ?>
                            <script>
                                alert("You have entered a wrong Password");
                                    window.history.back();
                            </script>                    
            <?php  }
                }
                else {
                    $query_getStatus = "SELECT * FROM `status` WHERE status_id = '$status'";
                    $getStatus = mysqli_query($dbConn, $query_getStatus) or die(mysqli_error($dbConn));
                    if($getStatus){
                        $totalRows_getStatus = mysqli_num_rows($getStatus);
                        if($totalRows_getStatus > 0){ 
                            $row_getStatus = mysqli_fetch_assoc($getStatus);
                            if($row_getStatus['status_id'] == 2){ ?>
                                <script>
                                    alert("You account is in pending, To activate your account please click the link send to your email");
                                        window.history.back();
                                </script>                    
                <?php        }
                            else{ ?>
                                <script>
                                    alert("You Account has been <?php echo $row_getStatus['status_name']; ?>, Please contact our customer care");
                                        window.history.back();
                                </script>                    
                <?php 
                                
                            }
                        }
                    }
                }
            }
            else{ ?>
                <script>
                    alert("You have entered a worn Email Address");
                        window.history.back();
                </script>                    
     <?php }
        }
    }
  
    
}
else if(isset($_POST['sign_up_first_name'])){
    $sign_up_first_name = $_POST['sign_up_first_name'];
    $sign_up_last_name = $_POST['sign_up_last_name'];
    $sign_up_mobile_number = $_POST['sign_up_mobile_number'];
    $sign_up_email = $_POST['sign_up_email'];
    $sign_up_password = md5($_POST['sign_up_password']);
    $daet = date('Y-m-d');
    if($sign_up_first_name !="" && $sign_up_last_name != "" && $sign_up_mobile_number != "" && $sign_up_email != "" && $sign_up_password != ""){
        if (!filter_var($sign_up_email, FILTER_VALIDATE_EMAIL) === false) {
            /* Checking the email address is existing or not */
            $query_checkEmail = "SELECT * FROM `users` WHERE user_email = '$sign_up_email'";
                $checkEmail = mysqli_query($dbConn, $query_checkEmail) or die(mysqli_error($dbConn));
            if($checkEmail){
                $totalRows_checkEmail = mysqli_num_rows($checkEmail);
                if($totalRows_checkEmail > 0){
                    echo "<script> alert('Your email address is already existing'); window.history.back();</script>";
                }else{
                    function generateRandomString($length = 10) {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                        for ($i = 0; $i < $length; $i++) {
                            $randomString .= $characters[rand(0, $charactersLength - 1)];
                        }
                    return $randomString;
                    }
                    $tok = generateRandomString();
                    $actcd = $sign_up_email.'#'.$tok;
	                $activecod = base64url_encode(encrypt_blowfish($actcd));
                     /* Generating user name */
                    $una = explode('@',$sign_up_email)[0];
                      $c = 0;
                    while($c == 0){
                        $nm = rand(10, 99);
                        $uname = $una.$nm;
                        $query_CheckUsrname = "SELECT * FROM `users` WHERE user_name = '$uname'";
                        $CheckUsrname = mysqli_query($dbConn, $query_CheckUsrname) or die(mysqli_error($dbConn));
                        if($CheckUsrname){
                            $totalRows_CheckUsrname = mysqli_num_rows($CheckUsrname); 
                            if($totalRows_CheckUsrname == 0){
                                $c=1;
                                break;
                            
                            }
                            else{
                                $c=0;
                            }
                        }
                        
                    }
                     /* inserting details to database*/
                    $query_entrView = "INSERT INTO `users` (`user_name`,`user_email`, `user_phone`,`password`, `role_id`, `user_f_name`, `user_l_name`, `token`, `status`, `created_by`, `created_on`, `updated_by`,`user_profile_pick`,`user_address`,`user_description`,`suspention_reason`) VALUES ('$uname','$sign_up_email','$sign_up_mobile_number','$sign_up_password','6','$sign_up_first_name','$sign_up_last_name','$tok','2','0','$daet','0', '', '', '', '');";
                    $entrView = mysqli_query($dbConn, $query_entrView) or die(mysqli_error($dbConn));
                    
                    $relative_path .= $rootURL;
                    $relative_path .= 'activate_account.php';
                    $user_email = $sign_up_email;
                        $headers = 'MIME-Version: 1.0' . "\r\n";
                        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $headers .= "From: Aptinova <admin@aptinova.com>" . "\r\n" ;
                        $subject = "Your Aptinova Account created";
                        $body = "Hi $sign_up_first_name,<br>
                        ===================================================================<br>
                        Your Aptinova Account has been created.<br>
                        To activate your account please follow the link below and set a password.
                    <br>
                        <a href='$relative_path?key=$activecod' target = '_blnk'>$relative_path</a><br>
                        =====================================================================<br>
                        Thanks,<br>
                        Aptinova Team<BR>
                        ";
                    include "mailer/mailtest.php";
                    echo "<script> alert('Your account has been created Successfully. Please check your email.'); location.href='".$rootURL."signin'; ;</script>";
                }
                
            }
                
    
            
        }
        else{
            echo "<script> alert('Your have entered wrong email address'); window.history.back();</script>";
        }
    }
    else{
        echo "<script> alert('Please fill all the fealds'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $pagtitle; ?></title>
    <meta name="description" content="Here goes description" />
    <meta name="author" content="author name" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
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

<body>
    <!-- ======================================================================
                                        START HEADER
    ======================================================================= -->
    <div class="header">
        <?php
        include('header.php');
        include('menu.php');
        ?>
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
                <div class="col-md-7">
                    <div class="contact-form-box">
                        <h2>Create New Account</h2>
                        <input type="button" value="Sign up with Gmail" class="custom-button" onclick="location.href='<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>';">
                        <h3>&nbsp;</h3>
                        <form class="contact-form" id="signup_form" name="signupp" method="post">
                            <input type="text" name="sign_up_first_name" class="contact-line" required>
                            <span>First Name</span>
                            <input type="text" name="sign_up_last_name" class="contact-line" required>
                            <span>Last Name</span>
                            <input type="tel" name="sign_up_mobile_number" class="contact-line" required>
                            <span>Mobile Number</span>
                            <input type="text" name="sign_up_email" class="contact-line" required>
                            <span>email</span>
                            <input type="password" name="sign_up_password" class="contact-line">
                            <span>password</span>
                            <div class="clear"></div>
                            <input type="submit" name="sign_up" value="Sign up with Email" class="contact-button">
                        </form>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="sidebar">
                    <div class="contact-form-box">
                        <h2>Already had account?</h2>
                        <input type="button" value="Sign in with Gmail" class="custom-button" onclick="location.href='<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>';"> 
                        <h3>&nbsp;</h3>
                        <form class="contact-form" id="signin_form" name="signinn" method="post">
                            <input type="email" name="sign_in_email" class="contact-line">
                            <input type="hidden" name="roturl" id="roturl" value="<?php echo $roturl ?>">
                            <span>email</span>
                            <input type="password" name="sign_in_password" class="contact-line">
                            <span>password</span>
                            <div class="clear"></div>
                            <input type="submit" name="sign_in" value="Sign in with Email" class="contact-button">
                        </form>
                    </div>
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
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- ======================================================================
                                    END SCRIPTS
    ======================================================================= -->
</body>

</html>
