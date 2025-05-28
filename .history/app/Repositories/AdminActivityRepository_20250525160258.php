<?php

namespace App\Repositories;

use App\Models\AdminActivity;
use Illuminate\Http\Request;

class AdminActivityRepository
{
    public function log($adminId, $affectedUserId, $actionType, $description = null, $ipAddress = null)
    {
        return AdminActivity::create([
            'admin_id' => $adminId,
            'affected_user_id' => $affectedUserId,
            'action_type' => $actionType,
            'description' => $description,
            'ip_address' => $ipAddress,
        ]);
    }

    public function getAll()
    {
        return AdminActivity::with(['admin', 'affectedUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
}
