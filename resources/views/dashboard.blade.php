@extends('layouts.admin')

@section('content')
<style>
.dashboard-kpi-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
.dashboard-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 0 1px rgba(15, 23, 42, 0.06);
    border: 1px solid #e2e8f0;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.dashboard-card:hover { border-color: #09515D; box-shadow: 0 8px 32px rgba(9, 81, 93, 0.12); }
.dashboard-card-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; font-weight: 600; margin-bottom: 8px; }
.dashboard-card-value { font-size: 28px; font-weight: 800; color: #09515D; }
.dashboard-card-icon { width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #09515D, #0a6573); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 22px; }
.dashboard-card-hint { font-size: 13px; color: #64748b; margin-top: 12px; }
.dashboard-kpi-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
.dashboard-activities-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 0 1px rgba(15, 23, 42, 0.06);
    border: 1px solid #e2e8f0;
}
.dashboard-activities-card h3 { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 16px; }
.dashboard-table { width: 100%; border-collapse: collapse; font-size: 15px; }
.dashboard-table th { text-align: left; padding: 12px 16px; color: #64748b; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #e2e8f0; }
.dashboard-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #334155; }
.dashboard-table tbody tr:hover { background: #f8fafc; }
.dashboard-table .status-ok { color: #059669; font-weight: 600; }
.dashboard-table .status-pending { color: #d97706; font-weight: 600; }
</style>

<div class="dashboard-kpi-grid">
    <div class="dashboard-card">
        <div class="dashboard-kpi-row">
            <div>
                <p class="dashboard-card-label">Total Enrollments</p>
                <p class="dashboard-card-value">{{ $totalEnrollments }}</p>
            </div>
            <div class="dashboard-card-icon">📋</div>
        </div>
        <p class="dashboard-card-hint">Total course enrollment submissions</p>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-kpi-row">
            <div>
                <p class="dashboard-card-label">Active Courses</p>
                <p class="dashboard-card-value">{{ $totalActiveCourses }}</p>
            </div>
            <div class="dashboard-card-icon">🎓</div>
        </div>
        <p class="dashboard-card-hint">Currently published courses</p>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-kpi-row">
            <div>
                <p class="dashboard-card-label">Inquiries</p>
                <p class="dashboard-card-value">{{ $pendingInquiriesCount + $pendingContactsCount }}</p>
            </div>
            <div class="dashboard-card-icon">❓</div>
        </div>
        <p class="dashboard-card-hint">Course: {{ $pendingInquiriesCount }} · Contact: {{ $pendingContactsCount }}</p>
    </div>
</div>

<div class="dashboard-activities-card" style="margin-top: 28px;">
    <h3>Recent Activities</h3>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Activity</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentActivities as $activity)
                <tr>
                    <td>{{ $activity['activity'] }}</td>
                    <td>{{ $activity['date']->format('M d, Y') }}</td>
                    <td>
                        <span class="{{ $activity['status_color'] === 'green' ? 'status-ok' : 'status-pending' }}">
                            {{ $activity['status'] }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="padding: 32px; text-align: center; color: #64748b;">No recent activity found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
