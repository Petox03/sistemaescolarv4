<?php
require_once 'data.php';
if(!$loggedin)
{
echo'
<body>
    <div class="">
        <div class="columns is-centered is-2 mt-6">
            <div class="column is-half login animate__animated animate__fadeIn animate__slow">
                <div class="notification is-link">
                    <h1>Ingrese sus datos para iniciar sesión</h1>
                </div>
                <form action="api/index.php/login" method="POST">
                    <span class="is-size-4 mt-3 mb-3"><h4 id="a" class=""></h4></span>
                    <div class="field">
                        <label class="label">User</label>
                        <div class="control">
                            <input class="input" type="text" id="user" name="user" placeholder="User">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">pass</label>
                        <div class="control">
                            <input class="input" type="password" id="pass" name="pass" placeholder="Password">
                        </div>
                    </div>
                    <button type="button" class="button is-link" onclick="login()">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function login()
        {
            axios.post(`api/index.php/login/${document.forms[0].user.value}`, {
                user: document.forms[0].user.value,
                pass: document.forms[0].pass.value
            })
            .then(resp => {
                if(resp.data.aceptado)
                {
                    if(resp.data.idaccess == 1)
                    {
                        document.getElementById("a").innerHTML = `Bienvenido profesor/a:  ${resp.data.name} ${resp.data.lastname}`;
                        document.getElementById("a").className = "animate__animated animate__rubberBand add";
                        setTimeout(`location.href="index.php?usuario=${resp.data.user}"`, 1500);
                    }
                    else
                    {
                        document.getElementById("a").innerHTML = `Bienvenido alumn@:  ${resp.data.name} ${resp.data.lastname}`;
                        document.getElementById("a").className = "animate__animated animate__rubberBand add";
                        setTimeout(`location.href="index.php?usuario=${resp.data.user}"`, 1500);
                    }
                }
                else
                {
                    alert(`El usuario y/o cotraseña son incorrectos\nPor favor, verifique los datos y vuelva a intentarlo por favor`)
                }
            })
            .catch(error => {
                console.log(error);
            });
        }
    </script>

</body>
</html>
';
}
else {
    //! Metadata para redirijir al index
    echo'
    <meta http-equiv="Refresh" content="0;url=index.php">
    </div></body></html>
    ';
}
?>