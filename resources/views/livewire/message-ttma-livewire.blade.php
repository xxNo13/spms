<div>
    <div wire:poll.visible class="overflow-auto" style="height: 225px;">
        @php
            $user_id = 0;
        @endphp
        @foreach ($ttma->messages as $message) 
            @if ($message->user_id == auth()->user()->id) 
                <div class="my-3 ms-auto" style="width: fit-content; max-width: 80%;">
                    <small class="rounded text-white bg-primary p-2">
                        {{ $message->message }}
                    </small>
                </div>
            @else
                @if ($user_id != $message->user_id)
                    <div class="mb-1">
                        {{ $message->user->name }}:
                    </div>
                @endif
                <div class="mb-3">
                    <small class="rounded text-white bg-secondary p-2" style="width: fit-content; max-width: 80%;">
                        {{ $message->message }}
                    </small>
                </div>
            @endif
            @php
                $user_id = $message->user_id;
            @endphp
        @endforeach
    </div>
</div>
