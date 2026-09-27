<?php

namespace Tests\Feature;

use App\Helpers\CommonHelper;
use Tests\TestCase;

/**
 * Regression coverage for issue #169.
 *
 * AdminLogHelper::log() forwards every admin action to
 * CommonHelper::sendActionLog(). When DISCORD_WEBHOOK is configured, the
 * switch() in sendActionLog() only handled ban/unban/mute/unmute/appeal/report.
 * For any of the other 17 audit-only actions the local variables
 * ($username, $steamId, $reason, $admin) were never assigned and the embed
 * interpolation below threw "Undefined variable $username", producing an
 * HTTP 500 and aborting the caller before its DB write.
 *
 * A `default: return;` branch makes audit-only actions no-op instead.
 */
class SendActionLogTest extends TestCase
{
    /** @var string|false */
    private $previousWebhook;

    protected function setUp(): void
    {
        parent::setUp();

        // Force the webhook to be considered "configured" so sendActionLog()
        // enters the branch that used to throw. env() reads from these supers.
        $this->previousWebhook = $_ENV['DISCORD_WEBHOOK'] ?? false;
        $_ENV['DISCORD_WEBHOOK'] = 'https://discord.com/api/webhooks/test/regression-169';
        $_SERVER['DISCORD_WEBHOOK'] = $_ENV['DISCORD_WEBHOOK'];
        putenv('DISCORD_WEBHOOK=' . $_ENV['DISCORD_WEBHOOK']);
    }

    protected function tearDown(): void
    {
        if ($this->previousWebhook === false) {
            unset($_ENV['DISCORD_WEBHOOK'], $_SERVER['DISCORD_WEBHOOK']);
            putenv('DISCORD_WEBHOOK');
        } else {
            $_ENV['DISCORD_WEBHOOK'] = $this->previousWebhook;
            $_SERVER['DISCORD_WEBHOOK'] = $this->previousWebhook;
            putenv('DISCORD_WEBHOOK=' . $this->previousWebhook);
        }

        parent::tearDown();
    }

    /**
     * Every audit-only action must return without throwing and without
     * attempting to build/send a Discord embed.
     *
     * @dataProvider auditOnlyActions
     */
    public function test_audit_only_actions_do_not_throw(string $action): void
    {
        // A non-existent target id is fine: the default branch returns before
        // touching the database or the HTTP client.
        $this->assertNull(
            CommonHelper::sendActionLog($action, 999999),
            "sendActionLog('{$action}') should no-op for audit-only actions"
        );
    }

    public static function auditOnlyActions(): array
    {
        return array_map(fn ($action) => [$action], [
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
        ]);
    }
}
