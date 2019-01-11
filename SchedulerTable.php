<?php
/**
 * Created by PhpStorm.
 * User: a.vartan
 * Date: 09.08.2018
 * Time: 11:24
 */

namespace Taber\Podrygka\Scheduler;

use Bitrix\Main\Application;

/**
 * Class SchedulerTable
 * @package Taber\Podrygka\Scheduler
 */
class SchedulerTable
{
    const TABLE_NAME = "scheduler";

    /**
     * создает таблицу для планировщика
     *
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public static function create()
    {
        Application::getConnection()->query(
            "create table if not exists " . self::TABLE_NAME . " (
            id int(11) NOT NULL AUTO_INCREMENT,
            
            start_time timestamp not null default 0,
            end_time timestamp null,
            done int(5) not null default 0,
            error int(1) not null default 0,
              
            action_type varchar(30) not null default '', 
            data_json varchar(3000),
                        
            created timestamp not null default current_timestamp,
            updated timestamp,
            primary key (id));");
    }

    /**
     * вставляем планировщик в таблицу
     *
     * @param SchedulerCommand $schedulerCommand
     * @return int
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public static function insert(SchedulerCommand $schedulerCommand)
    {
        self::create();
        $dbConnection = Application::getConnection();
        $dbConnection->query(
            'INSERT INTO ' . self::TABLE_NAME .
            ' (start_time, end_time, done, action_type, data_json) VALUES ("' .
            $schedulerCommand->getStartTimeFormatted() . '", "' .
            $schedulerCommand->getEndTimeFormatted() . '" , ' .
            $schedulerCommand->getDone() . ', "' .
            $schedulerCommand->getActionType() . '", \'' .
            $schedulerCommand->getDataJson()
            . '\');'
        );
        return $dbConnection->getInsertedId();
    }

    /**
     * не обновляет action_type и created
     *
     * @param SchedulerCommand $schedulerCommand
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public static function update(SchedulerCommand $schedulerCommand)
    {
        self::create();
        if ($schedulerCommand->getId()) {
            Application::getConnection()->query("UPDATE " . self::TABLE_NAME . " SET 
            start_time='" . $schedulerCommand->getStartTimeFormatted() . "',
            end_time='" . $schedulerCommand->getEndTimeFormatted() . "',
            done=" . $schedulerCommand->getDone() . ",
            error=" . $schedulerCommand->getError() . ",
            data_json='" . $schedulerCommand->getDataJson() . "',
            updated='" . date('Y-m-d H:i:s') . "' WHERE id=" . $schedulerCommand->getId());
        }
    }

    public static function delete($id)
    {
        self::create();
        Application::getConnection()->query("DELETE FROM " . self::TABLE_NAME . " WHERE id=" . $id);
    }

    /**
     * для выбора записей по фильтру
     *
     * @param array $filters
     * @return array
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public static function filter(array $filters)
    {
        self::create();
        $filterSql = ' WHERE ';
        $setFilter = false;
        $schedulerCommands = [];
        $i = 0;
        foreach ($filters as $filterName => $filterValue) {
            if (in_array($filterName, self::getFields())) {
                if ($i > 0) {
                    $filterSql .= ' AND ';
                }
                $filterSql .= $filterName . "=" . $filterValue . " ";
                $setFilter = true;
                $i++;
            }
        }
        $filterSql = $setFilter ? $filterSql : '';
        if ($filterSql) {
            $schedulers = Application::getConnection()->query('SELECT * FROM ' . self::TABLE_NAME . $filterSql);
            while ($scheduler = $schedulers->fetch()) {
                $schedulerCommand = new SchedulerCommand($scheduler["action_type"]);
                $schedulerCommand->setId($scheduler["id"]);
                $schedulerCommand->setDataJson($scheduler["data_json"]);
                $schedulerCommand->setDone($scheduler["done"]);
                $schedulerCommand->setEndTime($scheduler["end_time"] ? $scheduler["end_time"]->getTimestamp() : 0);
                $schedulerCommand->setStartTime($scheduler["start_time"] ? $scheduler["start_time"]->getTimestamp() : 0);
                $schedulerCommands[] = $schedulerCommand;
            }
        }
        return $schedulerCommands;
    }

    /**
     * @return array
     */
    public static function getFields()
    {
        return [
            'id',
            'start_time',
            'end_time',
            'done',
            'error',
            'delta',  // шаг повторения(расписание)
            'repeat_type',  // тип повторяемости 1-по расписанию, 2-разово
            'action_type',
            'data_json'
        ];
    }
}