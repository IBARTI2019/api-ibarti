<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// Import Monolog classes into the global namespace
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$container = $app->getContainer();

$container['cache'] = function () {
	return new \Slim\HttpCache\CacheProvider();
};

$container["logger"] = function ($c) {
	// create a log channel
	$log = new Logger("api");
	$log->pushHandler(new StreamHandler(__DIR__ . "/../logs/app.log", Logger::INFO));

	return $log;
};

define('RUTAS',[]);
// $rutas = ['hola'];

$app->add(new \Slim\HttpCache\Cache('private', 300, true));

/**
 * This method restricts access to addresses. <br/>
 * <b>post: </b>To access is required a valid token.
 */
$app->add(new \Slim\Middleware\JwtAuthentication([
	// The secret key
	"secret" => SECRET,
	"rules" => [
		new \Slim\Middleware\JwtAuthentication\RequestPathRule([
			// Degenerate access to "/webresources"
			"path" => "/webresources",
			// It allows access to "login" without a token
			"passthrough" => [
				"/webresources/mobile_app/ping",
				"/webresources/mobile_app/login",
				"/webresources/mobile_app/register",
				"/webresources/mobile_app/validate"
			]
		])
	]
]));

/**
 * This method settings CORS requests
 *
 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
 * @param	callable                                 	$next     	Next middleware
 *
 * @return	\Psr\Http\Message\ResponseInterface
 */
$app->add(function (Request $request, Response $response, $next) {
	$response = $next($request, $response);
	// Access-Control-Allow-Origin: <domain>, ... | *
	$response = $response->withHeader('Access-Control-Allow-Origin', '*')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	return $response;
});

$app->add(function (Request $request, Response $response, $next) {
	$acceder = true;
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	$email = $request->getHeader('HTTP_USUARIO') != null ? $request->getHeader('HTTP_USUARIO')[0] : '';
	$bds = $request->getHeader('HTTP_BD') != null ? $request->getHeader('HTTP_BD')[0] : '';
	$newDb = DB_NAME;
	if ($bds == '') {
		if ($email != '') {
			try {
				$conn = PDOConnection::getConnection(DB_NAME);
				// Gets the posts into the database
				$sql = "SELECT db FROM usuarios where email = '$email'";
				$stmt = $conn->query($sql);
				$data = $stmt->fetchAll();
				$newDb = $data[0]['db'];
			} catch (PDOException $e) {
				$resp['error'] = true;
				$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
				$this["logger"]->error("DataBase Error: {$e->getMessage()}");
			} catch (Exception $e) {
				$resp['error'] = true;
				$resp['errorD'] = "General Error: {$e->getMessage()}";
				$this["logger"]->error("General Error: {$e->getMessage()}");
			} finally {
				$conn = null;
			}
		} else {
			$newDb = DB_NAME;
		}
	} else {
		$newDb = $bds;
		define('DB_CLIENT', $newDb);
		$cod_usuario = $request->getHeader('HTTP_LOCAL') != null ? $request->getHeader('HTTP_LOCAL')[0] : '';
		if ($cod_usuario != "") {
			$metodo = $request->getMethod();
			$url = $request->getUri();
			$path = explode('/', $url->getPath())[0];
			try {
				// Gets the database connection

				$conn = PDOConnection::getConnection($bds);

				// Gets the posts into the database
				$sql = "SELECT
						usuario.id cod_usuario,
						roll.SU superUsuario,
						permiso.direccion,
						accion.metodo
					FROM
						usuario,
						roll,
						permiso,
						accion,
						permiso_roll
					WHERE
						usuario.codigo_acceso = '$cod_usuario'
					AND usuario.roll = roll.id
					AND permiso_roll.cod_roll = roll.id
					AND permiso_roll.cod_permiso = permiso.id
					AND permiso_roll.cod_accion = accion.id
					AND permiso.direccion = '/$path'
					AND accion.metodo = '$metodo'
					UNION 
					SELECT
						usuario.id cod_usuario,
						roll.SU superUsuario,
						permiso.direccion,
						accion.metodo
					FROM
						usuario,
						roll,
						permiso,
						accion,
						permiso_usuario
					WHERE
						usuario.codigo_acceso = '$cod_usuario'
					AND usuario.roll = roll.id
					AND permiso_usuario.cod_usuario = usuario.id
					AND permiso_usuario.cod_permiso = permiso.id
					AND permiso_usuario.cod_accion = accion.id
					AND permiso.direccion = '/$path'
					AND accion.metodo = '$metodo'
				";
				$stmt = $conn->query($sql);
				$data = $stmt->fetchAll();
				$resp['data'] = $data;
				if(!isset($data[0]['cod_usuario']) && !in_array($path,RUTAS)){
					$acceder = false;
					$resp['error'] = true;
					$resp['errorD'] = "Usted no tiene acceso";
				}
				// return $response;
			} catch (PDOException $e) {
				$resp['error'] = true;
				$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
				$this["logger"]->error("DataBase Error: {$e->getMessage()}");
			} catch (Exception $e) {
				$resp['error'] = true;
				$resp['errorD'] = "General Error: {$e->getMessage()}";
				$this["logger"]->error("General Error: {$e->getMessage()}");
			} finally {
				$conn = null;
			}
		}
	}
	if($acceder){
		$response = $next($request, $response);
	}else{
		$response = $response->withHeader("Content-Type", "application/json")
		->withStatus(200, "OK")
		->withJson($resp);
	}
	return $response;
});

require('src/controllers/users.controller.php');
require('src/controllers/ubication.controller.php');
require('src/controllers/person.controller.php');
require('src/controllers/auto.controller.php');
require('src/controllers/permiso.controller.php');
