<?php 
	
	class Author
	{
		private $name;
		private $id;
		
		function __construct($name, $id = null)
		{
			$this->name = $name;
			$this->id = $id;
		}
		
		function setName($new_name)
		{
			$this->name = $new_name;
		}
		
		function getName()
		{
			return $this->name;
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
			$GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}');");
			$this->setId($GLOBALS['DB']->lastInsertId());
		}
		
		function update($new_name)
		{
			$GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}' WHERE id = {$this->getId()};");
			$this->setName($new_name);
		}
		
		static function getAll()
		{
			$returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
			$authors = array();
			foreach($returned_authors as $author)
			{
				$name = $author['name'];
				$id = $author['id'];
				$new_author = new Author($name, $id);
				array_push($authors, $new_author);
			}
			return $authors;
		}
		
		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM authors;");
		}
		
		function delete()
		{
			$GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
			$GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->getId()};");
		}
		
		function getBooks()
		{
			$books = array();
			$results = $GLOBALS['DB']->query("SELECT books.* FROM
											  authors JOIN authors_books ON (authors.id = authors_books.author_id)
											  		  JOIN books ON (authors_books.book_id = books.id)
											  WHERE authors.id = {$this->getId()};");
			foreach($results as $book) {
				$book_title = $book['title'];
				$book_id = $book['id'];
				$new_book = new Book($book_title, $book_id);
				array_push($books, $new_book);
			}
			return $books;
		}
		
		function addBook($new_book)
		{
			$GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$this->getId()}, {$new_book->getId()});");
		}
		
		static function find($search_id)
		{
			$found_author = null;
			$authors = Author::getAll();
			foreach($authors as $author) {
				$author_id = $author->getId();
				if($author_id == $search_id) {
					$found_author = $author;
				}
			}
			return $found_author;
		}
		
		static function searchAuthor($search_author) 
		{
			$returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors WHERE name = '{$search_author}';");
			$authors = array();
			foreach($returned_authors as $author)
			{
				$name = $author['name'];
				$id = $author['id'];
				$new_author = new Author($name, $id);
				array_push($authors, $new_author);
			}
			return $authors;
		}
	}
?>