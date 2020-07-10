-- ----------------------------
-- 用户
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_user`;
CREATE TABLE `cms_bbs_user` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '用户名',
  `avatar` varchar(255) NOT NULL COMMENT '用户头像',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
-- 版块
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_section`;
CREATE TABLE `cms_bbs_section` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '版块名称',
  `icon` varchar(255) NOT NULL COMMENT '版块图标',
  `affiche` varchar(255) NOT NULL COMMENT '版块公告',
  `pid` int(11) NOT NULL COMMENT '父版块ID',
  `sort` int(11) NOT NULL COMMENT '排序',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='版块';

-- ----------------------------
-- 帖子
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_article`;
CREATE TABLE `cms_bbs_article` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `section_id` int(11) NOT NULL COMMENT '所属版块ID',
  `title` varchar(32) NOT NULL COMMENT '帖子标题',
  `content` text NOT NULL COMMENT '帖子内容',
  `read_num` int(11) NOT NULL COMMENT '查看量',
  `reply_num` int(11) NOT NULL COMMENT '回复量',
  `like_num` int(11) NOT NULL COMMENT '点赞量',
  `collect_num` int(11) NOT NULL COMMENT '收藏量',
  `hot_num` int(11) NOT NULL COMMENT '热度(评论数+查看数+点赞数)',
  `user_id` int(11) NOT NULL COMMENT '发帖人ID',
  `review_status` tinyint(1) NOT NULL COMMENT '审核状态, 1审核中 2审核通过 3审核不通过',
  `review_remark` varchar(255) NOT NULL COMMENT '审核备注',
  `sort` int(11) NOT NULL COMMENT '排序(置顶时间)',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子';

-- ----------------------------
-- 帖子查看记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_article_read`;
CREATE TABLE `cms_bbs_article_read` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '帖子ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子查看记录';

-- ----------------------------
-- 帖子点赞记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_article_like`;
CREATE TABLE `cms_bbs_article_like` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '帖子ID',
  `reply_id` int(11) NOT NULL COMMENT '帖子回复ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子点赞记录';

-- ----------------------------
-- 帖子收藏记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_article_collect`;
CREATE TABLE `cms_bbs_article_collect` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '帖子ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子收藏记录';

-- ----------------------------
-- 帖子回复记录
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_article_reply`;
CREATE TABLE `cms_bbs_article_reply` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `article_id` int(11) NOT NULL COMMENT '帖子ID',
  `floor` int(11) NOT NULL COMMENT '所属楼层',
  `pid` int(11) NOT NULL COMMENT '回复父ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `content` text NOT NULL COMMENT '回复内容',
  `reply_num` int(11) NOT NULL COMMENT '回复量',
  `like_num` int(11) NOT NULL COMMENT '点赞量',
  `is_show` tinyint(1) NOT NULL COMMENT '是否显示',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='帖子回复记录';

-- ----------------------------
-- 配置
-- ----------------------------
DROP TABLE IF EXISTS `cms_bbs_config`;
CREATE TABLE `cms_bbs_config` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID' AUTO_INCREMENT,
  `key` varchar(255) NOT NULL COMMENT 'key',
  `value` varchar(255)  NOT NULL COMMENT 'value',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置';

-- ----------------------------
-- 插入配置
-- ----------------------------
INSERT INTO `cms_bbs_config` ( `key`, `value` )
VALUES
	( 'add_article_integral', 0 ),
	( 'reply_article_integral', 0 ),
	( 'read_article_integral', 0 )
