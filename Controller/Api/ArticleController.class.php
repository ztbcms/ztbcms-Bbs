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
use Bbs\Service\CollectService;
use Bbs\Service\LikeService;
use Bbs\Service\ReplyService;

class ArticleController extends BaseController
{
    /**
     * 获取帖子列表
     */
    public function getArticleList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $section_id = I('get.section_id');
        $search = I('get.search');
        if(!$section_id){
            $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
        }
        $where = ['is_show' => ArticleModel::SHOW_YES, 'review_status' => ArticleModel::REVIEW_STATUS_SUCCESS];
        if($section_id){
            $where['section_id'] = $section_id;
        }
        if($search){
            //TODO 优化搜索
            $where['title|content'] = ['LIKE', '%'.$search.'%'];
        }
        $order = '`sort` DESC'; //置顶排序
        $sort = I('get.sort');
        if($sort == 'new'){
            $order .= ',`create_time` DESC'; //最新排序
        }elseif($sort == 'hot'){
            $order .= ',`hot_num` DESC'; //热门排序
        }
        $res = ArticleService::getArticleList($where, $order, $page, $limit);
        foreach($res['data']['items'] as &$item){
            $item['user_name'] = M('BbsUser')->where(['id' => $item['user_id']])->getField('name');
            $item['user_avatar'] = M('BbsUser')->where(['id' => $item['user_id']])->getField('avatar');
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 获取帖子详情
     */
    public function getArticle()
    {
        $id = I('get.id');
        if(!$id){
            $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
        }
        $res = ArticleService::getArticleById($id);
        if($res['status']){
            if($res['data']['review_status'] != ArticleModel::REVIEW_STATUS_SUCCESS){
                $this->ajaxReturn(self::createReturn(false, null, '该帖子未审核通过'));
            }
            $param = ['user_id' => $this->bbs_user_id, 'article_id' => $id];
            tag('bbs_read_article', $param);
            $res['data']['user_name'] = M('BbsUser')->where(['id' => $res['data']['user_id']])->getField('name');
            $res['data']['user_avatar'] = M('BbsUser')->where(['id' => $res['data']['user_id']])->getField('avatar');
            $res['data']['create_time'] = date('Y-m-d H:i', $res['data']['create_time']);
            $res['data']['content'] = htmlspecialchars_decode($res['data']['content']);
            $res['data']['is_like'] = LikeService::isLike($this->bbs_user_id, $id, 0);
            $res['data']['is_collect'] = CollectService::isCollect($this->bbs_user_id, $id);
            $reply_list = ReplyService::getReplyList($id, 0, 1, 10)['data'];
            foreach($reply_list['items'] as &$reply){
                $reply['is_like'] = LikeService::isLike($this->bbs_user_id, $id, $reply['id']);
            }
            $res['data']['reply_list'] = $reply_list;
        }
        $this->ajaxReturn($res);
    }

    /**
     * 获取帖子回复列表
     */
    public function getArticleReplyList()
    {
        $page = I('get.page', 1);
        $limit = I('get.limit', 20);
        $article_id = I('get.article_id');
        $pid = I('get.pid');
        $sort = I('get.sort');
        if(!$article_id){
            $this->ajaxReturn(self::createReturn(false, null, '参数错误'));
        }
        $res = ReplyService::getReplyList($article_id, $pid, $page, $limit, $sort);
        foreach($res['data']['items'] as &$item){
            $item['is_like'] = LikeService::isLike($this->bbs_user_id, $article_id, $item['id']);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 回复
     */
    public function doReply(){
        $article_id = I('post.article_id');
        $content = I('post.content');
        $res = ReplyService::doReply($this->bbs_user_id, $article_id, 0, $content);
        $this->ajaxReturn($res);
    }

    /**
     * 点赞
     */
    public function doLike(){
        $article_id = I('post.article_id');
        $res = LikeService::doLike($this->bbs_user_id, $article_id, 0);
        $this->ajaxReturn($res);
    }

    /**
     * 取消点赞
     */
    public function unLike(){
        $article_id = I('post.article_id');
        $res = LikeService::unLike($this->bbs_user_id, $article_id, 0);
        $this->ajaxReturn($res);
    }

    /**
     * 收藏
     */
    public function doCollect()
    {
        $article_id = I('post.article_id');
        $res = CollectService::doCollect($this->bbs_user_id, $article_id);
        $this->ajaxReturn($res);
    }

    /**
     * 取消收藏
     */
    public function unCollect()
    {
        $article_id = I('post.article_id');
        $res = CollectService::unCollect($this->bbs_user_id, $article_id);
        $this->ajaxReturn($res);
    }
}