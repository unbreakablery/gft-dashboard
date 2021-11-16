<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
</style>
<div class="hero-static d-flex align-items-center">
    <div class="w-100">
        <div class="bg-white">
            <div class="content content-full">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                        <div class="text-center mb-5">
                            <p class="mb-2">
                                <i class="fa fa-2x fa-calendar text-primary"></i>
                            </p>
                            <h1 class="h3 mb-1 font-w600 text-uppercase">
                                Weekly Schedule For Driver
                            </h1>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-lg btn-alt-primary" href="javascript:window.history.back(-1);">
                                <i class="fa fa-arrow-left mr-1"></i> Schedule List
                            </a>
                        </div>
                        <ul class="list-group push">
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Year:
                                <span class="font-w600"><strong>{{ $schedule->year_num }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Week:
                                <span class="font-w600"><strong>{{ $schedule->week_num }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Date:
                                <span class="font-w600"><strong>{{ $schedule->from_date }} - {{ $schedule->to_date }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver Name:
                                <span class="font-w600"><strong>{{ $schedule->driver_name }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver ID:
                                <span class="badge badge-success">{{ $schedule->driver_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tractor ID:
                                <span class="badge badge-pill badge-primary">{{ $schedule->tractor_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                TCheck #:
                                <span class="badge badge-info">{{ $schedule->tcheck }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Spare Unit (if needed):
                                <span class="font-w600">{{ $schedule->spare_unit }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Saturday:
                                @if ($schedule->saturday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->saturday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->saturday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sunday:
                                @if ($schedule->sunday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->sunday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->sunday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Monday:
                                @if ($schedule->monday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->monday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->monday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Tuesday:
                                @if ($schedule->tuesday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->tuesday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->tuesday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Wednesday:
                                @if ($schedule->wednesday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->wednesday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->wednesday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Thursday:
                                @if ($schedule->thursday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->thursday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->thursday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Friday:
                                @if ($schedule->friday == 'OFF')
                                    <span class="badge badge-dark">{{ $schedule->friday }}</span>
                                @else
                                    <span class="font-w600">{{ $schedule->friday }}</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Road Side Service:
                                <span class="font-w600 text-primary">{{ $schedule->fleet_net }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
