<?php
        $host = "localhost";
        $port = "5432"; 
        $dbname = "GCaaS"; 
        $user = "postgres"; 
        $password = "1234";

        $connection = pg_connect("host=localhost port=5432 dbname=GCaaS user=postgres password=1234");

        $_SESSION['connection'] = $connection ;
        $_SESSION['host'] = $host;
        $_SESSION['port'] = $port ;
        $_SESSION['dbname'] = $dbname;
        $_SESSION['user'] = $user ;
        $_SESSION['password'] = $password;
?>