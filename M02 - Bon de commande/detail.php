<?php

const DSN = 'mysql:host=localhost;dbname=classicmodels;charset=UTF8';
const DB_USER = 'root';
const DB_PASS = 'troiswa';
const TVA= 0.2;
$results = array(); // sert pour la première requête
$produits = array(); // sert pour la deuxième requête
$totaux = array(); // sert pour la troisème requête
try{

    $idCommande = $_GET['id'];
    // if(array_key_exists('q',$_GET))
    // {
    //1 : connexion
    //2 : préparer ma requête
    //3 : executer ma requête
    //4 : recupérer les résultats
    
    //1ere étape (une fois pour toutes les requêtes)

        $dbh = new PDO(DSN,DB_USER,DB_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


         // 1ere requete
            //2°
            $sth = $dbh->prepare('SELECT customerName,contactLastName,contactFirstName,addressLine1,addressLine2,city,state,postalCode,country,o.orderNumber
            FROM orders o 
            INNER JOIN customers c ON o.customerNumber = c.customerNumber WHERE o.orderNumber = :idCommande'); // 1ere requete 
            /*SELECT toutes les infos des clients WHERE orderNumber = id donné dans la page commande.phtml*/
            //3°
            $sth->execute(['idCommande'=>$idCommande]);
            //4°  
            $client = $sth->fetch(PDO::FETCH_ASSOC); 
       
        // 2eme requete
            //2°
        $sth = $dbh->prepare('SELECT p.productCode, p.productName, od.quantityOrdered, od.priceEach,(quantityOrdered *priceEach) AS total 
        FROM orderdetails od
        INNER JOIN products p ON p.productCode = od.productCode WHERE od.orderNumber = :idCommande'); 
            //3°       
        $sth->execute(['idCommande'=>$idCommande]);
            //4°
        $produits = $sth->fetchAll(PDO::FETCH_ASSOC);


        // 3eme requete
            //2°
        $sth = $dbh->prepare('SELECT SUM(quantityOrdered *priceEach) AS total
        FROM orderdetails od
        INNER JOIN products p ON p.productCode = od.productCode
        WHERE od.orderNumber = :idCommande');       // $total == sous-total pour 
            //3°
        $sth->execute(['idCommande'=>$idCommande]);    
            //4°
            $totaux = $sth->fetchAll(PDO::FETCH_ASSOC); 
            $totTVA = $totaux[0]['total']*TVA; 
            $totalTTC = $totaux[0]['total']+$totTVA;

    // }
}
catch(PDOException $e)
{
    $error = 'Une erreur de connexion a eu lieu'.$e->getMessage();
}

include('detail.phtml');

?>