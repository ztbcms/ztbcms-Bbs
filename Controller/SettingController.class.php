<?php
/**
 * Created by PhpStorm.
 * User: yezhilie
 * Date: 2020/7/7
 * Time: 17:31
 */

namespace Bbs\Controller;


class SettingController extends BaseController
{
    public function index(){
        $this->display();
    }

    public function getSetting()
    {
        $data = M('BbsConfig')->getField('key,value');
        $this->ajaxReturn(self::createReturn(true, $data));
    }

    public function setSetting()
    {
        $data = I('post.');
        foreach($data as $k => $v){
            $data = M('BbsConfig')->where(['key' => $k])->save(['value' => $v]);
        }
        $this->ajaxReturn(self::createReturn(true, $data));
    }

}