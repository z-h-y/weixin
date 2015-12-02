<?php define("TITLE", "个人中心"); 
date_default_timezone_set("PRC");
$time = date('Y-m-d h:i:s',time());
?>
<?php include "head.php"; ?>
<?php
if (isset($_GET['code'])){
    $code = $_GET['code'];
    $output = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx92e7561eb7728f83&secret=896c1c68dad160c7bc02f199ff24d55b&code=".$code."&grant_type=authorization_code");
    $res = json_decode($output, true);
    $access_token = $res['access_token'];
    $refresh_token = $res['refresh_token'];
    $openid = $res['openid'];
    $output = file_get_contents("https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid);
    $res = json_decode($output, true);
}else{
    header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92e7561eb7728f83&redirect_uri=http://cece.appgame.com/wp-content/weixin/sign.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"); 
    //确保重定向后，后续代码不会被执行 
    exit;
}
?>
    <div id="header">
        <h1><?php echo TITLE;?></h1>
    </div>
    <div id="user_info" class="mt50">
        <img src="<?php echo $res['headimgurl']; ?>" />
        <a href="http://cece.appgame.com/wp-content/weixin/user_homepage.php?name=<?php echo $res['nickname']; ?>&img=<?php echo $res['headimgurl']; ?>&time=<?php echo $time; ?>">个人主页</a>
        <h2><?php echo $res['nickname']; ?></h2>
    </div>
    <ul class="user_list">
        <li><a href="">账号设置</a></li>
        <li><a href="">绑定手机</a></li>
        <li><a href="">我的好友</a></li>
        <li><a href="">礼包中心</a></li>
        <li><a href="">徽章中心</a></li>
        <li><a href="">反馈意见</a></li>
        <li><a href="">关于</a></li>
    </ul>
    <p id="status"></p>
<?php include "foot.php"; ?>
