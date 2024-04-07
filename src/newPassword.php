<?php
$ready = false;
session_start();
if(!isset($_SESSION["token"])){
    $_SESSION["token"] =  md5(uniqid(mt_rand(), true));
}
include '../src/includes/autoloader.inc.php';
if (!isset($_SESSION["complete"])&& isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"])){
    $recovery = new Recover();
    if(!$recovery->checkIfKeyExists($_GET["key"])){
        $error = "<h2 class='text-center text-light'>Invalid Link</h2>";
    $error .= "<p class='text-center text-light'>The link you clicked is either invalid or expired</p>";
    echo $error;
    exit();  
    }else{
        $key = $_GET["key"];
        $email = $_GET["email"];
        $curDate = date("Y-m-d H:i:s");
        $row = $recovery->getKey($email, $key);
        if($row == "" || $curDate > $row["expDate"]){
            $error = "<h2>Invalid Link</h2>";
            $error .= "<p>The link you clicked is either invalid or expired</p>";
        }else{
          $ready = true;
        
            // $recovery->deleteAllKeys($email);
          
        }
    }
   
}else{
    $error = "<h2 class='text-center text-light'>Invalid Link</h2>";
    $error .= "<p class='text-center text-light'>The link you clicked is either invalid or expired</p>";
    echo $error;
    exit();
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
    <title>Set a New Password</title>
</head>
<body>
  <?php 
  if($ready == true){
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 text-center mx-auto">
                <h1 class="text-center text-light">The DailyWrote</h1>
                <h3 class="text-center text-light">Set a new password</h3>
             <form action="successPassword.php" method ="POST">
                <div class="form-group">
                    <label for="pass1" class="text-light">Introduce your new password</label>
                    <div class="d-flex">
                    <input type="password" name="pass1" id="pass1" class="form-control">
                    <i class="bi bi-eye-fill bg-light p-2" id="eye"></i>
                    </div>
                    <p class="text-light">The password must contain a <span class="must">cap letter</span>, <span class="must">a number</span>,a <span class="must">special character</span> and more than <span class="must"> 8 characters</span></p>
                </div>
                <div class="form-group">
                    <label for="pass2" class="text-light">Repeat the password</label>
                    <div class="d-flex">
                    <input type="password" name="pass2" id="pass2" class="form-control">
                    <i class="bi bi-eye-fill bg-light p-2" id="eye"></i>
                    </div>
                    <p class="text-light">Both passwords <span class="must">match!</span></p>
                </div>
                <div style="display: none;">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                </div>
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <div class="form-group">
                    <input type="submit" value="Send password" name="send" id="send">
                </div>
             </form>
             <div class="alert alert-danger" role="alert" id="warning">
               <p>Invalid Email. This email is either already registered or is not a valid email, please try again</p>
            </div>
            </div>
        </div>
    </div>
    <?php
  }
?>
<script>
        let pass1 = document.getElementById("pass1");
        let pass2 = document.getElementById("pass2");
        let musts = document.querySelectorAll(".must")
        musts.forEach((must)=>{must.classList.add("bg-danger");});
         let send = document.getElementById("send");
         let warning = document.getElementById("warning");
         warning.style.display="none";

        pass1.addEventListener("input",()=>{
            let password = pass1.value;
            if(hasCapLetter(password)){
              musts[0].classList.remove("bg-danger");
              musts[0].classList.add("bg-success");
            }else{
                musts[0].classList.remove("bg-success");
                musts[0].classList.add("bg-danger");
            }
            if(hasANumber(password)){
              musts[1].classList.remove("bg-danger");
              musts[1].classList.add("bg-success");
            }else{
                musts[1].classList.remove("bg-success");
                musts[1].classList.add("bg-danger");
            }
            if(specialChar(password)){
              musts[2].classList.remove("bg-danger");
              musts[2].classList.add("bg-success");
            }else{
                musts[2].classList.remove("bg-success");
                musts[2].classList.add("bg-danger");
            }
            if(password.length > 7){
                musts[3].classList.remove("bg-danger");
                musts[3].classList.add("bg-success");
            }else{
                musts[3].classList.remove("bg-success");
                musts[3].classList.add("bg-danger");
            }
        })

        pass2.addEventListener("input",()=>{
            let pass2V = pass2.value;
            if(pass2V === pass1.value){
                musts[4].classList.remove("bg-danger");
                musts[4].classList.add("bg-success");
            }else{
                musts[4].classList.remove("bg-success");
                musts[4].classList.add("bg-danger");
            }
        })

        
  function allGood() {
    return [...musts].every((must) => must.classList.contains("bg-success"));
}

        send.addEventListener("click", (event)=>{
              if(!allGood()){
                event.preventDefault();
                warning.style.display="block";
              }
        })


        function specialChar(password){
            return /[!@#$%^&*(),.?":{}|<>]/.test(password);
        }

        function hasANumber(password){return /\d/.test(password);}

        function hasCapLetter(password){return /[A-Z]/.test(password);}
</script>
</body>
</html>