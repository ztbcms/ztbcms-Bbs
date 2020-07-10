<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:51
 */

namespace Bbs\Model;

use Common\Model\Model;

class ArticleModel extends Model
{
    protected $tableName = 'bbs_article';

    /**
     * 是否显示
     */
    const SHOW_YES = 1;
    const SHOW_NO = 0;

    /**
     * 审核状态 待审核
     */
    const REVIEW_STATUS_WAIT = 1;
    /**
     * 审核状态 已通过
     */
    const REVIEW_STATUS_SUCCESS = 2;
    /**
     * 审核状态 已拒绝
     */
    const REVIEW_STATUS_ERROR = 3;
}