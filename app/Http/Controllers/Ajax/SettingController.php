<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function set_theme(Request $request)
    {
        if (!empty($request->theme_reset)) {
            auth()->user()->settings()->where('key', 'LIKE', 'theme%')->delete();

            return response()->json([
                'status' => 202
            ]);
        }

        $dados = $request->validate([
            'type' => ['required', 'string'],
            'value' => ['required', 'string'],
        ]);

        Setting::updateOrCreate(
            ['user_id' => auth()->id(), 'key' => $dados['type']],
            ['value' => $dados['value']]
        );

        foreach (auth()->user()->settings as $Setting) {
            $UserSettings[$Setting->key] = $Setting->value;
        }

        session()->put('UserSettings', $UserSettings);

        return response()->json([
            'status' => 200
        ]);
    }
}
