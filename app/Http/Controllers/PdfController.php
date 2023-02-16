<?php

namespace App\Http\Controllers;

use App\Models\Ttma;
use App\Models\User;
use App\Models\Funct;
use App\Models\Duration;
use App\Models\Percentage;
use Illuminate\Http\Request;
use App\Models\SubPercentage;
use App\Models\ScoreEquivalent;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PdfController extends Controller
{
    public function ipcrFaculty($id, Request $request) {
        if (isset($request->duration_id)) {
            $duration = Duration::fund($request->duration_id);
        } else {
            $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        }
        $percentage = Percentage::where('type', 'ipcr')->where('user_type', 'faculty')->where('user_id', null)->first();
        $sub_percentages = SubPercentage::where('type', 'ipcr')->where('user_type', 'faculty')->where('user_id', null)->get();

        $scoreEquivalent = ScoreEquivalent::first();

        $user = User::find($id);

        if ($duration) {
            $approval = $user->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $duration->id)->where('user_type', 'faculty')->first();
            
            $approval_reviewer = User::find($approval->review_id);
            $approval_approver = User::find($approval->approve_id);

            $assess = $user->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'ipcr')->where('duration_id', $duration->id)->where('user_type', 'faculty')->first();
            
            $assess_reviewer = User::find($assess->review_id);
            $assess_approver = User::find($assess->approve_id);
        }

        $data = [
            'functs' => Funct::all(),
            'approval' => $approval,
            'approval_reviewer' => $approval_reviewer,
            'approval_approver' => $approval_approver,
            'assess' => $assess,
            'assess_reviewer' => $assess_reviewer,
            'assess_approver' => $assess_approver,
            'duration' => $duration,
            'percentage' => $percentage,
            'sub_percentages' => $sub_percentages,
            'scoreEquivalent' => $scoreEquivalent,
            'user_id' => $user->id,
            'user' => $user,
        ];

        $pdf = PDF::loadView('print.ipcr-faculty', $data)->setPaper('a4','landscape');
        return $pdf->stream('ipcr-faculty.pdf');
    }
    
    public function ipcrStaff($id, Request $request) {
        if (isset($request->duration_id)) {
            $duration = Duration::fund($request->duration_id);
        } else {
            $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        }
        $percentage = Percentage::where('type', 'ipcr')->where('user_type', 'staff')->where('user_id', null)->first();
        $sub_percentages = SubPercentage::where('type', 'ipcr')->where('user_type', 'staff')->where('user_id', null)->get();

        $scoreEquivalent = ScoreEquivalent::first();

        $user = User::find($id);

        if ($duration) {
            $approval = $user->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'ipcr')->where('duration_id', $duration->id)->where('user_type', 'staff')->first();
            
            $approval_reviewer = User::find($approval->review_id);
            $approval_approver = User::find($approval->approve_id);

            $assess = $user->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'ipcr')->where('duration_id', $duration->id)->where('user_type', 'staff')->first();
            
            $assess_reviewer = User::find($assess->review_id);
            $assess_approver = User::find($assess->approve_id);
        }

        $data = [
            'functs' => Funct::all(),
            'approval' => $approval,
            'approval_reviewer' => $approval_reviewer,
            'approval_approver' => $approval_approver,
            'assess' => $assess,
            'assess_reviewer' => $assess_reviewer,
            'assess_approver' => $assess_approver,
            'duration' => $duration,
            'percentage' => $percentage,
            'sub_percentages' => $sub_percentages,
            'scoreEquivalent' => $scoreEquivalent,
            'user_id' => $user->id,
            'user' => $user,
        ];

        $pdf = PDF::loadView('print.ipcr-staff', $data)->setPaper('a4','landscape');
        return $pdf->stream('ipcr-staff.pdf');
    }

    public function opcr($id, Request $request) {
        if (isset($request->duration_id)) {
            $duration = Duration::fund($request->duration_id);
        } else {
            $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        }
        $percentage = Percentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->first();
        $sub_percentages = SubPercentage::where('type', 'opcr')->where('user_type', 'office')->where('user_id', null)->get();

        $scoreEquivalent = ScoreEquivalent::first();

        $user = User::find($id);

        if ($duration) {
            $approval = $user->approvals()->orderBy('id', 'DESC')->where('name', 'approval')->where('type', 'opcr')->where('duration_id', $duration->id)->where('user_type', 'office')->first();
            
            $approval_reviewer = User::find($approval->review_id);
            $approval_approver = User::find($approval->approve_id);

            $assess = $user->approvals()->orderBy('id', 'DESC')->where('name', 'assess')->where('type', 'opcr')->where('duration_id', $duration->id)->where('user_type', 'office')->first();
            
            $assess_reviewer = User::find($assess->review_id);
            $assess_approver = User::find($assess->approve_id);
        }

        $data = [
            'functs' => Funct::all(),
            'approval' => $approval,
            'approval_reviewer' => $approval_reviewer,
            'approval_approver' => $approval_approver,
            'assess' => $assess,
            'assess_reviewer' => $assess_reviewer,
            'assess_approver' => $assess_approver,
            'duration' => $duration,
            'percentage' => $percentage,
            'sub_percentages' => $sub_percentages,
            'scoreEquivalent' => $scoreEquivalent,
            'user_id' => $user->id,
            'user' => $user
        ];

        $pdf = PDF::loadView('print.opcr', $data)->setPaper('a4','landscape');
        return $pdf->stream('opcr.pdf');
    }

    public function ttma() {
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $ttmas = Ttma::where('duration_id', $duration->id)->where('head_id', auth()->user()->id)->get();

        $data = [
            'ttmas' => $ttmas,
        ];

        
        $pdf = PDF::loadView('print.ttma', $data)->setPaper('a4');
        return $pdf->stream('ttma.pdf');
    }

    public function rankingPerOffice($office_id) {
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $percentages = Percentage::where('type', 'ipcr')->where('userType', 'faculty')->where('duration_id', $duration->id)->get();

        $users = User::query();

        $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) use ($office_id) {
            return $query->where('id', $office_id);
        });
        
        $users = $users->distinct()->get();

        $functs = Funct::all();
        $scoreEquivalent = ScoreEquivalent::first();

        $data = [
            'functs' => $functs,    
            'duration' => $duration,
            'percentages' => $percentages,
            'scoreEq$scoreEquivalent' => $scoreEquivalent,
            'users' => $users
        ];

        
        $pdf = PDF::loadView('print.rankings', $data)->setPaper('a4');
        return $pdf->stream('rankings.pdf');
    }

    public function rankingFaculty() {
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $percentages = Percentage::where('type', 'ipcr')->where('userType', 'faculty')->where('duration_id', $duration->id)->get();

        $users = User::query();

        $users->orwhereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query) {
            return $query->where('account_type', 'LIKE', '%faculty%');
        });
        
        $users = $users->distinct()->get();

        $functs = Funct::all();
        $scoreEquivalent = ScoreEquivalent::first();

        $data = [
            'functs' => $functs,    
            'duration' => $duration,
            'percentages' => $percentages,
            'scoreEq$scoreEquivalent' => $scoreEquivalent,
            'users' => $users
        ];

        
        $pdf = PDF::loadView('print.rankings', $data)->setPaper('a4');
        return $pdf->stream('rankings.pdf');
    }

    public function rankingStaff() {
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $percentages = Percentage::where('type', 'ipcr')->where('userType', 'staff')->where('duration_id', $duration->id)->get();

        $users = User::query();

        $users->orwhereHas('account_types', function(\Illuminate\Database\Eloquent\Builder $query) {
            return $query->where('account_type', 'LIKE', '%staff%');
        });
        
        $users = $users->distinct()->get();

        $functs = Funct::all();
        $scoreEquivalent = ScoreEquivalent::first();

        $data = [
            'functs' => $functs,    
            'duration' => $duration,
            'percentages' => $percentages,
            'scoreEq$scoreEquivalent' => $scoreEquivalent,
            'users' => $users
        ];

        
        $pdf = PDF::loadView('print.rankings', $data)->setPaper('a4');
        return $pdf->stream('rankings.pdf');
    }

    public function rankingOpcr() {
        $duration = Duration::orderBy('id', 'DESC')->where('start_date', '<=', date('Y-m-d'))->first();
        $percentages = Percentage::where('type', 'ipcr')->where('userType', 'staff')->where('duration_id', $duration->id)->get();

        $users = User::query();

        $users->orwhereHas('offices', function(\Illuminate\Database\Eloquent\Builder $query) {
            return $query->where('isHead', true);
        });
        
        $users = $users->distinct()->get();

        $functs = Funct::all();
        $scoreEquivalent = ScoreEquivalent::first();

        $data = [
            'functs' => $functs,    
            'duration' => $duration,
            'percentages' => $percentages,
            'scoreEq$scoreEquivalent' => $scoreEquivalent,
            'users' => $users
        ];

        
        $pdf = PDF::loadView('print.rankings-opcr', $data)->setPaper('a4');
        return $pdf->stream('rankings.pdf');
    }
}
