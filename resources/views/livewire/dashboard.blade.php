<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Statistics</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-3 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                                        <div class="stats-icon purple mb-2 mx-auto">
                                            <i class="iconly-boldDocument"></i>
                                        </div>
                                    </div>
                                    <div
                                        class="col-md-9 col-lg-12 col-xl-12 col-xxl-8 text-md-start text-lg-center text-xxl-start">
                                        <h6 class="text-muted font-semibold">Rated Targets</h6>
                                        <h6 class="font-extrabold mb-0">
                                            @if ((isset($approvalIPCRS) && $approvalIPCRS->review_status == 1 && $approvalIPCRS->approve_status == 1) && (isset($approvalIPCRF) && $approvalIPCRF->review_status == 1 && $approvalIPCRF->approve_status == 1))
                                                {{ count($ratings) }} / {{ (count($targetsS) + count($targetsF)) }}
                                            @elseif (isset($approvalIPCRS) && $approvalIPCRS->review_status == 1 && $approvalIPCRS->approve_status == 1)
                                                {{ count($ratings) }} / {{ count($targetsS) }}
                                            @elseif(isset($approvalIPCRF) && $approvalIPCRF->review_status == 1 && $approvalIPCRF->approve_status == 1)
                                                {{ count($ratings) }} / {{ count($targetsF) }}
                                            @else
                                                Not approved or Semester's not started yet.
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-3 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2 mx-auto">
                                            <i class="iconly-boldActivity"></i>
                                        </div>
                                    </div>
                                    <div
                                        class="col-md-9 col-lg-12 col-xl-12 col-xxl-8 text-md-start text-lg-center text-xxl-start">
                                        <h6 class="text-muted font-semibold">Assignments</h6>
                                        <h6 class="font-extrabold mb-0">
                                            @if ($duration)
                                                {{ count($finished) }} / {{ count($assignments) }}
                                            @else
                                                Semester's not started yet.
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Target Finish/Day Chart</h4>
                            </div>
                            <div class="card-body">
                                @if ($duration)
                                    <livewire:targetchart />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Assignment Finish/Day Chart</h4>
                            </div>
                            <div class="card-body">
                                @if ($duration)
                                    <livewire:assignmentchart />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-3 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start ">
                                <div class="avatar avatar-xl mb-2 mx-auto">
                                    <img src="{{ asset('/images/faces/1.jpg') }}">
                                </div>
                            </div>
                            <div
                                class="col-md-9 col-lg-12 col-xl-12 col-xxl-8 text-md-start text-lg-center text-xxl-start">
                                <div class="name">
                                    <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                    <h6 class="text-muted mb-2 text-wrap">{{ Auth::user()->email }}</h6>
                                </div>
                            </div>
                            <h6 class="text-muted text-center mt-4 mb-2">
                                <a href="{{ route('profile.show') }}">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Targets</h4>
                    </div>
                    <div class="card-body">
                        @if (isset($recentTargets))
                            @forelse ($recentTargets as $target)
                                @if ((isset($approvalIPCRF) && $approvalIPCRF->review_status == 1 && $approvalIPCRF->approve_status == 1) && (isset($approvalIPCRS) && $approvalIPCRS->review_status == 1 && $approvalIPCRS->approve_status == 1))
                                    <h6 class="text-muted mb-2">
                                        <a
                                            href="#">
                                            @if ($target->rating)
                                                <i class="bi bi-check"></i>
                                            @endif
                                            {{ $target->target }}
                                        </a>
                                    </h6>
                                @elseif (isset($approvalIPCRF) && $approvalIPCRF->review_status == 1 && $approvalIPCRF->approve_status == 1)
                                    @if ($target->user_type == 'faculty')
                                        <h6 class="text-muted mb-2">
                                            <a href="">
                                                @if ($target->rating)
                                                    <i class="bi bi-check"></i>
                                                @endif
                                                {{ $target->target }}
                                            </a>
                                        </h6>
                                    @endif
                                @elseif (isset($approvalIPCRS) && $approvalIPCRS->review_status == 1 && $approvalIPCRS->approve_status == 1)
                                    @if ($target->user_type == 'staff')
                                        <h6 class="text-muted mb-2">
                                            <a href="">
                                                @if ($target->rating)
                                                    <i class="bi bi-check"></i>
                                                @endif
                                                {{ $target->target }}
                                            </a>
                                        </h6>
                                    @endif
                                @elseif($loop->last)
                                    <h6 class="text-muted mb-2">No Data avialable</h6>
                                @endif
                            @empty
                                <h6 class="text-muted mb-2">No Data avialable</h6>
                            @endforelse
                        @else
                            <h6 class="text-muted mb-2">No Data avialable</h6>
                        @endif
                        <h6 class="text-muted text-center mt-4 mb-2">
                            @php
                                $faculty = false;
                            @endphp
                            @foreach (Auth::user()->account_types as $account_type)
                                @if (str_contains(strtolower($account_type->account_type), 'faculty'))
                                    @php
                                        $faculty = true;
                                    @endphp
                                @endif
                            @endforeach
                            <a
                                href="#">
                                View all <i class="bi bi-arrow-right"></i>
                            </a>
                        </h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Assignments</h4>
                    </div>
                    <div class="card-body">
                        @if (isset($recentAssignments))
                            @forelse ($recentAssignments as $assignment)
                                <h6 class="text-muted mb-2">
                                    <a href="{{ route('ttma') }}">
                                        @if ($assignment->remarks == 'Done')
                                            <i class="bi bi-check"></i>
                                        @endif
                                        {{ $assignment->output }}
                                    </a>
                                </h6>
                            @empty
                                <h6 class="text-muted mb-2">No Data avialable</h6>
                            @endforelse
                        @else
                            <h6 class="text-muted mb-2">No Data avialable</h6>
                        @endif
                        <h6 class="text-muted text-center mt-4 mb-2">
                            <a href="{{ route('ttma') }}">
                                View all <i class="bi bi-arrow-right"></i>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Score/Targets Chart</h4>
                        </div>
                        <div class="card-body">
                            @if ($duration)
                                <livewire:ratingchart />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
