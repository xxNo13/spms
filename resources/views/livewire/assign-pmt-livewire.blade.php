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
                                    <td>
                                        @if (str_contains(strtolower($pmt->position), 'vice'))
                                            <select name="vice" id="vice" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a College Vice President</option>
                                                @foreach ($vice_users as $user) 
                                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'finance'))
                                            <select name="finance" id="finance" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a Director of Finance</option>
                                                @foreach ($finance_users as $user) 
                                                    <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'planning'))
                                            <select name="planning" id="planning" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a Director of Planning</option>
                                                @foreach ($planning_users as $user) 
                                                    <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'resource'))
                                            <select name="resource" id="resource" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a Director of Human Resource</option>
                                                @foreach ($resource_users as $user) 
                                                    <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'evaluation'))
                                            <select name="evaluation" id="evaluation" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a Head of Evaluation Comitee</option>
                                                @foreach ($evaluation_users as $user) 
                                                    <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'faculty'))
                                            <select name="faculty" id="faculty" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a Representative of Faculty</option>
                                                @foreach ($faculty_users as $user) 
                                                    <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif (str_contains(strtolower($pmt->position), 'staff'))
                                            <select name="staff" id="staff" class="form-select" wire:model="ids.{{$pmt->id}}" >
                                                <option value="">Select a Representative of Staff</option>
                                                @foreach ($staff_users as $user) 
                                                    <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
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
                </div>
            </div>
        </div>
    </section>
    <x-modals />
</div>