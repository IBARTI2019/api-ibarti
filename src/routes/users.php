<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Import Monolog classes into the global namespace
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$app->group("/usuario", function () use ($app) {
	/**
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

		try {
			// Gets the database connection
			$conn = PDOConnection::getConnection();

			// Gets the user into the database
			$sql = "SELECT	*
					FROM	usuario
					WHERE	codigo_acceso = :cod";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(":cod", $cod);
			$stmt->execute();
			$query = $stmt->fetchObject();
			// return json_encode($query);
			// If user exist
			if ($query) {
				// If password is correct
				// print_r(password_verify($pass, $query->clave));
				if (password_verify($pass, $query->clave)) {
					// Create a new resource
					$data["token"] = JWTAuth::getToken($query->id, $query->codigo_acceso);
					$sql = "UPDATE usuario SET token='" . $data['token'] . "', logIn='1' WHERE codigo_acceso = '$cod'";
					$res = $conn->exec($sql);
					if ($res) {
						$response = $response->withHeader("Content-Type", "application/json")
							->withStatus(201, "Created")
							->withJson($data);
						return $response;
					} else {
						print_r("Error");
					}
				} else {
					// Password wrong
					$data["status"] = "Error: The password you have entered is wrong.";
				}
			} else {
				// Username wrong
				$data["status"] = "Error: The user specified does not exist.";
			}

			// Return the result

			// return json_encode($data);
		} catch (PDOException $e) {
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
	});

	/**
	 * This method sets an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return	\Psr\Http\Message\ResponseInterface
	 */
	$app->post("/register", function (Request $request, Response $response) {
		$letra = '';
		$body = $request->getParsedBody();
		for ($i = 0; $i < 2; $i++) {
			$letra .= ($i % 2 == 0) ? strtoupper(chr(rand(ord('0'), ord('9')))) : chr(rand(ord('0'), ord('9')));
			$letra .= ($i % 2 == 0) ? strtoupper(chr(rand(ord('a'), ord('z')))) : chr(rand(ord('a'), ord('z')));
			$letra .= ($i % 2 == 0) ? strtoupper(chr(rand(ord('A'), ord('Z')))) : chr(rand(ord('a'), ord('z')));
		}

		try {
			$conn = PDOConnection::getConnection();
			$sql = "SELECT count(codigo_acceso) cant FROM usuario WHERE codigo_acceso='$letra'";
			$stmt = $conn->query($sql);
			$data = $stmt->fetchAll();
			if ($data) {
				if (intval($data[0]['cant']) < 1) {
					$passHash = password_hash($body['pass'], PASSWORD_BCRYPT);
					try {
						$sql = "INSERT INTO	usuario (nombre, apellido, codigo_acceso,clave, logIn, status,token)
						VALUES ('" . $body['nombre'] . "','" . $body['apellido'] . "', '$letra','" . $passHash . "', '0', '1','')";
						$res = $conn->exec($sql);


						if ($res) {
							$newData = array(
								"codigo" => $letra
							);
							$response = $response->withHeader("Content-Type", "application/json")
								->withStatus(200, "OK")
								->withJson($newData);
							return $response;
						} else {
							print_r("No se pudo registrar");
						}
					} catch (PDOException $e) {
						$this["logger"]->error("DataBase Error: {$e->getMessage()}");
					} catch (Exception $e) {
						$this["logger"]->error("General Error: {$e->getMessage()}");
					}
				}
			} else {
				print_r("Error: Your account cannot be created at this time. Please try again later.");
			}
		} catch (PDOException $e) {
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
		// try {
		// 	// Gets the database connection
		// 	$conn = PDOConnection::getConnection();

		// 	// Gets the user into the database
		// 	$sql = "INSERT INTO	USERS (GUID, TOKEN, USERNAME, PASSWORD, CREATED_AT, ID_COUNTRY)
		// 			VALUES		(:guid, :token, :user, :pass, :created, :country)";
		// 	$stmt = $conn->prepare($sql);
		// 	$stmt->bindParam(":guid", $guid);
		// 	$stmt->bindParam(":token", $token);
		// 	$stmt->bindParam(":user", $user);
		// 	$stmt->bindParam(":pass", $pass);
		// 	$stmt->bindParam(":created", $created);
		// 	$stmt->bindParam(":country", $country);
		// 	$result = $stmt->execute();

		// 	// If user has been registered
		// 	if ($result) {
		// 		$data["status"] = "Your account has been successfully created. We will send you an email to confirm that your email address is valid.";

		// 		$to = $email;
		// 		$name = $user;
		// 		$subject = "Confirm your email address";

		// 		// Example of the confirmation link: http://localhost/REST-Api-with-Slim-PHP/public/webresources/mobile_app/validate/testUser/326f0911657d94d0a48530058ca2a383
		// 		$html = "Click on the link to verify your email <a href='http://{yourdomain}/public/webresources/mobile_app/validate/{$user}/{$token}' target='_blank'>Link</a>";
		// 		$text = "Go to the link to verify your email: http://{yourdomain}/public/webresources/mobile_app/validate/{$user}/{$token}";

		// 		// Sent mail verification
		// 		Mailer::send($to, $name, $subject, $html, $text);
		// 	} else {
		// 		$data["status"] = "Error: Your account cannot be created at this time. Please try again later.";
		// 	}

		// 	$response = $response->withHeader("Content-Type", "application/json")
		// 		->withStatus(200, "OK")
		// 		->withJson($data);
		// 	return $response;
		// } catch (PDOException $e) {
		// 	$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		// } catch (Exception $e) {
		// 	$this["logger"]->error("General Error: {$e->getMessage()}");
		// } finally {
		// 	// Destroy the database connection
		// 	$conn = null;
		// }
	});

	/**
	 * This method validates an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->get("/validate/{user}", function (Request $request, Response $response) {
		/** @var string $user - Username */
		$user = $request->getAttribute("user");

		try {
			// Gets the database connection
			$conn = PDOConnection::getConnection();

			$sql = "SELECT	*
					FROM	USERS
					WHERE	USERNAME = :user
						AND	TOKEN = :token";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(":user", $user);
			$stmt->bindParam(":token", $token);
			$stmt->execute();
			$query = $stmt->fetchObject();

			// If user exist
			if ($query) {
				// Gets the user into the database
				$sql = "UPDATE	USERS
						SET		TOKEN = NULL,
								STATUS = 1
						WHERE	USERNAME = :user";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(":user", $user);
				$result = $stmt->execute();

				// If user has been verified
				if ($result) {
					$data["status"] = "Your account has been successfully verified.";
				} else {
					$data["status"] = "Error: Your account cannot be verified.";
				}
			} else {
				// Username wrong
				$data["status"] = "Error: The user specified does not exist.";
			}

			$response = $response->withHeader("Content-Type", "application/json")
				->withStatus(200, "OK")
				->withJson($data);
			return $response;
		} catch (PDOException $e) {
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
	});

	/**
	 * This method updates an user into the database.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return	\Psr\Http\Message\ResponseInterface
	 */
	$app->put("/update/{cod}", function (Request $request, Response $response) {
		$cod = $request->getAttribute('cod');
		$body = $request->getParsedBody();
		try {
			$conn = PDOConnection::getConnection();
			$sql = "SELECT count(codigo_acceso) cant FROM usuario WHERE codigo_acceso='$cod'";
			$stmt = $conn->query($sql);
			$data = $stmt->fetchAll();
			if ($data) {
				if (intval($data[0]['cant']) >= 1) {

					$passHash = isset($body['pass']) ? password_hash($body['pass'], PASSWORD_BCRYPT) : '';
					try {
						$sql = "UPDATE usuario SET
							nombre = '" . $body['nombre'] . "',
							apellido = '" . $body['apellido'] . "',
							status = '" . $body['status'] . "'
						";
						$sql .= ($passHash != '') ? ",clave = '" . $passHash . "'" : '';
						$sql .= " WHERE codigo_acceso = '$cod'";
						print_r($sql);
						// $sql = "INSERT INTO	usuario (nombre, apellido, codigo_acceso,clave, logIn, status,token)
						// VALUES ('Luis', 'Augustin', '$letra','" . $passHash . "', '0', '1','')";
						$res = $conn->exec($sql);
						if ($res) {
							print_r("Listo");
						} else {
							print_r("Error");
						}

						// if ($res) {
						// 	$newData = array(
						// 		"codigo" => $letra
						// 	);
						// 	$response = $response->withHeader("Content-Type", "application/json")
						// 		->withStatus(200, "OK")
						// 		->withJson($newData);
						// 	return $response;
						// } else {
						// 	print_r("No se pudo registrar");
						// }
					} catch (PDOException $e) {
						$this["logger"]->error("DataBase Error: {$e->getMessage()}");
					} catch (Exception $e) {
						$this["logger"]->error("General Error: {$e->getMessage()}");
					}
				}
			} else {
				print_r("Error: Your account cannot be created at this time. Please try again later.");
			}
		} catch (PDOException $e) {
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
		// try {
		// 	// Gets the database connection
		// 	$conn = PDOConnection::getConnection();

		// 	// Gets the user into the database
		// 	$sql = "INSERT INTO	USERS (GUID, TOKEN, USERNAME, PASSWORD, CREATED_AT, ID_COUNTRY)
		// 			VALUES		(:guid, :token, :user, :pass, :created, :country)";
		// 	$stmt = $conn->prepare($sql);
		// 	$stmt->bindParam(":guid", $guid);
		// 	$stmt->bindParam(":token", $token);
		// 	$stmt->bindParam(":user", $user);
		// 	$stmt->bindParam(":pass", $pass);
		// 	$stmt->bindParam(":created", $created);
		// 	$stmt->bindParam(":country", $country);
		// 	$result = $stmt->execute();

		// 	// If user has been registered
		// 	if ($result) {
		// 		$data["status"] = "Your account has been successfully created. We will send you an email to confirm that your email address is valid.";

		// 		$to = $email;
		// 		$name = $user;
		// 		$subject = "Confirm your email address";

		// 		// Example of the confirmation link: http://localhost/REST-Api-with-Slim-PHP/public/webresources/mobile_app/validate/testUser/326f0911657d94d0a48530058ca2a383
		// 		$html = "Click on the link to verify your email <a href='http://{yourdomain}/public/webresources/mobile_app/validate/{$user}/{$token}' target='_blank'>Link</a>";
		// 		$text = "Go to the link to verify your email: http://{yourdomain}/public/webresources/mobile_app/validate/{$user}/{$token}";

		// 		// Sent mail verification
		// 		Mailer::send($to, $name, $subject, $html, $text);
		// 	} else {
		// 		$data["status"] = "Error: Your account cannot be created at this time. Please try again later.";
		// 	}

		// 	$response = $response->withHeader("Content-Type", "application/json")
		// 		->withStatus(200, "OK")
		// 		->withJson($data);
		// 	return $response;
		// } catch (PDOException $e) {
		// 	$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		// } catch (Exception $e) {
		// 	$this["logger"]->error("General Error: {$e->getMessage()}");
		// } finally {
		// 	// Destroy the database connection
		// 	$conn = null;
		// }
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
	$app->get("", function (Request $request, Response $response) {
		try {
			// Gets the database connection

			$conn = PDOConnection::getConnection();

			// Gets the posts into the database
			$sql = "SELECT * FROM usuario";
			$stmt = $conn->query($sql);
			$data = $stmt->fetchAll();

			// Return a list
			/*
			$response = $response->withHeader("Content-Type", "application/json")
				->withStatus(200, "OK")
				->withJson($data);
			*/
			return json_encode($data);
			// return $response;
		} catch (PDOException $e) {
			print_r("DataBase Error: {$e->getMessage()}");
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			print_r("General Error: {$e->getMessage()}");
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
	});
	$app->get("/{cod}", function (Request $request, Response $response) {
		try {
			// Gets the database connection
			$cod = $request->getAttribute('cod');
			$conn = PDOConnection::getConnection();

			// Gets the posts into the database
			$sql = "SELECT * FROM usuario where codigo_acceso = '$cod'";
			$stmt = $conn->query($sql);
			$data = $stmt->fetchAll();

			// Return a list
			/*
			$response = $response->withHeader("Content-Type", "application/json")
				->withStatus(200, "OK")
				->withJson($data);
			*/
			return json_encode($data);
			// return $response;
		} catch (PDOException $e) {
			print_r("DataBase Error: {$e->getMessage()}");
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			print_r("General Error: {$e->getMessage()}");
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
	});
	/**
	 * This method deletes a specific message by its id.
	 *
	 * @param	\Psr\Http\Message\ServerRequestInterface	$request	PSR7 request
	 * @param	\Psr\Http\Message\ResponseInterface      	$response	PSR7 response
	 *
	 * @return 	\Psr\Http\Message\ResponseInterface
	 */
	$app->delete("/delete/{cod}", function (Request $request, Response $response) {
		/** @var string $id - The quote id */
		$id = $request->getAttribute("cod");

		try {
			// Gets the database connection
			$conn = PDOConnection::getConnection();

			// Delete the quote
			$sql = "DELETE FROM	usuario
					WHERE		codigo_acceso = '$id'";
			$result = $conn->exec($sql);
			

			// Return the result
			$data["status"] = $result;

			$response = $response->withHeader("Content-Type", "application/json")
				->withStatus(200, "OK")
				->withJson($data);
			return $response;
		} catch (PDOException $e) {
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$this["logger"]->error("General Error: {$e->getMessage()}");
		} finally {
			// Destroy the database connection
			$conn = null;
		}
	});
});
