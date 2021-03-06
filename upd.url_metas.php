<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * URL Metas
 *
 * @package             Url metas for EE3
 * @author              Stephen Sweetland <stephen@triad.uk.com>
 * @copyright           Copyright (c) 2016 Triad Ltd
 * @link                http://triad.uk.com
 */

require_once PATH_THIRD . 'url_metas/common.php';

class Url_metas_upd
{
    public $version = URL_METAS_VERSION;

    public static function install()
    {
        // ok, add to modules table.
        ee()->db->insert(
            'modules',
            [
                'module_name' => URL_METAS_CLASS,
                'module_version' => URL_METAS_VERSION,
                'has_cp_backend' => 'y',
                'has_publish_fields' => 'n',
            ]
        );

        ee()->db->insert(
            'actions',
            [
                'class' => URL_METAS_CLASS,
                'method' => 'output_metas',
            ]
        );

        ee()->db->insert(
            'actions',
            [
                'class' => URL_METAS_CLASS_CP,
                'method' => 'add_new_metas',
            ]
        );

        // at some point we'll look at making this use dbforge
        $sql[] = "CREATE TABLE IF NOT EXISTS `exp_url_metas` (
                        `url_id` mediumint(6) NOT NULL auto_increment,
                        `url` varchar(255) default NULL,
                        `title` varchar(255) default NULL,
                        `keywords` varchar(255) default NULL,
                        `description` varchar(255) default NULL,
                        `def` enum('YES','NO') default 'NO',
                        PRIMARY KEY  (`url_id`)
                    )";

        $sql[] = "INSERT INTO exp_url_metas (`url`, `title`, `keywords`, `description`, `def`) " .
            "VALUES ('defaults', '', '', '', 'YES')";

        foreach ($sql as $query) {
            ee()->db->query($query);
        }

        return true;
    }

    public static function uninstall()
    {
        ee()->db->where('module_name', URL_METAS_CLASS);
        ee()->db->delete('modules');

        ee()->db->where('module_name', URL_METAS_CLASS_CP);
        ee()->db->delete('modules');

        ee()->db->query("DROP TABLE IF EXISTS exp_url_metas");

        return true;
    }

    public static function update($current = '')
    {
        return true;
    }
}
