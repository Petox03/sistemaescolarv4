<?php
require_once 'data.php';
echo'
<body onload="getdelalumn()">
';

//Se valdia si hay una sesión existente
if($loggedin)
{
    if($access == 1)
    {
        //! Llama a el header
        require_once 'header.php';


        if(isset($_GET['id_del']))
        {
            $alumno = $_GET['name'];
            echo'
            <div class="center animate__animated animate__backInDown" id="cancelar">
                <p class="is-size-4 check">Usted está apunto de eliminar las calificaciones del/la alumn@: '.$alumno.'</p>
                <form>
                    <input type="text" class="is-invisible" id="id_del" value="'.$_GET['id_del'].'">
                </form>
                <br>
                <a class="button is-info is-rounded mr-6" onclick="confirmar()">Confirmar</a>
                <a class="button is-danger is-rounded ml-6" onclick="cancelar()">Cancelar</a>
            </div>
            ';
        }

        echo'
        <div id="eliminado">
        </div>
        ';

        echo'
        <div class="container mb-4">
            <div class="columns is-centered">
                <div id="alumn" class="del shadow is-12 is-offset-7 animate__animated animate__fadeIn animate__slow mt-6 padDelete">

                </div>
            </div>
        </div>
        ';
    }
    else{
        echo'<meta http-equiv="Refresh" content="0;url=index.php">';
    }
    echo'

        <script>
            function redireccionar(){
                window.locationf="http://www.cristalab.com";
            }
            function getdelalumn()
            {
                axios.post(`api/index.php/delete`, {
                })
                .then(resp => {
                    const alumnos = resp.data.alumnos;
                    var miDiv = document.getElementById("alumn");
                    if(resp.data.cantidad)
                    {
                        alumnos.forEach(alumnos =>{
                            var miAlumno = document.createElement("p");
                            var id = alumnos.id_user;

                            miAlumno.innerHTML = alumnos.name+" "+alumnos.lastname;
                            miAlumno.className = "mt-4 mb-4 fonttxt is-size-4";
                            miAlumno.setAttribute("id", id);

                            var a = document.createElement("a");
                            var linkText = document.createTextNode(" Eliminar");
                            a.appendChild(linkText);
                            a.href = `delete.php?id_del=`+alumnos.id_user+`&name=`+alumnos.name+`%20`+alumnos.lastname+``;
                            a.className = "button is-link ml-4";
                            miAlumno.appendChild(a);

                            miDiv.appendChild(miAlumno);
                        })
                    }
                    else
                    {
                        var error = document.createElement("p");

                        error.innerHTML = "No hay alumnos con calificaciones";

                        error.className = "error is-size-2";

                        miDiv.appendChild(error);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
            function confirmar()
            {
                axios.post(`api/index.php/delete/${document.forms[0].id_del.value}`, {
                })
                .then(resp => {
                    if(resp.data.delete)
                    {
                        document.getElementById("cancelar").style.display = "none";
                        var divEliminar = document.getElementById("eliminado");
                        var p = document.createElement("p");

                        p.innerHTML = "Calificaciones del alumn@ "+resp.data.name+" eliminadas satisfactoriamente";
                        p.className = "check is-size-4 animate__animated animate__bounceInDown";

                        divEliminar.appendChild(p);

                        var selectalumn = document.getElementById(resp.data.id_user);

                        selectalumn.style.display = "none";

                        setTimeout(function(){document.getElementById("eliminado").className = "animate__animated animate__zoomOutUp";}, 1500);
                        setTimeout(function(){document.getElementById("eliminado").style.display = "none";}, 2200);
                    }
                    else
                    {
                        document.getElementById("cancelar").style.display = "none";
                        document.getElementById("cancelar").style.display = "none";
                        var diveliminar = document.getElementById("eliminado");
                        var p = document.createElement("p");

                        p.innerHTML = "Alumno sin calificaciones, seleccione otro";
                        p.className = "error center is-size-2 animate__animated animate__shakeX";

                        diveliminar.appendChild(p);

                        setTimeout(function(){document.getElementById("eliminado").className = "animate__animated animate__zoomOutUp";}, 2000);
                        setTimeout(function(){document.getElementById("eliminado").style.display = "none";}, 2650);
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
            function cancelar()
            {
                setTimeout(function(){document.getElementById("cancelar").className += "animate__animated animate__backOutUp";}, 300);
                setTimeout(function(){document.getElementById("cancelar").style.display = "none";}, 700);
            }
        </script>
    </body>

    </html>
    ';
}
//Si no hay sesión activa redirije al login
else
{
    echo'
    <meta http-equiv="Refresh" content="0;url=log.php">
    </div></body></html>
    ';
}
?>