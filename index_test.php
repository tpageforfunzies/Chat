<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Chat Module</title>
    <link type="text/css" rel="stylesheet" href="style.css"/>
</head>
<body>
  <?php
    if (!isset($_SESSION["name"])){
        loginForm();
    }
    else{
     ?>   
   
    <div id="wrapper">
       
        <div id="menu">
            <p class="welcome">What up, <b><?php echo $_SESSION["name"]; ?></b></p>
            <p class="logout"><a id="exit" href="#">Exit, son</a></p>
            <div style="clear:both"></div>
        </div>
        
        <div id="chatbox">
           <?php
                if(file_exists("log.html") && filesize("log.html") > 0){
                    $handle = fopen("log.html", "r");
                    $contents = fread($handle, filesize("log.html"));
                    fclose($handle);
                    echo $contents;
                }
            ?> 
            
        </div>
        
        <form name="message" action="">
            <input name="usermsg" type="text" id="usermsg" size="63" />
            <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
        </form>  
        
    </div>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            
            //exit button and confirm logout prompt
            $("exit").click(function(){
                var exit = confirm("Are you sure you want to exit?");
                if (exit == true){
                    window.location = "index.php?logout=true"
                }
            })
            
            //user submitting chat form
            $("submitmsg").click(function(){
                let clientmsg = $("#usermsg").val();
                $.post("post.php", {
                    text: clientmsg
                });
                $("#usermsg").attr("value", "");
                return false;
            })
            
            //load chat log file
            function loadLog(){
                //scroll height
                let oldScrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                
                $.ajax({
                    url: "log.html",
                    cache: false,
                    success: function(html){
                        $("#chatbox").html(html); //inserts html content into chatbox div element
                        let newScrollHeight = $("#chatbox").attr("scrollHeight");
                        if (newScrollHeight > oldScrollHeight){
                            $("#chatbox").animate({
                                scrollTop: newScrollHeight;
                            })
                        }
                    },
                });
            }
            
            //loads file every 2.5s
            setInterval (loadLog(), 2500);
        });        
    </script>
    <?php
        }
    ?>
    <?php
    session_start();
    function loginForm(){
        echo'
        <div id="loginform">
        <form action="index.php" method="post">
            <p>Please enter your name to continue:</p>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" />
            <input type="submit" name="enter" id="enter" value="Enter" />
        </form>
        </div>
        ';
    }
    ?>
    
    <?php
    if(isset($_POST['enter'])){
        if($_POST['name'] != ""){
            $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
        }
        else{
            echo '<span class="error">Please type in a real name</span>';
        }
    }
    ?>
    
    <?php    
    if(isset($_GET["logout"])){
        //exit message
        $fp = fopen("log.html", "a");
        fwrite($fp, "<div class=msgln><i>User " . $_SESSION["name"] . "has left the chat session.</i><br></div>");
        fclose($fp);
        
        session_destroy();
        header("Location: index.php");//redirect user after logout
    }
    ?>

    
    
</body>
</html>