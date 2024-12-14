<li class="list-group-item p-0">
    <a class="dropdown-item d-block px-4 py-3" style="white-space: initial" href="{{ route('admin.notification.redirect', $Notification->id) }}">
        <p class="text-span">{{ calculate_elapsed_time($Notification->created_at) . ($Notification->created_at->dayOfWeek > 0 && $Notification->created_at->dayOfWeek < 6 ? ', na ' : ', no ') . $Notification->created_at->dayName . ' às ' . $Notification->created_at->format('H:m') }}</p>

        <div class="d-flex">
            <div class="flex-shrink-0">
                <img src="{{ $Notification->notifier->profile_image }}" alt="user-image" class="user-avtar avtar avtar-s" />
            </div>

            <div class="flex-grow-1 ms-3">
                {!! $Notification->message !!}
            </div>
        </div>
    </a>
</li>
