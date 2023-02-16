<div>
    @foreach ($account_types as $account_type)
        <option value="{{$account_type->id}}" {{ (collect(old('account_type'))->contains($account_type->id)) ? 'selected':'' }}>{{$account_type->account_type}}</option>
    @endforeach
</div>