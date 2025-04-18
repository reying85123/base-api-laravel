<?php

namespace App\Console\Commands;

use Modules\PlatformAttribute\Services\PlatformAttributeService;

use Illuminate\Console\Command;

class UpdatePlatformAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:attributes {--initValue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新平台參數';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        PlatformAttributeService::initialData(parseBoolean($this->option('initValue')));

        $this->info('平台參數更新完成');

        return 0;
    }
}
