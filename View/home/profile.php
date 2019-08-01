<?php include("View/includes/head.php"); ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <div id="content" class="content">
        <div class="container">
            <div class="clear50"></div>  
            <section class="pwrstation-view-profile">
                <div class="section-table-name">Profile</div>
                <div class="clear50"></div>   
                <div class="shadowbox">
                    <div id="resultbox"></div>
                    <div id="view-profile-form">
                        <div class="row">
                            <div class="col-xs-6 pull-right">
                                <img src="<?php echo $data['prof_pic']; ?>" class="profile-img">
                                <div class="clear50"></div>
                                <div class="h5 greyColor"><b id="passload">Password</b><br> <button class="resetBtn" param="<?php echo $_SESSION['user_id']; ?>">Reset Password</button></div>
                                <div id="resetbox">
                                    <form id="updatepassform" action="" method="POST">
                                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                        <p class="error text-danger"></p>
                                        <div class="form-group">
                                            <label>New Password <span>(Minimum of 6 characters)</span></label>
                                            <input type="password" name="pass1" class="form-control" minlength="6" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" name="pass2" class="form-control" minlength="6" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="updatepass">Update Password</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="clear50"></div>
                            </div>
                            <div class="col-xs-6">
                                <div class="h5 greyColor"><b>Email</b><br> <?php echo $_SESSION['user']['email']; ?></div>
                                <div class="clear20"></div>
                                <div class="h5 greyColor"><b>Position</b><br> <?php echo $_SESSION['role']; ?></div>
                                <div class="clear20"></div>
                                <?php if ($_SESSION['role']=="Field Marketer"||$_SESSION['role']=="Field Marketer Elite"||$_SESSION['role']=="Jr Energy Consultant"||$_SESSION['role']=="Sr Energy Consultant"): ?> 
                                    <div class="h5 greyColor"><b>Team</b><br> <?php echo $_SESSION['user']['team']; ?></div>
                                    <div class="clear20"></div>
                                <?php endif ?>

                                <div class="h5 greyColor"><b>Full Name</b><br> <?php echo $_SESSION['user']['name']; ?></div>
                                <div class="clear20"></div>                                
                                <div class="h5 greyColor"><b>Phone Number</b><br> <?php echo $_SESSION['user']['phone']; ?></div>
                                <div class="clear50"></div>
                            </div>
                            <div class="col-xs-12">
                                <div class="clear30"></div>    
                                <a href="/profile/<?php echo $_SESSION['user_id']; ?>?edit=1"><button class="editBtn">Edit Profile &nbsp;<i class="fas fa-edit"></i></button></a>
                            </div>
                        </div>                        
                    </div>
                </div>  
            </section>                  
        </div>
    </div>

    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".resetBtn").click(function(){
                $(".resetBtn").hide();
                $("#resetbox").fadeIn();
            })

            $("#updatepassform").submit(function(e){
                e.preventDefault();
                var user_id = $("#resetbox input[name=user_id]").val();
                $("#passload").append('<img id="spinner" src="/assets/images/spinner.gif" style="height: 22px;margin-left: 5px;margin-top: -3px;">');
                if($('input[name=pass1]').val() == $('input[name=pass2]').val()) {
                    $.post("/api/update-password",{"user_id":user_id,"password":$("input[name=pass1]").val()},function(data){                        
                        if(data=="success") {
                            $("#spinner").remove();
                            $("#passload").append('<img id="spinner" src="/assets/images/success.png" style="height: 22px;margin-left: 5px;margin-top: -3px;">');
                            $("#resetbox input[name=pass1]").val("");
                            $("#resetbox input[name=pass2]").val("");

                        } else {
                            $("#spinner").remove();        
                            $(".error").text("Update error! Contact system admin");
                        }                     
                    })
                } else {                    
                    $(".error").text("Password doesn't match!");
                    $("#spinner").remove();
                }
            });

            $('input[name=pass1],input[name=pass2]').on("click change",function(){
                $(".error").text("");
            })
        })
    </script>
</body>
</html>