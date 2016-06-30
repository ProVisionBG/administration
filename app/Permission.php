<?php
namespace ProVision\Administration;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {
    protected $table = 'permission_role';
}