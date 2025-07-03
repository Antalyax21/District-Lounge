<?php

namespace Codez\DistrictLounge\Models;
use Codez\DistrictLounge\Core\DAO;

abstract class BaseModel
{
    protected DAO $dao;
    
    public function __construct()
    {
        $this->dao = new DAO();
    }
}
