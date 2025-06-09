@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Users</h1>
    <table class="table">
        <thead><tr><th>Name</th><th>Email</th><th>Wallet</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->wallet_balance }}</td>
                <td><a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">View</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
