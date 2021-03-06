<?php header('Access-Control-Allow-Origin: *'); ?>


<?php

    include_once('initialization.php');
    include_once('functions.inc.php');

    $errors = array();
    $errors['type'] = "errors";

    $user_id = $_POST['user_id'];
    //$tutoyer = $_POST['tutoyer'];
    $phoneNumber = ereg_replace("[^0-9]","", trim(strip_tags($_POST['phone'])));
    $phone_search_allow = $_POST['phone-auto'];

    if($phoneNumber == '') {
        $errors['phone'] = 'Veuillez entrer un numéro de téléphone valide';
    }

    if($phone_search_allow == ''){
        $errors['phone-auto'] = 'Veuillez choisir une des possibilités';
    }

    if (count($errors) < 2) {
        
        $sql = 'UPDATE users SET mobile = :mobile, allow_phoneresearch = :allow_phoneresearch WHERE id = :id';
        $query = $connexion->prepare($sql);
        $query->bindValue(':mobile', $phoneNumber);
        $query->bindValue(':allow_phoneresearch', $phone_search_allow);
        //$query->bindValue(':tutoyer', $tutoyer);
        $query->bindValue(':id', $user_id);
        $query->execute();
        
        $sql = 'SELECT * FROM users WHERE id = :id';
        $query = $connexion->prepare($sql);
        $query->bindValue(':id', $user_id);
        $query->execute();
        $user = $query->fetch();
        $user['type'] = "profile-complete";
        echo json_encode($user);
    
    } else {
        echo json_encode($errors);
    }

?>