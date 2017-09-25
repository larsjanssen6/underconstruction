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
    protected $description = 'Set here the underconstruction code';

    protected $hasher;

    /**
     * Create a new command instance.
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
        file_put_contents(__DIR__ . '/temp.txt', $this->hasher->make($this->argument('code')));
        $this->info('jow');
    }
}