# Todo v1

## Connect with sql

```php
$connection = new PDO('mysql:host=localhost;dbname=todo_v1','root','');
```

## Query something

```php
$selectStatement = $connection->prepare('SELECT * FROM todos');
$selectStatement->setFetchMode(PDO::FETCH_ASSOC);
$selectStatement->execute();

$todos = $selectStatement->fetchAll();
```

## Other queries

```php
$insertStatement = $connection->prepare('INSERT INTO todos (title) VALUES (:title)');
$insertStatement->bindParam('title',$_POST["title"]);
$insertStatement->execute();
```
