<?php
      unset($_SESSION);
      session_start();
      session_destroy();
      session_start();
      ob_start();
    $error = "";
    include "../database/database.php";

    if(isset($_REQUEST['login']))
    {
        if(!empty($_POST['username']) && !empty($_POST['password']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hashpassword = md5(sha1($password));

            $sql = "SELECT * FROM `dnusers` WHERE `USERNAME` = '$username' AND `PASSWORD` = '$hashpassword'";
            $query = mysqli_query($dbconnect, $sql);
            $kayit = mysqli_fetch_assoc($query);
            $id = $kayit['ID'];
            if($kayit['USERNAME'] == $username && $kayit['PASSWORD'] == $hashpassword)
            {
                if($kayit['WORD_AUTHORITY'] == 1 || $kayit['ADMIN'] == 1)
                {
                    if(isset($_POST['remember']))
                    {
                      $ip = $_SERVER["REMOTE_ADDR"];
                      $sql = "INSERT INTO `dnlog` (`NAME`, `DATE`, `IP`, `USER_ID`) VALUES ('Kullanıcı Girişi.', CURRENT_TIMESTAMP, '$ip', $id);";
                      mysqli_query($dbconnect, $sql);
                      setcookie("username", $username, time() + 3600*24);
                      setcookie("password", $password, time() + 3600*24);

                      $_SESSION["login"] = "active";
                      $_SESSION["id"] = $id;
                      $_SESSION["name"] = $kayit['NAME'];
                      $_SESSION["username"] = $username;
                      $_SESSION["password"] = $hashpassword;
                      $_SESSION["admin"] = $kayit['ADMIN'];
                      $_SESSION["last_login_timestamp"] = time();
                      header('location: ../index.php');
                      exit;
                    }
                    else
                    {
                      $ip = $_SERVER["REMOTE_ADDR"];
                      $sql = "INSERT INTO `dnlog` (`NAME`, `DATE`, `IP`, `USER_ID`) VALUES ('Kullanıcı Girişi.', CURRENT_TIMESTAMP, '$ip', $id);";
                      mysqli_query($dbconnect, $sql);
                      
                      $_SESSION["login"] = "active";
                      $_SESSION["id"] = $id;
                      $_SESSION["name"] = $kayit['NAME'];
                      $_SESSION["username"] = $username;
                      $_SESSION["password"] = $hashpassword;
                      $_SESSION["admin"] = $kayit['ADMIN'];
                      $_SESSION["last_login_timestamp"] = time();
                      header('location: ../index.php');
                      exit;
                    }
                }
                else
                {
                  $error = <<<error
                    <h4 class='card-title'>Hesabınız aktif değildir!</h4>
                  error;
                }
            }
            else
            {
              $error = <<<error
                <h4 class='card-title'>Kullanıcı adınız veya şifreniz hatalı!</h4>
              error;
            }
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <title>Giriş Yap</title>
    
  <link rel="icon" href="dist/img/logo.svg" type="image/x-icon" />

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/ingweb.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body>

  <div class="login-container">
    <div class="body">
      <div class="login-title">
        <h1>Sign in</h1>
      </div>
      <div class="login-box">
        <form method="POST">
          <div class="form-group">
            <input type="text" name="username" class="form-control" value="<?php if(!empty($_COOKIE['username'])) { echo $_COOKIE['username']; } ?>" placeholder="Username" required>
          </div>
          <div class="form-group">
            <input type="password" name="password" id="pass" ondblclick="showPass()" class="form-control" value="<?php if(!empty($_COOKIE['password'])) { echo $_COOKIE['password']; } ?>" placeholder="Password" required>
          </div>
            <span class="fas fa-lock" onclick="showPass()"></span>
          <div class="form-group">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript">
  function showPass() {
    var login = document.getElementById('pass');
    if(login.type == "password") {
        login.type = "text";
    } else {
        login.type = "password";
    }
  }
</script>

</body>
</html>
