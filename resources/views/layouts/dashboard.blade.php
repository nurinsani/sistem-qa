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

                    <a href="#" class="small-box-footer">
                        Lihat <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
