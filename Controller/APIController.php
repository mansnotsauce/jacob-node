<?php 
	function index() {			
		$sf = new horizon_salesforce();
		/* ================================================
		 Password Authentication
		================================================ */
		$response = $sf->authorize([
		    'grant_type'=>'password',
		    'username'=>SALESFORCE_USERNAME,
		    'password'=>SALESFORCE_PASSWORD,
		    'redirect_uri'=>SALESFORCE_REDIRECT_URI
		]);

		echo '<h1>oAuth Access Token</h1>';
		echo '<pre>';
		print_r($response);
		echo '</pre>';

		# Write new config file
		if (isset($response['access_token'])) {
		    $cfg_data = "<?php\r\n";
		    $cfg_data .= "define('SALESFORCE_ACCESS_TOKEN', '" . $response['access_token'] . "');\r\n";
		    $cfg_data .= "define('SALESFORCE_INSTANCE_URL', '" . $response['instance_url'] . "');\r\n";
		    file_put_contents(PATH_ROOT . 'config.php', $cfg_data);
		}		
		header("Location: /?e=api");		
	}

	function oauth_callback() {
		$sf = new horizon_salesforce();
		/* ================================================
		 Regular Authorization
		================================================ */
		$response = $sf->authorize([
		    'code' => $_POST['code'],
		    'grant_type'=>'authorization_code',
		    'redirect_uri'=>SALESFORCE_REDIRECT_URI
		]);

		echo '<h1>oAuth Access Token</h1>';
		echo '<pre>';
		print_r($response);
		echo '</pre>';
		/* ================================================
		 Refresh Token
		================================================ */
		$refresh_response = $sf->authorize(['grant_type'=>'refresh_token', 'refresh_token'=>$response['refresh_token']]);

		echo '<h1>Permanent Token Below</h1>';
		echo '<pre>';
		print_r($refresh_response);
		echo '</pre>';

		# Write new config file
		if (isset($refresh_response['access_token'])) {
		    $cfg_data = "<?php\r\n";
		    $cfg_data .= "define('SALESFORCE_ACCESS_TOKEN', '" . $refresh_response['access_token'] . "');\r\n";
		    $cfg_data .= "define('SALESFORCE_INSTANCE_URL', '" . $refresh_response['instance_url'] . "');\r\n";


		    echo PATH_ROOT . 'salesforce/horizon_sf_auth.php';
		    file_put_contents(PATH_ROOT . 'salesforce/horizon_sf_auth.php', $cfg_data);
		}
		header("Location: /?e=callback");
		exit;
	}

	function onboarding() {		
		if(isset($_POST['onboarding'])){
			parse_str($_POST["data"], $postdata);
			$sf = new horizon_salesforce();			
			$pass = chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122));			
			
			$result = $sf->query("SELECT Name, Id, Phone FROM Account WHERE Company_Email__c='" . $postdata['email'] . "' LIMIT 1");	
			
			if (isset($result['records'][0]['Id'])) {
				$result['success'] = false;
			}
			else {
				$password = horizon_login::create_password($pass);
				$email = $postdata['email'];
				$position = $postdata['position'];
				$team = $postdata['team'];
				$name = ucwords($postdata['first_name']." ".$postdata['last_name']);
				$data = array("Name"=>$name,"Position__c"=>$position,"Company_Email__c"=>$email,"Personal_Email__c"=>$email,"User_Password_Hash__c"=>$password,"Status__c"=>"Onboarding","Team__c"=>$team);
				$sf->welcome_email($email,$pass);
				$result = $sf->create_account($data);
			}	

			echo json_encode($result);
		} else {
			header("Location: /");
		}	
		exit;		
	}

	function update_password() {
		if(isset($_POST['password'])) {
			$sf = new horizon_salesforce();	
			$account = $sf->get_account($_POST['user_id']);		
			$pass = $_POST['password'];
			$password = horizon_login::create_password($pass);
			$param['User_Password_Hash__c'] = $password;
			$sf->update_account($_POST['user_id'],$param);			
			echo "success";
		} else {
			header("Location: /");
		}
		exit;
	}

	function password_reset() {
		if(isset($_POST['reset'])) {
			$sf = new horizon_salesforce();	
			$account = $sf->get_account($_POST['id']);		
			$pass = chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122));
			$password = horizon_login::create_password($pass);
			$param['User_Password_Hash__c'] = $password;
			$sf->update_account($_POST['id'],$param);
			$sf->password_reset_email($account['Company_Email__c'],$pass);
			echo "success";
		} else {
			header("Location: /");
		}
		exit;
	}

	function adduser() {
		if(isset($_POST['adduser'])){
			parse_str($_POST["data"], $postdata);
			$sf = new horizon_salesforce();			
			$pass = chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122));			
			
			$result = $sf->query("SELECT Name, Id, Phone FROM Account WHERE Company_Email__c='" . $postdata['email'] . "' LIMIT 1");	
			
			if (isset($result['records'][0]['Id'])) {
				$result['success'] = false;
			}
			else {
				$password = horizon_login::create_password($pass);
				$email = $postdata['email'];
				$phone = $postdata['phone'];
				$team = $postdata['team'];
				$position = $postdata['position'];
				$name = ucwords($postdata['first_name']." ".$postdata['last_name']);
				$data = array("Phone"=>$phone,"Name"=>$name,"Position__c"=>$position,"Company_Email__c"=>$email,"Personal_Email__c"=>$email,"User_Password_Hash__c"=>$password,"Team__C"=>$team,"Status__c"=>"Active","Onboarding_Complete__c"=>true);
				$sf->welcome_email2($email,$pass);
				$result = $sf->create_account($data);
			}	

			echo json_encode($result);
		} else {
			header("Location: /");
		}	
		exit;
	}

	function login() {	
		if (isset($_POST['pass'])) {				
			$attempt = horizon_login::attempt_to_authenticate();		
			if ($attempt=="true") {		
				$data['success'] = 1;
				$data['message'] = "success";
			}
			else {		
				$data['success'] = 0;
				$data['message'] = $attempt;
			}
	
			echo json_encode($data);	        
	    }
	    exit;
	}

	function update_profile(){
		if(isset($_POST['update'])) {
			$sf = new horizon_salesforce();
			$pm = new pwr_model();
			parse_str($_POST["data"], $postdata);
			if($postdata["id"]==$_SESSION['user_id']) {
				$_SESSION['user']['name']=$param["Name"]=$postdata["name"];
				$_SESSION['user']['name_arr']=explode(" ",$param["Name"]);			
				$_SESSION['user']['phone']=$param["Phone"]=$postdata["phone"];	
				if(isset($postdata["position"])) {
					$_SESSION['role']=$param["Position__c"]=$postdata["position"];	
				}
				if(isset($postdata["team"])) {
					$_SESSION['user']['team']=$param["Team__c"]=$postdata["team"];	
				}
				
			} else {
				$param["Name"]=$postdata["name"];
				$param["Phone"]=$postdata["phone"];

				if(isset($postdata["position"])) {
					$param["Position__c"]=$postdata["position"];
				}
				if(isset($postdata["team"])) {
					$_SESSION['user']['team']=$param["Team__c"]=$postdata["team"];	
				}
			}
			$pm->update_account($postdata["id"],$param);
			$sf->update_account($postdata["id"],$param);
			echo "success";
		} else {
			header("Location: /");
		}
		exit;
	}

	function approve_onboarding() {
		$sf = new horizon_salesforce();	
		if (isset($_POST['user_id'])) {
			$pm = new pwr_model();
			$pass = chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122)).chr(rand(65,122));
			$password = horizon_login::create_password($pass);
			$param['Status__c'] = "Active";
			$param['Onboarding_Complete__c'] = true;
			$param['Company_Email__c'] = $_POST['email'];
			$param["User_Password_Hash__c"] = $password;

			$res = $sf->update_account($_POST['user_id'],$param);				
			$pm->approve_onboarding_account($_POST['user_id'],$param);
			$account = $pm->get_account($_POST['user_id']);
			$sf->welcome_email2($_POST['email'],$pass);
			$sf->welcome_email2($_POST['email'],$pass,$account['Personal_Email__c']);			
			echo "success";
		}
		exit;
	}

	function admin_bulk() {
		$sf = new horizon_salesforce();	
		if (isset($_POST['type'])) {
			$pm = new pwr_model();
			if($_POST['type']=="Approve") {
				$param['Status__c'] = "Active";
				$param['Onboarding_Complete__c'] = true;					
				foreach ($_POST['id'] as $key => $value) {
					$res = $sf->update_account($value,$param);					
					$pm->approve_onboarding_account($value,$param);					
				}
			} else if($_POST['type']=="Delete") {
				foreach ($_POST['id'] as $key => $value) {					
					$param['Status__c'] = "Inactive";
					$param['Reason_Inactive__c'] = "Deleted";
					$sf->update_account($value,$param);
					$pm->delete_account($value);
				}
			}
			echo "success";
		}
		exit;
	}

	function get_dashboard_data() {		
		if(isset($_POST['period'])) {
			$period = $_POST['period'];
			$prevdate = false;
			$curdate = false;
			$sf = new horizon_salesforce();	
			$pm = new pwr_model();						

			if($period=="Custom") {
				$prevdate=$_POST['prevdate'];
				$curdate=$_POST['curdate'];
			}			

			if(isset($_POST['id'])) {
				$user_id = $_POST['id'];
			} else {
				$user_id = $_SESSION['user_id'];
			}			

			$account = $pm->get_account($user_id);
			if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {
				$sitspoints = 2;
			} else {
				$sitspoints = .25;
			}

			$data['user_info'] = $pm->get_user_basicinfo($user_id);
			$data['leads'] = $pm->get_lead($user_id,$period,$prevdate,$curdate);		
			$data['leadsweekvalue'] = get_weekly_record($user_id,"leads");
			$data['leadsmonthvalue'] = get_monthly_record($user_id,"leads");		
			$data['commission'] = $pm->get_commission($user_id,$period,$prevdate,$curdate);						
			$data['closes'] = $pm->get_closes($user_id,$period,$prevdate,$curdate);			
			$data['sits'] = $pm->get_sits($user_id,$period,$prevdate,$curdate);		
			$data['sitsweekvalue'] = get_weekly_record($user_id,"sits");
			$data['sitsmonthvalue'] = get_monthly_record($user_id,"sits");	
			$data['sitstoclose'] = getPercentage($data['closes'],$data['sits']);
			$data['leadtosits'] = getPercentage($data['sits'],$data['leads']);
			$data['installs'] = $pm->get_installs($user_id,$period,$prevdate,$curdate);											
			$data['assisted_installs'] = $pm->get_assisted_installs($user_id,$period,$prevdate,$curdate);		
			$data['assisted_close'] = $pm->get_assisted_close($user_id,$period,$prevdate,$curdate);	
			$data['self_generated_installs'] = $pm->get_self_generated_installs($user_id,$period,$prevdate,$curdate);			
			$data['closetoinstall'] = getPercentage($data['assisted_installs']+$data['self_generated_installs'],$data['closes']);
			$data['score'] = $data['leads'] + ($data['sits']*$sitspoints) + ($data['closes']*3) + ($data['assisted_installs']*4) + ($data['self_generated_installs']*6);
			$data['kwperyear'] = $pm->get_KW_per_year($user_id);			
			echo json_encode($data);
			exit;
		}

		exit;
	}

	function get_user_data($user_id,$period,$prevdate=false,$curdate=false) {		
			$sf = new horizon_salesforce();	
			$pm = new pwr_model();									
			$account = $pm->get_account($user_id);
			if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {
				$sitspoints = 2;
			} else {
				$sitspoints = .25;
			}

			$data['leads'] = $pm->get_lead($user_id,$period,$prevdate,$curdate);			
			$data['commission'] = $pm->get_commission($user_id,$period,$prevdate,$curdate);
			$data['closes'] = $pm->get_closes($user_id,$period,$prevdate,$curdate);
			$data['sits'] = $pm->get_sits($user_id,$period,$prevdate,$curdate);
			$data['appointments'] = $pm->get_appointments($user_id,$period,$prevdate,$curdate);
			$data['sitstoclose'] = getPercentage($data['closes'],$data['sits']);
			$data['leadtosits'] = getPercentage($data['sits'],$data['leads']);
			$data['installs'] = $pm->get_installs($user_id,$period,$prevdate,$curdate);
			$data['assisted_installs'] = $pm->get_assisted_installs($user_id,$period,$prevdate,$curdate);
			$data['assisted_installs_fm'] = $pm->get_assisted_installs_fm($user_id,$period,$prevdate,$curdate);
			$data['assisted_close'] = $pm->get_assisted_close($user_id,$period,$prevdate,$curdate);
			$data['self_generated_installs'] = $pm->get_self_generated_installs($user_id,$period,$prevdate,$curdate);
			$data['closetoinstall'] = getPercentage($data['assisted_installs']+$data['self_generated_installs'],$data['closes']);
			$data['score'] = $data['leads'] + ($data['sits']*$sitspoints) + ($data['closes']*3) + ($data['assisted_close']*3) + ($data['assisted_installs']*4) + ($data['assisted_installs_fm']*4) + ($data['self_generated_installs']*6);
			$data['kwperyear'] = $pm->get_KW_per_year($user_id);
			return $data;

		exit;
	}	

	function add_video() {		
		if(isset($_POST['video_link'])) {
			$pm = new pwr_model();		
				
			parse_str( parse_url( $_POST['video_link'], PHP_URL_QUERY ), $my_array_of_vars );
			if(count($my_array_of_vars)>0) 
			{
				$vid_id = $my_array_of_vars['v'];
			} else {
				$my_array_of_vars = explode("youtu.be/",$_POST['video_link']);
				$vid_id = $my_array_of_vars[1];
			}		

			$name = str_replace("'", "&#39;", $_POST['name']);
			$description = str_replace("'", "&#39;", $_POST['description']);
			if(isset($_POST['tags']))
				$tags = $_POST['tags'];
			else 
				$tags = NULL;

			$data = array("category"=>$_POST['category'],"name"=>$name,"description"=>$description,"link"=>$vid_id,"type"=>$_POST['type'],"tags"=>$tags);
			$result = $pm->add_video($data);
			echo json_encode($result);
		}
		exit;
	}

	function add_goal() {
		if(isset($_POST['goal'])) {
			$pm = new pwr_model();

			parse_str($_POST["data"], $postdata);

			if($postdata['period_type']=="month") {
				$week_start = NULL;
				$week_end = NULL;
				$week_number = 0;
				$month = $postdata['month'];
			} else {
				$week_arr = getStartAndEndDate($postdata['week'],date("Y"));
				$week_start = $week_arr['week_start'];
				$week_end = $week_arr['week_end'];
				$week_number = $postdata['week'];
				$month = NULL;
			}			

			if(isset($postdata['team'])) {
				$team = $postdata['team'];
			} else {
				$team = $_SESSION['user']['team'];
			}

			if($postdata['closes']=="") {
				$postdata['closes'] = 0;
			}
			if($postdata['installs']=="") {
				$postdata['installs'] = 0;
			}
			if(isset($postdata['fmunit']) && $postdata['fmunit']!="") {
				$fmunit = $postdata['fmunit'];
			} else {
				$fmunit = 0;
			}

			$data = array(
				"user_id"=>$postdata['user_name'],
				"team"=>$team,
				"leads"=>$postdata['leads'],
				"sits"=>$postdata['sits'],
				"appointments"=>$postdata['appointments'],
				"closes"=>$postdata['closes'],
				"installs"=>$postdata['installs'],		
				"fmunit"=>$fmunit,
				"week_start"=>date("Y-m-d",strtotime($week_start)),
				"week_end"=>date("Y-m-d",strtotime($week_end)),
				"week_number"=>$week_number,
				"month"=>$month,
				"created_by"=>$_SESSION['user_id']
			);			
			$result = $pm->add_goal($data);
			echo json_encode($result);
		}
		exit;
	}

	function delete_video() {		
		if(isset($_POST['id'])) {			
			$pm = new pwr_model();		
			$result = $pm->delete_video($_POST['id']);
			echo json_encode($result);
		}
		exit;
	}

	//CRON
	function set_accounts() {
		$timearr = explode(":",date("G:i"));
		if((int)$timearr[0] >= 7 && (int)$timearr[0] <= 23) {
			if((int)$timearr[1] >= 40) {
				echo "Success.";
			} else {
				if(isset($_GET['action'])) {
					$sf = new horizon_salesforce();
					$pm = new pwr_model();
					$url = "SELECT Id, Name, Company_Email__c, Personal_Email__c, Position__c, Phone, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c, Team__c from Account WHERE Status__c='Active' OR Status__c='Inactive'";
					$results = $sf->query($url);
					$url2 = "SELECT Id, Name, Company_Email__c, Personal_Email__c, Position__c, Phone, Team__c, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c from Account WHERE Status__c='Onboarding'";
					$results2 = $sf->query($url2);					
					$data = array_merge($results['records'], $results2['records']);						
					if(count($data)>0) {
						foreach ($data as $key => $value) {					
							$result = $pm->save_accounts($value);
							var_dump($result);
							echo "<br>";
						}
					}
				}
			}
		} else {
			if((int)$timearr[1] >= 5 && (int)$timearr[1] <= 30) {
				if(isset($_GET['action'])) {
					$sf = new horizon_salesforce();
					$pm = new pwr_model();
					$url = "SELECT Id, Name, Company_Email__c, Personal_Email__c, Position__c, Phone, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c, Team__c from Account WHERE Status__c='Active' OR Status__c='Inactive'";
					$results = $sf->query($url);
					$url2 = "SELECT Id, Name, Company_Email__c, Personal_Email__c, Position__c, Phone, Team__c, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c from Account WHERE Status__c='Onboarding'";
					$results2 = $sf->query($url2);					
					$data = array_merge($results['records'], $results2['records']);						
					if(count($data)>0) {
						foreach ($data as $key => $value) {					
							$result = $pm->save_accounts($value);
							var_dump($result);
							echo "<br>";
						}
					}
				}
			} else {
				echo "No Update.";
			}
		}		
		exit;		
	}

	//CRON
	function remind_onboarding() {
		if(isset($_GET['action'])) {
			$sf = new horizon_salesforce();
			$pm = new pwr_model();
			$url = "SELECT Id, Company_Email__c, Name, CreatedDate, Onboarding_Complete__c FROM Account WHERE Onboarding_Complete__c=false And Status__c='Onboarding'";
			$records = $sf->query($url);
			foreach ($records['records'] as $key => $value) {
				$value['CreatedDate'] = date("Y-m-d",strtotime($value['CreatedDate']));
				$result = $pm->add_reminder($value);
				var_dump($result);
				echo "<br>";
			}

		} else {
			header("Location: /");			
		}
		exit;
	}

	//CRON
	function opportunity() {
		$timearr = explode(":",date("G:i"));
		if((int)$timearr[0] >= 7 && (int)$timearr[0] <= 23) {
			if((int)$timearr[1] >= 40) {
				echo "Success.";
			} else {
				if(isset($_GET['action']))
				{			
					$pm = new pwr_model();	
					$pm->truncate_table("opportunity");
					$result = $pm->update_opportunity("leads");
					$result = $pm->update_opportunity("sits");
					exit;
				} else {
					header("Location: /");	
				}				
			}			
		} else {
			if((int)$timearr[1] >= 5 && (int)$timearr[1] <= 30) {
				if(isset($_GET['action']))
				{			
					$pm = new pwr_model();	
					$pm->truncate_table("opportunity");					
					$result = $pm->update_opportunity("leads");
					$result = $pm->update_opportunity("sits");
					exit;
				} else {
					header("Location: /");	
				}	
			} else {
				echo "No Update.";
			}
		}
		
		exit;
	}

	//CRON
	function residential() {
		$timearr = explode(":",date("G:i"));
		if((int)$timearr[0] >= 7 && (int)$timearr[0] <= 23) {
			if(isset($_GET['action']))
			{
				if($_GET['action']=="update") {				
					$pm = new pwr_model();
					$pm->truncate_table("residential_projects");
					$result = $pm->update_residential_projects("closes");								
					$result = $pm->update_residential_projects("installs");
					$result = $pm->update_residential_projects("commission");
					$result = $pm->update_residential_projects("kw");
					exit;
				}
			} else {
				header("Location: /");
			}
		} else {
			if((int)$timearr[1] >= 5 && (int)$timearr[1] <= 30) {
				if(isset($_GET['action']))
				{
					if($_GET['action']=="update") {				
						$pm = new pwr_model();
						$pm->truncate_table("residential_projects");
						$result = $pm->update_residential_projects("closes");								
						$result = $pm->update_residential_projects("installs");
						$result = $pm->update_residential_projects("commission");
						$result = $pm->update_residential_projects("kw");
						exit;
					}
				} else {
					header("Location: /");
				}
			} else {
				echo "No Update.";
			}
		}
			
		exit;
	}

	//CRON
	function image_set() {		
		$timearr = explode(":",date("G:i"));
		if((int)$timearr[0] >= 7 && (int)$timearr[0] <= 23) {
			if(isset($_GET['action']))
			{
				if($_GET['action']=="update") {		
					$count = 0;		
					$pm = new pwr_model();
					$result = $pm->get_user_images();
					foreach ($result["records"] as $key => $value) {					
						$res = update_image($value['Id'],$value['profile_picture__c']);
						$count += $res;
					}
					echo $count." File/s Updated.";

				}
			} else {
				header("Location: /");
			}
		} else {
			if((int)$timearr[1] >= 5 && (int)$timearr[1] <= 30) {
				if(isset($_GET['action']))
				{
					if($_GET['action']=="update") {		
						$count = 0;		
						$pm = new pwr_model();
						$result = $pm->get_user_images();
						foreach ($result["records"] as $key => $value) {					
							$res = update_image($value['Id'],$value['profile_picture__c']);
							$count += $res;
						}
						echo $count." File/s Updated.";

					}
				} else {
					header("Location: /");
				}
			} else {
				echo "No Update.";
			}
		}	
		exit;
	}

	function update_image($id,$profile_picture=null) {
		if(isset($id)) {
			$x=0;
			$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
			$code = chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122));
			$img = $_SERVER['DOCUMENT_ROOT']."/assets/images/users/".$id.$code.".jpg";
			$new_arr['profile_picture__c'] = $img2 = "/assets/images/users/".$id.$code.".jpg";
			if (file_exists($img)) {
			    unlink($img);
			}
			
			$res = file_put_contents($img, file_get_contents($actual_link."/pic/".$id."")); 
	  
	  		if($res) {
	  			$pm = new pwr_model();	  			
	  			$sf = new horizon_salesforce();
	  			if($profile_picture!=null && $profile_picture!="") {
	  				$sf->update_account($id,$new_arr);
	  			}	  			
				$result = $pm->add_picture_db($id,$img2);
				if($result['success']==false) {
					unlink($img);		
				} else {
					$x++;
				}	  			
	  		} 
	  		return $x;		
		}				
		exit;
	}

	function add_team_names() {
		if(isset($_POST['name'])) {
			$pm = new pwr_model();
			$pm->add_team_names($_POST['name']);
			echo true;
		} else {
			echo false;
		}
		exit;
	}

	function get_field_marketers() {
		if(isset($_POST['period'])) {
			if(isset($_POST['sort'])) {
				$sort_val = str_replace(" ", "_", strtolower($_POST['sort']));
				if($_POST['sort']=="Close")
				{
					$sort_val = "closes";
				}
				else if($_POST['sort']=="QS")
				{
					$sort_val = "sits";
				}
				else if($_POST['sort']=="AS")
				{
					$sort_val = "appointments";
				}
				else if($_POST['sort']=="Self Generated Installs")
				{
					$sort_val = "score";
				}
				else if($_POST['sort']=="Assisted Installs")
				{
					$sort_val = "assisted_installs_fm";
				}
			} else {
				$sort_val = "score";
			}

			if(isset($_POST['order'])) {
				$orderby = $_POST['order'];
			} else {
				$orderby = "ASC";
			}

			if(isset($_POST['prevdate'])) {
				$prevdate = $_POST['prevdate'];
			}
			if(isset($_POST['curdate'])) {
				$curdate = $_POST['curdate'];
			}
			$pm = new pwr_model();
			$result = $pm->get_fm();
			$data=[];
			$order=[];
			foreach ($result as $key => $value) {
				$row['stats'] = get_user_data($value['user_id'],$_POST['period'],$prevdate,$curdate);				
				$row['info'] = $value;
				$data[] = $row;
				if($sort_val=="rep") {
					$order[$key] = $row['info']["Name"];
				} else {
					$order[$key] = $row['stats'][$sort_val];
				}
			}													

			if($orderby=="ASC")
				asort($order);
			else
				arsort($order);

			foreach ($order as $key => $value) {		
				echo "<tr>";
					echo "<td class='viewdetails' param='".$data[$key]['info']["user_id"]."'><img src='".$data[$key]['info']["picture"]."' class='prof-table-img'>".$data[$key]['info']["Name"]."</td>";
					echo "<td>".$data[$key]['stats']["score"]."</td>";
					echo "<td>".$data[$key]['stats']["leads"]."</td>";
					echo "<td>".$data[$key]['stats']["sits"]."</td>";
					echo "<td>".$data[$key]['stats']["appointments"]."</td>";
					echo "<td>".$data[$key]['stats']["assisted_close"]."</td>";
					echo "<td>".$data[$key]['stats']["assisted_installs_fm"]."</td>";
				echo "</tr>";				
			}	
			
		} else {
			echo false;
		}		
		exit;
	}

	function get_energy_consultant() {
		if(isset($_POST['period'])) {
			if(isset($_POST['sort'])) {
				$sort_val = str_replace(" ", "_", strtolower($_POST['sort']));
				if($_POST['sort']=="Close")
				{
					$sort_val = "closes";
				}
				else if($_POST['sort']=="QS")
				{
					$sort_val = "sits";
				}
				else if($_POST['sort']=="AS")
				{
					$sort_val = "appointments";
				}
				else if($_POST['sort']=="Assisted Installs")
				{
					$sort_val = "assisted_installs";
				}
				else if($_POST['sort']=="Assisted Installs")
				{
					$sort_val = "self_generated_installs";
				}
			} else {
				$sort_val = "score";
			}
			if(isset($_POST['order'])) {
				$orderby = $_POST['order'];
			} else {
				$orderby = "ASC";
			}
			if(isset($_POST['prevdate'])) {
				$prevdate = $_POST['prevdate'];
			}
			if(isset($_POST['curdate'])) {
				$curdate = $_POST['curdate'];
			}
			$pm = new pwr_model();
			$result = $pm->get_ec();			
			$data=[];
			$order=[];
			foreach ($result as $key => $value) {
				$row['stats'] = get_user_data($value['user_id'],$_POST['period'],$prevdate,$curdate);
				$row['info'] = $value;
				$data[] = $row;
				if($sort_val=="rep") {
					$order[$key] = $row['info']["Name"];
				} else {
					$order[$key] = $row['stats'][$sort_val];
				}
				
			}												
			if($orderby=="ASC")
				asort($order);
			else
				arsort($order);

			foreach ($order as $key => $value) {
				echo "<tr>";
					echo "<td class='viewdetails' param='".$data[$key]['info']["user_id"]."'><img src='".$data[$key]['info']["picture"]."' class='prof-table-img'>".$data[$key]['info']["Name"]."</td>";
					echo "<td>".$data[$key]['stats']["score"]."</td>";
					echo "<td>".$data[$key]['stats']["leads"]."</td>";
					echo "<td>".$data[$key]['stats']["sits"]."</td>";
					echo "<td>".$data[$key]['stats']["appointments"]."</td>";
					echo "<td>".$data[$key]['stats']["closes"]."</td>";
					echo "<td>".$data[$key]['stats']["assisted_installs"]."</td>";
					echo "<td>".$data[$key]['stats']["self_generated_installs"]."</td>";
				echo "</tr>";			
			}						
		} else {
			echo false;
		}		
		exit;
	}

	function get_video_byid() {
		if(isset($_POST['id'])) {
			$pm = new pwr_model();
			$result = $pm->get_video_byid($_POST['id']);			
			echo json_encode($result);	
		} else {
			echo false;
		}		
		exit;
	}

	function update_video_byid() {
		if(isset($_POST["data"])) {
			parse_str($_POST["data"], $postdata);	

			parse_str( parse_url( $postdata['video_link'], PHP_URL_QUERY ), $my_array_of_vars );
			if(count($my_array_of_vars)>0) 
			{
				$vid_id = $my_array_of_vars['v'];
			} else {
				$my_array_of_vars = explode("youtu.be/",$postdata['video_link']);
				$vid_id = $my_array_of_vars[1];
			}	

			$postdata['video_link'] = $vid_id;

			$pm = new pwr_model();
			$pm->update_video_byid($postdata);
			echo true;
		} else {
			echo false;
		}
		exit;
	}

	function get_admin_userlist(){
		if(isset($_POST['sort'])) {			
			$pm = new pwr_model();
			if($_POST['table']=="pwrstation") {
				if(isset($_POST['search'])) {
					$result = $pm->get_pwrstation_accounts($_POST['column'],$_POST['sort'],$_POST['search']);
				} else {
					$result = $pm->get_pwrstation_accounts($_POST['column'],$_POST['sort']);
				}
				

				if(count($result)>0) {
                    $column = 1; foreach ($result as $key => $value) {
                        if( $column == 1) {
                            echo "<tr>";
                            $column=0;
                        } else {
                            echo '<tr class="even-column">';
                            $column=1;
                        }
                        
                            echo '<td><div class="checkbox"><span param="'.$value['Id'].'"></span></div></td>
                            <td><div class="profile-holder"><img src="'.$value['picture'].'" class="prof-table-img"><span class="username">'.$value['Name'].'</span><br><span class="action"><a href="/profile/'.$value['Id'].'?edit=1">Edit</a> | <a href="/profile/'.$value['Id'].'">View</a></span></div>                              
                        </td>                                                                
                        <td class="colorBlue">'.$value['Company_Email__c'].'</td>
                        <td class="greyColor">'.$value['Team__c'].'</td>
                        <td class="greyColor">'.$value['Position__c'].'</td>
                        <td class="greyColor">'.$value['Status__c'].'</td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan=6>No records found.</td></tr>';
                }
			} else if($_POST['table']=="onboarding") {
				if(isset($_POST['search'])) {
					$result = $pm->get_onboarding_accounts($_POST['column'],$_POST['sort'],$_POST['search']);
				} else {
					$result = $pm->get_onboarding_accounts($_POST['column'],$_POST['sort']);
				}
				

				if(count($result)>0) {
                    $column = 1; 

                    foreach ($result as $key => $value) {
                        if( $column == 1) {
                            echo "<tr>";
                            $column=0;
                        } else {
                            echo '<tr class="even-column">';
                            $column=1;
                        }

                    	if($value['Onboarding_Complete_Percent__c']==NULL) { 
                       		$onboarding_percent = "0%";
                       	} else { 
                       		$onboarding_percent = $value['Onboarding_Complete_Percent__c']; 
                       	}
                       	if($value['Onboarding_Complete_Percent__c']==NULL) { 
                        	$onboarding_percent_val = "<span class='text-danger'>0%</span>";
                    	} else { 
                    		$onboarding_percent_val = $value['Onboarding_Complete_Percent__c']."%"; 
                    	}

                    	$status = '<button param="'.$value['Id'].'" class="approve-onb">Approve</button>';
                    	
                        
                       	echo '<td><div class="checkbox"><span param="'.$value['Id'].'"></span></div></td><td><div class="profile-holder"><span class="username">'.$value['Name'].'</span><br><span class="action"><a href="/profile/'.$value['Id'].'?edit=1">Edit</a> | <a href="/profile/'.$value['Id'].'">View</a></span></div></td><td class="colorBlue">'.$value['Company_Email__c'].'</td><td><div class="progress"><div class="progress-bar" style="width:'.$onboarding_percent.'%"></div></div>'.$onboarding_percent_val.'</td><td>'.$status.'</td></tr>';
                    }
				} else {
					echo '<tr><td colspan=5>No records found.</td></tr>';
				}			
			} else {
				echo false;
			}
			exit;
		}
	}

	function get_user_byteam_addgoal() {
		if(isset($_POST['team'])) {
			$pm = new pwr_model();
			if($_POST['team']=="") {
				$teams = $pm->get_team_names();
				$team_val = $teams[0]['name'];
			} else {
				$team_val = $_POST['team'];
			}
			$team = $pm->get_accounts_byteam($team_val);
			$result = "<option value=''>Select User</option>";
			foreach ($team as $key => $value) {
				$result .= "<option value='".$value['user_id']."'>".$value['Name']."</option>";
			}
			echo $result;
		}		
		exit;
	}	

	function get_team_goals_api($team=null,$weeknum=null,$month=null) {
		$pm = new pwr_model();		
		if(isset($_POST['team']))
		{			
			$team = $_POST['team'];
		}
		if(isset($_POST['weeknum'])) {
			$weeknum = $_POST['weeknum'];
		}
		if(isset($_POST['month'])) {
			$month = $_POST['month'];
		}		
		$teams = $pm->get_accounts_byteam($team);
		$user_goals = [];
		$newdata = [];

		$date = new DateTime(date("Y-m-d"));	
		if($weeknum==NULL) {
			$weeknum = $date->format("W");
		}

		if($month==NULL) {
			$week_arr = getStartAndEndDate($weeknum,date("Y"));
		} else {
			$week_arr['week_start'] = date("Y-m-d",strtotime($month." 1 ".date("Y")));	
			$week_arr['week_end'] =  date("Y-m-t",strtotime($month." 1 ".date("Y")));
		}

		// GET GOALS
		foreach ($teams as $key => $value) {			
			$indv_goal = $pm->get_goals_userid_period($value['user_id'],$weeknum,$month);			
			if($indv_goal!=null) {
				$user_info['name'] = $value['Name'];
				$user_info['user_id'] = $value['user_id'];
				$user_info['goals'] = $indv_goal;
				$user_goals[$value['Position__c']][]=$user_info;
			} else {
				$arr = array("id"=>0,
							"leads"=>0,
							"sits"=>0,
							"appointments"=>0,
							"closes"=>0,
							"installs"=>0);
				$user_info['name'] = $value['Name'];
				$user_info['user_id'] = $value['user_id'];
				$user_info['goals'] = $arr;
				$user_goals[$value['Position__c']][]=$user_info;
			}
		}		

		if(count($user_goals)>0) {
			// GET ACTUAL
			foreach ($user_goals as $role => $team) {			
				foreach ($team as $key => $value) {												
					$result = get_user_data($value['user_id'],"Custom",date("Y-m-d",strtotime($week_arr['week_start'])),date("Y-m-d",strtotime($week_arr['week_end'])));	

					$userdata['user']['name']=$value['name'];
					$userdata['user']['user_id']=$value['user_id'];
					$userdata['actual'] = $result;
					$userdata['goals'] = $value['goals'];
					$newdata[$role][] = $userdata;				
				}
			}						
			
			$team_table = $fm_table = $jec_table = $sec_table = "";
			$total_actual_leads = $total_actual_sits = $total_actual_appointments = $total_actual_closes = $total_actual_installs = $total_actual_fmunit = 0;
			$total_goal_leads = $total_goal_sits = $total_goal_appointments = $total_goal_closes = $total_goal_installs = $total_goal_fmunit = 0;

			//Field Marketer Section		
			if(isset($newdata['Field Marketer']) || isset($newdata['Field Marketer Elite'])) {
				$count=0;
				if(isset($newdata['Field Marketer'])) {
					foreach ($newdata['Field Marketer'] as $key => $value) {	
						$total_goal_leads += $value['goals']['leads'];
						$total_actual_leads += $value['actual']['leads'];
						$total_goal_sits += $value['goals']['sits'];
						$total_actual_sits += $value['actual']['sits'];
						$total_goal_appointments += $value['goals']['appointments'];
						$total_actual_appointments += $value['actual']['appointments'];
						$total_goal_closes += $value['goals']['closes'];
						$total_actual_closes += $value['actual']['closes'];
						$total_goal_installs += $value['goals']['installs'];
						$total_actual_installs += $value['actual']['installs'];

						if($count==0) {
							$fm_table .= '<tr class="striped">';	                		
			                $count++;
						} else {
							$fm_table .= '<tr>';
							$count=0;
						}	

						$leadtosit = getPercentage($value['actual']['sits'],$value['actual']['appointments']) - getPercentage($value['goals']['sits'],$value['goals']['appointments']);

						$fm_table .= '<td>'.$value["user"]["name"].'</td>
		            		<td class="crit">
		            			<div>Goal</div>
		            			<div class="layer-actual">Actual</div>
		            			<div>%</div>
		            		</td>
		            		<td>
		            			<div>'.$value['goals']['leads'].'</div>
		            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
		            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
		            		</td>   
		            		<td>
		            			<div>'.$value['goals']['sits'].'</div>
		            			<div class="layer-actual">'.$value['actual']['sits'].'</div>
		            			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
		            		</td>
		            		<td>
		            			<div>'.$value['goals']['appointments'].'</div>
		            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
		            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
		            		</td>
		            		<td>
		            			<div>'.getPercentage($value['goals']['sits'],$value['goals']['appointments']).'%</div>
		            			<div class="layer-actual">'.getPercentage($value['actual']['sits'],$value['actual']['appointments']).'%</div>';

		            			if($leadtosit>0) {
			        				$fm_table .= '<div class="layer-goal text-success">'.$leadtosit.'%</div>';
			        			} else if($leadtosit==0) {
			        				$fm_table .= '<div class="layer-goal text-danger">'.$leadtosit.'%</div>';
			        			} else {
			        				$leadtosit *= -1;
			        				$fm_table .= '<div class="layer-goal text-danger">'.$leadtosit.'%</div>';
			        			}	            			
		            		$fm_table .= '</td>                      		
		            	</tr>';
					}
				}
				if(isset($newdata['Field Marketer Elite'])) {
					foreach ($newdata['Field Marketer Elite'] as $key => $value) {	
						$total_goal_leads += $value['goals']['leads'];
						$total_actual_leads += $value['actual']['leads'];
						$total_goal_sits += $value['goals']['sits'];
						$total_actual_sits += $value['actual']['sits'];
						$total_goal_appointments += $value['goals']['appointments'];
						$total_actual_appointments += $value['actual']['appointments'];
						$total_goal_closes += $value['goals']['closes'];
						$total_actual_closes += $value['actual']['closes'];
						$total_goal_installs += $value['goals']['installs'];
						$total_actual_installs += $value['actual']['installs'];

						if($count==0) {
							$fm_table .= '<tr class="striped">';	                		
			                $count++;
						} else {
							$fm_table .= '<tr>';
							$count=0;
						}	

						$leadtosit = getPercentage($value['actual']['sits'],$value['actual']['appointments']) - getPercentage($value['goals']['sits'],$value['goals']['appointments']);

						$fm_table .= '<td>'.$value["user"]["name"].'</td>
		            		<td class="crit">
		            			<div>Goal</div>
		            			<div class="layer-actual">Actual</div>
		            			<div>%</div>
		            		</td>		            		
		            		<td>
		            			<div>'.$value['goals']['leads'].'</div>
		            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
		            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
		            		</td>
		            		<td>
		            			<div>'.$value['goals']['sits'].'</div>
		            			<div class="layer-actual">'.$value['actual']['sits'].'</div>
		            			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
		            		</td>      
		            		<td>
		            			<div>'.$value['goals']['appointments'].'</div>
		            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
		            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
		            		</td>      		
		            		<td>
		            			<div>'.getPercentage($value['goals']['sits'],$value['goals']['appointments']).'%</div>
		            			<div class="layer-actual">'.getPercentage($value['actual']['sits'],$value['actual']['appointments']).'%</div>';

		            			if($leadtosit>0) {
			        				$fm_table .= '<div class="layer-goal text-success">'.$leadtosit.'%</div>';
			        			} else if($leadtosit==0) {
			        				$fm_table .= '<div class="layer-goal text-danger">'.$leadtosit.'%</div>';
			        			} else {
			        				$leadtosit *= -1;
			        				$fm_table .= '<div class="layer-goal text-danger">'.$leadtosit.'%</div>';
			        			}	            			
		            		$fm_table .= '</td>                      		
		            	</tr>';
					}
				}
			}

			//Jr Energy Consultant Section		
			if(isset($newdata['Jr Energy Consultant'])) {
				$count=0;
				foreach ($newdata['Jr Energy Consultant'] as $key => $value) {
					$total_goal_leads += $value['goals']['leads'];
					$total_actual_leads += $value['actual']['leads'];
					$total_goal_sits += $value['goals']['sits'];
					$total_actual_sits += $value['actual']['sits'];
					$total_goal_appointments += $value['goals']['appointments'];
					$total_actual_appointments += $value['actual']['appointments'];
					$total_goal_closes += $value['goals']['closes'];
					$total_actual_closes += $value['actual']['closes'];
					$total_goal_installs += $value['goals']['installs'];
					$total_actual_installs += $value['actual']['installs'];				
					if($count==0) {
						$jec_table .= '<tr class="striped">';	                		
		                $count++;
					} else {
						$jec_table .= '<tr>';
						$count=0;
					}	

					$leadtosit = getPercentage($value['actual']['sits'],$value['actual']['appointments']) - getPercentage($value['goals']['sits'],$value['goals']['appointments']);

					$closetoinstall = getPercentage($value['actual']['installs'],$value['actual']['closes']) - getPercentage($value['goals']['installs'],$value['goals']['closes']);

					$jec_table .= '<td>'.$value['user']["name"].'</td>
	            		<td class="crit">
	            			<div>Goal</div>
	            			<div class="layer-actual">Actual</div>
	            			<div>%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['leads'].'</div>
	            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
	            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
	            		</td>
	            		<td>
	             			<div>'.$value['goals']['sits'].'</div>
	             			<div class="layer-actual">'.$value['actual']['sits'].'</div>
	             			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
	             		</td>
	             		<td>
	            			<div>'.$value['goals']['appointments'].'</div>
	            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
	            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
	            		</td>  
	            		<td>
	            			<div>'.$value['goals']['closes'].'</div>
	            			<div class="layer-actual">'.$value['actual']['closes'].'</div>
	            			<div>'.getPercentage($value['actual']['closes'],$value['goals']['closes']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['installs'].'</div>
	            			<div class="layer-actual">'.$value['actual']['installs'].'</div>
	            			<div>'.getPercentage($value['actual']['installs'],$value['goals']['installs']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.getPercentage($value['goals']['sits'],$value['goals']['appointments']).'%</div>
	            			<div class="layer-actual">'.getPercentage($value['actual']['sits'],$value['actual']['appointments']).'%</div>';

	            			if($leadtosit>0) {
		        				$jec_table .= '<div class="layer-goal text-success">'.$leadtosit.'%</div>';
		        			} else if($leadtosit==0) {
		        				$jec_table .= '<div class="layer-goal text-danger">'.$leadtosit.'%</div>';
		        			} else {
		        				$leadtosit *= -1;
		        				$jec_table .= '<div class="layer-goal text-danger">'.$leadtosit.'%</div>';
		        			}	            			
	            		$jec_table .= '</td>   
	            		<td>
	            			<div>'.getPercentage($value['goals']['installs'],$value['goals']['closes']).'%</div>
	            			<div class="layer-actual">'.getPercentage($value['actual']['installs'],$value['actual']['closes']).'%</div>';

	            			if($closetoinstall>0) {
		        				$jec_table .= '<div class="layer-goal text-success">'.$closetoinstall.'%</div>';
		        			} else if($closetoinstall==0) {
		        				$jec_table .= '<div class="layer-goal text-danger">'.$closetoinstall.'%</div>';
		        			} else {
		        				$closetoinstall *= -1;
		        				$jec_table .= '<div class="layer-goal text-danger">'.$closetoinstall.'%</div>';
		        			}	            			
	            		$jec_table .= '</td>                    		
	            	</tr>';
				}
			}

			//Sr Energy Consultant Section		
			if(isset($newdata['Sr Energy Consultant'])) {
				$count=0;
				foreach ($newdata['Sr Energy Consultant'] as $key => $value) {	
					$total_goal_leads += $value['goals']['leads'];
					$total_actual_leads += $value['actual']['leads'];
					$total_goal_sits += $value['goals']['sits'];
					$total_actual_sits += $value['actual']['sits'];
					$total_goal_appointments += $value['goals']['appointments'];
					$total_actual_appointments += $value['actual']['appointments'];
					$total_goal_closes += $value['goals']['closes'];
					$total_actual_closes += $value['actual']['closes'];
					$total_goal_installs += $value['goals']['installs'];
					$total_actual_installs += $value['actual']['installs'];	
					if($count==0) {
						$sec_table .= '<tr class="striped">';	                		
		                $count++;
					} else {
						$sec_table .= '<tr>';
						$count=0;
					}	

					$sitstoclose = getPercentage($value['actual']['closes'],$value['actual']['sits']) - getPercentage($value['goals']['closes'],$value['goals']['sits']);

					$sec_table .= '<td>'.$value['user']["name"].'</td>
	            		<td class="crit">
	            			<div>Goal</div>
	            			<div class="layer-actual">Actual</div>
	            			<div>%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['leads'].'</div>
	            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
	            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
	            		</td>
	            		<td>
	             			<div>'.$value['goals']['sits'].'</div>
	             			<div class="layer-actual">'.$value['actual']['sits'].'</div>
	             			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
	             		</td>
	             		<td>
	            			<div>'.$value['goals']['appointments'].'</div>
	            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
	            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
	            		</td> 
	            		<td>
	            			<div>'.$value['goals']['closes'].'</div>
	            			<div class="layer-actual">'.$value['actual']['closes'].'</div>
	            			<div>'.getPercentage($value['actual']['closes'],$value['goals']['closes']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['installs'].'</div>
	            			<div class="layer-actual">'.$value['actual']['installs'].'</div>
	            			<div>'.getPercentage($value['actual']['installs'],$value['goals']['installs']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.getPercentage($value['goals']['closes'],$value['goals']['sits']).'%</div>
	            			<div class="layer-actual">'.getPercentage($value['actual']['closes'],$value['actual']['sits']).'%</div>';

	            			if($sitstoclose>0) {
		        				$sec_table .= '<div class="layer-goal text-success">'.$sitstoclose.'%</div>';
		        			} else if($sitstoclose==0) {
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			} else {
		        				$sitstoclose *= -1;
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			}	            			
	            		$sec_table .= '</td></tr>';
				}
			}

			//Manager Section		
			if(isset($newdata['Manager'])) {
				$count=0;
				foreach ($newdata['Manager'] as $key => $value) {	
					$total_goal_leads += $value['goals']['leads'];
					$total_actual_leads += $value['actual']['leads'];
					$total_goal_sits += $value['goals']['sits'];
					$total_actual_sits += $value['actual']['sits'];
					$total_goal_appointments += $value['goals']['appointments'];
					$total_actual_appointments += $value['actual']['appointments'];
					$total_goal_closes += $value['goals']['closes'];
					$total_actual_closes += $value['actual']['closes'];
					$total_goal_installs += $value['goals']['installs'];
					$total_actual_installs += $value['actual']['installs'];	
					if($count==0) {
						$sec_table .= '<tr class="striped">';	                		
		                $count++;
					} else {
						$sec_table .= '<tr>';
						$count=0;
					}	

					$sitstoclose = getPercentage($value['actual']['closes'],$value['actual']['sits']) - getPercentage($value['goals']['closes'],$value['goals']['sits']);

					$sec_table .= '<td>'.$value['user']["name"].'</td>
	            		<td class="crit">
	            			<div>Goal</div>
	            			<div class="layer-actual">Actual</div>
	            			<div>%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['leads'].'</div>
	            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
	            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
	            		</td>
	            		<td>
	             			<div>'.$value['goals']['sits'].'</div>
	             			<div class="layer-actual">'.$value['actual']['sits'].'</div>
	             			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
	             		</td>
	             		<td>
	            			<div>'.$value['goals']['appointments'].'</div>
	            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
	            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
	            		</td> 
	            		<td>
	            			<div>'.$value['goals']['closes'].'</div>
	            			<div class="layer-actual">'.$value['actual']['closes'].'</div>
	            			<div>'.getPercentage($value['actual']['closes'],$value['goals']['closes']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['installs'].'</div>
	            			<div class="layer-actual">'.$value['actual']['installs'].'</div>
	            			<div>'.getPercentage($value['actual']['installs'],$value['goals']['installs']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.getPercentage($value['goals']['closes'],$value['goals']['sits']).'%</div>
	            			<div class="layer-actual">'.getPercentage($value['actual']['closes'],$value['actual']['sits']).'%</div>';

	            			if($sitstoclose>0) {
		        				$sec_table .= '<div class="layer-goal text-success">'.$sitstoclose.'%</div>';
		        			} else if($sitstoclose==0) {
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			} else {
		        				$sitstoclose *= -1;
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			}	            			
	            		$sec_table .= '</td></tr>';
				}
			}

			//VP Section		
			if(isset($newdata['VP'])) {
				$count=0;
				foreach ($newdata['VP'] as $key => $value) {	
					$total_goal_leads += $value['goals']['leads'];
					$total_actual_leads += $value['actual']['leads'];
					$total_goal_sits += $value['goals']['sits'];
					$total_actual_sits += $value['actual']['sits'];
					$total_goal_appointments += $value['goals']['appointments'];
					$total_actual_appointments += $value['actual']['appointments'];
					$total_goal_closes += $value['goals']['closes'];
					$total_actual_closes += $value['actual']['closes'];
					$total_goal_installs += $value['goals']['installs'];
					$total_actual_installs += $value['actual']['installs'];	
					if($count==0) {
						$sec_table .= '<tr class="striped">';	                		
		                $count++;
					} else {
						$sec_table .= '<tr>';
						$count=0;
					}	

					$sitstoclose = getPercentage($value['actual']['closes'],$value['actual']['sits']) - getPercentage($value['goals']['closes'],$value['goals']['sits']);

					$sec_table .= '<td>'.$value['user']["name"].'</td>
	            		<td class="crit">
	            			<div>Goal</div>
	            			<div class="layer-actual">Actual</div>
	            			<div>%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['leads'].'</div>
	            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
	            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
	            		</td>
	            		<td>
	             			<div>'.$value['goals']['sits'].'</div>
	             			<div class="layer-actual">'.$value['actual']['sits'].'</div>
	             			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
	             		</td>
	             		<td>
	            			<div>'.$value['goals']['appointments'].'</div>
	            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
	            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
	            		</td> 
	            		<td>
	            			<div>'.$value['goals']['closes'].'</div>
	            			<div class="layer-actual">'.$value['actual']['closes'].'</div>
	            			<div>'.getPercentage($value['actual']['closes'],$value['goals']['closes']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['installs'].'</div>
	            			<div class="layer-actual">'.$value['actual']['installs'].'</div>
	            			<div>'.getPercentage($value['actual']['installs'],$value['goals']['installs']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.getPercentage($value['goals']['closes'],$value['goals']['sits']).'%</div>
	            			<div class="layer-actual">'.getPercentage($value['actual']['closes'],$value['actual']['sits']).'%</div>';

	            			if($sitstoclose>0) {
		        				$sec_table .= '<div class="layer-goal text-success">'.$sitstoclose.'%</div>';
		        			} else if($sitstoclose==0) {
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			} else {
		        				$sitstoclose *= -1;
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			}	            			
	            		$sec_table .= '</td></tr>';
				}
			}

			//CEO Section		
			if(isset($newdata['CEO'])) {
				$count=0;
				foreach ($newdata['CEO'] as $key => $value) {	
					$total_goal_leads += $value['goals']['leads'];
					$total_actual_leads += $value['actual']['leads'];
					$total_goal_sits += $value['goals']['sits'];
					$total_actual_sits += $value['actual']['sits'];
					$total_goal_appointments += $value['goals']['appointments'];
					$total_actual_appointments += $value['actual']['appointments'];
					$total_goal_closes += $value['goals']['closes'];
					$total_actual_closes += $value['actual']['closes'];
					$total_goal_installs += $value['goals']['installs'];
					$total_actual_installs += $value['actual']['installs'];	
					if($count==0) {
						$sec_table .= '<tr class="striped">';	                		
		                $count++;
					} else {
						$sec_table .= '<tr>';
						$count=0;
					}	

					$sitstoclose = getPercentage($value['actual']['closes'],$value['actual']['sits']) - getPercentage($value['goals']['closes'],$value['goals']['sits']);

					$sec_table .= '<td>'.$value['user']["name"].'</td>
	            		<td class="crit">
	            			<div>Goal</div>
	            			<div class="layer-actual">Actual</div>
	            			<div>%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['leads'].'</div>
	            			<div class="layer-actual">'.$value['actual']['leads'].'</div>
	            			<div>'.getPercentage($value['actual']['leads'],$value['goals']['leads']).'%</div>
	            		</td>
	            		<td>
	             			<div>'.$value['goals']['sits'].'</div>
	             			<div class="layer-actual">'.$value['actual']['sits'].'</div>
	             			<div>'.getPercentage($value['actual']['sits'],$value['goals']['sits']).'%</div>
	             		</td>
	             		<td>
	            			<div>'.$value['goals']['appointments'].'</div>
	            			<div class="layer-actual">'.$value['actual']['appointments'].'</div>
	            			<div>'.getPercentage($value['actual']['appointments'],$value['goals']['appointments']).'%</div>
	            		</td> 
	            		<td>
	            			<div>'.$value['goals']['closes'].'</div>
	            			<div class="layer-actual">'.$value['actual']['closes'].'</div>
	            			<div>'.getPercentage($value['actual']['closes'],$value['goals']['closes']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.$value['goals']['installs'].'</div>
	            			<div class="layer-actual">'.$value['actual']['installs'].'</div>
	            			<div>'.getPercentage($value['actual']['installs'],$value['goals']['installs']).'%</div>
	            		</td>
	            		<td>
	            			<div>'.getPercentage($value['goals']['closes'],$value['goals']['sits']).'%</div>
	            			<div class="layer-actual">'.getPercentage($value['actual']['closes'],$value['actual']['sits']).'%</div>';

	            			if($sitstoclose>0) {
		        				$sec_table .= '<div class="layer-goal text-success">'.$sitstoclose.'%</div>';
		        			} else if($sitstoclose==0) {
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			} else {
		        				$sitstoclose *= -1;
		        				$sec_table .= '<div class="layer-goal text-danger">'.$sitstoclose.'%</div>';
		        			}	            			
	            		$sec_table .= '</td></tr>';
				}
			}

			//TEAM TABLE
			$totalleadtosit = getPercentage($total_actual_sits,$total_actual_appointments) - getPercentage($total_goal_sits,$total_goal_appointments);
			$totalclosetoinstall = getPercentage($total_actual_installs,$total_actual_closes) - getPercentage($total_goal_installs,$total_goal_closes);
			$team_table = '<tr class="striped">	                        		
	                        	<td class="crit">
	    			<div>Goal</div>
	    			<div class="layer-actual">Actual</div>
	    			<div>%</div>
	    		</td>
	    		<td>
	    			<div>'.$total_goal_leads.'</div>
	    			<div class="layer-actual">'.$total_actual_leads.'</div>
	    			<div>'.getPercentage($total_actual_leads,$total_goal_leads).'%</div>
	    		</td>
	    		<td>
	    			<div>'.$total_goal_sits.'</div>
	    			<div class="layer-actual">'.$total_actual_sits.'</div>
	    			<div>'.getPercentage($total_actual_sits,$total_goal_sits).'%</div>
	    		</td>
	    		<td>
	    			<div>'.$total_goal_appointments.'</div>
	    			<div class="layer-actual">'.$total_actual_appointments.'</div>
	    			<div>'.getPercentage($total_actual_appointments,$total_goal_appointments).'%</div>
	    		</td>
	    		<td>
	    			<div>'.$total_goal_closes.'</div>
	    			<div class="layer-actual">'.$total_actual_closes.'</div>
	    			<div>'.getPercentage($total_actual_closes,$total_goal_closes).'%</div>
	    		</td>
	    		<td>
	    			<div>'.$total_goal_installs.'</div>
	    			<div class="layer-actual">'.$total_actual_installs.'</div>
	    			<div>'.getPercentage($total_actual_installs,$total_goal_installs).'%</div>
	    		</td>
	    		<td>
        			<div>'.getPercentage($total_goal_sits,$total_goal_appointments).'%</div>
        			<div class="layer-actual">'.getPercentage($total_actual_sits,$total_actual_appointments).'%</div>';

        			if($totalleadtosit>0) {
        				$team_table .= '<div class="layer-goal text-success">'.$totalleadtosit.'%</div>';
        			} else if($totalleadtosit==0) {
        				$team_table .= '<div class="layer-goal text-danger">'.$totalleadtosit.'%</div>';
        			} else {
        				$totalleadtosit *= -1;
        				$team_table .= '<div class="layer-goal text-danger">'.$totalleadtosit.'%</div>';
        			}	            			
        		$team_table .= '</td>   
        		<td>
        			<div>'.getPercentage($total_goal_installs,$total_goal_closes).'%</div>
        			<div class="layer-actual">'.getPercentage($total_actual_closes,$total_actual_installs).'%</div>';

        			if($totalclosetoinstall>0) {
        				$team_table .= '<div class="layer-goal text-success">'.$totalclosetoinstall.'%</div>';
        			} else if($totalclosetoinstall==0) {
        				$team_table .= '<div class="layer-goal text-danger">'.$totalclosetoinstall.'%</div>';
        			} else {
        				$totalclosetoinstall *= -1;
        				$team_table .= '<div class="layer-goal text-danger">'.$totalclosetoinstall.'%</div>';
        			}	            			
        		$team_table .= '</td></tr>';

			$combined_tables['success'] = true;
			$combined_tables['team_table']=$team_table;
	        $combined_tables['fm_table']=$fm_table;
	        $combined_tables['jec_table']=$jec_table;
	        $combined_tables['sec_table']=$sec_table;

	        echo json_encode($combined_tables);
		} else {
			$combined_tables['success'] = false;
			$combined_tables['message'] = "No goals currently found.";
			echo json_encode($combined_tables);
		}		
		
		exit;		
	}	

	function lb_team() {
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];
		} else {
			$account_id = horizon_login::check_cookie();			
		}

		if(isset($_POST['period'])) {
			if(isset($_POST['sort'])) {				
				if($_POST['sort']=="AS") {
					$sort_val = "appointments";
				} else {
					$sort_val = strtolower($_POST['sort']);
				}
			}  else {
				$sort_val = "score";
			}


			if(isset($_POST['order'])) {
				$order = $_POST['order'];
			} else {
				$order = "ASC";
			}
			$curdate=$prevdate=false;
			if(isset($_POST['prevdate'])) {
				$prevdate = $_POST['prevdate'];
			}
			if(isset($_POST['curdate'])) {
				$curdate = $_POST['curdate'];
			}

			$pm = new pwr_model();
			$teams = $pm->get_team_names();				
			$data=[];
			$newdata=[];
			$test = "";
			foreach ($teams as $key => $value) {
				$result = $pm->get_accounts_byteam($value['name']);
				$data[$value['name']]["score"] = 0;
				$data[$value['name']]["leads"] = 0;
				$data[$value['name']]["qs"] = 0;
				$data[$value['name']]["appointments"] = 0;
				$data[$value['name']]["close"] = 0;
				$data[$value['name']]["installs"] = 0;

				foreach ($result as $key2 => $value2) {
					$user_data = get_user_data($value2['user_id'],$_POST['period'],$prevdate,$curdate);
					$account = $pm->get_account($value2['user_id']);					
					if($account['Position__c']=="Field Marketer" || $account['Position__c']=="Field Marketer Elite") {						
						$data[$value['name']]["leads"] += $user_data['leads'];
						$data[$value['name']]["qs"] += $user_data['sits'];
						$data[$value['name']]["appointments"] += $user_data['appointments'];
						$data[$value['name']]["close"] += $user_data['closes'];
						$data[$value['name']]["installs"] += 0;
						$score = $user_data['leads'] + ($user_data['sits'] * 2) + ($user_data['assisted_close']*3) + ($user_data['assisted_installs_fm']*4);
						$data[$value['name']]["score"] += $score;
					} else {						
						$data[$value['name']]["leads"] += $user_data['leads'];
						$data[$value['name']]["qs"] += $user_data['sits'];
						$data[$value['name']]["appointments"] += $user_data['appointments'];
						$data[$value['name']]["close"] += $user_data['closes'];
						$data[$value['name']]["installs"] += ($user_data['assisted_installs']+$user_data['self_generated_installs']);
						$score = $user_data['leads'] + ($user_data['sits'] * .25) + ($user_data['closes']*3) + ($user_data['assisted_installs']*4) + ($user_data['self_generated_installs']*6);
						$data[$value['name']]["score"] += $score;
					}

					
				}			
			}		

			foreach ($data as $key => $value) {				
				if($sort_val=="team") {
					$sorting[$key] = $key;
				} else {
					$sorting[$key] = $value[$sort_val];
				}
				
			}				

			if($order=="ASC")
				asort($sorting);
			else
				arsort($sorting);			

			foreach ($sorting as $key => $value) {
				$newdata[$key] = $data[$key];
			}

			$tscore = $tleads = $tqs = $tclose = $tinstall = 0;
			foreach ($newdata as $key => $value) {
				$tscore += $value['score'];
				$tleads += $value['leads'];
				$tqs += $value['qs'];
				$tclose += $value['close'];
				$tinstall += $value['installs'];
			}
			
			$company_leads = $pm->get_company_leads($_POST['period'],$prevdate,$curdate);
			$company_sits = $pm->get_company_sits($_POST['period'],$prevdate,$curdate);
			$company_appointments = $pm->get_company_appointments($_POST['period'],$prevdate,$curdate);
			$company_closes = $pm->get_company_closes($_POST['period'],$prevdate,$curdate);
			$company_installs = $pm->get_company_installs($_POST['period'],$prevdate,$curdate);

			//GET TEAM SITS
			$appt = $pm->get_team_sits($_POST['period'],$prevdate,$curdate);
			$teams = $pm->get_team_names();
			$team_sits = array();
			foreach ($teams as $key => $value) {
				$team_sits[$value["name"]] = 0;
			}
			foreach ($appt as $key => $value) {
				$ec = $pm->get_account($value['account_id']);			
				$fm = $pm->get_account($value['Field_Marketer__c']);

				if($ec['Team__c'] == $fm['Team__c']) {
					if(isset($team_sits[$ec['Team__c']])) {
						$team_sits[$ec['Team__c']]++;	
					}					
				} else {
					if(isset($team_sits[$ec['Team__c']])) {
						$team_sits[$ec['Team__c']]++;						
					}
					if(isset($team_sits[$fm['Team__c']])) {
						$team_sits[$fm['Team__c']]++;
					}					
				}
			}

			//GET TEAM APPOINTMENTS 
			$appt = $pm->get_team_appointments($_POST['period'],$prevdate,$curdate);
			$teams = $pm->get_team_names();
			$team_appointments = array();
			foreach ($teams as $key => $value) {
				$team_appointments[$value["name"]] = 0;
			}
			foreach ($appt as $key => $value) {
				$ec = $pm->get_account($value['account_id']);			
				$fm = $pm->get_account($value['Field_Marketer__c']);

				if($ec['Team__c'] == $fm['Team__c']) {
					if(isset($team_appointments[$ec['Team__c']])) {
						$team_appointments[$ec['Team__c']]++;	
					}					
				} else {
					if(isset($team_appointments[$ec['Team__c']])) {
						$team_appointments[$ec['Team__c']]++;						
					}

					if(isset($team_appointments[$fm['Team__c']])) {
						$team_appointments[$fm['Team__c']]++;
					}					
				}
			}

			echo "<tr class='grayBg'>";
				echo "<td>Company</td>";
				echo "<td>&nbsp;</td>";
				echo "<td>".$company_leads."</td>";
				echo "<td>".$company_sits."</td>";
				echo "<td>".$company_appointments."</td>";
				echo "<td>".$company_closes."</td>";
				echo "<td>".$company_installs."</td>";
			echo "</tr>";
			
			foreach ($newdata as $key => $value) {				
				echo "<tr>";
					echo "<td>".$key."</td>";
					echo "<td>".$value['score']."</td>";
					echo "<td>".$value['leads']."</td>";
					echo "<td>".$team_sits[$key]."</td>";
					echo "<td>".$team_appointments[$key]."</td>";
					echo "<td>".$value['close']."</td>";
					echo "<td>".$value['installs']."</td>";
				echo "</tr>";
			}
		}

		exit;
	}

	function check_goal() {
		if(isset($_POST['user_id'])) {
			$pm = new pwr_model();
			$acc = $pm->get_account($_POST['user_id']);
			if($_POST['type']=="week") {
				$result = $pm->get_goals_userid_period($_POST['user_id'],$_POST['week'],NULL);
			} else {
				$result = $pm->get_goals_userid_period($_POST['user_id'],NULL,$_POST['month']);
			}
			$result['role'] = $acc['Position__c'];
			echo json_encode($result);
		}
		exit;
	}

	function getStartAndEndDate($week, $year) {
	  $dto = new DateTime();
	  $dto->setISODate($year, $week);
	  $ret['week_start'] = $dto->format('m/d/Y');
	  $dto->modify('+6 days');
	  $ret['week_end'] = $dto->format('m/d/Y');
	  return $ret;
	}

	function getPercentage($val1,$val2) {
		if($val2>0) {
			$number = ($val1 / $val2)*100;	
			return number_format($number, 1, '.', ',');	
		} else {
			return 0;
		}
		
	}	

	function get_weekly_record($id=null,$type=null) {
		$pm = new pwr_model();
		if ($id!=null) {			
			$date = new DateTime(date("Y-m-d"));	
			$weekdiff = datediffInWeeks('1/1/2018', date("m/d/Y"));	
			$prevdate = $date1 = "2018-01-01";
			$x=0;
			$leads_holder = [];
			while($x!=$weekdiff+1) {
				$date = strtotime($date1);
				$prevdate = date("Y-m-d",$date);
				$date = strtotime("+6 day", $date);
				$curdate = date("Y-m-d",$date);						
				$date1 = date("Y-m-d",strtotime("+1 day", $date));
				$x++;
				if($type=="leads") {
					$res = $pm->get_lead($id,"Custom",$prevdate,$curdate);	
				} else {
					$res = $pm->get_sits($id,"Custom",$prevdate,$curdate);
				}
				
				$res_holder[$res] = $res;
			}
			
			krsort($res_holder,SORT_REGULAR);


			foreach ($res_holder as $key => $value) {
				return $key;
			}
		}
		exit;
	}

	function get_monthly_record($id=null,$type=null) {
		$pm = new pwr_model();
		if($id!=null) {			
			$date1 = strtotime("2018-01-01");
			$date2 = strtotime(date("Y-m-d"));
			$monthdiff = round(($date2-$date1) / 60 / 60 / 24 / 30);

			$prevdate = $date1 = "2018-01-01";
			$x=0;
			$leads_holder = [];
			while($x!=$monthdiff) {
				$date = strtotime($date1);
				$prevdate = date("Y-m-d",$date);
				$t = date("t",$date)-1;			
				$date = strtotime("+".$t." days", $date);
				$curdate = date("Y-m-d",$date);						
				$date1 = date("Y-m-d",strtotime("+1 day", $date));
				$x++;			
				if($type=="leads") {
					$res = $pm->get_lead($id,"Custom",$prevdate,$curdate);	
				} else {
					$res = $pm->get_sits($id,"Custom",$prevdate,$curdate);
				}				
				$res_holder[$res] = $res;
			}
			
			krsort($res_holder,SORT_REGULAR);


			foreach ($res_holder as $key => $value) {
				return $key;				
			}
		}
	
		exit;
	}

	function datediffInWeeks($date1, $date2)
	{
	    if($date1 > $date2) return datediffInWeeks($date2, $date1);
	    $first = DateTime::createFromFormat('m/d/Y', $date1);
	    $second = DateTime::createFromFormat('m/d/Y', $date2);
	    return floor($first->diff($second)->days/7);
	}

	function test() {
		$pm = new pwr_model();
		$appt = $pm->get_team_appointments("Today");
		$teams = $pm->get_team_names();
		$team = array();
		foreach ($teams as $key => $value) {
			$team[$value["name"]] = 0;
		}
		foreach ($appt as $key => $value) {
			$ec = $pm->get_account($value['account_id']);			
			$fm = $pm->get_account($value['Field_Marketer__c']);

			if($ec['Team__c'] == $fm['Team__c']) {
				$team[$ec['Team__c']]++;
			} else {
				$team[$ec['Team__c']]++;
				$team[$fm['Team__c']]++;
			}
		}
		var_dump($team);
		exit;
	}
	
?>