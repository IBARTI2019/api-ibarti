<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
//Usuarios

function getAllUser()
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "SELECT * FROM usuario
			";
		$stmt = $conn->query($sql);
		$data = $stmt->fetchAll();
		$resp['data'] = $data;
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
	return $resp;
}

function getUser($codigo)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "SELECT * FROM usuario where codigo_acceso = '$codigo'";
		$stmt = $conn->query($sql);
		$data = $stmt->fetchAll();
		$resp['data'] = $data;
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
	return $resp;
}

function validateCod($cod)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "SELECT
					usuario.codigo_acceso,
					usuario.id cod_usuario,
					permiso.id cod_permiso,
					permiso.descripcion permiso,
					accion.id cod_accion,
					accion.descripcion accion
				FROM
					usuario,
					roll,
					permiso,
					accion,
					permiso_roll
				WHERE
					usuario.codigo_acceso = '$cod'
				AND usuario.roll = roll.id
				AND permiso_roll.cod_roll = roll.id
				AND permiso_roll.cod_permiso = permiso.id
				AND permiso_roll.cod_accion = accion.id
				UNION
					SELECT
						usuario.codigo_acceso,
						usuario.id cod_usuario,
						permiso.id cod_permiso,
						permiso.descripcion permiso,
						accion.id cod_accion,
						accion.descripcion accion
					FROM
						usuario,
						roll,
						permiso,
						accion,
						permiso_usuario
					WHERE
						usuario.codigo_acceso = '$cod'
					AND usuario.roll = roll.id
					AND permiso_usuario.cod_usuario = usuario.id
					AND permiso_usuario.cod_permiso = permiso.id
					AND permiso_usuario.cod_accion = accion.id";
		$stmt = $conn->query($sql);
		$data = $stmt->fetchAll();
		$respuesta = array(
			"codigo_acceso" => $data[0]['codigo_acceso'],
			"permisos" => []
		);
		$newData = array();
		$permisoA = "";
		$i = 0;
		$metodos = [];
		$longitud_maxima = count($data);
		foreach ($data as $fila => $valor) {
			if ($i == 0) {
				$permisoA = $valor['permiso'];
				array_push($metodos, $valor);
			} else {
				if ($permisoA != $valor['permiso']) {
					$newData['permiso'] = $permisoA;
					$newData['acciones'] = $metodos;
					array_push($respuesta['permisos'], $newData);
					$metodos = [];
					$permisoA = $valor['permiso'];
					array_push($metodos, $valor);
				} else {
					array_push($metodos, $valor);
				}
			}
			if ($i == ($longitud_maxima - 1)) {
				$newData['permiso'] = $permisoA;
				$newData['acciones'] = $metodos;
				array_push($respuesta['permisos'], $newData);
				$metodos = [];
			}
			$i++;
		}

		try {
			$sql = "UPDATE usuario SET logIn='T' WHERE codigo_acceso = '$cod' ";
			$res = $conn->exec($sql);
			$resp['data'] = $respuesta;
		} catch (PDOException $e) {
			$resp['error'] = true;
			$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$resp['error'] = true;
			$resp['errorD'] = "General Error: {$e->getMessage()}";
			$this["logger"]->error("General Error: {$e->getMessage()}");
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
	return $resp;
}

function logOut($cod)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "UPDATE usuario SET logIn='F' WHERE codigo_acceso = '$cod' ";
		$stmt = $conn->query($sql);
		$data = $stmt->fetchAll();
		$resp['data'] = $data;
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
	return $resp;
}
function addUser($body)
{
	$letra = "";
	for ($i = 0; $i < 2; $i++) {
		$letra .= ($i % 2 == 0) ? strtoupper(chr(rand(ord('0'), ord('9')))) : chr(rand(ord('0'), ord('9')));
		$letra .= ($i % 2 == 0) ? strtoupper(chr(rand(ord('a'), ord('z')))) : chr(rand(ord('a'), ord('z')));
		$letra .= ($i % 2 == 0) ? strtoupper(chr(rand(ord('A'), ord('Z')))) : chr(rand(ord('a'), ord('z')));
	}
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		$conn = PDOConnection::getConnection(DB_CLIENT);
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
						$resp['data'] = array(
							"codigo" => $letra
						);
					} else {
						$resp['error'] = true;
						$resp['errorD'] = "No se pudo registrar";
						print_r("No se pudo registrar");
					}
				} catch (PDOException $e) {
					$resp['error'] = true;
					$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
					$this["logger"]->error("DataBase Error: {$e->getMessage()}");
				} catch (Exception $e) {
					$resp['error'] = true;
					$resp['errorD'] = "General Error: {$e->getMessage()}";
					$this["logger"]->error("General Error: {$e->getMessage()}");
				}
			}
		} else {
			$resp['error'] = true;
			$resp['errorD'] = "Error: Your account cannot be created at this time. Please try again later.";
			print_r("Error: Your account cannot be created at this time. Please try again later.");
		}
	} catch (PDOException $e) {
		$resp['error'] = true;
		$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
		$this["logger"]->error("DataBase Error: {$e->getMessage()}");
	} catch (Exception $e) {
		$resp['error'] = true;
		$resp['errorD'] = "General Error: {$e->getMessage()}";
		$this["logger"]->error("General Error: {$e->getMessage()}");
	} finally {
		// Destroy the database connection
		$conn = null;
	}
	return $resp;
}

function updateUser($cod, $body)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		$conn = PDOConnection::getConnection(DB_CLIENT);
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

					$res = $conn->exec($sql);
					if ($res) {
						$resp['data'] = array("Campos modificados" => $res);
					} else {
						$resp['data'] = array("Campos modificados" => $res);
					}
				} catch (PDOException $e) {
					$resp['error'] = true;
					$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
					$this["logger"]->error("DataBase Error: {$e->getMessage()}");
				} catch (Exception $e) {
					$resp['error'] = true;
					$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
					$this["logger"]->error("General Error: {$e->getMessage()}");
				}
			}
		} else {
			$resp['error'] = true;
			$resp['errorD'] = "Error: Your account cannot be created at this time. Please try again later.";
			print_r("Error: Your account cannot be created at this time. Please try again later.");
		}
	} catch (PDOException $e) {
		$resp['error'] = true;
		$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
		$this["logger"]->error("DataBase Error: {$e->getMessage()}");
	} catch (Exception $e) {
		$resp['error'] = true;
		$resp['errorD'] = "General Error: {$e->getMessage()}";
		$this["logger"]->error("General Error: {$e->getMessage()}");
	} finally {
		// Destroy the database connection
		$conn = null;
	}
	return $resp;
}

function deleteUser($codigo)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "DELETE FROM	usuario
		WHERE		codigo_acceso = '$codigo'";
		print_r($sql);
		$data = $conn->exec($sql);
		$resp['data'] = array("eliminados" => $data);
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
	return $resp;
}

function login($cod, $pass)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection
		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the user into the database
		$sql = "SELECT	*
				FROM	tbl_usuario
				WHERE	email = :cod";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":cod", $cod);
		$stmt->execute();
		$query = $stmt->fetchObject();
		// return json_encode($query);
		// If user exist
		if ($query) {
			// If password is correct
			// print_r(password_verify($pass, $query->clave));
			if (password_verify($pass, $query->pass)) {
				// Create a new resource
				// $token = JWTAuth::getToken($query->email, $query->cod_cliente);
				$resp['data'] = array(
					// "token"=> $token,
					"login" => true,
					"db" => $query->db
				);
				// $sql = "UPDATE usuario SET token='" . $token . "', logIn='1' WHERE codigo_acceso = '$cod'";
				// $res = $conn->exec($sql);
			} else {
				// Password wrong
				$resp['error'] = true;
				$resp['errorD'] = "Error: The password you have entered is wrong.";
			}
		} else {
			// Username wrong
			$resp['error'] = true;
			$resp['errorD'] = "Error: The user specified does not exist.";
		}

		// Return the result

		// return json_encode($data);
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
	return $resp;
}

///Ubicacion de Usuarios


function addUbicationUser($body)
{
	$ubicaciones = $body['ubicaciones'];
	$usuario = $body['usuario'];
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		$conn = PDOConnection::getConnection(DB_CLIENT);
		$sql = "DELETE FROM usuario_ubicacion WHERE cod_usuario='" . $usuario . "'";
		$data = $conn->exec($sql);
		try {
			$sql = "INSERT INTO usuario_ubicacion (cod_usuario,cod_ubicacion,status) VALUES ";
			foreach ($ubicaciones as $pos => $ubic) {
				if (intval($pos) > 0) {
					$sql .= ",";
				}
				$sql .= "($usuario,$ubic,'1')";
			}
			$res = $conn->exec($sql);
			if ($res) {
				$resp['data'] = array(
					"aprobado" => $res
				);
			} else {
				$resp['error'] = true;
				$resp['errorD'] = "No se pudo registrar";
			}
		} catch (PDOException $e) {
			$resp['error'] = true;
			$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
			$this["logger"]->error("DataBase Error: {$e->getMessage()}");
		} catch (Exception $e) {
			$resp['error'] = true;
			$resp['errorD'] = "General Error: {$e->getMessage()}";
			$this["logger"]->error("General Error: {$e->getMessage()}");
		}
	} catch (PDOException $e) {
		$resp['error'] = true;
		$resp['errorD'] = "DataBase Error: {$e->getMessage()}";
		$this["logger"]->error("DataBase Error: {$e->getMessage()}");
	} catch (Exception $e) {
		$resp['error'] = true;
		$resp['errorD'] = "General Error: {$e->getMessage()}";
		$this["logger"]->error("General Error: {$e->getMessage()}");
	} finally {
		// Destroy the database connection
		$conn = null;
	}
	return $resp;
}

function getAllUbicationUser()
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "SELECT
			usuario.id cod_usuario,
			ubicaciones.id cod_ubicacion,
			usuario.nombre usuario,
			ubicaciones.descripcion ubicacion
		FROM
			usuario,
			ubicaciones,
			usuario_ubicacion
		WHERE
			usuario_ubicacion.cod_usuario = usuario.id
		AND usuario_ubicacion.cod_ubicacion = ubicaciones.id
			";
		$stmt = $conn->query($sql);
		$data = $stmt->fetchAll();
		$resp['data'] = $data;
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
	return $resp;
}

function getUbicationUser($codigo)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "SELECT
			usuario.id cod_usuario,
			ubicaciones.id cod_ubicacion,
			usuario.nombre usuario,
			ubicaciones.descripcion ubicacion
		FROM
			usuario,
			ubicaciones,
			usuario_ubicacion
		WHERE
			usuario_ubicacion.cod_usuario = usuario.id
		AND usuario_ubicacion.cod_ubicacion = ubicaciones.id
		AND usuario.id = $codigo";
		$stmt = $conn->query($sql);
		$data = $stmt->fetchAll();
		$resp['data'] = $data;
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
	return $resp;
}

function deleteUbicationUser($codigo)
{
	$resp = array(
		"error" => false,
		"data" => [],
		"errorD" => ""
	);
	try {
		// Gets the database connection

		$conn = PDOConnection::getConnection(DB_CLIENT);

		// Gets the posts into the database
		$sql = "DELETE FROM	usuario_ubicacion
					WHERE		cod_usuario = '$codigo'";
		$data = $conn->exec($sql);
		$resp['data'] = array("eliminados" => $data);
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
	return $resp;
}
