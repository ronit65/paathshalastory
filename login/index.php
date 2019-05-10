<?php
require_once("DbrLogin.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    #echo json_encode(($db->selectQuery("SELECT * from login")));
    $postbody = file_get_contents("php://input");
    $postbody = json_decode($postbody,true);
    $aDbrLogin = new DbrLogin();
    $responseCode = $aDbrLogin->authenticate($postbody['username'],$postbody['password']);
    http_response_code($responseCode);
    if($responseCode == 401){
        $err = array('error' => array('Incorrect username or password.'));
        echo json_encode($err);
    } else if($responseCode == 200) {
        $msg = array('data' => array('role' => $_SESSION['role'], 'last_login' => $_SESSION['last_login']));
        echo json_encode($msg);
    }else if($responseCode == 500){
        $err = array('error' => array('Internal Error. Please contact helpdesk.'));
        echo json_encode($err);
    }
}else {
    http_response_code(405);
}
?>