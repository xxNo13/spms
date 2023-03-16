<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/logo.png')">
    
    @php
        $head = false;
        $agency = false;
        $staff = false;
        $faculty = false;
        $pmo = false;
        $hrmo = false;
        $bool = true;
        $president = false;
    @endphp
    @foreach (Auth::user()->offices as $office)
        @if ($office->pivot->isHead)
            @php
                $head = true;
            @endphp
        @endif  
        @if ((str_contains(strtolower($office->office_name), 'college president'))) 
            @php
                $president = true;
            @endphp
        @endif
        @if (str_contains(strtolower($office->office_name), 'planning'))
            @php
                $pmo = true;
            @endphp
        @endif  
        @if (str_contains(strtolower($office->office_name), 'hr') || str_contains(strtolower($office->office_abbr), 'hr') || auth()->user()->id == 1)
            @php
                $hrmo = true;
            @endphp
        @endif  
    @endforeach
    @foreach (Auth::user()->account_types as $account_type)
        @if (str_contains(strtolower($account_type->account_type), 'staff'))
            @php
                $staff = true;
            @endphp
        @endif
        @if (str_contains(strtolower($account_type->account_type), 'faculty'))
            @php
                $faculty = true;
            @endphp
        @endif
    @endforeach
    <!-- Add Sidebar Menu Items Here -->
    

    <x-maz-sidebar-item alias="dashboard" name="Dashboard" :link="route('dashboard')" icon="bi bi-grid-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="archives" name="Archives" :link="route('archives')" icon="bi bi-archive-fill"></x-maz-sidebar-item>
    @if ($pmo) 
        <x-maz-sidebar-item alias="configure" name="Configure" :link="route('configure')" icon="bi bi-nut-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item alias="assign.pmt" name="Assign PMT" :link="route('assign.pmt')" icon="bi bi-person-plus-fill"></x-maz-sidebar-item>
    @endif

    @if ($head)
    <x-maz-sidebar-item alias="for.approval" name="For Approval" :link="route('for.approval')" icon="bi bi-person-lines-fill"></x-maz-sidebar-item>
    @endif

    <x-maz-sidebar-item alias="ttma" name="Tracking Tool for Monitoring Assignment" :link="route('ttma')" icon="bi bi-clipboard2-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="recommendation.list" name="List of Recommendation" :link="route('recommendation.list')" icon="bi bi-person-video3"></x-maz-sidebar-item>
    
    @if ($faculty || $staff || $pmo || $hrmo || $head)
        <x-maz-sidebar-item alias="ipcr" link="#" name="IPCR" icon="bi bi-clipboard2-data-fill">
            @if ($faculty)
                <x-maz-sidebar-sub-item name="Faculty" :link="route('ipcr.faculty')"></x-maz-sidebar-sub-item>
                <x-maz-sidebar-sub-item name="Standards for Faculty" :link="route('ipcr.standard.faculty')"></x-maz-sidebar-sub-item>
            @endif

            @if ($staff)
                <x-maz-sidebar-sub-item name="Staff" :link="route('ipcr.staff')"></x-maz-sidebar-sub-item>
                <x-maz-sidebar-sub-item name="Standards for Staff" :link="route('ipcr.standard.staff')"></x-maz-sidebar-sub-item>
            @endif

            @if ($pmo || $hrmo || $head)
                <x-maz-sidebar-sub-item name="Listing for Faculty" :link="route('ipcr.listing.faculty')"></x-maz-sidebar-sub-item>
                <x-maz-sidebar-sub-item name="Listing Standards for Faculty" :link="route('ipcr.listing.standard.faculty')"></x-maz-sidebar-sub-item>
            @endif
        </x-maz-sidebar-item>
    @endif
    
    @if (($head && !$president) || $pmo || $hrmo)
        <x-maz-sidebar-item alias="opcr" link="#" name="OPCR" icon="bi bi-clipboard-data-fill">
            @if ($head && !$president)
                <x-maz-sidebar-sub-item name="OPCR" :link="route('opcr.opcr')"></x-maz-sidebar-sub-item>
                <x-maz-sidebar-sub-item name="Standards for OPCR" :link="route('opcr.standard')"></x-maz-sidebar-sub-item>
            @endif

            @if ($pmo || $hrmo)
                <x-maz-sidebar-sub-item name="Listing for OPCR" :link="route('opcr.listing')"></x-maz-sidebar-sub-item>
                <x-maz-sidebar-sub-item name="Listing Standards for OPCR" :link="route('opcr.listing.standard')"></x-maz-sidebar-sub-item>
            @endif
        </x-maz-sidebar-item>
    @endif

    @if ($head)    
        <x-maz-sidebar-item alias="trainings" name="Trainings" :link="route('trainings')" icon="bi bi-person-workspace"></x-maz-sidebar-item>
        <x-maz-sidebar-item alias="subordinates" name="Subordinates" :link="route('subordinates')" icon="bi bi-people-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item alias="recommended.for.training" name="Recommended for Trainings" :link="route('recommended.for.training')" icon="bi bi-person-rolodex"></x-maz-sidebar-item>
    @endif
    
    @if ($hrmo)
        <x-maz-sidebar-item alias="register" name="Register User" :link="route('register.user')" icon="bi bi-person-plus-fill"></x-maz-sidebar-item>
    @endif
</x-maz-sidebar>