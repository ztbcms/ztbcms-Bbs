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
 * 回复服务
 */
class ReplyService extends BaseService {

    /**
     * 获取帖子留言列表
     *
     * @param int $article_id   帖子ID
     * @param int $pid          回复父ID
     * @param int $page
     * @param int $limit
     * @param string $sort
     * @return array
     */
    static function getReplyList($article_id, $pid = 0, $page = 1, $limit = 10, $sort = ''){
        if($sort == 'like'){
            $order = '`like_num` DESC,`create_time` DESC'; //点赞排序
        }elseif($sort == 'time_desc'){
            $order = '`create_time` DESC'; //最早时间排序
        }elseif($sort == 'time_asc'){
            $order = '`create_time` ASC'; //最晚时间排序
        }else{
            $order = '`create_time` DESC'; //默认排序
        }
        $items = D('Bbs/ArticleReply')->where([
            'article_id' => $article_id,
            'pid' => $pid,
            'is_show' => 1
        ])->order($order)->page($page, $limit)->select() ?: [];
        $total_items = D('Bbs/ArticleReply')->where([
            'article_id' => $article_id,
            'pid' => $pid,
            'is_show' => 1
        ])->count();
        foreach($items as &$item){
            $item['user_name'] = M('BbsUser')->where(['id' => $item['user_id']])->getField('name');
            $item['user_avatar'] = M('BbsUser')->where(['id' => $item['user_id']])->getField('avatar');
            $item['create_time'] = date('Y-m-d H:i', $item['create_time']);

            $item['content'] = htmlspecialchars_decode($item['content']);
        }
        $data = [
            'page' => $page,
            'limit' => $limit,
            'items' => $items,
            'total_items' => $total_items,
            'total_pages' => ceil($total_items/$limit)
        ];
        return self::createReturn(true, $data);
    }

    /**
     * 回复
     *
     * @param $user_id
     * @param $article_id
     * @param $pid
     * @param $content
     * @return array
     */
    static function doReply($user_id, $article_id, $pid, $content){
        if($pid){
            $floor = D('Bbs/ArticleReply')->where(['article_id' => $article_id, 'id' => $pid])->getField('floor');
        }else{
            $last_floor = D('Bbs/ArticleReply')->where(['article_id' => $article_id])->max('floor');
            if($last_floor){
                $floor = $last_floor + 1;
            }else{
                $floor = 2;
            }
        }
        $res = D('Bbs/ArticleReply')->add([
            'article_id' => $article_id,
            'floor' => $floor,
            'pid' => $pid,
            'user_id' => $user_id,
            'content' => $content,
            'is_show' => 1,
            'create_time' => time()
        ]);
        $param = ['user_id' => $user_id, 'article_id' => $article_id, 'reply_id' => $pid];
        tag('bbs_reply_article', $param);
        return self::createReturn(true, $res);
    }
}