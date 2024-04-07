<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';
$tester = new user();
if(!isset($_SESSION["username"]) || !isset($_GET["username"])){
    header("location:index.php");
    exit();
}else{ 
    if(isset($_GET["username"])
     && preg_match("/^[a-zA-Z0-9]+$/", $_GET["username"])&&
     $tester->test_input($_GET["username"])&&
      isset($_GET["page"])
      && preg_match("/^[0-9]+$/", $_GET["page"])){
        
        $username = $_GET["username"];
        $usuario = new User();
        if(!$usuario->isVisible($username)){
        session_unset();
        session_abort();
        session_destroy();
        header("location:index.php");
        exit(); 
        }else{

            $art = new Article();
            $name = $art->test_input($username);
            $user_id = $art->giveMeUserId($name);
            
            $articlesFromOther = $art->retrieveArticlesFromOtherUser($user_id);
           
            $pages = $art->givemePages($user_id);
            $currentPage = $_GET["page"] -1;
            $startPage = max(1, $currentPage - 2);
            $endPage = min($pages, $startPage + 4);
            
            $start = $currentPage * 4;
            $articles = $art->retrieveArticlesFromOtherUserPaginated($user_id, $start, 4);
            $myid = $_SESSION["username"];
            
           
            
            // $usuario = new User();
         
            // $follow = $usuario->followCheck($user_id, $myid);
        }
        
    }else{
        session_unset();
        session_abort();
        session_destroy();
        header("location:index.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style1.css">
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <link rel="stylesheet" href="css/background.css">
    <!-- <script src="javascript/script.js"defer></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <script src="javascript/dark-mode.js" defer></script> -->
    <title><?php echo $username . "'s Profile"; ?></title>
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
                <li class="navi-item p-3 py-md-1"><a class="nav-link" href="profile.php">Home</a></li>
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


<div id="main">
<section class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="text-center my-4"><h1><?php echo $username . "'s Profile"; ?></h1>
            <button class="bg-primary" id="follow">
             
            </button>
            <p>Read <?php echo $username . "'s" ?> posts.</p>
        </div>
        <div class="col-md-10 mx-auto" id ="articles">
          <?php
           if(empty($articles)){
            $notice =  $username." has not written any article yet";
            echo "<p class ='text-center'>".$notice."</p>";
        }else{
            foreach($articles as $article){
                $title = $article["title"];
                $date = $article["date"];
                $fontSize = $article["font_size"];
                $fontType = $article["font_family"];
                $entry = $article["entry"];

                echo"<div class='border border-dark my-2'>";
                  echo"<div class='p-3'>";
                    echo "<h3>".$title."</h3>";
                    echo "<p>".$date."</p>";
                    echo "<p>".$entry."</p>";
                  echo"</div>";
                echo"</div>";
            }
        }
          ?>
        </div class="container-fluid">
            <div class="row">
                <div class=" col-md-6 mx-auto text-center">
                    <div class="d-flex">
                    <?php 
                     if($pages>0){
                        echo"<a href='user.php?username=".$username."&page=1'> <i class='bi bi-chevron-double-left'></i>";
                         for($i = $startPage; $i <= $endPage; $i++){
                         echo "
                            <div class='mx-2 text-center bg-secondary h-125' style='width: 50px'>
                           
                            <span><a href='user.php?username=".$username."&page=".$i."' class='text-decoration-none text-dark'>".$i."</a></span>
                            </div>";
                            } 
                       
                          echo "<a href='user.php?username=".$username."&page=".$pages."'>";
                          echo "<i class='bi bi-chevron-double-right'></i>";
                          echo "</a> </div>";  
                          
                        
                          

                        //   for($i = $startPage; $i <= $endPage; $i++){
                        //     echo "
                        //        <div class='mx-2 text-center bg-secondary h-125' style='width: 50px'>
                              
                        //        <span><a href='user.php?username=".$username."&page=".$i."' class='text-decoration-none text-dark'>".$i."</a></span>
                        //        </div>"
                        //        ;}
                                 
                            //    echo "<a href='user.php?username=".$username."&page=".$pages."'>"; 
                            //    echo "<i class='bi bi-chevron-double-right'></i> </a> </div>";;
   
                        }

                    ?>

             

                    <div >
                        <?php if($pages==0){
                            echo "<p class='text-center'>Page 1 of 1</p>";
                        }else{
                       
                           echo "<p class='text-center'>Page ".$_GET["page"]." of ".$pages."</p>";
                        }
                            ?>
                     
                   </div>
                </div>
            </div>
        </div>  
</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>
    let userId = <?php echo json_encode($user_id); ?>;
    let myId = <?php echo json_encode($myid); ?>;
    
    let followed =  document.getElementById("follow"); // Definir la variable followed en el ámbito global

    document.addEventListener("DOMContentLoaded", function () {
        // followed = document.getElementById("follow");
        isFollowed();
        
    });

   

    function isFollowed() {
        var fetchOptions = {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `userId=${encodeURIComponent(userId)}& myId=${encodeURIComponent(myId)}`,
        };

        fetch("../Ajax/doIFollowYou.php", fetchOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error");
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                if (data.follow) {
                    followed.innerText = "Unfollow"
                    followed.addEventListener("click", unfollowUser);

                } else {
                    followed.innerText = "Follow";
                    followed.addEventListener("click", followUser);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }


       function followUser(){
        var fetchOptions = {
             method: "POST",
             headers: {
                 "Content-Type": "application/x-www-form-urlencoded",
             },
             body: `userId=${encodeURIComponent(userId)}& myId=${encodeURIComponent(myId)}`,
         };

         fetch("../Ajax/follow.php", fetchOptions)
         .then(response => response.json())
         .then(data => isFollowed())
         .catch(error => console.log(error))
       }
       

       
       function unfollowUser(){
        var fetchOptions = {
             method: "POST",
             headers: {
                 "Content-Type": "application/x-www-form-urlencoded",
             },
             body: `userId=${encodeURIComponent(userId)}& myId=${encodeURIComponent(myId)}`,
         };

         fetch("../Ajax/unfollow.php", fetchOptions)
         .then(response => response.json())
         .then(data => isFollowed())
         .then(data => console.log(data))
         .catch(error => console.log(error))
       }

 

//    unfollowUser()

      
</script>

</body>
</html>