<?php
class Html {
    static function newItem() {
        echo '<div class = "box">
    <h1>Fill in the boxes with the data of your item below: </h1>
    <form method="post" action="">
    <label for="newProductName">Name: </label>
    <input type="text" id="newProductName" name="newProductName" required><br>
    <label for="newProductStoreId">Store ID: </label>
    <input type="text" id="newProductStoreId" name="newProductStoreId" required><br>
    <label for="newProductShelfId">Shelf ID: </label>
    <input type="text" id="newProductShelfId" name="newProductShelfId" required><br>
    <label for="newProductRowId">Row ID: </label>
    <input type="text" id="newProductRowId" name="newProductRowId" required><br>
    <label for="newProductPrice">Price: </label>
    <input type="text" id="newProductPrice" name="newProductPrice" required><br>
    <label for="newProductQuantity">Quantity: </label>
    <input type="text" id="newProductQuantity" name="newProductQuantity" required><br>
    <label for="newProductMinQty">Min Quantity: </label>
    <input type="text" id="newProductMinQty" name="newProductMinQty" required><br>
    <button type="submit" name="addNewItem">Add New Product</button>
    </form>
    </div>';
    }
}
