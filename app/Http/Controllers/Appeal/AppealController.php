<?php
namespace App\Http\Controllers\Appeal;

use App\Helpers\CommonHelper;
use App\Helpers\AdminLogHelper;
use App\Http\Controllers\Controller;
use App\Mail\AppealApproved;
use App\Models\Appeal\Appeal;
use App\Models\SaBan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppealController extends Controller
{
    public function index()
    {
        $appeals = Appeal::all();
        return view('appeals.create', compact('appeals'));
    }

    public function create()
    {
        return view('appeals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ban_type' => 'required|string',
            'steamid' => 'required_if:ban_type,Steam ID|nullable|string',
            'ip' => 'required_if:ban_type,IP|nullable|ip',
            'name' => 'required|string',
            'reason' => 'required|string',
            'email' => 'required|email',
        ]);

        $existingAppeal = Appeal::where(function ($query) use ($validated) {
            if (isset($validated['steamid'])) {
                $query->where('steamid', $validated['steamid']);
            }
    
            if (isset($validated['ip'])) {
                $query->orWhere('ip', $validated['ip']);
            }
        })
        ->where('status', 'PENDING')
        ->first();

        if ($existingAppeal) {
            return redirect()->route('appeals.create')->with('error', __('You have a pending Appeal request.'));
        }

        if(SaBan::where('player_steamid', $validated['steamid'])
            ->where('status', 'ACTIVE')
            ->exists()) {
            $data = $request->only(['ban_type', 'steamid', 'ip', 'name', 'reason', 'email']);
            $data['status'] = 'PENDING';
            // Save the appeal
            $appeal = Appeal::create($data);
            CommonHelper::sendActionLog('appeal', $appeal->id);
            return redirect()->route('appeals.create')->with('success', __('Appeal submitted successfully.'));
        }
        else {
            return redirect()->route('appeals.create')->with('error', __('No active bans exists for this Steam ID or IP'));
        }
    }

    public function list()
    {
        $appeals = Appeal::orderBy('created_at', 'desc')->get();
        return view('appeals.list', compact('appeals'));
    }

    public function view($id)
    {
        $appeal = Appeal::findOrFail($id);
        return view('appeals.show', compact('appeal'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:APPROVED,REJECTED',
        ]);

        $appeal = Appeal::findOrFail($id);
        $appeal->status = $request->input('status');
        $appeal->save();
        Mail::to($appeal->email)->send(new AppealApproved($appeal));
        
        // Log the appeal status update
        AdminLogHelper::log(
            $request->input('status') === 'APPROVED' ? 'approve_appeal' : 'reject_appeal',
            'appeal',
            $appeal->id,
            ['status' => $request->input('status')]
        );

        return redirect()->route('appeals.list', $appeal->id)->with('success',  __('Appeal status updated successfully.'));
    }
}
