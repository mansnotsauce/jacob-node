<?php include("View/includes/head.php"); ?>
<body>    
    <?php include("View/includes/home-nav.php"); ?>
    <div id="content" class="content">        
        <div class="container">
        	<div class="clear50"></div>           	
        	<div class="tab-section">
        		<div class="tab-option-holder">
        			<div class="teams-icon tab-option active"><div class="icon-badge"></div>Teams</div>
        			<div class="topreps-icon tab-option"><div class="icon-badge"></div>Top Reps</div>
        		</div>
        		<div class="tab-content">
        			<div class="tab-content-top">
        				<strong>Period</strong> <select name="period">
        					<option value="Today">Today</option>
        					<option value="Yesterday">Yesterday</option>
        					<option value="This Week">This Week</option>
        					<option value="Last Week">Last Week</option>
        					<option value="This Month">This Month</option>
        					<option value="Last Month">Last Month</option>
        					<option value="This Quarter">This Quarter</option>
        					<option value="Last Quarter">Last Quarter</option>
        					<option value="This Year">This Year</option>
        					<option value="Last Year">Last Year</option>
        					<option value="All Time">All Time</option>
        					<option value="Custom">Custom</option>
        				</select>                        
        				<div class="toggle-options">        					
        					<div class="toggle-options-val <?php if(($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite")!==true) { echo "active";} ?>">E.C.</div>
        					<div class="toggle-options-val <?php if($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite") { echo "active";} ?>">F.M.</div>
        					<div class="clear"></div>
        				</div>
                        <div class="custom-time-holder">
                            <div class="clear20"></div>
                            Start <input type="date" name="prevtime" max="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>">
                            End <input type="date" name="curtime" max="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>">
                        </div>
        			</div>
        			<div id="teams" class="tab-content-bottom active">
                        <div class="table-header">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="team-sort" param="DESC">Team</th>
                                        <th class="team-sort" param="DESC">Score</th>
                                        <th class="team-sort" param="DESC">Leads</th>
                                        <th class="team-sort" param="DESC">QS</th>
                                        <th class="team-sort" param="DESC">AS</th>
                                        <th class="team-sort" param="DESC">Close</th>
                                        <th class="team-sort" param="DESC">Installs</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
        				<div class="table-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Team</th>
                                        <th>Score</th>
                                        <th>Leads</th>
                                        <th>QS</th>
                                        <th>AS</th>
                                        <th>Close</th>
                                        <th>Installs</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr>
                                        <td colspan="7"><div id="spinner" style="text-align:center;"><img src="/assets/images/spinner.gif" style="margin: 120px 0 100px;height: 40px;"></div></td>
                                    </tr>                                    
                                </tbody>
                            </table>            
                        </div>
        			</div>
        			<div id="topreps" class="tab-content-bottom">
                        <div class="table-header">
                            <table class="table-ec <?php if(($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite")!==true) { echo "active";} ?>">
                                <thead>
                                    <tr>
                                        <th class="ec-sort" param="DESC">Rep</th>
                                        <th class="ec-sort" param="DESC">Score</th>
                                        <th class="ec-sort" param="DESC">Leads</th>
                                        <th class="ec-sort" param="DESC">QS</th>
                                        <th class="ec-sort" param="DESC">AS</th>
                                        <th class="ec-sort" param="DESC">Close</th>
                                        <th class="ec-sort" param="DESC">Assisted Installs</th>                                  
                                        <th class="ec-sort" param="DESC">Self Generated Installs</th>                                        
                                    </tr>
                                </thead>
                            </table>
                            <table class="table-fm <?php if($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite") { echo "active";} ?>">
                                <thead>
                                    <tr>
                                        <th class="fm-sort" param="DESC">Rep</th>
                                        <th class="fm-sort" param="DESC">Score</th>
                                        <th class="fm-sort" param="DESC">Leads</th>
                                        <th class="fm-sort" param="DESC">QS</th>
                                        <th class="fm-sort" param="DESC">AS</th>
                                        <th class="fm-sort" param="DESC">Assisted Close</th>
                                        <th class="fm-sort" param="DESC">Assisted Installs</th>                                                                      
                                    </tr>
                                </thead>
                            </table>
                        </div>  
                        <div class="table-content">
                            <table class="table-ec <?php if(($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite")!==true) { echo "active";} ?>">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Score</th>
                                        <th>Leads</th>
                                        <th>QS</th>
                                        <th>AS</th>
                                        <th>Close</th>
                                        <th>Assisted Installs</th>                                  
                                        <th>Self Generated Installs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <table class="table-fm <?php if($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite") { echo "active";} ?>">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Score</th>
                                        <th>Leads</th>
                                        <th>QS</th>
                                        <th>AS</th>
                                        <th>Assisted Close</th>
                                        <th>Assisted Installs</th>                                                                      
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>        
                        </div>      				
        			</div>
        		</div>
        	</div>
        </div>
    </div>

    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
    	$(document).ready(function(){
            //FOR TEAM
            get_team_table_data("Today");
            //FOR INDIVIDUAL FM/EC
            get_table_data("Today");

            $(".team-sort").click(function(){
                $(".team-sort").each(function(){
                    $(this).removeClass("active");
                })
                var curtime = $(".tab-content-top input[name=curtime]").val();
                var prevtime = $(".tab-content-top input[name=prevtime]").val();  
                $(this).addClass("active");
                var order = $(this).attr("param");
                if(order=="ASC") {
                    $(this).attr("param","DESC");
                } else {
                    $(this).attr("param","ASC");
                }
                var sort = $(this).text();
                var period = $('.tab-content-top select[name=period]').val();
                get_team_table_data(period,sort,order,prevtime,curtime);
            })

            $(".ec-sort").click(function(){
                $(".ec-sort").each(function(){
                    $(this).removeClass("active");
                })
                var curtime = $(".tab-content-top input[name=curtime]").val();
                var prevtime = $(".tab-content-top input[name=prevtime]").val();
                $(this).addClass("active");
                var order = $(this).attr("param");
                if(order=="ASC") {
                    $(this).attr("param","DESC");
                } else {
                    $(this).attr("param","ASC");
                }

                var sort = $(this).text();
                var period = $('.tab-content-top select[name=period]').val();                                
                get_table_data(period,sort,order,prevtime,curtime);
            })

            $(".fm-sort").click(function(){
                $(".fm-sort").each(function(){
                    $(this).removeClass("active");
                })
                var curtime = $(".tab-content-top input[name=curtime]").val();
                var prevtime = $(".tab-content-top input[name=prevtime]").val();
                $(this).addClass("active");
                var order = $(this).attr("param");
                if(order=="ASC") {
                    $(this).attr("param","DESC");
                } else {
                    $(this).attr("param","ASC");
                }
                var sort = $(this).text();
                var period = $('.tab-content-top select[name=period]').val();                                
                get_table_data(period,sort,order,prevtime,curtime);
            })

            $(".tab-content-top input[name=prevtime]").change(function(){
                customdate_tabcontenttop();
            })
            $(".tab-content-top input[name=curtime]").change(function(){
                customdate_tabcontenttop();
            })

            function customdate_tabcontenttop() {
                $(".tab-content-top input[name=prevtime],.tab-content-top input[name=curtime]").css("border-color","initial");
                var curtime = $(".tab-content-top input[name=curtime]").val();
                var prevtime = $(".tab-content-top input[name=prevtime]").val();                
                if(curtime >= prevtime) {
                    $('.tab-content-top select[name=period]').after('<img id="mainspinner" src="/assets/images/spinner.gif" style="height: 25px;margin-left: 10px;margin-top: -3px;">');
                    var period = $('.tab-content-top select[name=period]').val();                          
                    get_table_data(period,"Score","DESC",prevtime,curtime);
                    get_team_table_data(period,"Score","DESC",prevtime,curtime);
                } else if (curtime==""){
                    $(".tab-content-top input[name=curtime]").css("border-color","red");
                } else if (prevtime==""){
                    $(".tab-content-top input[name=prevtime]").css("border-color","red");
                }
            }

            function customdate_popup_compare() {
                $(".modal-compare input[name=prevtime],.modal-compare input[name=curtime]").css("border-color","initial");
                var curtime = $(".modal-compare input[name=curtime]").val();
                var prevtime = $(".modal-compare input[name=prevtime]").val();  
                if(curtime >= prevtime && curtime !="" && prevtime!="") {                    
                    var period = $('.modal-compare select[name=period]').val();  
                    var id = $(".modal-solo input[name=vs-id]").val();                       
                    $.post("/api/get-dashboard-data",{'id':id,"period":period,"prevdate":prevtime,"curdate":curtime},function(data){                            
                        get_json_data_vs(data);

                    })
                    $.post("/api/get-dashboard-data",{"period":period,"prevdate":prevtime,"curdate":curtime},function(data){
                        get_json_data(data)
                    }) 
                } else if (curtime==""){
                    $(".modal-compare input[name=curtime]").css("border-color","red");
                } else if (prevtime==""){
                    $(".modal-compare input[name=prevtime]").css("border-color","red");
                }
            }

            function customdate_popup_solo() {
                $(".modal-solo input[name=prevtime],.modal-solo input[name=curtime]").css("border-color","initial");
                var curtime = $(".modal-solo input[name=curtime]").val();
                var prevtime = $(".modal-solo input[name=prevtime]").val();                  
                if(curtime >= prevtime && curtime !="" && prevtime!="") {                    
                    var period = $('.modal-solo select[name=period]').val();   
                    var id = $(".modal-solo input[name=vs-id]").val();                 
                    $.post("/api/get-dashboard-data",{'id':id,"period":period,"prevdate":prevtime,"curdate":curtime},function(data){                            
                        get_json_data_vs(data);
                    })  
                } else if (curtime==""){
                    $(".modal-solo input[name=curtime]").css("border-color","red");
                } else if (prevtime==""){
                    $(".modal-solo input[name=prevtime]").css("border-color","red");
                }
            }

            
            $('.tab-content-top select[name=period]').change(function(){            
                var period = $(this).val();                                                
                $('.modal-box select[name=period]').val(period);                
                if(period=="Custom") {
                    $(".tab-content-top .custom-time-holder").show();
                    customdate_tabcontenttop();
                } else {
                    $(".tab-content-top .custom-time-holder").hide();
                    $(this).after('<img id="mainspinner" src="/assets/images/spinner.gif" style="height: 25px;margin-left: 10px;margin-top: -3px;">');
                    get_table_data(period);
                    get_team_table_data(period,"Score","DESC");
                }            
            })

            $(".modal-solo input[type=date]").change(function(){
                customdate_popup_solo();
            })
            $(".modal-compare input[type=date]").change(function(){
                customdate_popup_compare();
            })

            $(".modal-solo select[name=period]").change(function(){   
                var period = $(this).val();
                var id = $(".modal-solo input[name=vs-id]").val();
                if(period!="Custom") {
                    $(".modal-box .custom-time-holder").hide();
                    $.post("/api/get-dashboard-data",{'id':id,"period":period},function(data){                            
                        get_json_data_vs(data);
                    })  
                } else {
                    $(".modal-box .custom-time-holder").show();
                }
            })

            $(".modal-compare select[name=period]").change(function(){   
                var period = $(this).val();
                var id = $(".modal-solo input[name=vs-id]").val();     
                if(period!="Custom") {
                    $(".modal-box .custom-time-holder").hide();
                    $.post("/api/get-dashboard-data",{'id':id,"period":period},function(data){                            
                        get_json_data_vs(data);

                    })
                    $.post("/api/get-dashboard-data",{"period":period},function(data){
                        get_json_data(data)
                    })                      
                } else {
                    $(".modal-box .custom-time-holder").show();
                }             
            })

            //FOR TEAM
            function get_team_table_data(period,sort="Score",order="DESC",prevdate=null,curdate=null) { 
                $.post("/api/lb-team",{"period":period,"order":order,"prevdate":prevdate,"curdate":curdate,"sort":sort},function(data){
                    $("#mainspinner").remove();
                    $("#teams tbody").html(data);                     
                })           
            }  
            //FOR INDIVIDUAL FM/EC
            function get_table_data(period,sort="Score",order="DESC",prevdate=null,curdate=null) {                                
                $.post("/api/get-field-marketers",{"period":period,"order":order,"prevdate":prevdate,"curdate":curdate,"sort":sort},function(data){
                    $("#mainspinner").remove();
                    $(".table-fm tbody").html(data); 
                    $(".table-fm .viewdetails").click(function(){
                        $('.tab-content-top select[name=period]').after('<img id="mainspinner" src="/assets/images/spinner.gif" style="height: 25px;margin-left: 10px;margin-top: -3px;">');
                        var id = $(this).attr("param");
                        var period = $(".modal-box select[name=period]").val();                         

                        $(".modal-box input[name=vs-id]").val(id);

                        $.post("/api/get-dashboard-data",{'id':id,"period":period,"prevdate":prevdate,"curdate":curdate},function(data){    
                            $("#mainspinner").remove();
                            get_json_data_vs(data);                            
                            if("<?php echo $_SESSION['role']; ?>" == "Field Marketer" || "<?php echo $_SESSION['role']; ?>" == "Field Marketer Elite") {
                                $(".modal-compare,.role-fm,#cover").show();
                            } else {
                                $(".modal-solo,.role-fm,#cover").show();                        
                            }
                        })
                        $.post("/api/get-dashboard-data",{"period":period,"prevdate":prevdate,"curdate":curdate},function(data){
                            $("#mainspinner").remove();
                            get_json_data(data)
                            if("<?php echo $_SESSION['role']; ?>" == "Field Marketer" || "<?php echo $_SESSION['role']; ?>" == "Field Marketer Elite") {
                                $(".modal-compare,.role-fm,#cover").show();
                            } else {
                                $(".modal-solo,.role-fm,#cover").show();                        
                            }
                        })                        
                    });               
                })
                $.post("/api/get-energy-consultant",{"period":period,"order":order,"prevdate":prevdate,"curdate":curdate,"sort":sort},function(data){                
                    $(".table-ec tbody").html(data);                
                    $(".table-ec .viewdetails").click(function(){
                        $('.tab-content-top select[name=period]').after('<img id="mainspinner" src="/assets/images/spinner.gif" style="height: 25px;margin-left: 10px;margin-top: -3px;">');
                        var id = $(this).attr("param");
                        var period = $(".modal-box select[name=period]").val();

                        $(".modal-box input[name=vs-id]").val(id);

                        $.post("/api/get-dashboard-data",{'id':id,"period":period,"prevdate":prevdate,"curdate":curdate},function(data){ 
                            $("#mainspinner").remove();                           
                            get_json_data_vs(data);
                            if("<?php echo $_SESSION['role']; ?>" == "Jr Energy Consultant" || "<?php echo $_SESSION['role']; ?>" == "Sr Energy Consultant") {
                                $(".modal-compare,.role-ec,#cover").show();
                            } else {
                                $(".modal-solo,.role-ec,#cover").show();
                            } 
                        })
                        $.post("/api/get-dashboard-data",{"period":period,"prevdate":prevdate,"curdate":curdate},function(data){
                            $("#mainspinner").remove();
                            get_json_data(data)
                            if("<?php echo $_SESSION['role']; ?>" == "Jr Energy Consultant" || "<?php echo $_SESSION['role']; ?>" == "Sr Energy Consultant") {
                                $(".modal-compare,.role-ec,#cover").show();
                            } else {
                                $(".modal-solo,.role-ec,#cover").show();
                            } 
                        })                        
                    });
                })
            }

            //DISPLAY OWN USER DATA ON POPUP
            function get_json_data(data) {                          
                var result = JSON.parse(data);    
                var installs = result.assisted_installs+result.self_generated_installs;            
                $(".modal-box .name-value").text(result.user_info.Name);
                $(".modal-box .picture-value").attr("src",result.user_info.picture);
                $(".modal-box .commission-value").text("$"+result.commission);
                $(".modal-box .leads-value").text(result.leads);
                $(".modal-box .leads-week-value").text(result.leadsweekvalue);
                $(".modal-box .leads-month-value").text(result.leadsmonthvalue);
                $(".modal-box .qs-value").text(result.sits);
                $(".modal-box .qs-week-value").text(result.leadsweekvalue);
                $(".modal-box .qs-month-value").text(result.leadsmonthvalue);
                $(".modal-box .leadstoqs-value").text(result.leadtosits+"%");
                $(".modal-box .score-value").text(result.score);
                $(".modal-box .closes-value").text(result.closes);
                $(".modal-box .sittoclose-value,.modal-box .vs-sitstoclose-value").text(result.sitstoclose+"%");
                $(".modal-box .installs-value").text(installs);
                $(".modal-box .closestoinstall-value").text(result.closetoinstall+"%");                
                $(".modal-box .kw-value").text(result.kwperyear+" KWs");                
            }
            //DISPLAY OTHER USER DATA ON POPUP
            function get_json_data_vs(data) { 
                var result = JSON.parse(data);
                var installs = result.assisted_installs+result.self_generated_installs;
                                                                
                $(".modal-box .vs-name").text(result.user_info.Name);
                $(".modal-box .vs-picture").attr("src",result.user_info.picture);
                $(".modal-box .vs-commission-value").text("$"+result.commission);
                $(".modal-box .vs-leads-value").text(result.leads);
                $(".modal-box .vs-leads-week-value").text(result.leadsweekvalue);
                $(".modal-box .vs-leads-month-value").text(result.leadsmonthvalue);
                $(".modal-box .vs-qs-value").text(result.sits);
                $(".modal-box .vs-qs-week-value").text(result.sitsweekvalue);
                $(".modal-box .vs-qs-month-value").text(result.sitsmonthvalue);
                $(".modal-box .vs-leadstoqs-value").text(result.leadtosits+"%");
                $(".modal-box .vs-score-value").text(result.score);
                $(".modal-box .vs-closes-value").text(result.closes);
                $(".modal-box .vs-sittoclose-value,.modal-box .vs-sitstoclose-value").text(result.sitstoclose+"%");
                $(".modal-box .vs-installs-value").text(installs);
                $(".modal-box .vs-closestoinstall-value").text(result.closetoinstall+"%");
                $(".modal-box .vs-kw-value").text(result.kwperyear+" KWs");                
            }            

    		$(".tab-option").click(function(){
    			$(".tab-option").each(function(){
    				$(this).removeClass("active");
    			})
    			$(this).addClass("active");
    			var section = $(this).text().toLowerCase().replace(/ /g,"");
    			$(".tab-content-bottom").each(function(){
    				$(this).removeClass("active");
    			})
    			$("#"+section).addClass("active");
    			if(section == "topreps") {
    				$(".toggle-options").addClass("open");
    			} else {
    				$(".toggle-options").removeClass("open");
    			}
    		});

    		$(".toggle-options-val").click(function(){
    			if(!$(this).hasClass("active")) {

    				if($(this).text()=="E.C.") {
    					$("#topreps table").hide();
    					$("#topreps").prepend('<div id="spinner" style="text-align:center;"><img src="/assets/images/spinner.gif" style="margin: 100px;height: 40px;"></div>');

    					$(".toggle-options-val").each(function(){
		    				$(this).removeClass("active");
		    			})
		    			$(this).addClass("active");

		    			setTimeout(function(){ 
		    				$("#spinner").remove();
		    				$("#topreps .table-ec").show();
		    			}, 1000);
    				} else {
    					$("#topreps table").hide();
    					$("#topreps").prepend('<div id="spinner" style="text-align:center;"><img src="/assets/images/spinner.gif" style="margin: 100px;height: 40px;"></div>');

    					$(".toggle-options-val").each(function(){
		    				$(this).removeClass("active");
		    			})
		    			$(this).addClass("active");

		    			setTimeout(function(){ 
		    				$("#spinner").remove();
		    				$("#topreps .table-fm").show();
		    			}, 1000);
    				}	    				    			
    			}    			
    		})
    	})
    </script>
    <?php include("View/includes/modal-compare-fm.php"); ?>
</body>
</html>