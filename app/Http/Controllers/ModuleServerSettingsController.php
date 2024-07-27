<?php
namespace App\Http\Controllers;

use App\Models\ModuleServerSetting;
use Illuminate\Http\Request;

class ModuleServerSettingsController extends Controller
{
    public function index()
    {
        $settings = ModuleServerSetting::all();
        return view('settings.moduleSettings.index', compact('settings'));
    }

    public function create()
    {
        return view('settings.moduleSettings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'db_host' => 'required|string|max:255',
            'db_user' => 'required|string|max:255',
            'db_pass' => 'required|string|max:255',
            'db_name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        ModuleServerSetting::create($request->all());

        return redirect()->route('module-server-settings.index')->with('success', 'Server settings created successfully.');
    }

    public function edit($id)
    {
        $setting = ModuleServerSetting::findOrFail($id);
        return view('settings.moduleSettings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'db_host' => 'required|string|max:255',
            'db_user' => 'required|string|max:255',
            'db_pass' => 'required|string|max:255',
            'db_name' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $setting = ModuleServerSetting::findOrFail($id);
        $data = $request->all();

        if (!$request->filled('db_pass')) {
            unset($data['db_pass']);
        }

        $setting->update($data);

        return redirect()->route('module-server-settings.index')->with('success', 'Server settings updated successfully.');
    }

    public function destroy($id)
    {
        $setting = ModuleServerSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('module-server-settings.index')->with('success', 'Server settings deleted successfully.');
    }
}
