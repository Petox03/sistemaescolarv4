<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

echo'
<body onload="getalumn()">
';
//Se valdia si hay una sesión existente
if($loggedin)
{
    if($access == 1)
    {
        //! Llama a el header
        require_once 'header.php';

        if(isset($_POST['alumn']))
        {
            //*Escapamos variables
            $alumno = sanitizeString($_POST['alumn']);
            $cali1 = sanitizeString($_POST['cali1']);
            $cali2 = sanitizeString($_POST['cali2']);
            $cali3 = sanitizeString($_POST['cali3']);

            //!Update de las calificaciones
            $Ncali = DB::table('materias')
                ->where('users_id_user', $alumno)
                ->update(['español' => $cali1, 'matematicas' => $cali2, 'historia' => $cali3]);

            //!Consulta para saber el nombre del alumno
            $name = DB::table('users')->where('id_user', $alumno)->first();

            //Validamos que se haya modificado las calificaciones del alumno
            if($Ncali)
            {
                die("
                <div class='check is-size-4'>
                        <meta http-equiv='Refresh' content='3;url=modi.php'>
                        <h1>Calificaciones del alumno(a) ". $name->name . " " . $name->lastname ." modificadas<h1>
                    </div>
                    </div></body></html>
                ");
            }
            else
            {
                $error2 = "Algo ha ido mal, por favor, inténtalo de nuevo";
            }
        }

        //? Módulo para elegir alumnos
        echo'
        <div class="container animate__animated animate__fadeIn animate__slow mt-3 mb-4">
            <form method="post" action="modi.php">
                <label></label>
                <h4 class="is-size-3">Selecciona un alumno.</h4>
                <h1 id="error" style="display: none">Ese alumno no tiene calificaciones, por favor, ingréselas</h1>
                <div class="field">
                    <label class="label" for="alumn">Alumno</label>
                    <div class="control">
                        <div class="select is-medium">
                            <select id="alumn" name="id_alumno" required>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="mt-3 button is-link" type="button" onclick="getmoduleModi()">Seleccionar</button>
                <br>
            </form>
        </div>
        ';

        //? Módulo para camnbiar calificaciones
        echo'
        <div id="correcto">
        </div>
        <div style="display: none" id="moduleModi">
            <div class="container animate__animated animate__fadeIn animate__slow mt-3 mt-4 mb-4">
                <form method="post" action="modi.php">
                    <label></label>
                    <h4 class="is-size-3" id="nombreModuleCambiar"></h4>
                    <h4 class="mt-3 mb-3 is-hidden" id="error2">Las calificaciones deben ser diferentes</h4>
                    <div class="field">
                        <div class="control">
                            <input class="input" type="text" id="id_alumno" name="id_alumno" hidden>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="cali1">Español</label>
                        <div class="control">
                            <input class="input" type="number" id="cali1" name="cali1" min="1" max="10" placeholder="Español">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="cali2">Matemáticas</label>
                        <div class="control">
                            <input class="input" type="number" id="cali2" name="cali2" min="1" max="10" placeholder="Matemáticas">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="cali3">Historia</label>
                        <div class="control">
                            <input class="input" type="number" id="cali3" name="cali3" min="1" max="10" placeholder="Historia">
                        </div>
                    </div>
                    <button class="mt-3 button is-link" type="button" onclick="modi()">Modificar</button>
                </form>
            </div>
        </div>
        <br>
        ';

        echo"
        </body>

        </html>
        ";
    }
    else{
        echo'<meta http-equiv="Refresh" content="0;url=index.php">';
    }
    echo'
        <script>
            function getmoduleModi()
            {
                axios.post(`api/index.php/getmoduleModi/${document.forms[0].alumn.value}`, {
                    alumn: document.forms[0].alumn.value
                })
                .then(resp => {
                    if(resp.data.alumno)
                    {
                        var nombre = document.getElementById("nombreModuleCambiar");

                        nombre.innerHTML = "Modifica las calificaciones del alumno(a) "+ resp.data.name +"";

                        document.getElementById("id_alumno").value = resp.data.id;
                        document.getElementById("cali1").value = resp.data.cali1;
                        document.getElementById("cali2").value = resp.data.cali2;
                        document.getElementById("cali3").value = resp.data.cali3;

                        document.getElementById("moduleModi").style.display = "block";
                    }
                    else
                    {
                        var error = document.getElementById("error");

                        error.className = "animate__animated animate__shakeX error is-size-3";
                        error.style.display = "block";
                        setTimeout(function(){error.className = "animate__animated animate__zoomOutUp error is-size-3";}, 1500);
                        setTimeout(function(){error.style.display = "none";}, 2200);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
            function modi()
            {
                axios.post(`api/index.php/modify`, {
                    alumn: document.getElementById("id_alumno").value,
                    cali1: document.getElementById("cali1").value,
                    cali2: document.getElementById("cali2").value,
                    cali3: document.getElementById("cali3").value
                })
                .then(resp => {
                    if(resp.data.modi)
                    {
                        var miDiv = document.getElementById("correcto");

                        var correcto = document.createElement("p");

                        setTimeout(function(){correcto.className = "is-size-4 check animate__animated animate__bounceInDown";correcto.innerHTML = "Calificaciones del/la alumn@ "+resp.data.name+" modificadas satisfactoriamente";}, 1200);
                        setTimeout(function(){correcto.className = "is-size-4 check animate__animated animate__zoomOutUp";}, 3200);
                        setTimeout(function(){correcto.style.display = "none";}, 3900);

                        miDiv.appendChild(correcto);
                    }
                    else
                    {
                        var error = document.getElementById("error2");

                        error.className = "animate__animated animate__shakeX error is-size-3";
                        error.style.display = "block";
                        setTimeout(function(){error.className = "animate__animated animate__flipOutX error is-size-3";}, 1500);
                        setTimeout(function(){error.style.display = "none";}, 2050);
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
    <meta http-equiv="Refresh" content="0;url=index.php">
    </div></body></html>
    ';
}

?>