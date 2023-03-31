<?php

Class Post {

 
  private $pdo; // set the database connection object as a private property of the class
  private $table = "posts";
  public $id;

  
   // Constructor method to initialize the database connection
    public function __construct($pdo) {
        $this->pdo = $pdo; 
    }

     // Create a new blog post
    public function createPost($title, $content , $author_id) {
        // Prepare the SQL statement with placeholders for the values to be inserted
        $query = "INSERT INTO ".$this->table ."(title, content,author_id) VALUES (:title, :content, :author_id)";
       
        // Use a try-catch block to handle any exceptions that may occur during the query execution
        try {
            // Prepare the SQL statement for execution
            $stmt = $this->pdo->prepare($query);

            // Bind the values to the placeholders in the prepared statement
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':author_id', $author_id);
             
            // Execute the prepared statement
            $stmt->execute();

            // Return the ID of the newly created post
            return true;
        } catch(PDOException $e) {
            // Handle any exceptions that may occur during the query execution
            echo $e->getMessage();
            return false;
        }
    }

    
      // Read all blog posts
    public function readAllPosts() {

       // Prepare the SQL statement to select all posts
         $query = 'SELECT ' . $this->table . '.* , users.username FROM ' . $this->table . ' LEFT JOIN users
        ON(' . $this->table . '.author_id = users.id
         )ORDER BY ' . $this->table . '.created_at DESC' ;
    


    // Use a try-catch block to handle any exceptions that may occur during the query execution
        try {
            // Prepare the SQL statement for execution
            $stmt = $this->pdo->prepare($query);

            // Execute the prepared statement
            $stmt->execute();

            // Fetch the results as an associative array
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the results
            return $results;
        } catch(PDOException $e) {
            // Handle any exceptions that may occur during the query execution
            echo $e->getMessage();
            return false;
        }


    }

    // Read a single post
    function readOne($id)
    {


             // SQL query to select a single post from the database
        $query = "SELECT p.title, p.content , p.author_id , p.created_at, u.username as author_name 
        FROM " . $this->table . " p INNER JOIN users u
         ON p.author_id = u.id WHERE p.id = ? LIMIT 0,1";         
        // Use a try-catch block to handle any exceptions that may occur during the query execution
        try {
            // Prepare the SQL statement for execution
            $stmt = $this->pdo->prepare($query);

            // Bind the id parameter
            $stmt->bindParam(1, $id);

            // Execute the prepared statement
            $stmt->execute();
            
            // Get the row data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            
            // Return the results
            return $row;
            
        } catch (PDOException $e) {
            // Handle any exceptions that may occur during the query execution
            echo $e->getMessage();
            return false;
        }
    }



    // Delete an existing post
    function delete()
    {
        // SQL query to delete an existing post from the database
        $query = "DELETE FROM " . $this->table . " WHERE id=:id";


        // Use a try-catch block to handle any exceptions that may occur during the query execution
        try {
            // Prepare the SQL statement for execution
            $stmt = $this->pdo->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind the data
            $stmt->bindParam(":id", $this->id);

            // Execute the prepared statement
            $stmt->execute();


            // Return the results
            return true;
        } catch (PDOException $e) {
            // Handle any exceptions that may occur during the query execution
            echo $e->getMessage();
            return false;
        }
    }

    // Update an existing post
    function update($title, $content, $id)
    {
        // SQL query to update an existing post in the database
        $query = "UPDATE " . $this->table . " SET title=:title, content=:content WHERE id=:id";

        // Prepare the query for execution
        $stmt = $this->pdo->prepare($query);

        // Clean data
        $title = htmlspecialchars(strip_tags($title));
        $content = htmlspecialchars(strip_tags($content));
        $id = htmlspecialchars(strip_tags($id));

        // Bind the data
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":id", $id);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    
}


?>