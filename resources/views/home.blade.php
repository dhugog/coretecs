@extends('adminlte::page')

@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Vendas</span>
                    <span class="info-box-number">54</span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-id-card-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Funcionários</span>
                    <span class="info-box-number">7</span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-grey"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number">87</span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-cogs"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Em Manutenção</span>
                    <span class="info-box-number">8</span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><i class="fa fa-usd"></i> Gráfico de Vendas</div>
        <div class="panel-body">
            <canvas id="myChart" width="100%" height="30px"></canvas>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ URL::asset('js/Chart.min.js') }}"></script>

    <script>
        $(function() {
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["01/05", "05/05", "10/05", "15/05", "20/05", "25/05", "30/05"],
                    datasets: [{
                        label: 'Vendas',
                        data: [12, 19, 3, 5, 2, 3, 10],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.4)',
                            'rgba(54, 162, 235, 0.4)',
                            'rgba(255, 206, 86, 0.4)',
                            'rgba(75, 192, 192, 0.4)',
                            'rgba(153, 102, 255, 0.4)',
                            'rgba(255, 159, 64, 0.4)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        });
    </script>
@endpush