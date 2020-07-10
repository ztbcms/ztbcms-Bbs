<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/10
 * Time: 15:02
 */

namespace Bbs\Behavior;

use Common\Behavior\BaseBehavior;

/**
 * 回复
 * Class BbsReplyArticleBehavior
 * @package Bbs\Behavior
 */
class BbsReplyArticleBehavior extends BaseBehavior
{

    public function run(&$param) {
        $article_id = $param['article_id'];
        $user_id = $param['user_id'];
        $reply_id = $param['reply_id'];

        //增加回复数
        if($reply_id){
            $reply_num = D('Bbs/ArticleReply')->where(['article_id' => $article_id, 'pid' => $reply_id])->count();
            D('Bbs/ArticleReply')->where(['id' => $reply_id])->save(['reply_num' => $reply_num]);
        }else{
            $reply_num = D('Bbs/ArticleReply')->where(['article_id' => $article_id])->count();
            D('Bbs/Article')->where(['id' => $article_id])->save(['reply_num' => $reply_num]);
            //热度(评论数+查看数+点赞数)
            $article = D('Bbs/Article')->field('reply_num,read_num,like_num')->where(['id' => $article_id])->find();
            $hot_num = $article['reply_num']+$article['read_num']+$article['like_num'];
            D('Bbs/Article')->where(['id' => $article_id])->save(['hot_num' => $hot_num]);
        }
        //获得积分
        $read_article_integral = M('BbsConfig')->where(['key' => 'reply_article_integral'])->getField('value');
        if($read_article_integral > 0){
            //TODO
        }
    }
}