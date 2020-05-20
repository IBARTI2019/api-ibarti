<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require('src/services/usuario.service.php');
// Import Monolog classes into the global namespace
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app->group("/usuario", function () use ($app) {

	$app->get("/test",function (Request $request, Response $response){
		// print_r(DB_NAME);
		$tst = array(0=>'placa_client',1=>'placa_client2');
		$n = rand(0,1);
		define('DB_NAME',$tst[$n]);
		print_r(DB_NAME);
	});
	/**
	 * This method sets an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return	\Psr\Http\Message\ResponseInterface
	 */
	$app->post("", function (Request $request, Response $response) {
		$body = $request->getParsedBody();
		$respuesta = call_user_func('addUser', $body);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	/**
	 * This method updates an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return	\Psr\Http\Message\ResponseInterface
	 */
	$app->put("/{cod}", function (Request $request, Response $response) {
		$cod = $request->getAttribute('cod');
		$body = $request->getParsedBody();
		$respuesta = call_user_func('updateUser', $cod, $body);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	/**
	 * This method deletes a specific message by its id.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->delete("/{cod}", function (Request $request, Response $response) {
		/** @var string $id - The quote id */
		$id = $request->getAttribute("cod");
		$respuesta = call_user_func('deleteUser', $id);
		print_r($id);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});
	/**
	 * 
	 * 
	 * 
	 * This method gets an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return	\Psr\Http\Message\ResponseInterface
	 */
	$app->get("/login/{cod}/{pass}", function (Request $request, Response $response) {
		/** @var string $user - Username */
		$cod = $request->getAttribute("cod");
		/** @var string $pass - Password */
		$pass = $request->getAttribute("pass");
		printf($cod);
		$respuesta = call_user_func('login', $cod,$pass);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});


	///////////////////////////PENDIENTE////////////////////////////////
	/**
	 * This method validates an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->get("/validate/{cod}", function (Request $request, Response $response) {
		/** @var string $user - Username */
		$cod = $request->getAttribute("cod");

		$respuesta = call_user_func('validateCod', $cod);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	$app->get("/logOut/{cod}", function (Request $request, Response $response) {
		/** @var string $user - Username */
		$cod = $request->getAttribute("cod");

		$respuesta = call_user_func('logOut', $cod);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	/**
	 * This method cheks the token.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->get("/verify", function (Request $request, Response $response) {
		// Gets the token of the header.
		// Authorization: Bearer {token}
		/** @var string $token - Token */
		$token = str_replace("Bearer ", "", $request->getServerParams()["HTTP_AUTHORIZATION"]);
		// Verify the token.
		$result = JWTAuth::verifyToken($token);
		// Return the result
		if ($result) {
			$data["id"] = $result->header->id;
			$data["cod"] = $result->header->codigo_acceso;
			$data["status"] = true;
		} else {
			$data["status"] = "Error: Authentication token is invalid.";
		}
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($data);
		return $response;
	});

	/**
	 * This method list the latest published messages.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */

	 ///////////////////////////END PENDIENTE////////////////////////////////
	$app->get("", function (Request $request, Response $response) {
		$respuesta = call_user_func('getAllUser',);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	$app->get("/{cod}", function (Request $request, Response $response) {
		$cod = $request->getAttribute('cod');
		$respuesta = call_user_func('getUser', $cod);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});
});

$app->group("/usuarioUbic", function () use ($app) {

	/**
	 * This method sets an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return	\Psr\Http\Message\ResponseInterface
	 */

	$app->post("", function (Request $request, Response $response) {

		$body = $request->getParsedBody();
		$respuesta = call_user_func('addUbicationUser', $body);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	/**
	 * This method deletes a specific message by its id.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->delete("/{cod}", function (Request $request, Response $response) {
		/** @var string $id - The quote id */
		$id = $request->getAttribute("cod");
		$respuesta = call_user_func('deleteUbicationUser', $id);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});
	/**
	 * This method list the latest published messages.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->get("", function (Request $request, Response $response) {
		$respuesta = call_user_func('getAllUbicationUser');

		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
	});

	$app->get("/{cod}", function (Request $request, Response $response) {
		$cod = $request->getAttribute('cod');
		$respuesta = call_user_func('getUbicationUser', $cod);
		$response = $response->withHeader("Content-Type", "application/json")
			->withStatus(200, "OK")
			->withJson($respuesta);
		return $response;
		// return $response;
	});
});
