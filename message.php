<?php define('TITLE','我的消息');?>
<?php include "head.php" ?>
<?php
if (!isset($_GET['code'])){
    header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92e7561eb7728f83&redirect_uri=http://cece.appgame.com/wp-content/weixin/message.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"); 
    //确保重定向后，后续代码不会被执行 
    exit;
}
?>
    <div id="header">
        <h1><?php echo TITLE;?></h1>
    </div>
    <div class="mt50">No Message</div>
	<p id="status"></p>
<?php include "foot.php" ?>
