<?php

const DSN = 'mysql:host=localhost;dbname=classicmodels;charset=UTF8';
const DB_USER = 'root';
const DB_PASS = 'troiswa';

$results = array();

try{
    // if(array_key_exists('q',$_GET))
    // {
    //1 : connexion
        $dbh = new PDO(DSN,DB_USER,DB_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //2 : préparer ma requête
        $sth = $dbh->prepare('SELECT * FROM orders /*WHERE customerName LIKE ?*/');

    //3 : executer ma requête
    $sth->execute(/*array('%'.$_GET['q'].'%')*/);

    //4 : recupérer les résultats
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);
    // // var_dump($results);
    // }
}
catch(PDOException $e)
{
    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}

include('commandes.phtml');

?>