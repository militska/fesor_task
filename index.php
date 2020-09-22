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
    public $id;

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
     * @param int|null $parentProjectId
     */
    public function __construct(string $title)
    {
        $this->title = $title;
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
        $this->tasks[] = $task;
    }

    /** @return array */
    public function getTasks() : array
    {
        return $this->tasks;
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

    /** @var string */
    public $descr;

    /** @var string */
    public $descrFull;

    /** @var integer */
    public $projectId;

    /*** @var array Ошибки */
    public $errors;

    /** @var integer */
    private $state;

    /***
     * Task constructor.
     * @param integer $projectId
     */
    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
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


/***
 * Class Client
 */
class Client
{

    /***
     *
     */
    public function create()
    {
        $project = new Project("new project");

        $task = new  Task($project->id);
        $task->descr = "test";
        $task->descrFull = "test test";

        $task2 = new  Task($project->id);
        $task2->descr = "test";
        $task2->descrFull = "test test";


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