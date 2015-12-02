<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx92e7561eb7728f83", "896c1c68dad160c7bc02f199ff24d55b");
$signPackage = $jssdk->GetSignPackage();
?>
<html>
<head>
    <meta charset="utf-8" />
	<title>test</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
	<h1>
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
	    echo $res['nickname'];
	}else{
	    echo "NO CODE";
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92e7561eb7728f83&redirect_uri=http://cece.appgame.com/wp-content/oauth2.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"); 
        //确保重定向后，后续代码不会被执行 
        exit;
	}
	?>
	</h1>
	<p id="status"></p>
<script type="text/javascript">
    var last_update = 0;
    var SHAKE_THRESHOLD = 1000;
    var x=y=z=last_x=last_y=last_z=0;
	if (window.DeviceMotionEvent) {       
		window.addEventListener('devicemotion', deviceMotionHandler, false);
    } else {
        alert('你的手机太差了，扔掉买个新的吧。__方倍工作室');
    }
    function deviceMotionHandler(eventData) {
        var acceleration = eventData.accelerationIncludingGravity;
        var curTime = new Date().getTime();

        if ((curTime - last_update) > 100) {
            var diffTime = curTime - last_update;
            last_update = curTime;
            x = acceleration.x;
            y = acceleration.y;
            z = acceleration.z;
            var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
            var status = document.getElementById("status");

            if (speed > SHAKE_THRESHOLD) {
                doResult();
            }
            last_x = x;
            last_y = y;
            last_z = z;
        }
    }
    function doResult() {
    	//alert("摇一摇结束");
    }	
</script>
<script type="text/javascript">
wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
        // 所有要调用的 API 都要加到这个列表中
        'checkJsApi',
        'openLocation',
        'getLocation',
        'onMenuShareTimeline',
        'openProductSpecificView'
      ]
});
wx.ready(function () {
    wx.checkJsApi({
        jsApiList: [
            'getLocation'
        ],
        success: function (res) {
            // alert(JSON.stringify(res));
            // alert(JSON.stringify(res.checkResult.getLocation));
            if (res.checkResult.getLocation == false) {
                alert('你的微信版本太低，不支持微信JS接口，请升级到最新的微信版本！');
                return;
            }
        }
    });
    wx.getLocation({
        success: function (res) {
            var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
            var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
            var speed = res.speed; // 速度，以米/每秒计
            var accuracy = res.accuracy; // 位置精度
            document.getElementById("status").innerHTML = "当前纬度:"+latitude+", 经度:"+longitude;
        },
        cancel: function (res) {
            alert('用户拒绝授权获取地理位置');
        }
    });
    wx.onMenuShareTimeline({
        title: 'zzz', // 分享标题
        link: location.href, // 分享链接
        imgUrl: 'http://h5.appgame.com/wp-content/uploads/2015/08/person-lj-142x150.png', // 分享图标
        success: function () { 
            // 用户确认分享后执行的回调函数
        },
        cancel: function () { 
            // 用户取消分享后执行的回调函数
        }
    });
});

</script>
</body>
</html>
