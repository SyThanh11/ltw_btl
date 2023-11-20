<?php
require_once('controllers/main/base_controller.php');
require_once('models/user.php');

class LoginController extends BaseController
{
	function __construct()
	{
		$this->folder = 'login';
	}

	public function index()
	{
		session_start();
		if (isset($_SESSION["guest"]))
		{
			session_start();
			unset($_SESSION["guest"]);
			session_destroy();
			header("Location: index.php?page=main&controller=login&action=index");
		}
		else if (isset($_POST['submit-btn']))
		{
			$requiredFields = ['email', 'password'];
    
			foreach ($requiredFields as $field) {
				if (empty($_POST[$field])) {
					$err = "Vui lòng điền đầy đủ thông tin.";
					$data = array('err' => $err);
					$this->render('index', $data);
					header('Location: index.php?page=main&controller=register&action=index');
				}
			}

			$email = $_POST['email'];
			$password = $_POST['password'];
			unset($_POST);
			$check = User::validation($email, $password);
			if ($check == 1)
			{
				$_SESSION["guest"] = $email;
				header('Location: index.php?page=main&controller=layouts&action=index');
			}
			else 
			{
				$err = "Sai tài khoản hoặc mật khẩu";
				$data = array('err' => $err);
				$this->render('index', $data);
			}
		}
		else
		{
			$this->render('index');
		}
	}

	public function logout()
	{
		session_start();
		unset($_SESSION["guest"]);
		session_destroy();
		header("Location: index.php?page=main&controller=login&action=index");
	}
}
