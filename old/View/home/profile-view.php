<?php include("View/includes/head.php"); ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin"): ?>
        <div id="approvebox" class="modal-box">
            <form class="approveform" action="" method="POST">
                <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
                <div class="h4 center">Approve User</div>
                <p></p>
                <input type="hidden" name="approve_id" value="<?php echo $data['account']['user_id']; ?>">
                <input type="email" name="email" placeholder="Enter HorizonPWR Email" value="" required>
                <div class="clear30"></div>
                <div class="center">
                    <button type="button" class="btn btn-secondary cancelbtn">Cancel</button>
                    <button type="submit" class="btn btn-success approvebtnconfirm">Approve</button>
                </div>
            </form>
        </div>
        <div id="confirmbox" class="modal-box">
            <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
            <div class="h4 center">Are you sure you want to delete?</div>
            <input type="hidden" name="bulk_id">
            <input type="hidden" name="bulk_type">        
            <div class="clear30"></div>
            <div class="center">
                <button type="button" class="btn btn-secondary cancelbtn">Cancel</button>
                <button type="button" class="btn btn-danger deletebtnconfirm">Delete</button>
            </div>
        </div>
    <?php endif; ?>
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
                                <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin" || $_SESSION['user_id']==$data['account']['user_id']): ?>
                                    <div class="h5 greyColor"><b id="passload">Password</b><br> <button class="resetBtn" param="<?php echo $data['account']['user_id']; ?>">Reset Password</button></div>
                                    <div id="resetbox">
                                        <form id="updatepassform" action="" method="POST">
                                            <input type="hidden" name="user_id" value="<?php echo $data['account']['user_id']; ?>">
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
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-6">
                                <div class="h5 greyColor"><b>Email</b><br> <?php echo $data['account']['Company_Email__c']; ?></div>
                                <div class="clear20"></div>
                                <div class="h5 greyColor"><b>Position</b><br> <?php echo $data['account']['Position__c']; ?></div>
                                <div class="clear20"></div>
                                <div class="h5 greyColor"><b>Team</b><br> <?php if($data['account']['Team__c']!=NULL) {echo $data['account']['Team__c']; } else { echo "None"; }?></div>
                                <div class="clear20"></div>
                                <div class="h5 greyColor"><b>Full Name</b><br> <?php echo $data['account']['Name']; ?></div>
                                <div class="clear20"></div>                                                                
                                <div class="h5 greyColor"><b>Phone Number</b><br> <?php echo $data['account']['Phone']; ?></div>
                                <div class="clear50"></div>
                            </div>
                            <?php if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin" || $_SESSION['user_id']==$data['account']['user_id']): ?>
                                <div class="col-xs-12">
                                    <div class="clear30"></div>
                                    <?php if($data['account']['Status__c']=="Onboarding"):?>
                                        <button class="approveBtn" param="<?php echo $data['account']['user_id']; ?>">Approve</button>                                 
                                    <?php endif; ?>
                                    <button class="deleteBtn" param="<?php echo $data['account']['user_id']; ?>">Delete</button> <a href="/profile/<?php echo $data['account']['user_id']; ?>?edit=1"><button class="editBtn">Edit Profile &nbsp;<i class="fas fa-edit"></i></button></a>
                                </div>
                            <?php endif; ?>
                        </div>                        
                    </div>
                </div>  
            </section>                  
        </div>
    </div>

    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".approveBtn").click(function(){
                $("#approvebox,#cover").show();
            });
            $(".cancelbtn,.closebtn").click(function(){
                $("#approvebox,#cover").hide();
            })

            $("#approvebox .approveform").submit(function(e){
                e.preventDefault();
                var email = $("input[name=email]",this).val();
                var id = $("input[name=approve_id]",this).val();                
                $("#approvebox p").append('<img src="/assets/images/spinner.gif" style="height:20px;margin-left:10px;" class="spinner-gif">');

                $.post("/api/approve-onboarding",{"user_id":id,"email":email},function(data){
                    $(".spinner-gif").remove();
                    if(data=="success") {
                        $("#approvebox p").append('<img src="/assets/images/success.png" style="height:20px;margin-left:10px;" class="spinner-gif">');    
                        setTimeout(function(){ location.reload() }, 1500);
                    }                                    
                })
            })
            $(".deleteBtn").click(function(){                
                var param = $(this).attr("param");
                var type = "Delete";
                var id = [];
                id.push(param);
                $("input[name=bulk_id]").val(id);
                $("input[name=bulk_type]").val(type);
                $("#cover,#confirmbox").show();                                
            });

            $(".deletebtnconfirm").click(function(){                
                var bulk_id = $("input[name=bulk_id]").val().split(",");
                var bulk_type = $("input[name=bulk_type]").val();                
                $("#resultbox").html('<img src="/assets/images/spinner.gif" style="height:40px;margin-bottom:20px;">');
                $("#confirmbox,#cover").hide();

                $.post("/api/admin-bulk/",{"type":bulk_type,"id":bulk_id},function(data){  
                    console.log(data);                      
                     if(data=="success") {
                        $("#resultbox").html('<p class="text-success"><b style="font-size:20px;">Account Deleted!</b><br>Redirecting to dashboard...</p><br>');
                        setTimeout(function(){ window.location="/dashboard"; }, 3000);
                    }
                });
            })

            $(".closebtn,.cancelbtn").click(function(){
                $("#confirmbox,#cover").hide();                
            })


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