<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends SearchableController
{
    public const MAX_ITEMS = 10;

    #[\Override]
    public function getQuery(): Builder
    {
        return User::query()->orderBy('email');
    }

    
    protected function find(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    protected function currentUser(): User
    {
        return \Auth::user();
    }

   
    public function prepareCriteria(array $src): array
    {
        return [
            'term' => $src['term'] ?? null,
        ];
    }

    public function filter($query, array $criteria)
    {
        $term = trim((string)($criteria['term'] ?? ''));
        if ($term !== '') {
            $query->where(static function ($q) use ($term) {
                $q->where('email', 'like', "%{$term}%")
                  ->orWhere('name', 'like', "%{$term}%")
                  ->orWhere('role', 'like', "%{$term}%");
            });
        }
        return $query;
    }

   

    public function list(ServerRequestInterface $request): View
    {
       
        Gate::authorize('create', User::class); 
        $criteria = $this->prepareCriteria($request->getQueryParams());
        $users = $this->filter($this->getQuery(), $criteria)->paginate(self::MAX_ITEMS);

        return view('users.list', compact('criteria', 'users'));
    }

    public function showCreateForm(): View
    {
        Gate::authorize('create', User::class);
        return view('users.create-form');
    }

    public function create(ServerRequestInterface $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $data = validator($request->getParsedBody(), [
            'email' => ['required','email','max:255','unique:users,email'],
            'name' => ['required','max:255'],
            'password' => ['required','max:255'],
            'role' => ['required', Rule::in(['ADMIN','USER'])],
        ])->validate();

        $user = new User();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = $data['password']; 
        $user->role = $data['role'];
        $user->email_verified_at = now();
        $user->remember_token = '';
        $user->save();

        return redirect()
            ->route('users.list')
            ->with('status', "User {$user->email} was created.");
    }

    public function view(string $user): View
    {
        Gate::authorize('create', User::class); 
        $user = $this->find($user);

       
        session()->put('bookmarks.users.view', url()->full());

        return view('users.view', compact('user'));
    }

    public function showUpdateForm(string $user): View
    {
        Gate::authorize('create', User::class); 
        $user = $this->find($user);
        return view('users.update-form', compact('user'));
    }

    public function update(ServerRequestInterface $request, string $user): RedirectResponse
    {
        Gate::authorize('create', User::class); 
        $user = $this->find($user);

       
        $isSelf = $this->currentUser()->id === $user->id;

        $data = $request->getParsedBody();

        $rules = [
            'name' => ['required','max:255'],
            
            'password' => ['nullable','max:255'],
            'role' => ['required', Rule::in(['ADMIN','USER'])],
        ];

        $validated = validator($data, $rules)->validate();

        $user->name = $validated['name'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password']; 
        }

        if (!$isSelf) {
            $user->role = $validated['role'];
        }
       

        $user->save();

        return redirect()
            ->route('users.view', ['user' => $user->email])
            ->with('status', "User {$user->email} was updated.");
    }

    public function delete(string $user): RedirectResponse
    {
        Gate::authorize('create', User::class); 
        $target = $this->find($user);

       
        if ($this->currentUser()->id === $target->id) {
            return redirect()
                ->route('users.view', ['user' => $target->email])
                ->with('status', "Cannot delete self-record.");
        }

        $target->delete();

        $back = session()->get('bookmarks.users.view', route('users.list'));
        return redirect($back)->with('status', "User {$user} was deleted.");
    }

    

    public function selfView(): View
    {
        $user = $this->currentUser();

        
        session()->put('bookmarks.users.selves.view', url()->full());

        return view('users.selves.view', compact('user'));
    }

    public function showSelfUpdateForm(): View
    {
        $user = $this->currentUser();
        return view('users.selves.update-form', compact('user'));
    }

    public function selfUpdate(ServerRequestInterface $request): RedirectResponse
{
    $user = $this->currentUser();
    $data = $request->getParsedBody();

    $validated = validator($data, [
        'name'     => ['required','max:255'],
        'password' => ['nullable','max:255'],
    ])->validate();

    $user->name = $validated['name'];
    if (!empty($validated['password'])) {
        $user->password = $validated['password']; 
    }
    $user->save();

    $back = session()->get('bookmarks.users.selves.update-form', route('users.selves.view'));
    return redirect($back)->with('status', 'Your information was updated.');
}

}
