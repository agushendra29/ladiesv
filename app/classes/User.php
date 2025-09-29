<?php
ob_start();

class User extends Objects {
	protected $pdo;

	// construct $pdo
	function __construct($pdo) {
		$this->pdo = $pdo;
	}
	
	public function getName($username)
	{
    $stmt = $this->pdo->prepare("SELECT name FROM suppliar WHERE suppliar_code = :username LIMIT 1");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['name'];
    }
    return ""; // kalau tidak ada
	}


	// user login method to dashboard
public function login($username, $pass)
{
    // --- Ambil user terakhir sesuai username / suppliar_code ---
    $stmt = $this->pdo->prepare("
        SELECT *
        FROM user
        WHERE
            (
                (login_name IS NOT NULL AND login_name <> '' AND login_name = :username)
                OR
                ((login_name IS NULL OR login_name = '') AND suppliar_code = :username)
            )
        ORDER BY id DESC       -- ambil paling akhir
        LIMIT 1
    ");
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        $_SESSION['login_error'] = "Invalid Username or Password";
        redirect("login.php");
        return;
    }

    // --- Cek status aktif (suspended) ---
    if ((int)$user->is_active === 0) {
        $_SESSION['login_error'] = "Your account has been suspended. Please contact admin.";
        redirect("login.php");
        return;
    }

    // --- Ambil general password ---
    $stmt2 = $this->pdo->prepare("SELECT general_password FROM common_setting LIMIT 1");
    $stmt2->execute();
    $generalPass = $stmt2->fetchColumn();

    // --- Cek password user atau general password ---
    $validPassword = false;
    if ($user->password === $pass) {
        $validPassword = true;
    }
    if (!$validPassword && $generalPass && $generalPass === $pass) {
        if (!in_array((int)$user->role_id, [1, 10], true)) {
            $validPassword = true;
        }
    }

    if (!$validPassword) {
        $_SESSION['login_error'] = "Invalid Username or Password";
        redirect("login.php");
        return;
    }

    // --- Set session ---
    $_SESSION['user_id']        = $user->id;
    $_SESSION['user_role']      = $user->username;
    $_SESSION['name']           = $this->getName($user->suppliar_code);
    $_SESSION['role_id']        = $user->role_id;
    $_SESSION['distributor_id'] = $user->suppliar_id;

    redirect("index.php");
}



	public function is_admin(){
		if ($_SESSION['user_role'] === 'admin') {
			return true;
		}else{
			return false;
		}
	}

	public function redirect_unauth_users($page){
		if ($_SESSION['user_role'] === 'admin') {
			return true;
		}else{
			redirect($page);
		}
	}


	//is user loged in or not
	public function is_login() {
		if (!empty($_SESSION['user_id'])) {
			return true;
		} else {
			return false;
		}
	}


	public function logOut() {
		unset($_SESSION['user_id']);
		unset($_SESSION['user_role']);
		$_SESSION = array();
		session_destroy();
		redirect("login.php");
	}

	public function checkUser($username)
	{
	  $stmt = $this->pdo->prepare("SELECT username FROM users WHERE username = :username AND deleted_at = ''");
	  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
	  $stmt->execute();
	  $count = $stmt->rowCount();
	  return ($count > 0)? true : false;
	}

	//check email if it is alrady sign up
	public function checkEmail($email)
	{
	  $stmt = $this->pdo->prepare("SELECT email FROM users WHERE email = :email AND deleted_at = ''");
	  $stmt->bindParam(":email", $email, PDO::PARAM_STR);
	  $stmt->execute();
	  $count = $stmt->rowCount();
	  return ($count > 0)? true : false;
	}

	public function userLog(){
		$stmt = $this->pdo->prepare("SELECT * FROM logs ORDER BY id DESC LIMIT 5 ");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// check email if it is alrady sign up
	public function checkUsername($username)
	{
	  $stmt = $this->pdo->prepare("SELECT username FROM users WHERE username = :username");
	  $stmt->bindParam(":username", $username, PDO::PARAM_STR);
	  $stmt->execute();
	  $count = $stmt->rowCount();
	  return ($count > 0)? true : false;
	}

	// user resigstration method
	// public function register($screenName,$email,$password)
	// {
	//   $stmt = $this->pdo->prepare("INSERT INTO users(screenName,email,password,profileImage,profileCover) VALUES(:screenName, :email, :password , 'assets/images/defaultprofileimage.png','assets/images/defaultCoverImage.png')");
	//   $stmt->bindParam(":screenName", $screenName, PDO::PARAM_STR);
	//   $stmt->bindParam(":email", $email, PDO::PARAM_STR);
	//   $stmt->bindParam(":password", md5($password), PDO::PARAM_STR);
	//   $stmt->execute();
	//   $user_id = $this->pdo->lastInsertId();

	//   $_SESSION['user_id'] = $user_id;
	//   header("Location: home.php");
	// }

}

?>
