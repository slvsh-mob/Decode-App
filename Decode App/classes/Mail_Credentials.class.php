<?php
class Credentials{
    private $username = 'GAFWebServer@gmail.com';
    private $password = 'GAFNJ001';
    private $port = 587;
    private $host = 'smtp.gmail.com';
    private $smtp = 'tls';

    public function getCredentials(){
        return $this->username;
        return $this->password;
        return $this->port;
        return $this->host;
        return $this->smtp;
    }
}

?>