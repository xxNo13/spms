<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/logo.png')">
    
    @php
        $head = false;
        $agency = false;
        $staff = false;
        $faculty = false;
        $pmo = false;
        $hrmo = false;
        $bool = true;
    @endphp
    @if (Auth::user()->isHead)
        @php
            $head = true;
        @endphp
        @foreach (Auth::user()->offices as $office)
            @if ($office->office_name == 'Office of the President')
                @php
                    $head = true;
                @endphp
            @endif  
            @if ($office->office_abbr == 'PMO')
                @php
                    $pmo = true;
                @endphp
            @endif  
            @if ($office->office_abbr == 'HRMO')
                @php
                    $hrmo = true;
                @endphp
            @endif  
        @endforeach
    @endif
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
    
    
    <x-maz-sidebar-item alias="ipcr" name="IPCR" icon="bi bi-clipboard2-data-fill">
        <x-maz-sidebar-sub-item name="Faculty" :link="route('ipcr.faculty')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Staff" :link="route('ipcr.staff')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Standards for Staff" :link="route('ipcr.standard.staff')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Listing for Faculty" :link="route('ipcr.listing.faculty')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Standards for Faculty" :link="route('ipcr.standard.faculty')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    <x-maz-sidebar-item alias="opcr" name="OPCR" icon="bi bi-clipboard-data-fill">
        <x-maz-sidebar-sub-item name="OPCR" :link="route('opcr.opcr')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Listing for OPCR" :link="route('opcr.listing')"></x-maz-sidebar-sub-item>
        <x-maz-sidebar-sub-item name="Standards for OPCR" :link="route('opcr.standard')"></x-maz-sidebar-sub-item>
    </x-maz-sidebar-item>
    <x-maz-sidebar-item alias="ttma" name="Tracking Tool for Monitoring Assignment" :link="route('ttma')" icon="bi bi-clipboard2-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="configure" name="Configure" :link="route('configure')" icon="bi bi-nut-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="trainings" name="Trainings" :link="route('trainings')" icon="bi bi-person-workspace"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="subordinates" name="Subordinates" :link="route('subordinates')" icon="bi bi-people-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="recommendation.list" name="List of Recommendation" :link="route('recommendation.list')" icon="bi bi-person-video3"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="recommended.for.training" name="Recommended for Trainings" :link="route('recommended.for.training')" icon="bi bi-person-rolodex"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="for.approval" name="For Approval" :link="route('for.approval')" icon="bi bi-person-lines-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item alias="assign.pmt" name="Assign PMT" :link="route('assign.pmt')" icon="bi bi-person-plus-fill"></x-maz-sidebar-item>
    
    {{-- 
    @if ($pmo)
        <x-maz-sidebar-item name="Agency/Organization's Target" :link="route('agency.target')" icon="bi bi-person-circle"></x-maz-sidebar-item>
    @endif
    @if ($head || $agency)
    @endif
    @if ($head)
        <x-maz-sidebar-item name="OPCR" :link="route('opcr')" icon="bi bi-clipboard2-data-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item name="Standard - OPCR" :link="route('standard.opcr')" icon="bi bi-clipboard-data-fill"></x-maz-sidebar-item>
    @endif
    @if ($staff)
        <x-maz-sidebar-item name="IPCR - Staff" :link="route('ipcr.staff')" icon="bi bi-clipboard2-data-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item name="Standard - Staff" :link="route('standard.staff')" icon="bi bi-clipboard-data-fill"></x-maz-sidebar-item>
    @endif
    @if ($faculty)
        <x-maz-sidebar-item name="IPCR - Faculty" :link="route('ipcr.faculty')" icon="bi bi-clipboard2-data-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item name="Standard - Faculty" :link="route('standard.faculty')" icon="bi bi-clipboard-data-fill"></x-maz-sidebar-item>
    @endif
    @if ($agency || $hrmo || $pmo)
        <x-maz-sidebar-item name="Archive" :link="route('archive')" icon="bi bi-archive-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item name="Listing of Faculty IPCR" :link="route('ipcr.add.faculty')" icon="bi bi-clipboard2-data-fill"></x-maz-sidebar-item>
    @endif
    @if ($hrmo)
        <x-maz-sidebar-item name="Register User" :link="route('register.user')" icon="bi bi-person-plus-fill"></x-maz-sidebar-item>
    @endif --}}
</x-maz-sidebar>