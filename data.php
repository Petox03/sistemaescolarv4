<?php
@ob_start();
session_start();
//*Este archivo es el que contiene todo lo principal del proyecto y llama las dependencias

//Inicia variables de sesión


//? llama a todo lo necesario
use Illuminate\Database\Capsule\Manager as DB;
require 'vendor/autoload.php';
require 'config/database.php';

//? estructura principal de HTML y llamada a las metadatas, frameworks y estilos.
echo '
<!DOCTYPE html>
    <html lang="es" class="fondo">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Sistema escolar</title>

        <!--bulma & normalize-->
        <link rel="stylesheet" href="node_modules/bulma/css/bulma.min.css">
        <link rel="stylesheet" href="node_modules/normalize.css/normalize.css">

        <!-- Animate.css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

        <!-- web page styles -->
        <link rel="stylesheet" href="css/styles.css">

        <!-- web page js -->
        <script src="js/validar.js"></script>

        <script src="node_modules/axios/dist/axios.min.js"></script>

        <script>
            function getalumn()
            {
                axios.post(`api/index.php/getalumn`, {
                })
                .then(resp => {
                    if(resp.data.users)
                    {
                        const alumnos = resp.data.alumnos;
                        alumnos.forEach(alumnos => {
                            var miSelect=document.getElementById("alumn");

                            // Creamos un objeto option
                            var miOption=document.createElement("option");

                            // Añadimos las propiedades value y label
                            miOption.setAttribute("value",alumnos.id_user);
                            miOption.setAttribute("label",alumnos.name + " " + alumnos.lastname);

                            // Añadimos el option al select
                            miSelect.appendChild(miOption);
                        });
                    }
                    else
                    {
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        </script>
    </head>
';

//* Validación de sesión existente
if (isset($_SESSION['user'])) {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;

    $users = DB::table('users')->where('user','=',$user)->first();

    //foreach($user as $u){
    //}

    $name = $users->name;
    $lastname = $users->lastname;
    $access = $users->idaccess;
    $id = $users->id_user;
} else $loggedin = FALSE;

//*Tiepo de vida de la sesión con respecto a la inactividad

//Comprobamos si esta definida la sesión 'tiempo'.
if (isset($_SESSION['tiempo'])) {

    //Tiempo en segundos para dar vida a la sesión.
    $innactive = 1800; //30 min.

    //Calculamos tiempo de vida inactivo.
    $lifeTime = time() - $_SESSION['tiempo'];

    //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
    if ($lifeTime > $innactive) {
        //Removemos sesión.
        session_unset();
        //Destruimos sesión.
        session_destroy();
        //Redirigimos pagina.
        header("Location: login.php");

        exit();
    }
} else {
    //Activamos sesion tiempo.
    $_SESSION['tiempo'] = time();
}

//! Función que destrulle la sesión (No tocar)
function destroySession()
{
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
}

//* Función para escape de varibles
function sanitizeString($var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}
?>