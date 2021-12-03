<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-csv text-primary"></i>
                </span>
                <span class="">Edit Rate</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row justify-content-lg-center">
        <div class="col-lg-8">
            <div class="block block-themed">
                <div class="block-header bg-primary-darker">
                    <h3 class="block-title">Edit Form</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizonal" action="/payroll/rate/save" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="{{ $rate->id }}" />
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="driver-id">Driver ID : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="driver-id" name="driver-id" value="{{ $rate->driver_id }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="driver-name">Driver Name : </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="driver-name" name="driver-name" value="{{ $rate->driver_name }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="price-per-mile">Price Per Mile <span class="text-danger">*</span> : </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" class="form-control text-center" id="price-per-mile" name="price-per-mile" value="{{ number_format($rate->price_per_mile, 2) }}" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <button type="button" class="btn btn-light ml-auto mr-3" id="cancel-rate" name="cancel-rate" onclick="javascript:window.history.back(-1);">Cancel</button>
                            <button class="btn btn-dark mr-3" id="update-rate" name="update-rate">Update Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
