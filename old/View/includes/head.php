<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">    
    <title><?php echo $data['menu-link']; ?> - PWR Station</title>
    <!--favicon-->
	<link rel="icon" href="https://horizonpwr.com/wp-content/uploads/2019/02/cropped-horizon-pwr-favicon-32x32.png" sizes="32x32" />
	<link rel="icon" href="https://horizonpwr.com/wp-content/uploads/2019/02/cropped-horizon-pwr-favicon-192x192.png" sizes="192x192" />
	<link rel="apple-touch-icon-precomposed" href="https://horizonpwr.com/wp-content/uploads/2019/02/cropped-horizon-pwr-favicon-180x180.png" />
	<meta name="msapplication-TileImage" content="https://horizonpwr.com/wp-content/uploads/2019/02/cropped-horizon-pwr-favicon-270x270.png" />

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/default.css">
    <?php if($data["menu-link"]=="Log In" || $data["menu-link"]=="Service Temporarily Unvailable"): ?>
        <link rel="stylesheet" type="text/css" href="/assets/css/login-style.css">
    <?php endif; ?>
</head>