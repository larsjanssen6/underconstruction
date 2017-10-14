<?php

namespace LarsJanssen\UnderConstruction\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher;

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
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Create a new command instance.
     *
     * @param Hasher $hasher
     */
    public function __construct(Hasher $hasher)
    {
        parent::__construct();
        $this->hasher = $hasher;
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
            $hash = $this->hasher->make($code);
            $this->setHashInEnvironmentFile($hash);
            $this->info(sprintf('Code: "%s" is set successfully.', $code));
        } else {
            $this->error('Wrong input. Code should contain 4 numbers.');
        }
    }

    protected function setHashInEnvironmentFile($hash)
    {
        $envPath = $this->laravel->environmentFilePath();
        $envContent = file_get_contents($envPath);
        $regex = '/^UNDER_CONSTRUCTION_HASH=.*$/';
        $newLine = sprintf('UNDER_CONSTRUCTION_HASH=%s', $hash);

        if (preg_match($regex, $envContent)) {
            $envContent = preg_replace_array($regex, $newLine, $envContent);
        } else {
            $envContent .= "\n".$newLine."\n";
        }

        file_put_contents($envPath, $envContent);
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
