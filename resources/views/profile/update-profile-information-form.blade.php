<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Profile Information') }}</h4>
            <p class="card-description">{{ __('Update your account\'s profile information and email address.') }}</p>
        </div>
        <div class="card-body">
            
            <x-maz-alert class="mr-3" on="saved" color='success'>
                Saved
            </x-maz-alert>
            <form wire:submit.prevent="updateProfileInformation">

                @foreach (auth()->user()->offices as $office)
                    <!-- is Head -->
                    <div class="form-group">
                        <label for="office.pivot.isHead">Head of the {{ $office->pivot->isHead }}</label>
                        <input id="office.pivot.isHead" type="checkbox" class="form-check-input" wire:model.defer="state.office.pivot.isHead" autocomplete="office.pivot.isHead" >
                        <x-maz-input-error for="office.pivot.isHead" />
                    </div>
                @endforeach

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" wire:model.defer="state.name" autocomplete="name" >
                    <x-maz-input-error for="name" />
                </div>

                <!-- Email -->
                <div class="col-span-6 sm:col-span-4">
                    <label for="email">Email</label>
                    <input type="email" name="email " id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" wire:model.defer="state.email" >
                    <x-maz-input-error for="email" />
                </div>

                <button class="btn btn-primary float-end mt-2"  wire:loading.attr="disabled" wire:target="photo">Save</button>
            </form>
        </div>
    </div>
</section>
