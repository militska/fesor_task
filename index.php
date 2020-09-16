<?php

/**
 * Class Project
 */
class Project
{
    /** Состояния проекта */
    const IN_PROGRESS_STATE = 1;
    const RESOLVED_STATE = 2;

    /** @var integer */
    private $id;

    /** @var string */
    public $title;

    /** @var integer */
    private $state;

    /*** @var array Задачи */
    public $tasks;

    /*** @var array Ошибки */
    public $errors;

    /***
     * Project constructor.
     * @param string $title
     */
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /*** @return bool */
    public function isResolved() :bool
    {
        return $this->state = self::RESOLVED_STATE;
    }

    /*** @return bool */
    public function isInProgress() :bool
    {
        return $this->state = self::IN_PROGRESS_STATE;
    }

    /** Закрытие проекта, закрыть можно, только если закрыты всего его задачи */
    public function resolved(): void
    {
        $allResolved = true;
        /*** @var $task Task */
        foreach ($this->tasks as $task) {
            if (!$task->isResolved()) {
                $allResolved = false;
                $this->errors[] = 'Не все задачи закрыты';
            }
        }

        if ($allResolved) {
            $this->state = self::RESOLVED_STATE;
        }
    }


    public function addTask(Task $task): void
    {
        // @todo можно перести в таблицу связку, что бы задача не хранила в себе ид проекта
        $task->projectId = $this->id;

        $this->tasks[] = $task;
    }

}

class Task
{
    /** Состояния проекта */
    const TODO_STATE = 1;
    const IN_PROGRESS_STATE = 2;
    const RESOLVED_STATE = 3;

    private $state;


    public $projectId;

    /*** @var array Ошибки */
    public $errors;

    /** @return bool */
    public function isResolved()
    {
        return $this->state == self::RESOLVED_STATE;
    }

    /*** Берем задача в работу*/
    public function takeToWork() :void {
        if ($this->state == self::TODO_STATE) {
            $this->state = self::RESOLVED_STATE;
        } else {
            $this->errors[] = "Взять в работу, можно только задачу из статуса `новый`";
        }
    }

    /*** Закрываем задачу */
    public function resolved() :void {
        if ($this->state == self::IN_PROGRESS_STATE) {
            $this->state = self::IN_PROGRESS_STATE;
        } else {
            $this->errors[] = "Закрыть задачу можно только из статуса `в работе`";
        }
    }
}



class Client {


}