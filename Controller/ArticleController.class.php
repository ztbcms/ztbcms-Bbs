<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller;


use Bbs\Model\ArticleModel;
use Bbs\Service\ArticleService;

class ArticleController extends BaseController
{
    public function index()
    {
        $sectionList = D('Bbs/Section')->field('id,name')->where(['pid' => ['NEQ', 0]])->select() ?: [];
        $this->assign('sectionList', $sectionList);
        $this->display();
    }

    /**
     * 获取帖子列表
     */
    public function getArticleList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $where = I('get.where', []);
        foreach($where as $k => $v){
            if($v === ''){
                unset($where[$k]);
                continue;
            }
            if($k == 'title'){
                $where[$k] = ['LIKE', '%'.$v.'%'];
            }
            if($k == 'section_id'){
                $where[$k] = $v;
            }
        }
        $res = ArticleService::getArticleList($where, '`sort` DESC,`create_time` DESC', $page, $limit);
        foreach($res['data']['items'] as &$item){
            $item['section_name'] = D('Bbs/Section')->where(['id' => $item['section_id']])->getField('name');
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['is_top'] = $item['sort'] > 0 ? '1' : '0';
        }
        $this->ajaxReturn($res);
    }

    public function setTop(){
        $id = I('post.id');
        $is_top = I('post.is_top');
        if($is_top){
            $res = ArticleService::updateArticle($id, ['sort' => time()]);
        }else{
            $res = ArticleService::updateArticle($id, ['sort' => 0]);
        }
        $this->ajaxReturn($res);
    }

    public function article()
    {
        $sectionList = D('Bbs/Section')->field('id,name')->where(['pid' => ['NEQ', 0]])->select() ?: [];
        $this->assign('sectionList', $sectionList);
        $this->display();
    }

    public function getArticle()
    {
        $id = I('get.id');
        $res = ArticleService::getArticleById($id);
        if($res['status']){
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
        }
        $this->ajaxReturn($res);
    }

    public function editArticle()
    {
        $id = I('post.id');
        $section_id = I('post.section_id');
        $content = I('post.content');
        $submit_status = I('post.submit_status');
        $updateData = ['section_id' => $section_id, 'content' => $content];
        if($submit_status == 2){
            $updateData['review_status'] = ArticleModel::REVIEW_STATUS_SUCCESS;
        }elseif($submit_status == 3){
            $updateData['review_status'] = ArticleModel::REVIEW_STATUS_ERROR;
            $updateData['review_remark'] = I('post.review_remark');
        }
        $res = ArticleService::updateArticle($id, $updateData);
        if($res['status'] && $submit_status == 2){
            $param = ['article_id' => $id];
            tag('bbs_add_article', $param);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 回复列表
     */
    public function replyList()
    {
        $this->display();
    }

    public function getReplyList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $where = I('get.where', []);
        foreach($where as $k => $v){
            if($v === ''){
                unset($where[$k]);
                continue;
            }
        }
        $where['is_show'] = 1;
        $items = D('Bbs/ArticleReply')->where($where)->order('`create_time` DESC')->page($page, $limit)->select() ?: [];
        $total_items = D('Bbs/ArticleReply')->where($where)->count();
        foreach($items as &$item){
            $item['user_name'] = M('BbsUser')->where(['id' => $item['user_id']])->getField('name');
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['content'] = $this->handleHtmlStr($item['content']);
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

    /**
     * 处理富文本(显示到列表中)
     * @param $html
     * @return string
     */
    protected function handleHtmlStr($html){
        $html = htmlspecialchars_decode($html);
        $preg = '/<img(.*?)>/';
        $html = preg_replace($preg, '[图片]', $html);
        $preg = '/<video(.*?)>/';
        $html = preg_replace($preg, '[视频]', $html);
        return strip_tags($html);
    }

    public function reply()
    {
        $this->display();
    }

    public function getReply()
    {
        $id = I('get.id');
        $data = D('Bbs/ArticleReply')->where(['id' => $id])->find();
        if($data){
            $data['content'] = htmlspecialchars_decode($data['content']);
            $this->ajaxReturn(self::createReturn(true, $data, '获取成功'));
        }else{
            $this->ajaxReturn(self::createReturn(false, null, '获取失败'));
        }
    }

    public function delReply()
    {
        $id = I('post.id');
        if($id){
            $res = D('Bbs/ArticleReply')->where(['id' => $id])->save(['is_show' => 0]);
            $this->ajaxReturn(self::createReturn(true, $res, '删除成功'));
        }else{
            $this->ajaxReturn(self::createReturn(false, null, '删除失败'));
        }
    }
}