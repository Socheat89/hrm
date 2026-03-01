<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#111}
        table{width:100%;border-collapse:collapse;margin-top:8px}
        th,td{border:1px solid #ddd;padding:6px}
        .right{text-align:right}
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <p><strong>Employee:</strong> {{ $employee->user->name }} ({{ $employee->employee_id }})</p>
    <p><strong>Month:</strong> {{ $month }}</p>

    <table>
        <thead><tr><th>Date</th><th>Morning In</th><th>Lunch Out</th><th>Lunch In</th><th>Evening Out</th><th>Late (min)</th><th>Work Hours</th><th>Overtime</th></tr></thead>
        <tbody>
            @foreach($sessions as $session)
                <tr>
                    <td>{{ $session->attendance_date->toDateString() }}</td>
                    <td>{{ optional($session->logs->firstWhere('scan_type', 'morning_in'))->scanned_at?->format('H:i') ?? '-' }}</td>
                    <td>{{ optional($session->logs->firstWhere('scan_type', 'lunch_out'))->scanned_at?->format('H:i') ?? '-' }}</td>
                    <td>{{ optional($session->logs->firstWhere('scan_type', 'lunch_in'))->scanned_at?->format('H:i') ?? '-' }}</td>
                    <td>{{ optional($session->logs->firstWhere('scan_type', 'evening_out'))->scanned_at?->format('H:i') ?? '-' }}</td>
                    <td class="right">{{ $session->late_minutes }}</td>
                    <td class="right">{{ number_format($session->work_minutes / 60, 2) }}</td>
                    <td class="right">{{ number_format($session->overtime_minutes / 60, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
