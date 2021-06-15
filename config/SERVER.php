<?php

//Constantes de parámetros de conexión a la base de datos

    const SERVER="localhost";
    const DB="prestamos";
    const USER="root";
    const PASS="";

    const SGBD="mysql:host=".SERVER.";dbname=".DB;


    //constante para procesar por hash o encriptación
    const METHOD="AES-256-CBC";
    const SECRET_KEY='€PRESTAMOS@2021';
    const SECRET_IV='067452';