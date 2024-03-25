<?php
session_start();

require_once 'raktarTable.php';
echo "<link rel='stylesheet' type='text/css' href='raktar.css'>";

$dataBase = new Raktar();

echo '<header>Makeup storage</header>';
/*echo '<form method = "post" action="">
        <button type = "submit" id = "createTab" name = "createTab">Tables</button>
        </form>';

echo '<form method="post" action="">
        <button type="submit" name="loadstorage">Storage tábla</button>
        <button type = "submit" name="loadShelves">Shelf tábla</button>
        <button type = "submit" name="loadRows">Rows tábla</button>
        <button type = "submit" name="loadProducts">Products tábla</button>
        </form>';
*/


if(isset($_POST['createTab']))
{
    $dataBase->createTable();
}

if(isset($_POST['loadstorage'])) 
{
    $csvFile = 'storage.csv';
    $dataBase->importStorageFromCsv($csvFile);
}

if(isset($_POST['loadShelves']))
{
    $csvFile = 'shelf.csv';
    $dataBase->importShelfFromCsv($csvFile);
}

if(isset($_POST['loadRows']))
{
    $csvFile = 'row.csv';
    $dataBase->importRowFromCsv($csvFile);
}

if(isset($_POST['loadProducts']))
{
    $csvFile = 'adatok.csv';
    $dataBase->importProductsFromCsv($csvFile);
}

echo '<h1>Search for a product</h1></br>';
echo '<form method = "post" action = "">
        <label for="itemName">The name of the product you want to find: </label>
        <input type = "text" id = "itemName" name = "itemName" required>
        <button type = "submit" name = "findLocation">Search</button>
        </form>';

if(isset($_POST['findLocation']))
{
    $itemName = $_POST['itemName'];
    $result = $dataBase->FindProduct($itemName);

    echo '<h2>The location of the product: </h2>';
    if (is_array($result))
    {
        echo '<p>The ' . $itemName . ' can be found in:</p>';
        echo '<p>Storage: ' . $result['store_name'] . '</p>';
        echo '<p>Row: ' . $result['row_name'] . '</p>';
        echo '<p>Shelf: ' . $result['shelf_name'] . '</p>';
    } else {
        echo '<p>' . $result . '</p>';
    }
}

echo '<form method="post" action="">
        <button type="submit" name="listItems">Show products</button>
        <button type="submit" name="hideProducts">Hide Products</button
        </form>';

if (isset($_POST['listItems'])) {
    echo '<h2>Items in our storage: </h2>';
    $inventory = $dataBase->getProducts();
    echo '<table>';
    echo '<tr><th>Name</th><th>Store Name</th><th>Row Name</th><th>Shelf Name</th><th>Price</th><th>Quantity</th></tr>';
    foreach($inventory as $item)
    {
        echo '<tr>';
        echo '<td>' . $item['name'] . '</td>';
        echo '<td>' . $item['store_name'] . '</td>';
        echo '<td>' . $item['row_name'] . '</td>';
        echo '<td>' . $item['shelf_name'] . '</td>';
        echo '<td>'. $item['price'] . ' Ft</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} elseif(isset($_POST['hideProducts'])) {
    
}

$sale = 20;
$lowStockProducts = $dataBase->lowStockItems($sale);

echo '<h2>ATTENTION! Almost out of stock items!</h2>';
echo '<button onclick="showLowStockProducts()">Almost out of stock!</button>';
echo '<script>
function showLowStockProducts() {
    var modal = document.getElementById("lowStockModal");
    modal.style.display = "block";
}
</script>';

echo '<div id="lowStockModal" class="modal" style="display: none;">';
echo '  <div class="modal-content">';
echo '    <span class="close" onclick="document.getElementById(\'lowStockModal\').style.display = \'none\'">&times;</span>';
if(!empty($lowStockProducts))
{
    echo '<h2>The following items are almost out of stock: </h2>';
    foreach($lowStockProducts as $item)
    {
        echo '<p>' . $item['name'] . '(' . $item['quantity'] . ' left)</p>';
    }
} else {
    echo 'There are no items that are almost out of stock!';
}
echo '  </div>';
echo '</div>';
