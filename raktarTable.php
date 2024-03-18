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

    public function importDataFromCsv($csvFile) 
    {
        $file = fopen($csvFile,"w");

        if (!$file)
        {
            die("HIBA!");
        }

        fgetcsv($file);
        while (($data = fgetcsv($file,100,";")) !== false)
        {
            $name = $data[0];
            $id_store = data[1];

        }

        fclose($file);
    }


    public function __destruct()
    {
        $this->mysqli->close();
    }
}