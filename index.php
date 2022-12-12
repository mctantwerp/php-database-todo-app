<?php

    include './vendor/autoload.php';
    include './functions/helpers.php';
    include './functions/database.php';

    registerExceptionHandler();

    $db = dbConnect(
        user: 'root',
        pass: '',
        db: 'todo_v2',
    );

    //php check if submit
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['todo']) && !empty($_POST['todo']))
        {
            addTodo($db, $_POST['todo']);
        }

        if(isset($_POST['check']))
        {
            checkTodo($db, $_POST['id']);
        }

        if(isset($_POST['uncheck']))
        {
            uncheckTodo($db, $_POST['id']);
        }

        if(isset($_POST['delete']))
        {
            deleteTodo($db, $_POST['id']);
        }
    }

    $todos = getTodos($db);

    include './snippets/header.php';
?>

<div class="text-3xl text-center font-bold mb-3 uppercase">Todo List</div>
    <div>
        <form method="POST" class="flex justify-center">
            <input type="text" name="todo" placeholder="Enter Todo" class="text-xl text-orange-800 placeholder-orange-400 py-2 px-5 bg-orange-100 rounded-l-full outline-orange-300">
            <button type="submit" class="text-xl text-orange-100 placeholder-orange-400 py-2 pr-5 pl-4 bg-orange-500 rounded-r-full">
                <?= svg('plus'); ?>
            </button>
        </form>
    </div>
    <div class="bg-gray-100 mt-5 p-5 rounded-xl shadow-lg text-gray-700">
        <h1 class="font-bold text-xl italic block mb-0 leading-none">Todo's</h1>
        <small class="block mb-5 mt-0 text-xs text-gray-500"><?= getPendingCount($db); ?> Todos pending, <?= getCompletedCount($db); ?> Completed.</small>
        <div class="max-h-80 overflow-y-auto">
            <table class="table-fixed w-full">
                <thead>
                    <tr>
                        <th class="w-1/6 text-center px-1 py-2 bg-orange-500 text-orange-100 rounded-tl-xl">#</th>
                        <th class="text-left px-1 py-2 bg-orange-500 text-orange-100">Details</th>
                        <th class="w-1/5 text-left px-1 py-2 bg-orange-500 text-orange-100 rounded-tr-xl">Action</th>
                    </tr>
                </thead>
                <tbody >
                    <?php if(count($todos) === 0): ?>
                    <tr class="odd:bg-orange-100 even:bg-orange-50">
                        <td class="text-center  px-1 py-2 text-orange-800" colspan="3">No Todos found. Add a few to begin.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($todos as $nr => $todo): ?>
                        <tr class="<?= $todo['done'] == 1 ? 'bg-green-100' : 'odd:bg-orange-100 even:bg-orange-50'; ?>">
                            <td class="text-center px-1 py-2 text-orange-800<?= getLine($todo); ?>"><?= $nr+1; ?></td>
                            <td class="px-1 py-2 text-orange-800<?= getLine($todo); ?>"><?= $todo['text']; ?></td>
                            <td class="text-center  px-1 py-2 text-orange-800 flex gap-3 justify-start no-underline">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $todo['id']; ?>">
                                    <?php if($todo['done'] == 0): ?>
                                    <button type="submit" name="check" class="text-orange-600">
                                        <?= svg('check'); ?>
                                    </button>
                                    <?php endif; ?>
                                    <?php if($todo['done'] == 1): ?>
                                    <button type="submit" name="uncheck" class="text-orange-600">
                                        <?= svg('cross'); ?>
                                    </button>
                                    <?php endif; ?>
                                    <button type="submit" name="delete" class="text-orange-600">
                                        <?= svg('trash'); ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
    include './snippets/footer.php';
?>
