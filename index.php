<?php

require_once 'data.php';

if(!$loggedin)
{
    if(isset($_GET['usuario']))
    {
        $usuario = $_GET['usuario'];
        $_SESSION['user'] = $usuario;
    }
}

//Se valdia si hay una sesión existente
if($loggedin)
{
    if($access == 1)
    {
        echo'<body onload="getalumn()">';
    }
    else {
        echo'<body onload="getcali()">';
    }
    //! Llama a el header
    require_once 'header.php';

    //? Maestro
    if($access == 1){

        /* //? Consulta a la base de datos, llama alumnos
        $users = DB::table('users')->where('id_user',"<>",1)->orderBy('lastname')->get(); */

        //? módulo de adhesión de calificaciones de maestro
        echo'
        <div class="container animate__animated animate__fadeIn animate__slow mt-3 mb-4">
            <form method="post" action="api/index.php/add/4" name="miformulario">
                <label></label>
                <h4 class="is-size-3">Ingrese las calificaciones de un alumno.</h4>
                <span class="is-size-4"><h4 id="a" class="is-size-4 mt-3 mb-3"></h4></span>
                <div class="field">
                    <label class="label" for="alumn">Alumno</label>
                    <div class="control">
                        <div class="select is-medium">
                            <select id="alumn" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="cali1">Español</label>
                    <div class="control">
                        <input class="input" type="number" id="cali1" name="cali1" min="1" max="10" value="0" placeholder="Español">
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="cali2">Matemáticas</label>
                    <div class="control">
                        <input class="input" type="number" id="cali2" name="cali2" min="1" max="10" value="0" placeholder="Matemáticas">
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="cali3">Historia</label>
                    <div class="control">
                        <input class="input" type="number" id="cali3" name="cali3" min="1" max="10" value="0" placeholder="Historia">
                    </div>
                </div>
                <button class="mt-3 button is-link" type="button" onclick="add()">Agregar</button>
                <br>
            </form>
        ';
    }
    //?alumnos
    else{

        echo'<div class="container animate__animated animate__fadeIn animate__slow mt-3 mb-4">';

            echo'
            <form method="post">
                    <input class="is-invisible" id="id" type="text" value="'.$id.'" readonly>
            </form>
            ';

            echo<<<_cali
                <form method="post">
                    <label></label>
                    <h4 class="is-size-3">Calificaciones de '$name  $lastname'</h4>
                    <h1 class="is-size-3 error" id="error" style="display: none">Aún no hay calificaciones</h1>
                    <br>
                    <div class="field">
                        <label class="label">Español</label>
                        <div class="control">
                            <input class="input" id="cali1" type="text" readonly>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Matemáticas</label>
                        <div class="control">
                            <input class="input" id="cali2" type="text" readonly>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Historia</label>
                        <div class="control">
                            <input class="input" id="cali3" type="text" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="field">
                        <label class="label">Promedio general</label>
                        <div class="control">
                            <input class="input" id="prom" type="text" readonly>
                        </div>
                    </div>
                </form>
            _cali;

        echo'</div>';
    }

    echo'

        <script>
            function add()
            {
                axios.post(`api/index.php/add/${document.forms[0].alumn.value}`, {
                    alumn: document.forms[0].alumn.value,
                    cali1: document.forms[0].cali1.value,
                    cali2: document.forms[0].cali2.value,
                    cali3: document.forms[0].cali3.value
                })
                .then(resp => {
                    if(resp.data.validar)
                    {
                        if(resp.data.insert)
                        {
                            document.getElementById("a").innerHTML = `Calificaciones del/la alumn@: ${resp.data.name} agregadas`;
                            document.getElementById("a").className = "animate__animated animate__lightSpeedInLeft add";
                            setTimeout(function(){document.getElementById("a").className = "animate__animated animate__lightSpeedOutLeft add";}, 2000);
                            setTimeout(function(){document.getElementById("a").innerHTML = "";}, 2800);
                        }
                        else
                        {
                            alert(`Algo ha salido mal`)
                        }
                    }
                    else
                    {
                        document.getElementById("a").innerHTML = `Ese alumno ya tiene calificaciones`;
                        document.getElementById("a").className = "animate__animated animate__shakeX error";
                        setTimeout(function(){document.getElementById("a").className = "animate__animated animate__backOutLeft error";}, 2000);
                        setTimeout(function(){document.getElementById("a").innerHTML = "";}, 2300);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }

            function getcali()
            {
                axios.post(`api/index.php/getcali/${document.forms[0].id.value}`, {
                })
                .then(resp => {
                    if(resp.data.users)
                    {
                        document.getElementById("cali1").value = resp.data.cali1;
                        document.getElementById("cali2").value = resp.data.cali2;
                        document.getElementById("cali3").value = resp.data.cali3;
                        document.getElementById("prom").value = (resp.data.cali1 + resp.data.cali2 + resp.data.cali3)/3;
                    }
                    else
                    {
                        var error = document.getElementById("error");

                        error.style.display = "block";
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
//Si no hay sesión activa redirije al login
else
{
    //! Metadata para redirijir al index
    echo'
    <meta http-equiv="Refresh" content="0;url=login.php">
    </div></body></html>
    ';
}
?>