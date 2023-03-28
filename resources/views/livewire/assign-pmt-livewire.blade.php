<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Assigning of PMT</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">PMT</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section pt-3">
        <div class="card">
            <div class="card-header hstack">
                <button class="btn btn-outline-primary ms-auto" wire:click="save">Save</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-lg text-center">
                        <thead>
                            <tr>
                                <th>PMT POSITION</th>
                                <th>USER</th>
                                <th>HEAD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pmts as $pmt)
                                <tr>
                                    <td>
                                        {{ $pmt->position }}
                                    </td>
                                    <td wire:ignore>
                                        @if (str_contains(strtolower($pmt->position), 'vice'))
                                            <select style="width: 75%;" name="vice" id="vice" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'finance'))
                                            <select style="width: 75%;" name="finance" id="finance" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'planning'))
                                            <select style="width: 75%;" name="planning" id="planning" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'resource'))
                                            <select style="width: 75%;" name="resource" id="resource" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'evaluation'))
                                            <select style="width: 75%;" name="evaluation" id="evaluation" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'faculty'))
                                            <select style="width: 75%;" name="faculty" id="faculty" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($faculty_users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'staff'))
                                            <select style="width: 75%;" name="staff" id="staff" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option></option>
                                                @foreach ($staff_users as $user) 
                                                    <option value="{{ $user['id'] }}" @if ($user['id'] == $pmt->user_id) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="hstack justify-content-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="radio" name="isHead" value="{{ $pmt->id }}" wire:model="isHead">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @push('script')
                        <script>
                            $('#vice').select2({
                                placeholder: "Select an Position for Vice President",
                            });
                            $('#vice').on('change', function () {
                                var data = $('#vice').select2("val");
                                @this.set('ids.1', data);
                            });

                            $('#finance').select2({
                                placeholder: "Select an Position for Director of Finance",
                            });
                            $('#finance').on('change', function () {
                                var data = $('#finance').select2("val");
                                @this.set('ids.2', data);
                            });

                            $('#planning').select2({
                                placeholder: "Select an Position for Director of Planning",
                            });
                            $('#planning').on('change', function () {
                                var data = $('#planning').select2("val");
                                @this.set('ids.3', data);
                            });

                            
                            $('#resource').select2({
                                placeholder: "Select an Position for Director of Human Resource",
                            });
                            $('#resource').on('change', function () {
                                var data = $('#resource').select2("val");
                                @this.set('ids.4', data);
                            });

                            $('#evaluation').select2({
                                placeholder: "Select an Position for Head of Evaluation Comitee",
                            });
                            $('#evaluation').on('change', function () {
                                var data = $('#evaluation').select2("val");
                                @this.set('ids.5', data);
                            });

                            $('#faculty').select2({
                                placeholder: "Select an Position for Representative of Faculty",
                            });
                            $('#faculty').on('change', function () {
                                var data = $('#faculty').select2("val");
                                @this.set('ids.6', data);
                            });

                            $('#staff').select2({
                                placeholder: "Select an Position for Representative of Staff",
                            });
                            $('#staff').on('change', function () {
                                var data = $('#staff').select2("val");
                                @this.set('ids.7', data);
                            });
                        </script>
                    @endpush
                </div>
            </div>
        </div>
    </section>
    <x-modals />
</div>