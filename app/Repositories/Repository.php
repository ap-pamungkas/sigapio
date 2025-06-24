<?php

namespace App\Repositories;

use App\Services\LogActivityService;

class Repository
{
  
    protected $logActivityService;
    public function __construct(){
        $this->logActivityService = new LogActivityService();
    }

}
