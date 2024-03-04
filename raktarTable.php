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
            name VARCHAR(255) NOT NULL,
            id_store INT)');
    }

    private function createTableShelf()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS shelf(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(255) NOT NULL,
            id_tablerow INT)');
    }

    private function createTableProducts()
    {
        $this->mysqli->query('CREATE TABLE IF NOT EXISTS products(
            id INT PRIMARY KEY auto_increment,
            name VARCHAR(200) NOT NULL,
            id_shelf INT,
            min_qty INT,
            quantity INT,
            price INT)');
    }

    public function createTable()
    {
        $this->createTableStore();
        $this->createTableRow();
        $this->createTableShelf();
        $this->createTableProducts();
    }
}