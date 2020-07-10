<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller\Api;


use Bbs\Model\ArticleModel;
use Bbs\Service\ArticleService;
use Bbs\Service\LikeService;
use Bbs\Service\ReplyService;

class MyArticleController extends BaseController
{
    /**
     * 发布帖子
     */
    public function addArticle()
    {
        $section_id = I('post.section_id');
        $title = I('post.title');
        $content = I('post.content');
        if(!$section_id){
            $this->ajaxReturn(self::createReturn(false, null, '请选择所属版块'));
        }
        if(!$title){
            $this->ajaxReturn(self::createReturn(false, null, '请输入标题'));
        }
        if(!$content){
            $this->ajaxReturn(self::createReturn(false, null, '请输入内容'));
        }
        $res = ArticleService::createArticle([
            'section_id' => $section_id,
            'title' => $title,
            'content' => $content,
            'user_id' => $this->bbs_user_id,
            'review_status' => ArticleModel::REVIEW_STATUS_WAIT,
            'is_show' => 1,
            'create_time' => time(),
        ]);
        $this->ajaxReturn($res);
    }

    /**
     * 发布帖子
     */
    public function editArticle()
    {
        $id = I('post.id');
        $section_id = I('post.section_id');
        $title = I('post.title');
        $content = I('post.content');
        if(!$section_id){
            $this->ajaxReturn(self::createReturn(false, null, '请选择所属版块'));
        }
        if(!$title){
            $this->ajaxReturn(self::createReturn(false, null, '请输入标题'));
        }
        if(!$content){
            $this->ajaxReturn(self::createReturn(false, null, '请输入内容'));
        }
        $res = ArticleService::updateArticle($id, [
            'section_id' => $section_id,
            'title' => $title,
            'content' => $content,
            'review_status' => ArticleModel::REVIEW_STATUS_WAIT,
        ]);
        $this->ajaxReturn($res);
    }

    /**
     * 获取我的帖子
     */
    public function getArticleList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $where = [
            'is_show' => ArticleModel::SHOW_YES,
            'user_id' => $this->bbs_user_id
        ];
        $order = '`create_time` DESC'; //最新排序
        $res = ArticleService::getArticleList($where, $order, $page, $limit);
        foreach($res['data']['items'] as &$item){
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['content'] = htmlspecialchars_decode($item['content']);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 获取我的帖子详情
     */
    public function getArticle(){
        $id = I('get.id');
        if(!$id){
            $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
        }
        $res = ArticleService::getArticleById($id);
        if($res['status']){
            $res['data']['create_time'] = date('Y-m-d H:i', $res['data']['create_time']);
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 获取我的收藏列表
     */
    public function getCollectList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $where = ['is_show' => ArticleModel::SHOW_YES];
        $article_ids = D('Bbs/ArticleCollect')->where(['user_id' => $this->bbs_user_id])->order('`create_time` DESC')->getField('article_id', true);
        if($article_ids){
            $where['id'] = ['IN', $article_ids];
            $order = 'FIELD(id,'.implode(',', $article_ids).')';
        }else{
            $where['_string'] = '1=2';
            $order = '`create_time` DESC';
        }
        $res = ArticleService::getArticleList($where, $order, $page, $limit);
        foreach($res['data']['items'] as &$item){
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['content'] = htmlspecialchars_decode($item['content']);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 获取我的回复列表
     */
    public function getReplyList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $where = [
            'user_id' => $this->bbs_user_id,
            'is_show' => 1
        ];
        $items = D('Bbs/ArticleReply')->where($where)->order('`create_time` DESC')->page($page, $limit)->select() ?: [];
        $total_items = D('Bbs/ArticleReply')->where($where)->count();
        foreach($items as &$item){
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
            $item['content'] = htmlspecialchars_decode($item['content']);
            $item['article_title'] = D('Bbs/Article')->where(['id' => $item['article_id']])->getField('title');
            if($item['pid']){
                $p_reply = D('Bbs/ArticleReply')->field('content,user_id,create_time')->where(['id' => $item['pid']])->find();
                $item['p_content'] = htmlspecialchars_decode($p_reply['content']);
                $item['p_user_name'] = M('BbsUser')->where(['id' => $p_reply['user_id']])->getField('name');
                $item['p_create_time'] = date('Y-m-d H:i', $p_reply['create_time']);
            }
        }
        $data = [
            'page' => $page,
            'limit' => $limit,
            'items' => $items,
            'total_items' => $total_items,
            'total_pages' => ceil($total_items/$limit)
        ];
        $this->ajaxReturn(self::createReturn(true, $data));
    }

}