<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

$error = $user = $pass = $Rpass = $name = $ape = "";

echo'

<body>
    <!-- project start -->
';
if(!$loggedin)
{
    //? Módulo de Singup
    echo<<<_singup
        <div class="columns is-centered is-2 mt-4">
            <div class="column is-half login animate__animated animate__fadeIn animate__slow">
                <div class="notification is-link">
                    <h1>Ingrese sus datos para registrarse</h1>
                </div>
                <form action="api/index.php/login" method="POST">
                    <h4 id="mensaje" style="display: block"></h4>
                    <div class="field">
                        <label class="label" for="user">Usuario</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="user" id="user" type="text" placeholder="Usuario" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="name">Nombre(s)</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="name" id="name" type="text" placeholder="Nombre" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="ape">Apellido(s)</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="ape" id="ape" type="text" placeholder="Apellido" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="pass">Contraseña</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="pass" id="pass" type="password" placeholder="Contraseña" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="Rpass">Repetir Contraseña</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="Rpass" id="Rpass" type="password" placeholder="Contraseña" requiered>
                        </div>
                    </div>
                    <button type="button" class="button is-link" onclick="singup()">Registrarse</button>
                    <a type="button" href="login.php" class="button is-link ml-4">Iniciar sesión</a>
                </form>
            </div>
        </div>
    _singup;
echo'
    <script>
        function singup()
        {
            axios.post(`api/index.php/singup`, {
                user: document.forms[0].user.value,
                name: document.forms[0].name.value,
                lastname: document.forms[0].ape.value,
                pass: document.forms[0].pass.value,
                Rpass: document.forms[0].Rpass.value
            })
            .then(resp => {
                var mensaje = document.getElementById("mensaje");
                var msg = document.createElement("p");
                var a = 1;

                if(resp.data.datos)
                {
                    if(resp.data.passes)
                    {
                        if(resp.data.userExist)
                        {
                            msg.innerHTML = "Usuario no disponible, por favor, elije otro";

                            document.forms[0].user.value="";

                            mensaje.appendChild(msg);
                        }
                        else
                        {
                            var mensaje2 = document.getElementById("mensaje");

                            var msg2 = document.createElement("p");
                            msg2.innerHTML = "Registro completado con éxito, por favor inicie sesión";

                            mensaje2.className = "animate__animated animate__rubberBand check is-size-3";

                            mensaje2.appendChild(msg2);

                            setTimeout(`location.href="login.php"`, 2300);

                            a = 2;
                        }
                    }
                    else
                    {
                        msg.innerHTML = "Las contraseñas no son iguales";


                        document.forms[0].pass.value = "";
                        document.forms[0].Rpass.value = "";

                        mensaje.appendChild(msg);
                    }
                }
                else
                {
                    msg.innerHTML = "Faltan datos";

                    mensaje.appendChild(msg);
                }

                if(a != 2)
                {
                    mensaje.className = "animate__animated animate__shakeX error is-size-3";
                    setTimeout(function(){mensaje.className = "animate__animated animate__fadeOut error is-size-3";}, 1500);
                    setTimeout(function(){mensaje.removeChild(msg);}, 2250);
                }
            })
            .catch(error => {
                console.log(error);
            });
        }
    </script>
</body>
';
}
//Si ya se está loggeado, redirije a la página principal
else
{
    //! Metadata para enviar al index
    echo'
    <meta http-equiv="Refresh" content="0;url=index.php">
    </div></body></html>
    ';
}
?>