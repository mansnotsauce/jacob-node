<?php
	function test() {			
		$sf = new horizon_salesforce();
		if(isset($_POST['query']))
		{
			$sf = new horizon_salesforce();
			if($_POST['type']=="query") {
				$result = $sf->query($_POST['query']);
			} else {
				$result = $sf->services($_POST['query']);
			}
			
			var_dump($result);
		}
		echo "<br><br>";
		echo "<form action='' method='post'>
			<select name='type'><option value='query'>query</option><option value='services'>services</option></select>
			<input type='text' name='query' style='width:500px;' value='' placeholder=''/>
			<input type='submit' name='submit' value='submit'>
			</form>";
		exit;
	}

	function index() {			
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];
		} else {
			$account_id = horizon_login::check_cookie();			
		}

		if($account_id==false) {
			header("Location: /login");
		} else {
			header("Location: /dashboard");
		}
	}

	function dashboard() {				
		if(isset($_SESSION['user_id']))	{			
			$account_id = $_SESSION['user_id'];			
		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}								
		$pm = new pwr_model();							

		if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin") {			
			$data['pwrstation_acc']=$pm->get_pwrstation_accounts("Name","ASC");			 			
			$data['onboarding_acc']=$pm->get_onboarding_accounts("Name","ASC");			
			$data['path'] = "home/admin-dashboard";
		} else {	
			$data['sf_data'] = ["leads"=>0,"commission"=>0,"closes"=>0,"sits"=>0,"closes"=>0,"sitstoclose"=>0,"installs"=>0,"closetoinstall"=>0,"assisted_installs"=>0,"self_generated_installs"=>0,"score"=>0];					
			$data['path'] = "home/user-dashboard";
		}
		$data['menu-link'] = "Dashboard";		
		return $data;	
	}

	function leaderboard() {
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];

		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}

		$data['path'] = "home/lb-team";
		$data['menu-link'] = "Leaderboard";
		return $data;	
	}

	function pwr_goals() {
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];

		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}
		$pm = new pwr_model();		
		$data['teams'] = $pm->get_team_names();
		$data['path'] = "home/pwr-goals";
		$data['menu-link'] = "PWR Goals";
		return $data;
	}

	function pwr_line() {
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];

		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}
		$pm = new pwr_model();
		$data['videos']	= $pm->get_video("pwrline");
		$data['path'] = "home/pwrline";
		$data['menu-link'] = "PWR Line";
		return $data;
	}

	function training() {
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];

		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}
		$pm = new pwr_model();
		$data['videos']	= $pm->get_video("training");
		$data['path'] = "home/training";
		$data['menu-link'] = "Training";
		return $data;
	}

	function login() {
		$account_id = horizon_login::check_cookie();		
		if($account_id!=false) {
			header("Location: /");
		}
		if(isset($_COOKIE['hsession'])) {
		    if(isset($_COOKIE['remember_email']));
		    {       
		        if (horizon_login::continue_login()) {
		            header("location: /");          
		        }
		    }
		}
		$data['path'] = "signup/login";
		$data['menu-link'] = "Log In";
		return $data;
	}	

	function logout() {
		horizon_login::logout();
		session_destroy();
		header("location: /");
	}

	function unavailable() {
		$data['path'] = "includes/404";
		$data['menu-link'] = "Service Temporarily Unvailable";
		return $data;
	}	

	function terms_of_use() {
		$data['path'] = "home/tou";
		$data['menu-link'] = "Terms of Use";
		return $data;
	}

	function privacy_policy() {
		$data['path'] = "home/pp";
		$data['menu-link'] = "Privacy Policy";
		return $data;
	}

	function onboarding() {
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];
		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}		
		$pm = new pwr_model();

		if($_SESSION['role']=="Manager" || $_SESSION['role']=="Regional" || $_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin") {
			$data['path'] = "home/onboarding";
			$data['teams'] = $pm->get_team_names();	
			$data['menu-link'] = "Onboarding";
			return $data;
		} else {
			header("Location: /");
		}
	}

	function profile() {						
		$sf = new horizon_salesforce();
		$pm = new pwr_model();		

		if(isset($_POST['submit'])) {			
			$target_dir = $_SERVER['DOCUMENT_ROOT']."/assets/images/users/";			
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);			
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
			    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			    if($check !== false) {
			        $message = "File is an image";
			        $uploadOk = 1;
			    } else {
			        $message = "File is not an image.";
			        $uploadOk = 0;
			    }
			}			

			$code = chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122));

			$target_file = $target_dir.$_POST['id'].$code.".".$imageFileType;	
			$target_file2 = "/assets/images/users/".$_POST['id'].$code.".".$imageFileType;		

			// Check if file already exists
			if (file_exists($target_file)) {
			    unlink($target_file);
			}
			// Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
			    $message = "Sorry, your file is too large.";
			    $uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			    $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			    $uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			    $message = "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        $message = "The image file has been uploaded.";
			        $success = true;
			    } else {
			        $message = "Sorry, there was an error uploading your file.";
			        $success = false;
			    }
			}
			$data['img']['success'] = $success;
			$data['img']['message'] = $message;			
		}

		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];
		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}
		$id = getUriSegment(2);
		if($id!="")
		{
			$account_id = $id;
			$data['view'] = 1;
		} else {
			$data['view'] = 0;
		}
	
		$account = $pm->get_account($account_id);
		if($account==NULL) {
			header("Location: /");
		}
		$data['teams'] = $pm->get_team_names();		

		$data['account'] = $account;		

		if(isset($_GET['edit'])) {
			if($_SESSION['role']=="VP" || $_SESSION['role']=="CEO" || $_SESSION['role']=="Sales Support" || $_SESSION['role']=="Admin" || $_SESSION['user_id']==$account_id) {
				$data['edit'] = 1;
			} else {
				$data['edit'] = 0;
			}
		} else {					
			$data['edit'] = 0;
			if($_SESSION['user_id']==$account_id) {
				$data['view'] = 0;
			}
		}

		$picture = $pm->get_picture($account_id);

		if(isset($data['img'])) {			
			if($data['img']['success']) {
				$pm->add_picture($account_id,$target_file2);
				$picture = $pm->get_picture($account_id);
			}			
		}
		
		if($picture!=NULL) {
			$data['prof_pic'] = IMAGE_BASE_URL.$picture['link'];
		} else {
			$data['prof_pic'] = "/pic/".$account_id;
		}		
		if($account_id == $_SESSION['user_id']) {
			$_SESSION['user']['picture'] = $data['prof_pic'];
		}		

		$data['name'] = explode(" ",$account["Name"]);
		if($data['view']==1 && $data['edit']==1) {						
			$data['path'] = "home/profile-edit";	
		} else if ($data['view']==1) {			
			$data['path'] = "home/profile-view";
		} else {						
			$data['path'] = "home/profile";
		}
		
		$data['menu-link'] = "Profile";
		return $data;
	}

	function pic() {
		$sf = new horizon_salesforce();	
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];
		} else {
			$account_id = horizon_login::check_cookie();
		}

		$id = getUriSegment(2);
		if($id!="")
		{
			$account_id = $id;
		}		
		
		if ($account_id !== false) { # Logged in. OK
			# Now output the image with content-type and all
			$sf->output_attachment_from_account($account_id, 'photo.%');
		}
		else {
			header("content-type: image/jpg");
			readfile('/assets/images/sample.jpg');
		}
		exit;
	}
?>