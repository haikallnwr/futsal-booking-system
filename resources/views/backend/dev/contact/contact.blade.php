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
        <h3>{{ $title }}</h3>
    </div>

    @if ($chat->count() > 0)
        @foreach ($chat as $chat)
            <div class="card mb-3">
                <div class="row">
                    <div class="col">
                        <div class="card-body">
                            <a class="me-3" href="/dashboarddev/contact/{{ $chat->id }}">
                                <span class="card-title fs-5 text-white fw-bold">{{ $chat->name }}</span>
                            </a>
                            <span class="fw-5">
                                <span>
                                    </span> {{ $chat->email }}
                                        <span>
                                        </span>
                                </span>
                            </span>
                            <span class="me-3">{{ $chat->created_at->diffForHumans() }}</span>
                            <span class="d-none" id="button">
                                <form action="/dashboarddev/contact/{{ $chat->id }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="badge bg-danger border-0" type="submit"
                                        onclick="return confirm('Apa anda yakin ingin menghapus pesan ini?')">
                                        <i class="bi bi-x-octagon-fill"></i>
                                    </button>
                                </form>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-center fs-4">No Message .</p>
    @endif
    <script>
        const card = document.querySelector('.card');
        const button = card.querySelector('#button');

        card.addEventListener('mouseenter', () => {
            button.classList.remove('d-none');
        });

        card.addEventListener('mouseleave', () => {
            button.classList.add('d-none');
        });
    </script>
@endsection
