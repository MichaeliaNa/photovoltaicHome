<?php

namespace App\Models;

class M3Result {

  public $status;//0->成功
  public $message;

  public function toJson()
  {
    return json_encode($this, JSON_UNESCAPED_UNICODE);
  }

}
