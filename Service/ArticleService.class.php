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
 * 帖子服务
 */
class ArticleService extends BaseService {

    /**
     * 根据ID获取帖子
     *
     * @param $id
     * @return array
     */
    static function getArticleById($id) {
        return self::find('Bbs/Article', ['id' => $id]);
    }


    /**
     * 获取帖子列表
     *
     * @param array  $where
     * @param string $order
     * @param int    $page
     * @param int    $limit
     * @param bool   $isRelation
     * @return array
     */
    static function getArticleList($where = [], $order = '', $page = 1, $limit = 20, $isRelation = false) {
        return self::select('Bbs/Article', $where, $order, $page, $limit, $isRelation);
    }

    /**
     * 添加帖子
     *
     * @param array $data
     * @return array
     */
    static function createArticle($data = []) {
        return self::create('Bbs/Article', $data);
    }

    /**
     * 更新帖子
     *
     * @param       $id
     * @param array $data
     * @return array
     */
    static function updateArticle($id, $data = []) {
        return self::update('Bbs/Article', ['id' => $id], $data);
    }

    /**
     * 删除帖子
     *
     * @param $id
     * @return array
     */
    static function deleteArticleById($id) {
        return self::delete('Bbs/Article', ['id' => $id]);
    }
}