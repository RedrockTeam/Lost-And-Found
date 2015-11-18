<?php
namespace Home\Controller;


/**
 * 第一次访问页面控制器
 */
class FirstVisitController extends CommonController {

    /**
     * 第一次访问首页
     */
    public function index(){
        $userInfo = $this->_getUserInfo(session('openid'));
        $userInfo['realName'] = $this->_getRealName();
        $userInfo['stu_num'] = $this->_getStuNum();

        $this->ajaxReturn(array(
            'user_info'=> $userInfo
        ));
    }

    /**
     * 处理更新的信息
     */
    public function handleInfo(){

    }
}