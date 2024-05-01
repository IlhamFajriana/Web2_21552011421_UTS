<?php
class Book {
    protected $title;
    protected $author;
    protected $year;
    protected $status; 

    public function __construct($title, $author, $year) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->status = 0; 
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getYear() {
        return $this->year;
    }

    public function getStatus() {
        return $this->status;
    }

    public function borrowBook() {
        if ($this->status === 0) {
            $this->status = 1;
            return true;
        } else {
            return false; 
        }
    }

    public function returnBook() {
        $this->status = 0;
    }
}
?>
