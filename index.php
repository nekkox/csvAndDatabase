<?php
include "env.php";
include "functions.php";
try {
    $pdo = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
};
echo '<br>';

//tableName='dept'; fileName=dept.csv';'


try {
    insert_into_table_from_csvFile('emp', 'emp.csv');
} catch (PDOException $e) {
    print "Exception:\n";
    die($e->getMessage() . "\n");
}