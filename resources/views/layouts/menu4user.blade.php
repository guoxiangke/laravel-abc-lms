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