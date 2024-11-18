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
        $appliedSkins =  DB::connection('mysqlskins')->table('wp_player_skins')->where('steamid', Auth::user()?->steam_id)->get();

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
            unset($weaponTypes['knife']);
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
        // Modified query to include weapon_nametag
        $appliedSkins = DB::connection('mysqlskins')
            ->table('wp_player_skins')
            ->select('weapon_defindex', 'weapon_paint_id', 'weapon_wear', 'weapon_seed', 'weapon_nametag', 'weapon_stattrak', 'weapon_team')
            ->where('steamid', Auth::user()?->steam_id)
            ->get();
        $appliedKnife = DB::connection('mysqlskins')
            ->table('wp_player_knife')
            ->where('steamid', Auth::user()?->steam_id)
            ->first()?->knife;

        $filteredSkins = array_filter($skins, function($skin) use ($type) {
            if($type == 'knife' && in_array($skin['weapon_defindex'], [
                500, 503, 505, 506, 507, 508, 509, 512, 514, 515,
                516, 517, 518, 519, 520, 521, 522, 523, 525, 526
            ])) {
                return true;
            }
            return str_contains(strtolower($skin['weapon_name']), strtolower($type));
        });

        foreach ($filteredSkins as &$skin) {
            // Check for team-specific applications
            $skin['is_applied_t'] = $appliedSkins->contains(function ($value) use ($skin, $type, $appliedKnife) {
                if ($type == 'knife') {
                    return $value->weapon_defindex == $skin['weapon_defindex']
                        && $value->weapon_paint_id == $skin['paint']
                        && $appliedKnife == $skin['weapon_name']
                        && $value->weapon_team == 2; // T team
                }
                return $value->weapon_defindex == $skin['weapon_defindex']
                    && $value->weapon_paint_id == $skin['paint']
                    && $value->weapon_team == 2; // T team
            });
        
            $skin['is_applied_ct'] = $appliedSkins->contains(function ($value) use ($skin, $type, $appliedKnife) {
                if ($type == 'knife') {
                    return $value->weapon_defindex == $skin['weapon_defindex']
                        && $value->weapon_paint_id == $skin['paint']
                        && $appliedKnife == $skin['weapon_name']
                        && $value->weapon_team == 3; // CT team
                }
                return $value->weapon_defindex == $skin['weapon_defindex']
                    && $value->weapon_paint_id == $skin['paint']
                    && $value->weapon_team == 3; // CT team
            });
        
            // Retain weapon nametag logic for applied skins
            $appliedSkin = $appliedSkins->first(function ($value) use ($skin, $type, $appliedKnife) {
                if ($type == 'knife') {
                    return $value->weapon_defindex == $skin['weapon_defindex']
                        && $value->weapon_paint_id == $skin['paint']
                        && $appliedKnife == $skin['weapon_name'];
                }
                return $value->weapon_defindex == $skin['weapon_defindex']
                    && $value->weapon_paint_id == $skin['paint'];
            });
        
            $skin['wear'] = $appliedSkin ? $appliedSkin->weapon_wear : '';
            $skin['seed'] = $appliedSkin ? $appliedSkin->weapon_seed : '';
            $skin['weapon_nametag'] = $appliedSkin ? $appliedSkin->weapon_nametag : '';
            $skin['weapon_stattrak'] = $appliedSkin ? $appliedSkin->weapon_stattrak : '';
            $skin['weapon_team'] = $appliedSkin ? $appliedSkin->weapon_team : '';
        }
        
        // Sort applied skins to be first (based on either team application)
        usort($filteredSkins, function($a, $b) {
            return ($b['is_applied_t'] || $b['is_applied_ct']) - ($a['is_applied_t'] || $a['is_applied_ct']);
        });        

        return view('weapons.partials.weapon-types', ['skins' => $filteredSkins]);
    }

    public function applySkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steamid' => 'required|string',
            'weapon_defindex' => 'required|integer',
            'weapon_paint_id' => 'required|integer',
            'wearSelect' => 'required_without:wear|numeric',
            'wear' => 'nullable|numeric',
            'seed' => 'nullable|integer',
            'weapon_team' => 'required|integer',
            'weapon_nametag' => 'nullable|string',
            'weapon_stattrak' => 'nullable|integer',
            'weapon_stattrak_count' => 'nullable|integer',
            'weapon_sticker_0' => 'nullable|string',
            'weapon_sticker_1' => 'nullable|string',
            'weapon_sticker_2' => 'nullable|string',
            'weapon_sticker_3' => 'nullable|string',
            'weapon_sticker_4' => 'nullable|string',
            'weapon_keychain' => 'nullable|string'
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
             DB::connection('mysqlskins')->table('wp_player_knife')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                    'weapon_team' => $validated['weapon_team'],
                ],
                [
                    'knife' => $request->input('weapon_name'),
                ]
            );
        }

        $wear = $validated['wear'] ?? $validated['wearSelect'];
    
        
         DB::connection('mysqlskins')->table('wp_player_skins')->updateOrInsert(
            [
                'steamid' => $validated['steamid'],
                'weapon_defindex' => $validated['weapon_defindex'],
                'weapon_team' => $validated['weapon_team'],
            ],
            [
                'weapon_paint_id' => $validated['weapon_paint_id'],
                'weapon_wear' => $wear,
                'weapon_seed' => $validated['seed'] ?? 0,
                'weapon_nametag' => $validated['weapon_nametag'] ?? '',
                'weapon_stattrak' => $validated['weapon_stattrak'] ?? 0,
                'weapon_stattrak_count' => $validated['weapon_stattrak_count'] ?? 0,
                'weapon_sticker_0' => $validated['weapon_sticker_0'] ?? '0;0;0;0;0;0;0',
                'weapon_sticker_1' => $validated['weapon_sticker_1'] ?? '0;0;0;0;0;0;0',
                'weapon_sticker_2' => $validated['weapon_sticker_2'] ?? '0;0;0;0;0;0;0',
                'weapon_sticker_3' => $validated['weapon_sticker_3'] ?? '0;0;0;0;0;0;0',
                'weapon_sticker_4' => $validated['weapon_sticker_4'] ?? '0;0;0;0;0;0;0',
                'weapon_keychain' => $validated['weapon_keychain'] ?? '0;0;0;0;0',
            ]
        );

        return response()->json(['success' => 'Skin applied successfully!']);
    }


    public function agents()
    {
        $agents = json_decode(File::get(resource_path('json/agents.json')), true);

        // Fetch applied agents from the database
        $appliedAgents = DB::connection('mysqlskins')->table('wp_player_agents')->where('steamid', Auth::user()?->steam_id)->first();

        foreach ($agents as &$agent) {
            if ($appliedAgents) {
                $agent['is_applied_t'] = $agent['team'] == 2 && $agent['model'] == $appliedAgents->agent_t;
                $agent['is_applied_ct'] = $agent['team'] == 3 && $agent['model'] == $appliedAgents->agent_ct;
            } else {
                $agent['is_applied_t'] = false;
                $agent['is_applied_ct'] = false;
            }
        }

        // Sort applied agents to be first (T or CT applied)
        usort($agents, function ($a, $b) {
            return ($b['is_applied_t'] || $b['is_applied_ct']) - ($a['is_applied_t'] || $a['is_applied_ct']);
        });

        return view('weapons.agents', ['agents' => $agents]);
    }

    public function gloves()
    {
        $gloves = json_decode(File::get(resource_path('json/gloves.json')), true);
        $appliedGloveIndex =  DB::connection('mysqlskins')->table('wp_player_gloves')->where('steamid', Auth::user()?->steam_id)->first()?->weapon_defindex;

        // Fetch applied gloves from the database
        $appliedGloves =  DB::connection('mysqlskins')->table('wp_player_skins')
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
            $glove['is_applied'] = in_array($glove['paint'], $appliedGloves) && $glove['weapon_defindex'] == $appliedGloveIndex;
           // dump($glove);
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
        $user = Auth::user();

        if (!$user) {
            return view('weapons.partials.gloves-types', ['gloves' => []]);
        }

        // Get applied skins for gloves
        $appliedSkins = DB::connection('mysqlskins')->table('wp_player_skins')->where('steamid', $user->steam_id)->get();
        $appliedGloveIndex = DB::connection('mysqlskins')->table('wp_player_gloves')->where('steamid', $user->steam_id)->first()?->weapon_defindex;

        $filteredGloves = array_filter($gloves, function($glove) use ($type) {
            return str_contains(strtolower($glove['paint_name']), strtolower($type));
        });

        // Mark glove as applied for T and CT teams and add additional properties
        foreach ($filteredGloves as &$glove) {
            // Check for T team
            $glove['is_applied_t'] = $appliedSkins->contains(function ($value) use ($glove) {
                return $value->weapon_defindex == $glove['weapon_defindex']
                    && $value->weapon_paint_id == $glove['paint']
                    && $value->weapon_team == 2; // T team
            });

            // Check for CT team
            $glove['is_applied_ct'] = $appliedSkins->contains(function ($value) use ($glove) {
                return $value->weapon_defindex == $glove['weapon_defindex']
                    && $value->weapon_paint_id == $glove['paint']
                    && $value->weapon_team == 3; // CT team
            });

            // Find the applied skin for additional properties (wear, seed, team)
            $appliedSkin = $appliedSkins->first(function ($value) use ($glove) {
                return $value->weapon_defindex == $glove['weapon_defindex']
                    && $value->weapon_paint_id == $glove['paint'];
            });

            // Set additional properties for wear, seed, and team
            if ($appliedSkin) {
                $glove['wear'] = $appliedSkin->weapon_wear ?? ''; // Wear
                $glove['seed'] = $appliedSkin->weapon_seed ?? ''; // Seed
                $glove['weapon_team'] = $appliedSkin->weapon_team ?? ''; // Team
            } else {
                $glove['wear'] = '';
                $glove['seed'] = '';
                $glove['weapon_team'] = '';
            }
        }

        // Sort applied gloves to be first for T and CT teams
        usort($filteredGloves, function($a, $b) {
            return ($b['is_applied_t'] || $b['is_applied_ct']) <=> ($a['is_applied_t'] || $a['is_applied_ct']);
        });

        return view('weapons.partials.gloves-types', ['gloves' => $filteredGloves]);
    }


    public function music()
    {
        $music = json_decode(File::get(resource_path('json/music.json')), true);

        // Fetch applied music from the database
        $appliedMusic = DB::connection('mysqlskins')->table('wp_player_music')->where('steamid', Auth::user()?->steam_id)->get();

        foreach ($music as &$track) {
            // Check for team-specific applications using numeric values
            $track['is_applied_t'] = $appliedMusic->contains(function ($value) use ($track) {
                return $value->music_id == $track['id'] && $value->weapon_team == 2;  // T team
            });
            
            $track['is_applied_ct'] = $appliedMusic->contains(function ($value) use ($track) {
                return $value->music_id == $track['id'] && $value->weapon_team == 3;  // CT team
            });
        }

        // Sort applied music to be first (based on either team application)
        usort($music, function($a, $b) {
            return ($b['is_applied_t'] || $b['is_applied_ct']) - ($a['is_applied_t'] || $a['is_applied_ct']);
        });

        return view('weapons.music', ['music' => $music]);
    }

    public function pin()
    {
        $pins = json_decode(File::get(resource_path('json/pins.json')), true);

        // Fetch applied pins from the database
        $appliedPin = DB::connection('mysqlskins')->table('wp_player_pins')->where('steamid', Auth::user()?->steam_id)->get();

        foreach ($pins as &$pin) {
            // Check for team-specific applications using numeric values
            $pin['is_applied_t'] = $appliedPin->contains(function ($value) use ($pin) {
                return $value->id == $pin['id'] && $value->weapon_team == 2;  // T team
            });
            
            $pin['is_applied_ct'] = $appliedPin->contains(function ($value) use ($pin) {
                return $value->id == $pin['id'] && $value->weapon_team == 3;  // CT team
            });
        }

        // Sort applied pins to be first (based on either team application)
        usort($pins, function($a, $b) {
            return ($b['is_applied_t'] || $b['is_applied_ct']) - ($a['is_applied_t'] || $a['is_applied_ct']);
        });

        return view('weapons.pins', ['pins' => $pins]);
    }

    public function applyAgent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steamid' => 'required|string',
            'team' => 'required|integer',
            'agent_name' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
             DB::connection('mysqlskins')->table('wp_player_agents')->updateOrInsert(
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
            'weapon_team' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            // Delete any existing glove data for the same weapon_team and weapon_defindex
            DB::connection('mysqlskins')->table('wp_player_gloves')
                ->where('steamid', $validated['steamid'])
                ->where('weapon_team', $validated['weapon_team'])
                ->whereBetween('weapon_defindex', [4725, 5035])
                ->delete();

            // Delete any existing skin data for the same weapon_team and weapon_defindex
            DB::connection('mysqlskins')->table('wp_player_skins')
                ->where('steamid', $validated['steamid'])
                ->where('weapon_team', $validated['weapon_team'])
                ->whereBetween('weapon_defindex', [4725, 5035])
                ->delete();

            // Update or insert in wp_player_gloves table
            DB::connection('mysqlskins')->table('wp_player_gloves')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                    'weapon_team' => $validated['weapon_team'],
                ],
                [
                    'weapon_defindex' => $validated['weapon_defindex'],
                ]
            );

            // Update or insert in wp_player_skins table
            DB::connection('mysqlskins')->table('wp_player_skins')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                    'weapon_defindex' => $validated['weapon_defindex'],
                    'weapon_team' => $validated['weapon_team'],
                ],
                [
                    'weapon_paint_id' => $validated['weapon_paint_id'],
                    'weapon_wear' => $validated['wearSelect'],
                    'weapon_seed' => $validated['seed'] ?? 0,
                    'weapon_nametag' => '',
                    'weapon_stattrak' => 0,
                    'weapon_stattrak_count' => 0,
                    'weapon_sticker_0' => '0;0;0;0;0;0;0',
                    'weapon_sticker_1' => '0;0;0;0;0;0;0',
                    'weapon_sticker_2' => '0;0;0;0;0;0;0',
                    'weapon_sticker_3' => '0;0;0;0;0;0;0',
                    'weapon_sticker_4' => '0;0;0;0;0;0;0',
                    'weapon_keychain' => '0;0;0;0;0',
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
            'weapon_team' => 'required|integer',
        ]);

         DB::connection('mysqlskins')->table('wp_player_music')->updateOrInsert(
            [
                'steamid' => $validated['steamid'],
                'weapon_team' => $validated['weapon_team'],
            ],
            [
                'music_id' => $validated['music_id'],
            ]
        );

        return response()->json(['success' => 'Music applied successfully!']);
    }

    public function applyPin(Request $request)
    {
        $validated = $request->validate([
            'steamid' => 'required|string',
            'id' => 'required|integer',
            'weapon_team' => 'required|integer',
        ]);

         DB::connection('mysqlskins')->table('wp_player_pins')->updateOrInsert(
            [
                'steamid' => $validated['steamid'],
                'weapon_team' => $validated['weapon_team'],
            ],
            [
                'id' => $validated['id'],
            ]
        );

        return response()->json(['success' => 'Pin applied successfully!']);
    }

    public function knives()
    {
        $skins = json_decode(File::get(resource_path('json/skins.json')), true);

        // Fetch applied knives from the database
        $appliedSkins =  DB::connection('mysqlskins')->table('wp_player_skins')->where('steamid', Auth::user()?->steam_id)->get();
        $appliedKnife = DB::connection('mysqlskins')->table('wp_player_knife')->where('steamid', Auth::user()?->steam_id)->first()?->knife;

        // Group knives by weapon types dynamically
        $knifeCategories = [];
        foreach ($skins as $skin) {
            if (in_array($skin['weapon_defindex'], [
                500, 503, 505, 506, 507, 508, 509, 512, 514, 515, 516, 517, 518, 519, 520, 521, 522, 523, 525, 526
            ])) {
                if (!isset($knifeCategories[$skin['weapon_defindex']])) {
                    $knifeCategories[$skin['weapon_defindex']] = [];
                }

                // Mark skin as applied if it exists in appliedKnives
                $skin['is_applied'] = $appliedSkins->contains(function ($value) use ($skin, $appliedKnife) {
                    return $value->weapon_defindex == $skin['weapon_defindex'] && $value->weapon_paint_id == $skin['paint'] && $skin['weapon_name'] == $appliedKnife;
                });
                $knifeCategories[$skin['weapon_defindex']][] = $skin;
            }
        }

        // Sort applied knives to be first in each category
        foreach ($knifeCategories as $type => $knives) {
            usort($knives, function ($a, $b) {
                return $b['is_applied'] - $a['is_applied'];
            });
            $knifeCategories[$type] = $knives;
        }
        return view('weapons.knives', compact('knifeCategories'));
    }

    public function loadKnives($category)
    {
        $skins = json_decode(File::get(resource_path('json/skins.json')), true);
        $user = Auth::user();
        
        if (!$user) {
            return view('weapons.partials.knife-types', ['skins' => []]);
        }
        
        $appliedSkins = DB::connection('mysqlskins')->table('wp_player_skins')->where('steamid', $user->steam_id)->get();
            
        $appliedKnife = DB::connection('mysqlskins')->table('wp_player_knife')->where('steamid', $user->steam_id)->first();

        $kniveDefindexes = [
            500, 503, 505, 506, 507, 508, 509, 512, 514, 
            515, 516, 517, 518, 519, 520, 521, 522, 523, 
            525, 526
        ];

        $filteredKnives = array_filter($skins, function ($skin) use ($category, $kniveDefindexes) {
            return in_array($skin['weapon_defindex'], $kniveDefindexes) && 
                $skin['weapon_defindex'] == $category;
        });

        $type = 'knife'; // Since this is loadKnives function, we set type to knife

        // Mark skin as applied for T and CT teams
        foreach ($filteredKnives as &$skin) {
            // Check for T team
            $skin['is_applied_t'] = $appliedSkins->contains(function ($value) use ($skin) {
                return $value->weapon_defindex == $skin['weapon_defindex'] 
                    && $value->weapon_paint_id == $skin['paint']
                    && $value->weapon_team == 2; // T team
            });
        
            // Check for CT team
            $skin['is_applied_ct'] = $appliedSkins->contains(function ($value) use ($skin) {
                return $value->weapon_defindex == $skin['weapon_defindex'] 
                    && $value->weapon_paint_id == $skin['paint']
                    && $value->weapon_team == 3; // CT team
            });

            // Find the applied skin for additional properties
            $appliedSkin = $appliedSkins->first(function ($value) use ($skin, $appliedKnife) {
                return $value->weapon_defindex == $skin['weapon_defindex'] && 
                    $value->weapon_paint_id == $skin['paint'] && 
                    $skin['weapon_name'] == optional($appliedKnife)->knife;
            });
            
            // Set additional properties
            if ($appliedSkin) {
                $skin['wear'] = $appliedSkin->weapon_wear ?? '';
                $skin['seed'] = $appliedSkin->weapon_seed ?? '';
                $skin['weapon_nametag'] = $appliedSkin->weapon_nametag ?? '';
                $skin['weapon_stattrak'] = $appliedSkin->weapon_stattrak ?? '';
                $skin['weapon_team'] = $appliedSkin->weapon_team ?? '';
            } else {
                $skin['wear'] = '';
                $skin['seed'] = '';
                $skin['weapon_nametag'] = '';
                $skin['weapon_stattrak'] = '';
                $skin['weapon_team'] = '';
            }
        }

        // Sort applied knives to be first for T and CT teams
        usort($filteredKnives, function ($a, $b) {
            return ($b['is_applied_t'] || $b['is_applied_ct']) <=> ($a['is_applied_t'] || $a['is_applied_ct']);
        });

        return view('weapons.partials.knife-types', ['skins' => $filteredKnives]);
    }
    
    public function applyKnife(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'steamid' => 'required|string',
            'weapon_defindex' => 'required|integer',
            'weapon_paint_id' => 'required|integer',
            'wearSelect' => 'required|numeric',
            'wear' => 'nullable|numeric',
            'seed' => 'nullable|integer',
            'weapon_team' => 'required|integer',
            'weapon_nametag' => 'nullable|string',
            'weapon_stattrak' => 'nullable|integer',
            'weapon_stattrak_count' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // Delete any existing skin data for the same weapon_team and weapon_defindex
        DB::connection('mysqlskins')->table('wp_player_skins')
        ->where('steamid', $validated['steamid'])
        ->where('weapon_team', $validated['weapon_team'])
        ->whereBetween('weapon_defindex', [500, 526])
        ->delete();

        try {
            DB::connection('mysqlskins')->table('wp_player_knife')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                    'weapon_team' => $validated['weapon_team'],
                ],
                [
                    'knife' => $request->input('weapon_name'),
                ]
            );

            DB::connection('mysqlskins')->table('wp_player_skins')->updateOrInsert(
                [
                    'steamid' => $validated['steamid'],
                    'weapon_defindex' => $validated['weapon_defindex'],
                    'weapon_team' => $validated['weapon_team'],
                ],
                [
                    'weapon_paint_id' => $validated['weapon_paint_id'],
                    'weapon_wear' => $validated['wearSelect'],
                    'weapon_seed' => $validated['seed'] ?? 0,
                    'weapon_nametag' => $validated['weapon_nametag'] ?? '',
                    'weapon_stattrak' => $validated['weapon_stattrak'] ?? 0,
                    'weapon_stattrak_count' => $validated['weapon_stattrak_count'] ?? 0,
                    'weapon_sticker_0' => $validated['weapon_sticker_0'] ?? '0;0;0;0;0;0;0',
                    'weapon_sticker_1' => $validated['weapon_sticker_1'] ?? '0;0;0;0;0;0;0',
                    'weapon_sticker_2' => $validated['weapon_sticker_2'] ?? '0;0;0;0;0;0;0',
                    'weapon_sticker_3' => $validated['weapon_sticker_3'] ?? '0;0;0;0;0;0;0',
                    'weapon_sticker_4' => $validated['weapon_sticker_4'] ?? '0;0;0;0;0;0;0',
                    'weapon_keychain' => $validated['weapon_keychain'] ?? '0;0;0;0;0',
                ]
            );

            return response()->json(['success' => 'Knife applied successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}