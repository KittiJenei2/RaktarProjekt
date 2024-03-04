<?php
session_start();

require_once 'raktarTable.php';

$dataBase = new Raktar();

echo '<h1>Raktár</h1>';
echo '<form method = "post" action="">
        <button type = "submit" id = "createTab" name = "createTab">Tables</button>
        </form>';

if(isset($_POST['createTab']))
{
    $dataBase->createTable();
}
