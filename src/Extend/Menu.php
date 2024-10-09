<?php

namespace ManoCode\CustomExtend\Extend;
use Slowlyo\OwlAdmin\Admin;

/**
 *
 */
class Menu extends \Slowlyo\OwlAdmin\Support\Cores\Menu
{
    /**
     * 额外菜单
     *
     * @return array|array[]
     */
    public function extra()
    {
        $extraMenus = [];

        if (Admin::config('admin.auth.enable')) {
            $extraMenus[] = [
                'name'      => 'user_setting',
                'path'      => '/user_setting',
                'component' => 'amis',
                'meta'      => [
                    'hide'         => true,
                    'title'        => admin_trans('admin.user_setting'),
                    'icon'         => 'material-symbols:manage-accounts',
                    'singleLayout' => 'basic',
                ],
            ];
        }

        if (Admin::config('admin.show_development_tools')) {
            if(admin_user()->slug ==='Administrator'){
                $extraMenus = array_merge($extraMenus, $this->devToolMenus());
            }
        }

        return $extraMenus;
    }
}
