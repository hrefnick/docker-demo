<?php
include "includes/header.php";
?>
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>Nick's Magic: The Gathering Database</h1>
            <hr>
        </div>
    </div>
	<div class="row">
		<div class="col-md-6">
			<h3>Sign Up</h3>
			<?php
          $accountCreated = false;
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $emailError = '';
            $passError = '';
            $formIsValid = true;

            if(isset($_POST['signup'])) {
                // Validation
                if (strpbrk($email, '!#$%^&*([<>]{}`~)=+') !== false) {
                    $emailError = "Email cannot have special characters!";
                    $formIsValid = false;
                }

                $emailSymbol = '@.';
                $validEmail = strpos($email, $emailSymbol);
                if($validEmail === false){
                    $emailError = "Please enter a valid email";
                    $formIsValid = false;
                }

                if (strpbrk($password, '$^&([<>]{}`~)=+') !== false) {
                    $emailError = "Password cannot contain <>()[]{}`~=+*$%";
                    $formIsValid = false;
                }

                if (strlen($password) < 8) {
                    $passError = "Password must be 8 characters long!";
                    $formIsValid = false;
                }

                if ($formIsValid) {

                    // encrypt password (after validation)
                    // don't use md5(), sha1(), etc.
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    // add user to database
                    $query = "INSERT INTO `MTG__Users` (`UserId`, `Email`, `Password`, `Role`) 
                                    VALUES (NULL, ?, ?, 'User')";

                    // prepare, bind, and execute query
                    $stmt = mysqli_prepare($db, $query);
                    mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
                    $result = mysqli_stmt_execute($stmt);

                    // check if record was created
                    if (mysqli_stmt_insert_id($stmt)) {
                        $accountCreated = true;
                        echo '<div class="alert alert-success">
                          <b>Account created!</b><br>Please login.
                        </div>';

                    } else {
                        echo '<div class="alert alert-danger">
                          <b>Error creating account!</b><br> (Email already used?)
                        </div>';
                    }
                }
            }
			?>

      <?php if(!$accountCreated): ?>
        <form method="post">
					<div class="form-group">
                        <span class="text-danger"><?= $emailError ?></span>
						<input type="text" name="email" class="form-control" placeholder="Enter Email" value="<?= $email ?>">
					</div>
					<div class="form-group">
                        <span class="text-danger"><?= $passError ?></span>
						<input type="password" name="password" class="form-control" placeholder="Create Password" value="<?= $password ?>">
					</div>
					<div class="form-group">
						<input type="submit" name="signup" class="btnSubmit" value="Sign Up">
					</div>
				</form>
      <?php endif; ?>
		</div>
		<div class="col-md-6 login-form-2">
			<h3>Login</h3>
			<?php
			if(isset($_POST['login'])){
				// get form values
				$email = $_POST['email'];
				$password = $_POST['password'];

				// get user record from database and check login
                $query = "SELECT UserId, Email, Password, Role
                FROM MTG__Users
                WHERE Email = ?";

                // prepare, bind, execute
                $stmt = mysqli_prepare($db, $query);
                mysqli_stmt_bind_param($stmt, 's', $email);
                $result = mysqli_stmt_execute($stmt);

                // bind these variables to the columns in the record (same order as SELECT clause
                mysqli_stmt_bind_result($stmt, $userId, $email, $hashedPassword, $role);

                // fetch values from the database into "bound" variables
                // (this would go in your while loop when you expect multiple results)
                mysqli_stmt_fetch($stmt);

                if(password_verify($password, $hashedPassword)){
                    // GREAT!  We still have more to do

                    // check if password needs to be rehashed
                    if(password_needs_rehash($hashedPassword, PASSWORD_DEFAULT)){
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $query = "UPDATE `MTG__Users` 
                                SET `Password` = ?
                                WHERE `MTG__Users`.`UserId` = ?";

                        $stmt = mysqli_prepare($db, $query) or die('Error in query');
                        mysqli_stmt_bind_param($stmt, ii, $password, $userId);
                        $result = mysqli_stmt_execute($stmt) or die('Error executing query');
                    }

                    // create a new session id (new cookie and session data)
                    session_regenerate_id(true);

                    // update the session with the user's info
                    $_SESSION['authUser']['email'] = $email;
                    $_SESSION['authUser']['role'] = $role;

                    // store this to tie cards to user
                    $_SESSION['authUser']['userId'] = $userId;


                    // redirect
                    header('Location: cards.php');
                    die();
                }else{
                    echo '<div class="alert alert-danger">
                            <strong>Invalid username or password.</strong><br>
                            Please try again.
                            </div>';
                }
			}

			// logout and redirect to login page
      if(isset($_GET['logout'])){
					// remove session data
                    // only remove user info, but leaves the same cookie and session data
					unset($_SESSION['authUser']);

          // destroy the session (and cookie)
          session_destroy();

          // redirect
          header("Location: login.php");
      }

			?>
      <?php if(isset($_SESSION['authUser'])): ?>
        <form method="get">
            <input type="submit" name="logout" class="btnSubmit" value="Log Out">
        </form>
      <?php else: ?>
				<form method="post">
					<div class="form-group">
						<input type="text" name="email" class="form-control" placeholder="Your Email" value="">
					</div>
					<div class="form-group">
						<input type="password" name="password" class="form-control" placeholder="Your Password" value="">
					</div>
					<div class="form-group">
						<input type="submit" name="login" class="btnSubmit" value="Login">
					</div>
				</form>
      <?php endif; ?>
		</div>
	</div>
</div>
<?php
include "includes/footer.php";
?>
