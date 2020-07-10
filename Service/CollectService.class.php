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
 * 收藏服务
 */
class CollectService extends BaseService {

    static function isCollect($user_id, $article_id){
        $count = D('Bbs/ArticleCollect')->where([
            'user_id' => $user_id,
            'article_id' => $article_id,
        ])->count();
        return $count ? 1 : 0;
    }

    /**
     * 收藏
     *
     * @param $user_id
     * @param $article_id
     * @return array
     */
    static function doCollect($user_id, $article_id){
        $count = D('Bbs/ArticleCollect')->where([
            'user_id' => $user_id,
            'article_id' => $article_id,
        ])->count();
        if(!$count){
            $res = D('Bbs/ArticleCollect')->add([
                'user_id' => $user_id,
                'article_id' => $article_id,
                'create_time' => time()
            ]);
            if($res){
                $param = ['user_id' => $user_id, 'article_id' => $article_id];
                tag('bbs_collect_article', $param);
            }
        }
        return self::createReturn(true);
    }

    /**
     * 取消收藏
     *
     * @param $user_id
     * @param $article_id
     * @return array
     */
    static function unCollect($user_id, $article_id){
        $count = D('Bbs/ArticleCollect')->where([
            'user_id' => $user_id,
            'article_id' => $article_id,
        ])->count();
        if($count){
            $res = D('Bbs/ArticleCollect')->where([
                'user_id' => $user_id,
                'article_id' => $article_id,
            ])->delete();
            if($res){
                $param = ['user_id' => $user_id, 'article_id' => $article_id];
                tag('bbs_collect_article', $param);
            }
        }
        return self::createReturn(true);
    }

}