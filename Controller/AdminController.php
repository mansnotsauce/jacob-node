<?php 
	function index() {
		header("Location: /");
		exit;
	}

	function add_user() {		
		if(isset($_SESSION['user_id']))	{
			$account_id = $_SESSION['user_id'];
		} else {
			$account_id = horizon_login::check_cookie();
			if($account_id==false) {
				header("Location: /login");
			}
		}
		$sf = new horizon_salesforce();			
		$pm = new pwr_model();		
		$data['teams'] = $pm->get_team_names();
		$account = $sf->get_account($account_id);		
		$data['name'] = explode(" ",$account["Name"]);						
		$data['account'] = $account;
		if($data['account']['Position__c']=="VP" || $data['account']['Position__c']=="CEO" || $data['account']['Position__c']=="Sales Support" || $data['account']['Position__c']=="Admin") {
			$data['path'] = "admin/add-new-user";
			$data['menu-link'] = "Add New User";
			return $data;	
		} else {
			header("Location: /");
		}
	}

?>