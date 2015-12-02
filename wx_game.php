<?php
/*
    测试接口
    CopyRight 2014 All Rights Reserved
*/

define("TOKEN", "weixin_h5game");

require_once "jssdk.php";
$jssdk = new JSSDK("wx92e7561eb7728f83", "896c1c68dad160c7bc02f199ff24d55b");
$access_token = $jssdk->getAccessToken();

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            $result = "";
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveEvent($object)
    {
        switch ($object->Event)
        {
            case "subscribe":
                global $access_token;
                $openid = $object->FromUserName;
                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
                $output = https_request($url);
                $output = json_decode($output);
                $contentStr = "欢迎关注 ".$output->nickname;
                break;
            case "unsubscribe":
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "community":
                    case "activity":
                        $contentStr = "该功能正在建设当中";
                        break;
                    case "present":
                        $contentStr[] = array("Title" =>"礼包", 
                        "Description" =>"您正在使用的是礼包", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;               
                    default:
                        $contentStr[] = array("Title" =>"默认菜单回复", 
                        "Description" =>"您正在使用的是自定义菜单测试接口", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                }
                break;
            default:
                break;
        }
         if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    private function receiveText($object)
    {
        $keyword = trim($object->Content);
        $url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($keyword); 
        $output = file_get_contents($url);
        $content = json_decode($output, true);

        $result = $this->transmitNews($object, $content);
        return $result;
    }

    private function transmitText($object, $content)
    {
        if (!isset($content) || empty($content)){
            return "";
        }
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return $this->transmitText($object,"<a href=\"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92e7561eb7728f83&redirect_uri=http://cece.appgame.com/wp-content/weixin/oauth2.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect\">点击这里体验</a>");
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    private function logger($log_content)
    {
      
    }
}

$jsonmenu = '{
      "button":[
      {
            "type":"view",
            "name":"'.bytes_to_emoji(0x1F3AE).'玩游戏",
            "url":"http://h5.appgame.com"
      },
      {
           "name":"'.bytes_to_emoji(0x1F525).'热门游戏",
           "sub_button":[
            {
               "type":"view",
               "name":"奔跑吧兔子",
               "url":"http://h5.appgame.com/bpbtz"
            },
            {
               "type":"view",
               "name":"罗斯魔影",
               "url":"http://h5.appgame.com/luosimoying"
            },
            {
               "type":"view",
               "name":"足球小将",
               "url":"http://h5.appgame.com/zqxj"
            }]
       },
       {
           "name":"'.bytes_to_emoji(0x1F381).'活动",
           "sub_button":[
            {
               "type":"click",
               "name":"游戏礼包",
               "key":"present"
            },
            {
                "type":"click",
                "name":"最新活动",
                "key":"activity"
            },
            {
                "type":"click",
                "name":"玩家社区",
                "key":"community"
            },
            {
                "type":"view",
                "name":"个人中心",
                "url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx92e7561eb7728f83&redirect_uri=http://cece.appgame.com/wp-content/weixin/sign.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect"
            }]
       }]
 }';

$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
var_dump($result);

//字节转Emoji表情
function bytes_to_emoji($cp)
{
    if ($cp > 0x10000){       # 4 bytes
        return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
    }else if ($cp > 0x800){   # 3 bytes
        return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
    }else if ($cp > 0x80){    # 2 bytes
        return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
    }else{                    # 1 byte
        return chr($cp);
    }
}

function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

?>