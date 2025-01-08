<?php

function dbConnect(string $user, string $pass, string $db, string $host = 'localhost')
{
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

function getPendingCount(PDO $db): int
{
    $res = $db->query('SELECT count(*) FROM todos WHERE done = 0 AND deleted_at IS NULL');

    return $res->fetchColumn();
}

function getCompletedCount(PDO $db): int
{
    $res = $db->query('SELECT count(*) FROM todos WHERE done = 1 AND deleted_at IS NULL');

    return $res->fetchColumn();
}

function getTodos(PDO $db, bool $withTrashed = false): array
{
    if ($withTrashed === true) {
        $res = $db->query('SELECT * FROM todos');
    }

    if ($withTrashed === false) {
        $res = $db->query('SELECT * FROM todos WHERE deleted_at IS NULL');
    }

    return $res->fetchAll();
}

function addTodo(PDO $db, string $text): void
{
    $text = htmlspecialchars($text);

    $res = $db->prepare('INSERT INTO todos(text) VALUES(:text)');
    $res->bindParam('text', $text);
    $res->execute();
}

function checkTodo(PDO $db, int $id): void
{
    $date = date('Y-m-d H:i:s');

    $res = $db->prepare('UPDATE todos SET done = 1, updated_at = :updated_at WHERE id = :id');
    $res->bindParam('id', $id);
    $res->bindParam('updated_at', $date);
    $res->execute();
}

function uncheckTodo(PDO $db, int $id): void
{
    $date = date('Y-m-d H:i:s');

    $res = $db->prepare('UPDATE todos SET done = 0, updated_at = :updated_at WHERE id = :id');
    $res->bindParam('id', $id);
    $res->bindParam('updated_at', $date);
    $res->execute();
}

function deleteTodo(PDO $db, int $id): void
{
    // $res = $db->prepare('DELETE FROM todos WHERE id = :id');
    // $res->bindParam('id', $id);
    // $res->execute();

    $date = date('Y-m-d H:i:s');

    $res = $db->prepare('UPDATE todos SET deleted_at = :deleted_at WHERE id = :id');
    $res->bindParam('id', $id);
    $res->bindParam('deleted_at', $date);
    $res->execute();
}
