@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<script src="{{ asset('js/plugins/highcarts/highstock.js')}}"></script>
<script src="{{ asset('js/plugins/highcarts/exporting.js')}}"></script>
<script src="{{ asset('js/plugins/highcarts/accessibility.js')}}"></script>
@endsection

@section('content-header')
<div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="background-image: url({{ asset('/img/cover-bg-profil.jpg') }}); background-size: cover; background-position: center top;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats shadow h-100">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Jumlah Kepala Keluarga</h5>
                                <span class="h2 font-weight-bold mb-0">{{ App\Penduduk::whereHas('statusHubunganDalamKeluarga', function ($status) {$status->where('nama', 'Kepala Keluarga');})->count() }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats shadow h-100">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Total Penduduk</h5>
                                <span class="h2 font-weight-bold mb-0">{{ App\Penduduk::count() }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats shadow h-100">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Jumlah Laki-laki</h5>
                                <span class="h2 font-weight-bold mb-0">{{ App\Penduduk::where('jenis_kelamin',1)->count() }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-sm-6 mb-3">
                <div class="card card-stats shadow h-100">
                    <!-- Card body -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Jumlah Perempuan</h5>
                                <span class="h2 font-weight-bold mb-0">{{ App\Penduduk::where('jenis_kelamin',2)->count() }}</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-gradient-pink text-white rounded-circle shadow">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    @include('statistik-penduduk.card')
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/apbdes.js') }}"></script>
<script src="{{ asset('js/statistik-penduduk.js') }}"></script>
<script>
    let bar = {
        chart: {
            type: 'bar',
        },
        xAxis: {
            type: 'category',
            title: {
                text: null
            },
            min: 0,
            max: 10,
            scrollbar: {
                enabled: true
            },
            tickLength: 0
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Jumlah Cetak',
                align: 'high'
            }
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            enabled: false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Jumlah Cetak',
            data: []
        }]
    };

    let chart_harian = Highcharts.chart('chart-harian', bar);
    chart_harian.title.textSetter("Grafik Cetak Surat Harian");

    let chart_bulanan = Highcharts.chart('chart-bulanan', bar);
    chart_bulanan.title.textSetter("Grafik Cetak Surat Bulanan");

    let chart_tahunan = Highcharts.chart('chart-tahunan', bar);
    chart_tahunan.title.textSetter("Grafik Cetak Surat Tahunan");

    $(document).ready(function(){
        $("#loading-tanggal-surat").css('display','');
        $("#loading-bulan-surat").css('display','');
        $("#loading-tahun-surat").css('display','');
        $("#tanggal").css('display','none');
        $("#bulan").css('display','none');
        $("#tahun").css('display','none');

        $.get("{{ route('surat-harian') }}", function (response) {
            $("#loading-tanggal-surat").css('display','none');
            $("#tanggal").css('display','');
            chart_harian.series[0].setData(response);
        });

        $.get("{{ route('surat-bulanan') }}", function (response) {
            $("#loading-bulan-surat").css('display','none');
            $("#bulan").css('display','');
            chart_bulanan.series[0].setData(response);
        });

        $.get("{{ route('surat-tahunan') }}", function (response) {
            $("#loading-tahun-surat").css('display','none');
            $("#tahun").css('display','');
            chart_tahunan.series[0].setData(response);
        });

        $("#tanggal").change(function () {
            $("#loading-tanggal-surat").css('display','');
            $("#tanggal").css('display','none');
            $.get("{{ route('surat-harian') }}", {'tanggal': $(this).val()}, function (response) {
                $("#tanggal").css('display','');
                $("#loading-tanggal-surat").css('display','none');
                chart_harian.series[0].setData(response);
            });
        });

        $("#bulan").change(function () {
            $("#loading-bulan-surat").css('display','');
            $("#bulan").css('display','none');
            $.get("{{ route('surat-bulanan') }}", {'bulan': $(this).val()}, function (response) {
                $("#bulan").css('display','');
                $("#loading-bulan-surat").css('display','none');
                chart_bulanan.series[0].setData(response);
            });
        });

        $("#tahun").change(function () {
            $("#loading-tahun-surat").css('display','');
            $("#tahun").css('display','none');
            $.get("{{ route('surat-tahunan') }}", {'tahun': $(this).val()}, function (response) {
                $("#tahun").css('display','');
                $("#loading-tahun-surat").css('display','none');
                chart_tahunan.series[0].setData(response);
            });
        });
    });
</script>
@endpush
