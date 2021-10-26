<?php

namespace EmreRed\IYS;

class Ahs {
  protected static $_url = "https://ahsapi.iys.org.tr/";
  protected static $_token = null;
  protected static $_arr = [];
  protected static $_api = [];

  function __construct($username=null, $password=null){
    self::$_token = base64_encode("$username:$password");
  }

  public static function brands($tin){
    return self::call("brands/$tin");
  }

  public static function rejecteds($brandCode, $recipientType, $data, $type = "MESAJ"){
    $data = array_map(function($val){ return substr($val,0,1) !== '+' ? "+$val" : $val; }, $data);
    $json = self::call("brands/$brandCode/consents/$recipientType/rejected/$type", ['recipients'=> $data]);
    return array_map(function($v){ return substr($v,1); }, $json->list);
  }

  private static function call($action, $data=[]){
    self::$_arr[\EmreRed\IYS::ERROR] = false;
    self::$_arr[\EmreRed\IYS::ERROR_MSG] = false;
    $url = self::$_url.$action;
    self::$_arr[\EmreRed\IYS::REQUEST_URL] = $url;
    self::$_arr[\EmreRed\IYS::REQUEST] = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    $header = [];
    if(!empty($data) && ((array)$data) > 0){
      $postData = json_encode($data);
      self::$_arr[\EmreRed\IYS::REQUEST] = $postData;
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
      $header[] = 'Content-Type: application/json';
    }
    if(self::$_token !== null) $header[] = 'Authorization: Basic '.self::$_token;
    self::$_arr[\EmreRed\IYS::REQUEST_HEADER] = $header;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    self::$_arr[\EmreRed\IYS::RESULT] = curl_exec($ch);
    $result = json_decode(self::$_arr[\EmreRed\IYS::RESULT]);
    if(json_last_error() != JSON_ERROR_NONE) $result = self::$_arr[\EmreRed\IYS::RESULT];
    self::$_arr[\EmreRed\IYS::RESULT_CODE] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    self::$_arr[\EmreRed\IYS::ERROR] = isset($result->errors) ? $result->errors[0]->code : false;
    self::$_arr[\EmreRed\IYS::ERROR_MSG] = isset($result->errors) ? $result->errors[0]->message : false;
    $result_success = [200,202,204];
    return $result;
  }

  public static function get($v=null){
    return isset(self::$_arr[$v]) ? self::$_arr[$v] : false;
  }
}
