<?php

namespace App\Http\Livewire;

use App\Models\Ttma;
use Livewire\Component;
use App\Models\Approval;
use App\Models\Duration;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AssignmentNotification;
use App\Notifications\RecommendedNotification;

class NotificationLivewire extends Component
{    
    public function render()
    {

        foreach (Auth::user()->unreadNotifications as $notification) {
            if(str_replace(url('/'), '', url()->current()) == '/ttma' && isset($notification->data['ttma_id'])){
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/staff' && isset($notification->data['type']) && ($notification->data['type'] == 'ipcr' && $notification->data['userType'] == 'staff')) {
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/faculty' && isset($notification->data['type']) && ($notification->data['type'] == 'ipcr' && $notification->data['userType'] == 'faculty')) {
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/standard/staff' && isset($notification->data['type']) && ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'staff')) {
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/standard/faculty' && isset($notification->data['type']) && ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'faculty')) {
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/opcr' && isset($notification->data['type']) && ($notification->data['type'] == 'opcr' && $notification->data['userType'] == 'office')) {
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/standard/opcr' && isset($notification->data['type']) && ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'office')) {
                $notification->markAsRead();
            } elseif (str_replace(url('/'), '', url()->current()) == '/for-approval' && isset($notification->data['status']) && $notification->data['status'] == 'Submitting'){
                $notification->markAsRead();
            }
        }

        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();

        if ($duration) {
            $assignments = auth()->user()->ttmas()
                        ->where('duration_id', $duration->id)
                        ->where('remarks', null)
                        ->where('deadline', '<', date('Y-m-d'))
                        ->get();

            $notifications = Auth::user()->notifications()->orderBy('created_at', 'DESC')->get();

            foreach ($assignments as $assignment) {
                foreach ($notifications as $notification) {
                    if (isset($notification->data['ttma_id']) && $notification->data['ttma_id'] == $assignment->id) {
                        if(isset($notification->data['status']) && $notification->data['status'] == 'deadline'){
                            break;
                        } else {
                            Auth::user()->notify(new AssignmentNotification($assignment, 'deadline'));
                            break;
                        }
                    }
                }
            }

            $head = false;

            foreach (Auth::user()->account_types as $account_type) {
                if (str_contains(strtolower($account_type->account_type), 'head')){
                    $head = true;
                    break;
                }
            }

            if ($head) {
                foreach ($notifications as $notification) {
                    if (isset($notification->data['duration_id']) && $notification->data['duration_id'] == $duration->id) {
                        if (isset($notification->data['message'])  && $notification->data['message'] == 'recommended') {
                            break;
                        } else {
                            $users = User::where('office_id', Auth::user()->office_id)->get();
                            foreach ($users as $user) {
                                foreach ($user->account_types as $account_type) {
                                    if (str_contains(strtolower($account_type->account_type), 'faculty')){
                                        $faculty = true;
                                    }
                                    if (str_contains(strtolower($account_type->account_type), 'staff')){
                                        $staff = true;
                                    }
                                }
                    
                                if ($faculty) {
                                    if ($duration) {
                                        $assessF = Approval::orderBy('id', 'DESC')
                                            ->where('name', 'assess')
                                            ->where('approve_status', 1)
                                            ->where('user_id', $user->id)
                                            ->where('type', 'ipcr')
                                            ->where('duration_id', $duration->id)
                                            ->where('user_type', 'faculty')
                                            ->first();
                        
                                        if (isset($assessF)) {
                                            $faculty = true;
                                        }
                                    }
                                } else {
                                    $faculty = true;
                                }
                        
                                if ($staff) {
                                    if ($duration) {
                                        $assessS = Approval::orderBy('id', 'DESC')
                                            ->where('name', 'assess')
                                            ->where('approve_status', 1)
                                            ->where('user_id', $user->id)
                                            ->where('type', 'ipcr')
                                            ->where('duration_id', $duration->id)
                                            ->where('user_type', 'staff')
                                            ->first();
                        
                                        if (isset($assessS)) {
                                            $staff = true;
                                        }
                                    }
                                } else {
                                    $staff = true;
                                }
                                
                                if (isset($faculty) && isset($staff)) {
                                    $targ = '';
                                    if($duration) {
                                        $targ = Target::where('user_id', $user->id)
                                        ->where('duration_id', $duration->id)
                                        ->where(function ($query) {
                                            $query->whereHas('rating', function (\Illuminate\Database\Eloquent\Builder $query) {
                                                return $query->where('average', '<', $this->scoreEq->sat_to);
                                            });
                                        })->first();
                                    }
                                        
                                    if($targ) {
                                        Auth::user()->notify(new RecommendedNotification('recommended', $duration->id));
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('livewire.notification-livewire',[
            'unreads' => 0
        ]);
    }

    public function read($id, $url) {
        foreach (Auth::user()->notifications as $notification)
        {
            if ($notification->id == $id)
            {
                $notification->markAsRead();

            }
        }
        
        return redirect($url);
    }
}
