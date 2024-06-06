<?php
// app/Http/Controllers/WeaponSkinController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class WeaponSkinController extends Controller
{
    public function index()
    {
        $skins = json_decode(File::get(resource_path('json/skins.json')), true);

        // Fetch applied skins from the database
        $appliedSkins = DB::table('wp_player_skins')->where('steamid', Auth::user()?->steam_id)->get();

        // Group skins by weapon types dynamically
        $weaponTypes = [];
        foreach ($skins as $skin) {
            if(in_array($skin['weapon_defindex'], [
                500,
                503,
                505,
                506,
                507,
                508,
                509,
                512,
                514,
                515,
                516,
                517,
                518,
                519,
                520,
                521,
                522,
                523,
                525,
                526
            ])) {
                $weaponType = 'knife';
            } else {
                $weaponType = explode('_', $skin['weapon_name'])[1]; // Extract weapon type
            }
            if (!isset($weaponTypes[$weaponType])) {
                $weaponTypes[$weaponType] = [];
            }
            // Mark skin as applied if it exists in appliedSkins
            $skin['is_applied'] = $appliedSkins->contains(function ($value) use ($skin) {
                return $value->weapon_defindex == $skin['weapon_defindex'] && $value->weapon_paint_id == $skin['paint'];
            });

            $weaponTypes[$weaponType][] = $skin;
        }

        // Sort applied skins to be first in each category
        foreach ($weaponTypes as $type => $skins) {
            usort($skins, function($a, $b) {
                return $b['is_applied'] - $a['is_applied'];
            });
            $weaponTypes[$type] = $skins;
        }

        return view('weapons.skins', compact('weaponTypes'));
    }

    public function load($type)
    {
        $skins = json_decode(File::get(resource_path('json/skins.json')), true);
        $appliedSkins = DB::table('wp_player_skins')->where('steamid', Auth::user()?->steam_id)->get();

        $filteredSkins = array_filter($skins, function($skin) use ($type) {
            if($type == 'knife' && in_array($skin['weapon_defindex'], [
                    500,
                    503,
                    505,
                    506,
                    507,
                    508,
                    509,
                    512,
                    514,
                    515,
                    516,
                    517,
                    518,
                    519,
                    520,
                    521,
                    522,
                    523,
                    525,
                    526
                ])){
                 return true;
            }
            return str_contains(strtolower($skin['weapon_name']), strtolower($type));
        });

        // Mark skin as applied if it exists in appliedSkins
        foreach ($filteredSkins as &$skin) {
            $skin['is_applied'] = $appliedSkins->contains(function ($value) use ($skin) {
                return $value->weapon_defindex == $skin['weapon_defindex'] && $value->weapon_paint_id == $skin['paint'];
            });
        }

        // Sort applied skins to be first
        usort($filteredSkins, function($a, $b) {
            return $b['is_applied'] - $a['is_applied'];
        });

        return view('weapons.partials.weapon-types', ['skins' => $filteredSkins]);
    }
    public function applySkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steamid' => 'required|string',
            'weapon_defindex' => 'required|integer',
            'weapon_paint_id' => 'required|integer',
            'wearSelect' => 'required|numeric',
            'wear' => 'nullable|numeric',
            'seed' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validated = $validator->validated();
        if(in_array($validated['weapon_defindex'], [
                500,
                503,
                505,
                506,
                507,
                508,
                509,
                512,
                514,
                515,
                516,
                517,
                518,
                519,
                520,
                521,
                522,
                523,
                525,
                526
            ])){
            DB::table('wp_player_knife')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                ],
                [
                    'knife' => $request->input('weapon_name'),
                ]
            );
        }
        DB::table('wp_player_skins')->updateOrInsert(
            [
                'steamid' => $validated['steamid'],
                'weapon_defindex' => $validated['weapon_defindex'],
            ],
            [
                'weapon_paint_id' => $validated['weapon_paint_id'],
                'weapon_wear' => $validated['wearSelect'],
                'weapon_seed' => $validated['seed'] ?? 0,
            ]
        );

        return response()->json(['success' => 'Skin applied successfully!']);
    }


    public function agents()
    {
        $agents = json_decode(File::get(resource_path('json/agents.json')), true);

        // Fetch applied agents from the database
        $appliedAgents = DB::table('wp_player_agents')->where('steamid', Auth::user()?->steam_id)->first();

        foreach ($agents as &$agent) {
            if ($appliedAgents) {
                $agent['is_applied'] = ($agent['team'] == 2 && $agent['model'] == $appliedAgents->agent_t) || ($agent['team'] == 3 && $agent['model'] == $appliedAgents->agent_ct);
            } else {
                $agent['is_applied'] = false;
            }
        }

        // Sort applied agents to be first
        usort($agents, function($a, $b) {
            return $b['is_applied'] - $a['is_applied'];
        });

        return view('weapons.agents', ['agents' => $agents]);
    }

    public function gloves()
    {
        $gloves = json_decode(File::get(resource_path('json/gloves.json')), true);

        // Fetch applied gloves from the database
        $appliedGloves = DB::table('wp_player_skins')
            ->where('steamid', Auth::user()?->steam_id)
            ->pluck('weapon_paint_id')
            ->toArray();

        // Group gloves by paint name prefix
        $gloveTypes = [];
        foreach ($gloves as $glove) {
            $paintPrefix = explode(' | ', $glove['paint_name'])[0]; // Extract paint prefix
            if (!isset($gloveTypes[$paintPrefix])) {
                $gloveTypes[$paintPrefix] = [];
            }

            // Mark glove as applied if it exists in appliedGloves
            $glove['is_applied'] = in_array($glove['paint'], $appliedGloves);

            $gloveTypes[$paintPrefix][] = $glove;
        }

        // Sort applied gloves to be first in each category
        foreach ($gloveTypes as $type => $gloves) {
            usort($gloves, function($a, $b) {
                return $b['is_applied'] - $a['is_applied'];
            });
            $gloveTypes[$type] = $gloves;
        }

        return view('weapons.gloves', compact('gloveTypes'));
    }

    public function loadGloves($type)
    {
        $gloves = json_decode(File::get(resource_path('json/gloves.json')), true);
        $appliedGloves = DB::table('wp_player_skins')
            ->where('steamid', Auth::user()?->steam_id)
            ->pluck('weapon_paint_id')
            ->toArray();

        $filteredGloves = array_filter($gloves, function($glove) use ($type) {
            return str_contains(strtolower($glove['paint_name']), strtolower($type));
        });

        // Mark glove as applied if it exists in appliedGloves
        foreach ($filteredGloves as &$glove) {
            $glove['is_applied'] = in_array($glove['paint'], $appliedGloves);
        }

        // Sort applied gloves to be first
        usort($filteredGloves, function($a, $b) {
            return $b['is_applied'] - $a['is_applied'];
        });

        return view('weapons.partials.gloves-types', ['gloves' => $filteredGloves]);
    }


    public function music()
    {
        $music = json_decode(File::get(resource_path('json/music.json')), true);

        // Fetch applied music from the database
        $appliedMusic = DB::table('wp_player_music')->where('steamid', Auth::user()?->steam_id)->get();

        foreach ($music as &$track) {
            $track['is_applied'] = $appliedMusic->contains(function ($value) use ($track) {
                return $value->music_id == $track['id'];
            });
        }

        // Sort applied music to be first
        usort($music, function($a, $b) {
            return $b['is_applied'] - $a['is_applied'];
        });

        return view('weapons.music', ['music' => $music]);
    }


    public function applyAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steamid' => 'required|string',
            'team' => 'required|integer',
            'agent_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            DB::table('wp_player_agents')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                ],
                [
                    $validated['team'] == 2 ? 'agent_t' : 'agent_ct' => $validated['agent_name'],
                ]
            );

            return response()->json(['success' => 'Agent skin applied successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function applyGlove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steamid' => 'required|string',
            'weapon_defindex' => 'required|integer',
            'weapon_paint_id' => 'required|integer',
            'wearSelect' => 'required|numeric',
            'wear' => 'nullable|numeric',
            'seed' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            // Update or insert in wp_player_gloves table
            DB::table('wp_player_gloves')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                ],
                [
                    'weapon_defindex' => $validated['weapon_defindex'],
                ]
            );

            // Update or insert in wp_player_skins table
            DB::table('wp_player_skins')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                    'weapon_defindex' => $validated['weapon_defindex'],
                ],
                [
                    'weapon_paint_id' => $validated['weapon_paint_id'],
                    'weapon_wear' => $validated['wearSelect'],
                    'weapon_seed' => $validated['seed'] ?? 0,
                ]
            );

            return response()->json(['success' => 'Glove skin applied successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function applyMusic(Request $request)
    {
        $validated = $request->validate([
            'steamid' => 'required|string',
            'music_id' => 'required|integer',
        ]);

        DB::table('wp_player_music')->updateOrInsert(
            [
                'steamid' => $validated['steamid'],
            ],
            [
                'music_id' => $validated['music_id'],
            ]
        );

        return response()->json(['success' => 'Music applied successfully!']);
    }

}


