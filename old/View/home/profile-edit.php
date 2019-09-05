<?php include("View/includes/head.php"); ?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <div id="modal-addteam" class="modal-box">
        <form id="addteamform" action="" method="POST">
            <p class="result-message"></p>
            <div class="closebtn"><img src="/assets/images/closebtn.png"></div>
            Team Name:
            <input type="text" name="name" class="form-control" placeholder="Team Name Here" required/>            
            <button type="submit" class="addTeamBtnConfirm">Create Team</button>
        </form>
    </div>
    <div id="content" class="content">
        <div class="container">
            <div class="clear50"></div>  
            <section class="pwrstation-view-profile">
                <div class="section-table-name">Profile</div>
                <div class="clear50"></div>   
                <div class="shadowbox">
                    <div id="resultbox"></div>                    
                        <div class="row">
                            <div class="col-xs-6 pull-right profile-password-section">
                                <img src="<?php echo $data['prof_pic']; ?>" class="profile-img" id="prev-img">
                                <div class="clear10"></div>
                                <form id="uploadpic" action="" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $data['account']['user_id']; ?>">
                                    Select image to upload:
                                    <input type="file" name="fileToUpload" onchange="readURL(this);" id="fileToUpload" required>
                                    <input type="submit" value="Upload Image" name="submit">
                                </form>                                
                                <div class="clear50"></div>
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
                            </div>
                            <div class="col-xs-6">
                                <form id="edit-profile-form" action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $data['account']['user_id']; ?>" required>
                                    <div class="h5 greyColor"><b>Email</b><br> <?php echo $data['account']['Company_Email__c']; ?></div>
                                    <div class="clear20"></div>
                                    <?php if($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite" || $_SESSION['role']=="Jr Energy Consultant" || $_SESSION['role']=="Sr Energy Consultant"): ?>
                                        <div class="h5 greyColor"><b>Position</b><br> <?php echo $_SESSION['role']; ?></div>
                                        <div class="clear20"></div>
                                        <div class="h5 greyColor"><b>Team</b><br> <?php echo $_SESSION['user']['team']; ?></div>
                                        <div class="clear20"></div>
                                    <?php else: ?>
                                    <div class="h5 greyColor"><b>Position</b><br> <select class="form-control" name="position" required>
                                            <option value=""><em>Select User Role</em></option>
                                            <option value="Field Marketer" <?php if($data['account']['Position__c']=="Field Marketer") {echo "selected";} ?>>Field Marketer</option>
                                            <option value="Field Marketer Elite" <?php if($data['account']['Position__c']=="Field Marketer Elite") {echo "selected";} ?>>Field Marketer Elite</option>
                                            <option value="Jr Energy Consultant" <?php if($data['account']['Position__c']=="Jr Energy Consultant") {echo "selected";} ?>>Jr Energy Consultant</option>
                                            <option value="Sr Energy Consultant" <?php if($data['account']['Position__c']=="Sr Energy Consultant") {echo "selected";} ?>>Sr Energy Consultant</option>
                                            <option value="Sales Support" <?php if($data['account']['Position__c']=="Sales Support") {echo "selected";} ?>>Sales Support</option>
                                            <option value="Manager" <?php if($data['account']['Position__c']=="Manager") {echo "selected";} ?>>Manager</option>
                                            <option value="Regional" <?php if($data['account']['Position__c']=="Regional") {echo "selected";} ?>>Regional Manager</option>
                                            <option value="VP" <?php if($data['account']['Position__c']=="VP") {echo "selected";} ?>>VP of Sales</option>
                                            <option value="CEO" <?php if($data['account']['Position__c']=="CEO") {echo "selected";} ?>>CEO</option>
                                        </select></div>
                                        <div class="h5 greyColor"><b>Team</b><button type="button" class="addnewteambtn btn btn-primary">Create New Team</button><br> <select class="form-control" name="team">
                                            <option value=""><em>Select Team</em></option>
                                            <?php foreach ($data['teams'] as $key => $value): ?>
                                                <option <?php if($value['name']==$data['account']['Team__c']) { echo "selected"; } ?> value="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></option>
                                            <?php endforeach ?>                                            
                                        </select></div>                                        
                                        <div class="clear20"></div>  
                                <?php endif; ?>                                
                                    <div class="h5 greyColor"><b>Full Name</b><br> <input type="text" name="name" value="<?php echo $data['account']['Name']; ?>" required class="form-control"></div>
                                    <div class="clear20"></div>                                    
                                    <div class="h5 greyColor"><b>Phone Number</b><br> <input type="text" name="phone" value="<?php echo $data['account']['Phone']; ?>" class="form-control"></div>
                                    <div class="clear50"></div>
                                    <div class="clear30"></div>
                                    <button class="approveBtn" type="submit">Update</button>
                                    <a href="/profile/<?php echo $data['account']['user_id']; ?>"><button class="editBtn" type="button">Cancel</button></a>
                                </form>
                            </div>                            
                        </div>                                            
                </div>  
            </section>                  
        </div>
    </div>

    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#prev-img').attr('src', e.target.result).show();                        
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).ready(function(){
            $("#addteamform").submit(function(e){
                e.preventDefault();                
                $(".addnewteambtn").after('<img class="spinner" src="/assets/images/spinner.gif" style="height:25px;margin-left:20px;">')
                $.post("/api/add-team-names",{"name":$("input[name=name]",this).val()},function(data){
                    if(data) {
                        location.reload();
                    }
                })
            })

            $(".addnewteambtn").click(function(){
                $("#modal-addteam,#cover").show();
            })

            $("#modal-addteam .closebtn").click(function(){
                $("#modal-addteam,#cover").hide();
            })            

            $("#edit-profile-form").submit(function(e){
                $("#resultbox").html('<img src="/assets/images/spinner.gif" style="height:40px;margin-bottom:20px;">');
                e.preventDefault();
                $.post("/api/update-profile",{"update":1,"data":$( this ).serialize()},function(data){                    
                    if(data=="success"){
                        $("#resultbox").html('<p class="text-success"><b style="font-size:20px;">Profile Successfully Updated!</b><br>Refreshing...</p>');
                        setTimeout(function(){ window.location="/profile/"+$("input[name=id]").val(); }, 1000);
                    }
                })
            });

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