<?php

// +----------------------------------------------------------------------
// | 论坛模块配置
// +----------------------------------------------------------------------

return array(
	//模块名称
	'modulename' => '论坛模块',
	//图标
	'icon' => 'https://dn-coding-net-production-pp.qbox.me/e57af720-f26c-4f3b-90b9-88241b680b7b.png',
	//模块简介
	'introduce' => '论坛模块',
	//模块介绍地址
	'address' => 'http://doc.ztbcms.com/module/',
	//模块作者
	'author' => 'yezhilie',
	//作者地址
	'authorsite' => 'http://ztbcms.com',
	//作者邮箱
	'authoremail' => 'admin@ztbcms.com',
	//版本号，请不要带除数字外的其他字符
	'version' => '1.0.0.4',
	//适配最低CMS版本，
	'adaptation' => '3.0.0.0',
	//签名
	'sign' => 'd04078c5b86475cd5a0c690b9905953d',
	//依赖模块
	'depend' => array(),
	//行为注册
	'tags' => array(
        'bbs_read_article' => array(
            'title' => '浏览帖子',
            'remark' => '浏览帖子',
            'type' => 1,
            'phpfile:BbsReadArticleBehavior|module:Bbs',
        ),
        'bbs_add_article' => array(
            'title' => '发布帖子',
            'remark' => '发布帖子(审核通过时触发)',
            'type' => 1,
            'phpfile:BbsAddArticleBehavior|module:Bbs',
        ),
        'bbs_reply_article' => array(
            'title' => '回复帖子',
            'remark' => '回复帖子',
            'type' => 1,
            'phpfile:BbsReplyArticleBehavior|module:Bbs',
        ),
        'bbs_like_article' => array(
            'title' => '点赞',
            'remark' => '点赞',
            'type' => 1,
            'phpfile:BbsLikeArticleBehavior|module:Bbs',
        ),
        'bbs_collect_article' => array(
            'title' => '收藏',
            'remark' => '收藏',
            'type' => 1,
            'phpfile:BbsCollectArticleBehavior|module:Bbs',
        ),
    ),
	//缓存，格式：缓存key=>array('module','model','action')
	'cache' => array(),
);
