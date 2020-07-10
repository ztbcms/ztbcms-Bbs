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
 * 收藏
 * Class BbsCollectArticleBehavior
 * @package Bbs\Behavior
 */
class BbsCollectArticleBehavior extends BaseBehavior
{

    public function run(&$param) {
        $article_id = $param['article_id'];
        $user_id = $param['user_id'];

        $collect_num = D('Bbs/ArticleCollect')->where(['article_id' => $article_id])->count();
        D('Bbs/Article')->where(['id' => $article_id])->save(['collect_num' => $collect_num]);
    }
}