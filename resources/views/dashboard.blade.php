@extends('layouts.index')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">

    <!-- Header -->
    <div class="text-center mb-4">
        <h2>Today's Dashboard</h2>
        <p>Here You Can View Many Dashboard For Today's Sales</p>
    </div>

    <!-- Statistik Utama -->
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Visitors Online</h5>
                    <p class="card-text display-4">{{ $visitorsOnline }}</p>
                    <p>10% More Than Yesterday</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Products Sold</h5>
                    <p class="card-text display-4">{{ $productsSold }}</p>
                    <p>42 More Than Yesterday</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">Products Arrived Safely</h5>
                    <p class="card-text display-4">{{ $productsArrivedSafely }}</p>
                    <p>15% Faster Than Last Week</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Produk Terbaru -->
    <div class="card mb-4">
        <div class="card-header">Recent Product In 24 Hours</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Number</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                @foreach($recentProducts as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['number'] }}</td>
                        <td>{{ $product['status'] }}</td>
                        <td>{{ $product['payment'] }}</td>
                        <td><a href="#">Details</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grafik Penjualan Mingguan -->
    <div class="card mb-4">
        <div class="card-header">Grafik Penjualan Minggu Ini</div>
        <div class="card-body">
            <canvas id="weeklySalesChart"></canvas>
        </div>
    </div>

    <!-- Statistik Data Total Barang -->
    <div class="card mb-4">
        <div class="card-header">Statistik Data Total Barang</div>
        <div class="card-body">
            <canvas id="totalDataChart"></canvas>
            <ul>
                @foreach($weeklyStats as $week => $data)
                    <li>{{ $week }}: {{ $data }} barang</li>
                @endforeach
            </ul>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Grafik Penjualan Mingguan
    const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
    new Chart(weeklySalesCtx, {
        type: 'bar',
        data: {
            labels: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            datasets: [{
                label: 'Jumlah Penjualan',
                data: @json($weeklySales),
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Grafik Statistik Total Barang
    const totalDataCtx = document.getElementById('totalDataChart').getContext('2d');
    new Chart(totalDataCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(@json($weeklyStats)),
            datasets: [{
                label: 'Data Barang Terjual',
                data: Object.values(@json($weeklyStats)),
                backgroundColor: ['#4CAF50', '#FF5733', '#33A4FF', '#FFC300', '#DA33FF']
            }]
        }
    });
</script>

@endsection
