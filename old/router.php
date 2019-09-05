<?php 
include('Model/horizon.php');
include('Model/pwrmodel.php');
$fn = "index";
$path;

function getUriSegments() {
	return explode("/", parse_url(str_replace("-", "_", $_SERVER['REQUEST_URI']), PHP_URL_PATH));
}

function getUriSegment($n) {		
	$segs = getUriSegments();
	return count($segs)>0&&count($segs)>=($n-1)?$segs[$n]:'';
}

if(getUriSegment(1)!="")
{
	if(count(getUriSegments())>=3)
	{
		$fn = getUriSegment(2);
		if($fn=="" || $fn == NULL)
			$fn = "index";
	}	
}

switch(getUriSegment(1))
{
	case "api":		
		$controller = "APIController";
		break;
	case "admin":	
		$controller = "AdminController";
		break;
	default:
		if(getUriSegment(1)!="")
			$fn=getUriSegment(1);
		
		$controller = "HomeController";
		break;
}
include('Controller/'.$controller.'.php');
include('Controller/Controller.php');
?>