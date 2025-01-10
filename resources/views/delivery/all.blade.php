@extends('layouts.index')

@section('title', 'Delivery Status')

@section('content')
    <h1>Delivery Status</h1>

    <form method="POST" action="#">
        <div class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search by item name" value="">
        </div>

        <div class="mb-3">
            <label>Status:</label>
            <div>
                @foreach(['semua', 'lunas', 'diproses', 'packing', 'dikirim', 'diterima', 'kadaluarsa'] as $status)
                    <button type="button" class="btn filter-btn">
                        {{ ucfirst($status) }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mb-3">
            <label>Tanggal Transaksi:</label>
            <input type="date" name="transaction_date" class="form-control">
        </div>

        <button type="button" class="btn btn-primary">Filter</button>
        <button type="button" class="btn btn-secondary">Reset Filter</button>
    </form>

    <h2 class="mt-4">Transaction List</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Status</th>
            <th>Transaction Date</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Example Item</td>
            <td>Diproses</td>
            <td>2024-01-01</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Example Item 2</td>
            <td>Dikirim</td>
            <td>2024-01-02</td>
        </tr>
        </tbody>
    </table>
@endsection