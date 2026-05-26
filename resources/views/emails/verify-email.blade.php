<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
</head>

<body
    style="
        margin:0;
        padding:40px;
        background:#050816;
        font-family:Arial,sans-serif;
    "
>

<div
    style="
        max-width:650px;
        margin:auto;
        background:#0f172a;
        border-radius:24px;
        padding:50px;
        border:1px solid rgba(0,255,255,0.12);
        box-shadow:0 0 30px rgba(0,255,255,0.12);
        text-align:center;
    "
>

    <img
    src="{{ url('logo/logo.png') }}"
    width="240"
    style="
        display:block;
        margin:auto auto 35px;
    "
>

    <h1
        style="
            color:#00e5ff;
            font-size:34px;
            margin-bottom:20px;
            font-weight:bold;
        "
    >
        Verify Your Email
    </h1>

    <p
        style="
            color:#cbd5e1;
            font-size:17px;
            line-height:1.8;
            margin-bottom:35px;
        "
    >
        Welcome to Referral System.<br>
        Please verify your email address to activate your account.
    </p>

    <a
        href="{{ $url }}"
        style="
            background:linear-gradient(135deg,#00f5c3,#009dff);
            padding:16px 36px;
            border-radius:14px;
            color:#ffffff;
            text-decoration:none;
            font-weight:bold;
            display:inline-block;
            font-size:16px;
            box-shadow:0 0 20px rgba(0,255,255,0.25);
        "
    >
        Verify Email
    </a>

    <div
        style="
            margin-top:40px;
            color:#94a3b8;
            font-size:14px;
            line-height:1.7;
        "
    >
        If you did not create an account, no further action is required.
    </div>

    <div
    style="
        margin-top:40px;
        padding-top:25px;
        border-top:1px solid rgba(255,255,255,0.1);
        color:#94a3b8;
        font-size:13px;
        line-height:1.8;
        text-align:left;
        word-break:break-all;
    "
>
    If you're having trouble clicking the button, copy and paste the URL below into your browser:

    <br><br>

    <a
        href="{{ $url }}"
        style="
            color:#00e5ff;
            text-decoration:none;
        "
    >
        {{ $url }}
    </a>
</div>

</div>

</body>
</html>