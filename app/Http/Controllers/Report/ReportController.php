<?php

namespace App\Http\Controllers\Report;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Report\Report;
use App\Models\SaServer;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        $servers = SaServer::all();
        return view('reports.create', compact('servers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ban_type' => 'required|string',
            'steamid' => 'required_if:ban_type,Steam ID|nullable|string',
            'ip' => 'required_if:ban_type,IP|nullable|ip',
            'nickname' => 'required|string',
            'comments' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'server_id' => 'required|integer',
            'media_link' => 'nullable|url',
        ]);

        $data = $request->only(['ban_type', 'steamid', 'ip', 'nickname', 'comments', 'name', 'email', 'server_id', 'media_link']);
        $report = Report::create($data);
        CommonHelper::sendActionLog('report', $report->id);

        return redirect()->route('reports.create')->with('success', 'Report submitted successfully.');
    }

    public function list()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('reports.list', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);
        return view('reports.show', compact('report'));
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('reports.list')->with('success', 'Report deleted successfully.');
    }
}
