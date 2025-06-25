@extends('layouts.app')

@section('content')
    <h1>All Users</h1>
    <div class="mb-3" style="max-width:400px;">
        <input type="text" id="user-search" class="form-control" placeholder="Search users by name, email or role...">
        <button id="user-search-btn" class="btn btn-secondary mt-2" type="button">Filter</button>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Make Admin</th>
            </tr>
        </thead>
        <tbody id="users-table-body">
            @foreach($users as $user)
                <tr class="user-row">
                    <td class="user-name">{{ $user->name }}</td>
                    <td class="user-email">{{ $user->email }}</td>
                    <td class="user-role">{{ $user->role }}</td>
                    <td>
                        @if($user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.makeAdmin', $user->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">Make Admin</button>
                        </form>
                        @else
                            Already Admin
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user-search');
    const searchBtn = document.getElementById('user-search-btn');
    if (!searchInput || !searchBtn) return;
    function filterUsers() {
        const query = searchInput.value.toLowerCase();
        let found = false;
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.querySelector('.user-name').textContent.toLowerCase();
            const email = row.querySelector('.user-email').textContent.toLowerCase();
            const role = row.querySelector('.user-role').textContent.toLowerCase();
            if (
                name.includes(query) ||
                email.includes(query) ||
                role.includes(query)
            ) {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });
        // Eğer hiç sonuç yoksa tabloya bir satır ekle
        let noRow = document.getElementById('no-users-row');
        if (!found) {
            if (!noRow) {
                noRow = document.createElement('tr');
                noRow.id = 'no-users-row';
                noRow.innerHTML = `<td colspan="4" class="text-center text-muted">No users found.</td>`;
                document.getElementById('users-table-body').appendChild(noRow);
            }
        } else {
            if (noRow) noRow.remove();
        }
    }
    searchBtn.addEventListener('click', filterUsers);
    searchInput.addEventListener('keydown', function(e) {
        if(e.key === 'Enter') filterUsers();
    });
});
</script>
@endpush