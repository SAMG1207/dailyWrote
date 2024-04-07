<?php
require_once("../back/classes/connect_pass.class.php");
$pass = new Pass();
$password = $pass->giveMeG("composer");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader


require_once "config.php";
include '../src/includes/autoloader.inc.php';
require '../vendor/autoload.php';
$mail = new PHPMailer(true);


    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'samgdev91@gmail.com';                     //SMTP username
    $mail->Password   = $password;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('samgdev91@gmail.com', 'Mailer');
       //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    // $mail->Subject = 'Here is the subject';
    // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

   



if(isset($_POST["email"]) && !empty($_POST["email"])){
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if(!$email){
        $mistake = "<p> Please type a valid email address </p>";
    }else{
        $user = new User();
        $registered = $user->alreadyIn($email);
        if(!$registered){
          $mistake = "<p> There is no user registered with this email. <a href='register.php'> Click here to register yourself </a></p>";

        }
    }
    if(!$registered){
        $goBack = "<div class='error'>".$mistake."</div> <br /><a href='javascript:history.go(-1)'>Go Back</a>";
      
    }  else{
          $expFormat = mktime(
            date("H"), date("i"), date("s"), date("m"), date("d")+1, date("Y")
          );
          
          $expDate = date("Y-m-d H:i:s",$expFormat);
          $key = md5(2418*2 . $email);
          
          $addKey = substr(md5(uniqid(rand(),1)),3,10);
          $key = $key . $addKey;
          $recover = new Recover();
          $insert = $recover->insertKey($email, $key, $expDate);
          $output = "<p>Dear user, please click the follow link to reset your password</p>";
          $output.="<p>_________________________________________________________________</p>";
          $output.="<p><a href='localhost/dailyWrote/src/newPassword.php?key=".$key."&email=".$email."&action=reset&' target='_blank'> This Link </a></p>";
          $output.="<p>If you didn't want to change your password, ignore this</p>";
          
          

          

          $body=$output;
          $subject = "Password Recovery -- The DailyWrote";
          $emailTo = $email;
          $fromserver = "noreply@thedailywrote.com"; 
          $headers = "From: $fromserver\r\n";
          $mail->addAddress($email, 'sergio'); 
          $mail->Subject  ="Password Recovery";
          $mail->Body=$body;
          $mail->send();
          
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Recover your password</title>
</head>
<body>

    <h1 class="text-light text-center">The DailyWrote</h1>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-6 text-center mx-auto">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="">
            <h3 class="text-light">Recover your password</h3>
            <div class="form-group">
                <label for="email" class="text-light">Email</label >
                <input type ="email" name="email" id="email">
                <p class="text-light mt-1" id="notice">Let's check if this email is <span id="check">alreayd registered</span></p>
            </div>
                <input type="submit" value="Send" class="btn btn-primary mt-1" id="send">
        </form>
        <div class="alert alert-danger" role="alert" id="warning">
            <p>Invalid Email. This email is either already registered or is not a valid email, please try again</p>
        </div>
        </div>
    </div>
</div>
<script>
     let email = document.getElementById("email");
     let check = document.getElementById("check");
     let notice = document.getElementById("notice");
     let send = document.getElementById("send");
     let warning=document.getElementById("warning");
     warning.style.display="none";
     notice.style.display="none";
     let registeredIn = false;
     
     function validEmail(email){
            let valid = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

            if(valid.test(email)){
                return true;
            }else return false;
        }

     email.addEventListener("input", ()=>{
        let emailValue = email.value;
        if(validEmail(emailValue))
          notice.style.display="block";
          fetch('../Ajax/getEmail.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(emailValue),
        })
        .then(response => response.json())
        .then(data=>{
            console.log(data);
            if(!data.error){
                if(data.registered){
                    check.classList.remove("bg-danger");
                    check.classList.add("bg-success");
                    registeredIn = true;
                }else{
                    check.classList.remove("bg-success");
                    check.classList.add("bg-danger");
                    registeredIn = false;
                }
            }
        })
     });

     send.addEventListener("click", (event)=>{
        if(registeredIn === false){
            event.preventDefault();
            warning.style.display="block";
        }else{
            warning.style.display="none";
        }
     })

     
</script>
</body>
</html>