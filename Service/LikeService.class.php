<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:51
 */

namespace Bbs\Service;

use System\Service\BaseService;

/**
 * 点赞服务
 */
class LikeService extends BaseService {

    static function isLike($user_id, $article_id, $reply_id = 0){
        $count = D('Bbs/ArticleLike')->where([
            'user_id' => $user_id,
            'article_id' => $article_id,
            'reply_id' => $reply_id,
        ])->count();
        return $count ? 1 : 0;
    }

    /**
     * 点赞
     *
     * @param $user_id
     * @param $article_id
     * @param $reply_id
     * @return array
     */
    static function doLike($user_id, $article_id, $reply_id = 0){
        $count = D('Bbs/ArticleLike')->where([
            'user_id' => $user_id,
            'article_id' => $article_id,
            'reply_id' => $reply_id,
        ])->count();
        if(!$count){
            $res = D('Bbs/ArticleLike')->add([
                'user_id' => $user_id,
                'article_id' => $article_id,
                'reply_id' => $reply_id,
                'create_time' => time()
            ]);
            if($res){
                $param = ['user_id' => $user_id, 'article_id' => $article_id, 'reply_id' => $reply_id];
                tag('bbs_like_article', $param);
            }
        }
        return self::createReturn(true);
    }

    /**
     * 取消点赞
     *
     * @param $user_id
     * @param $article_id
     * @param int $reply_id
     * @return array
     */
    static function unLike($user_id, $article_id, $reply_id = 0){
        $count = D('Bbs/ArticleLike')->where([
            'user_id' => $user_id,
            'article_id' => $article_id,
            'reply_id' => $reply_id,
        ])->count();
        if($count){
            $res = D('Bbs/ArticleLike')->where([
                'user_id' => $user_id,
                'article_id' => $article_id,
                'reply_id' => $reply_id,
            ])->delete();
            if($res){
                $param = ['user_id' => $user_id, 'article_id' => $article_id, 'reply_id' => $reply_id];
                tag('bbs_like_article', $param);
            }
        }
        return self::createReturn(true);
    }

}