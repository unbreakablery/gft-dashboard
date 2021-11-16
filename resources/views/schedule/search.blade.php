<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="far fa-calendar text-primary"></i>
                </span>
                <span class="">Weekly Schedule</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Search Form</h3>
        </div>
        <div class="block-content">
            <form action="/schedule/search" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            @php
                            if (!isset($year_num)) {
                                $year_num = Date("Y");
                            }
                            if (!isset($week_num)) {
                                $week_num = Date("W");
                            }
                            @endphp
                            <label for="year-num">Year <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="year-num" name="year-num" required>
                                @for ($i = 2019; $i <= Date("Y"); $i++)
                                    <option value="{{ $i }}" @if ($i == $year_num) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <label for="week-num">Week <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="week-num" name="week-num" required>
                                @for ($i = 1; $i <= 52; $i++)
                                    <option value="{{ $i }}" @if ($i == $week_num) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <label for="driver-id">Driver ID :</label>
                            <input type="text" 
                                class="form-control" 
                                name="driver-id" 
                                id="driver-id" 
                                value="{{ $driver_id }}"
                                placeholder="Driver ID" 
                            />
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="view-payroll">&nbsp;</label>
                            <button class="form-control btn btn-dark ml-auto mr-3" id="view-schedule" name="view-schedule">View Schedule</button>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">
                            <strong><i class="far fa-user"></i></strong>
                        </th>
                        <th class="text-center"><strong>Driver Name</strong></th>
                        <th class="d-none d-md-table-cell text-center" style="width: 10%;"><strong>Driver ID</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 20%;"><strong>Tractor ID</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>TCheck #</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 15%;"><strong>Spare Unit</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 15%;"><strong>Road Side Service</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $idx => $schedule)
                    <tr>
                        <td class="text-center">
                            {{ $idx + 1 }}
                        </td>
                        <td class="font-w600 font-size-sm text-left">
                            <a href="/schedule/get/{{ $schedule->id }}">{{ $schedule->driver_name }}</a>
                            @if ($schedule->driver->work_status == 0)
                                <span class="badge badge-pill badge-danger">No longer working</span>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            {{ $schedule->driver_id }}
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            {{ $schedule->tractor_id }}
                        </td>
                        <td class="d-none d-sm-table-cell text-center">
                            <span class="badge badge-primary">&nbsp;{{ $schedule->tcheck }}&nbsp;</strong>
                        </td>
                        <td class="d-none d-sm-table-cell text-right">
                            {{ $schedule->spare_unit }}
                        </td>
                        <td class="text-center text-success">
                            <strong>{{ $schedule->fleet_net }}</strong>
                        </td>
                    </tr>
                    @endforeach
                    @if (count($schedules) == 0)
                    <tr>
                        <td colspan="7" class="text-center">
                            <span class="text-warning">No Schedules</span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        
    });
});
</script>
</x-app-layout>
