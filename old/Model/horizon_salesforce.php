<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class horizon_salesforce {
	public $access_token = '';
	public $instance_url = '';
	public $last_error = array('error'=>false);
	public $die_on_error = true;
	public function __construct() {
		# Defined in horizon_sf_config.php
		$this->access_token = SALESFORCE_ACCESS_TOKEN;
		$this->instance_url = SALESFORCE_INSTANCE_URL;
	}
	/*
	How to use:
	This is used during the callback. AKA callback.php

	$sf = new horizon_salesforce();
	$sf->authorize(['grant_type'=>'authorization_code', 'redirect_url'=>SALESFORCE_REDIRECT_URI]);
	*/
	public function save_error($curl, $http_status, $url, $json_response) {
		$this->last_error = array();

		$this->last_error['error'] = true;
		$this->last_error['http_status'] = $http_status;
		$this->last_error['url'] = $url;
		$this->last_error['json_response'] = $json_response;
		$this->last_error['curl_errno'] = curl_errno($curl);
		$this->last_error['curl_error'] = curl_error($curl);

		if ($this->die_on_error) {
			// // die("Error: call to URL $url failed with status $http_status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
			// die("Error here!");
			header("Location: /unavailable");
		}
	}

	public function oauth2() {

		$token_url = SALESFORCE_LOGIN_URI . "/services/oauth2/authorize";

		# Get the refresh token
		$params = array(
			'response_type' => 'code',
			'client_id' => SALESFORCE_CLIENT_ID,
			'redirect_uri' => SALESFORCE_REDIRECT_URI,
		);

		$curl = curl_init($token_url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status != 200) {
			$this->save_error($curl, $status, $token_url, $json_response);
		}

		curl_close($curl);
		return json_decode($json_response, true);
	}	

	public function authorize($user_params) {

		$token_url = SALESFORCE_LOGIN_URI . "/services/oauth2/token";

		# Get the refresh token
		$params = array_merge([
			'client_id' => SALESFORCE_CLIENT_ID,
			'client_secret' => SALESFORCE_CLIENT_SECRET,
		], $user_params);

		$curl = curl_init($token_url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status != 200) {
			$this->save_error($curl, $status, $token_url, $json_response);
		}

		curl_close($curl);
		return json_decode($json_response, true);
	}
	public function get_pwrstation_accounts() {
		return $this->query("SELECT Id, Name, Company_Email__c, Position__c, Status__c, Onboarding_Complete__c from Account WHERE Onboarding_Complete__c=true AND Position__c!='Admin' AND Status__c='Active'");
	}
	public function get_onboarding_accounts() {
		return $this->query("SELECT Id, Name, Company_Email__c, Position__c, Status__c, Onboarding_Complete__c, Onboarding_Complete_Percent__c from Account WHERE Onboarding_Complete__c=false AND Status__c='Onboarding' ORDER BY CreatedDate DESC");
	}
	public function list_accounts() {
		return $this->query("SELECT Name, Id from Account LIMIT 100");
	}
	public function delete_account($id) {
		$url = $this->instance_url . "/services/data/v39.0/sobjects/Account/" . $id;
		return $this->delete($url);
	}
	public function create_account($params) {
		$url = $this->instance_url . "/services/data/v39.0/sobjects/Account/";
		return $this->post($url, $params);
	}


	//GET OPPORTUNITY
	public function get_period($period,$customprev=false,$customcur=false) {
		if($period=="Yesterday") {
			$prevdate = date("Y-m-d",strtotime("-1 day"));
			$curdate = date("Y-m-d",strtotime("-1 day"));	
		} else if($period=="This Week") {
			$prevdate = date('Y-m-d', strtotime('-'.date("w").' days'));
			$curdate = date("Y-m-d");
		} else if($period=="Last Week") {
			$days = date("w")+7;
			$days2 = date("w")+1;			
			$prevdate = date('Y-m-d', strtotime('-'.$days.' days'));			
			$curdate = date('Y-m-d', strtotime('-'.$days2.' days'));
			
		} else if($period=="This Month") {
			$m = date("m");
			$prevdate = date('Y-m-d', strtotime(date("Y")."-".$m."-01"));
			$curdate = date("Y-m-d");				
		} else if($period=="Last Month") {
			$m = date("m")-1;
			$t = date("t",strtotime(date("Y")."-".$m."-01"));			
			$prevdate = date('Y-m-d', strtotime(date("Y")."-".$m."-01"));
			$curdate = date('Y-m-d', strtotime(date("Y")."-".$m."-".$t));			
		} else if($period=="This Quarter") {
			$q = (int)date("m");
			if($q >= 1 && $q<4) {
				$m = 1;
			}  else if($q >= 4 && $q<7) {
				$m = 4;
			} else if($q >= 7 && $q<10) {
				$m = 7;
			} else if(($q >= 10 && $q<13) || $q == 0) {
				$m = 10;
			}			
			if($q ==0) {
				$y = date("Y",strtotime("-1 year"));
			} else {
				$y = date("Y");
			}
			$prevdate = date('Y-m-d', strtotime($y."-".$m."-01"));
			$curdate = date("Y-m-d");			
		} else if($period=="Last Quarter") {
			$q = (int)date("m")-3;			
			if($q >= 1 && $q<4) {
				$m = 1;
			}  else if($q >= 4 && $q<7) {
				$m = 4;
			} else if($q >= 7 && $q<10) {
				$m = 7;
			} else if(($q >= 10 && $q<13) || $q == 0) {
				$m = 10;
			}			
			if($q ==0) {
				$y = date("Y",strtotime("-1 year"));
			} else {
				$y = date("Y");
			}			
			$prevdate = date('Y-m-d', strtotime($y."-".$m."-01"));
			$m2 = $m+2;
			$t = date("t",strtotime($y."-".$m2."-01"));
			$curdate = date("Y-m-d",strtotime($y."-".$m2."-".$t));				
		} else if($period=="This Year") {
			$prevdate = date('Y-m-d',strtotime(date("Y")."-01-01"));
			$curdate = date("Y-m-d");
		} else if($period=="Last Year") {
			$y = date("Y")-1;
			$prevdate = date('Y-m-d',strtotime($y."-01-01"));
			$curdate = date('Y-m-d',strtotime($y."-12-31"));			
		} else if($period=="All Time") {
			$prevdate = "2010-01-01";
			$curdate = date("Y-m-d");
		} else if($period=="Custom") {
			$prevdate = $customprev;
			$curdate = $customcur;
		} else {
			$prevdate = date("Y-m-d");
			$curdate = date("Y-m-d");
		}
		$data['prevdate'] = $prevdate;
		$data['curdate'] = $curdate;
		return $data;
	}

	public function get_lead($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$url = "SELECT Lead__c, LeadCount__c ,Lead_Created_Date__c FROM Opportunity WHERE Account.Id = '$account_id' AND Lead_Created_Date__c >= $prevdate AND Lead_Created_Date__c <= $curdate";
		$result = $this->query($url);
		return $result['totalSize'];
	}

	public function get_commission($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$url = "SELECT Earned_Commission__c, Earned_Commission_Paid_Date__c, Account__c FROM Residential_Projects__c WHERE Account__c = '$account_id' AND Earned_Commission_Paid_Date__c >= $prevdate AND Earned_Commission_Paid_Date__c <= $curdate";				
		$result = $this->query($url);	
		$commission = 0;
		foreach ($result['records'] as $key => $value) {
			$commission += $value['Earned_Commission__c'];
		}
		return $commission;
	}

	public function get_closes($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate']."T00:00:00.000+0000";
		$curdate = $date['curdate']."T00:00:00.000+0000";
		
		$url = "SELECT Site_Audit_Complete__c, Site_Audit_Complete_Date_Time__c FROM Residential_Projects__c WHERE Account__c = '$account_id' AND Site_Audit_Complete_Date_Time__c >= $prevdate AND Site_Audit_Complete_Date_Time__c <= $curdate AND Site_Audit_Complete__c = true";		
		$result = $this->query($url);
		return $result['totalSize'];
	}

	public function get_sits($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate'];
		$curdate = $date['curdate'];
		
		$url = "SELECT Sat__c, Appointment_Date__c FROM Opportunity WHERE Account.Id = '$account_id' AND Appointment_Date__c >= $prevdate AND Appointment_Date__c <= $curdate AND Sat__c = true";		
		$result = $this->query($url);
		return $result['totalSize'];
	}

	public function get_installs($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate']."T00:00:00.000+0000";
		$curdate = $date['curdate']."T00:00:00.000+0000";
		
		$url = "SELECT Install_Complete__c, Install_Complete_Date_Time__c FROM Residential_Projects__c WHERE Account__c = '$account_id' AND Install_Complete_Date_Time__c >= $prevdate AND Install_Complete_Date_Time__c <= $curdate AND Install_Complete__c = true";		
		$result = $this->query($url);		
		return $result['totalSize'];
	}

	public function get_assisted_installs($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate']."T00:00:00.000+0000";
		$curdate = $date['curdate']."T00:00:00.000+0000";
		
		$url = "SELECT Install_Complete__c, Field_Marketer__c FROM Residential_Projects__c WHERE Account__c = '$account_id' AND Install_Complete_Date_Time__c >= $prevdate AND Install_Complete_Date_Time__c <= $curdate AND Install_Complete__c = true AND Field_Marketer__c != '".$_SESSION['user']['name']."'";		
		$result = $this->query($url);
		return $result['totalSize'];
	}

	public function get_self_generated_installs($account_id,$period,$customprev=false,$customcur=false) {
		$date = $this->get_period($period,$customprev,$customcur);
		$prevdate = $date['prevdate']."T00:00:00.000+0000";
		$curdate = $date['curdate']."T00:00:00.000+0000";
		
		$url = "SELECT Install_Complete__c, Field_Marketer__c FROM Residential_Projects__c WHERE Account__c = '$account_id' AND Install_Complete_Date_Time__c >= $prevdate AND Install_Complete_Date_Time__c <= $curdate AND Install_Complete__c = true AND Field_Marketer__c = '".$_SESSION['user']['name']."'";		
		$result = $this->query($url);
		return $result['totalSize'];
	}	

	public function get_KW_per_year($account_id) {		
		$prevdate = date("Y")."-01-01T00:00:00.000+0000";		
		$curdate = date("Y-m-d")."T00:00:00.000+0000";
		
		$url = "SELECT Final_System_Size__c FROM Residential_Projects__c WHERE Account__c = '$account_id' AND Install_Complete_Date_Time__c >= $prevdate AND Install_Complete_Date_Time__c <= $curdate AND Final_System_Size__c!=NULL AND Install_Complete__c = true";		
		$result = $this->query($url);
		$kw = 0;
		foreach ($result['records'] as $key => $value) {
			$kw += $value['Final_System_Size__c'];
		}
		return number_format($kw,2);
	}	

	# NOTE: This function was built to update the account info AND calculate percentage complete
	public function update_with_percent($id, $old_params, $new_params) {

		# This is to calculate completion
		$params = array_merge($old_params, $new_params);

		# Calculate completion, and attach it to our parameters
		$completion = get_completion_data($params);
		$new_params['Onboarding_Complete_Percent__c'] = $completion['total'];

		if($completion['total'] == 100) {
			$account = $this->get_account($id);
			if($account['Onboarding_Complete__c']==false) {
				$result = $this->congrats_email($id);
				$new_params['Status__c'] = "Active";
				$new_params['Onboarding_Complete__c'] = true;
			}			
		}

		$this->update_account($id, $new_params);
	}

	# Simple API call for updating an account record
	public function update_account($id, $params) {
		$url = $this->instance_url . "/services/data/v39.0/sobjects/Account/" . $id;
		return $this->patch($url, $params);
	}
	public function get_account($id) {
		$url = $this->instance_url . "/services/data/v39.0/sobjects/Account/" . $id;
		return $this->get($url);
	}

	# Welcome Email
	public function welcome_email($email,$pass) {		
		$message = '<!doctype html>
<html><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<title>welcome-email</title>
<style>
@media (max-width:767px){
table {
    width: 100%;
}
}
</style>
</head>
<body style="margin:0;padding:0;font-family: "Montserrat", sans-serif;background: #eee;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="display:none;"><td>Here at HorizonPWR, we train, perform, and play as a team. We win together and we learn together. Because we&apos;re a team, each of us is dedicated to your success. You&apos;ll be given all the knowledge and resources necessary for you to be successful. All you have to do is supply the grit--that perfect combination of hustle, passion and perseverance.</td></tr>
<tr>
<td style="padding: 25px 0;background-color:#eee;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>    
<td valign="middle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tbody><tr>
<td style="background-image:url(https://pwrstation.horizonpwr.com/assets/images/email/header-bg.jpg); background-repeat: no-repeat;
    background-size: cover;"><h1 style="color: #fff; text-align:center; font-size:24px;">Welcome to </h1><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" alt="logo" style="display: block; margin: 19px auto 32px;">
                                          
</td>
</tr>
</tbody></table></td></tr>
</tbody></table>
</td>
</tr>
</tbody></table>
			
			
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 23px 37px 8px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">Welcome to the HorizonPWR Team!</p></td>
</tr><tr>
<td style="padding: 0px 37px 0px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">Here at HorizonPWR, we train, perform, and play as a team. We win together and we learn
together. Because we&apos;re a team, each of us is dedicated to your success. You&apos;ll be given all the
knowledge and resources necessary for you to be successful. All you have to do is supply the
grit--that perfect combination of hustle, passion and perseverance.</p></td></tr>
<tr>
<td style="padding: 0 37px;"><p style="color: #3b4958; font-size: 17px; line-height: 29px;font-weight: 400;">We carefully choose the people that join our team. We believe you have what it takes to
succeed on an individual level, but also to contribute to the success of the team as a whole. So
let&apos;s get started!</p></td></tr>
<tr>
<td style="padding: 0px 37px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">With high ambition comes high expectation. In this email, you&apos;ll find some of the basic resources
that will be vital in helping you be ready for your first day. The learning curve will be steep over
the next few weeks, but having a good jump start on each of the following items will make all the
difference in your success here.</p></td></tr>
<tr>
<td style="padding: 18px 37px 20px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">First things first, follow this link to get onboarded: onboarding.horizonpwr.com</p></td></tr>



<tr>
<td style="padding:0 37px 16px;font-size: 14px;font-weight: 600;">Username: '.$email.'</td>
</tr>
<tr>
<td style="padding:0 37px 16px;font-size: 14px;font-weight: 600;">Password: '.$pass.'</td>
</tr><tr>
<td style="padding: 0px 37px 0px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">Complete these tasks and you will then receive an email with further instructions.</p></td></tr>
<tr>
<td style="padding: 14px 37px 0;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;"><strong>Required Reading:</strong> Above the Line by Urban Meyer (paperback)</p></td></tr>
<tr>
<td style="padding: 18px 37px 0px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0; text-align: center;">This book can be purchased on Amazon or at Barnes &amp; Noble.</p></td></tr>
<tr><td style="padding: 18px 37px 16px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">For any clarifications, we are just a message or a call away. Feel free to drop in for guidance,
answers or anything else you might need! We are happy to help!</p></td></tr>
<tr><td style="padding: 0px 37px 28px;"><p style="color: #3b4958;font-size: 17px;line-height: 29px;font-weight: 400;margin: 0;">Get ready to win with us!</p></td></tr>


</tbody></table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #f8f8f8;text-align: center;padding: 24px 0 19px;">
<tbody><tr>
<td><p style="margin: 0; font-size: 14px; color: #3b4958; font-weight: 600;vertical-align: middle;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/phone.png" style="padding-right: 7px;">Phone: 888-468-7180</p></td>   
</tr>
<tr><td><p style="font-size: 14px; color: #3b4958; font-weight: 600; vertical-align: middle; margin: 23px 0px 21px;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/location.png" style="padding-right: 7px;">HEADQUARTERS 237 N 2nd E St, Rexburg, ID 83440
</p></td>
</tr>
<tr><td><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" style="max-width: 242px;"></td></tr>
</tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 17px 34px;"><p style="font-size: 13px; line-height: 27px; color: #3b4958; font-weight: 500; text-align: center; margin: 0;">You are being sent this email because your email was entered into our system. If this is being send in error please email <a href="" style="color: #5199d2;">itsupport@horizonpwr.com</a> to unsubscribe.</p></td>        
</tr></tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>
<td style="background-color: #3b4958;"><p style="color: #fff; text-align: center; font-size: 10px; font-weight: 600;  margin: 12px 0;">&copy; Copyright 2019 Horizon PWR</p></td>        
</tr></tbody>
</table>
        </td>
    </tr>
</tbody></table>

</body></html>';

		$this->send_smtp_email($email, 'Welcome to the HorizonPWR Team!', $message);
		return true;
	}

	public function password_reset_email($email,$pass) {
		$message = "
		<html>
		<head>
		<title>Password Reset Successful!</title>
		</head>
		<body>
		<p>Your password has successfully been reset.</p>
		<p>Please log in with the update credentials below:</p>
		<p>Username: ".$email."</p>
		<p>Password: ".$pass."</p>
		<p><a href='https://pwrstation.horizonpwr.com/'>Log in to PWR Station</a></p>			
		</body>
		</html>
		";

		$this->send_smtp_email($email, 'Password Reset Successful!', $message);
		return true;
	}

	public function welcome_email2($email,$pass,$email2=null) {		
		$message = '<!doctype html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>pwr-station</title>
<style>
@media (max-width:767px){
table {
    width: 100%;
}
}
</style>
</head>
<body style="margin:0;padding:0;font-family: "Montserrat", sans-serif;background: #eee;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="display:none;"><td>Now that you are fully onboarded, it is time to get you set up in the PWRStation. The PWRStation is where you will access exciting content like the Leader Board, training content and our weekly show, PWRLine. The PWRStation is continually evolving and will be updated from time to time with new features that will help enhance your overall experience with HorizonPWR.</td></tr>
<tr>
<td style="padding: 25px 0;background-color: #eee;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>    
<td valign="middle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tbody><tr>
<td style="background-image:url(https://pwrstation.horizonpwr.com/assets/images/email/header-bg.jpg); background-repeat: no-repeat;
    background-size: cover;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" alt="logo" style="display: block; margin: 19px auto 32px;">
<h1 style="color: #fff; text-align:center; font-size:24px;">Welcome to PWRStation!</h1>                                          
</td>
</tr>
</tbody></table></td></tr>
</tbody></table>
</td>
</tr>
</tbody></table>
			
			
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 23px 37px 8px;"><p style="color: #3b4958; font-size: 17px; line-height: 29px;font-weight: 400;">Now that you are fully onboarded, it is time to get you set up in the PWRStation. The PWRStation is where you will access exciting content like the Leader Board, training content and our weekly show, PWRLine. The PWRStation is continually evolving and will be updated from time to time with new features that will help enhance your overall experience with HorizonPWR.</p></td>
</tr>
<tr>
<td style="padding: 0px 37px 27px;"><a href="https://pwrstation.horizonpwr.com/login" style="color: #5199d3; font-size: 17px;">Follow this link to sign in with PWRStation:</a></td>
</tr>
<tr>
<td style="padding:0 37px 16px; font-size: 14px;">Username: '.$email.'</td>
</tr>
<tr>
<td style="padding:0 37px 16px; font-size: 14px;">Password: '.$pass.'</td>
</tr>
<tr>
<td><h2 style="color: #3b4958; font-size: 22px; margin: 11px 0 0; font-weight: 400; padding: 0 37px;">Get ready to start winning!</h2></td>
</tr>
<tr>
<td style="padding: 0 37px 38px;"><h3 style="font-size: 17px; color: #3b4958; font-weight: 600;">Human Capital Department</h3></td>
</tr>
</tbody></table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #f8f8f8;text-align: center;padding: 24px 0 19px;">
<tbody><tr>
<td><p style="margin: 0; font-size: 14px; color: #3b4958; font-weight: 600;vertical-align: middle;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/phone.png" style="padding-right: 7px;">Phone: 888-468-7180</p></td>   
</tr>
<tr><td><p style="font-size: 14px; color: #3b4958; font-weight: 600; vertical-align: middle; margin: 23px 0px 21px;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/location.png" style="padding-right: 7px;">HEADQUARTERS 237 N 2nd E St, Rexburg, ID 83440
</p></td>
</tr>
<tr><td><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" style="max-width: 242px;"></td></tr>
</tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 17px 34px;"><p style="font-size: 13px; line-height: 27px; color: #3b4958; font-weight: 500; text-align: center; margin: 0;">You are being sent this email because your email was entered into our system. If this is being send in error please email <a href="" style="color: #5199d2;">itsupport@horizonpwr.com</a> to unsubscribe.</p></td>        
</tr></tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>
<td style="background-color: #3b4958;"><p style="color: #fff; text-align: center; font-size: 10px; font-weight: 600;  margin: 12px 0;">&copy; Copyright 2019 Horizon PWR</p></td>        
</tr></tbody>
</table>
        </td>
    </tr>
</tbody></table>
</body>
</html>';
	
	if($email2!=null) {
		$email = $email2;
	}

		$this->send_smtp_email($email, 'Welcome to PWRStation!', $message);
		return true;
	}


	# Onboarding Reminder Email
	public function reminder_email($email) {		
		$message = '<!doctype html>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>onboarding</title>
<style>
@media (max-width:767px){
table {
    width: 100%;
}
}
</style>
</head>
<body style="margin:0;padding:0;font-family: "Montserrat", sans-serif;background: #eee;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr>
<td style="padding: 25px 0;background-color:#eee;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>    
<td valign="middle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="display:none;"><td>Hello again! It looks like we&apos;re still waiting on you to complete part of your Onboarding Process. Please get
that done right away. Your success begins with completing this first task!</td></tr>
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tbody><tr>
<td style="background-image:url(https://pwrstation.horizonpwr.com/assets/images/email/header-bg.jpg); background-repeat: no-repeat;
    background-size: cover;"><h1 style="color: #fff; text-align:center; font-size:24px;">Welcome to </h1><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" alt="logo" style="display: block; margin: 19px auto 32px;">
                                          
</td>
</tr>
</tbody></table></td></tr>
</tbody></table>
</td>
</tr>
</tbody></table>
			
			
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 15px 37px 0px;"><p style="color: #3b4958; font-size: 17px; line-height: 29px;font-weight: 400;">Hello again!</p></td>
</tr>
<tr>
<td style="padding: 0px 37px 0;"><p style="
    margin: 5px 0 15px;
    line-height: 30px;
">It looks like we&apos;re still waiting on you to complete part of your Onboarding Process. Please get
that done right away. Your success begins with completing this first task!</p></td>
</tr>


<tr>
<td><h2 style="color: #3b4958;font-size: 22px;margin: 10px 0 0px;font-weight: 400;padding: 0 37px;">Get ready to start winning!</h2></td>
</tr>
<tr>
<td style="padding: 0 37px 38px;"><h3 style="font-size: 17px; color: #3b4958; font-weight: 600;">Human Capital Department</h3></td>
</tr>
</tbody></table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #f8f8f8;text-align: center;padding: 24px 0 19px;">
<tbody><tr>
<td><p style="margin: 0; font-size: 14px; color: #3b4958; font-weight: 600;vertical-align: middle;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/phone.png" style="padding-right: 7px;">Phone: 888-468-7180</p></td>   
</tr>
<tr><td><p style="font-size: 14px; color: #3b4958; font-weight: 600; vertical-align: middle; margin: 23px 0px 21px;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/location.png" style="padding-right: 7px;">HEADQUARTERS 237 N 2nd E St, Rexburg, ID 83440
</p></td>
</tr>
<tr><td><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" style="max-width: 242px;"></td></tr>
</tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 17px 34px;"><p style="font-size: 13px; line-height: 27px; color: #3b4958; font-weight: 500; text-align: center; margin: 0;">You are being sent this email because your email was entered into our system. If this is being send in error please email <a href="" style="color: #5199d2;">itsupport@horizonpwr.com</a> to unsubscribe.</p></td>        
</tr></tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>
<td style="background-color: #3b4958;"><p style="color: #fff; text-align: center; font-size: 10px; font-weight: 600;  margin: 12px 0;">&copy; Copyright 2019 Horizon PWR</p></td>        
</tr></tbody>
</table>
        </td>
    </tr>
</tbody></table>
</body>
</html>';

		$this->send_smtp_email($email, 'Onboarding Reminder', $message);
		return true;
	}


	# Send Congratulations Email
	public function congrats_email($id) {	
		$account = $this->get_account($id);
		$message = '<!doctype html>
<html><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<title>onboarding-complete</title>
<style>
@media (max-width:767px){
table {
    width: 100%;
}
}
</style>
</head>
<body style="margin:0;padding:0;font-family: "Montserrat", sans-serif;background: #eee;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="display:none;"><td>It looks like your Onboarding is complete and you&apos;re ready to hit the ground running! Remember
that if you need anything, please feel free to reach out to your managers or to us. We&apos;re excited
to help you succeed!</td></tr>
<tr>
<td style="padding: 25px 0;background-color: #eee;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>    
<td valign="middle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tbody><tr>
<td style="background-image:url(https://pwrstation.horizonpwr.com/assets/images/email/header-bg.jpg); background-repeat: no-repeat;
    background-size: cover;"><h1 style="color: #fff; text-align:center; font-size:24px;">Welcome to </h1><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" alt="logo" style="display: block; margin: 19px auto 32px;">
                                          
</td>
</tr>
</tbody></table></td></tr>
</tbody></table>
</td>
</tr>
</tbody></table>
			
			
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 15px 37px 0px;"><p style="color: #3b4958; font-size: 17px; line-height: 29px;font-weight: 400;">Congratulations!</p></td>
</tr>
<tr>
<td style="padding: 0px 37px 0;"><p style="
    margin: 5px 0 15px;
    line-height: 30px;
">It looks like your Onboarding is complete and you&apos;re ready to hit the ground running! Remember
that if you need anything, please feel free to reach out to your managers or to us. We&apos;re excited
to help you succeed!</p></td>
</tr>


<tr>
<td><h2 style="color: #3b4958;font-size: 22px;margin: 10px 0 0px;font-weight: 400;padding: 0 37px;">Welcome to the team!</h2></td>
</tr>
<tr>
<td style="padding: 0 37px 38px;"><h3 style="font-size: 17px; color: #3b4958; font-weight: 600;">Human Capital Department</h3></td>
</tr>
</tbody></table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #f8f8f8;text-align: center;padding: 24px 0 19px;">
<tbody><tr>
<td><p style="margin: 0; font-size: 14px; color: #3b4958; font-weight: 600;vertical-align: middle;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/phone.png" style="padding-right: 7px;">Phone: 888-468-7180</p></td>   
</tr>
<tr><td><p style="font-size: 14px; color: #3b4958; font-weight: 600; vertical-align: middle; margin: 23px 0px 21px;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/location.png" style="padding-right: 7px;">HEADQUARTERS 237 N 2nd E St, Rexburg, ID 83440
</p></td>
</tr>
<tr><td><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" style="max-width: 242px;"></td></tr>
</tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 17px 34px;"><p style="font-size: 13px; line-height: 27px; color: #3b4958; font-weight: 500; text-align: center; margin: 0;">You are being sent this email because your email was entered into our system. If this is being send in error please email <a href="" style="color: #5199d2;">itsupport@horizonpwr.com</a> to unsubscribe.</p></td>        
</tr></tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>
<td style="background-color: #3b4958;"><p style="color: #fff; text-align: center; font-size: 10px; font-weight: 600;  margin: 12px 0;">&copy; Copyright 2019 Horizon PWR</p></td>        
</tr></tbody>
</table>
        </td>
    </tr>
</tbody></table>

</body></html>';

		$this->send_smtp_email($account['Company_Email__c'], 'Congratulations!', $message);
		return true;
	}

	public function test_congrats($email) {			
		$message = '<!doctype html>
<html><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
<title>onboarding-complete</title>
<style>
@media (max-width:767px){
table {
    width: 100%;
}
}
</style>
</head>
<body style="margin:0;padding:0;font-family: "Montserrat", sans-serif;background: #eee;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="display:none;"><td>It looks like your Onboarding is complete and you&apos;re ready to hit the ground running! Remember
that if you need anything, please feel free to reach out to your managers or to us. We&apos;re excited
to help you succeed!</td></tr>
<tr>
<td style="padding: 25px 0;background-color: #eee;">
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>    
<td valign="middle">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody><tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tbody><tr>
<td style="background-image:url(https://pwrstation.horizonpwr.com/assets/images/email/header-bg.jpg); background-repeat: no-repeat;
    background-size: cover;"><h1 style="color: #fff; text-align:center; font-size:24px;">Welcome to </h1><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" alt="logo" style="display: block; margin: 19px auto 32px;">
                                          
</td>
</tr>
</tbody></table></td></tr>
</tbody></table>
</td>
</tr>
</tbody></table>
			
			
<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 15px 37px 0px;"><p style="color: #3b4958; font-size: 17px; line-height: 29px;font-weight: 400;">Congratulations!</p></td>
</tr>
<tr>
<td style="padding: 0px 37px 0;"><p style="
    margin: 5px 0 15px;
    line-height: 30px;
">It looks like your Onboarding is complete and you&apos;re ready to hit the ground running! Remember
that if you need anything, please feel free to reach out to your managers or to us. We&apos;re excited
to help you succeed!</p></td>
</tr>


<tr>
<td><h2 style="color: #3b4958;font-size: 22px;margin: 10px 0 0px;font-weight: 400;padding: 0 37px;">Welcome to the team!</h2></td>
</tr>
<tr>
<td style="padding: 0 37px 38px;"><h3 style="font-size: 17px; color: #3b4958; font-weight: 600;">Human Capital Department</h3></td>
</tr>
</tbody></table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #f8f8f8;text-align: center;padding: 24px 0 19px;">
<tbody><tr>
<td><p style="margin: 0; font-size: 14px; color: #3b4958; font-weight: 600;vertical-align: middle;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/phone.png" style="padding-right: 7px;">Phone: 888-468-7180</p></td>   
</tr>
<tr><td><p style="font-size: 14px; color: #3b4958; font-weight: 600; vertical-align: middle; margin: 23px 0px 21px;"><img src="https://pwrstation.horizonpwr.com/assets/images/email/location.png" style="padding-right: 7px;">HEADQUARTERS 237 N 2nd E St, Rexburg, ID 83440
</p></td>
</tr>
<tr><td><img src="https://pwrstation.horizonpwr.com/assets/images/email/logo.png" style="max-width: 242px;"></td></tr>
</tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0" style="background: #fff;">
<tbody><tr>
<td style="padding: 17px 34px;"><p style="font-size: 13px; line-height: 27px; color: #3b4958; font-weight: 500; text-align: center; margin: 0;">You are being sent this email because your email was entered into our system. If this is being send in error please email <a href="" style="color: #5199d2;">itsupport@horizonpwr.com</a> to unsubscribe.</p></td>        
</tr></tbody>
</table>

<table width="630" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody><tr>
<td style="background-color: #3b4958;"><p style="color: #fff; text-align: center; font-size: 10px; font-weight: 600;  margin: 12px 0;">&copy; Copyright 2019 Horizon PWR</p></td>        
</tr></tbody>
</table>
        </td>
    </tr>
</tbody></table>

</body></html>';

		$this->send_smtp_email($email, 'Congratulations!', $message);
		return true;
	}


	# Send Email
	function send_smtp_email($to_email, $subject, $message) {
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
		    // Recipients
		    $mail->setFrom('welcome@onboarding.horizonpwr.com', 'HorizonPWR');
		    $mail->addAddress($to_email);

	 	   	//Server settings
			$mail->SMTPDebug = 2;                                 // Enable verbose debug output
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'email-smtp.us-west-2.amazonaws.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'AKIASQSCL2DWDX2JCK75';                 // SMTP username
			$mail->Password = 'BBirsFHfRP9IYXixkjQY/T6qmgTSpFdX+pX2/OCSP1Lm';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to


		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body = $message;

		    $mail->send();

		    return true;
		} catch (Exception $e) {
		    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}

	# Gets an attachment from an account ID and with a specific filename
	# NOTE: Do not use user input here. Parameters are not sanitized and are vulnerable to MySQL injection
	# For filename, feel free to use wildcards. e.g. $filename = 'photo.%' instead of 'photo.jpg'
	public function list_account_attachments($account_id, $filename='') {
		if ($filename != '') { # Return a specific file
			$query = "SELECT Id, Name, ParentId, Parent.Type FROM Attachment WHERE Parent.Type = 'Account' AND Name LIKE '" . $filename . "' AND Parent.Id='" . $account_id . "'";
		}
		else { # Return all files attached to this Account
			$query = "SELECT Id, Name, ParentId, Parent.Type FROM Attachment WHERE Parent.Type = 'Account' AND Parent.Id='" . $account_id . "'";
		}
		return $this->query($query);
	}

	# Finds the first attachment in this $account_id tagged with $filename
	# Please see warnings listed above on list_account_attachments()
	public function output_attachment_from_account($account_id, $filename) {
		$list = $this->list_account_attachments($account_id, $filename);

		if (isset($list['records'][0])) {
			$file_id = $list['records'][0]['Id'];
			$pathinfo = pathinfo($list['records'][0]['Name']);
			switch (strtolower($pathinfo['extension'])) {
				case 'jpg':
					header("content-type: image/jpg");
				break;
				case 'gif':
					header("content-type: image/gif");
				break;
				case 'png':
					header("content-type: image/png");
				break;
			}

			echo $this->get_attachment_body($file_id);
			exit;
		}

		# We didn't exit. File doesn't exist. Return false for failure
		echo file_get_contents(PATH_ROOT."/assets/images/sample.jpg");
		exit;
		return false;
	}
	public function delete_attachment_from_account($account_id, $filename) {
		$list = $this->list_account_attachments($account_id, $filename);

		if (isset($list['records'][0])) {

			# Delete all attachments with this filename
			foreach ($list['records'] as $item) {
				$this->delete_attachment($item['Id']);
			}
		}
	}

	# Gets a single attachment
	public function get_attachment_body($id) {
		$url = $this->instance_url . "/services/data/v39.0/sobjects/Attachment/" . $id . "/Body";
		return $this->get($url, false); # false = don't decode because it's gonna be an image
	}
	# Gets a single attachment
	public function delete_attachment($id) {
		$url = $this->instance_url . "/services/data/v39.0/sobjects/Attachment/" . $id;
		return $this->delete($url);
	}

	# SQL Statement
	public function query($query) {
		$url = $this->instance_url . "/services/data/v39.0/query?q=" . urlencode($query);
		return $this->get($url);
	}

	public function services($query) {
		$url = $this->instance_url . $query;
		return $this->get($url);
	}

	public function upload_attachment($id, $filename, $filedata=false) {

		$url = SALESFORCE_INSTANCE_URL . "/services/data/v29.0/sobjects/Attachment/";

		if ($filedata === false) {
			# Attempt to load the file
			$filedata = file_get_contents($filename);
			$filename = basename($filename); # Strip out path and stuff
		}

		$fields = array(
			'parentId'=>$id, # Account ID
			'name'=>$filename, # e.g. image.jpg
			'body'=>base64_encode($filedata) # raw data
		);

		return $this->post($url, $fields);
	}

	# HTTP GET request with Auth token attached
	public function get($url, $decode_json = true) {		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: Bearer " . $this->access_token));		

		# Execute, retrieve, and close the port
		$json_response = curl_exec($curl);		
		if (strpos($json_response, "Session expired or invalid") == true) {								
			header("Location: /api/");
		}

		if (strpos($json_response, "does not exist") == true) {								
			header("Location: /dashboard/");
		}

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($status != 200) {
			$this->save_error($curl, $status, $url, $json_response);
		}
		curl_close($curl);		

		if ($decode_json) {
			# Decode JSON and send back as array
			return json_decode($json_response, true);
		}
		else {
			# Don't decode because it's raw data
			return $json_response;
		}
	}
	# HTTP POST request with Auth token attached
	public function post($url, $post_params) {
		$content = json_encode($post_params);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: Bearer " . $this->access_token,
					"Content-type: application/json"));
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

		# Execute, retrieve, and close the port
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($status != 201 && $status != 200) {
			$this->save_error($curl, $status, $url, $json_response);
		}
		curl_close($curl);
		return json_decode($json_response, true);
	}
	# HTTP PATCH request with Auth token attached
	public function patch($url, $post_params) {
		$content = json_encode($post_params);

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: Bearer " . $this->access_token,
					"Content-type: application/json"));
		//curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

		# Execute, retrieve, and close the port
		$json_response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($status != 201 && $status != 204) {
			$this->save_error($curl, $status, $url, $json_response);
		}
		curl_close($curl);
		return json_decode($json_response, true);
	}

	public function delete($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER,
				array("Authorization: Bearer " . $this->access_token));
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

		curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($status != 204) {
			$this->save_error($curl, $status, $url, $json_response);
		}

		curl_close($curl);

		return true;
	}
}