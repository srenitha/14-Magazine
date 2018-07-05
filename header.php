<?php require_once('Connections/dbConn.php');
ob_start();
if(isset($_POST['nsemail'])){
    $daet = date('Y-m-d');
    $nsemail = $_POST['nsemail'];
    $query_getNewsleter = "SELECT * FROM `newsletter` WHERE newsletter_email_id = '$nsemail'";
	$getNewsleter = mysqli_query($dbConn, $query_getNewsleter) or die(mysqli_error($dbConn));
    $totalRows_getNewsleter = mysqli_num_rows($getNewsleter);
        if($totalRows_getNewsleter > 0){ ?>
<script>
    alert("Email Address is already existing");

</script>
<?php   }
    else{
            $query_entrView = "INSERT INTO `newsletter` (`newsletter_email_id`,`status`, `created_by`, `created_on`, `updated_by`) VALUES ('$nsemail','1','0','$daet','0');";
            $entrView = mysqli_query($dbConn, $query_entrView) or die(mysqli_error($dbConn));
        }
    
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="logo">
                <a href="<?php echo $rootURL; ?>">
                    <img class="img-responsive" style="border-width:0px !important;max-width: 55% !important;" src="<?php echo $rootURL; ?>images/logo/aptinova_magazine.png" /></a>
            </div>
        </div>
        <div class="col-md-4 hidden-xs">
            <?php if(!isset($_SESSION['uid'])){ ?>
            <form class="subscription" name="nsform" id="newsletter" method="post" action="#">
                <span class="subscription-text">Newsletter subscribe</span>
                <span class="input-cover">
                            <input type="submit" name="submit" value="" class="subscription-button">
                            <input type="email" name="nsemail" placeholder="Email ..." class="subscription-line" required>
                        </span>
            </form>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <ul class="header-signup">
                <li>
                    <div class="dropdown">
                        <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">Hi, <?php if(isset($_SESSION['uid'])){ 
echo $_SESSION['ufname'].' '.$_SESSION['ulname']; } else { ?>Guest <?php } ?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php if(isset($_SESSION['uid'])){ ?>
                            <li><a href="<?php echo $rootURL; ?>user"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;My Profile</a></li>
                            <?php if($_SESSION['urole'] == 1){ ?>
                            <li><a href="<?php echo $rootURL; ?>admin"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Go To Admin Panel</a></li>
                            <?php } ?>
                            <?php if($_SESSION['urole'] == 5){ ?>
                            <li><a href="<?php echo $rootURL; ?>auther"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Go To Auther Panel</a></li>
                            <?php } ?>
                            <li><a href="<?php echo $rootURL; ?>goodbye"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Signout</a></li>
                            
                            <?php } else { ?>
                            <form action="<?php echo $rootURL; ?>signin" id="latest" method="post" style="padding: 0px 20px;color: black;">
                            <li> 
                                 <input type="hidden" name="roturl" id="roturl" value="<?php echo substr($_SERVER['REQUEST_URI'], 1); ?>" />
                                <button hidden=""></button>
                                <a href="javascript:{}" onclick="document.getElementById('latest').submit();" style="color: #333;"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Signin</a>
                               <!-- <a href="<?php echo $rootURL; ?>signin" onclick="return gurl();"><i class="fa fa-sign-in" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;Signin</a>--></li></form>
                            <li><a href="<?php echo $rootURL; ?>signin"><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;&nbsp;Signup</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
function gurl(){
    alert("<?php echo $_SERVER['REQUEST_URI']; ?>");
}
</script>