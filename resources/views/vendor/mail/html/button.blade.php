@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])

<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">

<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}" role="presentation">

<a
href="{{ $url }}"
target="_blank"
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
{{ $slot }}
</a>

</td>
</tr>
</table>

</td>
</tr>
</table>