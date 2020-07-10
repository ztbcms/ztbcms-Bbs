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
 * 浏览
 * Class BbsReadArticleBehavior
 * @package Bbs\Behavior
 */
class BbsReadArticleBehavior extends BaseBehavior
{

    public function run(&$param) {
        $article_id = $param['article_id'];
        $user_id = $param['user_id'];
        //添加阅读记录
        $res = D('Bbs/ArticleRead')->add([
            'article_id' => $article_id,
            'user_id' => $user_id,
            'create_time' => time()
        ]);
        if($res){
            //增加阅读数
            D('Bbs/Article')->where(['id' => $article_id])->setInc('read_num');
            //热度(评论数+查看数+点赞数)
            $article = D('Bbs/Article')->field('reply_num,read_num,like_num')->where(['id' => $article_id])->find();
            $hot_num = $article['reply_num']+$article['read_num']+$article['like_num'];
            D('Bbs/Article')->where(['id' => $article_id])->save(['hot_num' => $hot_num]);

            //获得积分
            $read_article_integral = M('BbsConfig')->where(['key' => 'read_article_integral'])->getField('value');
            if($read_article_integral > 0){
                //TODO
            }
        }
    }
}