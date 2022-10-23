@extends('pages.admin.app')
@section('js-after')
    <script defer src="{{ asset('/js/admin.app.js') }}"></script>
    <link href="{{ asset('/extensions/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <script defer src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script defer src="{{ asset('/extensions/jquery.dataTables.min.js') }}"></script>
    <script defer src="{{ asset('/extensions/dataTables.bootstrap5.min.js') }}"></script>
@endsection
@section('admin-content')
    <div class="row mt-5">
        <div class="col-12">
            <h1 class="text-center mb-5">Contributors</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#contributor-form" aria-expanded="true" aria-controls="contributor-form">
                            Create Contributor
                        </button>
                    </h2>
                    <div id="contributor-form" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="contributor form-wrapper mt-2 row">
                                <div class="col-8">
                                    <form action="{{ route('contributors.update', isset($contributor) ? $contributor->id : '') }}"
                                            method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" id="contributor-id" name="contributor_id">
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required value="{{ $contributor->fullname ?? old('fullname') }}"/>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="role" disabled selected>Role</option>
                                            <option value="helper" {{ ($contributor->role ?? old('role')) == 'helper' ? 'selected' : '' }}>🍃 Helper</option>
                                            <option value="host" {{ ($contributor->role ?? old('role')) == 'host' ? 'selected' : '' }}>🎤 Host</option>
                                            <option value="staff" {{ ($contributor->role ?? old('role')) == 'staff' ? 'selected' : '' }}>🍂 Staff</option>
                                        </select>
                                        <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" required value="{{ $contributor->slug ?? old('slug') }}"/>
                                        <input type="file" class="form-control" id="image" name="image" placeholder="Image" value="{{ $contributor->image ?? old('image') }}"/>
                                        <div class="btn-actions">
                                            <button type="submit" class="form-control btn tc-blue-bg">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="image-preview-wrapper col-4 d-flex justify-content-center">
                                    <img src="" id="image-preview" class="img-fluid img-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="contributors-list"></table>
            </div>
        </div>
    </div>
@endsection
