<select name="su" id="su">
    <option value="0">===Selec===</option>
    @foreach( \App\User::where('id','!=',auth()->user()->id)->get() as $user)
    <option value="{{$user->id}}">{{$user->name}}</option>
    @endforeach
</select>