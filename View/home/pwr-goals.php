<?php 	
	function getStartAndEndDate($week, $year) {
	  $dto = new DateTime();
	  $dto->setISODate($year, $week);
	  $ret['week_start'] = $dto->format('m/d/Y');
	  $dto->modify('+6 days');
	  $ret['week_end'] = $dto->format('m/d/Y');
	  return $ret;
	}				
	$date = new DateTime(date("Y-m-d"));
	$weeknum = $date->format("W");

?>
<?php include("View/includes/head.php"); ?>
<body>
    <?php include("View/includes/home-nav.php"); ?>
    <div id="addgoalsbox" class="modal-box">
    	<div class="closebtn"><img src="/assets/images/closebtn.png"></div>
    	<form id="addgoalform" action="" method="POST">
	    	<div class="h4"><strong><?php if($_SESSION['role']=="Sr Energy Consultant") {echo $_SESSION['user']['team']." ";} ?>GOAL</strong> 
	    		<?php if($_SESSION['role']=="Admin"||$_SESSION['role']=="CEO"||$_SESSION['role']=="VP"||$_SESSION['role']=="Sales Support"||$_SESSION['role']=="Manager"): ?>    	
		    		<select name="team">
		    			<?php foreach ($data['teams'] as $key => $value): ?>
		    				<option value="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></option>
		    			<?php endforeach ?>
		    		</select>	    		
		    	<?php endif; ?>		    	
	    	</div>
	    	<div class="clear10"></div>	   
	    	<select name="user_name" required>	    		
	    	</select>
	    	<div class="clear10"></div> 	
    		<input type="hidden" name="period_type" value="week">
	    	<div class="dropdown-period">
				<select name="week" class="active">
					<?php for ($i=$weeknum-3; $i <= $weeknum+3 ; $i++) { 
						$week_array = getStartAndEndDate($i,date("Y"));
						$selected = "";
						if($weeknum==$i) {
							$selected = "selected";
						}

						echo '<option value="'.$i.'" '.$selected.'>'.$week_array["week_start"].' - '.$week_array["week_end"].'</option>';
					} ?>        				
				</select>
				<select name="month">
					<?php for($x=1;$x<=12;$x++) {
						$selected = "";
						if(date("n")==$x) {
							$selected = "selected";
						}
						$month = date('F', mktime(0,0,0,$x, 1, date('Y')));
						echo "<option value='".$month."' ".$selected.">".$month."</option>";
					} ?>
				</select>
			</div>
	    	<div class="toggle-options-wm show">        					
				<div class="toggle-options-wm-val week active">Week</div>
				<div class="toggle-options-wm-val month">Month</div>
				<div class="clear"></div>
			</div>
			<style type="text/css">
				.leadsgroup, .sitsgroup, .closesgroup, .installsgroup, .fmunitgroup, .appointmentsgroup {
					display: none;
				}
			</style>
			<div class="clear20"></div>    	
    		<div class="row">
    			<div class="col-md-6 leadsgroup">
	    			<div class="form-group">
		    			<label>Leads</label>
		    			<input type="number" class="form-control" name="leads" value="0">
		    		</div>
	    		</div>
	    		<div class="col-md-6 sitsgroup">
	    			<div class="form-group">
		    			<label>Sits</label>
		    			<input type="number" class="form-control" name="sits" value="0">
		    		</div>
	    		</div>
	    		<div class="col-md-6 appointmentsgroup">
	    			<div class="form-group">
		    			<label>Appointments Scheduled</label>
		    			<input type="number" class="form-control" name="appointments" value="0">
		    		</div>
	    		</div>
	    		<div class="col-md-6 closesgroup">
	    			<div class="form-group">
		    			<label>Closes</label>
		    			<input type="number" class="form-control" name="closes" value="0">
		    		</div>
	    		</div>
	    		<div class="col-md-6 installsgroup">
	    			<div class="form-group">
		    			<label>Installs</label>
		    			<input type="number" class="form-control" name="installs" value="0">
		    		</div>
	    		</div>	    		
	    		<div class="col-xs-12">
	    			<button type="submit" class="addgoal">Add Goal</button>
	    		</div>
    		</div>
    	</form>
    </div>
    <div id="content" class="content">
        <div class="container">        	
        	<div class="h1 colorBlue center"><b>GOALS</b></div>        	
        </div>
        <div class="divblue-backdrop">
        	<div class="container"> 
        		<select name="team_name">
					<?php foreach ($data['teams'] as $key => $value): ?>
						<option value="<?php echo $value['name']; ?>" <?php if(isset($_SESSION['user']['team']) && $_SESSION['user']['team']==$value['name']) { echo "selected"; } ?>><?php echo $value['name']; ?></option>
					<?php endforeach ?>
				</select>	
        		<div class="dropdown-period">        			
        			<select name="week" class="active">
        				<?php for ($i=$weeknum-3; $i <= $weeknum+3 ; $i++) { 
        					$week_array = getStartAndEndDate($i,date("Y"));
        					$selected = "";
        					if($weeknum==$i) {
        						$selected = "selected";
        					}

        					echo '<option value="'.$i.'" '.$selected.'>'.$week_array["week_start"].' - '.$week_array["week_end"].'</option>';
        				} ?>        				
        			</select>
        			<select name="month">
        				<?php for($x=1;$x<=12;$x++) {
        					$selected = "";
        					if(date("n")==$x) {
        						$selected = "selected";
        					}
        					$month = date('F', mktime(0,0,0,$x, 1, date('Y')));
        					echo "<option value='".$month."' ".$selected.">".$month."</option>";
        				} ?>
        			</select>
        		</div>
        		<div class="toggle-options-wm show">        					
					<div class="toggle-options-wm-val week active">Week</div>
					<div class="toggle-options-wm-val month">Month</div>
					<div class="clear"></div>
				</div>
				<?php if((isset($_SESSION['user']['team_manager']) && $_SESSION['user']['team_manager']==1) || $_SESSION['role']=="Admin" || $_SESSION['role']=="CEO" || $_SESSION['role']=="VP" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Manager"): ?>
					<button class="addGoalsBtn"><span class="add-goals-icon">+</span> Goals</button>
				<?php endif; ?>
        	</div>
        </div>        
        <div class="container goals">
        	<div class="clear50"></div>
        	<section class="team-table">        		        	
        		<div class="table-title">
        			TEAM
        		</div>
                <div class="table-holder">
                	<div class="table-header">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                            	<th></th>	                            	
	                                <th>Leads</th>	                                
	                                <th>Sits</th>
	                                <th>Appointments<br>Scheduled</th>
	                                <th>Closes</th>
	                                <th>Installs<br>Scheduled</th>
	                                <th>Leads to Sit<br>Conversion Rate</th>	                                
	                                <th>Close to<br>Install Rate</th>	                                
	                            </tr>
	                        </thead>
	                    </table>
	                </div>  	                
	                <div class="table-content">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                                <th></th>
	                                <th>Leads</th>
	                                <th>Sits</th>	   
	                                <th>Appointments<br>Scheduled</th>                             
	                                <th>Closes</th>
	                                <th>Installs<br>Scheduled</th>
	                                <th>Leads to Sit<br>Conversion Rate</th>	                                
	                                <th>Close to<br>Install Rate</th>	                                
	                            </tr>
	                        </thead>
	                        <tbody>	                        	
	                        </tbody>
	                    </table>      
	                </div> 
                </div>
                <div class="clear100"></div> 
            </section>            
            <section class="fm-table fm">        		
        		<div class="table-title">
        			FIELD MARKETERS
        		</div>
                <div class="table-holder">
                	<div class="table-header">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                            	<th>Rep</th>
	                            	<th></th>	                            	
	                                <th>Leads</th>	                                
	                                <th>Sits</th>
	                                <th>Appointments<br>Scheduled</th>
	                                <th>Leads to Sit<br>Conversion Rate</th>	                                
	                            </tr>
	                        </thead>
	                    </table>
	                </div>  	                
	                <div class="table-content">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                                <th>Rep</th>
	                            	<th></th>	                            	
	                                <th>Leads</th>	                                
	                                <th>Sits</th>
	                                <th>Appointments<br>Scheduled</th>
	                                <th>Leads to Sit<br>Conversion Rate</th>
	                            </tr>
	                        </thead>
	                        <tbody>	                        		                    
	                        </tbody>
	                    </table>      
	                </div> 
                </div>
                <div class="clear100"></div> 
            </section>            
            <section class="fm-table jec">        		
        		<div class="table-title">
        			JUNIOR ENERGY CONSULTANT
        		</div>
                <div class="table-holder">
                	<div class="table-header">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                            	<th>Rep</th>
	                            	<th></th>	                            	
	                                <th>Leads</th>	                                	  
	                                <th>Sits</th>
	                                <th>Appointments<br>Scheduled</th>
	                                <th>Closes</th>                             
	                                <th>Installs Scheduled</th>
	                                <th>Leads to Sit<br>Conversion Rate</th>	                                
	                                <th>Close to Install Rate</th>
	                            </tr>
	                        </thead>
	                    </table>
	                </div>  	                
	                <div class="table-content">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                                <th>Rep</th>
	                            	<th></th>	                            	
	                                <th>Leads</th>	                                	  
	                                <th>Sits</th>
	                                <th>Appointments<br>Scheduled</th>
	                                <th>Closes</th>                             
	                                <th>Installs Scheduled</th>
	                                <th>Leads to Sit<br>Conversion Rate</th>	                                
	                                <th>Close to Install Rate</th>
	                            </tr>
	                        </thead>
	                        <tbody>	                    	
	                        </tbody>
	                    </table>      
	                </div> 
                </div>
                <div class="clear100"></div> 
            </section>            
            <section class="fm-table sec">        		
        		<div class="table-title">
        			SENIOR ENERGY CONSULTANT
        		</div>
                <div class="table-holder">
                	<div class="table-header">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                            	<th>Rep</th>
	                            	<th></th>	       
	                            	<th>Leads</th>                         
	                                <th>Sits</th>	
	                                <th>Appointments<br>Scheduled</th>   	                                
	                                <th>Closes</th>                             
	                                <th>Installs Scheduled</th>
	                                <th>Sit to<br>Close Rate</th>	                                
	                            </tr>
	                        </thead>
	                    </table>
	                </div>  	                
	                <div class="table-content">
	                    <table class="table center">
	                        <thead>
	                            <tr>
	                                <th>Rep</th>
	                            	<th></th>	       
	                            	<th>Leads</th>                         
	                                <th>Sits</th>	 
	                                <th>Appointments<br>Scheduled</th>  	                                
	                                <th>Closes</th>                             
	                                <th>Installs Scheduled</th>	                                
	                                <th>Sit to<br>Close Rate</th>	                               
	                            </tr>
	                        </thead>
	                        <tbody>	                        	                    
	                        </tbody>
	                    </table>      
	                </div> 
                </div> 
            </section>
        </div>  

    </div>
    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">        
    	$(document).ready(function(){
    		function updatetables(data) {  
    			$("#message").remove();
    			if(data['success']) {
    				if(data['team_table']=="") {
	    				$(".team-table").hide();
	    			} else {
	    				$(".team-table").show();
	    				$(".team-table tbody").html(data['team_table']);
	    			}

	    			if(data['fm_table']=="") {
	    				$("section.fm").hide();	
	    			} else {
	    				$("section.fm").show();	
	    				$("section.fm tbody").html(data['fm_table']);
	    			}

	    			if(data['jec_table']=="") {
	    				$("section.jec").hide();
	    			} else {
	    				$("section.jec").show();
	    				$("section.jec tbody").html(data['jec_table']);
	    			}

	    			if(data['sec_table']=="") {
	    				$("section.sec").hide();
	    			} else {
	    				$("section.sec").show();
	    				$("section.sec tbody").html(data['sec_table']);
	    			}
    			} else {
    				$(".team-table,section.fm,section.jec,section.sec").hide();
    				$(".goals").prepend('<div id="message"><div class="clear50"></div><div class="h4 center">'+data['message']+'</div></div>');
    			}

    			
    		}

    		$.post("/api/get_team_goals_api",{"goals":1,"team":$('select[name=team_name]').val()},function(data){          								
    			var result = JSON.parse(data);
    			updatetables(result);
    		})

    		$("select[name=team_name]").change(function(){
    			var team = $(this).val();
    			$("select[name=team]").val(team);
    			if($('.divblue-backdrop .toggle-options-wm-val.active').text()=="Week") {					
					$.post("/api/get_team_goals_api",{"goals":1,"weeknum":$(".dropdown-period select[name=week]").val(),"team":$('select[name=team_name]').val()},function(data){ 
	    				var result = JSON.parse(data);    			
		    			updatetables(result);
		    		})			
    			} else {
    				$.post("/api/get_team_goals_api",{"goals":1,"month":$(".dropdown-period select[name=month]").val(),"team":$('select[name=team_name]').val()},function(data){    		
	    				var result = JSON.parse(data);    			
		    			updatetables(result);
		    		})
    			}
    		});

    		$(".divblue-backdrop .dropdown-period select[name=week]").change(function(){      
    			$("#addgoalform select[name=week]").val($(this).val());
    			$.post("/api/get_team_goals_api",{"goals":1,"weeknum":$(this).val(),"team":$('select[name=team_name]').val()},function(data){     			  var result = JSON.parse(data);    			
	    			updatetables(result);
	    		})
    		})

    		$(".divblue-backdrop .dropdown-period select[name=month]").change(function(){
    			$("#addgoalform select[name=month]").val($(this).val());
    			$.post("/api/get_team_goals_api",{"goals":1,"month":$(this).val(),"team":$('select[name=team_name]').val()},function(data){   
    				var result = JSON.parse(data);
    				updatetables(result);
	    		})
    		})

    		$("#addgoalform").submit(function(e){
    			e.preventDefault();
    			$("#addgoalform .h4").append('<img src="/assets/images/spinner.gif" style="height:25px;margin-top:-5px" class="spinner-gif">');    			
    			$.post("/api/add-goal/",{"goal":1,"data":$( this ).serialize()},function(data){ 
    				console.log(data);   				
    				var result = JSON.parse(data);
    				if(result["success"]) {    			
    					setTimeout(function(){ 
    						$(".spinner-gif").remove();
    						$("#addgoalform .h4").append('<img src="/assets/images/success.png" style="height:25px;margin-top:-5px;" class="spinner-gif">')
    						$( '#addgoalform' ).each(function(){
							    this.reset();
							});

							setTimeout(function(){ 
	    						$(".spinner-gif").remove();
	    						location.reload();
	    					}, 1500);
    					}, 1000);

    				}
    			})
    		})

    		$("select[name=user_name],#addgoalform select[name=week],#addgoalform select[name=month]").change(function(){
    			if($("select[name=user_name]").val()!="") {
    				$.post("/api/check-goal",{"user_id":$("select[name=user_name]").val(),"type":$("input[name=period_type]").val(),"week":$("#addgoalsbox select[name=week]").val(),"month":$("#addgoalsbox select[name=month]").val()},function(data){        								
	    				var result = JSON.parse(data);    					
	    				
	    				if(result) {
	    					$("#addgoalform input[name=leads]").val(result.leads);
	    					$("#addgoalform input[name=sits]").val(result.sits);
	    					$("#addgoalform input[name=appointments]").val(result.appointments);
	    					$("#addgoalform input[name=closes]").val(result.closes);
	    					$("#addgoalform input[name=installs]").val(result.installs);

	    					if(Object.keys(result).length > 1) {
	    						$("#addgoalform button.addgoal").text("Update Goal"); 
							} else {
								$("#addgoalform input[name=leads]").val(0);
		    					$("#addgoalform input[name=sits]").val(0);
		    					$("#addgoalform input[name=appointments]").val(0);
		    					$("#addgoalform input[name=closes]").val(0);
		    					$("#addgoalform input[name=installs]").val(0);		    					
		    					$("#addgoalform button.addgoal").text("Add Goal");							
							}    					

	    					if(result.role == "Field Marketer") {
	    						$("#addgoalform .leadsgroup").show();
		    					$("#addgoalform .sitsgroup").show();
		    					$("#addgoalform .appointmentsgroup").show();
		    					$("#addgoalform .closesgroup").hide();
		    					$("#addgoalform .installsgroup").hide();		    					
	    					} else if(result.role == "Jr Energy Consultant") {
	    						$("#addgoalform .leadsgroup").show();
		    					$("#addgoalform .sitsgroup").show();
		    					$("#addgoalform .appointmentsgroup").show();
		    					$("#addgoalform .closesgroup").show();
		    					$("#addgoalform .installsgroup").show();		    					
	    					} else if(result.role == "Sr Energy Consultant" || result.role == "Manager" || result.role == "VP" || result.role == "CEO") {
	    						$("#addgoalform .leadsgroup").show();
		    					$("#addgoalform .sitsgroup").show();
		    					$("#addgoalform .appointmentsgroup").show();
		    					$("#addgoalform .closesgroup").show();
		    					$("#addgoalform .installsgroup").show();		    					
	    					}
	    				}
	    			})
    			} else {
    				$("#addgoalform .leadsgroup").hide();
					$("#addgoalform .sitsgroup").hide();
					$("#addgoalform .appointmentsgroup").hide();
					$("#addgoalform .closesgroup").hide();
					$("#addgoalform .installsgroup").hide();					
    			}
    		})

    		$("#addgoalform select[name=team]").change(function(){
    			$.post("/api/get_user_byteam_addgoal",{"team":$(this).val()},function(data){     				
    				$("select[name=user_name]").html(data);
    			});
    		})

    		$(".addGoalsBtn").click(function(){
    			
    			var team = $('select[name=team_name]').val();

    			$.post("/api/get_user_byteam_addgoal",{"team":team},function(data){    						
    				$("select[name=user_name]").html(data);
    			});

    			$("#addgoalsbox,#cover").show();
    		})

    		$(".closebtn").click(function(){
    			$("#addgoalsbox,#cover").hide();
    		})

    		$(document).scroll(function(){
    			var height = $(window).scrollTop();
    			if(height >= 705) {
    				$(".divblue-backdrop").addClass("sticky");    				
    				$(".goals").css("margin-top","200px");
    			} else {
    				$(".divblue-backdrop").removeClass("sticky");
    				$(".goals").css("margin-top","0px");
    			}
    		})

    		$(".table-content table").each(function(){
    			if($(this).height() > 380) {
    				$(this).parent().addClass("scroll");
    			}

    		})

    		$(".divblue-backdrop .toggle-options-wm-val").click(function(){
    			$(".divblue-backdrop .toggle-options-wm-val").each(function(){
    				$(this).removeClass("active");
    			})    			
    			$(this).addClass("active");
    			var type = $(this).text().toLowerCase();

    			$(".divblue-backdrop .dropdown-period select").each(function(){
    				$(this).removeClass("active");
    			})
    			$("#addgoalform .toggle-options-wm-val."+type).click();
    			$(".divblue-backdrop .dropdown-period select[name="+type+"]").addClass("active");

    			if($('.divblue-backdrop .toggle-options-wm-val.active').text()=="Week") {					
					$.post("/api/get_team_goals_api",{"goals":1,"weeknum":$(".dropdown-period select[name=week]").val(),"team":$('select[name=team_name]').val()},function(data){    						
	    				var result = JSON.parse(data);    			
		    			updatetables(result);
		    		})			
    			} else {
    				$.post("/api/get_team_goals_api",{"goals":1,"month":$(".dropdown-period select[name=month]").val(),"team":$('select[name=team_name]').val()},function(data){    		
	    				var result = JSON.parse(data);    			
		    			updatetables(result);
		    		})
    			}
    		})   

    		$("#addgoalsbox .toggle-options-wm-val").click(function(){    			
    			$("#addgoalsbox .toggle-options-wm-val").each(function(){
    				$(this).removeClass("active");
    			})    			
    			$(this).addClass("active");
    			var type = $(this).text().toLowerCase();

    			$("#addgoalsbox .dropdown-period select").each(function(){
    				$(this).removeClass("active");
    			})
    			$("#addgoalsbox .dropdown-period select[name="+type+"]").addClass("active");
    			$("input[name=period_type]").val(type);

    			if($("select[name=user_name]").val()!="") {    				
    				$.post("/api/check-goal",{"user_id":$("select[name=user_name]").val(),"type":$("input[name=period_type]").val(),"week":$("#addgoalsbox select[name=week]").val(),"month":$("#addgoalsbox select[name=month]").val()},function(data){
	    				var result = JSON.parse(data);	    				
	    				if(result) {
	    					$("#addgoalform input[name=leads]").val(result.leads);
	    					$("#addgoalform input[name=sits]").val(result.sits);
	    					$("#addgoalform input[name=appointments]").val(result.appointments);
	    					$("#addgoalform input[name=closes]").val(result.closes);
	    					$("#addgoalform input[name=installs]").val(result.installs);	
	    					$("#addgoalform button.addgoal").text("Update Goal");
	    				} else {
	    					$("#addgoalform input[name=leads]").val(0);
	    					$("#addgoalform input[name=sits]").val(0);
	    					$("#addgoalform input[name=appointments]").val(0);
	    					$("#addgoalform input[name=closes]").val(0);
	    					$("#addgoalform input[name=installs]").val(0);
	    					$("#addgoalform button.addgoal").text("Add Goal");
	    				}
	    			})
    			}
    		})    		    		
    	})
    </script>
</body>
</html>