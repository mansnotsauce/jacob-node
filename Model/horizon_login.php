<?php
class horizon_login {
	public static $account;

	# See who we're logged in as
	public static function check_cookie($die_on_error = true) {
		$sf = new horizon_salesforce();
		$sf->die_on_error = $die_on_error;

		if (isset($_COOKIE['hsession']) && strlen($_COOKIE['hsession']) >= 32) {
			$session = substr($_COOKIE['hsession'], 0, 32);
			$user_id = substr($_COOKIE['hsession'], 32);
			$account = $sf->get_account($user_id);

			# Make sure we're using the right session key
			if ($account['User_Session_Token__c'] == $session) {
				self::$account = $account;

				$pm = new pwr_model();				
				$picture = $pm->get_picture($account['Id']);
				if($picture!=NULL) {
					$data['picture'] = $picture['link'];
				} else {
					$data['picture'] = "/pic/";
				}							
				$_SESSION['user_id'] = $account['Id'];
				$_SESSION['role'] = $account['Position__c'];
				$data['name'] = $account['Name'];
				$data['name_arr'] = explode(" ",$account["Name"]);
				$data['onboarding_complete'] = $account['Onboarding_Complete__c'];
				$data['status'] = $account['Status__c'];
				$data['email'] = $account['Company_Email__c'];
				$data['personal_email'] = $account['Personal_Email__c'];
				$data['phone']=$account['Phone'];
				$data['team']=$account['Team__c'];
				$check = $pm->is_team_manager($_SESSION['user_id']);
				$data['team_manager']=$check['team_manager'];
				$_SESSION['user'] = $data;
				return $user_id;
			}
		}

		# no success
		return false;
	}


	# Call this function after we've checked authentication. Sets the cookes so we login as this user
	public static function login_as($account_id, $die_on_error = true) {
		$sf = new horizon_salesforce();
		$sf->die_on_error = $die_on_error;

		# Generate a session key
		$session_key = substr(self::generate_crypto_bytes(22, true), 0, 32);

		$params = array(
			#'User_Password_Hash__c' => $_POST['Company_Email__c'],
			'User_Session_Token__c' => $session_key
		);
		$sf->update_account($account_id, $params);

		# Set the cookie with the new session key
		self::update_cookie_session($account_id, $session_key);
	}

	public static function logout($redirect = '') {
		self::update_cookie_session('', '', true);
		session_destroy();
	}

	public static function attempt_to_authenticate($die_on_error = true) {
		$sf = new horizon_salesforce();
		$sf->die_on_error = $die_on_error;

		# If there is no email address then forget everything else
		if (!isset($_POST['email'])) {return false;}

		# Find out which ID we're using based on this email
		$result = $sf->query("SELECT Name, Id, Phone FROM Account WHERE Company_Email__c='" . $_POST['email'] . "' AND Status__c = 'Active' LIMIT 1");
		if (isset($result['records'][0]['Id'])) {
			$account_id = $result['records'][0]['Id'];
		}
		else {
			return "Account doesn't exist";
		}

		# Get account info to find salt/hash
		$account = $sf->get_account($account_id);
		$password = explode("|", $account['User_Password_Hash__c']);

		$pass_salt = $password[0];
		$pass_hash = $password[1];



		# Check if the password works
		$password_ok = false;
		if (crypt($_POST['pass'], $pass_hash) == $pass_hash) {

			if (isset($_POST['remember'])) {
				setcookie('remember_login', '1', time() + 60*60*24*30, BASE_URL, '');
				setcookie('remember_email', $_POST['email'], time() + 60*60*24*30, BASE_URL, '');
			}
			else {
				setcookie('remember_login', '', time() - 60*60*24*30, BASE_URL, '');
				setcookie('remember_email', '', time() - 60*60*24*30, BASE_URL, '');
			}

			# Login as this user
			self::login_as($account_id);	

			$pm = new pwr_model();				
			$picture = $pm->get_picture($account_id);
			if($picture!=NULL) {
				$data['picture'] = $picture['link'];
			} else {
				$data['picture'] = "/pic/";
			}

			$_SESSION['user_id'] = $account['Id'];
			$_SESSION['role'] = $account['Position__c'];			 
			$data['name'] = $account['Name'];
			$data['name_arr'] = explode(" ",$account["Name"]);
			$data['onboarding_complete'] = $account['Onboarding_Complete__c'];
			$data['status'] = $account['Status__c'];
			$data['email'] = $account['Company_Email__c'];
			$data['personal_email'] = $account['Personal_Email__c'];
			$data['phone']=$account['Phone'];
			$data['team']=$account['Team__c'];
			$check = $pm->is_team_manager($_SESSION['user_id']);
			$data['team_manager']=$check['team_manager'];
			$_SESSION['user'] = $data;

			return true;
		}
		else {
			return "Incorrect Username/Password";
		}
	}

	#continues login if set to remember me
	public static function continue_login($die_on_error = true) {					
		$sf = new horizon_salesforce();		
		$sf->die_on_error = $die_on_error;

		# If there is no email address then forget everything else
		if (!isset($_COOKIE['remember_email'])) {return false;}		

		# Find out which ID we're using based on this email
		$result = $sf->query("SELECT Name, Id, Phone FROM Account WHERE Company_Email__c='" . $_COOKIE['remember_email'] . "' LIMIT 1");		
		if (isset($result['records'][0]['Id'])) {
			$account_id = $result['records'][0]['Id'];			
		}
		else {
			return false;
		}

		# Login as this user
		self::login_as($account_id);
		$account = $sf->get_account($account_id);
		$_SESSION['user_id'] = $account['Id'];
		$_SESSION['role'] = $account['Position__c'];
		$data['name'] = $account['Name'];
		$data['name_arr'] = explode(" ",$account["Name"]);
		$data['onboarding_complete'] = $account['Onboarding_Complete__c'];
		$data['status'] = $account['Status__c'];
		$data['email'] = $account['Company_Email__c'];
		$data['personal_email'] = $account['Personal_Email__c'];
		$data['phone']=$account['Phone'];
		$_SESSION['user'] = $data;
		return true;
	}


	public static function create_password($password, $pass_rounds=12) {

		# Encrypt the password and store it
		$pass_hash = "*0";
		while($pass_hash == "*0") {
			$pass_salt = horizon_login::generate_password_salt((int)12);	
			$pass_hash = crypt($password, $pass_salt);
		}

		return $pass_salt . '|' . $pass_hash;
	}

	public static function update_cookie_session($user_id, $session, $delete=false) {
		if (headers_sent()) {
			return false;
		}
		/*
		Notes on sessions:
		- We are not setting the domain because localhost is not a valid cookie domain.
		- By not setting a cookie domain, subdomains (possibly including www) will not see cookies
		*/
		if ($delete) {
			# javascript cookie used to determine if we are logged in or not
			setcookie('jsession', '', time() - 6000, BASE_URL, '');

			# Return the status of the real auth cookie
			return setcookie('hsession', '', time() - 6000, BASE_URL, '', false, true);
		}
		else {
			# javascript cookie used to determine if we are logged in or not
			setcookie('jsession', '1', time() + 60*60*24*30, BASE_URL, '');

			# Return the status of the real auth cookie
			return setcookie('hsession', $session . $user_id, time() + 60*60*24*30, BASE_URL, '', false, true);
		}
	}
	public static function generate_password_salt($rounds = 12) {

		// Setup the salt config
		$salt = sprintf('$2a$%02d$', $rounds); 

		// Generate the salt bytes
		$salt .= self::generate_crypto_bytes(16, true);

		return $salt;
	}

	/*
	Params:
	$length = length of the intended output in bytes
	$encoded = encode in modified base64 or return bytes
	*/
	public static function generate_crypto_bytes($length, $encoded=true) {

		$bytes = '';

		// Try to use openssl_random_pseudo_bytes if we're on Linux (Most secure)
		if(function_exists('openssl_random_pseudo_bytes') && (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL slow on Win
			$bytes = openssl_random_pseudo_bytes($length);
		}

		// If openssl_random_pseudo_bytes didn't work out then try a linux command
		if($bytes === '' && is_readable('/dev/urandom') && ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
			$bytes = fread($hRand, $length);
			fclose($hRand);
		}

		// Internal number generator (Not very secure)
		if(strlen($bytes) < $length) {
			$bytes = '';

			// Seed the generator
			if(function_exists('getmypid')) {
				$randomState = getmypid();
				$randomState .= getmypid();
			}

			for($i = 0; $i < $length; $i += 16) {
				$randomState = md5(microtime() . $randomState);

				if (PHP_VERSION >= '5') {
					$bytes .= md5($randomState, true);
				} 
				else {
					$bytes .= pack('H*', md5($randomState));
				}
			}

			$bytes = substr($bytes, 0, $length);
		}

		if (!$encoded) {
			return $bytes;
		}
		else {
			// The following is code from the PHP Password Hashing Framework
			$itoa64 = '_-ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

			$output = '';
			$i = 0;

			$byte_len = strlen($bytes)-1;
			do {
				if (++$i > $byte_len) {break;}
				$c1 = ord($bytes[$i]);
				$output .= $itoa64[$c1 >> 2];
				$c1 = ($c1 & 0x03) << 4;

				if (++$i > $byte_len) {break;}
				$c2 = ord($bytes[$i]);
				$c1 |= $c2 >> 4;
				$output .= $itoa64[$c1];
				$c1 = ($c2 & 0x0f) << 2;

				if ($i > $byte_len) {break;}
				$c2 = ord($bytes[$i]);
				$c1 |= $c2 >> 6;
				$output .= $itoa64[$c1];
				$output .= $itoa64[$c2 & 0x3f];
			} while (1);

			return $output;			
		}

	}

}