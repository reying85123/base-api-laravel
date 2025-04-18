<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitialProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init
                            {--quick}
                            {--generate=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '專案初始化';

    /**
     * 初始化環境變數
     *
     * @var array
     */
    protected $env = [];

    /**
     * 初始化設定檔
     *
     * @var array
     */
    protected $config = [];

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
        //快速初始化，略過以下流程
        if($this->option('quick') === false){
            $this->askEnv();
            $this->updateEnv($this->env);
            
            $generate = array_map('strtolower', $this->option('generate'));
            
            if(in_array('key', $generate) || $this->confirm('是否初始化APP KEY?', false)){
                $this->callSilent('key:generate');
            }
            
            if(in_array('jwt', $generate) || $this->confirm('是否初始化JWT SECRET?', false)){
                $this->callSilent('jwt:secret');
            }
            
            config($this->config);
        }

        $startTime = microtime(1);

        //資料庫建置
        $this->migrate();

        //功能菜單、權限建置
        $this->menu();

        $this->info('------------------------------------------');
        $this->comment("總執行時間(s): \t" . (microtime(1) - $startTime));

        return 0;
    }

    protected function askEnv()
    {
        $this->config['app.name'] = $this->env['APP_NAME'] = $this->ask('請輸入專案名稱', 'Laravel');
        $this->config['app.url'] = $this->env['APP_URL'] = $this->ask('請輸入專案URL', 'https://localhost');

        $this->config['database.default'] = $this->env['DB_CONNECTION'] = $this->ask('請輸入DB_CONNECTION', 'mysql');

        $databaseConnectPrefix = "database.connections.{$this->config['database.default']}";
        $this->config["{$databaseConnectPrefix}.host"] = $this->env['DB_HOST'] = $this->ask('請輸入DB_HOST', '127.0.0.1');
        $this->config["{$databaseConnectPrefix}.port"] = $this->env['DB_PORT'] = $this->ask('請輸入DB_PORT', '3306');
        $this->config["{$databaseConnectPrefix}.database"] = $this->env['DB_DATABASE'] = $this->ask('請輸入DB_DATABASE', 'laravel');
        $this->config["{$databaseConnectPrefix}.username"] = $this->env['DB_USERNAME'] = $this->ask('請輸入DB_USERNAME', 'root');
        $this->config["{$databaseConnectPrefix}.password"] = $this->env['DB_PASSWORD'] = $this->ask('請輸入DB_PASSWORD', '');
    }

    protected function migrate()
    {
        $this->info("Migrate: \t資料庫建置開始");
        $this->callSilent('migrate:fresh');
        $this->info("Migrate: \t資料庫建置完成");
    }

    protected function menu()
    {
        $this->callSilent('update:menus', ['--role' => null, '--permission' => null]);
        $this->info("Menu: \t\t菜單功能權限初始化完成");
    }

    protected function updateEnv($data = array())
    {
        if (!count($data)) {
            return;
        }

        $pattern = '/([^\=]*)\=[^\n]*/';

        $envFile = base_path() . '/.env';

        try {
            $lines = file($envFile);
        } catch (\Exception $e) {
            $this->error('env檔案不存在，請先建立.env');
            exit;
        }

        $newLines = [];
        foreach ($lines as $line) {
            preg_match($pattern, $line, $matches);

            if (!count($matches)) {
                $newLines[] = $line;
                continue;
            }

            if (!key_exists(trim($matches[1]), $data)) {
                $newLines[] = $line;
                continue;
            }

            $line = trim($matches[1]) . "={$data[trim($matches[1])]}\n";
            $newLines[] = $line;
        }

        $newContent = implode('', $newLines);
        file_put_contents($envFile, $newContent);
    }
}
