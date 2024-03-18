<?php
class Raktar
{    
    protected $mysqli;


    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'storage') {
        $this->mysqli = mysqli_connect($host, $user, $password, $db);
        if ($this->mysqli->connect_errno) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    private function createTableStore() 
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS store(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL)');
    }

    private function createTableRow()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS tablerow(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(255) NOT NULL)');
    }

    private function createTableShelf()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS shelf(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(255) NOT NULL)');
    }

    private function createTableProducts()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS products(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL,
            id_store INT,
            id_shelf INT,
            id_row INT,
            price INT,
            quantity INT,
            min_qty INT)');
    }

    public function createTable()
    {
        $this->createTableStore();
        $this->createTableRow();
        $this->createTableShelf();
        $this->createTableProducts();
    }

    public function importStorageFromCsv($csvFile) 
    {
        $file = fopen($csvFile,'r');

        if (!$file) {
            die('Hiba! Nincs ilyen fájl!');
        }

        fgetcsv($file);
        while (($data = fgetcsv($file, 100, ";")) !== false) 
        {
            $name = $data[0];
            $address = $data[1];

            $query = "INSERT INTO store (name, address) VALUES ('$name', '$address')";
            $result = $this->mysqli->query($query);
        }

        fclose($file);
    }

    public function importShelfFromCsv($csvFile)
    {
        $file = fopen($csvFile,"r");

        if (!$file) {
            die("HIBA!");
        }

        fgetcsv($file);
        while (($data = fgetcsv($file, 1000,';')) !== false)
        {
            $name = $data[0];

            $query = "INSERT INTO shelf (name) VALUES ('$name')";
            $result = $this->mysqli->query($query);
        }

        fclose($file);
    }

    public function importRowFromCsv($csvFile)
    {
        $file = fopen($csvFile,"r");

        if (!$file)
        {
            die("HIBA!");
        }

        fgetcsv($file);
        while (($data = fgetcsv($file,1000,";")) !== false)
        {
            $name = $data[0];

            $query = "INSERT INTO tablerow (name) VALUES ('$name')";
            $result = $this->mysqli->query($query);
        }

        fclose($file);
    }

    public function importProductsFromCsv($csvFile)
    {
        $file = fopen($csvFile,"r");

        if (!$file)
        {
            die("HIBA!");
        }

        fgetcsv($file);
        while (($data = fgetcsv($file,1000,";")) !== false)
        {
            $name = $data[0];
            $idstore = $data[1];
            $idshelf = $data[2];
            $idrow = $data[3];
            $price = $data[4];
            $quantity = $data[5];
            $min_qty = $data[6];

            $store_query = "SELECT id FROM store WHERE id = '$idstore'";
            $store_result = $this->mysqli->query($store_query);
            $store_row = $store_result->fetch_assoc();
            $id_store = $store_row['id'];
            

            $shelf_query = "SELECT id FROM shelf WHERE name = '$idshelf'";
            $shelf_result = $this->mysqli->query($shelf_query);
            $shelf_row = $shelf_result->fetch_assoc();
            $id_shelf = $shelf_row['id'];
            

            $row_query = "SELECT id FROM tablerow WHERE name = '$idrow'";
            $row_result = $this->mysqli->query($row_query);
            $row_row = $row_result->fetch_assoc();
            $id_row = $row_row['id'];
            


            $query = "INSERT INTO products (name, id_store, id_shelf, id_row, price, quantity, min_qty) VALUES ('$name', '$id_store', '$id_shelf', '$id_row', '$price', '$quantity', '$min_qty')";
            $result = $this->mysqli->query($query);
        }

        fclose($file);
    }

    public function getProducts() 
    {
        $query = "SELECT p.name, s.name AS store_name, r.name AS row_name, sh.name AS shelf_name, p.price, p.quantity FROM products p
                    JOIN store s ON p.id_store = s.id
                    JOIN tablerow r ON p.id_row = r.id
                    JOIN shelf sh ON p.id_shelf = sh.id";

        $result = $this->mysqli->query($query);
        $inventory = [];
        while ($row = $result->fetch_assoc())
        {
            $inventory[] = $row;
        }
        return $inventory;
    }

    public function FindProduct($itemName)
    {
        $query = "SELECT s.name AS store_name, r.name AS row_name, sh.name AS shelf_name
                  FROM products p
                  JOIN store s ON p.id_store = s.id
                  JOIN tablerow r ON p.id_row = r.id
                  JOIN shelf sh ON p.id_shelf = sh.id
                  WHERE p.name = '$itemName'";

        $result = $this->mysqli->query($query);
        if ($result->num_rows > 0)
        {
            return $result->fetch_assoc();
        } else {
            return "We don't have such item in our storage";
        }
    }

    public function lowStockItems($sale)
    {
        $query = "SELECT p.name, p.quantity
                    FROM products p
                    WHERE p.quantity < $sale";

        $result = $this->mysqli->query($query);
        $lowStockItems = [];
        while ($row = $result->fetch_assoc())
        {
            $lowStockItems[] = $row;
        }
        return $lowStockItems;
    }

    public function __destruct()
    {
        $this->mysqli->close();
    }

}

