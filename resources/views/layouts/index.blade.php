<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
        <body>
          <h1> layouts</h1>
          @if (!is_null(Auth::user()))
              <p>{{ Auth::user()->name }}</p>
          @endif
          <a href="/layouts/create">レイアウトの投稿</a>
        </body>
</html>