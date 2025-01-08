<tr class="odd:bg-orange-100 even:bg-orange-50">
    <td class="text-center  px-1 py-2 text-orange-800<?= getLine($todo); ?>"><?= $nr+1; ?></td>
    <td class=" px-1 py-2 text-orange-800<?= getLine($todo); ?>"><?= $todo['text']; ?></td>
    <td class="text-center  px-1 py-2 text-orange-800 flex gap-3 justify-start">
        <form method="POST">
            <input type="hidden" name="id" value="<?= $todo['id']; ?>" />
            <?php if($todo['done'] === 0): ?>
            <button name="check" class="text-orange-600">
                <?= svg('check'); ?>
            </button>
            <?php endif; ?>
            <?php if($todo['done'] === 1): ?>
            <button name="uncheck" class="text-orange-600">
                <?= svg('cross'); ?>
            </button>
            <?php endif; ?>
            <button type="submit" name="delete" class="text-orange-600">
                <?= svg('trash'); ?>
            </button>
        </form>
    </td>
</tr>
