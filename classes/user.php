<?php
class User {

     private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
   
    public function register($username , $email , $password)
    {
        
          // Validate the form data
            $errors = array();
            
            
            if (empty($username)) {
                $errors[] = "Please enter a username.";
            }
            if (empty($email)) {
                $errors[] = "Please enter an email address.";
            }
            if (empty($password)) {
                $errors[] = "Please enter a password.";
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Please enter a valid email address.";
            }

            

           // Check if the username or email address is already in use
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            
            $stmt->execute(array(':username' => $username, ':email' => $email));
     
            
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $errors[] = "Username or email address already in use.";
            }
                        // If there are no errors, insert the user into the database

              if (empty($errors)) {
                  
                $hash = password_hash($password, PASSWORD_DEFAULT);

                
                $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                
                $stmt->execute(array(':username' => $username, ':email' => $email, ':password' => $hash));

                return true;
            } else {
                return $errors;
            }
            
            
    }



    public function login( $email , $password)
    {
                
            

           // Check if the username or email address is already in use
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE  email = :email");
            
        $stmt->execute(array( ':email' => $email));  

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ['id'=>'1' , 'username'=>''ahmed_tahoon','email'=>'dsfsdf' ,'password'=>''1213]
      
        if ($user && password_verify($password, $user['password'])) {
        
            // The username and password are correct, so set the session variables and redirect to the home

            $_SESSION['user_id']=$user['id'];
            $_SESSION['username']=$user['username'];
            $_SESSION['email']=$user['email'];
            
            header('Location: home.php');
            return true;
            exit;
        } else {
            // The username or password is incorrect
            return "The email or password is incorrect";
        }
        
            
    }

    
}
?>