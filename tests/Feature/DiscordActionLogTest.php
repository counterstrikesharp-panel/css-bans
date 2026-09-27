<?php

namespace Tests\Feature;

use App\Helpers\CommonHelper;
use Tests\TestCase;

class DiscordActionLogTest extends TestCase
{
    /**
     * Actions that AdminLogHelper::log() forwards to sendActionLog() but that
     * have no Discord embed of their own. Before #169 these fell through the
     * switch with no `default:` branch, leaving $username/$steamId/$reason
     * undefined and throwing an ErrorException once DISCORD_WEBHOOK was set.
     *
     * @var array<int, string>
     */
    private const AUDIT_ONLY_ACTIONS = [
        'add_admin',
        'edit_admin',
        'delete_admin',
        'create_group',
        'update_group',
        'delete_group',
        'add_admin_to_group',
        'migrate_admin_to_group',
        'add_group_to_server',
        'delete_group_from_servers',
        'add_permission_to_admin',
        'remove_permission_from_admin',
        'add_permission_to_group',
        'remove_permission_from_group',
        'edit_ban',
        'edit_mute',
        'delete_report',
    ];

    private const WEBHOOK = 'https://discord.test/api/webhooks/regression/169';

    protected function setUp(): void
    {
        parent::setUp();

        // Reproduce the failing condition: DISCORD_WEBHOOK configured.
        putenv('DISCORD_WEBHOOK='.self::WEBHOOK);
        $_ENV['DISCORD_WEBHOOK'] = self::WEBHOOK;
        $_SERVER['DISCORD_WEBHOOK'] = self::WEBHOOK;
    }

    protected function tearDown(): void
    {
        putenv('DISCORD_WEBHOOK');
        unset($_ENV['DISCORD_WEBHOOK'], $_SERVER['DISCORD_WEBHOOK']);

        parent::tearDown();
    }

    /**
     * Regression test for #169: audit-only actions must not raise
     * "Undefined variable $username" (HTTP 500) when DISCORD_WEBHOOK is set.
     * They have no embed, so sendActionLog() should return early.
     *
     * @dataProvider auditOnlyActionProvider
     */
    public function test_audit_only_action_does_not_throw_when_webhook_is_set(string $action): void
    {
        $this->assertNull(
            CommonHelper::sendActionLog($action, 999),
            "sendActionLog('{$action}') should return early instead of building a Discord embed."
        );
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function auditOnlyActionProvider(): array
    {
        $cases = [];
        foreach (self::AUDIT_ONLY_ACTIONS as $action) {
            $cases[$action] = [$action];
        }

        return $cases;
    }
}
