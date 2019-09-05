<?php include("View/includes/head.php"); ?>
<body>
    <?php include("View/includes/home-nav.php"); ?>    
    <div id="content" class="content">
        <div class="container">        	
        	<div class="center">
        		<img src="<?php echo IMAGE_BASE_URL.$_SESSION['user']['picture']; ?>" class="dashboard-profile-img">
        		<div class="clear10"></div>
        		<div class="h3 colorBlue"><?php echo $_SESSION['user']['name']; ?></div>
        		<div class="h5"><strong><?php echo $_SESSION['role']; ?></strong></div>
        	</div> 	
        </div>
        <div class="divblue-backdrop">
        	Period <select name="period">
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
			<div class="center customdate">
				<div class="clear20"></div>
				Start Date
				<input type="date" name="prevdate" value=""/>
				End Date
				<input type="date" name="curdate" value=""/>
			</div>
			
        </div>
        <?php if($_SESSION['role']=="Field Marketer" || $_SESSION['role']=="Field Marketer Elite"): ?>
	        <section class="fm-board stats-board">
	        	<div class="mini-container container">
	        		<div class="row">
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">Score</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value score-value"><?php echo number_format($data['sf_data']['score']); ?></div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">Leads</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value leads-value"><?php echo number_format($data['sf_data']['leads']); ?></div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">QS</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value qs-value"><?php echo $data['sf_data']['sits']; ?></div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">Leads to QS</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value leadstoqs-value">0%</div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">        						
	        						<div class="col-xs-7">
	        							<div class="stat-title">Close to Install Rate</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value closetoinstall-value">0%</div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">KWs Installed Year to Date</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value kw-value">0 KWs</div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="clear20"></div>
	        			<div class="col-xs-12">
	        				<div class="record-holder">
		        				<div class="row">
		        					<div class="col-sm-5 col-md-4">
		        						<div class="records-section-title"><strong>Record</strong></div>
		        					</div>
		        				</div>
		        			</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="record-holder">
	        					<div class="row">        						
	        						<div class="col-sm-4 col-sm-offset-0 col-xs-4 col-xs-offset-1 pdr0">
	        							<div class="record-title">Leads</div>
	        						</div>
	        						<div class="col-sm-3 col-xs-3 pdr0">
	        							<div class="record-data-holder week">
	        								<div class="record-label">Week</div>
	        								<div class="record-value leads-week-value">0</div>
	        							</div>
	        						</div>
	        						<div class="col-sm-3 col-xs-3 pdl0">
	        							<div class="record-data-holder month">
	        								<div class="record-label">Month</div>
	        								<div class="record-value leads-month-value">0</div>
	        							</div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="record-holder">
	        					<div class="row">        						
	        						<div class="col-sm-4 col-sm-offset-0 col-xs-4 col-xs-offset-1 pdr0">
	        							<div class="record-title">QS</div>
	        						</div>
	        						<div class="col-sm-3 col-xs-3 pdr0">
	        							<div class="record-data-holder week">
	        								<div class="record-label">Week</div>
	        								<div class="record-value qs-week-value">0</div>
	        							</div>
	        						</div>
	        						<div class="col-sm-3 col-xs-3 pdl0">
	        							<div class="record-data-holder month">
	        								<div class="record-label">Month</div>
	        								<div class="record-value qs-month-value">0</div>
	        							</div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>	        			
	        		</div>
	        	</div>
	        </section>
	    <?php endif; ?>
	    <?php if($_SESSION['role']=="Jr Energy Consultant" || $_SESSION['role']=="Sr Energy Consultant"): ?>
	        <section class="ec-board stats-board">
	        	<div class="mini-container container">
	        		<div class="row">
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">Score</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value score-value"><?php echo $data['sf_data']['score']; ?></div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">Closes</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value closes-value"><?php echo $data['sf_data']['closes']; ?></div>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">QS to Close %</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value sitstoclose-value"><?php echo $data['sf_data']['sitstoclose']; ?>%</div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">Installs</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value installs-value"><?php echo $data['sf_data']['installs']; ?></div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">        						
	        						<div class="col-xs-7">
	        							<div class="stat-title">Close to Install Rate</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value closetoinstall-value"><?php echo $data['sf_data']['closetoinstall']; ?></div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        			<div class="col-sm-6">
	        				<div class="stats-holder">
	        					<div class="row">
	        						<div class="col-xs-7">
	        							<div class="stat-title">KWs Installed Year to Date</div>
	        						</div>
	        						<div class="col-xs-5">
	        							<div class="stat-value kw-value">0 KWs</div>        							
	        						</div>
	        					</div>
	        				</div>
	        			</div>	        				        		
	        		</div>
	        	</div>
	        </section>
	    <?php endif; ?>
    </div>
    <?php include("View/includes/foot.php"); ?>
    <script type="text/javascript">
    	$(document).ready(function(){
    		$(".stats-board").prepend('<div id="spinner" style="text-align:center;margin-bottom:-40px;"><img src="/assets/images/spinner.gif" style="margin: 100px;height: 40px;"></div>');
    		$(".stats-board .container").css("opacity","0");
    		$.post("/api/get-dashboard-data",{"period":"Today"},function(data){
				get_json_data(data);
			})

    		$("select[name=period]").change(function(){
    			if($(this).val()=="Custom") {
    				$(".customdate").show();
    			} else {
    				$(".customdate").hide();
    				$(".stats-board").prepend('<div id="spinner" style="text-align:center;margin-bottom:-40px;"><img src="/assets/images/spinner.gif" style="margin: 100px;height: 40px;"></div>');
    				$(".stats-board .container").css("opacity","0");
    				$.post("/api/get-dashboard-data",{"period":$("select[name=period]").val()},function(data){  
    					get_json_data(data);
					})
    			}
    			
    		})

    		$("input[name=prevdate]").change(function(){
    			check_customdate();
    		})

    		$("input[name=curdate]").change(function(){
    			check_customdate();
    		})

    		function check_customdate() {
    			$("input[name=prevdate]").css("border-color","initial");
    			if($("input[name=prevdate]").val()!="" && $("input[name=curdate]").val()) {
    				if($("input[name=prevdate]").val() <= $("input[name=curdate]").val()) {
    					$(".stats-board").prepend('<div id="spinner" style="text-align:center;margin-bottom:-40px;"><img src="/assets/images/spinner.gif" style="margin: 100px;height: 40px;"></div>');
    					$(".stats-board .container").css("opacity","0");
    					$.post("/api/get-dashboard-data",{"period":$("select[name=period]").val(),"prevdate":$("input[name=prevdate]").val(),"curdate":$("input[name=curdate]").val()},function(data){
    						get_json_data(data);
    					})
    				} else {    					
    					$("input[name=prevdate]").css("border-color","#f00");
    				}
    			}
    		}

    		function get_json_data(data) {    			
				var result = JSON.parse(data);						
				$(".commission-value").text("$"+result.commission);
				$(".leads-value").text(result.leads);
				$(".leads-week-value").text(result.leadsweekvalue);
				$(".leads-month-value").text(result.leadsmonthvalue);
				$(".qs-value").text(result.sits);
				$(".qs-week-value").text(result.sitsweekvalue);
				$(".qs-month-value").text(result.sitsmonthvalue);
				$(".leadstoqs-value").text(result.leadtosits+"%");
				$(".score-value").text(result.score);
				$(".closes-value").text(result.closes);
				$(".sitstoclose-value").text(result.sitstoclose+"%");
				$(".installs-value").text(result.installs);
				$(".closetoinstall-value").text(result.closetoinstall);
				$(".kw-value").text(result.kwperyear+" KWs");
				$("#spinner").remove();
				$(".stats-board .container").css("opacity","1");
    		}
    	});
    </script>
</body>
</html>