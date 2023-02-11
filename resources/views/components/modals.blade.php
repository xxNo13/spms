<div>
    @if (isset($selected))
        {{-- Add Output/Suboutput/Target Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddIPCROSTModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Add Output/Suboutput/Target</h4>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            @if ($userType == 'faculty' && isset($institutes))
                                <div class="mt-3 form-group d-flex justify-content-b">
                                    <select placeholder="Select Institute" class="form-control"
                                        wire:model="instituteId">
                                        <option value="null">All Faculty</option>
                                        @foreach ($institutes as $institute)
                                            <option value="{{ $institute->id }}">
                                                {{ $institute->institute }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <hr>
                            @endif

                            <div class="mt-3 form-group d-flex justify-content-between">
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="output"
                                        value="sub_funct" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="sub_funct">
                                        Sub Function
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="output"
                                        value="output" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="output">
                                        Output
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="suboutput"
                                        value="suboutput" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="suboutput">
                                        Suboutput
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="target"
                                        value="target" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="target">
                                        Target
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <div class="mt-3">
                                @if ($selected == 'sub_funct')
                                    <label>Sub Function: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Sub Function" class="form-control"
                                            wire:model="sub_funct">
                                        @error('sub_funct')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'output')
                                    <label>Sub Function (Optional): </label>
                                    <div class="form-group">
                                        <select placeholder="Sub Function" class="form-control"
                                            wire:model="sub_funct_id">
                                            <option value="">Select a Sub Function</option>
                                            @if ($userType == 'faculty')
                                                @foreach ($subFuncts as $sub_funct)
                                                    @if (($functs->currentPage() == 1 && $sub_funct->funct_id == 1) || ($functs->currentPage() == 2 && $sub_funct->funct_id == 2) || ($functs->currentPage() == 3 && $sub_funct->funct_id == 3))
                                                        @if ($sub_funct->institute_id == $instituteId && $sub_funct->type == 'ipcr' && $sub_funct->duration_id == $duration->id && $sub_funct->user_type == $userType)
                                                            <option value="{{ $sub_funct->id }}">
                                                                {{ $sub_funct->sub_funct }}</option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @elseif ($userType == 'facult')
                                                @foreach (Auth::user()->subFuncts as $sub_funct)
                                                    @if (($functs->currentPage() == 1 && $sub_funct->funct_id == 1) || ($functs->currentPage() == 2 && $sub_funct->funct_id == 2) || ($functs->currentPage() == 3 && $sub_funct->funct_id == 3))
                                                        @if ($sub_funct->isDesignated && $sub_funct->type == 'ipcr' && $sub_funct->duration_id == $duration->id && $sub_funct->user_type == 'faculty')
                                                            <option value="{{ $sub_funct->id }}">{{ $sub_funct->sub_funct }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach (Auth::user()->subFuncts as $sub_funct)
                                                    @if (($functs->currentPage() == 1 && $sub_funct->funct_id == 1) || ($functs->currentPage() == 2 && $sub_funct->funct_id == 2) || ($functs->currentPage() == 3 && $sub_funct->funct_id == 3))
                                                        @if ($sub_funct->type == 'ipcr' && $sub_funct->duration_id == $duration->id && $sub_funct->user_type == $userType)
                                                            <option value="{{ $sub_funct->id }}">{{ $sub_funct->sub_funct }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('output_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Output: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Output" class="form-control" name="output"
                                            wire:model="output">
                                        @error('output')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'suboutput')
                                    <label>Output: </label>
                                    <div class="form-group">
                                        <select placeholder="Output" class="form-control" wire:model="output_id"
                                            required>
                                            <option value="">Select an output</option>
                                            @php
                                                $number = 1;
                                            @endphp
                                            @if ($userType == 'faculty')
                                                @foreach ($outputs as $output)
                                                    @if ($output->institute_id == $instituteId && ($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                        @forelse ($output->targets as $target)
                                                        @empty
                                                            <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} 
                                                                {{ $output->output }}
                                                            </option>
                                                        @endforelse
                                                    @endif
                                                @endforeach
                                            @elseif ($userType == 'facult')
                                                @foreach (Auth::user()->outputs as $output)
                                                    @if (($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                        @forelse ($output->targets as $target)
                                                        @empty
                                                            @if ($output->isDesignated && $output->type == 'ipcr' && $output->duration_id == $duration->id && $output->user_type == 'faculty')
                                                                <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} 
                                                                    {{ $output->output }}
                                                                </option>
                                                            @endif
                                                        @endforelse
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach (Auth::user()->outputs as $output)
                                                    @if (($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                        @forelse ($output->targets as $target)
                                                        @empty
                                                            @if ($output->type == 'ipcr' && $output->duration_id == $duration->id && $output->user_type == $userType)
                                                                <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} 
                                                                    {{ $output->output }}
                                                                </option>
                                                            @endif
                                                        @endforelse
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('output_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Suboutput: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Suboutput" class="form-control"
                                            name="suboutput" wire:model="suboutput">
                                        @error('suboutput')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'target')
                                    <label>Output/Suboutput: </label>
                                    <div class="form-group">
                                        <select placeholder="Output/Suboutput" class="form-control" wire:model="subput"
                                            required>
                                            <option value="">Select an/a output/suboutput</option>
                                            @php
                                                $number = 1;
                                            @endphp
                                            @if ($userType == 'faculty')
                                                @foreach ($outputs as $output)
                                                    @if ($output->institute_id == $instituteId && ($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                        @forelse ($output->suboutputs as $suboutput)
                                                            <option value="suboutput, {{ $suboutput->id }}">
                                                                {{ $suboutput->output->code }} {{ $number++ }}
                                                                {{ $suboutput->output->output }} -
                                                                {{ $suboutput->suboutput }}
                                                            </option>
                                                        @empty
                                                            <option value="output, {{ $output->id }}">
                                                                {{ $output->code }} {{ $number++ }}
                                                                {{ $output->output }}
                                                            </option>
                                                        @endforelse
                                                    @endif
                                                @endforeach
                                            @elseif ($userType == 'facult')
                                                @foreach (Auth::user()->outputs as $output)
                                                    @if ($output->isDesignated && $output->type == 'ipcr' && $output->duration_id == $duration->id && $output->user_type == 'faculty')
                                                        @if (($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                            @forelse ($output->suboutputs as $suboutput)
                                                                <option value="suboutput, {{ $suboutput->id }}">
                                                                    {{ $suboutput->output->code }} {{ $number++ }}
                                                                    {{ $suboutput->output->output }} -
                                                                    {{ $suboutput->suboutput }}
                                                                </option>
                                                            @empty
                                                                    <option value="output, {{ $output->id }}">
                                                                        {{ $output->code }} {{ $number++ }}
                                                                        {{ $output->output }}
                                                                    </option>
                                                            @endforelse
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach (Auth::user()->outputs as $output)
                                                    @if ($output->type == 'ipcr' && $output->duration_id == $duration->id && $output->user_type == $userType)
                                                        @if (($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                            @forelse ($output->suboutputs as $suboutput)
                                                                <option value="suboutput, {{ $suboutput->id }}">
                                                                    {{ $suboutput->output->code }} {{ $number++ }}
                                                                    {{ $suboutput->output->output }} -
                                                                    {{ $suboutput->suboutput }}
                                                                </option>
                                                            @empty
                                                                    <option value="output, {{ $output->id }}">
                                                                        {{ $output->code }} {{ $number++ }}
                                                                        {{ $output->output }}
                                                                    </option>
                                                            @endforelse
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('subput')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @if ($userType != 'faculty' && $userType != 'facult')
                                        <label>Target Output: </label>
                                        <div class="form-group">
                                            <input type="text" placeholder="Target Output" class="form-control"
                                                name="target_output" wire:model="target_output">
                                            @error('target_output')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                    <label>Target: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Target" class="form-control"
                                            name="target" wire:model="target">
                                        @error('target')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @if ($userType == 'faculty')
                                        <div class="form-group hstack gap-2">
                                            <input type="checkbox" class="form-check-glow form-check-input form-check-primary"
                                                name="required" wire:model="required">
                                            <label>Required to all Faculty</label>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Output/Suboutput/Target Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditIPCROSTModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Edit Output/Suboutput/Target</h4>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="mt-3">
                                @if ($selected == 'sub_funct')
                                    <label>Sub Function: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Sub Function" class="form-control"
                                            wire:model="sub_funct">
                                        @error('sub_funct')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'output')
                                    <label>Output: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Output" class="form-control" name="output"
                                            wire:model="output">
                                        @error('output')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'suboutput')
                                    <label>Suboutput: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Suboutput" class="form-control"
                                            name="suboutput" wire:model="suboutput">
                                        @error('suboutput')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'target')
                                    @if ($userType != 'faculty' && $userType != 'facult')
                                        <label>Target Output: </label>
                                        <div class="form-group">
                                            <input type="text" placeholder="Target Output" class="form-control"
                                                name="target_output" wire:model="target_output">
                                            @error('target_output')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                    <label>Target: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Target" class="form-control"
                                            name="target" wire:model="target">
                                        @error('target')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @if ($userType == 'faculty')
                                        <div class="form-group hstack gap-2">
                                            <input type="checkbox" class="form-check-glow form-check-input form-check-primary"
                                                name="required" wire:model="required">
                                            <label>Required to all Faculty</label>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Add Output/Suboutput/Target Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddOPCROSTModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Add Output/Suboutput/Target</h4>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="mt-3 form-group d-flex justify-content-between">
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="output"
                                        value="sub_funct" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="sub_funct">
                                        Sub Function
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="output"
                                        value="output" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="output">
                                        Output
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="suboutput"
                                        value="suboutput" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="suboutput">
                                        Suboutput
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="changed" type="radio" class="form-check-input" id="target"
                                        value="target" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="target">
                                        Target
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <div class="mt-3">
                                @if ($selected == 'sub_funct')
                                    <label>Sub Function: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Sub Function" class="form-control"
                                            wire:model="sub_funct">
                                        @error('sub_funct')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'output')
                                    <label>Sub Function (Optional): </label>
                                    <div class="form-group">
                                        <select placeholder="Sub Function" class="form-control"
                                            wire:model="sub_funct_id">
                                            <option value="">Select a Sub Function</option>
                                            @if (isset($subFuncts))
                                                @foreach ($subFuncts as $sub_funct)
                                                    @if (($functs->currentPage() == 1 && $sub_funct->funct_id == 1) || ($functs->currentPage() == 2 && $sub_funct->funct_id == 2) || ($functs->currentPage() == 3 && $sub_funct->funct_id == 3))
                                                        @if ($sub_funct->type == 'opcr' && $sub_funct->duration_id == $duration->id && $sub_funct->user_type == $userType)
                                                            <option value="{{ $sub_funct->id }}">{{ $sub_funct->sub_funct }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('output_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Output: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Output" class="form-control" name="output"
                                            wire:model="output">
                                        @error('output')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'suboutput')
                                    <label>Output: </label>
                                    <div class="form-group">
                                        <select placeholder="Output" class="form-control" wire:model="output_id"
                                            required>
                                            <option value="">Select an output</option>
                                            @if (isset($outputs))
                                                @php 
                                                    $number = 1;
                                                    $prev = '';
                                                @endphp
                                                @foreach ($outputs as $output)
                                                    @if (($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                        @forelse ($output->targets as $target)
                                                        @empty
                                                            @if ($output->type == 'opcr' && $output->duration_id == $duration->id && $output->user_type == $userType)
                                                                <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} 
                                                                    {{ $output->output }}
                                                                </option>
                                                            @endif
                                                        @endforelse
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('output_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Suboutput: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Suboutput" class="form-control"
                                            name="suboutput" wire:model="suboutput">
                                        @error('suboutput')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'target')
                                    <label>Output/Suboutput: </label>
                                    <div class="form-group">
                                        <select placeholder="Output/Suboutput" class="form-control" wire:model="subput"
                                            required>
                                            <option value="">Select an/a output/suboutput</option>
                                            @if (isset($outputs) && isset($suboutputs))
                                                @php 
                                                    $number = 1;
                                                    $prev = '';
                                                @endphp
                                                @foreach ($outputs as $output)
                                                    @if ($output->type == 'opcr' && $output->duration_id == $duration->id && $output->user_type == $userType)
                                                        @if (($functs->currentPage() == 1 && $output->code == "CF ") || ($functs->currentPage() == 2 && $output->code == "STF ") || ($functs->currentPage() == 3 && $output->code == "SF "))
                                                            @forelse ($output->suboutputs as $suboutput)
                                                                <option value="suboutput, {{ $suboutput->id }}">
                                                                    {{ $suboutput->output->code }} {{ $number++ }}
                                                                    {{ $suboutput->output->output }} -
                                                                    {{ $suboutput->suboutput }}
                                                                </option>
                                                            @empty
                                                                <option value="output, {{ $output->id }}">
                                                                    {{ $output->code }} {{$number++}}
                                                                    {{ $output->output }}
                                                                </option>
                                                            @endforelse
                                                         @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('subput')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Target: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Target" class="form-control"
                                            name="target" wire:model="target">
                                        @error('target')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Output/Suboutput/Target Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditOPCROSTModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Edit Output/Suboutput/Target</h4>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="mt-3">
                                @if ($selected == 'sub_funct')
                                    <label>Sub Function: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Sub Function" class="form-control"
                                            wire:model="sub_funct">
                                        @error('sub_funct')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'output')
                                    <label>Output: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Output" class="form-control" name="output"
                                            wire:model="output">
                                        @error('output')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'suboutput')
                                    <label>Suboutput: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Suboutput" class="form-control"
                                            name="suboutput" wire:model="suboutput">
                                        @error('suboutput')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @elseif ($selected == 'target')
                                    <label>Target: </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Target" class="form-control"
                                            name="target" wire:model="target">
                                        @error('target')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="DeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Delete Modal</h4>
                </div>
                <form wire:submit.prevent="delete">
                    <div class="modal-body">
                        <p>You sure you want to delete?</p>
                        <p>Can't recover data once you delete it!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-danger ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Delete</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (isset($type) && isset($selectedTarget))
        {{-- Add Rating Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddRatingModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Add Rating</h4>
                    </div>

                    <form wire:submit.prevent="saveRating('{{ 'add' }}')">
                        <div class="modal-body">
                            @if (isset($selectedTarget->target_output))
                                <label>Output Finished (Target Output is "{{ $selectedTarget->target_output }}"): </label>
                                <div class="form-group">
                                    <input type="number" placeholder="Output Finished" class="form-control"
                                        wire:model="output_finished" max="{{ $selectedTarget->target_output }}">
                                    @error('output_finished')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <label>Efficiency: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="efficiency">
                                    <option value="">Efficiency</option>
                                    @if (isset($selectedTarget->standard->eff_1)) 
                                        <option value="1">1 - {{ $selectedTarget->standard->eff_1 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_2)) 
                                        <option value="2">2 - {{ $selectedTarget->standard->eff_2 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_3)) 
                                        <option value="3">3 - {{ $selectedTarget->standard->eff_3 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_4))
                                        <option value="4">4 - {{ $selectedTarget->standard->eff_4 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_5))
                                        <option value="5">5 - {{ $selectedTarget->standard->eff_5 }}</option>
                                    @endif
                                </select>
                                @error('efficiency')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Quality: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="quality">
                                    <option value="">Quality</option>
                                    @if (isset($selectedTarget->standard->qua_1)) 
                                        <option value="1">1 - {{ $selectedTarget->standard->qua_1 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_2))
                                        <option value="2">2 - {{ $selectedTarget->standard->qua_2 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_3))
                                        <option value="3">3 - {{ $selectedTarget->standard->qua_3 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_4)) 
                                        <option value="4">4 - {{ $selectedTarget->standard->qua_4 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_5)) 
                                        <option value="5">5 - {{ $selectedTarget->standard->qua_5 }}</option>
                                    @endif
                                </select>
                                @error('quality')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Timeliness: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="timeliness">
                                    <option value="">Timeliness</option>
                                    @if (isset($selectedTarget->standard->time_1))
                                        <option value="1">1 - {{ $selectedTarget->standard->time_1 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_2)) 
                                        <option value="2">2 - {{ $selectedTarget->standard->time_2 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_3)) 
                                        <option value="3">3 - {{ $selectedTarget->standard->time_3 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_4)) 
                                        <option value="4">4 - {{ $selectedTarget->standard->time_4 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_4)) 
                                        <option value="5">5 - {{ $selectedTarget->standard->time_5 }}</option>
                                    @endif
                                </select>
                                @error('timeliness')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Rating Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditRatingModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Edit Rating</h4>
                    </div>

                    <form wire:submit.prevent="saveRating('{{ 'edit' }}')">
                        <div class="modal-body">
                            @if (isset($selectedTarget->target_output))
                                <label>Output Finished (Target Output is "{{ $selectedTarget->target_output }}"): </label>
                                <div class="form-group">
                                    <input type="number" placeholder="Output Finished" class="form-control"
                                        wire:model="output_finished" max="{{ $selectedTarget->target_output }}">
                                    @error('output_finished')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <label>Efficiency: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="efficiency">
                                    <option value="">Efficiency</option>
                                    @if (isset($selectedTarget->standard->eff_1)) 
                                        <option value="1">1 - {{ $selectedTarget->standard->eff_1 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_2)) 
                                        <option value="2">2 - {{ $selectedTarget->standard->eff_2 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_3)) 
                                        <option value="3">3 - {{ $selectedTarget->standard->eff_3 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_4))
                                        <option value="4">4 - {{ $selectedTarget->standard->eff_4 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->eff_5))
                                        <option value="5">5 - {{ $selectedTarget->standard->eff_5 }}</option>
                                    @endif
                                </select>
                                @error('efficiency')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Quality: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="quality">
                                    <option value="">Quality</option>
                                    @if (isset($selectedTarget->standard->qua_1)) 
                                        <option value="1">1 - {{ $selectedTarget->standard->qua_1 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_2))
                                        <option value="2">2 - {{ $selectedTarget->standard->qua_2 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_3))
                                        <option value="3">3 - {{ $selectedTarget->standard->qua_3 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_4)) 
                                        <option value="4">4 - {{ $selectedTarget->standard->qua_4 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->qua_5)) 
                                        <option value="5">5 - {{ $selectedTarget->standard->qua_5 }}</option>
                                    @endif
                                </select>
                                @error('quality')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Timeliness: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="timeliness">
                                    <option value="">Timeliness</option>
                                    @if (isset($selectedTarget->standard->time_1))
                                        <option value="1">1 - {{ $selectedTarget->standard->time_1 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_2)) 
                                        <option value="2">2 - {{ $selectedTarget->standard->time_2 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_3)) 
                                        <option value="3">3 - {{ $selectedTarget->standard->time_3 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_4)) 
                                        <option value="4">4 - {{ $selectedTarget->standard->time_4 }}</option>
                                    @endif
                                    @if (isset($selectedTarget->standard->time_4)) 
                                        <option value="5">5 - {{ $selectedTarget->standard->time_5 }}</option>
                                    @endif
                                </select>
                                @error('timeliness')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Add Standard Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddStandardModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Standard</h4>
                </div>
                <form wire:submit.prevent="save('{{ 'add' }}')">
                    <div class="modal-body">
                        <div class="d-flex justify-content-around gap-2">
                            <div class="w-100 text-center">Efficiency: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Quality: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Timeliness: </div>
                        </div>
                        <hr>
                        @php
                            $effs = [];
                            $quas = [];
                            $times = [];
                            if (isset($standardValue)) {
                                $effs = preg_split('/\r\n|\r|\n/', $standardValue->efficiency);
                                $quas = preg_split('/\r\n|\r|\n/', $standardValue->quality);
                                $times = preg_split('/\r\n|\r|\n/', $standardValue->timeliness);
                            }
                        @endphp
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">5:</h5>
                                <select type="text" class="form-control" wire:model="eff_5">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">5:</h5>
                                <select type="text" class="form-control" wire:model="qua_5">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">5:</h5>
                                <select type="text" class="form-control" wire:model="time_5">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">4:</h5>
                                <select type="text" class="form-control" wire:model="eff_4">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">4:</h5>
                                <select type="text" class="form-control" wire:model="qua_4">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">4:</h5>
                                <select type="text" class="form-control" wire:model="time_4">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">3:</h5>
                                <select type="text" class="form-control" wire:model="eff_3">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">3:</h5>
                                <select type="text" class="form-control" wire:model="qua_3">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">3:</h5>
                                <select type="text" class="form-control" wire:model="time_3">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">2:</h5>
                                <select type="text" class="form-control" wire:model="eff_2">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">2:</h5>
                                <select type="text" class="form-control" wire:model="qua_2">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">2:</h5>
                                <select type="text" class="form-control" wire:model="time_2">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">1:</h5>
                                <select type="text" class="form-control" wire:model="eff_1">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">1:</h5>
                                <select type="text" class="form-control" wire:model="qua_1">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">1:</h5>
                                <select type="text" class="form-control" wire:model="time_1">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Standard Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditStandardModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Standard</h4>
                </div>
                <form wire:submit.prevent="save('{{ 'edit' }}')">
                    <div class="modal-body">
                        <div class="d-flex justify-content-around gap-2">
                            <div class="w-100 text-center">Efficiency: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Quality: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Timeliness: </div>
                        </div>
                        <hr>
                        @php
                            $effs = [];
                            $quas = [];
                            $times = [];
                            if (isset($standardValue)) {
                                $effs = preg_split('/\r\n|\r|\n/', $standardValue->efficiency);
                                $quas = preg_split('/\r\n|\r|\n/', $standardValue->quality);
                                $times = preg_split('/\r\n|\r|\n/', $standardValue->timeliness);
                            }
                        @endphp
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">5:</h5>
                                <select type="text" class="form-control" wire:model="eff_5">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">5:</h5>
                                <select type="text" class="form-control" wire:model="qua_5">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">5:</h5>
                                <select type="text" class="form-control" wire:model="time_5">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">4:</h5>
                                <select type="text" class="form-control" wire:model="eff_4">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">4:</h5>
                                <select type="text" class="form-control" wire:model="qua_4">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">4:</h5>
                                <select type="text" class="form-control" wire:model="time_4">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">3:</h5>
                                <select type="text" class="form-control" wire:model="eff_3">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">3:</h5>
                                <select type="text" class="form-control" wire:model="qua_3">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">3:</h5>
                                <select type="text" class="form-control" wire:model="time_3">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">2:</h5>
                                <select type="text" class="form-control" wire:model="eff_2">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">2:</h5>
                                <select type="text" class="form-control" wire:model="qua_2">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">2:</h5>
                                <select type="text" class="form-control" wire:model="time_2">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">1:</h5>
                                <select type="text" class="form-control" wire:model="eff_1">
                                    <option value="null"></option>
                                    @foreach ($effs as $eff)
                                        <option value="{{ $eff }}">{{ $eff }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">1:</h5>
                                <select type="text" class="form-control" wire:model="qua_1">
                                    <option value="null"></option>
                                    @foreach ($quas as $qua)
                                        <option value="{{ $qua }}">{{ $qua }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vr"></div>
                            <div class="hstack gap-4 w-100">
                                <h5 class="my-auto">1:</h5>
                                <select type="text" class="form-control" wire:model="time_1">
                                    <option value="null"></option>
                                    @foreach ($times as $time)
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (isset($users1) && isset($users2))
        {{-- Submit IPCR/Standard/OPCR Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="SubmitISOModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Save IPCR</h4>
                    </div>

                    <form wire:submit.prevent="submitISO">
                        <div class="modal-body">
                            <label>Head/Leader/Superior 1: </label>
                            <div class="form-group">
                                <select placeholder="Head/Leader/Superior 1" class="form-control"
                                    wire:model="superior1_id" wire:change="changeUser">
                                    <option value="">Select First Head/Leader/Superior</option>
                                    @foreach ($users1 as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('superior1_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Head/Leader/Superior 2: </label>
                            <div class="form-group">
                                <select placeholder="Head/Leader/Superior 2" class="form-control"
                                    wire:model="superior2_id" wire:change="changeUser">
                                    <option value="">Select Second Head/Leader/Superior</option>
                                    @foreach ($users2 as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('superior2_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Assess IPCR/Standard/OPCR Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AssessISOModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Save IPCR</h4>
                    </div>

                    <form wire:submit.prevent="assessISO">
                        @error('quality')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <div class="modal-body">
                            <label>Head/Leader/Superior 1: </label>
                            <div class="form-group">
                                <select placeholder="Head/Leader/Superior 1" class="form-control"
                                    wire:model="superior1_id" wire:change="changeUser">
                                    <option value="">Select First Head/Leader/Superior</option>
                                    @foreach ($users1 as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('superior1_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Head/Leader/Superior 2: </label>
                            <div class="form-group">
                                <select placeholder="Head/Leader/Superior 2" class="form-control"
                                    wire:model="superior2_id" wire:change="changeUser">
                                    <option value="">Select Second Head/Leader/Superior</option>
                                    @foreach ($users2 as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('superior2_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if (isset($users))
        {{-- Add TTMA Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddTTMAModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Add Assignment</h4>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <label>Subject: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Subject" class="form-control"
                                    wire:model="subject">
                                @error('subject')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Action Officer: </label>
                            <div class="form-group">
                                <select name="user_id" class="form-select" wire:model="user_id">
                                    <option>Select an Action Officer</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Output: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Output" class="form-control" wire:model="output">
                                @error('output')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Date Deadline: </label>
                            <div class="form-group">
                                <input type="date" placeholder="Date Deadline" class="form-control" wire:model="deadline" min="{{ date('Y-m-d') }}">
                                @error('deadline')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit TTMA Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditTTMAModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Edit Assignment</h4>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <label>Subject: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Subject" class="form-control"
                                    wire:model="subject">
                                @error('subject')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Action Officer: </label>
                            <div class="form-group">
                                <select type="text" placeholder="Action Officer" class="form-control"
                                    wire:model="user_id">
                                    <option value="">Select Action Officer</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Output: </label>
                            <div class="form-group">
                                <input type="text" placeholder="Output" class="form-control" wire:model="output">
                                @error('output')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Date Deadline: </label>
                            <div class="form-group">
                                <input type="date" placeholder="Date Deadline" class="form-control" wire:model="deadline" min="{{ date('Y-m-d') }}">
                                @error('deadline')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Done Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="DoneModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Done</h4>
                </div>
                <form wire:submit.prevent="done">
                    <div class="modal-body">
                        <p>Mark Assignment as Done?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Done</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Office Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddOfficeModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Office</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Office Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Office Name" class="form-control" wire:model="office_name">
                            @error('office_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Office Abbreviation: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Office Abbreviation" class="form-control" wire:model="abr">
                            @error('abr')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Building: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Building" class="form-control" wire:model="building">
                            @error('building')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Office Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditOfficeModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Office</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Office Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Office Name" class="form-control" wire:model="office_name">
                            @error('office_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Office Abbreviation: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Office Abbreviation" class="form-control" wire:model="abr">
                            @error('abr')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Building: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Building" class="form-control" wire:model="building">
                            @error('building')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Institute Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddInstituteModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Institute</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Institute Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Institute Name" class="form-control" wire:model="institute_name">
                            @error('institute_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Institute Abbreviation: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Institute Abbreviation" class="form-control" wire:model="institute_abr">
                            @error('institute_abr')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Institute Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditInstituteModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Institute</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Institute Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Institute Name" class="form-control" wire:model="institute_name">
                            @error('institute_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Institute Abbreviation: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Institute Abbreviation" class="form-control" wire:model="institute_abr">
                            @error('institute_abr')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Account Type Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddAccountTypeModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Account Type</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Account Type: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Account Type" class="form-control"
                                wire:model="account_type">
                                @error('account_type')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Account Type Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditAccountTypeModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Account Type</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Account Type: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Account Type" class="form-control"
                                wire:model="account_type">
                                @error('account_type')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Duration Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddDurationModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Duration</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <h5>IMPORT NOTICE!<br />You can't add, edit or delete semester duration if already started.</h5>


                        <label>Semester Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Semester Name" class="form-control" wire:model="duration_name">
                                @error('duration_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>

                        <label>Start Date: </label>
                        <div class="form-group">
                            <input type="date" placeholder="Start Date" class="form-control"
                                wire:change="startChanged" wire:model="start_date" min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>

                        <label>End Date: </label>
                        <div class="form-group">
                            <input type="date" placeholder="End Date" class="form-control"
                                wire:model="end_date"
                                @if (isset($startDate)) min="{{ $startDate }}"
                                @else
                                    min="{{ date('Y-m-d') }}" @endif>
                                @error('end_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Duration Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditDurationModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Duration</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <h5>IMPORT NOTICE!<br />You can't add, edit or delete semester duration if already started.</h5>


                        <label>Semester Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Semester Name" class="form-control" wire:model="duration_name">
                                @error('duration_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        
                        <label>Start Date: </label>
                        <div class="form-group">
                            <input type="date" placeholder="Start Date" class="form-control"
                                wire:model="start_date" min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>

                        <label>End Date: </label>
                        <div class="form-group">
                            <input type="date" placeholder="End Date" class="form-control"
                                wire:model="end_date" min="{{ date('Y-m-d') }}">
                                @error('end_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reset IPCR Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="ResetIPCRModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Reset IPR</h4>
                </div>
                <form wire:submit.prevent="resetIPCR">
                    <div class="modal-body">
                        <p>You sure you want to reset IPCR?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-danger ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Reset OPCR Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="ResetOPCRModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Reset OPCR</h4>
                </div>
                <form wire:submit.prevent="resetOPCR">
                    <div class="modal-body">
                        <p>You sure you want to reset OPCR?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-danger ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Percentage Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddPercentageModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Percentage</h4>
                </div>
                <form wire:submit.prevent="savePercent">
                    <div class="modal-body">
                        <label>Core Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Core Function" class="form-control"
                                wire:model="core">
                                @error('core')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if (isset($subFuncts))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 1)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="supp.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Strategic Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Strategic Function" class="form-control"
                                wire:model="strategic">
                                @error('strategic')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if (isset($subFuncts))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 2)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="supp.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Support Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Support Function" class="form-control"
                                wire:model="support">
                                @error('support')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if (isset($subFuncts))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 3)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="supp.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Percentage Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditPercentageModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Percentage</h4>
                </div>
                <form wire:submit.prevent="updatePercent">
                    <div class="modal-body">
                        <label>Core Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Core Function" class="form-control"
                                wire:model="core">
                                @error('core')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if (isset($subFuncts))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 1)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="supp.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Strategic Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Strategic Function" class="form-control"
                                wire:model="strategic">
                                @error('strategic')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if (isset($subFuncts))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 2)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="supp.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Support Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Support Function" class="form-control"
                                wire:model="support">
                                @error('support')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if (isset($subFuncts))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 3)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="supp.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Decline Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="DeclineModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Disapproving Message</h4>
                </div>
                <form wire:submit.prevent="declined">
                    <div class="modal-body">
                        <label>Comment: </label>
                        <div class="form-group">
                            <textarea placeholder="Comment" class="form-control"
                                wire:model="comment">
                            </textarea>
                            @error('comment')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MessageTTMA Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="MessageTTMAModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Done Message</h4>
                </div>
                <form wire:submit.prevent="message">
                    <div class="modal-body">
                        <label>Message: </label>
                        <div class="form-group">
                            <textarea placeholder="Message" class="form-control"
                                wire:model="message">
                            </textarea>
                            @error('message')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EditMessageTTMA Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditMessageTTMAModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Done Message</h4>
                </div>
                <form wire:submit.prevent="message">
                    <div class="modal-body">
                        <label>Message: </label>
                        <div class="form-group">
                            <textarea placeholder="Message" class="form-control"
                                wire:model="message">
                            </textarea>
                            @error('message')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Training Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddTrainingModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Training</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <label>Training Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Training Name" class="form-control"
                            wire:model="training_name">
                            @error('training_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Links: </label>
                        <div class="form-group">
                            <textarea placeholder="Links" class="form-control"
                                wire:model="links">
                            </textarea>
                            @error('links')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Keywords (Seperated with ,): </label>
                        <div class="form-group">
                            <textarea placeholder="Keywords" class="form-control"
                                wire:model="keywords">
                            </textarea>
                            @error('keywords')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Training Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditTrainingModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Update Training</h4>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <label>Training Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Training Name" class="form-control"
                            wire:model="training_name">
                            @error('training_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Links: </label>
                        <div class="form-group">
                            <textarea placeholder="Links" class="form-control"
                                wire:model="links">
                            </textarea>
                            @error('links')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Keywords (Seperate with ,): </label>
                        <div class="form-group">
                            <textarea placeholder="Keywords" class="form-control"
                                wire:model="keywords">
                            </textarea>
                            @error('keywords')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Score Equivalent Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditScoreEqModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Update Score Equivalent</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="d-flex justify-content-around gap-2">
                            <div class="w-100 text-center">Equivalent: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Score From: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Score To: </div>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-around gap-2">
                            <div class="gap-4 w-100">
                                <div class="fs-5">Outstanding: </div>
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="out_from">
                                @error('out_from')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="out_to">
                                @error('out_to')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-around gap-2">
                            <div class="gap-4 w-100">
                                <div class="fs-5">Very Satsifactory: </div>
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="verysat_from">
                                @error('verysat_from')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="verysat_to">
                                @error('verysat_to')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-around gap-2">
                            <div class="gap-4 w-100">
                                <div class="fs-5">Satisfactory: </div>
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="sat_from">
                                @error('sat_from')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="sat_to">
                                @error('sat_to')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-around gap-2">
                            <div class="gap-4 w-100">
                                <div class="fs-5">Unatisfactory: </div>
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="unsat_from">
                                @error('unsat_from')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="unsat_to">
                                @error('unsat_to')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-around gap-2">
                            <div class="gap-4 w-100">
                                <div class="fs-5">Poor: </div>
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="poor_from">
                                @error('poor_from')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class="gap-4 w-100 form-group">
                                <input type="text" class="form-control" wire:model="poor_to">
                                @error('poor_to')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Standard Values Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditStandardValueModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Update Standard Values</h4>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="d-flex justify-content-around gap-2">
                            <div class="w-100 text-center">Efficiency: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Quality: </div>
                            <div class="vr"></div>
                            <div class="w-100 text-center">Timeliness: </div>
                        </div>
                        <hr>

                        <div class="d-flex justify-content-around gap-2">
                            <div class="gap-4 w-100 form-group">
                                <textarea type="text" class="form-control" style="height: 150px" wire:model="efficiency"></textarea>
                                @error('efficiency')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr my-2"></div>
                            <div class="gap-4 w-100 form-group">
                                <textarea type="text" class="form-control" style="height: 150px" wire:model="quality"></textarea>
                                @error('quality')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr my-2"></div>
                            <div class="gap-4 w-100 form-group">
                                <textarea type="text" class="form-control" style="height: 150px" wire:model="timeliness"></textarea>
                                @error('timeliness')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <span class="fst-italic fw-lighter">Use Next Line for an additional choices.</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Target Output, Alloted Budget & Responsible Person/Office Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddBudgetModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Target Output, Alloted Budget & Responsible Person/Office</h4>
                </div>
                <form wire:submit.prevent="savebudget">
                    <div class="modal-body">
                        <label>Target Output: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Target Output" class="form-control"
                            wire:model="target_output">
                            @error('target_output')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Alloted Budget: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Alloted Budget" class="form-control"
                            wire:model="alloted_budget">
                            @error('alloted_budget')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Responsible Person/Office: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Responsible Person/Office" class="form-control"
                            wire:model="responsible">
                            @error('responsible')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Target Output, Alloted Budget & Responsible Person/Office Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditBudgetModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Target Output, Alloted Budget & Responsible Person/Office</h4>
                </div>
                <form wire:submit.prevent="savebudget">
                    <div class="modal-body">
                        <label>Target Output: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Target Output" class="form-control"
                            wire:model="target_output">
                            @error('target_output')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Alloted Budget: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Alloted Budget" class="form-control"
                            wire:model="alloted_budget">
                            @error('alloted_budget')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <label>Responsible Person/Office: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Responsible Person/Office" class="form-control"
                            wire:model="responsible">
                            @error('responsible')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Target Output, Alloted Budget & Responsible Person/Office Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="DeleteBudgetModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Delete Modal</h4>
                </div>
                <form wire:submit.prevent="deletebudget">
                    <div class="modal-body">
                        <p>You sure you want to delete?</p>
                        <p>Can't recover data once you delete it!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-danger ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Delete</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    {{-- Add Target Output Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddTargetOutputModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Target Output</h4>
                </div>
                <form wire:submit.prevent="saveTargetOutput">
                    <div class="modal-body">
                        <label>Target Output: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Target Output" class="form-control"
                            wire:model="target_output">
                            @error('target_output')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Target Output Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditTargetOutputModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Target Output</h4>
                </div>
                <form wire:submit.prevent="saveTargetOutput">
                    <div class="modal-body">
                        <label>Target Output: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Target Output" class="form-control"
                            wire:model="target_output">
                            @error('target_output')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-success ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Target Output Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="DeleteTargetOutputModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Delete Modal</h4>
                </div>
                <form wire:submit.prevent="deleteTargetOutput">
                    <div class="modal-body">
                        <p>You sure you want to delete?</p>
                        <p>Can't recover data once you delete it!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-danger ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Delete</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Done Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="SaveIPCRModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Save Faculty's IPCR</h4>
                </div>
                <form wire:submit.prevent="saveIPCR">
                    <div class="modal-body">
                        <p>Save it?</p>
                        <p>Can't Edit it once you save it.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="closeModal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
