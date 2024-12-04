<div class="border-bottom card-body last-response">
    <div class="row">
        <div class="col-sm-auto mb-3 mb-sm-0">
            <div class="d-sm-inline-block d-flex align-items-center">
                <img class="wid-60 img-radius mb-2" src="{{ $TicketResponse->user->profile_image }}" alt="Generic placeholder image " />
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="">
                        <h4 class="d-inline-block">Você</h4>
                        <span class="badge bg-light-secondary">replied</span>
                        <p class="text-muted">
                            {{ calculate_elapsed_time($TicketResponse->created_at) . ($TicketResponse->created_at->dayOfWeek > 0 && $TicketResponse->created_at->dayOfWeek < 6 ? ', na ' : ', no ') . $TicketResponse->created_at->dayName . ' às ' . $TicketResponse->created_at->format('H:m') }}
                        </p>
                    </div>
                </div>

                <div class="col-auto">
                    <ul class="list-unstyled mb-0">
                        <li class="d-inline-block f-20 me-1">
                            <a href="#" data-bs-toggle="tooltip" title="Edit">
                                <i data-feather="edit" class="icon-svg-success wid-20"></i>
                            </a>
                        </li>
                        <li class="d-inline-block f-20">
                            <a href="#" data-bs-toggle="tooltip" title="Delete">
                                <i data-feather="trash-2" class="icon-svg-danger wid-20"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="">
                {!! $TicketResponse->response !!}
            </div>
        </div>
    </div>
</div>
