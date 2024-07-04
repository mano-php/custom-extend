<?php

declare(strict_types=1);

namespace ManoCode\CustomExtend;

use Illuminate\Support\ServiceProvider;
use ManoCode\CustomExtend\Command\PublishDockerConfigCommand;

/**
 *
 */
class CustomExtendServiceProvider extends ServiceProvider
{
    protected array $command = [
        PublishDockerConfigCommand::class
    ];
}