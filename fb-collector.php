<?php
    header("Status: 301 Moved Permanently");
    header("Location:./index.php?r=user/facebookin&". $_SERVER['QUERY_STRING']);
    exit;
?>