<?php

declare(strict_types=1);

namespace ManoCode\CustomExtend;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ManoCode\CustomExtend\Command\PublishDockerConfigCommand;
use ManoCode\CustomExtend\Traits\AssetTraits;
use Slowlyo\OwlAdmin\Controllers\AdminController;
use ManoCode\CustomExtend\Extend\Menu;
use Slowlyo\OwlAdmin\Controllers\AdminPermissionController;

/**
 * 中间层服务提供者
 */
class CustomExtendServiceProvider extends ServiceProvider
{
    protected array $commands = [
        PublishDockerConfigCommand::class
    ];
    public function boot(): void
    {
        $this->commands($this->commands);
        // 覆写权限控制器
        $this->app->bind(AdminPermissionController::class,\ManoCode\CustomExtend\Extend\AdminPermissionController::class);
        // 覆写基础控制器（用于处理文件上传问题）
        $this->app->bind(AdminController::class,\ManoCode\CustomExtend\Extend\AdminPermissionController::class);
        // 开发者工具问题
        $this->app->bind('admin.menu',Menu::class);
        // 主题注入
        $this->app->singleton('admin.asset',AssetTraits::class);
        try{
            Schema::table('admin_permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('admin_permissions', 'extension')) {
                    $table->string('extension')->nullable();
                }
            });
            Schema::table('admin_dict', function (Blueprint $table) {
                if (!Schema::hasColumn('admin_dict', 'extension')) {
                    $table->string('extension')->nullable();
                }
            });
        }catch (\Throwable $throwable){

        }
    }
}
