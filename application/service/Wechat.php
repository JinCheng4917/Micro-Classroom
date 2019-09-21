<?php
namespace app\service;
use think\Controller;
use think\facade\Request;
class Wechat extends Controller{
    protected $appid='wxec9127081c13831e';
    protected $appsecret = 'a6c9b838533981f7d3dacc9501baf1e8';
 
    // 获取code，当我们访问这个方法，他在跳转之后会将code带到我们的redirect_url页面
    public function accredit($redirect_url){
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->appid}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        $this->redirect($url);
    }
    /**
     * @param $code
     * @return bool|string
     */
    // 利用code获取特殊的access_token
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
    // 利用获取的特殊access_token来获取用户信息
    public function getWeChatUserInfo($access_token,$openid){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $output = file_get_contents($url);
        $weChatUserInfo= json_decode($output,true);
        return $weChatUserInfo;
    }



}