<?php
session_start();
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
}
include '../src/includes/autoloader.inc.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)
&& isset($_POST["email"])
&& isset($_POST["password"])
&& isset($_POST["username"])
&& isset($_POST["overAge"])
&& isset($_POST["token"])
&& ($_POST["token"] === $_SESSION["token"])){
    $user = new User();

    $email = $user->test_input($_POST["email"]);
    $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    $password = $user->test_input($_POST["password"]);
   
    $passwordValidated =$user->validatePassword($password);
    $username = $user->test_input($_POST["username"]);
    $public = isset($_POST['public'])?$_POST['public']:0;
    $check = isset($_POST["overAge"]);
    if(isset($check) && $passwordValidated && $validEmail){
        $user->insertUser($username, $password, $email, $public);
        $_SESSION["well"]="Successful registration";
    }else{
        $error = "Please check if everything is ok";
        $well=false;
    }

    if(isset($_SESSION["well"])){
        echo "<div class ='alert alert-success text-center'><p>".$_SESSION["well"]."</p> <a href='index.php'>Go back to main!</a></div>";
        unset($_SESSION["well"]);
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
    <?php if(isset($error)){echo "<script>let error = " . json_encode($error) . ";</script>";}; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="javascript/dark-mode.js" defer></script>
    <title>Your DailyWrote!</title>
</head>
<body>
    <header>
        <a href="index.php"><h1 class="text-light text-center m-2">The DailyWrote!</h1></a>
        <p class="text-light text-center m-2">Register Form</p>
    </header>

    <div class="container-fluid">
      <div class="row">
        <div class="col-6 text-center mx-auto">
        <div class="col-6 text-center mx-auto">
       
          

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="registerForm">
                <div class="mb-3">
                    <label for="email" class="text-light">Email address</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <p id="availableEmail" class="text-light">This email <span class="must"></span></p>
                <div class="mb-3">
                    <label for="password" class="text-light">Password</label>
                    <div class="d-flex">
                      <input type="password" name="password" id="password" class="form-control">
                      <i class="bi bi-eye-fill bg-light p-2" id="eye"></i>
                    </div>
                    <p class="text-light">The password must contain a <span class="must">cap letter</span>, <span class="must">a number</span>,a <span class="must">special character</span> and more than <span class="must"> 8 characters</span></p>
                </div>
                <div class=" input-group mb-3 d-block">
                    <label for="username" class="text-light">User</label>
                    <div class="d-flex">
                      <span class="input-group-text ml-3" id="basic">@</span>
                      <input type="text" name="username" id="username" class="form-control" placeholder="We check if this user is available">
                    </div>
                    <p id="availableUsername" class="text-light">
                        <span id="avNotice">This user name is <span class="must">available.</span></span>Lenght must be more than three chars!
                    </p>
                </div>
                <div class="mb-3 form-check text-center">
                    <input type="checkbox" name="public" id="public">
                    <label for="public" class="form-check-label text-light">I want to be found by anyone in this app, if you cant decide, you can do this later</label>
                </div>
                <div class="mb-3 form-check text-center">
                    <input type="checkbox" name="overAge" id="overAge">
                    <label for="overAge" class="form-check-label text-light">I am over 18</label>
                    <p class="text-center text-light">The DailyWrote is not responsible for whether the answer given by the user is wrong or true. </p>
                </div>
                <p class="text-center text-light">We dont wanna know your name, your gender, your nationality or your phone, we encourage privacy</p>
                
                <div style="display: none;">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                </div>


                <div class="col-sm text-center mb-2">
                    <input type="submit" class="btn btn-primary" value="Sign In!" id="signButton">
                </div>
            </form>
        </div>
      </div>
    </div>
    <script>
      

        let eye = document.getElementById("eye");
        let passwordInput = document.getElementById("password");
        let email = document.getElementById("email");
        let password = passwordInput.value;
        let userName = document.getElementById("username");
        let musts = document.querySelectorAll(".must");
        let avNotice = document.getElementById("avNotice");
        avNotice.style.display="none";
        let availableEmail = document.getElementById("availableEmail");
        availableEmail.style.display="none";
     
        let overAge = document.getElementById("overAge");

        eye.addEventListener("mouseenter",()=>{passwordInput.type="text"});
        eye.addEventListener("mouseleave", ()=>{passwordInput.type="password"});

       /**
        * CONTROLLING THE EMAIL IS USED ALREADY
        */

        email.addEventListener("input", () => {
    let availableEmail = document.getElementById("availableEmail");
    availableEmail.style.display = "none";

    let emailValue = email.value;
    if (validEmail(emailValue)) {
        availableEmail.style.display = "block";
        fetch('../Ajax/getEmail.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'email=' + encodeURIComponent(emailValue),
        })
        .then(response => response.json())
        .then(data => {
            console.log("Server Response:", data);
            if (!data.error) {
                if (!data.registered) {
                    console.log("Server Response:", data);
                    musts[0].innerHTML="is available!"
                    musts[0].classList.remove("bg-danger");
                    musts[0].classList.add("bg-success");
                } else {
                    musts[0].innerHTML="is already on use :("
                    musts[0].classList.remove("bg-success");
                    musts[0].classList.add("bg-danger");
                }
            } else {
                console.error("Error checking email availability", data.error);
                // Mostrar un mensaje de error en HTML si es necesario
            }
        })
        // .catch(error => {
        //     console.error("Error checking email availability", error);
          
        // });
    }
});

        musts.forEach((must)=>{must.classList.add("bg-danger");});

        passwordInput.addEventListener("input", ()=>{
            let password = passwordInput.value;
            if(hasCapLetter(password)){
                musts[1].classList.remove("bg-danger");
                musts[1].classList.add("bg-success");
            }else{
                musts[1].classList.remove("bg-success");
                musts[1].classList.add("bg-danger");
            }
            if(hasANumber(password)){
                musts[2].classList.remove("bg-danger");
                musts[2].classList.add("bg-success");
            }else{
                musts[2].classList.remove("bg-success");
                musts[2].classList.add("bg-danger");
            }
            if(specialChar(password)){
                musts[3].classList.remove("bg-danger");
                musts[3].classList.add("bg-success");
            }else{
                musts[3].classList.remove("bg-success");
                musts[3].classList.add("bg-danger");
            }
            if(password.length > 7){
                musts[4].classList.remove("bg-danger");
                musts[4].classList.add("bg-success");
            }else{
                musts[4].classList.remove("bg-success");
                musts[4].classList.add("bg-danger");
            }
        })


        function validEmail(email){
            let valid = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

            if(valid.test(email)){
                return true;
            }else return false;
        }
        function specialChar(password){
            return /[!@#$%^&*(),.?":{}|<>]/.test(password);
        }

        function hasANumber(password){return /\d/.test(password);}

        function hasCapLetter(password){return /[A-Z]/.test(password);}

      /**
       * CONTROLLING THE USERNAME AVAILABILITY
       */

       userName.addEventListener("input", () => {
    let userValue = userName.value;
    avNotice.style.display = "none";
    if (userValue.length >= 3) {
        avNotice.style.display = "block";
        fetch('../Ajax/getUserName.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'username=' + encodeURIComponent(userValue),
        })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
            if (!data.registered) {
            console.log("Server Response:", data);
             musts[5].classList.remove("bg-danger");
             musts[5].classList.add("bg-success");
            }else{
                musts[5].classList.remove("bg-success");
                musts[5].classList.add("bg-danger");
            }     
})
             .catch(error => {
               
                 console.error("Error checking username availability", error);
                 response.text().then(text => console.error("Server response text:", text));
             });
    }
});

 /**
  * Sending
  */ 

  musts.forEach((must)=>{must.classList.contains("bg-success");})

  function allGood() {
    return [...musts].every((must) => must.classList.contains("bg-success"));
}
   
   signButton.addEventListener("click", (event)=>{
    if(!allGood() || !overAge.checked){
          event.preventDefault();
    }
   })
    </script>
</body>
</html>