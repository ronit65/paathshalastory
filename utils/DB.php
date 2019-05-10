<?php
class DB {
    private $conn;
    public function __construct(){
        $settings = json_decode(file_get_contents("../dbconfig.json"));
        $conn = new PDO('mysql:host='.$settings->host.';dbname='.$settings->dbname.';charset=utf8', $settings->username, $settings->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn = $conn;
    }

    public function selectQuery($query, $params = array()){
        try{
            $statement = $this->conn->prepare($query);
            $statement->execute($params);
            $data = $statement->fetchAll();
            return $data;
        } catch(Exception $e){
            throw new Exception("DB error");
        }
    }
}
?>