<div class="max-h-80 overflow-y-auto">
    <table class="table w-full">
        <thead>
            <tr>
                <th class="text-center px-1 py-2 bg-orange-500 text-orange-100 rounded-tl-xl">#</th>
                <th class="text-left px-1 py-2 bg-orange-500 text-orange-100">Details</th>
                <th class=" px-1 py-2 bg-orange-500 text-orange-100 rounded-tr-xl">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($todos) === 0): ?>
                <tr class="odd:bg-orange-100 even:bg-orange-50">
                    <td class="text-center  px-1 py-2 text-orange-800" colspan="3">No Todos found. Add a few to begin.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($todos as $nr => $todo): ?>
                <?php snippet('todos/todo', [
                    'nr' => $nr,
                    'todo' => $todo,
                ]); ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
