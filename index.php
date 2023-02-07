<?php
    /**
     * Autoload all vendor packages
     */
    include('./vendor/autoload.php');

    /**
     * Define which namespaces to use
     */
    use App\Lib\T;
    use App\Lib\V;
    use App\Models\Todo;
    use App\Controllers\HomeController;

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
    T::load(HomeController::Class);

    /**
     * Get all todos
     */
    $todos = Todo::get();

    /**
     * Include the header snippet
     */
    V::snippet('layout/header');

    /**
     * Include the home snippet
     */
    V::snippet('pages/home', [
        'todos' => $todos
    ]);

    /**
     * Include the footer snippet
     */
    V::snippet('layout/footer');
?>
