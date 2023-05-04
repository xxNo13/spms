<div>
    <div class="form-group">
        <label for="office">Offices</label>
        <select name="office[]" wire:model="office_selected" id="office" class="form-select" multiple="multiple">
            <option></option>
            @foreach ($offices as $office)
                <option value="{{$office->id}}" {{ (collect(old('office'))->contains($office->id)) ? 'selected':'' }}>{{$office->office_name}}</option>
            @endforeach
        </select>
    </div>

    <!-- Institute -->
    <div class="form-group">
        <label for="institute">Institute</label>
        <select name="institute" wire:model="institute_id" wire:change="institute_set" id="institute" class="form-select">
            <option></option>
            @foreach ($institutes as $institute)
                <option value="{{$institute->id}}" {{ (collect(old('institute'))->contains($institute->id)) ? 'selected':'' }}>{{$institute->institute_name}}</option>
            @endforeach
        </select>
    </div>
    

    @push('script')
        <script>
            $("#office").select2({
                multiple: true,
                placeholder: "Select an Office.",
            });

            $('#office').on('change', function () {
                var data = $('#office').select2("val");
                @this.set('office_selected', data);
            });

            document.addEventListener("livewire:load", function (event) {
                Livewire.hook('message.processed', function (message, component){
                    $("#office").select2({
                        multiple: true,
                        placeholder: "Select an Office.",
                    });
                    
                    $('#institute').select2({
                        placeholder: "Select an Institute",
                    });
                });
            });
        </script>
    @endpush
</div>
