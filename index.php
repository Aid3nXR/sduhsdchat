<?php
session_start();
if(isset($_GET['logout'])){    
    // Simple exit message
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);

    session_destroy();
    header("Location: index.php"); // Redirect the user 
    exit();
}

if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}

function loginForm(){
    echo 
    '<div id="loginform"> 
        <p>Please enter your name to continue!</p> 
        <form action="index.php" method="post"> 
            <label for="name">Name &mdash;</label> 
            <input type="text" name="name" id="name" /> 
            <input type="submit" name="enter" id="enter" value="Enter" /> 
        </form> 
    </div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Tuts+ Chat Application</title>
    <meta name="description" content="Tuts+ Chat Application" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<?php
if(!isset($_SESSION['name'])){
    loginForm();
}
else {
?>
    <div id="wrapper">
        <div id="menu">
            <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
            <p class="sorry">Sorry but the chat will shut down sometimes :/ (out of my control)</p>
            <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
        </div>

        <div id="chatbox">
        <?php
        if(file_exists("log.html") && filesize("log.html") > 0){
            $contents = file_get_contents("log.html");          
            echo $contents;
        }
        ?>
        </div>

        <form name="message" id="messageform" action="">
            <input name="usermsg" type="text" id="usermsg" autocomplete="off" />
            <input type="submit" id="submitmsg" value="Send" />
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#messageform").submit(function(e) {
                e.preventDefault(); // Prevent default form submit
                var clientmsg = $("#usermsg").val().trim();
                if(clientmsg.length > 0){
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                }
            });

            function loadLog() {
                var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; 
                $.ajax({
                    url: "log.html",
                    cache: false,
                    success: function (html) {
                        $("#chatbox").html(html);
                        var newscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                        if(newscrollHeight > oldscrollHeight){
                            $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); 
                        }	
                    }
                });
            }

            setInterval(loadLog, 2500);

            $("#exit").click(function () {
                var exit = confirm("Are you sure you want to end the session?");
                if (exit) {
                    window.location = "index.php?logout=true";
                }
            });
        });
    </script>
<?php
}
?>
</body>
</html>
