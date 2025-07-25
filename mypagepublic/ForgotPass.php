   <?php
   require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include("../BackEnd/connectSQL.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/fotgotpass.css">
    <link rel="icon" href="../image/logo.png" type="image/jpeg">
    <title>Quên mật khẩu</title>
</head>
<body>
    <form class = "form_Main"method="POST">
        <h1 for="" >Quên Mật Khẩu</h1>
        <label class = "lbl" for="">Email</label>
        <input name = "mail" id = "form_Main__input"type="email" placeholder="Nhập Gmail của bạn" required></input>
         <label for="" id = "thongBao">Gmail không tồn tại</label>
        <a class = "Back" href="../My_Page_public/login.php">Quay lại trang chủ</a>
        <button id = "form_Main__btn" type="submit">Gửi mail</button>
    </form>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail'])) {
    $email = $_POST['mail'];

   $querysql = "SELECT Email FROM nguoidung WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $querysql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $ktra = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);

    if ($ktra) {
        $cookie_name = "OTP";
        $token = base64_encode(random_int(100000,999999));
        $expire_time = time() + (5 * 60);
        
        setcookie($cookie_name, $token, $expire_time, "/");
        mysqli_close($conn);
        $mail = new PHPMailer(true);

        try {
        //Server settings

        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                     
        $mail->isSMTP();                                           
        $mail->Host       = 'smtp.gmail.com';                    
        $mail->SMTPAuth   = true;                               
        $mail->Username   = 'cuongmikasa@gmail.com';           
        $mail->Password   = 'yadaeejroykepazh';                             
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          
        $mail->Port       = 465;
        $mail->CharSet ='UTF-8';                               

        //Recipients
        $mail->setFrom('cuongmikasa@gmail.com', 'Đại học trâm mâm');
        $mail->addAddress($email);      
        $mail->addReplyTo('cuongmikasa@gmail.com', 'Information');

        //Content
        $mail->isHTML(true);                             
        $mail->Subject = 'Mã OTP Khôi phục mật khẩu';
        $mail->Body = '
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
  <div style="max-width: 500px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 10px; text-align: center; border: 1px solid #ddd;">
    <h2 style="color: #333333; font-size: 20px; margin-bottom: 20px;">Mã OTP khôi phục mật khẩu của bạn là:</h2>
    <div style="display: inline-block; background-color: #e0f0ff; color: #007bff; padding: 15px 30px; font-size: 28px; font-weight: bold; border-radius: 6px; letter-spacing: 5px; margin: 20px 0;">
      ' .base64_decode($token). '
    </div>
    <p style="font-size: 14px; color: #666666; margin-top: 20px;">Vui lòng không chia sẻ mã này với bất kỳ ai. Mã có hiệu lực trong 5 phút.</p>
  </div>
</body>
</html>
';
        $mail->AltBody = 'Cường Lê';
        $mail->send();
       header("Location: EnterCodeOTP.php?email=".base64_encode($email)."");
       exit();
    } catch (Exception $e) {
        echo "Lỗi send mail: {$mail->ErrorInfo}";
    }
    } else {
        echo "<script>document.getElementById('thongBao').style.display = 'block'</script>";
    }
}
?>

  
