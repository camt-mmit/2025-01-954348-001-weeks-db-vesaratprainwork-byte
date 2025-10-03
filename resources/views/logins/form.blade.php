<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('common.css') }}">
    <style>
        main { max-width: 420px; margin: 40px auto; padding: 16px; }
        form { display: grid; grid-template-columns: 1fr; row-gap: 10px; }
        label { font-weight: bold; }
        input { padding: 8px; }
        .warn { color: var(--app-warn-color); }
        button { padding: 10px; font-weight: bold; }
    </style>
</head>
<body>
<main id="app-cmp-main-content" class="app-ly-max-width">
    <h1 style="text-align:center;">Please sign in</h1>
    <form action="{{ route('authenticate') }}" method="post">
        @csrf
        <label>
            E-mail
            <input type="email" name="email" required autofocus />
        </label>
        <label>
            Password
            <input type="password" name="password" required />
        </label>
        <button type="submit">Login</button>

        <div class="app-cmp-notifications">
            @error('credentials')
                <div role="alert" class="warn">{{ $message }}</div>
            @enderror
        </div>
    </form>
</main>
</body>
</html>
