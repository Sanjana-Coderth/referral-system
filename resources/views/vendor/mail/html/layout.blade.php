<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{{ config('app.name') }}</title>
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
"
>

{{-- HEADER --}}
{{ $header ?? '' }}

{{-- BODY --}}
<div
style="
color:white;
font-size:16px;
line-height:1.8;
"
>
{!! $slot !!}
</div>

{{-- SUBCOPY --}}
@if (isset($subcopy))
<div
style="
margin-top:15px;
padding-top:8px;
border-top:1px solid #1e293b;
color:#94a3b8;
font-size:14px;
"
>
{!! $subcopy !!}
</div>
@endif

{{-- FOOTER --}}
<div
style="
margin-top:40px;
text-align:center;
color:#64748b;
font-size:13px;
"
>
© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
</div>

</div>

</body>
</html>