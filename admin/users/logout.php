<?php
    session_start();
    session_destroy();
    header("location: /bloggingsys/login.php");