<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data_breadcrumbs = [
            [
                'name' => 'Perfil',
            ],
        ];

        return view('admin.profile.index', [
            'data_breadcrumbs' => $data_breadcrumbs,
            'User' => auth()->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        auth()->user()->update($data);

        return redirect()->route('admin.profile.index', ['tab' => $request->tab])->with('success', 'Perfil atualizado com sucesso!');
    }
}
