<?php

namespace App\Repositories;

use App\Services\LogActivityService;
use App\Traits\QueryHelper;

class Repository
{
  
    protected $logActivityService;
    public function __construct(){
        $this->logActivityService = new LogActivityService();
    }

}
