<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller\Api;

use Common\Controller\Base;

class BaseController extends Base
{
    public $bbs_user_id;
    protected function _initialize(){
        parent::_initialize();

        //TODO 用户登录检测
        $this->bbs_user_id = 1;
    }
}