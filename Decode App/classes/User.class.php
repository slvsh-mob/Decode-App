<?php
class User{
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    private $password;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $query = "INSERT INTO " . $this->table_name . " (username, password) VALUES (:a, :b)";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(":a", $this->username);
        $stmt->bindParam(":b", $this->password);
    }

    public function validate($user_password){
        //check if username exists, if it does, then verify the password
        $query = "SELECT * FROM " . $this->table_name . "WHERE username = :a";
        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $stmt->bindParam(":a", $this->username);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num == 1){
            $temp = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $temp['id'];
            $this->password = $temp['password'];
            if(password_verify($user_password, $this->password)){
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $error = 'none';

                // Redirect user to welcome page
                header("location: GreatAmerican.php");
            }else{
                // Display an error message if password is not valid
                $error = "The password you entered was not valid.";
            }
        }else{
            // Display an error message if username doesn't exist
            $error = "No account found with that username.";
        }
        return $error;
    }
}

?>