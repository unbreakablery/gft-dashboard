<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-user text-primary"></i>
                </span>
                <span class="">Edit Driver</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-default-darker">
                    <h3 class="block-title">Driver Info Form</h3>
                </div>
                <div class="block-content">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="font-w600 m-1"><i class="fa fa-fw fa-times-circle"></i> {{ session('error') }}</p>
                        </div>
                    @endif
                    <form class="js-validation" action="/drivers/save" method="POST" id="driver-form" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="@if (isset($driver)){{ $driver->id }}@endif" />
                        <div class="table-responsive push text-right">
                            <button type="button" class="btn btn-dark view-drivers">
                                <i class="fa fa-list"></i> View Drivers
                            </button>
                            <button type="submit" class="btn btn-primary save-driver">
                                <i class="fa fa-save"></i> Save Driver
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="driver-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Driver ID<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <input type="text" class="form-control" name="driver_id" value="@if (isset($driver)){{ $driver->driver_id }}@endif" placeholder="Enter driver ID.." required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Driver Name<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <input type="text" class="form-control" name="driver_name" value="@if (isset($driver)){{ $driver->driver_name }}@endif" placeholder="Enter driver name.." required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Phone # : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="phone-number-prefix">+</span>
                                                </div>
                                                <input type="number" class="form-control" name="phone" value="@if (isset($driver)){{ $driver->phone }}@endif" placeholder="Enter phone number.." aria-describedby="phone-number-prefix" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">License # : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <input type="text" class="form-control" name="license" value="@if (isset($driver)){{ $driver->license }}@endif" placeholder="Enter driver license.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Address : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <input type="text" class="form-control" name="address" value="@if (isset($driver)){{ $driver->address }}@endif" placeholder="Enter driver address.." />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Price Per Mile : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="price-per-mile-prefix">$</span>
                                                </div>
                                                <input type="number" class="form-control" name="price_per_mile" value="@if (isset($driver)){{ $driver->price_per_mile }}@else{{ 0 }}@endif" placeholder="Enter price per mile.." step="0.0001" min="0" aria-describedby="price-per-mile-prefix" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Work Status : </td>
                                        <td class="text-left" style="width: 80%;">
                                            <select name="work_status" class="form-control">
                                                <option value="1" @if (isset($driver) && $driver->work_status == 1) selected @endif>Working</option>
                                                <option value="0" @if (isset($driver) && $driver->work_status == 0) selected @endif>No longer working</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $('button.view-drivers').click(function() {
        location.href = "/drivers";
    });
});
</script>
</x-app-layout>