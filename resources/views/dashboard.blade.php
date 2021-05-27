<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8" />
    <title>發噗幫手</title>
</head>

<body>
    <header>
        <img src="{{ $plurkAvatarUrl }}" alt="噗浪大頭貼" />
        <p>{{ $plurkDisplayName }}</p>
        <p>{{ $plurkUserId }}</p>
        <div id="navigation-bar">
            <ul>
                <li><a href="/user/logout">登出</a></li>
            </ul>
        </div>
    </header>
    <div id="latest-plurk">
        <p>最近發的噗：</p>
        <p>{{ $latestPlurk['plurks'][0]['posted'] }}</p>
        <p>{!! $latestPlurk['plurks'][0]['content'] !!}</p>
    </div>
    <div id="post-new-plurk">
        <form action="/plurks" method="post">
            @csrf
            <div>{{ $plurkDisplayName }}
                <select name="qualifier">
                    <option value="">：（自由發揮）</option>
                    <option value="plays">玩</option>
                    <option value="buys">買</option>
                    <option value="sells">賣</option>
                    <option value="loves">愛</option>
                    <option value="likes">喜歡</option>
                    <option value="shares">分享</option>
                    <option value="hates">討厭</option>
                    <option value="wants">想要</option>
                    <option value="wishes">期待</option>
                    <option value="needs">需要</option>
                    <option value="has">已經</option>
                    <option value="will">打算</option>
                    <option value="hopes">希望</option>
                    <option value="asks">問</option>
                    <option value="wonders">好奇</option>
                    <option value="feels">覺得</option>
                    <option value="thinks">想</option>
                    <option value="draws">畫</option>
                    <option value="is">正在</option>
                    <option value="says">說</option>
                    <option value="eats">吃</option>
                    <option value="writes">寫</option>
                    <option value="whispers">偷偷說</option>
                </select>
                <textarea name="content" placeholder="想說些什麼嗎？"></textarea>
            </div>
            <button type="submit">發噗</button>
        </form>
    </div>
</body>

</html>