<div>
    @foreach ($offices as $office)
        <!-- is Head -->
        <div class="form-group">
            <input id="office" type="checkbox" class="form-check-input" wire:model.defer="state.office" autocomplete="office" >
            <label for="office">Head of the office?</label>
            <x-maz-input-error for="office" />
        </div>
    @endforeach
</div>
