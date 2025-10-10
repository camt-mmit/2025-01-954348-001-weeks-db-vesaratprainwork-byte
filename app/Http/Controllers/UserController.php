<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\QueryException;

class UserController extends SearchableController
{
    public const MAX_ITEMS = 10;

    #[\Override]
    public function getQuery(): Builder
    {
        return User::query()->orderBy('id');
    }

    
    #[\Override]
    function find(string $email): User
    {
        return $this->getQuery()->where('email', $email)->firstOrFail();
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

    
    public function filter(Builder|Relation $query, array $criteria): Builder|Relation
    {
        $term = trim((string)($criteria['term'] ?? ''));

        if ($term !== '') {
            $query->where(static function ($q) use ($term) {
                $q->where('email', 'like', "%{$term}%")
                    ->orWhere('name',  'like', "%{$term}%")
                    ->orWhere('role',  'like', "%{$term}%");
            });
        }

        return $query;
    }

    public function list(ServerRequestInterface $request): View
    {
        Gate::authorize('list', User::class);

        $criteria = $this->prepareCriteria($request->getQueryParams());
        $users = $this->filter($this->getQuery(), $criteria)->paginate(self::MAX_ITEMS);
        session()->put('bookmarks.users.view', url()->full());
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

        try {
            $data = validator($request->getParsedBody(), [
                'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
                'name'     => ['required', 'max:255'],
                'password' => ['required', 'max:255'],
                'role'     => ['required', Rule::in(['ADMIN', 'USER'])],
            ])->validate();

            $user = new User();
            $user->email = $data['email'];
            $user->name  = $data['name'];
            $user->password = ($data['password']);
            $user->role  = $data['role'];
            $user->email_verified_at = now();
            $user->remember_token = '';
            $user->save();

            return redirect()
                ->route('users.list')
                ->with('status', "User {$user->email} was created.");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withInput()->withErrors(['alert' => $errorMessage]);
        }
    }

    public function view(string $user): View
    {
        $user = $this->find($user);
        Gate::authorize('view', $user);

        $back = session('bookmarks.users.view');

        if (!$back || $back === url()->current()) {
            $prev = url()->previous();
            $back = ($prev && parse_url($prev, PHP_URL_PATH) === '/users')
                ? $prev
                : route('users.list');
        }

        return view('users.view', compact('user', 'back'));
    }

    public function showUpdateForm(string $user): View
    {
        $user = $this->find($user);
        Gate::authorize('update', $user);

        return view('users.update-form', compact('user'));
    }

    public function update(ServerRequestInterface $request, string $user): RedirectResponse
    {
        $user = $this->find($user);
        Gate::authorize('update', $user);

        try {
            $isSelf = $this->currentUser()->id === $user->id;
            $data = $request->getParsedBody();

            $rules = [
                'name'     => ['required', 'max:255'],
                'password' => ['nullable', 'max:255'],
                'role'     => ['required', Rule::in(['ADMIN', 'USER'])],
            ];
            $validated = validator($data, $rules)->validate();

            $user->name = $validated['name'];

            if (!empty($validated['password'])) {
                $user->password = ($validated['password']);
            }

            if (!$isSelf) {
                $user->role = $validated['role'];
            }

            $user->save();

            return redirect()
                ->route('users.view', ['user' => $user->email])
                ->with('status', "User {$user->email} was updated.");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withInput()->withErrors(['alert' => $errorMessage]);
        }
    }

    public function delete(string $user): RedirectResponse
    {
        $target = $this->find($user);
        Gate::authorize('delete', $target);

        try {
            if ($this->currentUser()->id === $target->id) {
                return redirect()
                    ->route('users.view', ['user' => $target->email])
                    ->withErrors(['alert' => 'Cannot delete self-record.']);
            }

            $target->delete();

            $back = session()->get('bookmarks.users.view', route('users.list'));
            return redirect($back)->with('status', "User {$user} was deleted.");
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withErrors(['alert' => $errorMessage]);
        }
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

        try {
            $validated = validator($request->getParsedBody(), [
                'name'     => ['required', 'max:255'],
                'password' => ['nullable', 'max:255'],
            ])->validate();

            $user->name = $validated['name'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $back = session()->get('bookmarks.users.selves.view', route('users.selves.view'));
            return redirect($back)->with('status', 'Your information was updated.');
        } catch (\Throwable $excp) {
            $errorMessage = ($excp instanceof QueryException) ? $excp->errorInfo[2] : $excp->getMessage();
            return redirect()->back()->withInput()->withErrors(['alert' => $errorMessage]);
        }
    }
}