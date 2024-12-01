<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\model;

class AdminRoles extends Model
{

    protected  $table = 'admin_roles';

    protected  $primaryKey = 'id';

    protected  $fillable = ['id', 'name', 'slug', 'created_at', 'updated_at'];

    protected  $casts = ['id' => 'integer'];

}