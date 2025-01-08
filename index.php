<?php

include './vendor/autoload.php';
include './functions/helpers.php';
include './functions/database.php';

registerExceptionHandler();

$db = dbConnect(
    user: 'root',
    pass: '',
    db: 'kdg',
);

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if(isset($_POST['todo']) && !empty($_POST['todo']))
    {
        addTodo($db, $_POST['todo']);
    }

    if(isset($_POST['delete']))
    {
        deleteTodo($db, $_POST['id']);
    }

    if(isset($_POST['check']))
    {
        checkTodo($db, $_POST['id']);
    }

    if(isset($_POST['uncheck']))
    {
        uncheckTodo($db, $_POST['id']);
    }
}

$todos = getTodos($db);

include './snippets/layout/header.php';

?>

<div class="text-3xl text-center font-bold mb-3 uppercase">Todo List</div>
    <?php include('./snippets/todos/add.php'); ?>
    <div class="bg-gray-100 mt-5 p-5 rounded-xl shadow-lg text-gray-700">
        <h1 class="font-bold text-xl italic block mb-0 leading-none">Todo's</h1>
        <small class="block mb-5 mt-0 text-xs text-gray-500"><?= getPendingCount($db); ?> Todos pending, <?= getCompletedCount($db); ?> Completed.</small>
        <?php include('./snippets/todos/all.php'); ?>
    </div>
<?php
include './snippets/layout/footer.php';
