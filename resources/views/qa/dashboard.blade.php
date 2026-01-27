@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <style>
        .gradient-box {
            color: #fff;
        }
    </style>

    @include('layouts.dashboard')

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.gradient-box').forEach(box => {
            const current = Number(box.dataset.current);
            const total   = Number(box.dataset.total);

            if (!total) return;

            const percent = Math.min((current / total) * 100, 100);

            // jika progress 0%
            if (percent === 0) {
                box.style.background = `
                    linear-gradient(
                        135deg,
                        #ff7a7a,
                        #e03131
                    )
                `;
                return;
            }

            // jika progress 100%
            if (percent === 100) {
                box.style.background = `
                    linear-gradient(
                        135deg,
                        #51cf66,
                        #2f9e44
                    )
                `;
                return;
            }

            // jika progress 1–99%
            box.style.background = `
                linear-gradient(
                    90deg,
                    #51cf66 0%,
                    #51cf66 ${percent - 5}%,
                    #1c7ed6 ${percent + 5}%,
                    #1c7ed6 100%
                )
            `;
        });
    </script>
@endpush