<?php
class IYS {
  protected static $_url = "https://api.sandbox.iys.org.tr/";
  protected static $_token;
  protected static $_arr = [];
  protected static $_api = [];
  const ERROR = "api_error";
  const ERROR_DESC = "api_error_desc";
  const REQUEST = "api_request";
  const REQUEST_URL = "api_request_url";
  const RESULT = "api_result";
  const RESULT_CODE = "api_result_code";

  function __construct(){
  }

  public static function brands($iyscode = null){
    $data = ['iysCode' => $iyscode];
    return self::call('sps/brands',$data);
  }

  public static function auth($username, $password){
    $data = [
      'username' => $username,
      'password' => $password,
      'grant_type' => "password",
    ];
    return self::call('oauth2/token',$data);
  }

  private static function call($action, $data){
    $url = self::$_url.$action;
    self::$_arr[self::REQUEST_URL] = $url;
    self::$_arr[self::REQUEST] = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    if(!empty($data)){
      $postData = json_encode($data);
      self::$_arr[self::REQUEST] = $postData;
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    $result = curl_exec($ch);
    self::$_arr[self::RESULT_CODE] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    self::$_arr[self::RESULT] = $result;
    curl_close ($ch);
    $result = self::$_arr['api_result_code']==200 ? json_decode($result) : false;
    self::$_arr[self::ERROR] = isset($result->error) ? $result->error : false;
    self::$_arr[self::ERROR_DESC] = isset($result->error_description) ? $result->error_description : false;
    return $result;
  }

  public static function get($v=null){
    return isset(self::$_arr[$v]) ? self::$_arr[$v] : false;
  }
}
