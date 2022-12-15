<?php

/**
 * Vanaf php 8.2 kan je #[\SensitiveParameter] gebruiken bij paswoord
 * @param string $user
 * @param string $pass
 * @param string $db
 * @param string $host
 * @return PDO
 */
function dbConnect(string $user, string $pass, string $db, string $host = 'localhost'): PDO
{
    $connection = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);

    return $connection;
}

function getTodos(PDO $db, bool $withTrashed = false): array
{
    if($withTrashed === true)
    {
        $res = $db->query('SELECT * FROM todos');
    }
    else
    {
        $res = $db->query('SELECT * FROM todos WHERE deleted_at IS NULL');
    }

    return $res->fetchAll();
}

function addTodo(PDO $db, string $text): void
{
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    $res = $db->prepare('INSERT INTO todos (text) VALUES (:text)');
    $res->bindParam('text', $text);
    $res->execute();
}

function getPendingCount(PDO $db): int
{
    $res = $db->query('SELECT COUNT(*) FROM todos WHERE done = 0 and deleted_at IS NULL');

    return $res->fetchColumn();
}

function getCompletedCount(PDO $db): int
{
    $res = $db->query('SELECT COUNT(*) FROM todos WHERE done = 1 and deleted_at IS NULL');

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
