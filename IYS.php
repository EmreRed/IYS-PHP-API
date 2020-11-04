<?php
class IYS {
  protected $api_url = "https://api.sandbox.iys.org.tr/sps/";
  private $_arr = [];
  const ERROR = "api_error";
  const REQUEST = "api_request";
  const RESULT = "api_result";
  const RESULT_CODE = "api_result_code";

  function __construct(){

  }

  function brands($iyscode = null){
    $data = ['iysCode' => $iyscode];
    $call = $this->call('brands',$data);
  }

  private function auth(){

  }

  private function call($action, $data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    if(!empty($data)){
      $postData = json_encode($data);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, []);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    $this->$_arr['api_result_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $result = curl_exec($ch);
    curl_close ($ch);
    return $this->$_arr['api_result_code']==200 ? json_decode($result) : false;
  }

  function get($v=null){
    switch($v){
      case '':
        break;
      default:
      break;
    }
  }
}
