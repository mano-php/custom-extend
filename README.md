### 中继owl-admin、增加自定义组件

#### 1. docker 开发

```bash
    # 发布配置文件
    php artisan publish:docker
    # 运行服务
    docker-compose up -d
```

#### 2. 使用 示例 `ManoCodeServiceProvider`

```php
<?php

namespace ManoCode\Demo;

use ManoCode\CustomExtend\Extend\ManoCodeServiceProvider;

/**
 * 扩展的服务提供者
 */
class DemoServiceProvider extends ManoCodeServiceProvider
{
    protected $menu = [
        [
            'parent' => '',
            'title' => '演示系统',
            'url' => '/demo',
            'url_type' => '1',
            'icon' => 'ant-design:file-zip-outlined',
        ]
    ];
    protected $dict = [
        [
            'key' => 'filesystem.driver',
            'value' => '文件系统驱动',
            'keys' => [
                [
                    'key' => 'local',
                    'value' => '本地存储'
                ],
                [
                    'key' => 'kodo',
                    'value' => '七牛云kodo'
                ],
                [
                    'key' => 'cos',
                    'value' => '腾讯云COS'
                ],
                [
                    'key' => 'oss',
                    'value' => '阿里云OSS'
                ]
            ]
        ]
    ];
	public function settingForm()
	{
	    return $this->basePage()->body([]);
	}
}
```

#### 3. 定义API路由 在扩展下 创建 src/Http/api_routes.php

```php
<?php

use Illuminate\Support\Facades\Route;
Route::any('/demo',function(){
    return ['msg'=>'HelloWorld'];
});
```