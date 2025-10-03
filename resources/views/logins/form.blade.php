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