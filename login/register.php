<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    // Check if username is empty
    if(empty(trim($_POST["username"]))) {
        $username_err = "Username cannot be blank";
    }
    else {
        $sql = " SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            //set value of param username
            $param_username = trim($_POST['username']);

            //try to execute this statement
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) ==1)
                {
                    $username_err = "This Username is Already Taken";
                }
                else {
                    $username = trim($_POST['username']);
                }
            }
            else {
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);
}


// Check for password
if(empty(trim($_POST['password']))) {
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5) {
    $password_err = "Password cannot be less than 5 characters";
}
else {
    $password = trim($_POST['password']);
}

//check for confirm password field
if(trim($_POST['password']) != trim($_POST['confirm_password'])) {
    $password_err = "Passwords should match";
}

//If there were no errors, go ahead and insert into database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
    $sql = " INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_password =  password_hash($password,
        PASSWORD_DEFAULT);
 
        //Try to execute query
        if(mysqli_stmt_execute($stmt)) {
            header("location: login.php");
        }
        else {
            echo "Something Went Wrong...Cannot Redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);



?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>PHP Register</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">LOGIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
        </div>
    </nav>

    <div class="container">
        <h3 class="py-5">Please Register Here</h3>
        <form action="" method="post">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Username</label>
                    <input type="text" class="form-control" name="username" id="inputEmail4" placeholder="Email">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Password</label>
                    <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="Password">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" id="inputPassword" placeholder="Confirm Password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>