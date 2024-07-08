<?php

declare(strict_types=1);

namespace ManoCode\CustomExtend;

use Illuminate\Support\ServiceProvider;
use ManoCode\CustomExtend\Command\PublishDockerConfigCommand;

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
    }
}
