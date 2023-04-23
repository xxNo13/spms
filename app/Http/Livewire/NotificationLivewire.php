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
    public $amount = 10;

    public function render()
    {

        // foreach (Auth::user()->unreadNotifications as $notification) {
        //     if(str_replace(url('/'), '', url()->current()) == '/ttma' && isset($notification->data['ttma_id'])){
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/staff' && isset($notification->data['type']) && ($notification->data['type'] == 'ipcr' && $notification->data['userType'] == 'staff')) {
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/faculty' && isset($notification->data['type']) && ($notification->data['type'] == 'ipcr' && $notification->data['userType'] == 'faculty')) {
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/standard/staff' && isset($notification->data['type']) && ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'staff')) {
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/ipcr/standard/faculty' && isset($notification->data['type']) && ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'faculty')) {
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/opcr' && isset($notification->data['type']) && ($notification->data['type'] == 'opcr' && $notification->data['userType'] == 'office')) {
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/standard/opcr' && isset($notification->data['type']) && ($notification->data['type'] == 'standard' && $notification->data['userType'] == 'office')) {
        //         $notification->markAsRead();
        //     } elseif (str_replace(url('/'), '', url()->current()) == '/for-approval' && isset($notification->data['status']) && $notification->data['status'] == 'Submitting'){
        //         $notification->markAsRead();
        //     }
        // }

        $durationS = Duration::orderBy('id', 'DESC')->where('type', 'staff')->where('start_date', '<=', date('Y-m-d'))->first();
        $durationF = Duration::orderBy('id', 'DESC')->where('type', 'faculty')->where('start_date', '<=', date('Y-m-d'))->first();
        $durationO = Duration::orderBy('id', 'DESC')->where('type', 'office')->where('start_date', '<=', date('Y-m-d'))->first();

        $assignments = auth()->user()->ttmas()
                    ->where('duration_id', $durationO->id)
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
                if (isset($notification->data['duration_id']) && ($notification->data['duration_id'] == $durationS->id || $notification->data['duration_id'] == $durationF->id)) {
                    if (isset($notification->data['message'])  && $notification->data['message'] == 'recommended') {
                        break;
                    } else {
                        $users = User::where('office_id', Auth::user()->office_id)->get();
                        foreach ($users as $user) {
                            foreach ($user->account_types as $account_type) {
                                if (str_contains(strtolower($account_type->account_type), 'faculty')){
                                    $faculty = true;
                                    $f = true;
                                }
                                if (str_contains(strtolower($account_type->account_type), 'staff')){
                                    $staff = true;
                                    $s = true;
                                }
                            }
                
                            if ($faculty) {
                                $assessF = Approval::orderBy('id', 'DESC')
                                    ->where('name', 'assess')
                                    ->where('approve_status', 1)
                                    ->where('user_id', $user->id)
                                    ->where('type', 'ipcr')
                                    ->where('duration_id', $durationF->id)
                                    ->where('user_type', 'faculty')
                                    ->first();
                
                                if (isset($assessF)) {
                                    $faculty = true;
                                }
                            } else {
                                $faculty = true;
                            }
                    
                            if ($staff) {
                                $assessS = Approval::orderBy('id', 'DESC')
                                    ->where('name', 'assess')
                                    ->where('approve_status', 1)
                                    ->where('user_id', $user->id)
                                    ->where('type', 'ipcr')
                                    ->where('duration_id', $durationS->id)
                                    ->where('user_type', 'staff')
                                    ->first();
                
                                if (isset($assessS)) {
                                    $staff = true;
                                }
                            } else {
                                $staff = true;
                            }
                            
                            if (isset($faculty) && isset($staff)) {
                                $targ = '';
                                $targ = Target::where('user_id', $user->id)
                                ->where(function ($query) {
                                    return $query->where('duration_id', $durationS->id)
                                            ->orWhere('duration_id', $durationF->id);
                                })
                                ->where(function ($query) {
                                    $query->whereHas('rating', function (\Illuminate\Database\Eloquent\Builder $query) {
                                        return $query->where('average', '<', $this->scoreEq->sat_to);
                                    });
                                })->first();
                                    
                                if($targ) {
                                    if (isset($f)) {
                                        Auth::user()->notify(new RecommendedNotification('recommended', $durationF->id));
                                    } else {
                                        Auth::user()->notify(new RecommendedNotification('recommended', $durationS->id));
                                    }
                                    break;
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

    public function load() {
        $this->amount += 10;
    }

    public function readAll() {
        auth()->user()->unreadNotifications->markAsRead();
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
