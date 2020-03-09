<li class="nav-item">
    <a class="nav-link" href="{{ route('classRecords.indexByRole') }}">{{__('ClassRecords')}}</a>
</li>
@role('teacher') 
@else
<li class="nav-item">
    <a class="nav-link" href="{{ route('referrals') }}">分享</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('students.recommend') }}">{{__('Recommends')}}</a>
</li>
@endrole

@can('View any Order')
<li class="nav-item">
    <a class="nav-link" href="{{ route('orders.index') }}">{{__('Order')}}</a>
</li>
@endcan

@can('View any Student')
<li class="nav-item">
    <a class="nav-link" href="{{ route('students.index') }}">{{__('Students')}}</a>
</li>
@endcan

@can('View any ClassRecord')
<li class="nav-item">
    <a class="nav-link" href="{{ route('classRecords.index') }}">{{__('ClassRecords')}}</a>
</li>
@endcan