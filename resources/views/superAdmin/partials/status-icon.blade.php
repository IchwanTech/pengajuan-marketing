{{-- resources/views/superAdmin/partials/status-icon.blade.php --}}
@if ($status === 'approved')
    <i class="mdi mdi-check-circle text-success" title="Approved"></i>
@elseif ($status === 'rejected')
    <i class="mdi mdi-close-circle text-danger" title="Rejected"></i>
@else
    <i class="mdi mdi-clock-outline text-info" title="Pending"></i>
@endif
