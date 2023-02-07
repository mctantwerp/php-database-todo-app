<?php

namespace App\Models;

use PDO;
use App\Lib\DB;

Class Todo
{
    public int $id;
    private string $text;
    private bool $done = false;

    public function __construct($id = null)
    {
        if(!empty($id))
        {
            $this->find($id);
        }
    }

    public function find(int $id): void
    {
        $stmt = DB::getInstance()->prepare("SELECT * FROM todos WHERE id = :id");
        $stmt->bindParam('id', $id);
        $stmt->execute();

        $todo = $stmt->fetchObject("App\Models\Todo");

        $this->id = $todo->id;
        $this->text = $todo->text;
        $this->done = $todo->done;
    }

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

    public function save(): int
    {
        if(!empty($this->id))
        {
            $now = date('Y-m-d H:i:s');

            $res = DB::getInstance()->prepare('UPDATE todos SET text = :text, done = :done, updated_at = :updated_at WHERE id = :id');
            $res->bindParam('id', $this->id);
            $res->bindParam('text', $this->text);
            $res->bindParam('done', $this->done, PDO::PARAM_INT);
            $res->bindParam('updated_at', $now);
            $res->execute();

            return $this->id;
        }

        $res = DB::getInstance()->prepare('INSERT INTO todos (text, done) VALUES (:text, :done)');
        $res->bindParam('text', $this->text);
        $res->bindParam('done', $this->done, PDO::PARAM_INT);
        $res->execute();

        return DB::getInstance()->lastInsertId();
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function isNotDone(): bool
    {
        return !$this->done;
    }

    public static function pending(): int
    {
        $res = DB::getInstance()->query('SELECT COUNT(*) FROM todos WHERE done = 0 and deleted_at IS NULL');

        return $res->fetchColumn();
    }

    public static function completed(): int
    {
        $res = DB::getInstance()->query('SELECT COUNT(*) FROM todos WHERE done = 1 and deleted_at IS NULL');

        return $res->fetchColumn();
    }

    function setDone(): void
    {
        $this->done = true;
    }

    function setUnDone(): void
    {
        $this->done = false;
    }

    function delete(): void
    {
        $now = date('Y-m-d H:i:s');

        $res = DB::getInstance()->prepare('UPDATE todos SET deleted_at = :deleted_at WHERE id = :id');
        $res->bindParam('id', $this->id);
        $res->bindParam('deleted_at', $now);
        $res->execute();
    }

    public function setText($text): void
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
