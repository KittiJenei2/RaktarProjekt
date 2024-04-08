<?php
session_start();

require_once 'raktarTable.php';
require_once 'Html.php';
echo "<link rel='stylesheet' type='text/css' href='raktar.css'>";
$dataBase = new Raktar();

echo '<header><h1>Makeup storage</h1><br>
        <form method = "post" action = "">
        <button type = "submit" id = "Search" name = "Search">Search</button>
        <button type = "submit" id = "newItem" name = "newItem">Add new item</button>
        <button type="submit" name="listProducts">List Products</button>
        <button type="submit" name="hideProducts">Hide Products</button>
        <button onclick="showLowStockProducts()">Almost out of stock!</button>
<script>
function showLowStockProducts() {
    event.preventDefault();
    
    var modal = document.getElementById("lowStockModal");
    modal.style.display = "block";
}
</script>
</header>';

/*echo '<form method = "post" action="">
        <button type = "submit" id = "createTab" name = "createTab">Tables</button>
        </form>';*/

/*echo '<form method="post" action="">
        <button type="submit" name="loadstorage">Storage tábla</button>
        <button type = "submit" name="loadShelves">Shelf tábla</button>
        <button type = "submit" name="loadRows">Rows tábla</button>
        <button type = "submit" name="loadProducts">Products tábla</button>
        </form>';*/

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

if(isset($_POST['Search'])) {
echo '<h1>Search for a product</h1></br>';
echo '<form method = "post" action = "">
        <label for="itemName">The name of the product you want to find: </label>
        <input type = "text" id = "itemName" name = "itemName" required>
        <button type = "submit" name = "findLocation">Search</button>
        </form>';
}

if(isset($_POST['newItem'])) 
{
    Html::newItem();
}

if(isset($_POST['addNewItem'])) {
    $name = $_POST['newProductName'];
    $storeId = $_POST['newProductStoreId'];
    $shelfId = $_POST['newProductShelfId'];
    $rowId = $_POST['newProductRowId'];
    $price = $_POST['newProductPrice'];
    $quantity = $_POST['newProductQuantity'];
    $minQty = $_POST['newProductMinQty'];

    $result = $dataBase->addNewItem($name, $storeId, $shelfId, $rowId, $price, $quantity, $minQty);
    echo "<p>$result</p>";
}

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
      if(isset($_POST['listProducts'])) {
        echo '<h2>Items in our storage: </h2>';
        $inventory = $dataBase->getProducts();
        echo '<table id="productTable">';
        echo '<tr><th>Name</th><th>Store Name</th><th>Row Name</th><th>Shelf Name
    </th><th>Price</th><th>Quantity</th><th>Action</th><th> </th></tr>';
        foreach($inventory as $item) {
            echo '<tr>';
            echo '<td>' . $item['name'] . '</td>';
            echo '<td>' . $item['store_name'] . '</td>';
            echo '<td>' . $item['row_name'] . '</td>';
            echo '<td>' . $item['shelf_name'] . '</td>';
            echo '<td>'. $item['price'] . ' Ft</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            echo '<td>
                  <form method="post" action="">
                      <input type="hidden" name="itemName" value="' . $item['name'] . '">
                      <input type="hidden" name="itemId" value="' . $item['id'] . '">
                      <button type="submit" name="editItem">Edit</button>
                  </form>
              </td>';
            echo '<td>
                  <form method="post" action="">
                    <input type="hidden" name="itemId" value="' . $item['id'] . '">
                    <button type="submit" name="deleteItem">Delete</button>
                  </form>
                  </td>';
            echo '</tr>';
    }
      echo '</table>';
} elseif (isset($_POST['hideProducts'])) {
        echo '';
}

if(isset($_POST['editItem'])) {
    $itemName = $_POST['itemName'];

    $itemData = $dataBase->getItemByName($itemName);

    echo '<form method="post" action=""><input type="hidden" name="itemName" value="' . $itemName . '">';
    echo '<input type ="hidden" name = "productId" value = "' .$itemData['id'] . '">';
    echo '<label for="editProductName">Name: </label>';
    echo '<input type="text" id="editProductName" name="editProductName" value="' . $itemData["name"] . '" required><br>';
    echo '<label for="editProductStoreName">Store Name: </label>';
    echo '<input type="text" id="editProductStoreName" name="editProductStoreName" value="' . $itemData['id_store'] . '" required><br>';
    echo '<input type="text" id="editProductShelf" name="editProductShelf" value="' . $itemData['id_shelf'] . '" required><br>';
    echo '<input type="text" id="editProductRow" name="editProductRow" value="' . $itemData['id_row'] . '" required><br>';
    echo '<input type="text" id="editProductPrice" name="editProductPrice" value="' . $itemData['price'] . '" required><br>';
    echo '<input type="text" id="editProductQty" name="editProductQty" value="' . $itemData['quantity'] . '" required><br>';

    echo '<button type="submit" name="submitEdit">Mentés</button>';
    echo '</form>';
}

if(isset($_POST['submitEdit'])) {
    $productId = $_POST['productId'];
    $itemName = $_POST['itemName'];
    $editedName = $_POST['editProductName'];
    $editedStoreName = $_POST['editProductStoreName'];
    $editedShelf = $_POST['editProductShelf'];
    $editedRow = $_POST['editProductRow'];
    $editedPrice = $_POST['editProductPrice'];
    $editedQty = $_POST['editProductQty'];

    $result = $dataBase->editItem($productId, $editedName, $editedStoreName, $editedShelf, $editedRow, $editedPrice, $editedQty);
    echo "<p>Item edited succesfully.</p>";
}

if(isset($_POST['deleteItem']))
{
    $itemId = $_POST['itemId'];
    $result = $dataBase->deleteItem($itemId);
    echo "<p>Item deleted succesfully..</p>";
}

$sale = 20;
$lowStockProducts = $dataBase->lowStockItems($sale);

echo '<div id="lowStockModal" class="modal" style="display: none;">';
echo '  <div class="modal-content">';
echo '    <span class="close" onclick="document.getElementById(\'lowStockModal\').style.display = \'none\'">&times;</span>';
if(!empty($lowStockProducts))
{
    echo '<h2>ATTENTION! Almost out of stock items!</h2>';
    foreach($lowStockProducts as $item)
    {
        echo '<p>' . $item['name'] . '(' . $item['quantity'] . ' left)</p>';
    }
} else {
    echo 'There are no items that are almost out of stock!';
}
echo '  </div>';
echo '</div>';
