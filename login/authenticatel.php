<?php
require_once 'config/config.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $username = filter_input(INPUT_POST, 'username');
  $password = filter_input(INPUT_POST, 'password');

  // Get DB instance.
  $db = getDbInstance();

  // Validate the email address
  if (!empty($_POST['username'])) {
    $e = mysqli_real_escape_string($conn, $_POST['username']);
  } else {
    $e = FALSE;
    echo '<p class="error">You forgot to enter your email address.</p>';
  }
  //Validate the password
  if (!empty($_POST['password'])) {
    $p = mysqli_real_escape_string($conn, $_POST['password']);
  } else {
    $p = FALSE;
    echo '<p class="error">You forgot to enter your password.</p>';
  }

  if($e && $p){
    // Retrieve the user_name and password for that email/password combination
    $check = "SELECT user_name, password FROM admin_accounts WHERE (user-name='$e' AND password='$p')";
    // Run the query and assign it to the variable $result
    $result = mysqli_query ($conn, $check);
    // Count the number of rows that match the email/password combination

    if (@mysqli_num_rows($result) > 0) {//if one database row (record) matches the input:-
      // Start the session, fetch the record and insert the three values in an array
      session_start(); #3
      $_SESSION = mysqli_fetch_array($result, MYSQLI_ASSOC);
      // Ensure that the user level is an integer.
      // $_SESSION['user_level'] = (int) $_SESSION['user_level'];
      // Use a ternary operation to set the URL #4
      // $url = ($_SESSION['user_level'] === 1) ? 'admin-page.php' : 'members-page.php';
      header('Location: index.php');
      // Make the browser load either the members’ or the admin page
      // exit(); // Cancel the rest of the script
      // mysqli_free_result($result);
      // mysqli_close($conn);
    } else { // No match was made.
      header('Location: index.php');
      //
      // echo '<p class="error">The e-mail address and password entered do not match our records
      // <br>Perhaps you need to register, just click the Register button on the header menu</p>';
    }
  }
  else { // If there was a problem.
  echo '<p class="error">Please try again.</p>';
  }
  mysqli_close($conn);
} // End of SUBMIT conditional.
?>
