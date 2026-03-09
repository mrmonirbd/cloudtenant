@extends('layouts/app_auth')

@section('contents')
<div class="col-12">
    <div class="QA_section">
        <div class="white_box_tittle list_header">
            <h4>User Management</h4>
            <div class="box_right d-flex lms_block">
                <div class="serach_field_2">
                    <div class="search_inner">
                        <form  method="GET">
                            <div class="search_field">
                                <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
                            </div>
                            <button type="submit"> <i class="ti-search"></i> </button>
                        </form>
                    </div>
                </div>
                <div class="add_button ms-2">
                    <a href="{{ route('users.create') }}" data-bs-toggle="modal" data-bs-target="#addcategory" class="btn_1">Add New User</a>
                </div>
                @include('modals.adduser')
            </div>
        </div>

        <!-- Status Filter -->
        <div class="row mb-3 px-3">
            <div class="col-md-3">
                <select class="form-control" id="statusFilter" onchange="filterByStatus(this.value)">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="QA_table mb_30">
            <!-- Bulk Actions -->
            <div class="bulk-actions mb-3" style="display: none;" id="bulkActions">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success" onclick="bulkAction('active')">
                        <i class="ti-check"></i> Active Selected
                    </button>
                    <button type="button" class="btn btn-warning" onclick="bulkAction('inactive')">
                        <i class="ti-close"></i> Inactive Selected
                    </button>
                    <button type="button" class="btn btn-danger" onclick="bulkDelete()">
                        <i class="ti-trash"></i> Delete Selected
                    </button>
                </div>
            </div>

            <table class="table lms_table_active">
                <thead>
                    <tr>
                        <th scope="col" width="50">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td>
                            <input type="checkbox" class="user-checkbox form-check-input" value="{{ $user->id }}">
                        </td>
                        <th scope="row">
                            <a href="{{ route('users.edit', $user->id) }}" class="question_content">{{ $user->id }}</a>
                        </th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @elseif($user->role == 'editor')
                                <span class="badge bg-info">Editor</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button"
                                    class="btn btn-{{ $user->status == 'active' ? 'success' : 'danger' }} dropdown-toggle status-btn"
                                    data-bs-toggle="dropdown"
                                    data-user-id="{{ $user->id }}"
                                    data-current-status="{{ $user->status }}"
                                    aria-expanded="false">
                                    <span class="status-text-{{ $user->id }}">{{ ucfirst($user->status) }}</span>
                                </button>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item status-option {{ $user->status == 'active' ? 'disabled' : '' }}" 
                                           href="#" 
                                           data-user-id="{{ $user->id }}" 
                                           data-status="active">
                                            <i class="ti-check text-success"></i> Set Active
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item status-option {{ $user->status == 'inactive' ? 'disabled' : '' }}" 
                                           href="#" 
                                           data-user-id="{{ $user->id }}" 
                                           data-status="inactive">
                                            <i class="ti-close text-danger"></i> Set Inactive
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                <i class="ti-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-user" data-user-id="{{ $user->id }}">
                                <i class="ti-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if(method_exists($users, 'links'))
                <div class="mt-3">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // CSRF Token Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Status Update
    $(document).ready(function() {
    $('.status-option').on('click', function(e) {
        e.preventDefault();
        
        var userId = $(this).data('user-id');
        var newStatus = $(this).data('status');
        var button = $(this).closest('.btn-group').find('.status-btn');
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Loading...');
        
        var url = '{{ route("users.status", ["user" => ":userId"]) }}';
        url = url.replace(':userId', userId);
        
        $.ajax({
            url: url,  
            type: 'POST',
            data: {
                _method: 'PATCH',
                status: newStatus,
                user_id: userId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // success code
                if(response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                // error code
                console.log(xhr);
            },
            complete: function() {
                button.prop('disabled', false).html(button.data('original-text'));
            }
        });
    });
});
    // Select All Checkbox
    $('#selectAll').on('change', function() {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActions();
    });

    // Individual Checkbox Change
    $('.user-checkbox').on('change', function() {
        var allChecked = $('.user-checkbox:checked').length === $('.user-checkbox').length;
        $('#selectAll').prop('checked', allChecked);
        toggleBulkActions();
    });

    // Toggle Bulk Actions
    function toggleBulkActions() {
        var checkedCount = $('.user-checkbox:checked').length;
        if(checkedCount > 0) {
            $('#bulkActions').show();
        } else {
            $('#bulkActions').hide();
        }
    }

    // Delete User
    var deleteUserId = null;
    
    $('.delete-user').on('click', function() {
        deleteUserId = $(this).data('user-id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if(deleteUserId) {
            $.ajax({
                url: '{{ url("users") }}/' + deleteUserId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        $('#user-row-' + deleteUserId).fadeOut(500, function() {
                            $(this).remove();
                            showNotification('success', 'User deleted successfully!');
                        });
                    }
                },
                error: function(xhr) {
                    showNotification('error', 'Error deleting user.');
                },
                complete: function() {
                    $('#deleteModal').modal('hide');
                    deleteUserId = null;
                }
            });
        }
    });
});

// Filter by Status
function filterByStatus(status) {
    var url = new URL(window.location.href);
    if(status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

// Bulk Action
function bulkAction(status) {
    var selectedUsers = [];
    $('.user-checkbox:checked').each(function() {
        selectedUsers.push($(this).val());
    });
    
    if(selectedUsers.length === 0) {
        showNotification('warning', 'Please select at least one user');
        return;
    }
    
    if(confirm('Are you sure you want to mark ' + selectedUsers.length + ' users as ' + status + '?')) {
        $.ajax({
            url: '{{ url('/') }}',
            type: 'POST',
            data: {
                user_ids: selectedUsers,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                showNotification('error', 'Error updating users');
            }
        });
    }
}

// Bulk Delete
function bulkDelete() {
    var selectedUsers = [];
    $('.user-checkbox:checked').each(function() {
        selectedUsers.push($(this).val());
    });
    
    if(selectedUsers.length === 0) {
        showNotification('warning', 'Please select at least one user');
        return;
    }
    
    if(confirm('Are you sure you want to delete ' + selectedUsers.length + ' users?')) {
        $.ajax({
            url: '{{ url("/") }}',
            type: 'POST',
            data: {
                user_ids: selectedUsers,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                showNotification('error', 'Error deleting users');
            }
        });
    }
}

// Notification Function
function showNotification(type, message) {
    if(typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        alert(message);
    }
}

// Toastr Configuration
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000",
    "extendedTimeOut": "1000"
};
</script>
@endpush