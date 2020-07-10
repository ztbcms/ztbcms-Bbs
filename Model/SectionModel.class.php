<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:51
 */

namespace Bbs\Model;

use Common\Model\Model;

class SectionModel extends Model
{
    protected $tableName = 'bbs_section';

    /**
     * 是否显示
     */
    const SHOW_YES = 1;
    const SHOW_NO = 0;
}