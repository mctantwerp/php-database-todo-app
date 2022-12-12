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
        $selectStatement = $db->prepare('SELECT * FROM todos');
    }
    else
    {
        $selectStatement = $db->prepare('SELECT * FROM todos WHERE deleted_at IS NULL');
    }

    $selectStatement->setFetchMode(PDO::FETCH_ASSOC);
    $selectStatement->execute();

    return $selectStatement->fetchAll();
}

function addTodo(PDO $db, string $text): void
{
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    $insertStatement = $db->prepare('INSERT INTO todos (text) VALUES (:text)');
    $insertStatement->bindParam('text', $text);
    $insertStatement->execute();
}

function getPendingCount(PDO $db): int
{
    $selectStatement = $db->prepare('SELECT COUNT(*) FROM todos WHERE done = 0 and deleted_at IS NULL');
    $selectStatement->setFetchMode(PDO::FETCH_ASSOC);
    $selectStatement->execute();

    return $selectStatement->fetchColumn();
}

function getCompletedCount(PDO $db): int
{
    $selectStatement = $db->prepare('SELECT COUNT(*) FROM todos WHERE done = 1');
    $selectStatement->setFetchMode(PDO::FETCH_ASSOC);
    $selectStatement->execute();

    return $selectStatement->fetchColumn();
}

function checkTodo(PDO $db, int $id): void
{
    $now = date('Y-m-d H:i:s');

    $updateStatement = $db->prepare('UPDATE todos SET done = 1, updated_at = :updated_at WHERE id = :id');
    $updateStatement->bindParam('id', $id);
    $updateStatement->bindParam('updated_at', $now);
    $updateStatement->execute();
}

function uncheckTodo(PDO $db, int $id): void
{
    $now = date('Y-m-d H:i:s');

    $updateStatement = $db->prepare('UPDATE todos SET done = 0, updated_at = :updated_at WHERE id = :id');
    $updateStatement->bindParam('id', $id);
    $updateStatement->bindParam('updated_at', $now);
    $updateStatement->execute();
}

function deleteTodo(PDO $db, int $id): void
{
    // $deleteStatement = $db->prepare('DELETE FROM todos WHERE id = :id');
    // $deleteStatement->bindParam('id', $id);
    // $deleteStatement->execute();

    $now = date('Y-m-d H:i:s');

    $updateStatement = $db->prepare('UPDATE todos SET deleted_at = :deleted_at WHERE id = :id');
    $updateStatement->bindParam('id', $id);
    $updateStatement->bindParam('deleted_at', $now);
    $updateStatement->execute();
}
