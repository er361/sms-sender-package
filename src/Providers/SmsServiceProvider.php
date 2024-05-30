<?php

namespace Sf7kmmr\SmsService\Providers;


use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Sf7kmmr\SmsService\Beeline\BeelineService;
use Sf7kmmr\SmsService\Jobs\SendSmsJob;
use Sf7kmmr\SmsService\SmsInterface;


class SmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmsInterface::class, function ($app) {
            $login = env('BEELINE_LOGIN');
            $password = env('BEELINE_PASSWORD');
            $host = env('BEELINE_HOST');

            return new BeelineService($login, $password, $host);
        });
    }

    public function boot(): void
    {
        $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations');
        $this->loadMigrationsFrom($path);
    }
}
