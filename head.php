<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx92e7561eb7728f83", "896c1c68dad160c7bc02f199ff24d55b");
$signPackage = $jssdk->GetSignPackage();
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo TITLE;?></title>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo filemtime("style.css");?>" />
</head>
<body>