<?php

namespace App\Controllers;

use App\Models\Todo;

class HomeController
{
    public function __invoke()
    {
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
    }

    private function add()
    {

    }
}
