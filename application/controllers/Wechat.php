<?php
class Wechat extends MY_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->helper('myfun');
		$this->load->library('form_validation');
		$this->load->library('WechatCallbackApi');
    }
	
	public function token(){
		
		define("TOKEN", "qy201708");

		if (!isset($_GET['echostr'])) {
			$this->responseMsg();
		}else{
			$this->valid();
		}
			
	}
	
	
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = 'qy201708';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveText($object)
    {
        $funcFlag = 0;
        $contentStr = "你发送的内容为：".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        return $resultStr;
    }
    
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = $this->getReplySubscribe();
            case "unsubscribe":
                break;
            case "CLICK":
                switch ($object->EventKey)
                {

                    default:
                        $contentStr[] = array("Title" =>"默认菜单回复", 
                        "Description" =>"欢迎来到栖约官方微信公众号", 
                        "PicUrl" =>"https://mmbiz.qlogo.cn/mmbiz_jpg/6Wic9NUSU7DzncxI61qibiaMHqElFDIUsZNcAUGdgqxrNrvYocYiaia1r3p8lDs7wl7ZweV3tndFfNnlJSqgib74gQDw/0?wx_fmt=jpeg", 
                        "Url" =>"https://mp.weixin.qq.com/s?__biz=MzI2NDU0OTQxMw==&tempkey=OTI3X0o0UitQazV5VUlnNW15Q3JfMlFULTl3MTQwTFl0eVdVUFQ4Z3hBSFJBbTk2S1R5QUxHWEFLbmRaRWJ2dUgtN0V5el9tZzJ0dXlfa0wyamhHNGl6a2U0cjBhR2IyRFZXdTIzNWRYTUxSbzBwOFpJVXpOZFZ5U1pOMTdPWlRLOWVONGRrVS02dkctTnJTYnRqdDMwdWxLQWVoSVZFeEZNdWxYelpyZ0F%2Bfg%3D%3D&chksm=6aaba4e45ddc2df226704febd47c4963c166865e23777e3c094f983872935a9f4d1605cd1ab2#rd");
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

    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    private function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        //首条标题28字，其他标题39字
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
<FuncFlag>%s</FuncFlag>
</xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }
	
	
	public function getReplySubscribe(){
		$row = $this->db->order_by('id desc')->get('wechat_reply_subscribe')->row_array();
		return $row['content'];	
	}
	
    
}