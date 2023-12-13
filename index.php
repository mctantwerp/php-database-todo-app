<?php
try
{
    $connection = new PDO('mysql:host=localhost;dbname=santa_shop','root','');
}
catch (Exception $exception)
{
    echo $exception->getMessage();
    die;
}

/**
 * Query to get all todos
 */
$selectStatement = $connection->prepare('SELECT * FROM todos');
$selectStatement->setFetchMode(PDO::FETCH_ASSOC);
$selectStatement->execute();

$todos = $selectStatement->fetchAll();

/**
 * Form to get user input and add it to an insert query
 */
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST["title"]))
    {
        $insertStatement = $connection->prepare('INSERT INTO todos (title) VALUES (:title)');
        $insertStatement->bindParam('title',$_POST["title"]);
        $insertStatement->execute();
    }

    if(isset($_POST["id_to_delete"]))
    {
        $deleteStatement = $connection->prepare('DELETE FROM todos WHERE id = :id');
        $deleteStatement->bindParam('id',$_POST['id_to_delete']);
        $deleteStatement->execute();
    }

    header('Location: index.php');
    die;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDo</title>
</head>
<body>
    <h1>ToDo:</h1>
    <?php foreach($todos as $todo): ?>
    <li>
        <?=$todo["title"] ?>
        <form action="index.php" method="post">
            <input type="hidden" name="id_to_delete" value="<?= $todo['id'] ?>">
            <button>Delete</button>
        </form>
    </li>
    <?php endforeach; ?>

    <form action="index.php" method="post">
        <input type="text" name="title">
        <button>Add!</button>
    </form>

</body>
</html>
