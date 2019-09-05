<div class="d-inline">
  @php
    $filterValue = '';
    $filter = request('filter');
    if($filter)
      $filterValue = array_pop($filter);
  @endphp
  <input type="text" name="studentName" id="studentName" class="form-control" style="width: auto; display: inline-block;" @if($filterValue) value="{{$filterValue}}" @else placeholder="Search" @endif>
  <button class="btn btn-warning" id="search">搜索</button>
  @if($filterValue) 
  <button class="btn btn-warning" id="reset">Reset</button>
  @endif
</div>