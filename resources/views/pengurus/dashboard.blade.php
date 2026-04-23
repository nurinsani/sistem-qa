@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
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


    <div class="container-fluid">
        <div class="row">
            @foreach ($dataBulanan as $item)
                <div class="col-lg-3 col-6">
                    <div class="small-box gradient-box"
                        data-current="{{ $item['selesai'] }}"
                        data-total="{{ $item['total'] }}">

                        <div class="inner">
                            <h3>{{ $item['selesai'] }}/{{ $item['total'] }}</h3>
                            <p>{{ $item['bulan'] }}</p>
                        </div>

                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>

                        <a href="{{ route('pengurus.dashboard.detail', ['bulan' => $loop->iteration, 'tahun' => now()->year]) }}" class="small-box-footer">
                            Lihat <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection

@include('dashboard.script-gradient-box')