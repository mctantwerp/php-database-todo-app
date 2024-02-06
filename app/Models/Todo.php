<?php

namespace App\Models;

use PDO;
use App\Lib\DB;

Class Todo
{
    private int $id;
    private string $text;
    private bool $done = false;
    private $created_at;
    private $updated_at;
    private $deleted_at;

    /**
     * Constructor, if an id is passed, it will find the todo with that id
     * @param null $id
     */
    public function __construct($id = null)
    {
        if(!empty($id))
        {
            $this->find($id);
        }
    }

    /**
     * Get all todo records, optionally with trashed records
     * This is a static method, so you can call it like this: Todo::get()
     * It does not require an instance of the Todo class
     * @param bool $withTrashed
     *
     * @return array
     */
    public static function get(bool $withTrashed = false): array
    {
        if($withTrashed === true)
        {
            $stmt = DB::getInstance()->prepare("SELECT * FROM todos");
        }
        else
        {
            $stmt = DB::getInstance()->prepare("SELECT * FROM todos WHERE deleted_at IS NULL");
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, "App\Models\Todo");
    }

    /**
     * Find a todo by id, will return the todo object
     * @param int $id
     *
     * @return Todo
     */
    public function find(int $id): Todo
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM todos WHERE id = :id");
        $stmt->bindParam('id', $id);
        $stmt->execute();

        $todo = $stmt->fetchObject("App\Models\Todo");

        if(!empty($todo))
        {
            $this->id = $todo->id;
            $this->text = $todo->text;
            $this->done = $todo->done;
        }

        return $this;
    }

    /**
     * Add a new todo
     * @return int
     */
    public function add(): int
    {
        $res = DB::getInstance()->prepare('INSERT INTO todos (text, done) VALUES (:text, :done)');
        $res->bindParam('text', $this->text);
        $res->bindParam('done', $this->done, PDO::PARAM_INT);
        $res->execute();

        $this->id = DB::getInstance()->lastInsertId();

        return $this->id;
    }

    /**
     * Update the todo
     * @return int
     */
    public function update(): int
    {
        if(empty($this->id))
        {
            throw new \Exception("No todo selected");
        }

        $now = date('Y-m-d H:i:s');

        $res = DB::getInstance()->prepare('UPDATE todos SET text = :text, done = :done, updated_at = :updated_at WHERE id = :id');
        $res->bindParam('id', $this->id);
        $res->bindParam('text', $this->text);
        $res->bindParam('done', $this->done, PDO::PARAM_INT);
        $res->bindParam('updated_at', $now);
        $res->execute();

        return $this->id;
    }

    /**
     * Save the todo, if it has an id, it will update, otherwise it will add
     * Will return the id of the todo
     * @return int
     */
    public function save(): int
    {
        if(!empty($this->id))
        {
            return $this->update();
        }

        return $this->add();
    }

    /**
     * Delete the todo, this is a soft delete, it will set the deleted_at column
     * @return bool
     */
    function delete(): bool
    {
        if(empty($this->id))
        {
            throw new \Exception("No todo selected");
        }

        $now = date('Y-m-d H:i:s');

        $res = DB::getInstance()->prepare('UPDATE todos SET deleted_at = :deleted_at WHERE id = :id');
        $res->bindParam('id', $this->id);
        $res->bindParam('deleted_at', $now);
        $res->execute();

        return true;
    }

    /**
     * Is the todo done (checked) or not
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->done;
    }

    /**
     * Is the todo not done (unchecked) or not
     * @return bool
     */
    public function isNotDone(): bool
    {
        return !$this->done;
    }

    /**
     * Get the number of pending todos
     * This is a static function because it does not need an instance of the todo class
     * @return int
     */
    public static function pending(): int
    {
        $res = DB::getInstance()->query('SELECT COUNT(*) FROM todos WHERE done = 0 and deleted_at IS NULL');

        return $res->fetchColumn();
    }

    /**
     * Get the number of completed todos
     * This is a static function because it does not need an instance of the todo class
     * @return int
     */
    public static function completed(): int
    {
        $res = DB::getInstance()->query('SELECT COUNT(*) FROM todos WHERE done = 1 and deleted_at IS NULL');

        return $res->fetchColumn();
    }

    /**
     * Set the todo done (checked)
     * @return void
     */
    function setDone(): void
    {
        $this->done = true;
    }

    /**
     * Set the todo not done (unchecked)
     * @return void
     */
    function setUnDone(): void
    {
        $this->done = false;
    }

    /**
     * Set the todo text
     * @param mixed $text
     *
     * @return void
     */
    public function setText($text): void
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        $this->text = $text;
    }

    /**
     * Get the todo text
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Get the todo id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
