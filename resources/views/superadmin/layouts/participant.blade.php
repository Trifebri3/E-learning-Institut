@if(session()->has('impersonate_admin_id'))
    <a href="{{ route('superadmin.users.leave-impersonate') }}" class="btn btn-sm btn-danger">
        Kembali ke SuperAdmin
    </a>
@endif
