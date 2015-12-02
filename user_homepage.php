<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>个人主页</title>
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo filemtime("style.css");?>" />
</head>
<body>
	<div id="header">
        <a href="javascript:void(0);" class="back" onclick="javascript:history.go(-1);">&lt;</a>
        <h1>个人主页</h1>
    </div>
    <div class="top_img mt50">
    	<img src="<?php echo $_GET['img']; ?>" />
    </div>
    <ul class="user_homepage_li">
    	<li>昵称：<span><?php echo $_GET['name']; ?></span></li>
    	<li>金币：<span>0</span></li>
    	<li>绑定手机号：<span></span></li>
    	<li>最近登录：<span><?php echo $_GET['time']; ?></span></li>
    </ul>
</body>
</html>