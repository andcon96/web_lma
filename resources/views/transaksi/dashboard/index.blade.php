@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
@endsection

@section('content')
    <div class="row col-12" style="margin-left:1px;">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title mb-2" style="text-align:center;"></h4>
                        </div>
                        <div class="col-sm-12 hidden-sm-down">
                            <div role="toolbar" style="text-align:center !important;">
                                <div class="btn-group mr-3" data-toggle="buttons" aria-label="Second group">
                                    <select id="tahunsj" class="form-control mb-2 select2data" required>
                                        <option value="All">All</option>
                                        @foreach ($tahunsj as $tahunsj)
                                            <option value="{{ $tahunsj }}">{{ $tahunsj }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper mt-4 mr-3 ml-3">
                        <div class="chartjs-size-monitor"
                            style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:500px;height:500px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas id="sjChart" style="display: block; height: 360px; width:955px;"
                            class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12" style="margin-left:1px;">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title mb-2" style="text-align:center;"></h4>
                        </div>
                        <div class="col-sm-12 hidden-sm-down">
                            <div role="toolbar" style="text-align:center !important;">
                                <div class="btn-group mr-3" data-toggle="buttons" aria-label="Second group">
                                    <select id="partsj" class="form-control mb-2 select2data" required>
                                        <option value="1">Total Qty</option>
                                        <option value="2">Total SJ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper mt-4 mr-3 ml-3">
                        <div class="chartjs-size-monitor"
                            style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:500px;height:500px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas id="sjPartChart" style="display: block; height: 360px; width:955px;"
                            class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12" style="margin-left:1px;">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title mb-2" style="text-align:center;"></h4>
                        </div>
                        <div class="col-sm-12 hidden-sm-down">
                            <div role="toolbar" style="text-align:center !important;">
                                {{-- <div class="btn-group mr-3" data-toggle="buttons" aria-label="Second group">
                                    <select id="partsj" class="form-control mb-2" required>
                                        <option value="1">Total Qty</option>
                                        <option value="2">Total SJ</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper mt-4 mr-3 ml-3">
                        <div class="chartjs-size-monitor"
                            style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:500px;height:500px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas id="invoiceChart" style="display: block; height: 360px; width:955px;"
                            class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12" style="margin-left:1px;">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title mb-2" style="text-align:center;"></h4>
                        </div>
                        <div class="col-sm-12 hidden-sm-down">
                            <div role="toolbar" style="text-align:center !important;">
                                {{-- <div class="btn-group mr-3" data-toggle="buttons" aria-label="Second group">
                                    <select id="partsj" class="form-control mb-2" required>
                                        <option value="1">Total Qty</option>
                                        <option value="2">Total SJ</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper mt-4 mr-3 ml-3">
                        <div class="chartjs-size-monitor"
                            style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:500px;height:500px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas id="hutangChart" style="display: block; height: 360px; width:955px;"
                            class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12" style="margin-left:1px;">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title mb-2" style="text-align:center;"></h4>
                        </div>
                        <div class="col-sm-12 hidden-sm-down">
                            <div role="toolbar" style="text-align:center !important;">
                                {{-- <div class="btn-group mr-3" data-toggle="buttons" aria-label="Second group">
                                    <select id="partsj" class="form-control mb-2" required>
                                        <option value="1">Total Qty</option>
                                        <option value="2">Total SJ</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper mt-4 mr-3 ml-3">
                        <div class="chartjs-size-monitor"
                            style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:500px;height:500px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas id="itemChart" style="display: block; height: 360px; width:955px;"
                            class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12" style="margin-left:1px;">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="card-title mb-2" style="text-align:center;"></h4>
                        </div>
                        <div class="col-sm-12 hidden-sm-down">
                            <div role="toolbar" style="text-align:center !important;">
                                <div class="btn-group mr-3" data-toggle="buttons" aria-label="Second group">
                                    <select id="itemlokasi" class="form-control mb-2 select2data">
                                        <option value="">Select Data</option>
                                        @foreach ($labelitem as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--/.col-->
                    </div>
                    <!--/.row-->
                    <div class="chart-wrapper mt-4 mr-3 ml-3">
                        <div class="chartjs-size-monitor"
                            style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                            <div class="chartjs-size-monitor-expand"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:500px;height:500px;left:0;top:0"></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink"
                                style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                <div style="position:absolute;width:200%;height:200%;left:0; top:0">
                                </div>
                            </div>
                        </div>
                        <canvas id="itemByLokasiChart" style="display: block; height: 360px; width:955px;"
                            class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.select2data').select2({});

        var labels = {{ Js::from($labelsj) }};
        var users = {{ Js::from($datasj) }};

        const data = {
            labels: labels,
            datasets: [{
                label: 'Jumlah OutStanding SJ',
                backgroundColor: '#00539CFF',
                borderColor: '#00539CFF',
                data: users,
                hoverBackgroundColor: '#326EB3',
                hoverBorderColor: '#326EB3',
                hoverBorderWidth: '3'
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                title: {
                    display: true,
                    text: ['Outstanding SJ'],
                    fontSize: 18
                },
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt) => {
                    var activePoints = myChart.getElementsAtEventForMode(evt, 'point', myChart.options);
                    if (activePoints.length > 0) {
                        var firstPoint = activePoints[0];
                        var label = myChart.data.labels[firstPoint._index];
                        var value = myChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];

                        var tahun = $('#tahunsj').val();

                        var url = "{{ route('detailSJ', [':bulan', ':tahun']) }}"
                        url = url.replace(':bulan', label);
                        url = url.replace(':tahun', tahun);

                        window.location = url;
                    }

                }
            }
        };

        const myChart = new Chart(
            document.getElementById('sjChart'),
            config
        );

        var labelpart = {{ Js::from($labelpart) }};
        var totalpart = {{ Js::from($datapart) }};

        const datapart = {
            labels: labelpart,
            datasets: [{
                label: 'Jumlah OutStanding SJ By Item',
                backgroundColor: '#00539CFF',
                borderColor: '#00539CFF',
                data: totalpart,
                hoverBackgroundColor: '#326EB3',
                hoverBorderColor: '#326EB3',
                hoverBorderWidth: '3'
            }]
        }

        const configpart = {
            type: 'bar',
            data: datapart,
            options: {
                title: {
                    display: true,
                    text: ['Outstanding SJ By Part'],
                    fontSize: 18
                },
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt) => {
                    var activePoints = PartChart.getElementsAtEventForMode(evt, 'point', PartChart.options);
                    if (activePoints.length > 0) {
                        var firstPoint = activePoints[0];
                        var label = PartChart.data.labels[firstPoint._index];
                        var value = PartChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];

                        var opsi = $('#partsj').val();

                        var url = "{{ route('detailSJPart', [':part']) }}"
                        url = url.replace(':part', label);

                        window.location = url;
                    }

                }
            }
        };

        const PartChart = new Chart(
            document.getElementById('sjPartChart'),
            configpart
        );

        var labelinv = {{ Js::from($labelinvoice) }};
        var totalinv = {{ Js::from($datainvoice) }};

        const datainv = {
            labels: labelinv,
            datasets: [{
                label: 'Total Invoice',
                backgroundColor: '#00539CFF',
                borderColor: '#00539CFF',
                data: totalinv,
                hoverBackgroundColor: '#326EB3',
                hoverBorderColor: '#326EB3',
                hoverBorderWidth: '3'
            }]
        }

        const configinv = {
            type: 'bar',
            data: datainv,
            options: {
                title: {
                    display: true,
                    text: ['Outstanding Customer Invoice'],
                    fontSize: 18
                },
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt) => {
                    var activePoints = InvChart.getElementsAtEventForMode(evt, 'point', InvChart.options);
                    if (activePoints.length > 0) {
                        var firstPoint = activePoints[0];
                        var label = InvChart.data.labels[firstPoint._index];
                        var value = InvChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];

                        var url = "{{ route('detailInvoice', [':label']) }}"
                        url = url.replace(':label', label);

                        window.location = url;
                    }

                }
            }
        };

        const InvChart = new Chart(
            document.getElementById('invoiceChart'),
            configinv
        );

        
        var labelhutang = {{ Js::from($labelhutang) }};
        var totalhutang = {{ Js::from($datahutang) }};

        const datahutang = {
            labels: labelhutang,
            datasets: [{
                label: 'Total Hutang',
                backgroundColor: '#00539CFF',
                borderColor: '#00539CFF',
                data: totalinv,
                hoverBackgroundColor: '#326EB3',
                hoverBorderColor: '#326EB3',
                hoverBorderWidth: '3'
            }]
        }

        const confighutang = {
            type: 'bar',
            data: datahutang,
            options: {
                title: {
                    display: true,
                    text: ['Outstanding Supplier Invoice'],
                    fontSize: 18
                },
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt) => {
                    var activePoints = InvChart.getElementsAtEventForMode(evt, 'point', InvChart.options);
                    if (activePoints.length > 0) {
                        var firstPoint = activePoints[0];
                        var label = InvChart.data.labels[firstPoint._index];
                        var value = InvChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];

                        var url = "{{ route('detailHutang', [':label']) }}"
                        url = url.replace(':label', label);

                        window.location = url;
                    }

                }
            }
        };

        const HutangChart = new Chart(
            document.getElementById('hutangChart'),
            confighutang
        );


        var labelitem = {{ Js::from($labelitem) }};
        var totalitemreject = {{ Js::from($getItemReject) }};
        var totalitemnonreject = {{ Js::from($getItemNonReject) }};
        var ongoingweb = {{ Js::from($ongoingweb) }};


        const dataitem = {
            labels: labelitem,
            datasets: [{
                label: 'Reject',
                backgroundColor: '#00539CFF',
                borderColor: '#00539CFF',
                data: totalitemreject,
                hoverBackgroundColor: '#326EB3',
                hoverBorderColor: '#326EB3',
                hoverBorderWidth: '3'
            }, {
                label: 'Non Reject',
                backgroundColor: '#ee6c4d',
                borderColor: '#ee6c4d',
                data: totalitemnonreject,
                hoverBackgroundColor: '#b5523a',
                hoverBorderColor: '#b5523a',
                hoverBorderWidth: '3'
            }, {
                label: 'Ongoing Web',
                backgroundColor: '#454545',
                borderColor: '#454545',
                data: ongoingweb,
                hoverBackgroundColor: '#454545',
                hoverBorderColor: '#454545',
                hoverBorderWidth: '3'
            }, ]
        }

        const configitem = {
            type: 'bar',
            data: dataitem,
            options: {
                title: {
                    display: true,
                    text: ['Stok Item'],
                    fontSize: 18
                },
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt) => {
                    var activePoints = ItemChart.getElementsAtEventForMode(evt, 'point', ItemChart.options);
                    if (activePoints.length > 0) {
                        var firstPoint = activePoints[0];
                        var label = ItemChart.data.labels[firstPoint._index];
                        var value = ItemChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
                        var labeldata = ItemChart.data.datasets[firstPoint._datasetIndex].label;

                        var url = "{{ route('detailStokItem', [':label', ':tipe']) }}"
                        url = url.replace(':label', label);
                        url = url.replace(':tipe', labeldata);

                        window.location = url;
                    }

                }
            }
        };

        const ItemChart = new Chart(
            document.getElementById('itemChart'),
            configitem
        );

        const dataitemstok = {
            labels: [],
            datasets: [{
                label: 'Total Stok',
                backgroundColor: '#00539CFF',
                borderColor: '#00539CFF',
                data: [],
                hoverBackgroundColor: '#326EB3',
                hoverBorderColor: '#326EB3',
                hoverBorderWidth: '3'
            }]
        }

        const configitemstok = {
            type: 'bar',
            data: dataitemstok,
            options: {
                title: {
                    display: true,
                    text: ['Stok Item By Lokasi'],
                    fontSize: 18
                },
                responsive: true,
                maintainAspectRatio: false
            }
        };

        const ItemChartLokasi = new Chart(
            document.getElementById('itemByLokasiChart'),
            configitemstok
        );


        $('#tahunsj').on('change', function(e) {
            let value = $(this).val();
            $.ajax({
                url: '{{ route('getAllSJ') }}',
                data: {
                    "tahun": value
                },
                type: "get",
            }).done(function(data) {
                console.log(data);

                myChart.data.labels = data[0];
                myChart.data.datasets[0].data = data[1];

                myChart.update();

            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                swal.fire({
                    icon: 'error',
                    title: 'Failed to get Data',
                    showConfirmButton: true,
                    timer: 3000,
                })
            });
        })

        $('#partsj').on('change', function(e) {
            let value = $(this).val();
            $.ajax({
                url: '{{ route('getAllPartSJ') }}',
                data: {
                    "opsi": value
                },
                type: "get",
            }).done(function(data) {
                console.log(data);

                PartChart.data.labels = data[0];
                PartChart.data.datasets[0].data = data[1];

                PartChart.update();

            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                swal.fire({
                    icon: 'error',
                    title: 'Failed to get Data',
                    showConfirmButton: true,
                    timer: 3000,
                })
            });
        });

        $('#itemlokasi').on('change', function(e) {
            let value = $(this).val();
            $.ajax({
                url: '{{ route('getStokItemLokasi') }}',
                data: {
                    "part": value
                },
                type: "get",
            }).done(function(data) {

                ItemChartLokasi.data.labels = data[0];
                ItemChartLokasi.data.datasets[0].data = data[1];

                ItemChartLokasi.update();

            }).fail(function(jqXHR, ajaxOptions, thrownError) {
                swal.fire({
                    icon: 'error',
                    title: 'Failed to get Data',
                    showConfirmButton: true,
                    timer: 3000,
                })
            });
        });
    </script>
@endsection
