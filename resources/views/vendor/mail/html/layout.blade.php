<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{{ config('app.name') }}</title>
</head>

<body style="
margin:0;
padding:40px 60px;
background:#020617;
font-family:Arial,sans-serif;
">

<div style="
max-width:850px;
margin:auto;
background:#07122b;
border-radius:28px;
padding:40px 60px 40px 60px;
border:1px solid rgba(0,255,255,0.12);
box-shadow:0 0 30px rgba(0,255,255,0.10);
">

{{ $header ?? '' }}

<div style="
color:white;
font-size:16px;
line-height:1.8;
text-align:center;
">
{!! $slot !!}
</div>

@if (isset($subcopy))
<div style="
margin-top:30px;
padding-top:20px;
border-top:1px solid rgba(255,255,255,0.08);
color:#94a3b8;
font-size:13px;
line-height:1.8;
word-break:break-all;
">
{!! $subcopy !!}
</div>
@endif

<div style="
margin-top:30px;
text-align:center;
font-size:13px;
color:#64748b;
">
{{ $footer ?? '' }}
</div>

</div>

</body>
</html>