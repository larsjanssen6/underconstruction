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
     * @param $hash
     */
    protected function setHashInEnvironmentFile($hash)
    {
        $envPath = $this->laravel->environmentFilePath();
        $envContent = $this->filesystem->get($envPath);

        $regex = '/UNDER_CONSTRUCTION_HASH=\S+/';
        $newLine = "UNDER_CONSTRUCTION_HASH=$hash";

        if (preg_match($regex, $envContent, $matches)) {
            $envContent = str_replace($matches[0], $newLine, $envContent);
        } else {
            $envContent .= "\n".$newLine."\n";
        }

        $this->filesystem->put($envPath, $envContent);
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
