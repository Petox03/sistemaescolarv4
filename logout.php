<?php
    require_once 'data.php';

    echo'

    <meta http-equiv="Refresh" content="0;url=login.php">

    <title>logout</title>

    </head>

    <body>';
    //*Llama a la función que destruye la sesión
    if (isset($_SESSION['user']))
    {
        destroySession();
    }

    echo'

    </body>

    </html>
    ';

?>
