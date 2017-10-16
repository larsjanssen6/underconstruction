<?php

namespace LarsJanssen\UnderConstruction\Test\Unit;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Hashing\BcryptHasher;
use LarsJanssen\UnderConstruction\Commands\SetCodeCommand;
use LarsJanssen\UnderConstruction\Test\TestCase;
use Mockery;

class SetCodeCommandTest extends TestCase
{

    /**
     * @test
     * @dataProvider invalidCodeProvider
     *
     * @param $code
     */
    public function it_warns_of_invalid_code($code)
    {
        $command = Mockery::mock('LarsJanssen\UnderConstruction\Commands\SetCodeCommand[error]', [new BcryptHasher(), new Filesystem()]);

        $command->shouldReceive('error')->once()->with('Wrong input. Code should contain 4 numbers.');

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('code:set', ['code' => $code]);
    }

    /**
     * @test
     * @dataProvider environmentFileProvider
     *
     * @param $existingEnvFileContent
     * @param $contentsToNotOverwrite
     */
    public function it_adds_hash_to_environment_file($existingEnvFileContent, $contentsToNotOverwrite)
    {
        $hasher = Mockery::mock(Hasher::class);
        $filesystem = Mockery::mock(Filesystem::class);

        $hasher->shouldReceive('make')->once()->with('1234')->andReturn('ImMrMeeseeksLookAtMe');

        $expectedEnvFileContent = <<<EOP
$contentsToNotOverwrite
UNDER_CONSTRUCTION_HASH=ImMrMeeseeksLookAtMe

EOP;

        $filesystem
            ->shouldReceive('put')->once()->withArgs([Mockery::type('string'), $expectedEnvFileContent])
            ->shouldReceive('get')->andReturn($existingEnvFileContent);

        $command = new SetCodeCommand($hasher, $filesystem);

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('code:set', ['code' => '1234']);
    }

    /**
     * @return array
     */
    public function invalidCodeProvider()
    {
        return [
            ['1'],
            ['12'],
            ['123'],
            ['12345'],
            ['1 23'],
            ['zzzz'],
            ['a']
        ];
    }

    public function environmentFileProvider()
    {
        return [
            ['', ''],
            ['foo=bar', 'foo=bar'],
            ["foo=bar\nUNDER_CONSTRUCTION_HASH=fakehash\n", "foo=bar"]
        ];
    }
}
