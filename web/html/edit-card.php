<?php
$pageTitle = "Edit Card";
require "includes/header.php";

	// get card id
    $id = $_GET['id'] ?? '2';

    // sanitize id
    $id = intval($id);

    // build query
    $query = "SELECT * FROM MTG__Card WHERE CardId = '$id'";

    // execute query
    $result = mysqli_query($db, $query) or die('Error loading card.');

    // get one record from the database
    $card = mysqli_fetch_array($result, MYSQLI_ASSOC);
if(isset($_SESSION['authUser']) and $_SESSION['authUser']):
?>
<h1>Edit Card</h1>

<?php
$formIsValid = true;
$nameError = '';
$manaError = '';
$colorError = '';
$legendError = '';
$typeError = '';
$subError = '';
$ptError = '';
$descriptionError = '';
$setError = '';

$name = $_POST['name'] ?? '';
$manaCost = $_POST['manaCost'] ?? '';
$colorId = $_POST['colorId'] ?? 'White';
$typeId = $_POST['typeId'] ?? '1';
$subType = $_POST['subType'] ?? '';
$description = $_POST['description'] ?? '';
$powerToughness = $_POST['powerToughness'] ?? '';
$legendary = $_POST['legendary'] ?? '0';
$setId = $_POST['setId'] ?? '1';

if(isset($_POST['submit'])) {

    if($_SESSION['csrf_token'] != $_POST['csrf_token']){
        die('Invalid Token');
    }

    // check the name input
    if (strlen($name) < 2) {
        $nameError = "Name must be at least 2 characters.";
        $formIsValid = false;
    }

    // get the userid from the session
    $userId = $_SESSION['authUser']['userId'];

    // check the name input
    if (strlen($name) < 2) {
        $nameError = "Name must be at least 2 characters.";
        $formIsValid = false;
    }

    // checks name for numbers and special characters
    if (strpbrk($name, '0123456789!@#$%^&*[]{}`~-_=+') !== false) {
        $nameError = "Name cannot have special characters!";
        $formIsValid = false;
    }

    // checks that at least 1 character is entered for mana cost
    if (strlen($manaCost) === 0) {
        $manaError = "Mana Cost must have at least 1 character! (Enter 0 for No Mana Cost)";
        $formIsValid = false;
    }

    // checks for special characters and not allowed characters
    if (strpbrk($manaCost, 'ADEFHIJKLMNOPQSTVYZadefhijklmnopqstvyz,\"\'!@#$%^&*([]{`~})-_=+') !== false) {
        $manaError = "Mana Cost can only use limited characters! (Numbers and XCWUBRG)";
        $formIsValid = false;
    }

    // checks for non-numbers
    if (strpbrk($typeId, 'ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvxyz,\"\'!@#$%^&*([]`~{})-_=+') !== false) {
        $typeError = "Typing cannot have numbers or special characters!";
        $formIsValid = false;
    }

    // checks for non-numbers
    if (strpbrk($colorId, 'ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvxyz,\"\'!@#$%^&*([]`~{})-_=+') !== false) {
        $colorError = "Color ID cannot have letters or special characters!";
        $formIsValid = false;
    }

    // checks for non-numbers
    if (strpbrk($setId, 'ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvxyz,\"\'!@#$%^&*([]`~{})-_=+') !== false) {
        $setError = "Set ID cannot have letters or special characters!";
        $formIsValid = false;
    }

    // check the legend input
    if(!in_array($legendary, ['0', '1'])){
        $legendError = "Please choose a Legend Status.";
        $formIsValid = false;
    }

    // checks for numbers and special characters
    if (strpbrk($subType, '1234567890,\"\'!@#$%^&*([]{})-_=+') !== false) {
        $subError = "Cannot use special characters or numbers!";
        $formIsValid = false;
    }

    // checks for special characters and letters that aren't allowed
    if (strpbrk($powerToughness, 'ADEFHIJKLMNOPQSTVXYZadefhijklmnopqstvxyz,\"\'!@#$%^&*([`~]{})-_=+') !== false) {
        $ptError = "Power/Toughness or Loyalty can only use limited characters! (Numbers and /)";
        $formIsValid = false;
    }

    // checks description for special characters, allows empty
    if (strpbrk($description, '><@#$%^&*[]{}_=`~\|') !== false) {
        $descriptionError = "Cannot use special characters!";
        $formIsValid = false;
    }

    if($formIsValid) {
        // Sanitation
        $id = intval($id);
        $name = strip_tags($name);
        $manaCost = strip_tags($manaCost);
        $colorId = intval($colorId);
        $typeId = intval($typeId);
        $subType = strip_tags($subType);
        $description = strip_tags($description);
        $powerToughness = strip_tags($powerToughness);
        $legendary = intval($legendary);
        $setId = intval($setId);

        // insert record into the database
        $query = "UPDATE `MTG__Card` 
                SET `Name` = ?,
                    `ManaCost` = ?, 
                    `ColorId` = ?, 
                    `TypeId` = ?,
                    `SubType` = ?, 
                    `Description` = ?,
                    `PowerToughness` = ?,
                    `Legendary` = ?, 
                    `SetId` = ?
            WHERE `MTG__Card`.`CardId` = ?";

        // execute query
        //$result = mysqli_query($db, $query) or die('Error creating record.');

        // create the prepared statement
        $stmt = mysqli_prepare($db, $query) or die('Error in query');

        // bind variables to statement
        mysqli_stmt_bind_param($stmt, 'ssiisssiii', $name, $manaCost, $colorId, $typeId, $subType,
            $description, $powerToughness, $legendary, $setId, $id);

        // execute query
        $result = mysqli_stmt_execute($stmt) or die('Error executing query');

        // redirect back to the card page
        header('Location: card.php?CardId=' . $id);
} // end if validation
} // end if $_POST['submit']
?>

<form method="post">
    <p>
        <label for="name">Name: <span class="text-danger"><?= $nameError ?></span></label><br>
        <input type="text" id="name" name="name" value="<?= $card['Name'] ?>">
    </p>
    <p>
        <label for="manaCost">Mana Cost: <span class="text-danger"><?= $manaError ?></span><br>
            <small><em>White = W, Blue = U, Black = B,<br>
                    Red = R, Green = G, Colorless = C<br>
                    1 Generic and 1 Blue = 1U</em></small></label><br></label>
        <input type="text" id="manaCost" name="manaCost" value="<?= $card['ManaCost'] ?>">
    </p>
    <p>

        <label for="colorId">Color: <span class="text-danger"><?= $colorError ?></span></label><br>
       <select name="colorId" id="colorId">
            <?php
            $query = "SELECT ColorId, Color, Icon
                        FROM MTG__Color";
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $selected = $row['ColorId'] === $card['ColorId'] ? 'selected' : '';
                ?>
                <option value="<?= $row['ColorId'] ?>" <?= $selected ?>><?= $row['Color'] ?>
                </option>
                <?php
            }
            ?>
        </select>
    </p>
    <p>
        <span class="text-danger"><?= $legendError ?></span>
        <label for="legendary">Legendary: <span class="text-danger"><?= $legendError ?></span></label>
        <input type="radio" id="legendary" name="legendary" value="1" <?= $card['Legendary'] == 1 ? 'checked' : ''?>> <br>
        <label for="legendary">Not Legendary: </label>
        <input type="radio" id="legendary" name="legendary" value="0" <?= $card['Legendary'] == 0 ? 'checked' : ''?>>
    </p>
    <p>
        <label for="typeId">Type: <span class="text-danger"><?= $typeError ?></span></label><br>
        <select name="typeId" id="typeId">
            <?php
            $query = "SELECT TypeId, Type
                        FROM MTG__Type";
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $selected = $row['TypeId'] === $card['TypeId'] ? 'selected' : '';
                ?>
                <option value="<?= $row['TypeId'] ?>" <?= $selected ?>><?= $row['Type'] ?>
                </option>
                <?php
            }
            ?>
        </select>
    </p>
    <p>
        <label for="subType">Sub-Typing: <span class="text-danger"><?= $subError ?></span><br>
            <em><small>Wizard, Bird, Dragon, etc...</small></em>
        </label><br>
        <input type="text" id="subType" name="subType" value="<?= $card['SubType'] ?>">
    </p>
    <p>
        <label for="description">Description: <br>
            <em><small>Abilities, keywords, mechanics.<br>
                    Do not enter flavor text.</small></em><br>
            <span class="text-danger"><?= $descriptionError ?></span></label><br>
        <textarea id="description" name="description"><?= $card['Description'] ?></textarea>
    </p>
    <p>
        <label for="powerToughness">Power/Toughness or Loyalty: <span class="text-danger"><?= $ptError ?></span><br>
            <em><small>Format P/T as "2/1" or Loyalty as "4"</small></em></label><br>
        <input type="text" id="powerToughness" name="powerToughness" value="<?= $card['PowerToughness'] ?>">
    </p>
    <p>
        <label for="setId">Set: <span class="text-danger"><?= $setError ?></span></label><br>
        <select name="setId" id="setId">
            <?php
            $query = "SELECT SetId, SetName
                        FROM MTG__Set";
            $result = mysqli_query($db, $query);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $selected = $row['SetId'] === $card['SetId'] ? 'selected' : '';
                ?>
                <option value="<?= $row['SetId'] ?>" <?= $selected ?>><?= $row['SetName'] ?>
                </option>
                <?php
            }
            ?>
        </select>
    </p>
    <input type="hidden" name="cardId" value="<?= $card['CardId'] ?>">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="submit" name="submit" value="Update Card">
</form>

<?php else: ?>
<div class="container">
    <h1>Admin Area</h1>
    <div class="alert alert-danger">Access denied. Please <a href="login.php">login</a>.</div>
</div>

<?php
endif;

require "includes/footer.php";
// close database connection (put in footer to avoid doing multiple times)
mysqli_close($db);
?>
</body>
</html>
