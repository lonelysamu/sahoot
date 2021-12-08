<?php

$__server = "localhost";
$__user = "u772156354_mcmc";
$__pass = "wEaReThElEaRnInG123";
$__db = "u772156354_mcmc";

$dbc = mysqli_connect($__server,$__user,$__pass,$__db)
OR die( mysqli_connect_error() );
mysqli_set_charset($dbc,'utf-8');
