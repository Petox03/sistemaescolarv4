<?php

//? PRofesores
if($access == 1)
{
//* NAVBAR
echo <<<_head
<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">

        <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="true">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div class="navbar-menu" id="navMenu">
        <div class="navbar-start">
            <span class="navbar-item is-unselectable">
                Bienvenido profesor: <h6 class="user ml-2">$name $lastname</h6>
            </span>

            <a href="index.php" class="navbar-item linknav">
                Inicio
            </a>

            <a href="modi.php" class="navbar-item linknav">
                Actualizar
            </a>

            <a href="delete.php" class="navbar-item linknav">
                Borrar
            </a>

            <a href="logout.php" class="navbar-item linknav">
                Cerrar sesión
            </a>

        </div>
    </div>
</nav>
_head;
}
//? Alumnos
else{
//* NAVBAR
echo <<<_head
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">

            <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="true">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div class="navbar-menu" id="navMenu">
            <div class="navbar-start">
                <span class="navbar-item is-unselectable">
                    Bienvenido: <h6 class="user ml-2">$name $lastname</h6>
                </span>

                <a href="index.php" class="navbar-item linknav">
                    Inicio
                </a>

                <a href="logout.php" class="navbar-item linknav">
                    Cerrar sesión
                </a>

            </div>
        </div>
    </nav>
_head;
}
?>
