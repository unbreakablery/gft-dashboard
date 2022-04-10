<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item"><h3 class="font-w700 mb-0">Weekly Schedule</h3></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx text-primary font-w700 h3" href="">Upload</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title">Upload Schedule</h3>
                </div>
                <div class="block-content">
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>You can choose a <strong>Excel</strong> or <strong>CSV</strong> file with weekly schedule.</p>
                        </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{!! session('success') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <div class="row push">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <p class="font-size-sm text-muted text-right">
                                Schedule File:
                            </p>
                        </div>
                        <div class="col-xl-4 col-lg-8 col-md-4 col-sm-4 col-xs-12 overflow-hidden">
                            <form action="/schedule/upload" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" id="upload-file" name="upload-file" accept=".xlsx,.xls,.csv">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-upload text-mute"></i> Upload
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
