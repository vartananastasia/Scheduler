<?php
/**
 * Created by PhpStorm.
 * User: a.vartan
 * Date: 09.08.2018
 * Time: 11:31
 */

namespace Taber\Podrygka\Scheduler;

use Taber\Podrygka\FreeDelivery\UpdatingCommand;

/**
 * Class Scheduler
 * @package Taber\Podrygka\Scheduler
 */
final class Scheduler
{
    /**
     * @var array
     */
    private $schedulers;
    /**
     * @var int
     */
    private $startTime;

    /**
     * планировщик собирает все события, которые
     * должны начаться в ближайший промежуток времени TIME_DELTA
     */
    const TIME_DELTA = 300;

    public function __construct()
    {
        $this->startTime = time();
        $schedulers = self::getSchedulers();
        // отсеиваем все что не входит в TIME_DELTA
        foreach ($schedulers as $key => $scheduler) {
            if ($scheduler->getStartTime() > ($this->startTime + self::TIME_DELTA) || $scheduler->getStartTime() <= $this->startTime) {
                unset($schedulers[$key]);
            }
        }
        $this->schedulers = $schedulers;
    }

    /**
     * запускаем планировщики по типу
     */
    public function run()
    {
        foreach ($this->schedulers as $scheduler) {
            switch ($scheduler->getActionType()) {
                case UpdatingCommand::UPDATING_COMMAND_CODE:
                    UpdatingCommand::do($scheduler);
                    break;
            }
        }
    }

    /**
     * @return array
     */
    public function allSchedulers()
    {
        return $this->schedulers;
    }

    public function up()
    {
        // TODO: перезапуск планировщика
    }

    public function rubbishCollector()
    {
        // TODO: сборщик неотработанных команд
    }

    private function getSchedulers()
    {
        return SchedulerTable::filter(["done" => 0]);
    }
}