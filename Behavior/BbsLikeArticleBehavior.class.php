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
 * 点赞
 * Class BbsLikeArticleBehavior
 * @package Bbs\Behavior
 */
class BbsLikeArticleBehavior extends BaseBehavior
{

    public function run(&$param) {
        $article_id = $param['article_id'];
        $reply_id = $param['reply_id'];

        $like_num = D('Bbs/ArticleLike')->where([
            'article_id' => $article_id,
            'reply_id' => $reply_id,
        ])->count();
        if($reply_id){
            D('Bbs/ArticleReply')->where(['id' => $reply_id])->save(['like_num' => $like_num]);
        }else{
            D('Bbs/Article')->where(['id' => $article_id])->save(['like_num' => $like_num]);
            //热度(评论数+查看数+点赞数)
            $article = D('Bbs/Article')->field('reply_num,read_num,like_num')->where(['id' => $article_id])->find();
            $hot_num = $article['reply_num']+$article['read_num']+$article['like_num'];
            D('Bbs/Article')->where(['id' => $article_id])->save(['hot_num' => $hot_num]);
        }
    }
}