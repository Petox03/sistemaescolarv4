<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

// Instantiate app
$app = AppFactory::create();
$app->setBasePath("/sistemaescolarv4/api/index.php");

// Add Error Handling Middleware
$app->addErrorMiddleware(true, false, false);

// Add route callbacks
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Hello World');
    return $response;
});
$app->post('/login/{user}', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $users = DB::table('users')->where('user', $args['user'])->first();

    $msg = new stdClass();

    if($users->pass == $data->pass)
    {
        $msg->aceptado = true;
        $msg->iduser = $users->id_user;
        $msg->user = $users->user;
        $msg->idaccess = $users->idaccess;
        $msg->name = $users->name;
        $msg->lastname = $users->lastname;
    }
    else {
        $msg->aceptado = false;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/singup', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $user = $data->user;
    $pass = $data->pass;
    $Rpass = $data->Rpass;
    $name = $data->name;
    $ape = $data->lastname;

    $msg = new stdClass();

    //Validamos que las variables no estén vacías
    if($user == "" || $pass == "" || $Rpass == "" || $name == "" || $ape == "")
    {
        $msg->datos = false;
    }
    else
    {
        $msg->datos = true;

        //Se validan que las contraseñas sean iguales
        if ($pass != $Rpass)
        {
            $msg->passes = false;
        }
        else
        {
            $msg->passes = true;

            //! Consulta si hay un user igual al que se intenta registrar
            $users = DB::table('users')->where('user',$user)->first();

            //Se comprueba si existe un user con las condiciones puestas
            if($users)
            {
                $msg->userExist = true;
            }
            else
            {
                $msg->userExist = false;

                //? Inserción de los datos del nuevo usuario a la base de datos
                $usuario = DB::table('users')->insertGetId(
                    ['user' => $user, 'pass' => $pass, 'idaccess' => '2', 'name' => $name, 'lastname' => $ape]
                );

                if($usuario)
                {
                    $msg->singup = true;
                }
                else
                {
                    $msg->singup = false;
                }
            }
        }
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/add/{alumn}', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    //!Consulta para saber el nombre del alumno
    $name = DB::table('users')->where('id_user', $args['alumn'])->first();

    $msg = new stdClass();

    if($name)
    {
        $msg->name = $name->name . " " . $name->lastname;
        //!Consulta para validar que no haya un alumno con el mismo id en la tabla
        $validar = DB::table('materias')->where('users_id_user',$args['alumn'])
        ->first();
        if(!$validar)
        {
            $msg->validar = true;
            //!Inserción de calificaciones
            $calificaciones = DB::table('materias')->insertOrIgnore(
                ['users_id_user' => $data->alumn, 'español' => $data->cali1, 'matematicas' => $data->cali2, 'historia' => $data->cali3]
            );
            if($calificaciones)
            {
                $msg->insert = true;
            }
            else {
                $msg->insert = false;
            }
        }
        else {
            $msg->validar = false;
        }
    }
    else {
        $msg->existe = false;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/getalumn', function (Request $request, Response $response, array $args) {

    //! Consulta a la base de datos, llama alumnos
    $users = DB::table('users')->select(['id_user', 'name', 'lastname'])->where('id_user',"<>",1)->orderBy('lastname')->get();

    $msg = new stdClass();

    if($users)
    {
        $msg->users = true;
        $msg->alumnos = $users;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/getcali/{id_user}', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    //!consulta para saber las calificaciones de los alumnos
    $cali = DB::table('materias')->where('users_id_user', $args['id_user'])->first();

    $msg = new stdClass();

    if($cali)
    {
        $msg->users = true;
        $msg->cali1 = $cali->español;
        $msg->cali2 = $cali->matematicas;
        $msg->cali3 = $cali->historia;
    }
    else {
        $msg->users = false;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/getmoduleModi/{alumn}', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $alumno = DB::table('materias')->where('users_id_user', $args['alumn'])
    ->leftJoin('users', 'materias.users_id_user', '=', 'users.id_user')
    ->first();

    $msg = new stdClass();

    if($alumno)
    {
        $msg->alumno = true;
        $msg->name = $alumno->name . " " . $alumno->lastname;
        $msg->id = $alumno->id_user;
        $msg->cali1 = $alumno->español;
        $msg->cali2 = $alumno->matematicas;
        $msg->cali3 = $alumno->historia;
    }
    else {
        $msg->alumno = false;
    }


    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/modify', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    //!Update de las calificaciones
    $Ncali = DB::table('materias')
    ->where('users_id_user', $data->alumn)
    ->update(['español' => $data->cali1, 'matematicas' => $data->cali2, 'historia' => $data->cali3]);

    //!Consulta para saber el nombre del alumno
    $name = DB::table('users')->where('id_user', $data->alumn)->first();

    $msg = new stdClass();

    if($Ncali)
    {
        $msg->modi = true;
        $msg->name = $name->name . " " . $name->lastname;
    }
    else
    {
        $msg->modi = false;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/delete', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $C_alumnos = DB::table('materias')->count();

    $alumnos = DB::table('materias')
    ->leftJoin('users', 'materias.users_id_user', '=', 'users.id_user')
    ->select(['id_user', 'name', 'lastname'])
    ->orderBy('lastname')
    ->get();

    $msg = new stdClass();

    if($C_alumnos == 0)
    {
        $msg->cantidad = false;
    }
    else {
        $msg->cantidad = true;
        $msg->alumnos = $alumnos;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/delete/{id_del}', function (Request $request, Response $response, array $args) {

    $id_user = $args['id_del'];

    $name = DB::table('users')->where('id_user', $id_user)->first();

    $msg = new stdClass();

    $del = DB::table('materias')->where('users_id_user', $id_user)->delete();

    if($del)
    {
        $msg->delete = true;
        $msg->name = $name->name . " " . $name->lastname;
        $msg->id_user = $name->id_user;
    }
    else
    {
        $msg->delete = false;
    }


    $response->getBody()->write(json_encode($msg));
    return $response;
});

// Run application
$app->run();