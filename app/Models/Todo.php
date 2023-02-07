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

    public function save(): void
    {
        $res = DB::getInstance()->prepare('INSERT INTO todos (text, done) VALUES (:text, :done)');
        $res->bindParam('text', $this->text);
        $res->bindParam('done', $this->done, PDO::PARAM_INT);
        $res->execute();
    }

    public function isDone(): bool
    {
        return $this->done === 1 ? true : false;
    }

    public function isNotDone(): bool
    {
        return $this->done === 0 ? true : false;
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

    function checkTodo(PDO $db, int $id): void
    {
        $now = date('Y-m-d H:i:s');

        $res = $db->prepare('UPDATE todos SET done = 1, updated_at = :updated_at WHERE id = :id');
        $res->bindParam('id', $id);
        $res->bindParam('updated_at', $now);
        $res->execute();
    }

    function uncheckTodo(PDO $db, int $id): void
    {
        $now = date('Y-m-d H:i:s');

        $res = $db->prepare('UPDATE todos SET done = 0, updated_at = :updated_at WHERE id = :id');
        $res->bindParam('id', $id);
        $res->bindParam('updated_at', $now);
        $res->execute();
    }

    function deleteTodo(PDO $db, int $id): void
    {
        // $res = $db->prepare('DELETE FROM todos WHERE id = :id');
        // $res->bindParam('id', $id);
        // $res->execute();

        $now = date('Y-m-d H:i:s');

        $res = $db->prepare('UPDATE todos SET deleted_at = :deleted_at WHERE id = :id');
        $res->bindParam('id', $id);
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
