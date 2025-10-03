
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />

    <title>Login</title>
</head>

<body>
    <main id="app-cmp-main-content" style="width: min(400px, 100%);">
        <header>
            <h1>Login</h1>
        </header>

        <form action="{{ route('authenticate') }}" method="post">
            @csrf

            <div class="app-cmp-form-detail">
                <label for="app-inp-email">Email</label>
                <input type="email" id="app-inp-email" name="email" required />

                <label for="app-inp-password">Password</label>
                <input type="password" id="app-inp-password" name="password" required />
            </div>

            <div class="app-cmp-form-actions">
                <button type="submit">Login</button>
            </div>

            <div class="app-cmp-notifications" style="margin-top: 1em;">
                @error('credentials')
                    <div role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </form>
    </main>

    <footer id="app-cmp-main-footer">
        &#xA9; Copyright Pachara's Database.
    </footer>
</body>

</html>
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
