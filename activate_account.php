<?php require_once('Connections/dbConn.php');
if(isset($_GET['key'])){
	$valy = $_GET['key'];
	$dec = decrypt_blowfish(base64url_decode($valy));
	$v = explode("#",$dec);
	$email = $v[0];
	$token = $v[1];
	
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
	if(($email != "") && ($token != "")){
		
		
		$query_getCkUser = "SELECT * FROM `users` WHERE user_email = '$email' AND token = '$token' AND status = '2'";
		$getCkUser = mysqli_query($dbConn, $query_getCkUser) or die(mysql_error());
        if($getCkUser){
            $row_getCkUser = mysqli_fetch_assoc($getCkUser);
            $totalRows_getCkUser = mysqli_num_rows($getCkUser);
            if($totalRows_getCkUser > 0){
                $uid = $row_getCkUser['user_id'];
                $uname = $row_getCkUser['user_f_name'].' '.$row_getCkUser['user_l_name'];
                $query_updArtcl = "UPDATE `users` SET `token` = '$tok', `status`='1' where user_id = '$uid'";
                $updArtcl = mysqli_query($dbConn, $query_updArtcl) or die(mysql_error());
                    $urdl = $rootURL."signin";
                    $user_email = $row_getCkUser['user_email'];
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= "From: Aptinova <admin@aptinova.com>" . "\r\n" ;
                    $subject = "Your Aptinova Account activated";
                    $body = "Hi $uname,<br>
                    ===================================================================<br>
                    Congratulations, Your Aptinova Account has been activated successfully.<br>
                    You can now access your account. <a href='$urdl' target='_blnk'>Click Here</a> to login.
                    <br><br>
                    =====================================================================<br>
                    Thanks,<br>
                    Aptinova Team<BR>
                    ";
                   /* mail($user_email, $subject, $body, $headers);*/
                include "mailer/mailtest.php";

                echo "<script> alert('Your account has been Activated Successfully. Please Login.'); location.href='".$rootURL."signin'; ;</script>";
            }else{
                echo "<script> alert('Link is invalid, Please contact customer care.'); location.href='".$rootURL."signin'; ;</script>";
            }
        }
		
	}else{
			echo "<script> alert('Link is invalid, Please contact customer care.'); location.href='".$rootURL."signin'; ;</script>";
}
}else{
			echo "<script> alert('Link is invalid, Please contact customer care.'); location.href='".$rootURL."signin'; ;</script>";
}
?>
<!DOCTYPE html>

