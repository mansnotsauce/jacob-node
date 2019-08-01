<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['SERVER_NAME'] == 'onboarding.horizonpwr.com') { # Production Settings
	define('FULL_BASE_URL', 'https://onboarding.horizonpwr.com/');
	define('BASE_URL', '/');
}
else if ($_SERVER['SERVER_NAME'] == 'pwrstation.horizonpwr.com') { # Production Settings
	define('FULL_BASE_URL', 'https://pwrstation.horizonpwr.com/');
	define('BASE_URL', '/');
	define("SALESFORCE_USERNAME", "horizonpwr.salesforce@gmail.com");
	define("SALESFORCE_PASSWORD", "Horizon#2019");		
	define("SALESFORCE_REDIRECT_URI", "https://pwrstation.horizonpwr.com/api/oauth_callback");	
	define("SALESFORCE_LOGIN_URI", "https://login.salesforce.com");
	define("SALESFORCE_CLIENT_ID", "3MVG9mclR62wycM2QCvilyDrGjksKzvfd8edH6Z3biy5P_D2Xjr3OIh9o2QB5Xq4TicqnSsDZmNRjCaxOrS35");
	define("SALESFORCE_CLIENT_SECRET", "60E55F693A045C0308F4B7540D43C7AC07D787BD71D858A81940202FD860DD7D");
	define("IMAGE_BASE_URL","");
}
else if ($_SERVER['SERVER_NAME'] == 'devpwr.horizonpwr.com') { # Production Settings
	define('FULL_BASE_URL', 'https://devpwr.horizonpwr.com/');
	define('BASE_URL', '/');
	define("SALESFORCE_USERNAME", "horizonpwr.salesforce@gmail.com.website2");
	define("SALESFORCE_PASSWORD", "Horizon#2019");
	define("SALESFORCE_REDIRECT_URI", FULL_BASE_URL."api/oauth_callback");
	define("SALESFORCE_LOGIN_URI", "https://test.salesforce.com");
	define("SALESFORCE_CLIENT_ID", "3MVG9ahGHqp.k2_zSb4k9Z3Cpk9HPLe1e2jztmTWH2EFkdCPxFZpsx9bMcBVGwetCT_qr4omj9JZyjRV9sNG5");
	define("SALESFORCE_CLIENT_SECRET", "5FD3221194CE9FFB81540048B78322C37419A52C52B37254D58C73BEF52B1670");
	define("IMAGE_BASE_URL","");
}
else { # Localhost
	define('FULL_BASE_URL', 'http://pwrstation/');
	define('BASE_URL', '/');
	define("SALESFORCE_USERNAME", "horizonpwr.salesforce@gmail.com.website2");
	define("SALESFORCE_PASSWORD", "Horizon#2019");
	define("SALESFORCE_REDIRECT_URI", FULL_BASE_URL."api/oauth_callback");
	define("SALESFORCE_LOGIN_URI", "https://test.salesforce.com");
	define("SALESFORCE_CLIENT_ID", "3MVG9ahGHqp.k2_zSb4k9Z3Cpk9HPLe1e2jztmTWH2EFkdCPxFZpsx9bMcBVGwetCT_qr4omj9JZyjRV9sNG5");
	define("SALESFORCE_CLIENT_SECRET", "5FD3221194CE9FFB81540048B78322C37419A52C52B37254D58C73BEF52B1670");
	define("IMAGE_BASE_URL","");
}

define('PATH_ROOT', str_replace("\\", '/', realpath(dirname(__FILE__) . '/../')) . '/');
define('PATH_FILES', PATH_ROOT . 'files/');

include(PATH_ROOT.'config.php'); # Generated by oAuth
include('horizon_salesforce.php');
include('horizon_login.php');
include('files.php'); # For uploading files

require('PHPMailer-master/src/Exception.php');
require('PHPMailer-master/src/PHPMailer.php');
require('PHPMailer-master/src/SMTP.php');