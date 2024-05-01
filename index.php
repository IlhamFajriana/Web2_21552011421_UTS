<?php

require_once 'Book.php';
require_once 'Library.php';
require_once 'ReferenceBook.php';

$bookData = [
    new Book("Harry Potter and the Philosopher's Stone", "J.K. Rowling", 1997),
    new Book("To Kill a Mockingbird", "Harper Lee", 1960),
    new Book("1984", "George Orwell", 1949),
    new ReferenceBook("The Elements of Style", "William Strunk Jr. and E.B. White", 1959, "9780143112723", "Penguin")
];

$library = new Library($bookData);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['author']) && isset($_POST['year'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $library->addBook(new Book($title, $author, $year));
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $searchResult = $library->searchByTitleOrAuthor($keyword);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['borrow'])) {
    $bookTitle = $_POST['borrow'];
    $library->borrowBook($bookTitle);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return'])) {
    $bookTitle = $_POST['return'];
    $library->returnBook($bookTitle);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
    $bookTitle = $_POST['remove'];
    $library->removeBook($bookTitle);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisPer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>System Perpustakaan</h1>
        <form action="index.php" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author">
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" class="form-control" id="year" name="year">
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
        <hr>
        <form action="index.php" method="get">
            <div class="form-group">
                <label for="search">Search by Title or Author:</label>
                <input type="text" class="form-control" id="search" name="search">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <?php if (isset($searchResult) && !empty($searchResult)): ?>
            <h2>Search Result</h2>
            <ul>
                <?php foreach ($searchResult as $book): ?>
                    <li><?= $book->getTitle() ?> by <?= $book->getAuthor() ?> (<?= $book->getYear() ?>)
                        <?php if ($book->getStatus() === 0): ?>
                            <span class="text-success">(Available)</span>
                        <?php else: ?>
                            <span class="text-danger">(Borrowed)</span>
                        <?php endif; ?>
                        <form action="index.php" method="post" style="display:inline;">
                            <button type="submit" class="btn btn-success" name="borrow" value="<?= $book->getTitle() ?>" <?= $book->getStatus() === 1 ? 'disabled' : '' ?>>Borrow</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <hr>
        <h2>Available Books</h2>
        <ul>
            <?php foreach ($library->getAvailableBooks() as $book): ?>
                <li><?= $book->getTitle() ?> by <?= $book->getAuthor() ?> (<?= $book->getYear() ?>)
                    <span class="text-success">(Available)</span>
                    <form action="index.php" method="post" style="display:inline;">
                        <button type="submit" class="btn btn-success" name="borrow" value="<?= $book->getTitle() ?>" <?= $book->getStatus() === 1 ? 'disabled' : '' ?>>Borrow</button>
                    </form>
                    <form action="index.php" method="post" style="display:inline;">
                        <button type="submit" class="btn btn-danger" name="remove" value="<?= $book->getTitle() ?>">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <h2>Borrowed Books</h2>
        <ul>
            <?php foreach ($library->getBooks() as $book): ?>
                <?php if ($book->getStatus() === 1): ?>
                    <li><?= $book->getTitle() ?> by <?= $book->getAuthor() ?> (<?= $book->getYear() ?>)
                        <span class="text-danger">(Borrowed)</span>
                        <form action="index.php" method="post" style="display:inline;">
                            <button type="submit" class="btn btn-primary" name="return" value="<?= $book->getTitle() ?>">Return</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
