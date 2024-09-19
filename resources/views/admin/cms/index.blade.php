@extends('layout.adminapp')
@section('content')
    <link rel="stylesheet" href="{{ asset('admindic/Trumbowyg-main/dist/ui/trumbowyg.min.css') }}">
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <h4 class="py-3 breadcrumb-wrapper mb-4">
            <a href="{{ route('admin.shop.category.index') }}" class="text-muted fw-light">Category /</a> Edit
        </h4> --}}
        <div class="row px-5">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Edit Content ({{ $page->page }})</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if (Session::has('status'))
                                    <div class="alert alert-success">

                                        {{ Session::get('status') }}

                                    </div>
                                @endif
                                @if (Session::has('error'))
                                    <div class="alert alert-danger">

                                        {{ Session::get('error') }}

                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('admin.cms.edit', ['page' => $page->id]) }}" class="row g-3" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea name="content" class="form-control" rows="4" id="editor"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" name="submitButton" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('admindic/Trumbowyg-main/dist/trumbowyg.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#cms-{{$page->page}}').addClass('active')

            $('#editor').trumbowyg();

            var content = @json($page->content);
            $('#editor').trumbowyg('html', content);
        });
    </script>
@endsection
