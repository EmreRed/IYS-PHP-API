<?php

namespace EmreRed;

class IYS {
  private static $inc = [];
  const ERROR = "api_error",
        ERROR_MSG = "api_error_msg",
        REQUEST = "api_request",
        REQUEST_URL = "api_request_url",
        REQUEST_HEADER = "api_request_header",
        RESULT = "api_result",
        RESULT_CODE = "api_result_code";

  public static function integrator($username=null,$password=null, $iyscode=null){
    if(!(self::$inc['integrator'] ?? false)){
      require __DIR__.'/src/integrator.php';
      self::$inc['integrator'] = true;
    }
    return new IYS\Integrator($username,$password, $iyscode);
  }

  public static function ahs($username=null,$password=null){
    if(!(self::$inc['ahs'] ?? false)){
      require __DIR__.'/src/ahs.php';
      self::$inc['ahs'] = true;
    }
    return new IYS\Ahs($username, $password);
  }
}
