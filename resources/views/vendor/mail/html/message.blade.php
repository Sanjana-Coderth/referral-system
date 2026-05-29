<x-mail::layout>

{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
<div style="text-align:center;color:white;">

{!! str_replace(
[
    '# Hello!',
    'Click button below to verify your email.',
    'Regards,<br>
Laravel'
],
[
    '',

    '
    <h1 style="
    color:#00e5ff;
    font-size:36px;
    margin-bottom:25px;
    ">
    Verify Your Email
    </h1>

    <p style="
    font-size:16px;
    line-height:1.6;
    color:white;
    ">
    Welcome to Referral System.<br>
    Please verify your email address to activate your account.
    </p>
    ',

    '
    <p style="
    margin-top:30px;
    font-size:15px;
    color:#94a3b8;
    ">
    If you did not create an account, no further action is required.
    </p>
    '

],
$slot) !!}

</div>

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} Referral System. All rights reserved.
</x-mail::footer>
</x-slot:footer>

</x-mail::layout>