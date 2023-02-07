<?php
    /**
     * Autoload all vendor packages
     */
    include('./vendor/autoload.php');

    /**
     * Define which namespaces to use
     */
    use App\Lib\T;
    use App\Models\Todo;

    /**
     * Load environment variables (env package)
     * Will load everything from .env to $_ENV
     */
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    /**
     * Register the exception handler
     */
    T::registerExceptionHandler();

    /**
     * Was there a POST request?
     */
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        /**
         * Todo field was filled in, create a new todo
         */
        if(isset($_POST['todo']) && !empty($_POST['todo']))
        {
            $todo = new Todo();
            $todo->setText($_POST['todo']);
            $todo->save();
        }

        /**
         * Check was posted, so set the todo as done
         */
        if(isset($_POST['check']))
        {
            $todo = new Todo($_POST['id']);
            $todo->setDone();
            $todo->save();
        }

        /**
         * Uncheck was posted, so set the todo as undone
         */
        if(isset($_POST['uncheck']))
        {
            $todo = new Todo($_POST['id']);
            $todo->setUndone();
            $todo->save();
        }

        /**
         * Delete was posted, so delete the todo
         */
        if(isset($_POST['delete']))
        {
            $todo = new Todo();

            /**
             * Find the todo by id and delete it
             * Objects can be chained, so you can call methods on the object
             * if the object is returned by the method
             */
            $todo->find($_POST['id'])->delete();
        }
    }

    /**
     * Get all todos
     */
    $todos = Todo::get();

    /**
     * Include the header snippet
     */
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
        <small class="block mb-5 mt-0 text-xs text-gray-500"><?= Todo::pending(); ?> Todos pending, <?= Todo::completed(); ?> Completed.</small>
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
                        <tr class="<?= $todo->isDone() ? 'bg-green-100' : 'odd:bg-orange-100 even:bg-orange-50'; ?>">
                            <td class="text-center px-1 py-2 text-orange-800<?= getLine($todo); ?>"><?= $nr+1; ?></td>
                            <td class="px-1 py-2 text-orange-800<?= getLine($todo); ?>"><?= $todo->getText(); ?></td>
                            <td class="text-center  px-1 py-2 text-orange-800 flex gap-3 justify-start no-underline">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $todo->getId(); ?>">
                                    <?php if($todo->isNotDone()): ?>
                                    <button type="submit" name="check" class="text-orange-600">
                                        <?= svg('check'); ?>
                                    </button>
                                    <?php endif; ?>
                                    <?php if($todo->isDone()): ?>
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
    /**
     * Include the footer snippet
     */
    include './snippets/footer.php';
?>
