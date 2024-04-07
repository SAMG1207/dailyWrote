<?php
require_once "config.php";
include '../src/includes/autoloader.inc.php';
$user = new User();
if(!isset($_SESSION["username"])){
    header("location:index.php");
    exit();
}else{
  $id = $_SESSION["username"];
  $Myfollowers = $user->showFollows($id);

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
    <link rel="shortcut icon" href="DW.png" type="image/x-icon">
    <title>Followers</title>
</head>
<body>
<div id="main">
<section class="container-fluid">
    <div class="row">
        <div class="col-md-2 mx-auto text-center my-3">
            <h1>My Follows</h1>
          <?php 
          foreach($Myfollowers as $follower){
            echo "<div class = 'follower border border-dark my-1'>";
            if(!$user->isVisible($user->selectUserIdRow($follower["followed_id"])["userName"])){
                echo "<p><strong class='d-block'>".$user->selectUserIdRow($follower["followed_id"])["userName"]."</strong><span> This user doesn't share his posts. You can't get into his profile as long as he keeps his profile private <span></p>";
            }else{
                echo "<a href='user.php?username=".$user->selectUserIdRow($follower["followed_id"])["userName"]."&page=1' class='text-decoration-none text-dark'>".$user->selectUserIdRow($follower["followed_id"])["userName"]."</a>";
            }
            echo "</div>"; 
          }
          ?>
        </div>
    </div>
</section>
</div>

<script>
    let followers = document.querySelectorAll(".follower");
    if(followers.length > 0){
        followers.forEach((follower)=>{
            follower.addEventListener("mouseenter",()=>{
                follower.classList.add("bg-primary");
                follower.classList.add("text-light");
            })
            follower.addEventListener("mouseleave",()=>{
                follower.classList.remove("bg-primary");
                follower.classList.remove("text-light");
            })
        })
    }
</script>
</body>
</html>