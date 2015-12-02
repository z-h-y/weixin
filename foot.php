   <div id="footer">
        <ul class="cf">
            <li><a href="http://cece.appgame.com/wp-content/weixin/game.php"><span>游戏</span></a></li>
            <li><a href="http://cece.appgame.com/wp-content/weixin/history.php"><span>玩过</span></a></li>
            <li><a href="http://cece.appgame.com/wp-content/weixin/active.php"><span>活动</span></a></li>
            <li><a href="http://cece.appgame.com/wp-content/weixin/message.php"><span>消息</span></a></li>
            <li><a href="http://cece.appgame.com/wp-content/weixin/sign.php"><span>个人</span></a></li>
        </ul>
    </div>
    <div id="foot_loading">
        <img src="http://h5.appgame.com/wp-content/uploads/2015/10/loading_64.gif" />
    </div>
<script type="text/javascript">
    var foot_li = 4;
    <?php switch (TITLE) {
        case '游戏':
            echo "foot_li = 0;";
            break;
        case '玩过':
            echo "foot_li = 1;";
            break;
        case '活动':
            echo "foot_li = 2;";
            break;
        case '我的消息':
            echo "foot_li = 3;";
            break;
        default:
            break;
    }?>
    window.onload = function(){
        var foot_tab = document.getElementById('footer').getElementsByTagName('a');
        foot_tab[foot_li].className = "active";
        for(var i=0;i<foot_tab.length;i++){
            foot_tab[i].onclick=function(){
                document.getElementById('foot_loading').style.display = 'block';
            }
        }
    };
</script>    
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    var last_update = 0;
    var SHAKE_THRESHOLD = 1000;
    var x=y=z=last_x=last_y=last_z=0;
	if (window.DeviceMotionEvent) {       
		window.addEventListener('devicemotion', deviceMotionHandler, false);
    } else {
        alert('你的手机太差了，扔掉买个新的吧。');
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
            //document.getElementById("status").innerHTML = "当前纬度:"+latitude+", 经度:"+longitude;
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