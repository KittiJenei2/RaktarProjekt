<?php
session_start();

require_once 'raktarTable.php';

$dataBase = new Raktar();

echo '<h1>Raktár</h1>';
echo '<form method = "post" action="">
        <button type = "submit" id = "createTab" name = "createTab">Tables</button>
        </form>';

echo '<form method="post" action="">
        <button type="submit" name="loadstorage">Storage tábla</button>
        <button type = "submit" name="loadShelves">Shelf tábla</button>
        <button type = "submit" name="loadRows">Rows tábla</button>
        </form>';

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
