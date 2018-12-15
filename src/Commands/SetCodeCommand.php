<?php

namespace LarsJanssen\UnderConstruction\Commands;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Filesystem\Filesystem;

class SetCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:set {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set here the under construction code';

    /**
     * Configurations from config file.
     *
     * @var array
     */
    protected $config;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Repository $config
     * @param Filesystem $filesystem
     */
    public function __construct(Repository $config, Filesystem $filesystem)
    {
        parent::__construct();
        $this->config = $config->get('under-construction');
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $code = $this->argument('code');

        if ($this->validate($code)) {
            $hash = Hash::make($code);
            $this->setHashInEnvironmentFile($hash);
            $this->info(sprintf('Code: "%s" is set successfully.', $code));
        } else {
            $this->error(sprintf('Wrong input. Code should contain %s numbers (see config file), and can\'t be greater then 6.', $this->config['total_digits']));
        }
    }

    /**
     * Set the hash in .env file.
     *
     * @param $hash
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function setHashInEnvironmentFile($hash)
    {
        $envPath = $this->laravel->environmentFilePath();
        $envContent = $this->filesystem->get($envPath);

        $regex = '/UNDER_CONSTRUCTION_HASH=\S+/';

        if (preg_match($regex, $envContent)) {
            $hash = str_replace(['\\', '$'], ['', '\$'], $hash);
            $envContent = preg_replace($regex, $this->newLine($hash), $envContent);
        } else {
            $envContent .= "\n".$this->newLine($hash)."\n";
        }

        $this->filesystem->put($envPath, $envContent);
    }

    /**
     * @param $hash
     * @return string
     */
    private function newLine($hash)
    {
        return "UNDER_CONSTRUCTION_HASH=$hash";
    }

    /**
     * Check if given code is valid.
     *
     * @param $code
     *
     * @return bool
     */
    public function validate($code): bool
    {
        $codeLength = strlen($code);

        return ctype_digit($code) && $codeLength == $this->config['total_digits'] && strlen($code) <= 6;
    }
}
