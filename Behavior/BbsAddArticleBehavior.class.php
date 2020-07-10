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
 * 发布帖子(审核通过)
 * Class BbsAddArticleBehavior
 * @package Bbs\Behavior
 */
class BbsAddArticleBehavior extends BaseBehavior
{

    public function run(&$param) {
        $article_id = $param['article_id'];
        //获得积分
        $read_article_integral = M('BbsConfig')->where(['key' => 'add_article_integral'])->getField('value');
        if($read_article_integral > 0){
            //TODO
        }
    }
}