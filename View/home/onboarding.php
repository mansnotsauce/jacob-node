<?php include("View/includes/head.php"); ?>
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <div id="content" class="content">
        <div class="mini-container container">
            <div class="h4 center greyColor"><strong>Please provide the following information to start their onboarding process</strong></div>
            <div class="clear20"></div>
            <div class="shadowbox">
                <div id="resultbox"></div>
                <form id="onboarding-form" action="" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="h5 greyColor"><strong>First Name<span class="text-danger">*</span></strong></label>
                                <input type="text" name="first_name" class="form-control" placeholder="Enter their First Name" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="h5 greyColor"><strong>Last Name<span class="text-danger">*</span></strong></label>
                                <input type="text" name="last_name" class="form-control" placeholder="Enter their Last Name" required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="h5 greyColor"><strong>Position<span class="text-danger">*</span></strong></label>
                                <select class="form-control" name="position" required>
                                    <option value=""><em>Select the Position</em></option>
                                    <option value="Field Marketer">Field Marketer</option>
                                    <option value="Sales Support">Sales Support</option>
                                    <option value="Jr Energy Consultant">Energy Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="h5 greyColor"><strong>Email<span class="text-danger">*</span></strong></label>
                                <input type="email" name="email" class="form-control" placeholder="Enter their Email Address" required/>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-offset-3 center">
                            <div class="form-group">                                
                                <label class="h5 greyColor"><strong>Team<span class="text-danger">*</span></strong></label>
                                <select class="form-control" name="team" required>
                                    <option value=""><em>Select the Team</em></option>
                                    <?php foreach ($data['teams'] as $key => $value) {
                                        echo '<option value="'.$value['name'].'">'.$value['name'].'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 center">
                            <button type="submit" name="submit" class="submitBtn">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clear20"></div>
            <div class="h5 center greyColor"><em>They will receive an email with their login information to their onboarding account.</em></div>
        </div>
    </div>
    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#onboarding-form").submit(function(e){                
                e.preventDefault();
                $("#resultbox").html('<img src="/assets/images/spinner.gif" style="height:40px;margin-bottom:20px;">');
                $.post("/api/onboarding",{"onboarding":1,"data":$( this ).serialize()},function(data){
                    var result=JSON.parse(data);
                    if(result.success) {
                        $("#resultbox").html('<p class="text-success"><b style="font-size:20px;">Thank you!</b><br>They should receive an email with their username and password to begin their onboarding process.</p>');
                        $("#onboarding-form input, #onboarding-form select").each(function(){
                            $(this).val("");
                        })
                        $.post("/api/set-accounts?action=update");
                    } else {
                        $("#resultbox").html('<p class="text-danger"><b style="font-size:20px;">Invalid Email Address</b><br>Email Address already exists.</p>');
                    }
                });
            })
        })
    </script>
</body>
</html>