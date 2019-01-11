<?php
/**
 * Created by PhpStorm.
 * User: a.vartan
 * Date: 13.08.2018
 * Time: 10:26
 */

namespace Taber\Podrygka\Scheduler;


class SchedulerStatuses
{
    const TABLE_NAME = 'scheduler_statuses';

    public static function create()
    {
        Application::getConnection()->query(
            "create table if not exists " . self::TABLE_NAME . " (
            id int(11) NOT NULL AUTO_INCREMENT,
            status_code int(1) not null default 0,
            status_name varchar(30) not null default '',
            created timestamp not null default current_timestamp,
            updated timestamp not null default current_timestamp,
            primary key (id));");
    }

    public function insert()
    {

    }
}