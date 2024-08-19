### 中继owl-admin、增加自定义组件

##### 安装本扩展
```bash
composer require mano-code/custom-extend
```

#### 1. 为了方便初学者避免遇到php 扩展以及配置问题，推荐使用docker-compose 作为环境编排。`运行环境需要安装docker`

>  a. 发布配置
```bash
php artisan publish:docker
```
>
>  b. 启动环境
```bash
docker-compose up -d
``` 
> 
>  如需进入docker 环境内 运行环境，调试命令 请使用  即可进入容器内终端
```bash
docker-compose exec api bash
```
> 
>  如需修改访问端口 则在 项目目录下的 docker-compose.yml内修改 services->nginx->ports 的 8000 为你要使用的端口，默认端口为 ：localhost:8000/admin


#### 2. 为了解决字典自动加载，以及扩展更新时 新增的Migration文件。使用\ManoCode\CustomExtend\Extend\ManoCodeServiceProvider作为基础服务提供者即可解决。具体使用方式参考如下

>
>  并且将扩展依赖加入到自己扩展的composer.json->require内。版本要求为：*
>
>  在自己扩展内的 src/XxxxServiceProvider.php内 修改继承类 为 ManoCodeServiceProvider 如下所示。

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
    protected $permission = [
        [
            'name'=>'测试权限',
            'slug'=>'test',
            'method'=>[],// 空则代表ANY
            'path'=>[],// 授权接口
            'parent'=>'',// 父级权限slug字段
        ],
        [
            'name'=>'测试接口',
            'slug'=>'test',
            'method'=>[
                'POST',
                'GET',
            ],// 空则代表ANY
            'path'=>[
                '/test/api*'
            ],// 授权接口
            'parent'=>'test',// 父级权限slug字段
        ],
    ];
}
```

#### 3. 如需定义api 直接提供给外部使用，或者自定义鉴权机制的 则可以在 扩展目录下的src/Http/api_routes.php 定义路由。此文件默认不存在 需要自己创建。

> 新建路由文件 src/Http/api_routes.php
>
> 写入自己的路由 如下所示
> 
> 本路由可以使用 /demo 直接访问，与 `admin_api` 无关

```php
<?php

use Illuminate\Support\Facades\Route;
Route::any('/demo',function(){
    return ['msg'=>'HelloWorld'];
});
Route::get('/test',[ManoCode\FileSystem\Http\Controllers\DemoApiController::class,'test']);
Route::get('/test1',[ManoCode\FileSystem\Http\Controllers\DemoApiController::class,'test1']);
```


#### 4. API逻辑交互 也提供了 json 返回的工具类 

> 新建控制器，use ApiResponseTrait;即可通过$this 调用接口返回工具

```php
<?php

namespace ManoCode\FileSystem\Http\Controllers;

use Illuminate\Routing\Controller;
use ManoCode\CustomExtend\Traits\ApiResponseTrait;

/**
 *
 */
class DemoApiController extends Controller
{
    use ApiResponseTrait;
    public function test()
    {
        return $this->success('测试成功',[
            'list'=>[]
        ]);
    }
    public function test1()
    {
        return $this->fail('错误',[
            'list'=>[]
        ]);
    }
}
```



#### 5. 公共函数库

> 函数1 function setOptionsColor(string $name, array $options = []): string
> 
```php
/**
 * 根据指定选项生成一个动态的三元表达式字符串。
 *
 * @param string $name 变量名称，将用于表达式中作为条件变量。
 * @param array $options 选项数组，每个选项包含两个键：
 *                       - 'value': 需要与变量匹配的值
 *                       - 'color': 匹配成功时返回的颜色或状态值。颜色格式可以是 'active', 'inactive', 'error', 'success', 'processing', 'warning' 或具体色值。
 *                       例如：
 *                       [
 *                           ['value' => 'enable', 'color' => 'success'],
 *                           ['value' => 'disable', 'color' => 'error'],
 *                       ]
 * @return string 返回构建好的三元表达式字符串，格式为：
 *                 ${name=="value1"?"color1":(name=="value2"?"color2":"default")}
 *                 其中，'default' 是当所有条件不匹配时的默认值。
 */
function setOptionsColor(string $name, array $options = []): string

```

##### 使用示例

> 一般用于 状态字段的颜色选择，只需要提供字段名 颜色对照表即可使用
> 

```php
amis()->SelectControl('state', '状态')->options([
    [
        'label' => '启用',
        'value' => 'enable'
    ],
    [
        'label' => '禁用',
        'value' => 'disable'
    ],
])->type('tag')->set('color',setOptionsColor('state',[
    [
        'value'=>'1',
        'color'=>'active'
    ],
    [
        'value'=>'2',
        'color'=>'inactive'
    ],
    [
        'value'=>'enable',
        'color'=>'success'
    ],
    [
        'value'=>'disable',
        'color'=>'error'
    ],
])),
```
