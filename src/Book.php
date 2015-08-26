<?php 
	
	class Book
	{
		private $title;
		private $id;
		
		function __construct($title, $id = null)
		{
			$this->title = $title;
			$this->id = $id;
		}
		
		function setTitle($new_name)
		{
			$this->title = $title;
		}
		
		function getTitle()
		{
			return $this->title;
		}
		 
		function setId($new_id)
		{
			$this->id = $new_id;
		}
		
		function getId()
		{
			return $this->id;
		}
		
		function save()
		{
			$GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ({$this->getTitle()});");
			$this->setId($GLOBALS['DB']->lastInsertId());
		}
		
		static function getAll()
		{
			$returned_books = $GLOBALS['DB']->query("SELECT * FROM booksl;");
			$books = array();
			foreach($returned_books as $book)
			{
				$title = $book['title'];
				$this = $book['id'];
				$new_book = new Book($title, $id);
				array_push($books, $new_book);
			}
			return $books;
		}
		
		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM books;");
		}
		
		function getAuthors()
		{
			$authors = array();
			$results = $GLOBALS['DB']->query("SELECT authors.* FROM
											  books JOIN authors_books ON (books.id = authors_books.book_id)
											  		JOIN authors ON (authors_books.author_id = authors.id)
											  WHERE books.id = {$this->getId()};");
			foreach($results as $author) {
				$author_name = $author['name'];
				$author_id = $author['id'];
				$new_author = new Author($author_name, $author_id);
				array_push($authors, $new_author); 
			}
			return $authors;
		}
		
		function addAuthor($new_author)
		{
			$GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$new_author->getId()}, {$this->getId()});");
		}
		
		static function find($search_id)
		{
			$found_book = null;
			$books = Book::getAll();
			foreach($books as $book) {
				$book_id = $student->getId();
				if($book_id == $search_id) {
					$found_book = $book;
				}
			}
			return $found_book;
		}
	}
?>