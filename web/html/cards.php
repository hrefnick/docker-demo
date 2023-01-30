<?php
$pageTitle = "All MTG Cards";
require "includes/header.php";
if(isset($_SESSION['authUser']) and $_SESSION['authUser']):
?>

<?php
$sort = $_GET['sort'] ?? 'Name';
$dir = $_GET['dir'] ?? 'ASC';

// build and test query in PhpMyAdmin first
$query = "SELECT MTG__Card.Name AS Name, MTG__Card.ManaCost AS ManaCost, MTG__Card.Description AS Description, 
            MTG__Card.PowerToughness AS PowerToughness, MTG__Card.SubType AS SubType,
            MTG__Color.Color AS Color, MTG__Type.Type AS Type, MTG__Set.SetName AS SetName, MTG__Card.CardId AS CardId,
            MTG__Color.ColorId AS ColorId, MTG__Type.TypeId AS TypeId, MTG__Set.SetId AS SetId, CASE WHEN MTG__Card.Legendary = 1 THEN 'Legendary'
            ELSE ''
            END AS Legendary
            FROM `MTG__Type`
            JOIN MTG__Card USING (TypeId)
            JOIN MTG__Color USING (ColorId)
            JOIN MTG__Set USING (SetId)";
    if($_SESSION['authUser']['role'] !== 'Admin'){
        $query .= " WHERE UserId LIKE '{$_SESSION['authUser']['userId']}' 
        ORDER BY $sort $dir ";
    }else if($_SESSION['authUser']['role'] === 'Admin'){
        $query .= " ORDER BY $sort $dir";
    }


// execute query
$result = mysqli_query($db, $query) or die(mysqli_error($db));
?>

<h2>Collection</h2>
    <p>
        Try searching for a specific card, or <a href="add-card.php">add a card</a> to your collection!<br>
<form action="cards.php" method="GET">
    <input id="search" name="search" type="text" placeholder="Search by card name, type, cmc, set, or color...">
    <input id="submit" type="submit" value="Search">
</form>
</p>

<table class="table table-striped table-hover">
    <thead class="table-dark">
    <tr>
        <?php
        $nameDir = ($sort === 'Name' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $nameArrow = '';
        if($sort === 'Name'){
            $nameArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $colorDir = ($sort === 'Color' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $colorArrow = '';
        if($sort === 'Color'){
            $colorArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $manaDir = ($sort === 'ManaCost' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $manaArrow = '';
        if($sort === 'ManaCost'){
            $manaArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $legendDir = ($sort === 'Legendary' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $legendArrow = '';
        if($sort === 'Legendary'){
            $legendArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $typeDir = ($sort === 'Type' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $typeArrow = '';
        if($sort === 'Type'){
            $typeArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $subTypeDir = ($sort === 'SubType' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $subTypeArrow = '';
        if($sort === 'SubType'){
            $subTypeArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $ptDir = ($sort === 'PowerToughness' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $ptArrow = '';
        if($sort === 'PowerToughness'){
            $ptArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        $setDir = ($sort === 'SetName' && $dir === 'ASC') ? 'DESC' : 'ASC';
        $setArrow = '';
        if($sort === 'SetName'){
            $setArrow = $dir === 'ASC' ? '&darr;' : '&uarr;';
        }

        ?>
        <th><a href="?sort=Name&dir=<?= $nameDir ?>">Name</a> <?= $nameArrow ?></th>
        <th><a href="?sort=Color&dir=<?= $colorDir ?>">Color</a> <?= $colorArrow ?></th>
        <th><a href="?sort=ManaCost&dir=<?= $manaDir ?>">Mana Cost</a> <?= $manaArrow ?></th>
        <th><a href="?sort=Legendary&dir=<?= $legendDir ?>">Legendary</a> <?= $legendArrow ?></th>
        <th><a href="?sort=Type&dir=<?= $typeDir ?>">Type</a> <?= $typeArrow ?></th>
        <th><a href="?sort=SubType&dir=<?= $subTypeDir ?>">Sub-Typing</a> <?= $subTypeArrow ?></th>
        <th>Description</th>
        <th><a href="?sort=PowerToughness&dir=<?= $ptDir ?>">Power/Toughness | Loyalty</a> <?= $ptArrow ?></th>
        <th><a href="?sort=SetName&dir=<?= $setDir ?>">Set Name</a> <?= $setArrow ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        // while is the best loop to output the array when we don't know the result numbers
        ?>
        <tr>
            <td><a href="card.php?CardId=<?= $row['CardId'] ?>"><?=$row['Name'] ?></td>
            <td><?=$row['Color'] ?></td>
            <td><div class="nowrap"><span class="color-size"><?= swapLetters($row['ManaCost']) ?></span></div></td>
            <td><?=$row['Legendary'] ?></td>
            <td><?=$row['Type'] ?></td>
            <td><?=$row['SubType'] ?></td>
            <td><?=$row['Description'] ?></td>
            <td><?=$row['PowerToughness'] ?></td>
            <td><?=$row['SetName'] ?></td>
            <td>
                <a href="edit-card.php?id=<?= $row['CardId'] ?>" class="btn btn-sm btn-secondary">edit</a>
                <a href="delete-card.php?id=<?= $row['CardId'] ?>" class='btn btn-sm btn-danger'>delete</a>
            </td>

        </tr>
        <?php
    } // end while
    ?>
    </tbody>
</table>
<?php
    if(isset($_GET['search'])){
        $search = $_GET['search'];
        $sql = "SELECT MTG__Card.Name AS Name, MTG__Card.ManaCost AS ManaCost, MTG__Card.Description AS Description, 
            MTG__Card.PowerToughness AS PowerToughness, MTG__Card.SubType AS SubType,
            MTG__Color.Color AS Color, MTG__Type.Type AS Type, MTG__Set.SetName AS SetName, MTG__Card.CardId AS CardId,
            CASE WHEN MTG__Card.Legendary = 1 THEN 'Legendary'
            ELSE ''
            END AS Legendary
       
                FROM `MTG__Type`
                JOIN MTG__Card USING (TypeId)
                JOIN MTG__Color USING (ColorId)
                JOIN MTG__Set USING (SetId)
                WHERE (MTG__Card.Name LIKE '%$search%' OR MTG__Type.Type LIKE '%$search%' OR 
                       MTG__Set.SetName LIKE '%$search%' OR MTG__Card.SubType LIKE '%$search%' OR 
                       MTG__Card.ManaCost LIKE '%$search%' OR MTG__Color.Color LIKE '%$search%' OR
                       PowerToughness LIKE '%$search%' OR MTG__Card.Description LIKE '%$search%')";

            if($_SESSION['authUser']['role'] !== 'Admin'){
                  $sql .= " AND UserId = '{$_SESSION['authUser']['userId']}'";
        }

        $result = mysqli_query($db, $sql);


        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    // while is the best loop to output the array when we don't know the result numbers
    ?>
    <tr>
        <td><a href="card.php?CardId=<? $row['CardId'] ?>"><?=$row['Name'] ?></a></td>
        <td><div class="nowrap"><span class="color-size"><?= swapLetters($row['ManaCost']) ?></span></div></td>
        <td><?=$row['Color'] ?></td>
        <td><?=$row['Legendary'] ?></td>
        <td><?=$row['Type'] ?></td>
        <td><?=$row['SubType'] ?></td>
        <td><?=$row['Description'] ?></td>
        <td><?=$row['PowerToughness'] ?></td>
        <td><?=$row['SetName'] ?></td>
        <td>
        <?php //if($_SESSION['authUser']['role'] == 'Admin'): ?>
            <a href="edit-card.php?id=<?= $row['CardId'] ?>" class="btn btn-sm btn-secondary">edit</a>
            <a href="delete-card.php?id=<?= $row['CardId'] ?>" class='btn btn-sm btn-danger'>delete</a>
        <?php //endif; ?>
        </td>
    </tr>
    <?php
} // end while
    echo "</tbody>
</table>";
    }
        ?>





<?php else: ?>
<div class="container">
    <h1>User Area</h1>
    <div class="alert alert-danger">Access denied. Please <a href="login.php">login</a>.</div>
</div>

<?php
endif;

require "includes/footer.php";
?>