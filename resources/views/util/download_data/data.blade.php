<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-excel text-primary"></i>
                </span>
                <span class="">Download Data</span>
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
            <form id="search-form" action="" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="year-num">Year <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="year-num" name="year-num" required>
                                @for ($i = 2019; $i <= Date("Y"); $i++)
                                    <option value="{{ $i }}" {{ (isset($year_num)) ? ($i == $year_num ? "selected" : "") : ($i == Date("Y") ? "selected" : "")}}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="week-num">Week <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="week-num" name="week-num" required>
                                @for ($i = 1; $i <= 52; $i++)
                                    <option value="{{ $i }}" {{ (isset($week_num)) ? ($i == $week_num ? "selected" : "") : ($i == Date("W") - 1 ? "selected" : "")}}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <label for="key-metric">Key Metrics <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="key-metric" name="key-metric" required>
                                <option value="miles-total" @if (isset($key_metric) && $key_metric == "miles-total") selected @endif>Total Miles</option>
                                <option value="miles-driver" @if (isset($key_metric) && $key_metric == "miles-driver") selected @endif>Miles by driver</option>
                                <option value="miles-vehicle" @if (isset($key_metric) && $key_metric == "miles-vehicle") selected @endif>Miles by vehicle</option>
                                <option value="trips-driver" @if (isset($key_metric) && $key_metric == "trips-driver") selected @endif>Trips by driver</option>
                                <option value="mpg-vehicle" @if (isset($key_metric) && $key_metric == "mpg-vehicle") selected @endif>MPG by vehicle</option>
                                <option value="revenue" @if (isset($key_metric) && $key_metric == "revenue") selected @endif>Revenue</option>
                                <option value="fuelcost-total" @if (isset($key_metric) && $key_metric == "fuelcost-total") selected @endif>Total Fuel Cost</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="search-download-data">&nbsp;</label>
                            <button type="button" class="form-control btn btn-dark ml-auto mr-3" id="search-download-data" name="search-download-data">
                                <i class="fa fa-search"></i> Search Data
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="download-data">&nbsp;</label>
                            <button type="button" class="form-control btn btn-success ml-auto mr-3" id="download-data" name="download-data">
                                <i class="fa fa-download"></i> Download Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @if (isset($key_metric))
                @switch($key_metric)
                    @case('miles-total')
                        @include('util.download_data.mile_total')
                        @break
                
                    @case('miles-driver')
                        @include('util.download_data.mile_driver')
                        @break

                    @case('miles-vehicle')
                        @include('util.download_data.mile_vehicle')
                        @break

                    @case('trips-driver')
                        @include('util.download_data.trips_driver')
                        @break

                    @case('mpg-vehicle')
                        @include('util.download_data.mpg_vehicle')
                        @break

                    @case('revenue')
                        @include('util.download_data.revenue')
                        @break

                    @case('fuelcost-total')
                        @include('util.download_data.fuelcost_total')
                        @break

                    @default
                        InActive
                @endswitch
            @else
            <div class="alert alert-info alert-dismissable" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> You can search historical data and download as Excel file !</p>
            </div>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('#search-download-data').click(function() {
            $("form#search-form").attr('action', "{{ route('util.download-data.search') }}");
            $("form#search-form").submit();
        });
        $('#download-data').click(function() {
            $("form#search-form").attr('action', "{{ route('util.download-data.download') }}");
            $("form#search-form").submit();
        });
    });
});
</script>
</x-app-layout>
