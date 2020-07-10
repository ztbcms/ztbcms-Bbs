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
 * 版块服务
 */
class SectionService extends BaseService {

    /**
     * 根据ID获取版块
     *
     * @param $id
     * @return array
     */
    static function getSectionById($id) {
        return self::find('Bbs/Section', ['id' => $id]);
    }


    /**
     * 获取版块列表
     *
     * @param array  $where
     * @param string $order
     * @param int    $page
     * @param int    $limit
     * @param bool   $isRelation
     * @return array
     */
    static function getSectionList($where = [], $order = '', $page = 1, $limit = 20, $isRelation = false) {
        return self::select('Bbs/Section', $where, $order, $page, $limit, $isRelation);
    }

    /**
     * 添加版块
     *
     * @param array $data
     * @return array
     */
    static function createSection($data = []) {
        return self::create('Bbs/Section', $data);
    }

    /**
     * 更新版块
     *
     * @param       $id
     * @param array $data
     * @return array
     */
    static function updateSection($id, $data = []) {
        return self::update('Bbs/Section', ['id' => $id], $data);
    }

    /**
     * 删除版块
     *
     * @param $id
     * @return array
     */
    static function deleteSectionById($id) {
        return self::delete('Bbs/Section', ['id' => $id]);
    }
}