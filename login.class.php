<?php
class LoginUser
{
	// class properties
	private $username;
	private $password;
	private $role;
	public $error;
	public $success;
	private $storage = "data\utilisateurs.json";
	private $stored_users;

	// class methods
	public function __construct($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
		$this->stored_users = json_decode(file_get_contents($this->storage), true);

		foreach ($this->stored_users as $user) {
			if ($user['username'] == $this->username) {
				$this->role = $user['role'];
			}
		}

		$this->login();
	}


	private function login()
	{
		foreach ($this->stored_users as $user) {
			if ($user['username'] == $this->username) {
				if (password_verify($this->password, $user['password'])) {
					session_start();
					$_SESSION['user'] = $this->username;
					$_SESSION['role'] = $this->role;
					header("location: home.php");
					exit();
				}
			}
		}
		return $this->error = "Nom d'utilisateur ou mot de passe incorrect";
	}
}
