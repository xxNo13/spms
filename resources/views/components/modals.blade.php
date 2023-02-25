<div>
    @if (isset($selected))
        {{-- Add Output/Suboutput/Target Modal --}}
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddOSTModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Add Output/Suboutput/Target</h4>
                    </div>
                    <form wire:submit.prevent="saveIpcr">
                        <div class="modal-body">
                            <div class="mt-3 form-group d-flex justify-content-between">
                                <div class="form-check form-switch">
                                    <input wire:change="$emit('resetInput')" type="radio" class="form-check-input" id="output"
                                        value="sub_funct" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="sub_funct">
                                        Sub Function
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="$emit('resetInput')" type="radio" class="form-check-input" id="output"
                                        value="output" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="output">
                                        Output
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="$emit('resetInput')" type="radio" class="form-check-input" id="suboutput"
                                        value="suboutput" name="selected" wire:model="selected">
                                    <label class="form-check-label" for="suboutput">
                                        Suboutput
                                    </label>
                                </div>
                                <div class="form-check form-switch">
                                    <input wire:change="$emit('resetInput')" type="radio" class="form-check-input" id="target"
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
                                            <option value="" selected>Select a Sub Function</option>
                                            @if ($duration)
                                                @if (isset($userType) && ($userType == 'listing' || $userType == 'listingFaculty'))
                                                    @if (isset($subFuncts))
                                                        @foreach ($subFuncts->where('funct_id', $currentPage) as $sub_funct)
                                                            <option value="{{ $sub_funct->id }}">{{ $sub_funct->sub_funct }}</option>
                                                        @endforeach
                                                    @endif
                                                @elseif (isset($userType) && $userType == 'faculty')
                                                    @foreach (auth()->user()->sub_functs()->where('added_by', null)->where('duration_id', $duration->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('funct_id', $currentPage)->get() as $sub_funct)
                                                        <option value="{{ $sub_funct->id }}">{{ $sub_funct->sub_funct }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach (auth()->user()->sub_functs()->where('duration_id', $duration->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('funct_id', $currentPage)->get() as $sub_funct)
                                                        <option value="{{ $sub_funct->id }}">{{ $sub_funct->sub_funct }}</option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>
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
                                            <option value="" selected>Select an output</option>
                                            @php
                                                $number = 1;
                                                switch($currentPage) {
                                                    case 1:
                                                        $code = "CF";
                                                        break;
                                                    case 2:
                                                        $code = "STF";
                                                        break;
                                                    case 3:
                                                        $code = "SF";
                                                        break;
                                                }
                                            @endphp
                                            @if ($duration)
                                                @if (isset($userType) && ($userType == 'listing' || $userType == 'listingFaculty'))
                                                    @if (isset($outputs))
                                                        @foreach ($outputs->where('code', $code) as $output)
                                                            @forelse ($output->targets as $target)
                                                            @empty
                                                                <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} - 
                                                                    {{ $output->output }}
                                                                </option>
                                                            @endforelse
                                                        @endforeach
                                                    @endif
                                                @elseif (isset($userType) && $userType == 'faculty')
                                                    @foreach (auth()->user()->outputs()->where('added_by', null)->where('duration_id', $duration->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('code', $code)->get() as $output)
                                                        @forelse ($output->targets as $target)
                                                        @empty
                                                            <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} - 
                                                                {{ $output->output }}
                                                            </option>
                                                        @endforelse
                                                    @endforeach
                                                @else
                                                    @foreach (auth()->user()->outputs()->where('duration_id', $duration->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('code', $code)->get() as $output)
                                                        @forelse ($output->targets as $target)
                                                        @empty
                                                            <option value="{{ $output->id }}">{{ $output->code }} {{ $number++ }} - 
                                                                {{ $output->output }}
                                                            </option>
                                                        @endforelse
                                                    @endforeach
                                                @endif
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
                                            <option value="" selected>Select an/a Output/Suboutput</option>s
                                            @php
                                                $number = 1;
                                                switch($currentPage) {
                                                    case 1:
                                                        $code = "CF";
                                                        break;
                                                    case 2:
                                                        $code = "STF";
                                                        break;
                                                    case 3:
                                                        $code = "SF";
                                                        break;
                                                }
                                            @endphp
                                            @if ($duration)
                                                @if (isset($userType) && ($userType == 'listing' || $userType == 'listingFaculty'))
                                                    @if (isset($outputs))
                                                        @foreach ($outputs->where('code', $code) as $output)
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
                                                        @endforeach
                                                    @endif
                                                @elseif (isset($userType) && $userType == 'faculty')
                                                    @foreach (auth()->user()->outputs()->where('added_by', null)->where('duration_id', $duration->id)->where('type', 'ipcr')->where('user_type', 'faculty')->where('code', $code)->get() as $output)
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
                                                    @endforeach
                                                @else
                                                    @foreach (auth()->user()->outputs()->where('duration_id', $duration->id)->where('type', 'ipcr')->where('user_type', 'staff')->where('code', $code)->get() as $output)
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
                                                    @endforeach
                                                @endif
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
                                    @if (isset($userType) && $userType == 'listingFaculty')
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
        <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditOSTModal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">Edit Output/Suboutput/Target</h4>
                    </div>
                    <form wire:submit.prevent="updateIpcr">
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
                                    @if (isset($userType) && $userType == 'listingFaculty')
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

    @if (isset($selectedTarget) && isset($targetOutput))
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
                            @if (isset($targetOutput))
                                <label>Output Finished (Target Output is "{{ $targetOutput }}"): </label>
                                <div class="form-group">
                                    <input type="number" placeholder="Output Finished" class="form-control"
                                        wire:model="output_finished" max="{{$targetOutput}}">
                                    @error('output_finished')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <label>Efficiency: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="efficiency">
                                    <option value="">Efficiency</option>
                                    @if ($standard = $selectedTarget->standards()->first())
                                        @if (!empty($standard->eff_1)) 
                                            <option value="1">1 - {{ $standard->eff_1 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_2)) 
                                            <option value="2">2 - {{ $standard->eff_2 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_3)) 
                                            <option value="3">3 - {{ $standard->eff_3 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_4))
                                            <option value="4">4 - {{ $standard->eff_4 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_5))
                                            <option value="5">5 - {{ $standard->eff_5 }}</option>
                                        @endif
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
                                    @if ($standard = $selectedTarget->standards()->first())
                                        @if (!empty($standard->qua_1)) 
                                            <option value="1">1 - {{ $standard->qua_1 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_2)) 
                                            <option value="2">2 - {{ $standard->qua_2 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_3)) 
                                            <option value="3">3 - {{ $standard->qua_3 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_4))
                                            <option value="4">4 - {{ $standard->qua_4 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_5))
                                            <option value="5">5 - {{ $standard->qua_5 }}</option>
                                        @endif
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
                                    @if ($standard = $selectedTarget->standards()->first())
                                        @if (!empty($standard->time_1)) 
                                            <option value="1">1 - {{ $standard->time_1 }}</option>
                                        @endif
                                        @if (!empty($standard->time_2)) 
                                            <option value="2">2 - {{ $standard->time_2 }}</option>
                                        @endif
                                        @if (!empty($standard->time_3)) 
                                            <option value="3">3 - {{ $standard->time_3 }}</option>
                                        @endif
                                        @if (!empty($standard->time_4))
                                            <option value="4">4 - {{ $standard->time_4 }}</option>
                                        @endif
                                        @if (!empty($standard->time_5))
                                            <option value="5">5 - {{ $standard->time_5 }}</option>
                                        @endif
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
                            @if (isset($targetOutput))
                                <label>Output Finished (Target Output is "{{ $targetOutput }}"): </label>
                                <div class="form-group">
                                    <input type="number" placeholder="Output Finished" class="form-control"
                                        wire:model="output_finished" max="{{$targetOutput}}">
                                    @error('output_finished')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <label>Efficiency: </label>
                            <div class="form-group">
                                <select class="form-control" wire:model="efficiency">
                                    <option value="">Efficiency</option>
                                    @if ($standard = $selectedTarget->standards()->first())
                                        @if (!empty($standard->eff_1)) 
                                            <option value="1">1 - {{ $standard->eff_1 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_2)) 
                                            <option value="2">2 - {{ $standard->eff_2 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_3)) 
                                            <option value="3">3 - {{ $standard->eff_3 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_4))
                                            <option value="4">4 - {{ $standard->eff_4 }}</option>
                                        @endif
                                        @if (!empty($standard->eff_5))
                                            <option value="5">5 - {{ $standard->eff_5 }}</option>
                                        @endif
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
                                    @if ($standard = $selectedTarget->standards()->first())
                                        @if (!empty($standard->qua_1)) 
                                            <option value="1">1 - {{ $standard->qua_1 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_2)) 
                                            <option value="2">2 - {{ $standard->qua_2 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_3)) 
                                            <option value="3">3 - {{ $standard->qua_3 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_4))
                                            <option value="4">4 - {{ $standard->qua_4 }}</option>
                                        @endif
                                        @if (!empty($standard->qua_5))
                                            <option value="5">5 - {{ $standard->qua_5 }}</option>
                                        @endif
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
                                    @if ($standard = $selectedTarget->standards()->first())
                                        @if (!empty($standard->time_1)) 
                                            <option value="1">1 - {{ $standard->time_1 }}</option>
                                        @endif
                                        @if (!empty($standard->time_2)) 
                                            <option value="2">2 - {{ $standard->time_2 }}</option>
                                        @endif
                                        @if (!empty($standard->time_3)) 
                                            <option value="3">3 - {{ $standard->time_3 }}</option>
                                        @endif
                                        @if (!empty($standard->time_4))
                                            <option value="4">4 - {{ $standard->time_4 }}</option>
                                        @endif
                                        @if (!empty($standard->time_5))
                                            <option value="5">5 - {{ $standard->time_5 }}</option>
                                        @endif
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
        <div class="modal-dialog modal-dialog-centered modal-full" role="document">
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
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">5:</h5>
                                    <select type="text" class="form-control" wire:model="eff_5">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_5')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">5:</h5>
                                    <select type="text" class="form-control" wire:model="qua_5">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_5')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">5:</h5>
                                    <select type="text" class="form-control" wire:model="time_5">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_5')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">4:</h5>
                                    <select type="text" class="form-control" wire:model="eff_4">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_4')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">4:</h5>
                                    <select type="text" class="form-control" wire:model="qua_4">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_4')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">4:</h5>
                                    <select type="text" class="form-control" wire:model="time_4">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_4')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">3:</h5>
                                    <select type="text" class="form-control" wire:model="eff_3">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_3')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">3:</h5>
                                    <select type="text" class="form-control" wire:model="qua_3">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_3')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">3:</h5>
                                    <select type="text" class="form-control" wire:model="time_3">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_3')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">2:</h5>
                                    <select type="text" class="form-control" wire:model="eff_2">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_2')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">2:</h5>
                                    <select type="text" class="form-control" wire:model="qua_2">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_2')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">2:</h5>
                                    <select type="text" class="form-control" wire:model="time_2">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_2')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">1:</h5>
                                    <select type="text" class="form-control" wire:model="eff_1">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_1')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">1:</h5>
                                    <select type="text" class="form-control" wire:model="qua_1">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_1')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">1:</h5>
                                    <select type="text" class="form-control" wire:model="time_1">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_1')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
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
        <div class="modal-dialog modal-dialog-centered modal-full" role="document">
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
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">5:</h5>
                                    <select type="text" class="form-control" wire:model="eff_5">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_5')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">5:</h5>
                                    <select type="text" class="form-control" wire:model="qua_5">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_5')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">5:</h5>
                                    <select type="text" class="form-control" wire:model="time_5">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_5')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">4:</h5>
                                    <select type="text" class="form-control" wire:model="eff_4">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_4')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">4:</h5>
                                    <select type="text" class="form-control" wire:model="qua_4">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_4')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">4:</h5>
                                    <select type="text" class="form-control" wire:model="time_4">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_4')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">3:</h5>
                                    <select type="text" class="form-control" wire:model="eff_3">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_3')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">3:</h5>
                                    <select type="text" class="form-control" wire:model="qua_3">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_3')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">3:</h5>
                                    <select type="text" class="form-control" wire:model="time_3">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_3')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">2:</h5>
                                    <select type="text" class="form-control" wire:model="eff_2">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_2')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">2:</h5>
                                    <select type="text" class="form-control" wire:model="qua_2">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_2')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">2:</h5>
                                    <select type="text" class="form-control" wire:model="time_2">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_2')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-around gap-2">
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">1:</h5>
                                    <select type="text" class="form-control" wire:model="eff_1">
                                        <option value=""></option>
                                        @foreach ($effs as $eff)
                                            <option value="{{ $eff }}">{{ $eff }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('eff_1')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">1:</h5>
                                    <select type="text" class="form-control" wire:model="qua_1">
                                        <option value=""></option>
                                        @foreach ($quas as $qua)
                                            <option value="{{ $qua }}">{{ $qua }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('qua_1')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="vr"></div>
                            <div class=" w-100">
                                <div class="hstack gap-4">
                                    <h5 class="my-auto">1:</h5>
                                    <select type="text" class="form-control" wire:model="time_1">
                                        <option value=""></option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('time_1')
                                    <p class="text-danger text-center">{{ $message }}</p>
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

    @if (isset($users))
        {{-- Add TTMA Modal --}}
        <div wire:ignore data-bs-backdrop="static"  class="modal fade text-left" id="AddTTMAModal" role="dialog"
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
                            <div class="form-group" wire:ignore>
                                <select style="width: 100%;" name="users_id" id="users_id" class="form-select" wire:model="users_id" multiple="multiple">
                                    <option></option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            @push ('script')
                                <script>
                                    $("#users_id").select2({
                                        multiple: true,
                                        placeholder: "Select an Action Officer.",
                                        dropdownParent: $("#AddTTMAModal")
                                    });

                                    $('#users_id').on('change', function () {
                                        var data = $('#users_id').select2("val");
                                        @this.set('users_id', data);
                                    });
                                </script>
                            @endpush
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

    {{-- Decline Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="DeclineModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Declining Message</h4>
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

    @if (isset($offices))
        {{-- Add Office Modal --}}
        <div wire:ignore data-bs-backdrop="static"  class="modal fade text-left" id="AddOfficeModal" role="dialog"
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
                                <input type="text" placeholder="Office Abbreviation" class="form-control" wire:model="office_abbr">
                                @error('abr')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Office which it belongs: </label>
                            <div class="form-group" wire:ignore>
                                <select style="width: 100%;" name="parent_id" id="parent_id" class="form-select" wire:model="parent_id" >
                                    <option></option>
                                    @foreach ($offices as $office) 
                                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (isset($offices))
                            @push ('script')
                                <script>
                                    $("#parent_id").select2({
                                        placeholder: "Select an Office which it belongs.",
                                        dropdownParent: $("#AddOfficeModal")
                                    });
                                    $('#parent_id').on('change', function () {
                                        var data = $('#parent_id').select2("val");
                                        @this.set('parent_id', data);
                                    });
                                </script>
                            @endpush
                        @endif
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
        <div wire:ignore data-bs-backdrop="static"  class="modal fade text-left" id="EditOfficeModal" role="dialog"
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
                                <input type="text" placeholder="Office Abbreviation" class="form-control" wire:model="office_abbr">
                                @error('abr')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <label>Office which it belongs: </label>
                            <div class="form-group" wire:ignore>
                                <select style="width: 100%;" name="edit_parent_id" id="edit_parent_id" class="form-select" wire:model="parent_id" >
                                    <option></option>
                                    @foreach ($offices as $office) 
                                        <option value="{{ $office->id }}" @if (isset($parentId) && $parentId == $office->id) selected @endif>{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if (isset($offices))
                            @push ('script')
                                <script>
                                    $("#edit_parent_id").select2({
                                        placeholder: "Select an Office which it belongs.",
                                        dropdownParent: $("#EditOfficeModal")
                                    });
                                    $('#edit_parent_id').on('change', function () {
                                        var data = $('#edit_parent_id').select2("val");
                                        @this.set('parent_id', data);
                                    });
                                </script>
                            @endpush
                        @endif
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

    {{-- Add Percentage Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddPercentageModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Percentage</h4>
                </div>
                <form wire:submit.prevent="savePercentage">
                    <div class="modal-body">
                        <label>Core Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Core Function" class="form-control"
                                wire:model="percent.core">
                                @error('percent.core')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if ((isset($subFuncts) && isset($userType) && ($userType != 'listing' && $userType != 'listingFaculty')) || (isset($subFuncts) && !isset($userType)))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 1)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input required type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="sub_percent.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Strategic Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Strategic Function" class="form-control"
                                wire:model="percent.strategic">
                                @error('percent.strategic')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if ((isset($subFuncts) && isset($userType) && ($userType != 'listing' && $userType != 'listingFaculty')) || (isset($subFuncts) && !isset($userType)))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 2)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="sub_percent.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Support Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Support Function" class="form-control"
                                wire:model="percent.support">
                                @error('percent.support')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if ((isset($subFuncts) && isset($userType) && ($userType != 'listing' && $userType != 'listingFaculty')) || (isset($subFuncts) && !isset($userType)))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 3)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="sub_percent.{{ $sub_funct->id }}">
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
                <form wire:submit.prevent="updatePercentage">
                    <div class="modal-body">
                        @error('sub_percent')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <label>Core Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Core Function" class="form-control"
                                wire:model="percent.core">
                                @error('percent.core')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if ((isset($subFuncts) && isset($userType) && ($userType != 'listing' && $userType != 'listingFaculty')) || (isset($subFuncts) && !isset($userType)))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 1)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input required type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="sub_percent.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Strategic Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Strategic Function" class="form-control"
                                wire:model="percent.strategic">
                                @error('percent.strategic')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if ((isset($subFuncts) && isset($userType) && ($userType != 'listing' && $userType != 'listingFaculty')) || (isset($subFuncts) && !isset($userType)))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 2)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="sub_percent.{{ $sub_funct->id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <label>Support Function %: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Support Function" class="form-control"
                                wire:model="percent.support">
                                @error('percent.support')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                        </div>
                        @if ((isset($subFuncts) && isset($userType) && ($userType != 'listing' && $userType != 'listingFaculty')) || (isset($subFuncts) && !isset($userType)))
                            <div class="d-flex gap-3" style="height: 100%;">
                                <div class="vr"></div>
                                
                                <div class="">
                                    @foreach ($subFuncts as $sub_funct)
                                        @if ($sub_funct->funct_id == 3)
                                            <label>{{ $sub_funct->sub_funct }} %: </label>
                                            <div class="form-group">
                                                <input type="text" placeholder="{{ $sub_funct->sub_funct }}" class="form-control"
                                                    wire:model="sub_percent.{{ $sub_funct->id }}">
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
    
    {{-- Add Target Output Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="AddTargetOutputModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Target Output</h4>
                </div>
                <form wire:submit.prevent="{{ (isset($type) && $type == 'office') ? "saveOpcr" : "saveIpcr" }}">
                    <div class="modal-body">
                        <label>Target Output: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Target Output" class="form-control"
                            wire:model="target_output">
                            @error('target_output')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (isset($type) && $type == 'office')
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

    {{-- Edit Target Output Modal --}}
    <div wire:ignore.self data-bs-backdrop="static"  class="modal fade text-left" id="EditTargetOutputModal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Target Output</h4>
                </div>
                <form wire:submit.prevent="{{ (isset($type) && $type == 'office') ? "saveOpcr" : "saveIpcr" }}">
                    <div class="modal-body">
                        <label>Target Output: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Target Output" class="form-control"
                            wire:model="target_output">
                            @error('target_output')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (isset($type) && $type == 'office')
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
                        @endif
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
</div>
