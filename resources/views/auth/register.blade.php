<x-app-layout>
    <div id="auth-left">
        <h1 class="auth-title">Register Account</h1>
        <p class="auth-subtitle mb-5">Fill up form to register account.</p>
        
        @if (session('message'))
            <div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="" method="POST">
            @csrf
            <div class="form-group position-relative has-icon-left mb-4">
                <input type="text" class="form-control form-control-xl" value="{{ old('name') ? old('name') : "" }}" name="name" placeholder="Fullname">
                <div class="form-control-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
            <div class="form-group position-relative has-icon-left mb-4">
                <input type="email" class="form-control form-control-xl" value="{{ old('email') ? old('email') : "" }}" name="email" placeholder="Email">
                <div class="form-control-icon">
                    <i class="bi bi-envelope"></i>
                </div>
            </div>

            <hr>

            <!-- Offices -->
            <div class="form-group">
                <label for="office">Offices</label>
                <select name="office[]" id="office" class="form-select" multiple="multiple">
                    <option></option>
                    <livewire:offices-livewire />
                </select>
            </div>

            <!-- Account Types -->
            <div class="form-group" wire:ignore>
                <label for="account_type">Account Types</label>
                <select name="account_type[]" id="account_type" class="form-select" multiple="multiple">
                    <option></option>
                    <livewire:account-types-livewire />
                </select>
            </div>

            @push('script')
                <script>
                    $('#office').select2({
                        placeholder: "Select an Office",
                        multiple: true,
                    });
                    
                    $('#account_type').select2({
                        placeholder: "Select an Account Type",
                        multiple: true,
                    });

                    $('select[name="account_type[]"]').on('change',function(){
                        var $this = $(this); 
                        $('select[name="account_type[]"]').find('option[value="1"]').prop('disabled', ($this.val().indexOf("2") > -1) ); // disabled or enabled 
                        $('select[name="account_type[]"]').find('option[value="2"]').prop('disabled', ($this.val().indexOf("1") > -1) ); // disabled or enabled 
                    });

                    $('select[name="account_type[]"]').trigger('change');
                </script>
            @endpush
            
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif
            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Register</button>
        </form>
    </div>
</x-app-layout>
