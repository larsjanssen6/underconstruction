<?php

namespace LarsJanssen\UnderConstruction\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;

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
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $code = $this->argument('code');

        if ($this->validate($code)) {
            $hash = Hash::make($code);
            $this->setHashInEnvironmentFile($hash);
            $this->info(sprintf('Code: "%s" is set successfully.', $code));
        } else {
            $this->error('Wrong input. Code should contain 4 numbers.');
        }
    }

    /**
     * Set the hash in .env file.
     *
     * @param $hash
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
        return ctype_digit($code) && strlen($code) == 4;
    }
}
