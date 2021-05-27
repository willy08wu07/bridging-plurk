<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8" />
    <title>發噗幫手</title>
</head>

<body>
    <div>
        <form action="/user/login/plurk" method="post">
            @csrf
            <p>發噗幫手需要噗浪的授權，才可以幫忙在噗浪上發噗。</p>
            <button type="submit">以噗浪登入並授權</button>
        </form>
    </div>
</body>

</html>