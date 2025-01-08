<?php

include './vendor/autoload.php';
include './functions/helpers.php';
include './functions/database.php';

registerExceptionHandler();

$db = dbConnect(
    user: 'root',
    pass: '',
    db: 'kdg-todo',
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (get('todo')) {
        addTodo($db, get('todo'));
    }

    if (get('delete')) {
        deleteTodo($db, get('id'));
    }

    if (get('check')) {
        checkTodo($db, get('id'));
    }

    if (get('uncheck')) {
        uncheckTodo($db, get('id'));
    }
}

$todos = getTodos($db);

snippet('layout/header');

?>

<div class="text-3xl text-center font-bold mb-3 uppercase">Todo List</div>

<?php snippet('todos/add'); ?>

<div class="bg-gray-100 mt-5 p-5 rounded-xl shadow-lg text-gray-700">
    <h1 class="font-bold text-xl italic block mb-0 leading-none">Todo's</h1>
    <small class="block mb-5 mt-0 text-xs text-gray-500"><?= getPendingCount($db); ?> Todos pending,
        <?= getCompletedCount($db); ?> Completed.</small>
    <?php snippet('todos/all', [
        'todos' => $todos,
    ]); ?>
</div>

<?php snippet('layout/footer'); ?>
