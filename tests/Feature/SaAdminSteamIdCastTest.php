<?php

namespace Tests\Feature;

use App\Models\SaAdmin;
use Tests\TestCase;

class SaAdminSteamIdCastTest extends TestCase
{
    /**
     * A representative 17-digit SteamID64. This exceeds JavaScript's
     * Number.MAX_SAFE_INTEGER (2^53), so if it reaches the browser as a bare
     * JSON number it loses precision and produces a broken Steam profile link.
     */
    private const STEAM_ID64 = '76561198000000123';

    /**
     * Regression test for #164: SaAdmin must cast player_steamid to string.
     *
     * AdminController::getAdminsList() returns the admin rows as JSON for the
     * DataTable. Without a string cast, player_steamid is emitted as a numeric
     * value on systems where PDO returns BIGINT columns as integers, and the
     * /list/admins page renders a bad Steam profile (see #163 for the same bug
     * on users.steam_id).
     */
    public function test_player_steamid_is_cast_to_string(): void
    {
        $admin = new SaAdmin();
        // Simulate a row hydrated from the DB with an integer SteamID64.
        $admin->setRawAttributes(['player_steamid' => (int) self::STEAM_ID64]);

        $this->assertIsString($admin->player_steamid, 'player_steamid should be cast to string.');
        $this->assertSame(self::STEAM_ID64, $admin->player_steamid, 'SteamID64 must round-trip exactly.');
    }

    /**
     * The value must survive json_encode() as a quoted string, mirroring the
     * response built in AdminController::getAdminsList().
     */
    public function test_player_steamid_survives_json_encoding_without_precision_loss(): void
    {
        $admin = new SaAdmin();
        $admin->setRawAttributes(['player_steamid' => (int) self::STEAM_ID64]);

        $json = json_encode(['player_steamid' => $admin->player_steamid]);
        $decoded = json_decode($json, true);

        $this->assertIsString($decoded['player_steamid'], 'JSON must carry player_steamid as a string.');
        $this->assertSame(self::STEAM_ID64, $decoded['player_steamid'], 'SteamID64 must not lose precision through JSON.');
    }
}
