<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  
    public function index()
    {
    $users = User::orderBy('name')->get();

    return view('admin.users.index', ['users' => $users]);
    }

 
    public function create()
    {
         return view('admin.users.create');
    }

 
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $data['is_admin'] = $request->boolean('is_admin');

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Użytkownik został dodany.');
    }

    /*public function show(User $user)
    {
        
    }*/


    public function edit(User $user)
    {
         return view('admin.users.edit', compact('user'));
    }

 
   public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $data['is_admin'] = $request->boolean('is_admin');

       
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Zmiany zostały zapisane.');
    }


    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
        return back()->with('error', 'Nie możesz usunąć własnego konta.');
        }

        $user->delete();

        return redirect()
        ->route('admin.users.index')
        ->with('success', 'Użytkownik został usunięty.');
    }
}
