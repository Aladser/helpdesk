@if($slot != '')
<title>{{env('APP_NAME')}} - {{$slot}}</title>
@else
<title>{{env('APP_NAME')}}</title>
@endif
