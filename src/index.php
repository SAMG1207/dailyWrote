<?php

require_once "config.php";
include '../src/includes/autoloader.inc.php';
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["email"]) && isset($_POST['password']) && ($_POST["token"] === $_SESSION["token"])) {
    $user = new User();
    $email = $user->test_input($_POST['email']);
    $password = $_POST["password"];

    $login = $user->LogIn($email, $password);
  
    if($login){
        $_SESSION['username'] = $user->alreadyIn($email)["user_id"];
        $_SESSION["name"] = $user->alreadyIn($email)["userName"];
        header('location: profile.php');
        exit();
    }else{
        $error_message = "Invalid username or password, please try again.";
    }
}else{
    $_POST["email"]="";
    $_POST["password"]="";
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>DailyWrote</title>
</head>
<body>
    <header>
        <h1 class="display-1 text-light text-center m-2">The DailyWrote!</h1>
    </header>
    
    <div id="cookies">
        <div class="wrapper">
            <div class="d-flex mx-auto">
                <i class="bi bi-cookie"></i>
                <h3>Cookies</h3>
            </div>
            <div class="d-block">
                <p>Accepting this will make your navigation here easier. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis eum ipsa nesciunt asperiores numquam dignissimos temporibus quas alias est, commodi officia repudiandae nam pariatur beatae impedit doloremque accusamus expedita porro.</p>
                </div>
            <div class="buttons">
                <button type="button" id="accept">Accept!</button>
                <button type="button" id="decline">Decline!</button>
            </div>
        </div>
    </div>
   
    <div class="container mt-4">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="row g-3">
                <div class="col-sm-7">
                    <input type="text" class="form-control" placeholder="Email" aria-label="Email" name="email">
                </div>
                <div class="col-sm">
                    <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="password">
                </div>
                   
                <div style="display: none;">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                </div>
                <div class="col-sm">
                    <input type="submit" class="btn btn-primary" value="Log In!" name="logIn">
                </div>
            </div>
           
        </form>
        <div id="error" class="col-sm-7">
            <?php if(isset($error_message)){
                echo " <div class='alert alert-danger' role='alert'>";
                echo $error_message ."</div>";

            }?>
        </div>
      
        
        <div class="row">
            <div class="col-8 d-flex">
                <div class="col-6">
                    <a href="register.php" target="_blank">Not Registered Yet?</a>
                </div>
                <div>
                    <a href="recoverpsw.php" target="_blank" class="col-6 mr-md-3">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="container mt-3 p-2">
        <div class="row">
            <div class="col-md-8  mx-auto">
                <h1 class="text-light text-center">Your DailyWrote...</h1>
                <h5 class="text-light text-center">This is the place where you find yourself free to post whatever you want</h5>
                <p class="text-light text-justify">We encourage our users to write about everything, inside obvious respectful boundaries. 
                    So, we want to make you feel yourself free to say whatever you want to say. 
                </p>
                <p class="text-light text-justify">
                    Here, the only banned topics are the ones that could harm other people. But we never would ban your account for posting something
                    that is your true opinion about politics, social issues, sports... The only banned things are the ones that are disrespectful or involves illegal activities.
                </p>
                
            </div>
           
        </div>
    </div>

    <div class="container mt-3 p-2">
        <div class="row">
            <div class="col-md-6 text-center mx-auto">
                <img src="img/statue.png" alt="" class="img-fluid" id="statue">
            </div>
           
        </div>
    </div>
<footer>
    <div class="container-fluid">
        <div class="row">
            <p text-muted>This project is part of the Sergio Moreno's repository. Its made in a local environment</p>
            <p text-muted>This project is made with PHP (Composer and PHP MAiler), MySQL, JavaScript and Bootstrap</p>
            <p>PHP MAILER could not work if the sender email is not changed. The password is not shared</p>
        </div>
    </div>
</footer>
    <script>
        
        let cookies = document.getElementById("cookies");
        cookies.style.display="none";

        document.addEventListener("DOMContentLoaded", function(){
           cookies.style.display="block";

           let accept = document.getElementById("accept");
           let decline = document.getElementById("decline");

           if (document.cookie.includes('acceptedCookies=true')) {
            cookies.style.display = "none"; // Oculta el banner si la cookie ya ha sido aceptada
        } else {
            cookies.style.display = "block"; // Muestra el banner si la cookie no ha sido aceptada
        }

           accept.addEventListener("click", function(){
            document.cookie = "acceptedCookies=true; expires=Thu, 01 Jan 2025 00:00:00 UTC; path=/";
            cookies.style.display = "none";
           })

           decline.addEventListener("click", function(){
            cookies.style.display = "none";
           })

        })

        //   let intro = document.getElementById("intro");
        //   intro.style.display = "none";
        //   let introText = intro.textContent;
        //   let words = introText.split(/\s+/);
        //   console.log(words);

        //   words.forEach(function (word, index) {
        //       let span = document.createElement("span");
        //       span.textContent = word + " ";
        //       span.classList.add("text-light");
        //       span.style.display = "none";
        //       if (index === 0) {
        //         span.style.marginLeft = "8px";
        //       }

        //       document.body.appendChild(span);

        //       setTimeout(function () {
        //         span.style.display = "inline";
        //       }, index * 400);
        //   });
       
    </script>
</body>
</html>
