<?php

namespace App\Http\Controllers;

use App\Models\AdminActivity;
use App\Repositories\AdminActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminActivityController extends Controller
{
    protected $adminActivityRepository;

    public function __construct(AdminActivityRepository $adminActivityRepository)
    {
        $this->middleware(['auth', 'superadmin']);
        $this->adminActivityRepository = $adminActivityRepository;
    }

    /**
     * Display a listing of the admin activities.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = $this->adminActivityRepository->getAll();
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Log an admin activity
     *
     * @param int $adminId
     * @param int $affectedUserId
     * @param string $actionType
     * @param string $description
     * @param Request $request
     * @return \App\Models\AdminActivity
     */
    public static function logActivity($adminId, $affectedUserId, $actionType, $description = null, Request $request = null)
    {
        $repository = app(AdminActivityRepository::class);
        $ipAddress = $request ? $request->ip() : null;
        return $repository->log($adminId, $affectedUserId, $actionType, $description, $ipAddress);
    }
}
