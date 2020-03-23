<hr class="sidebar-divider my-0">
<li class="nav-item {{url()->current()==route('classRecords.indexByRole')?'active':''}}">
  <a class="nav-link" href="{{ route('classRecords.indexByRole') }}">
    <i class="fas fa-fw fa-book-reader"></i>
    <span>{{__('ClassRecords')}}</span>
  </a>
</li>

@role('teacher') 
@else
<hr class="sidebar-divider my-0">
<li class="nav-item {{url()->current()==route('referrals')?'active':''}}">
  <a class="nav-link" href="{{ route('referrals') }}">
    <i class="fas fa-fw fa-share-alt"></i>
    <span>分享</span>
  </a>
</li>

<hr class="sidebar-divider my-0">
<li class="nav-item {{url()->current()==route('students.recommend')?'active':''}}">
  <a class="nav-link" href="{{ route('students.recommend') }}">
    <i class="fab fa-fw fa-slideshare"></i>
    <span>{{__('Recommends')}}</span>
  </a>
</li>
@endrole