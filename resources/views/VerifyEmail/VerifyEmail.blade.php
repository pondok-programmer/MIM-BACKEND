<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>email verifikasi</h1>
    <h1>{{ $users->name }}</h1>
    <a href="{{ $verification }}">tap here</a>
    <h2>atau salin link ini</h2>
    <h4>{{ Illuminate\Mail\Markdown::parse($verification) }}</h4>
</body>
</html>