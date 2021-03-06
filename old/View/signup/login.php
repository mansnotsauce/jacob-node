<?php include("View/includes/head.php"); ?>
    <body>      
        <section class="page-background">
            <div class="darkform">
                <div class="logo-login">
                    <h1>Welcome to</h1>
                    <img src="/assets/images/logo-horizon.png" alt="Horizon PWR">
                    <h3>PWRStation</h3>
                </div>                
                <h4 class="login-title">Login</h4>
                <form id="loginform" class="login" method="post" action="">
                    <p id="error"></p>
                    <input type="email" name="email" placeholder="Enter Email Address" value="<?php                    
                    if (isset($_COOKIE['remember_email'])) {
                        echo htmlentities($_COOKIE['remember_email']);
                    }
                    ?>" required>
                    <input type="password" name="pass" placeholder="Enter Password" required>
                    <button type="submit">Login</button>
                    
                    <div><label class="remember-me"><input type="checkbox" name="remember" value="1" checked="checked"> Remember Me</label></div>
                </form>

                <a class="forgot-password" href="#">Forget password?</a>

                <p class="quote">“He who is not courageous enough to take the risk will accomplish nothing in life”</p>
                <p>-Muhammad Ali</p>
            </div>
        </section>

        <?php include("View/includes/foot.php"); ?>
        <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#loginform").submit(function(e){                    
                    e.preventDefault();
                    var remember = false;
                    if($("input[name=remember]").prop("checked")==true) {
                        remember = true;
                    }                    
                    $("button[type=submit]").append('<img id="spinner" src="/assets/images/spinner.gif" style="height: 25px;margin: -5px 0 0 6px;">');
                    $.post("/api/login",{"email":$("input[name=email]").val(),"pass":$("input[name=pass]").val(),"remember":remember},function(data){
                        $("#spinner").remove();
                        var result = JSON.parse(data);
                        if(result.success) {
                            window.location="/";
                        } else {
                            $("#error").text(result.message).slideDown();
                        }
                    })
                });

                $("input").on("click change",function(){
                    $("#error").slideUp();
                });
            })
        </script>
</body>
</html>