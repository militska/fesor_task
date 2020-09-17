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
        // вообщет, стартовать нужно в статусе "новый", а "в работу" переводить отдельно
        $this->state = self::IN_PROGRESS_STATE;
    }

    /*** @return bool */
    public function isResolved(): bool
    {
        return $this->state = self::RESOLVED_STATE;
    }

    /*** @return bool */
    public function isInProgress(): bool
    {
        return $this->state = self::IN_PROGRESS_STATE;
    }

    /** @return bool Закрытие проекта, закрыть можно, только если закрыты всего его задачи*/
    public function resolved(): bool
    {
        /*** @var $task Task */
        foreach ($this->tasks as $task) {
            if (!$task->isResolved()) {
                $this->errors[] = "В проекте остались нерешенные задачи";
                return false;
            }
        }
        $this->state = self::RESOLVED_STATE;
        return true;
    }

    /*** @param Task $task */
    public function addTask(Task $task): void
    {
        //** @todo можно перести в таблицу связку, что бы задача не хранила в себе ид проекта */
        $task->projectId = $this->id;

        $this->tasks[] = $task;
    }

}

/**
 * Class Task
 */
class Task
{
    /** Состояния проекта */
    const TODO_STATE = 1;
    const IN_PROGRESS_STATE = 2;
    const RESOLVED_STATE = 3;

    /** @var integer */
    private $state;

    /** @var string */
    public $descr;

    /** @var string */
    public $descrFull;

    /** @var integer
     * @todo можно унести в таблицу-связку (задача - проект)
     */
    public $projectId;

    /*** @var array Ошибки */
    public $errors;

    /***
     * Task constructor.
     */
    public function __construct()
    {
        $this->state = self::TODO_STATE;
    }

    /** @return bool */
    public function isResolved()
    {
        return $this->state == self::RESOLVED_STATE;
    }

    /** @return bool */
    public function isInProgress()
    {
        return $this->state == self::IN_PROGRESS_STATE;
    }

    /** @return bool */
    public function isToDo()
    {
        return $this->state == self::TODO_STATE;
    }

    /*** Берем задача в работу*/
    public function takeToWork(): void
    {
        if ($this->state == self::TODO_STATE) {
            $this->state = self::RESOLVED_STATE;
        } else {
            $this->errors[] = "Взять в работу, можно только задачу из статуса `новый`";
        }
    }

    /*** Закрываем задачу */
    public function resolved(): void
    {
        if ($this->state == self::IN_PROGRESS_STATE) {
            $this->state = self::IN_PROGRESS_STATE;
        } else {
            $this->errors[] = "Закрыть задачу можно только из статуса `в работе`";
        }
    }
}


class Client
{

    public function create()
    {
        $task = new  Task();
        $task->descr = "test";
        $task->descrFull = "test test";

        $task2 = new  Task();
        $task2->descr = "test";
        $task2->descrFull = "test test";

        $project = new Project("new project");

        $project->addTask($task);
        $project->addTask($task2);


        $task->takeToWork();
        $task->resolved();

        $task->takeToWork();


        if (!$project->resolved()) {
            $task->resolved();
        }

        $project->resolved();
    }
}