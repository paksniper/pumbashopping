@extends('layout.admin_app')

@section('title')
    PumbaShopping
@endsection
@section('content')

    <div class="container mt-5">
        <div class="row">
            <div id="accordion" class="w-100">
                <div class="card">
                    <div class="card-footer bg-white border-bottom border-top-0">
                        <button class="btn btn-outline-danger" data-toggle="collapse" data-target="#create-product">
                            <i class="far fa-edit"></i> Update A Section
                        </button>
                    </div>

                    <div class="collapse show" id="create-product" data-parent="#accordion">
                        <div class="card-body">
                            <form
                                action="{{route('post_edit_section',$section->id)}}"
                                method="post"
                                enctype="application/x-www-form-urlencoded"
                                id="section_form"
                                name="section_form">
                                @csrf
                                <div class="form-group">
                                    <input type="hidden" name="original_title" value="{{$section->title}}">
                                    <label for="section_title" class="form-label">Title</label>
                                    <input type="text" name="section_title"
                                           class="form-control @error('section_title') is-invalid @enderror"
                                           placeholder="section title..." value="{{$section->title}}">
                                    @error('section_title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-danger btn-block">
                                                Update Section
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
