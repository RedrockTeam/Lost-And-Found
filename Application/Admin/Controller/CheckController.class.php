<?php
/**
 * Created by PhpStorm.
 * User: firk1n
 * Date: 15/11/6
 * Time: 下午4:01
 */
namespace Admin\Controller;
use Think\Controller;

class CheckController extends CommonController{

    /**
     * 信息审核页面
     */
    public function index() {
        $list = M('product_list')->where('check_state = 0')->select();

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
     * 审核信息
     */
    public function checkHandle() {
        $data = array(
            'pro_id' => I('get.uid'),
            'check_state' => 1
        );
        $r = M('product_list')->save($data);
        if($r == 1){
            $this->redirect('Check/index', array('cate_id' => 2), 0, '审核成功');
        }else{
            $this->error('审核失败');
        }
    }
}