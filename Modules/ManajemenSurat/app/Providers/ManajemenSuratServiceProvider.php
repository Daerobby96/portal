<?php

namespace Modules\ManajemenSurat\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\ManajemenSurat\Services\NomorSuratService;

class ManajemenSuratServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'ManajemenSurat';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'manamejensurat';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        parent::register();

        // Register NomorSuratService as singleton
        $this->app->singleton(NomorSuratService::class, function ($app) {
            return new NomorSuratService();
        });
    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        parent::boot();

        // Register view namespace
        $this->loadViewsFrom(module_path($this->name, 'resources/views'), 'manajemen-surat');
    }

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
