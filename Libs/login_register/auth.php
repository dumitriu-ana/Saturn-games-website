<?php
namespace auth;
class user_log_reg
{
  private $db_obj;
  private $user_table = "";

  private $email_head = "";
  private $username_head = "";
  private $password_head = "";

  public $secure_key = "Saphir987";
  public $secret_iv = "Steve21344";

  function __construct(
   $db_obj,
   $table = "users",
   $email = "email",
   $username = "username",
   $password = "password"
   )
  {
    $this->db_obj = $db_obj;

    $this->user_table = $table;
    $this->email_head = $email;
    $this->username_head = $username;
    $this->password_head = $password;

    if(isset($_COOKIE['user'])){
      $_SESSION['user'] = $_COOKIE['user'];
    }

  }

  function simple_crypt( $string, $action = 'e' ) {
    $secret_key = $this->secure_key;
    $secret_iv = $this->secret_iv;

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }

    return $output;
  }

  function login(
    $username="",
    $email="",
    $password,
    $head_for_session = "user",
    $rememberMe = false
  ){
    $search_q = $this->db_obj
    ->select($this->user_table);

    $hadUsername = false;

    if(trim($username) != ""){
      $search_q = $search_q
      ->where($this->username_head, $username)
      ->where($this->password_head, $password);

      $hadUsername = true;
    }

    if(trim($email) != ""){
      if($hadUsername==true){
        $search_q = $search_q
        ->orWhere($this->email_head, $email);
      }else{
        $search_q = $search_q
        ->where($this->email_head, $email);
      }

      $search_q = $search_q
      ->where($this->password_head, $password);
    }

    $userResult = $search_q->get();

    if($userResult->num_rows == 0){
      return 0;
    }

    $user_string = $userResult->fetch_assoc()[$this->username_head];
    $_SESSION[$head_for_session] = $user_string;

    if($rememberMe==true){
      setcookie("user", $user_string, time() + (86400 * 30 * 12 * 10), "/");
    }

    return 1;
  }

  function register(array $reg_data){
    $db_obj = $GLOBALS['db_obj'];
    return $this->db_obj->insert($this->user_table, $reg_data);
  }

  function logout(
    $head_for_session = 'user'
  ){
    $_SESSION[$head_for_session] = "";
    unset($_SESSION[$head_for_session]);

    unset($_COOKIE[$head_for_session]);
    setcookie($head_for_session, null, -1, '/');
  }

}
?>
