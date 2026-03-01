<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#111}
        table{width:100%;border-collapse:collapse;margin-top:10px}
        th,td{border:1px solid #d1d5db;padding:6px}
        .right{text-align:right}
    </style>
</head>
<body>
    <h2>Payslip</h2>
    <p><strong>Employee:</strong> {{ $payroll->employee->user->name }} ({{ $payroll->employee->employee_id }})</p>
    <p><strong>Period:</strong> {{ $payroll->period_start->toDateString() }} to {{ $payroll->period_end->toDateString() }}</p>
    <p><strong>Status:</strong> {{ ucfirst($payroll->status) }}</p>

    <table>
        <thead><tr><th>Type</th><th>Description</th><th class="right">Amount</th></tr></thead>
        <tbody>
            @foreach($payroll->items as $item)
                <tr><td>{{ ucfirst($item->type) }}</td><td>{{ $item->label }}</td><td class="right">{{ number_format($item->amount,2) }}</td></tr>
            @endforeach
            <tr><th colspan="2">Net Salary</th><th class="right">{{ number_format($payroll->net_salary,2) }}</th></tr>
        </tbody>
    </table>
</body>
</html>
