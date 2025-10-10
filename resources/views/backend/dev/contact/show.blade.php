@extends('backend.layouts.main')
@section('container')
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-auto me-auto"></div>
            <div class="col-auto">
                <form action="/logout" method="post">
                    @csrf
                    <button class="btn btn-danger rounded-circle" type="submit" onclick="confirm('are yuo sure! logout')">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="page-heading">
        <soab class="fs-3 fw-bold text-white">{{ $chat->subject }}</soab>
    </div>
    <div class="card">
        <div class="card-body">
            <span>From: <span class="fw-bold text-white me-2">{{ $chat->name }} </span><span>
                    </span>{{ $chat->email }}<span></span>
                </span>
                <p>
                    {!! $chat->message !!}
                </p>
        </div>
    </div>
    </div>
@endsection
