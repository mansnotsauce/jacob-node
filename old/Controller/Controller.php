<?php 

	if(function_exists($fn))
	{					
		$tmp = $fn();							
		$data = array_merge($data,$tmp);		
	}
	else
	{		
		if(getUriSegment(1)=="profile")
		{			
			$tmp = profile();
			$data = array_merge($data,$tmp);			
		} else {
			header("Location: /");
			$data['title'] = "Page Not Found!";
			$data['path'] = "includes/404";
		}			
	}


include('View/'.$data['path'].".php");
?>