<?php include("View/includes/head.php"); ?>
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <div id="content" class="content admin-dashboard">
        <div class="container">
            <div class="clear50"></div>  
            <section class="pwrstation-table">
                <div class="section-table-name">Add New User</div>
                <div class="clear50"></div>   
                <div class="shadowbox">
                    <div id="resultbox"></div>
                    <form id="add-user-form" action="" method="POST">
                        <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="p greyColor"><strong>Email<span class="text-danger">*</span></strong></label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your Email Address" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="p greyColor"><strong>Role<span class="text-danger">*</span></strong></label>
                                    <select class="form-control" name="position" required>
                                        <option value=""><em>Select User Role</em></option>
                                        <option value="Field Marketer">Field Marketer</option>
                                        <option value="Field Marketer Elite">Field Marketer Elite</option>
                                        <option value="Jr Energy Consultant">Junior Energy Consultant</option>
                                        <option value="Sr Energy Consultant">Senior Energy Consultant</option>
                                        <option value="Sales Support">Sales Support</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Regional">Regional Manager</option>
                                        <option value="VP">VP of Sales</option>
                                        <option value="CEO">CEO</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="p greyColor"><strong>First Name<span class="text-danger">*</span></strong></label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Enter your First Name" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="p greyColor"><strong>Last Name<span class="text-danger">*</span></strong></label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Enter your Last Name" required/>
                                </div>
                            </div>                                        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="p greyColor"><strong>Phone Number<span class="text-danger">*</span></strong></label>
                                    <input type="text" name="phone" class="form-control" placeholder="Enter your Phone Number" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="p greyColor"><strong>Team</strong></label>
                                    <select class="form-control" name="team">
                                        <option value=""><em>Select the Team</em></option>
                                        <?php foreach ($data['teams'] as $key => $value) {
                                            echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="clear30"></div>
                                <button type="submit" name="submit" class="submitBtn">Add New User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>                  
        </div>
    </div>

    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#add-user-form").submit(function(e){
                e.preventDefault();                                
                $("#resultbox").html('<img src="/assets/images/spinner.gif" style="height:40px;margin-bottom:20px;">');
                $.post("/api/adduser",{"adduser":1,"data":$( this ).serialize()},function(data){
                    var result=JSON.parse(data);
                    if(result.success) {
                        $("#resultbox").html('<p class="text-success"><b style="font-size:20px;">Thank you!</b><br>They should receive an email with their username and password to access the PWR Station Dashboard.</p>');
                        $("#add-user-form input, #add-user-form select").each(function(){
                            $(this).val("");
                        })
                        $("#add-user-form select:eq(0)").attr('selected', true);
                        $.post("/api/set-accounts?action=update");
                    } else {
                        $("#resultbox").html('<p class="text-danger"><b style="font-size:20px;">Invalid Email Address</b><br>Email Address already exists.</p>');
                    }
                });
            });
        })
    </script>
</body>
</html>