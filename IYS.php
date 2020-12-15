<?php
class IYS {
  protected static $_url = "https://api.iys.org.tr/";
  protected static $_token = null;
  protected static $_iyscode;
  protected static $_arr = [];
  protected static $_api = [];
  const ERROR = "api_error";
  const ERROR_MSG = "api_error_msg";
  const REQUEST = "api_request";
  const REQUEST_URL = "api_request_url";
  const REQUEST_HEADER = "api_request_header";
  const RESULT = "api_result";
  const RESULT_CODE = "api_result_code";

  function __construct($username=null, $password=null, $iyscode=null){
    if($username!=null && $password!=null) self::auth($username, $password, $iyscode=null);
  }

  public static function auth($username, $password, $iyscode=null){
    if(self::$_token !== null) return true;
    $data = ['username' => $username,
             'password' => $password,
             'grant_type' => "password"];
    $call = self::call('oauth2/token',$data);
    if($call === false) return false;
    self::$_token = $call->access_token;
    self::$_iyscode = $iyscode;
    return true;
  }

  public static function brand($iysCode=''){
    if(!empty($iysCode)) $iysCode = "/$iysCode";
    $call = self::call("integrator/".self::$_iyscode."/sps$iysCode");
    return $call !== false ? (empty($iysCode) ? $call->list : $call->list[0]) : false;
  }

  public static function consent($iysCode, $brandCode, $recipient, $type=null, $source=null, $status=null, $consentDate=null, $recipientType=null, $retailerCode=null){
    if(is_array($recipient) || is_object($recipient)) return self::call("sps/$iysCode/brands/$brandCode/consents/request", $recipient);
    $data = new stdClass();
    $data->recipient = $recipient;
    $data->type = $type;
    $data->source = $source;
    $data->status = $status;
    $data->consentDate = $consentDate;
    if($recipientType !== null) $data->recipientType = $recipientType;
    if($retailerCode !== null) $data->retailerCode = $retailerCode;
    return self::call("sps/$iysCode/brands/$brandCode/consents", $data);
  }

  public static function result($iysCode, $brandCode, $requestid){
    return self::call("sps/$iysCode/brands/$brandCode/consents/request/$requestid");
  }

  public static function status($iysCode, $brandCode, $recipient, $type=null, $recipientType=null){
    $data = new stdClass();
    $data->recipient = $recipient;
    $data->type = $type;
    $data->recipientType = $recipientType;
    return self::call("sps/$iysCode/brands/$brandCode/consents/status", $data);
  }

  public static function changes($iysCode, $brandCode){
    return self::call("sps/$iysCode/brands/$brandCode/consents/changes");
  }

  public static function agreementFile($iysCode, $brandCode, $reportDate){
    return self::call("sps/$iysCode/brands/$brandCode/changes/$reportDate/file");
  }

  private static function call($action, $data=[]){
    self::$_arr[self::ERROR] = false;
    self::$_arr[self::ERROR_MSG] = false;
    $url = self::$_url.$action;
    self::$_arr[self::REQUEST_URL] = $url;
    self::$_arr[self::REQUEST] = null;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    $header = [];
    if(!empty($data) && ((array)$data) > 0){
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
    self::$_arr[self::RESULT] = curl_exec($ch);
    $result = json_decode(self::$_arr[self::RESULT]);
    if(json_last_error() != JSON_ERROR_NONE) $result = self::$_arr[self::RESULT];
    self::$_arr[self::RESULT_CODE] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    self::$_arr[self::ERROR] = isset($result->errors) ? $result->errors[0]->code : false;
    self::$_arr[self::ERROR_MSG] = isset($result->errors) ? $result->errors[0]->message : false;
    $result_success = [200,202,204];
    return in_array(self::$_arr[self::RESULT_CODE],$result_success) ? $result : false;
  }

  public static function get($v=null){
    return isset(self::$_arr[$v]) ? self::$_arr[$v] : false;
  }
}
