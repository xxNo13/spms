<div class='card' submit="updatePassword">
    <div class="card-header">
        <h4 class="card-title">{{ __('Update Password') }}</h4>
        <p class="card-description">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
        <ul>
            <li>8 characters minimum</li>
            <li>at least 1 special characters</li>
            <li>at least 1 number</li>
            <li>at least 1 upper case character</li>
        </ul>
    </div>
    <div class="card-body">
    
        <x-maz-alert color="success" on="saved">
            {{ __('Saved.') }}
        </x-maz-alert>
        <form wire:submit.prevent="updatePassword">
            
            <div class="form-group">
                <label for="current_password">{{ __('Current Password') }}</label>
                <div class="position-relative">
                    <input id="current_password" type="password"  class="password-padding form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" name="current_password" wire:model.defer="state.current_password" autocomplete="current-password" >
                    <i class="bi bi-eye-slash password-show-hide" id="toggleshow1"></i>
                </div>
                <x-maz-input-error for="current_password" />
            </div>
            
            <div class="form-group">
                <label for="password">{{ __('New Password') }}</label>
                <div class="position-relative">
                    <input id="password" type="password"  class="password-padding form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" wire:model.defer="state.password" autocomplete="new-password" >
                    <i class="bi bi-eye-slash password-show-hide" id="toggleshow2"></i>
                </div>
                <x-maz-input-error for="password" />
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <div class="position-relative">
                    <input id="password_confirmation" type="password"  class="password-padding form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" name="password" wire:model.defer="state.password_confirmation" autocomplete="new-password" >
                    <i class="bi bi-eye-slash password-show-hide" id="toggleshow3"></i>
                </div>
                <x-maz-input-error for="password_confirmation" />
            </div>

            <button class="btn btn-primary float-end mt-2"  wire:loading.attr="disabled" wire:target="updatePassword">Save</button>
        </form>

    </div>



</div>
