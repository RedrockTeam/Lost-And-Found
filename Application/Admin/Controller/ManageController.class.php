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

        $searchName = I('search');
        if ($searchName !== "") {   //按nickname查询

            $where['status'] = 0;
            $where['pro_name'] = array('like','%'.$searchName.'%');

            $count = M('product_list')
                ->where($where)
                ->count();

            $Page = new \Think\Page($count,10);// 实例化分页类
            $show = $Page->show();// 分页显示输出
            $list = M('product_list')
                ->limit($Page->firstRow.','.$Page->listRows)
                ->where($where)
                ->select();
        }else{

            $count = M('product_list')
                ->where('status = 0')
                ->count();

            $Page = new \Think\Page($count,10);// 实例化分页类
            $show = $Page->show();// 分页显示输出
            $list = D('product_list')
                ->where('status = 0')
//                ->order(array('stu_num' => 'desc'))
                ->limit($Page->firstRow.','.$Page->listRows)
                ->select();
        }

        $this->assign('list',$this->_getList($list));// 赋值数据集
        $this->assign('show',$show);// 赋值分页输出

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
            $this->redirect('Manage/index', array('cate_id' => 2), 0, '删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}