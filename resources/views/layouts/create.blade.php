<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
        @vite(['resources/js/create.js'])
    </head>
        <body>
            <button id="submit">投稿</button>
            <form id="form" action="{{ route('layouts.store') }}" method="POST">
                @csrf
                <input name="image" id="image" value="imagedesu">
                <div style="display: flex;">
                <textarea id="html-editor" name="html" placeholder="Enter HTML here..." style="width: 50%; height: 300px;"></textarea>
                <textarea id="css-editor" name="css" placeholder="Enter CSS here..." style="width: 50%; height: 300px;"></textarea>
                </div>
                
            </form>
            <iframe id="preview" style="width: 100%; height: 300px; border: 1px solid #ccc;"></iframe>

            <div class="footer">
                <a href="/layouts">戻る</a>
            </div>
        </body>    
</html>