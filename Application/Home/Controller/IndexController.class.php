<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {

    // 微信curl主地址
    private $wxUrl = 'http://hongyan.cqupt.edu.cn/MagicLoop/index.php?s=/addon/Api/Api/';

    /**
     * 微信端失物招领首页
     */
    public function index(){

        // 记得检查用户是否存在于user_info表中, 然后存储user_id的值, 在发布的时候这个值要存到product_list中
        //

        // 测试用
        $openId = 'ouRCyjpvLulo8TzHsMmGY2bTP13c';

//        $code = I('get.code');
//        $openId = $this->_getOpenId($code);
        $isBind = $this->_checkBind($openId);
        $userInfo = $this->_getUserInfo($openId);
        $care = $this->_checkCareXBS($openId);

        $lost = M('product_list')->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
                                ->where('lost_or_found = 0')
                                ->order('pro_id desc')
                                ->limit(5)
                                ->select();

        $found = M('product_list')->field('pro_name, pro_description, create_time, pro_kind_id, pro_user_id')
                                ->where('lost_or_found = 1')
                                ->order('pro_id desc')
                                ->limit(5)
                                ->select();

        $this->ajaxReturn(array(
            'user_info'=> $userInfo,
            'lost'=> getList($lost),
            'found'=> getList($found)
        ),'json');
    }

    /**
     * 检查是否绑定学号
     * @param $openId
     * @return mixed 绑定的状态 1 or 0
     */
    private function _checkBind($openId) {
        return $this->_curl($openId, "bindVerify")['status'];
    }

    /**
     * 检查是否关注重邮小帮手
     * @param $openId
     * @return mixed 关注的话返回200
     */
    private function _checkCareXBS($openId) {
        $url = 'openidVerify';
        $info = $this->_curl($openId, $url);

        return $info['status'];
    }

    /**
     * 获取用户的微信头像和微信昵称
     * @param $openId
     * @return array 返回用户昵称和头像信息
     */
    private function _getUserInfo($openId) {
        $res = $this->_curl($openId, "userInfo");
        $headImageUrl = substr($res['data']['headimgurl'], 0, -1) . "64";
        $nickname = $res['data']['nickname'];
        $result = array('nickName' => $nickname, 'headImageUrl' => $headImageUrl);

        return $result;
    }

    /**
     * 通过code获取openid
     * @param $code 参数code
     * @return mixed 返回openid的值
     */
    private function _getOpenId($code) {
        $info = $this->_curl(null, 'webOauth', $code);

        return $info['data']['openid'];
    }

    /**
     * 自用 curl通用函数
     * @param null $openId 微信端的openId
     * @param $uri 目标地址
     * @param null $code
     * @return mixed 返回的结果
     */
    private function _curl($openId=null, $uri, $code=null) {
        $timestamp = time();
        $string = "";
        $arr = "abcdefghijklmnopqistuvwxyz0123456789ABCDEFGHIGKLMNOPQISTUVWXYZ";
        for ($i=0; $i<16; $i++) {
            $y = rand(0,41);
            $string .= $arr[$y];
        }
        $secret = sha1(sha1($timestamp).md5($string).'redrock');

        if($code == null){
            $post_data = array (
                "timestamp" => $timestamp,
                "string" => $string,
                "secret" => $secret,
                "openid" => $openId,
                "token" => "gh_68f0a1ffc303"
            );
        }else if($openId == null){
            $post_data = array (
                "timestamp" => $timestamp,
                "string" => $string,
                "secret" => $secret,
                "token" => "gh_68f0a1ffc303",
                'code' => $code
            );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->wxUrl.$uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $return = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($return, true);

        return $result;
    }
}