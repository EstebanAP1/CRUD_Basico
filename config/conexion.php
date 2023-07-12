<?php
class dataBase
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "equipos";

    function conn()
    {
        return new mysqli($this->servername, $this->username, $this->password, $this->database);
    }
}

?>