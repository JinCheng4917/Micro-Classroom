<?php
namespace app\service\controller;
use think\Controller;
class WechatController extends Controller{
    protected $appid='wxec9127081c13831e';
    protected $appsecret = 'a6c9b838533981f7d3dacc9501baf1e8';
 
    public function accredit($redirect_url){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
        $this->redirect($url);
    }
    /**
     * @param $code
     * @return bool|string
     */
    public function getAccessToken($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
        $res = file_get_contents($url); //获取文件内容或获取网络请求的内容
        $access_token = json_decode($res,true);
        return $access_token;
    }
    /**
     * 获取用户信息
     * @param unknown $openid
     * @param unknown $access_token
     * @return unknown
     */
    public function getWeChatUserInfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $output = file_get_contents($url);
        $weChatUserInfo = json_decode($output,true);
        return $weChatUserInfo;
    }
}