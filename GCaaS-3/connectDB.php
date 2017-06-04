<?php

        // session_start();
        $_SESSION['host'] = "localhost";
        $_SESSION['port'] = "5432";
        $_SESSION['dbname'] = "GCaaS";
        $_SESSION['user'] = "postgres";
        $_SESSION['password'] = "1234";

        $connection = pg_connect("host=localhost port=5432 dbname=GCaaS user=postgres password=1234");
        $_SESSION['connection'] = $connection ;


?>
