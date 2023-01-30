<?php
    $pageTitle = "Delete Card";
    include "includes/header.php";

	// get card id from url
    $id = $_GET['id'] ?? '';

    // sanitize
    $id = intval($id);

    // build query
    $query = "SELECT * FROM MTG__Card WHERE CardId = '$id'";

    // execute query
    $result = mysqli_query($db, $query) or die('Error loading city place.');

    // get one record from the database
    $card = mysqli_fetch_array($result, MYSQLI_ASSOC);
if(isset($_SESSION['authUser']) and $_SESSION['authUser']):
?>
<h1>Delete Place</h1>

<?php
if(isset($_POST['submit'])){
    // get the values from the form
    $confirm = $_POST['submit'];
    $id = $_POST['id'] ?? 0;

    $id = intval($id);

    // only delete if they clicked yes
    if($confirm === "Yes"){
        // delete the database
        $query ="DELETE FROM `MTG__Card` WHERE `MTG__Card`.`CardId` = '$id' LIMIT 1;";

        // execute query
        $result = mysqli_query($db, $query) or die('Error deleting card.');
    }


    // check if record was edited
    //if(mysqli_affected_rows($db)) {
        // redirect back to the city page
        header('Location: cards.php');
    //}

} // end if $_POST['submit']
?>

<form method="post">
    <p>Are you sure you want to delete "<?= $card['Name'] ?>"?</p>
    <input type="hidden" name="id" value="<?= $card['CardId'] ?>">
    <input type="submit" name="submit" value="Cancel" class="btn btn-secondary btn-sm">
    <input type="submit" name="submit" value="Yes" class="btn btn-danger btn-sm">
</form>

<?php else: ?>
    <div class="container">
        <h1>Admin Area</h1>
        <div class="alert alert-danger">Access denied. Please <a href="login.php">login</a>.</div>
    </div>

<?php
endif;
// close database connection (put in footer to avoid doing multiple times)
mysqli_close($db);
?>
</body>
</html>
