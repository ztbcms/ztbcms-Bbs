<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller;


class UserController extends BaseController
{
    public function index(){
        $this->display();
    }

    public function getUserList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $where = I('get.where', []);
        foreach($where as $k => $v){
            if($v === ''){
                unset($where[$k]);
                continue;
            }
            if($k == 'name'){
                $where[$k] = ['LIKE', '%'.$v.'%'];
                continue;
            }
        }
        $items = M('BbsUser')->where($where)->order('`create_time` DESC')->page($page, $limit)->select() ?: [];
        $total_items = M('BbsUser')->where($where)->count();
        foreach($items as &$item){
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
        }
        $data = [
            'page' => $page,
            'limit' => $limit,
            'items' => $items,
            'total_items' => (int)$total_items,
            'total_pages' => ceil($total_items/$limit)
        ];
        $this->ajaxReturn(self::createReturn(true, $data, '获取成功'));
    }


}