<?php
/**
 * Created by PhpStorm.
 * User: a.vartan
 * Date: 09.08.2018
 * Time: 11:43
 */

namespace Taber\Podrygka\Scheduler;


class SchedulerCommand
{
    private $id;
    /**
     * @var int
     */
    private $startTime;
    /**
     * @var int
     */
    private $endTime;
    /**
     * @var int
     */
    private $done;
    /**
     * @var int
     */
    private $error;
    /**
     * @var int
     */
    private $actionType;
    /**
     * @var string
     */
    private $dataJson;

    /**
     * статус обработки планировщика
     */
    const NOT_DONE = 0;
    const DONE = 1;
    const IN_PROCESS = 2;

    public function __construct(int $actionType)
    {
        $this->actionType = $actionType;
        $this->done = self::NOT_DONE;
        $this->error = 0;
        $this->dataJson = '';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setStartTime(int $startTime)
    {
        $this->startTime = $startTime;
    }

    public function setError(int $error)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setEndTime(int $endTime = 0)
    {
        $this->endTime = $endTime;
    }

    public function setDone(int $done)
    {
        $this->done = $done;
    }

    public function setActionType(int $actionType)
    {
        $this->actionType = $actionType;
    }

    public function setDataJson($dataJson = '')
    {
        $this->dataJson = $dataJson;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getStartTimeFormatted()
    {
        return date('Y-m-d H:i:s', $this->startTime);
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function getEndTimeFormatted()
    {
        return $this->endTime ? date('Y-m-d H:i:s', $this->endTime) : 0;
    }

    public function getDone()
    {
        return $this->done;
    }

    public function getActionType()
    {
        return $this->actionType;
    }

    public function getDataJson()
    {
        return $this->dataJson;
    }

    public function getDataJsonInArr(){
        return json_decode($this->dataJson);
    }
}