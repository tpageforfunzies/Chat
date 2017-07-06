    
    <?php
        session_start();
        if (isset($_SESSION["name"])){
            date_default_timezone_set("America/Indiana/Indianapolis");
            $text = $_POST["text"];
            
            $fp = fopen("log.html", "a");
            fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION["name"]."</b>: ".stripslashes(htmlspecialchars($text))."<button type='button' class='btn btn-danger btn-xs' id='delButton'>X</button><br></div>");
            fclose($fp);
        }    
    ?>