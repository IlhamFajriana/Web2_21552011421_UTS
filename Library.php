<?php
class Library {
    private $books = [];
    private $maxBorrowedBooks = 3; 

    public function __construct($bookData = []) {
        foreach ($bookData as $book) {
            $this->addBook($book);
        }
    }

    public function addBook($book) {
        $this->books[] = $book;
    }

    public function searchByTitleOrAuthor($keyword) {
        $result = [];
        foreach ($this->books as $book) {
            if (stripos($book->getTitle(), $keyword) !== false || stripos($book->getAuthor(), $keyword) !== false) {
                $result[] = $book;
            }
        }
        return $result;
    }

    public function borrowBook($bookTitle) {
        $borrowedBooksCount = 0;
        foreach ($this->books as $book) {
            if ($book->getStatus() === 1) {
                $borrowedBooksCount++;
            }
        }
        if ($borrowedBooksCount < $this->maxBorrowedBooks) {
            foreach ($this->books as $book) {
                if (strcasecmp($book->getTitle(), $bookTitle) === 0) {
                    return $book->borrowBook();
                }
            }
        }
        return false;
    }

    public function returnBook($bookTitle) {
        foreach ($this->books as $book) {
            if (strcasecmp($book->getTitle(), $bookTitle) === 0) {
                $book->returnBook();
                return true;
            }
        }
        return false;
    }

    public function getAvailableBooks() {
        $availableBooks = [];
        foreach ($this->books as $book) {
            if ($book->getStatus() === 0) {
                $availableBooks[] = $book;
            }
        }
        return $availableBooks;
    }

    public function sortByYear() {
        usort($this->books, function($a, $b) {
            return $a->getYear() - $b->getYear();
        });
    }

    public function sortByAuthor() {
        usort($this->books, function($a, $b) {
            return strcasecmp($a->getAuthor(), $b->getAuthor());
        });
    }

    public function removeBook($bookTitle) {
        foreach ($this->books as $key => $book) {
            if (strcasecmp($book->getTitle(), $bookTitle) === 0) {
                unset($this->books[$key]);
                return true;
            }
        }
        return false;
    }
}
?>
