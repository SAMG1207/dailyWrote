<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';
if(!isset($_SESSION["username"])){
    header("location:index.php");
    exit();
}else{
    $user = new User();
    $art = new Article();
    
    $row = $user->selectUserIdRow($_SESSION['username']);
    $nombre = $row["userName"];
    $strikes = $art->countStrikes($_SESSION["username"]);
    if($strikes > 1){
        session_destroy();
        session_unset();
         
       header("location: banned.html");
       exit();
        
    }
    $followers = $user->countFollowers($_SESSION["username"]);
    $followed = $user->countFollows($_SESSION["username"]);
    $quotes = $user->selectQuotes();
    $min = 0;
    $max = count($quotes) - 1 ; 
    $random = rand($min, $max);
    $randomQuote = $quotes[$random];
    $prhase = $randomQuote["quote"];
    $author = $randomQuote["author"];


    if( $_SERVER["REQUEST_METHOD"] =="POST" && isset($_POST["send"])){
        $title = $user->test_input($_POST["title"]);
        $fontType = $_POST["letter"];
        $fontSize = $_POST['size'];
        $entryPreEvaluated = htmlspecialchars($_POST["entry"], ENT_QUOTES, 'UTF-8');
        $visible = isset($_POST['public']);
        $article = $user->test_input($_POST["entry"]);

        $entry = new Article();
        $entry -> insertArticle($_SESSION['username'], $visible, $title, $fontType, $fontSize, $article);
    }
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["end"])){
        session_destroy();
        session_unset();
        header("location: profile.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo "<script>let strikes = " . json_encode($strikes) . ";</script>"; ?>
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <link rel="stylesheet" href="css/background.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <script src="javascript/dark-mode.js" defer></script> -->
    <title>Profile</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">
    <a href="" class="navbar-brand text-info fw-semibold fs-4">DailyWrote</a>
    <button class="navbar-toggler" type="button"
     data-bs-toggle="offcanvas"
     data-bs-target="#sideBar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <section 
    class="offcanvas offcanvas-start bg-dark" 
    id="sideBar" 
    tabindex="-1">
        <div class="offcanvas-header" data-bs-theme="dark">
            <h5 class="offcanvas-title text-info"><?php echo $nombre ?></h5>
            <button class="btn-close" type="button" 
            aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
            <ul class="navbar-nav fs-5 justify-content-evenly">
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="">Home</a></li>
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="posts.php" target ="_blank">Posts</a></li>
                <li class="navi-item p-3 py-md-1"><a href="about.php" target="_blank" class="nav-link">About</a></li> 
                <li class="navi-item p-3 py-md-1"><a href="settings.php" target="_blank" class="nav-link">Settings</a></li>
                <li class="navi-item p-3 py-md-1">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <input type="submit" value="End Session" name="end" class="btn btn-light">
                    </form>
                </li>
            </ul>

            <div class="d-lg-none align-self-center py-3">
                <a href=""><i class="bi bi-twitter-x px-2 text-info fs-2"></i></a>
                <a href=""><i class="bi bi-facebook px-2 text-info fs-2"></i></a>
                <a href=""><i class="bi bi-whatsapp px-2 text-info fs-2"></i></a>
                <a href=""><i class="bi bi-github px-2 text-info fs-2"></i></a>
            </div>
        </div>
    </section>
</div>
</nav>



</section>
<div id="main">
<section class="container-fluid" id="warning">
    <div class="row text-center bg-danger">
        <p>Your account has a strike already. If you get one more, your account will be banned</p>
    </div>
</section>
<section class="container">
<div class="row">
    <div class="col-md-10 mx-auto">
        <blockquote class="blockquote text-center">
            <p class="my-3 "><?php echo $prhase?></p>
            <footer class="blockquote-footer"><?php echo $author?></footer>
        </blockquote>
    </div>
</div>
<section class="container-fluid">
    <div class="row mt-3">
        <div class="col-lg-3 mx-auto position-relative">
            <input type="text" name="" placeholder="Look for other users" class="h-2 d-block" id ="userSearch">
            <div>
                <ul id="usersAsWrite" class="list-group position-absolute top-101 start-1 w-22 d-none list-unstyled"></ul> 
            </div>  
        </div>
        <div class="col-lg-3 mx-0 mt-1">
            <?php echo "<h3> Welcome ".$nombre."</h3>" ?>
        </div>
        <div class="col-md-3">
            <i class="bi bi-person-plus-fill"><span><?php echo $followers?></span></i><a href="follower.php" id="followerWindow"> Followers</a>
        </div>
        <div class="col-md-3">
            <i class="bi bi-person-heart"><span><?php echo $followed?></span></i><a href="follow.php" id="followWindow"> Follows</a>
        </div>
    </div>
</section>



<section>   
    <div class="container-fluid">
        <div class="row m-5">
            <div class="col-4 mx-auto">
            <h2 class="d-inline">New Post</h2><i class="bi bi-arrow-down-square-fill mx-1" id="arrow"></i>
                <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" id="entrance">
                  <input type="text" name="title" id="title" placeholder="Title" class="d-block mb-2">
                  <div class="alert alert-danger role='alert" id="noTitle">
                        <p>Where is the title?</p>
                  </div>
                  <div class="d-flex">
                   <select name="letter" id="font-family">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Helvetica">Helvetica</option>
                    <option value="Times">Times</option>
                    <option value="Verdana">Verdana</option>
                    <option value="Calibri">Calibri</option>
                   </select>
                   <select name="size" id="font-size">
                    <?php
                    $i = 10;
                    while($i < 42){
                        echo "<option value=$i>$i</option>";
                        $i = $i+2;
                    } 
                    ?>
                   </select>
                   
                  </div>
                  <p><span id="letters" class="text-black">1000</span>/1000</p>
                  <textarea name="entry" id="entry" cols="70" rows="10" placeholder="Feel yourself free to write whatever you want"></textarea>
                  <div class="alert alert-danger role='alert" id="noEntry">
                        <p>Where is the article?</p>
                  </div>
                  <input type="checkbox" name="public" id="">
                  <label for="public">I want this post to be public</label>
                  <input type="submit" value="Send!" class="bg-primary d-block" name="send" id="send">
                </form>
            </div>
        </div>
    </div>
</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>

    let warning = document.getElementById("warning");
    warning.style.display ="none";
    let userSearch = document.getElementById("userSearch");
    let searchForm = document.getElementById("searchForm");
    let title = document.getElementById("title");
    let divTitle = document.getElementById("noTitle");
    divTitle.style.display="none";
    let divEntry = document.getElementById("noEntry");
    divEntry.style.display ="none";
     let arrow = document.getElementById("arrow");
     let entrance = document.getElementById("entrance");
     let counter = 0;
     let send = document.getElementById("send");
     let entry = document.getElementById("entry");
     let letters = document.getElementById("letters");
     entrance.style.display="none"

     if(strikes == 1){
        warning.style.display="block";
     }

     arrow.addEventListener("click", function(){
        if(counter%2!=0){
            entrance.style.display="none"
        }else{
            entrance.style.display="block";
        }
        counter++
     })

     /**
      * PARA EL CAMBIO DE LETRA
      */

      function applyChanges(){
          let fontFamily = document.getElementById("font-family").value;
          let fontSize = document.getElementById("font-size").value;

          document.getElementById("entry").style.fontFamily =fontFamily;
          document.getElementById("entry").style.fontSize = fontSize+"px";
      }

        document.getElementById("font-family").addEventListener("change", applyChanges);
        document.getElementById("font-size").addEventListener("change", applyChanges);

        send.addEventListener("submit", (event)=>{
          
        });

    /**
     * COUNTING LETTERS
     */

     function countingLetters() {
    var article = entry.value;
    var countedLetters = article.length;
    letters.textContent = countedLetters;

    if (countedLetters >= 750 && countedLetters <900) {
        
        letters.classList.replace("text-black","text-danger-emphasis");
        if (letters.classList.contains("text-danger")) {
            letters.classList.replace("text-danger", "text-danger-emphasis");
        }
    }if (countedLetters >= 900 && countedLetters <975) {
        letters.classList.replace("text-danger-emphasis", "text-danger");
        if (letters.classList.contains("text-warning")) {
            letters.classList.replace("text-warning", "text-danger-emphasis");
        }
    }if (countedLetters >= 975) {
        letters.classList.replace("text-danger", "text-warning");
    }if(countedLetters < 750) {
        letters.classList.add("text-black");
        if (letters.classList.contains("text-danger-emphasis")) {
            letters.classList.replace("text-danger-emphasis", "text-black");
        }
    }

    if (countedLetters > 1000) {
        entry.value = entry.value.substring(0, 1000);
        letters.textContent = "1000";
    }
}
     entry.addEventListener("input", countingLetters);

     /**
      * SEND POST
      * 
      */
  
      send.addEventListener("click", (event)=>{
         if(entry.value==""){
            divEntry.style.display="block";
            event.preventDefault();
         }
         if(title.value == ""){
            divTitle.styl.display="block";
            event.preventDefault();
         }
      })

      /**
       * SEARCH FORM
       */
      let usersAsWrite = document.getElementById("usersAsWrite");
  
       userSearch.addEventListener("input", function(){
        let width = userSearch.offsetWidth + "px";
        usersAsWrite.innerHTML="";
        let userSearchValue = userSearch.value;
        let fetchOptions = {
         method: "POST",
         headers: {
             "Content-Type": "application/x-www-form-urlencoded",
         },
       body: `userName=${encodeURIComponent(userSearchValue)}`,
    };

    fetch("../Ajax/getUsers.php", fetchOptions)
    .then(response =>{
        if(!response.ok){
            throw new Error("Error");
        }
        return response.json();
    })
    .then(users =>{
      users.forEach(user => {
        const li = document.createElement("li");
        
        const userList = document.createElement("a");
        userList.className = "list-group-item";
        userList.innerHTML = user.userName;
        // userList.href = "user.php?username=" + encodeURIComponent(user.userName);
        userList.href = "user.php?username=" + encodeURIComponent(user.userName) +"&page=1";
        li.append(userList)
        usersAsWrite.appendChild(li);
        userList.classList.add("d-block")
        userList.style.width =width;
        userList.addEventListener("mouseover", ()=>{
            userList.classList.add("text-light");
            userList.classList.add("bg-primary");
        })
        userList.addEventListener("mouseout",()=>{
            userList.classList.remove("text-light");
            userList.classList.remove("bg-primary");
        })
        if (userSearchValue.length > 0) {
                    usersAsWrite.classList.remove("d-none");
                } else {
                    usersAsWrite.classList.add("d-none");
                }
      });
      })
    })

    // OPEN THE FOLLOW AND FOLLOWERS WINDOWS
    function openWindow(url, windowOption){
        const options = "width=400,height=600,top=100,left=100,scrollbars=yes,resizable=yes";
        window.open(url, windowOption, options);
    }
    
     document.querySelector("#followerWindow").addEventListener("click", (event)=>{
        event.preventDefault();
        openWindow("follower.php", "Followers");
     });

     document.querySelector("#followWindow").addEventListener("click", (event)=>{
        event.preventDefault();
        openWindow("follow.php", "Follows");
     })

</script>
</body>
</html>