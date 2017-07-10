<?php

/**
 * 审核不通过/未审核/查询隐藏
 */
if (!defined('UNREVIEWED')) {
    define('UNREVIEWED', 0);
}

/**
 * 审核通过/查询显示
 */
if (!defined('REVIEWED')) {
    define('REVIEWED', 1);
}

/**
 * 新闻资讯
 */
if (!defined('NEWS_TYPE_NEWS')) {
    define('NEWS_TYPE_NEWS', 0);
}

/**
 * 快讯
 */
if (!defined('NEWS_TYPE_FLASH')) {
    define('NEWS_TYPE_FLASH', 1);
}


/**
 * 文字直播
 */
if (!defined('STUDIO_TEXT')) {
    define('STUDIO_TEXT', 0);
}

/**
 * 视频直播
 */
if (!defined('STUDIO_VIDEO')) {
    define('STUDIO_VIDEO', 1);
}