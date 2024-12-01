<?php
declare(strict_types=1);

namespace plugin\webmanAdmin\app\model;

class AdminMenus extends Model
{

    protected  $table = 'admin_menus';

    protected  $primaryKey = 'id';

    protected  $fillable = ['id', 'type', 'parent_id', 'title', 'slug', 'icon', 'uri', 'sort', 'created_at', 'updated_at'];

    protected  $casts = ['id' => 'integer', 'type' => 'integer', 'parent_id' => 'integer'];

    protected  $attributes = [
        'parent_id' => 0,
        'sort' => 1
    ];


    public static function getParentMenuIds($id)
    {
        $result = [$id];
        $parentId = $id;
        while ($parentId != 0) {
            $parent = self::where('id', $parentId)->first();
            $parentId = $parent['parent_id'];
            if ($parent && $parent['parent_id'] != 0) {
                $result[] = $parent['parent_id'];
            }
        }

        return $result;
    }

    public static function getMenuIdBySlug($slug){
        $menuId =  self::where('slug', $slug)->value('id');
        if($menuId){
            return $menuId;
        }
        return 0;
    }

}