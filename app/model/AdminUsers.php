<?php
namespace plugin\webmanAdmin\app\model;

class AdminUsers extends Model
{

    protected  $table = 'admin_users';


    protected  $primaryKey = 'id';

    protected  $fillable = ['id', 'username', 'password', 'salt', 'avatar', 'created_at', 'updated_at'];

    protected  $casts = ['id' => 'integer'];

}