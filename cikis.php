<?php

    $GIRIS_FLAG = true;
    require 'inc/init.php';

    $Logout = new Logout();
    $Logout->action();
    header("Location: giris.php");
