<?php

namespace Tests\Feature;

use App\Http\Controllers\ServerController;
use App\Services\RconService;
use Illuminate\Support\Facades\Http;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class ServerA2sQueryTest extends TestCase
{
    /**
     * Invoke the private ServerController::getServerDetails() with a mocked
     * RconService so we can exercise the A2S-first / Steam-API-fallback logic
     * without touching the network.
     */
    private function getServerDetails(RconService $rcon, string $ip = '31.57.151.200', string $port = '27015')
    {
        $method = new ReflectionMethod(ServerController::class, 'getServerDetails');
        $method->setAccessible(true);

        return $method->invoke(new ServerController(), $ip, $port, $rcon);
    }

    public function test_uses_a2s_result_when_the_query_succeeds(): void
    {
        Http::fake(); // any Steam Web API call would be a failure of the A2S path

        $rcon = Mockery::mock(RconService::class);
        $rcon->shouldReceive('connect')->once();
        $rcon->shouldReceive('getInfo')->once()->andReturn([
            'HostName' => 'Test Server',
            'Map' => 'de_mirage',
            'Players' => 13,
            'MaxPlayers' => 32,
        ]);
        $rcon->shouldReceive('disconnect')->once();

        $details = $this->getServerDetails($rcon);

        $this->assertSame(13, $details['players']);
        $this->assertSame(32, $details['max_players']);
        $this->assertSame('de_mirage', $details['map']);
        Http::assertNothingSent();
    }

    public function test_falls_back_to_steam_web_api_when_a2s_fails(): void
    {
        Http::fake([
            'api.steampowered.com/*' => Http::response([
                'response' => ['servers' => [[
                    'players' => 7,
                    'max_players' => 24,
                    'map' => 'de_dust2',
                ]]],
            ], 200),
        ]);

        $rcon = Mockery::mock(RconService::class);
        $rcon->shouldReceive('connect')->once()->andThrow(new \RuntimeException('A2S timeout'));
        $rcon->shouldReceive('disconnect'); // invoked in the catch block

        $details = $this->getServerDetails($rcon, '1.2.3.4');

        $this->assertSame(7, $details['players']);
        $this->assertSame(24, $details['max_players']);
        $this->assertSame('de_dust2', $details['map']);
        Http::assertSentCount(1);
    }

    public function test_returns_null_when_both_a2s_and_steam_api_fail(): void
    {
        Http::fake([
            'api.steampowered.com/*' => Http::response('upstream error', 500),
        ]);

        $rcon = Mockery::mock(RconService::class);
        $rcon->shouldReceive('connect')->once()->andThrow(new \RuntimeException('A2S timeout'));
        $rcon->shouldReceive('disconnect');

        $this->assertNull($this->getServerDetails($rcon, '1.2.3.4'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
