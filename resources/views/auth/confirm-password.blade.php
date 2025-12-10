<!DOCTYPE html>
<html>
<head>
    <title>Confirm Password</title>
</head>
<body>
    <h1>Confirm Password</h1>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>
        <div>
            <button type="submit">
                Confirm Password
            </button>
        </div>
    </form>
</body>
</html>