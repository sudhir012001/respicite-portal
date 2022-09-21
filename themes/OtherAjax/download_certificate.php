<?php 
    $email = base64_decode($_GET['email']);
    $solution = $_GET['group'];
    if($solution=='PPE')
    {
        header('location:ppe_certificate.php?email='.$_GET['email']);
    }
?>