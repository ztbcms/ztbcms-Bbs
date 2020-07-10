<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller\Api;

use Bbs\Service\LikeService;
use Bbs\Service\ReplyService;

class ReplyController extends BaseController
{
    /**
     * 回复
     */
    public function doReply(){
        $article_id = I('post.article_id');
        $pid = I('post.id');
        $content = I('post.content');
        $res = ReplyService::doReply($this->bbs_user_id, $article_id, $pid, $content);
        $this->ajaxReturn($res);
    }

    /**
     * 点赞
     */
    public function doLike(){
        $article_id = I('post.article_id');
        $id = I('post.id');
        $res = LikeService::doLike($this->bbs_user_id, $article_id, $id);
        $this->ajaxReturn($res);
    }

    /**
     * 点赞
     */
    public function unLike(){
        $article_id = I('post.article_id');
        $id = I('post.id');
        $res = LikeService::unLike($this->bbs_user_id, $article_id, $id);
        $this->ajaxReturn($res);
    }
}