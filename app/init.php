<?php

require_once 'core/App.php';
require_once 'core/Controller.php';
session_start();

if(!isset($_SESSION['loggedIn'])) //daca nu e setata deja, ca sa nu se schimbe cand dau refresh la pagina 
 $_SESSION['loggedIn']="false"; //initializam cu false sesiunea pt loggedIn

if(!isset($_SESSION['userID']))
 $_SESSION['userID']=-1;

 if(!isset($_SESSION['privileges']))
 $_SESSION['privileges']="none";

?>