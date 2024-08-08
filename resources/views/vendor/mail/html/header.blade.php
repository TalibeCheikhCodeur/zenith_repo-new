@props(['url'])

<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="https://media.licdn.com/dms/image/C5616AQFQW0tgDkr0Wg/profile-displaybackgroundimage-shrink_200_800/0/1553025781056?e=2147483647&v=beta&t=3OJhf8Y-SYLBd7fR3I-lU2V0vqoxBmhY9ViWtE6kFKE" width="565" height="150"  alt="My logo">
@endif
</a>
</td>
</tr>