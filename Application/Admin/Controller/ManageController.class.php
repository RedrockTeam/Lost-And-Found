<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/6
 * Time: 下午4:08
 */
namespace Admin\Controller;
use Think\Controller;

class ManageController extends CommonController{

    /**
     * 管理发布信息页面
     */
    public function index() {
        $list = M('product_list')->select();

        $this->assign('list', $this->_getList($list));
        $this->display();
    }

    /**
     * 把种类信息添加到数组里
     * @param $list 原数组
     * @return array 添加了种类信息的数组
     */
    private function _getList($list) {
        $reList = [];
        foreach($list as $k => $v){
            $condition['kind_id'] = $v['pro_kind_id'];
            $kindName = M('product_kinds')->where($condition)->find()['kind_name'];

            $v['kind_name'] = $kindName;
            array_push($reList, $v);
        }

        return $reList;
    }

    /**
     * 处理删除发布的信息
     */
    public function deleteHandle(){
        $r = M('product_list')->where(array('pro_id' => I('uid')))->delete();
        if($r == 1){
            $this->success('删除成功!');
        }else{
            $this->error('删除失败');
        }
    }
}