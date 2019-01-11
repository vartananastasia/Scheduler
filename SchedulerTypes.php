<?php
/**
 * Created by PhpStorm.
 * User: a.vartan
 * Date: 13.08.2018
 * Time: 10:27
 */

namespace Taber\Podrygka\Scheduler;


class SchedulerTypes
{
    const TABLE_NAME = 'scheduler_types';

    public static function create()
    {
        Application::getConnection()->query(
            "create table if not exists " . self::TABLE_NAME . " (
            id int(11) NOT NULL AUTO_INCREMENT,
            scheduler_code int(1) not null default 0,
            status_name varchar(30) not null default '',
            created timestamp not null default current_timestamp,
            updated timestamp not null default current_timestamp,
            primary key (id));");
    }

    public function insert()
    {

    }
}