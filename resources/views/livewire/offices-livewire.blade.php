<div>
    @foreach ($offices as $office)
        <option value="{{$office->id}}" {{ (collect(old('office'))->contains($office->id)) ? 'selected':'' }}>{{$office->office_name}}</option>
    @endforeach
</div>
