<?php

/**
* User class
*/
require_once('role.php');
require_once('employee.php');
require_once('exceptions/invaliduserexception.php');
require_once('sqlsrvconnection.php');

class User
{

	private $name;
	private $password;
	private $role;
	private $status;
	private $empleado;


	public function getName() { return $this->name; }
	public function setName($value) { $this->name = $value; }

	public function getPassword() { return $this->password; }
	public function setPassword($value) { $this->password = $value; }

	public function getRole() { return $this->role; }
	public function setRole($value) { $this->role = $value; }

	public function getStatus() { return $this->status; }
	public function setStatus($value) { $this->status = $value; }

	public function getEmpleado() { return $this->empleado; }
	public function setEmpleado($value) { $this->empleado = $value; }

	function __construct()
	{
		//empty object
		if (func_num_args() == 0)
		{
			$this->name = '';
			$this->password = '';
			$this->role = new Role();
			$this->status = '';
		}
		//object with data from database
		if (func_num_args() == 1)
		{
			$arguments = func_get_args();
			$control = $arguments[0];
			//get connection
			$connection = SqlSrvConnection::getConnection();
			//query
			$query = 'select empleado, perfil, nombre, status
					from admin.usuarios
					where empleado = ?';
			//command
			$params = array($control);
			$command = sqlsrv_query($connection, $query, $params);
			$found = sqlsrv_has_rows($command);
			if ($found)
			{
				 while($user = sqlsrv_fetch_array($command))
            	{
					$this->name = $user['nombre'];
					$this->status = $user['status'];
					$this->role = new Role($user['perfil']);
					$this->empleado = new Employee($user['empleado']);
					//$this->password = '<ENCRIPTED>';
            	} /*While*/
			}

			else throw new InvalidUserException($control);//throw exception if record not found
            sqlsrv_free_stmt($command);
            sqlsrv_close($connection);
		}
		//object with data from arguments
		if (func_num_args() == 2)
		{
			$arguments = func_get_args();
			//$control = $arguments[0];
			//get connection
			$connection = SqlSrvConnection::getConnection();
			//query
			$query = "select empleado, perfil, nombre, status from rh.[usuarios] where nombre = ? and contrasena = hashbytes('sha1', ?)";
			//command
			$params = array($arguments[0], $arguments[1]);
			$command = sqlsrv_query($connection, $query, $params);
			$found = sqlsrv_has_rows($command);
			if ($found)
			{
				while($user = sqlsrv_fetch_array($command))
            	{
					$this->name = $user['nombre'];
					$this->status = $user['status'];
					$this->role = new Role($user['perfil']);
					$this->empleado = new Employee($user['empleado']);
            	} /*While*/
			}

			else throw new InvalidUserException('xD');//throw exception if record not found
            sqlsrv_free_stmt($command);
            sqlsrv_close($connection);
		}
	}//Constructor

	public function toJson()
	{
		return json_encode(array(
			'nombre' => $this->name,
			'status' => $this->status,
			'role' => json_decode($this->role->toJson()),
			'empleado' => json_decode($this->empleado->toJson())
		));
	}//toJSON
}

?>
