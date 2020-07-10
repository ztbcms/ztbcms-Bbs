<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller\Api;

use Bbs\Model\SectionModel;
use Bbs\Service\SectionService;

class SectionController extends BaseController
{
    /**
     * 获取版块列表
     */
    public function getSectionList()
    {
        $pid = I('get.pid');
        $where = ['is_show' => SectionModel::SHOW_YES];
        if($pid){
            $where['pid'] = $pid;
        }else{
            $where['pid'] = 0;
        }
        $order = '`sort` DESC,`create_time` DESC';
        $res = SectionService::getSectionList($where, $order, 1, 999);
        $this->ajaxReturn($res);
    }

    /**
     * 获取板块详情
     */
    public function getSection()
    {
        $id = I('get.id');
        if(!$id){
            $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
        }
        $res = SectionService::getSectionById($id);
        $this->ajaxReturn($res);
    }
}