<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller;


use Bbs\Service\SectionService;

class SectionController extends BaseController
{
    /**
     * 版块列表
     */
    public function index()
    {
        $this->display();
    }

    /**
     * 获取版块列表
     */
    public function getSectionList()
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
            }
        }
        $res = SectionService::getSectionList($where, '`sort` DESC,`create_time` DESC', $page, $limit);
        foreach($res['data']['items'] as &$item){
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 版块
     */
    public function section()
    {
        $id = I('get.id');
        $sectionList = D('Bbs/Section')->field('id,name')->where(['pid' => 0, 'id' => ['NEQ', $id]])->select() ?: [];
        $this->assign('sectionList', $sectionList);
        $this->display();
    }

    /**
     * 获取版块
     */
    public function getSection()
    {
        $id = I('get.id');
        $res = SectionService::getSectionById($id);
        $this->ajaxReturn($res);
    }

    /**
     * 新增/编辑版块
     */
    public function addEditSection()
    {
        $data = I('post.');
        $id = $data['id'];
        unset($data['id']);
        if($id){
            $res = SectionService::updateSection($id, $data);
        }else{
            $data['is_show'] = 1;
            $data['create_time'] = time();
            $res = SectionService::createSection($data);
        }
        $this->ajaxReturn($res);
    }

    public function updateShow()
    {
        $id = I('post.id');
        $is_show = I('post.is_show');
        $res = SectionService::updateSection($id, ['is_show' => $is_show]);
        $this->ajaxReturn($res);
    }

    public function updateSort()
    {
        $id = I('post.id');
        $sort = I('post.sort');
        $res = SectionService::updateSection($id, ['sort' => $sort]);
        $this->ajaxReturn($res);
    }
}