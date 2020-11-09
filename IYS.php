<?php
class IYS {
  protected static $_url = "https://api.iys.org.tr/";
  protected static $_token = null;
  protected static $_iyscode;
  protected static $_arr = [];
  protected static $_api = [];
  const ERROR = "api_error";
  const ERROR_DESC = "api_error_desc";
  const REQUEST = "api_request";
  const REQUEST_URL = "api_request_url";
  const REQUEST_HEADER = "api_request_header";
  const RESULT = "api_result";
  const RESULT_CODE = "api_result_code";

  function __construct($username=null, $password=null, $iyscode=null){
    if($username!=null && $password!=null) self::auth($username, $password, $iyscode=null);
  }

  public static function brands(){
    return self::call('integrator/'.self::$_iyscode.'/sps');
  }

  public static function auth($username, $password, $iyscode=null){
    $data = ['username' => $username,
             'password' => $password,
             'grant_type' => "password"];
    $call = self::call('oauth2/token',$data);
    if($call === false) return false;
    self::$_token = $call->access_token;
    self::$_iyscode = $iyscode;
    return true;
  }

  private static function call($action, $data=[]){
    $url = self::$_url.$action;
    self::$_arr[self::REQUEST_URL] = $url;
    self::$_arr[self::REQUEST] = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    $header = [];
    if(!empty($data)){
      $postData = json_encode($data);
      self::$_arr[self::REQUEST] = $postData;
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $header[] = 'Content-Type: application/json';
    }
    if(self::$_token !== null) $header[] = 'Authorization: Bearer '.self::$_token;
    self::$_arr[self::REQUEST_HEADER] = $header;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    $result = curl_exec($ch);
    self::$_arr[self::RESULT_CODE] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    self::$_arr[self::RESULT] = $result;
    curl_close ($ch);
    $result = self::$_arr[self::RESULT_CODE]==200 ? json_decode($result) : false;
    self::$_arr[self::ERROR] = isset($result->error) ? $result->error : false;
    self::$_arr[self::ERROR_DESC] = isset($result->error_description) ? $result->error_description : false;
    return $result;
  }

  public static function get($v=null){
    return isset(self::$_arr[$v]) ? self::$_arr[$v] : false;
  }
}
