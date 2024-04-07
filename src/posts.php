<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';

if(!isset($_SESSION["username"])){
    header("location:index.php");
    exit();
}else{
    $userId = $_SESSION["username"];
    $user = new User();
    $nombre = $user->selectUserIdRow($userId)["userName"];
    
    $article = new Article();
    $totalArticles = count($article->retrieveArticles($userId));
   
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">    
    <link rel="stylesheet" href="css/background.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <?php echo "<script>let userId = " . json_encode($userId) . ";</script>"; ?>
    <?php echo "<script>let totalArt = " . json_encode($totalArticles) . ";</script>"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous" defer></script>
    <script src="javascript/dark-mode.js" defer></script>
    <title>Entries</title>
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
    <section class="offcanvas offcanvas-start bg-dark" 
    id="sideBar" 
    tabindex="-1">
        <div class="offcanvas-header" data-bs-theme="dark">
            <h5 class="offcanvas-title text-info"><?php echo $nombre ?></h5>
            <button class="btn-close" type="button" 
            aria-label="Close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between px-0">
            <ul class="navbar-nav fs-5 justify-content-evenly">
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="profile.php">Home</a></li>
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="posts.php" target ="_blank">Posts</a></li>
                <li class="navi-item p-3 py-md-1"><a href="about.php" target="_blank" class="nav-link">About</a></li>
                <li class="navi-item p-3 py-md-1"><a href="settings.php" target="_blank" class="nav-link">Settings</a></li>
                <li class="navi-item p-3 py-md-1">
                    <form action="profile.php" method="POST">
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
<div id="main">
  <section class ="container-fluid" id="postContainer">
    <div class="row">
    <div class="col-md-8 mx-auto  mt-2" id="postCol">
         <?php if($totalArticles==0)
         echo "<h3 class='text-center my-4'>You have written nothing yet<h3>"
         ?>
        </div>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
document.addEventListener("DOMContentLoaded", function(){
getArticles();

})

let articlesForPage = 4;
let pages = totalArt/articlesForPage;

function getArticles() {

  const fetchOptions = {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `user_id=${encodeURIComponent(userId)}`,
  };

  fetch("../Ajax/getArticles.php", fetchOptions)
    .then(response => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then(articles => {
      articles.forEach(article => {
      const div = document.createElement("div"); 
      div.classList.add("mt-2");
      div.classList.add("mb-1");
      div.classList.add("mx-auto");
      div.classList.add("border"); 
      div.classList.add("border-primary");
      div.classList.add("p-2");

      const artTitle = document.createElement("h3");
      artTitle.innerText=article.title;

      const artDate = document.createElement("p");
      artDate.innerText="Written on " + article.date;

      const mainArticle = document.createElement("div");
      mainArticle.style.fontSize = article.font_size +"px";
      mainArticle.style.fontFamily = article.font_family;
      if(article.strike == 1){
        mainArticle.innerText = "THIS ARTICLE HAS CENSORED CONTENT. THIS IS ONE STRIKE. IF MADE AGAIN YOUR ACCOUNT WILL BE BANNED"
      }else{
        mainArticle.innerHTML = article.entry;
      }
      
      mainArticle.classList.add("mx-2");
      
      postCol.appendChild(div);
      div.appendChild(artTitle);
      div.appendChild(artDate);
      div.appendChild(mainArticle);
      const warning = document.createElement("div");
      const deleteArt = document.createElement("button");
      deleteArt.classList.add("text-light");
      deleteArt.innerHTML = "Delete Article";
      deleteArt.classList.add("bg-primary")
      warning.append(deleteArt);
      postCol.appendChild(warning);
       
      deleteArt.addEventListener("click",function(){
        createWindow(article.entry_id, deleteArt)

      });


      });
    })
    .catch(error => {
      console.error("Error fetching articles:", error.message);
      const errorElement = document.createElement("p");
      errorElement.textContent = "Error loading articles. Please try again later.";
      articlesScript.appendChild(errorElement);
    });
}

function deleteArticle(art_id, deleteArt){
  let fetchOptions1 = {
         method: "POST",
         headers: {
             "Content-Type": "application/x-www-form-urlencoded",
         },
       body: `article_id=${encodeURIComponent(art_id)}`,
    };

    fetch('../Ajax/deleteArticle.php' , fetchOptions1)
    .then(response=>
    {
       if(!response.ok){
        throw new Error("Error");
       }
       console.log("Deleted");
       const articleContainer = deleteArt.closest('.border-primary');
       articleContainer.remove();
    })
    location.reload()
}
  

function createWindow(article, deleteArt){
    let opciones = "width=500,height=250,top=100,left=100,scrollbars=yes,resizable=yes";
    let windowWar = window.open(" "," Warning Window", opciones);
   
    fetch("window.html")
    .then(response => response.text())
    .then(html=>{
      windowWar.document.write(html);
      const yes = windowWar.document.getElementById("yes");
       const no = windowWar.document.getElementById("no");
       yes.addEventListener("click", () => {
                deleteArticle(article, deleteArt);
                cerrarVentana(windowWar);
            });

            no.addEventListener("click", () => {
                cerrarVentana(windowWar);
            });
    })
    .catch(error => {
            console.error('Error loading HTML content:', error);
        });
}

function cerrarVentana(ventana) {
            // Cierra la ventana emergente
            ventana.close();
        }
  
</script>
</body>
</html>