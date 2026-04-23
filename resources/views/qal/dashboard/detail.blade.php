@extends('layouts.main')

@section('content-header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }} - {{ $namaBulan }} {{ $tahun }}</h1>
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
            @foreach ($auditsGrouped as $user_id => $items)
                
                <div class="col-lg-3 col-6">
                    <div class="small-box gradient-box"
                    data-current="{{ $items->where('status', 'selesai')->count() }}"
                    data-total="{{ $items->count() }}">
                
                        <div class="inner">
                            <h3>{{ $items->where('status', 'selesai')->count() }}/{{ $items->count() }}</h3>
                            <p>{{ $items->first()->qa->name ?? 'Tanpa QA' }}</p>
                        </div>
                        
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        
                        <a href="{{ route('qal.dashboard.detailByQa', ['user_id' => $user_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="small-box-footer">
                            Lihat <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection

@include('dashboard.script-gradient-box')