<div>
    <x-slot name="header">
        <livewire:admin.global.page-header judul="Dashboard" subjudul="Dasboard" :breadcrumb="[]" />
    </x-slot>

    <div class="card card-body">
        <div class="row text-center">
            <div class="col-md-1">
                <p><i class="icon-users4 icon-2x d-inline-block text-primary"></i></p>
                <h5 class="font-weight-semibold mb-0">{{ $karyawan->count() }}</h5>
                <span class="text-muted font-size-sm">Karyawan</span>
            </div>

            <div class="col-md-1">
                <p><i class="icon-collaboration icon-2x d-inline-block text-warning"></i></p>
                <h5 class="font-weight-semibold mb-0">{{ $agent->count() }}</h5>
                <span class="text-muted font-size-sm">Agent</span>
            </div>

            <div class="col-md-1">
                <p><i class="icon-accessibility icon-2x d-inline-block text-info"></i></p>
                <h5 class="font-weight-semibold mb-0">{{ $vendor->count() }}</h5>
                <span class="text-muted font-size-sm">Vendor</span>
            </div>

            <div class="col-md-1">
                <p><i class="icon-store2 icon-2x d-inline-block text-success"></i></p>
                <h5 class="font-weight-semibold mb-0">{{ $product->count() }}</h5>
                <span class="text-muted font-size-sm">Product</span>
            </div>

            <div class="col-md-1">
                <p><i class="icon-stack3 icon-2x d-inline-block text-danger"></i></p>
                <h5 class="font-weight-semibold mb-0">{{ $product->sum('qty') }}</h5>
                <span class="text-muted font-size-sm">Total Stok</span>
            </div>

            <div class="col-2">
                <p><i class="icon-cash2 icon-2x d-inline-block text-warning"></i></p>
                <h5 class="font-weight-semibold mb-0">Rp, {{ number_format($pengeluaran, 0, ',', '.') }}</h5>
                <span class="text-muted font-size-sm">Pengeluaran</span>
            </div>

            <div class="col-2">
                <p><i class="icon-cash2 icon-2x d-inline-block text-primary"></i></p>
                <h5 class="font-weight-semibold mb-0">Rp, {{ number_format($pemasukan->sum('total'), 0, ',', '.') }}
                </h5>
                <span class="text-muted font-size-sm">Pemasukan</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card card-body p-0">
                <h2 class="card-title text-center">Penghasilan bersih Harian, dalam bulan {{ now()->format('F') }}</h2>
                <div class="chart-container">
                    <div class="chart has-fixed-height" id="container"></div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-body p-0">
                <h2 class="card-title text-center">Penghasilan bersih Bulanan, dalam tahun {{ now()->format('Y') }}</h2>
                <div class="chart-container">
                    <div class="chart has-fixed-height" id="container1"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@push('js')
    <script type="text/javascript">
        // Function to generate random color
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Reusable function to initialize charts
        function initChart(domId, data) {
            try {
                var labels = data.labels;
                var jumlah = data.jumlah;
                var dom = document.getElementById(domId);

                if (!dom) {
                    console.error("Element with ID '" + domId + "' not found.");
                    return;
                }

                var myChart = echarts.init(dom, null, {
                    renderer: 'canvas',
                    useDirtyRect: false
                });

                var option = {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: [{
                        type: 'category',
                        data: labels,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }],
                    yAxis: [{
                        type: 'value'
                    }],
                    series: [{
                        name: 'Penghasil Bersih',
                        type: 'bar',
                        barWidth: '95%',
                        itemStyle: {
                            color: function() {
                                return getRandomColor();
                            },
                        },
                        data: jumlah
                    }]
                };

                if (option && typeof option === 'object') {
                    myChart.setOption(option);
                }

                window.addEventListener('resize', myChart.resize);
            } catch (error) {
                console.error("Error initializing chart for '" + domId + "':", error);
            }
        }

        // Call the function for the first chart
        var data_jumlah = {!! $dailyData !!};
        initChart('container', data_jumlah);

        // Call the function for the second chart
        var data_jumlah1 = {!! $monthlyData !!};
        initChart('container1', data_jumlah1);
    </script>
@endpush
