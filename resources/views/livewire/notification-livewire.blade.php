<div>
    <a class="d-flex align-items-center nav-link active " href="" data-bs-toggle="dropdown"
        aria-expanded="false">
        <div class="">
            @foreach (Auth::user()->unreadNotifications as $notif)
                @if (!$notif->read_at)
                    @php
                        $unreads++
                    @endphp
                @endif
                @if ($loop->last)
                    @if ($unreads >= 1)
                        {{-- <p class="float-end fs-6 rounded-circle bg-danger px-2 text-white">
                            {{ $unreads }}
                        </p> --}}
                        <span class="position-absolute top-25 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreads }}</span>
                    @endif
                @endif
            @endforeach
            <i class='bi bi-bell bi-sub fs-4 text-gray-600'></i>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end overflow-auto" aria-labelledby="dropdownMenuButton"
        style="max-height: 88vh; width: 425px; border-radius: 20px; ">
        <li>
            <h6 class="dropdown-header">Notifications</h6>
        </li>
        @forelse (Auth::user()->notifications as $notification)
            @if (isset($notification->data['ttma_id']))
                @if (isset($notification->data['remarks']))
                    <li>
                        <button wire:click="read('{{ $notification->id }}', 'ttma')" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div style="width: 90%;">
                                    <div class="text-truncate fw-bold">
                                        <span>{{ $notification->data['head'] }} Marked Task as Done:</span>
                                    </div>
                                    <div class="text-truncate text-capitalize">
                                        {{ $notification->data['subject'] }} - {{ $notification->data['output'] }}
                                    </div>
                                    <div>
                                        <span
                                            class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-primary hstack" style="width: 10%;">
                                    @if (empty($notification->read_at))
                                        <span class="ms-auto">
                                            <i class="bi bi-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </li>
                @elseif (isset($notification->data['status']) && $notification->data['status'] == 'Done')
                    <li>
                        <button wire:click="read('{{ $notification->id }}', 'ttma')" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div style="width: 90%;">
                                    <div class="text-truncate fw-bold">
                                        <span>{{ $notification->data['user'] }} Complete Task:</span>
                                    </div>
                                    <div class="text-truncate text-capitalize">
                                        {{ $notification->data['subject'] }} - {{ $notification->data['output'] }}
                                    </div>
                                    <div>
                                        <span
                                            class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-primary hstack" style="width: 10%;">
                                    @if (empty($notification->read_at))
                                        <span class="ms-auto">
                                            <i class="bi bi-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </li>
                @elseif (isset($notification->data['status']) && $notification->data['status'] == 'Declined')
                    <li>
                        <button wire:click="read('{{ $notification->id }}', 'ttma')" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div style="width: 90%;">
                                    <div class="text-truncate fw-bold">
                                        <span>{{ $notification->data['user'] }} Decline Complete:</span>
                                    </div>
                                    <div class="text-truncate text-capitalize">
                                        {{ $notification->data['subject'] }} - {{ $notification->data['output'] }}
                                    </div>
                                    <div>
                                        <span
                                            class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-primary hstack" style="width: 10%;">
                                    @if (empty($notification->read_at))
                                        <span class="ms-auto">
                                            <i class="bi bi-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </li>
                @elseif (isset($notification->data['status']) && $notification->data['status'] == 'deadline')
                    <li>
                        <button wire:click="read('{{ $notification->id }}', 'ttma')" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div style="width: 90%;">
                                    <div class="text-truncate fw-bold">
                                        <span>{{ $notification->data['user'] }} Assignment Overdue:</span>
                                    </div>
                                    <div class="text-truncate text-capitalize">
                                        {{ $notification->data['subject'] }} - {{ $notification->data['output'] }}
                                    </div>
                                    <div>
                                        <span
                                            class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-primary hstack" style="width: 10%;">
                                    @if (empty($notification->read_at))
                                        <span class="ms-auto">
                                            <i class="bi bi-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </li>
                @else
                    <li>
                        <button wire:click="read('{{ $notification->id }}', 'ttma')" class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div style="width: 90%;">
                                    <div class="text-truncate fw-bold">
                                        <span>{{ $notification->data['head'] }} Assigned Task:</span>
                                    </div>
                                    <div class="text-truncate text-capitalize">
                                        {{ $notification->data['subject'] }} - {{ $notification->data['output'] }}
                                    </div>
                                    <div>
                                        <span
                                            class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-primary hstack" style="width: 10%;">
                                    @if (empty($notification->read_at))
                                        <span class="ms-auto">
                                            <i class="bi bi-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    </li>
                @endif
            @elseif (isset($notification->data['approval_id']))
                @php
                    if ($notification->data['status'] == 'Submitting') {
                        $url = 'for-approval';
                    } else {
                        if ($notification->data['type'] == 'ipcr' && $notification->data['userType'] == 'staff') {
                            $url = 'ipcr/staff';
                        } elseif ($notification->data['type'] == 'ipcr' && $notification->data['userType'] == 'faculty') {
                            $url = 'ipcr/faculty';
                        } elseif ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'staff') {
                            $url = 'standard/staff';
                        } elseif ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'faculty') {
                            $url = 'standard/faculty';
                        } elseif ($notification->data['type'] == 'opcr' && $notification->data['userType'] == 'office') {
                            $url = 'opcr';
                        } elseif ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'office') {
                            $url = 'standard/opcr';
                        }
                    }
                @endphp
                <li>
                    <button wire:click="read('{{ $notification->id }}', '{{ $url }}')" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <div style="width: 90%;">
                                <div class="text-truncate fw-bold">
                                    <span>{{ $notification->data['user'] }}:</span>
                                </div>
                                <div class="text-truncate text-capitalize">
                                    {{ $notification->data['status'] }} - {{ $notification->data['type'] }}
                                </div>
                                <div>
                                    <span
                                        class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-primary hstack" style="width: 10%;">
                                @if (empty($notification->read_at))
                                    <span class="ms-auto">
                                        <i class="bi bi-circle-fill"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </button>
                </li>
            @elseif (isset($notification->data['duration_id'])) 
                <li>
                    <button wire:click="read('{{ $notification->id }}', 'recommended-for-training')" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <div style="width: 90%;">
                                <div class="text-truncate fw-bold">
                                    <span>Recommended for Training</span>
                                </div>
                                <div class="text-truncate text-capitalize">
                                    Your subbordinate/s have been recommended for training.
                                </div>
                                <div>
                                    <span
                                        class="text-muted fst-italic">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-primary hstack" style="width: 10%;">
                                @if (empty($notification->read_at))
                                    <span class="ms-auto">
                                        <i class="bi bi-circle-fill"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </button>
                </li>
            @endif
        @empty
            <li><a class="dropdown-item">No notification available</a></li>
        @endforelse
    </ul>
</div>
