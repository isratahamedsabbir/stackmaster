<div>
    <style>
        .loading-center {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1000;
        }
        .loading-center img {
            width: 100px;
            height: 100px;
        }
    </style>

    <!-- Centered Loading Spinner -->
    <div class="loading-center" wire:loading>
        <img src="{{ asset('default/loader.gif') }}" alt="Loading..." />
        <div class="mt-2">Loading, please wait...</div>
    </div>

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->slug }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <img src="{{ $user->avatar ? asset($user->avatar) : asset('default/profile.jpg') }}"
                            alt="Avatar" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                    </td>
                    <td>{{ $user->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" wire:click="delete({{ $user->id }})">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>