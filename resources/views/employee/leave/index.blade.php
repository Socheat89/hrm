<x-layouts.employee page-title="Request" page-description="Request Leave, Overtime (OT), and Change Day Off." :show-page-banner="false">
    
    <!-- External Dependencies (Scoped to this page) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .page-container {
            padding: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Header Card */
        .header-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        .header-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 6px; height: 100%;
            background: var(--brand);
        }
        .header-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .header-desc {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .date-pill {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
        }

        /* Tabs */
        .tabs-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .tab-btn {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1rem 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #64748b;
        }
        .tab-btn i { font-size: 1.25rem; margin-bottom: 2px; }
        .tab-btn span { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.02em; }
        
        .tab-btn.active {
            background: #1e3a8a; /* Dark Blue */
            color: #fff;
            border-color: #1e3a8a;
            box-shadow: 0 8px 16px rgba(30, 58, 138, 0.2);
            transform: translateY(-2px);
        }
        .tab-btn:hover:not(.active) {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            margin-bottom: 30px;
        }
        .form-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.5rem;
        }
        .form-icon {
            width: 40px; height: 40px;
            border-radius: 12px;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .form-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .form-group { margin-bottom: 1rem; }
        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control, .form-select {
            display: block;
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #1e293b;
            background-color: #f8fafc;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background-color: #fff;
            outline: 0;
        }
        textarea.form-control { resize: none; }

        .btn-submit {
            width: 100%;
            background: #1e3a8a;
            color: #fff;
            padding: 0.9rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
            transition: all 0.2s;
            margin-top: 1rem;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 58, 138, 0.3);
            background: #172554;
        }

        /* History Section */
        .history-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1rem;
            padding-left: 0.5rem;
        }
        
        .history-card {
            background: #fff;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 12px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .history-icon {
            width: 42px; height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            margin-right: 12px;
        }
        .history-info { flex-grow: 1; }
        .history-type { font-weight: 700; font-size: 0.9rem; color: #1e293b; margin-bottom: 2px; }
        .history-date { font-size: 0.75rem; color: #64748b; font-weight: 500; }
        .status-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.6rem;
            border-radius: 8px;
            text-transform: uppercase;
        }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }

        /* Animation */
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Cancel Button Overlay */
        .cancel-btn {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
    </style>

    <div x-data="{ tab: 'leave', showForm: true }" class="page-container">
        
        <!-- Header Section -->
        <div class="header-card">
            <h1 class="header-title">My Requests</h1>
            <p class="header-desc">Manage your Leave, Overtime (OT), and Day Off changes.</p>
            <span class="date-pill">{{ \Carbon\Carbon::now()->format('D, M d') }}</span>
        </div>

        <!-- Selection Controls -->
        <div class="d-flex justify-content-between align-items-center mb-3 px-2">
            <span style="font-size:0.85rem; font-weight:700; color:#475569;">Select request type</span>
            <button x-show="showForm" @click="showForm = false" class="cancel-btn">
                <i class="fa-solid fa-xmark"></i> Cancel
            </button>
            <button x-show="!showForm" @click="showForm = true" class="cancel-btn" style="background:#e0f2fe; color:#0369a1;">
                <i class="fa-solid fa-plus"></i> New
            </button>
        </div>

        <div class="tabs-container">
            <div class="tab-btn" :class="tab === 'leave' ? 'active' : ''" @click="tab = 'leave'; showForm = true">
                <i class="fa-regular fa-calendar-check"></i>
                <span>Leave</span>
            </div>
            <div class="tab-btn" :class="tab === 'ot' ? 'active' : ''" @click="tab = 'ot'; showForm = true">
                <i class="fa-regular fa-clock"></i>
                <span>Overtime</span>
            </div>
            <div class="tab-btn" :class="tab === 'dayoff' ? 'active' : ''" @click="tab = 'dayoff'; showForm = true">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                <span>Day Off</span>
            </div>
        </div>

        <!-- Forms Area -->
        <div x-show="showForm" x-transition.opacity.duration.300ms>
            
            <!-- Leave Form -->
            <div x-show="tab === 'leave'" class="form-card fade-in">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fa-regular fa-calendar-plus"></i>
                    </div>
                    <h3 class="form-title">New Leave Request</h3>
                </div>

                <form method="POST" enctype="multipart/form-data" action="{{ route('employee.leave.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Leave Type</label>
                        <select name="leave_type_id" class="form-select" required>
                            <option value="">— Select Type —</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-6 form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Reason <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Why are you requesting leave?"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Attachment <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="file" name="attachment" class="form-control">
                    </div>

                    <button type="submit" class="btn-submit">Submit Leave Request</button>
                </form>
            </div>

            <!-- Overtime Form -->
            <div x-show="tab === 'ot'" x-cloak class="form-card fade-in" 
                 x-data="{ start:'', end:'', hours:'0.0', calc() { if(this.start&&this.end){let s=new Date('1970-01-01T'+this.start),e=new Date('1970-01-01T'+this.end);this.hours=e>s?((e-s)/3600000).toFixed(1):'—';} } }">
                <div class="form-header">
                    <div class="form-icon" style="background:#fef9c3; color:#b45309;">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <h3 class="form-title">New Overtime Request</h3>
                </div>

                <form method="POST" action="{{ route('employee.overtime.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="ot_date" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 form-group">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" x-model="start" @input="calc()" class="form-control" required>
                        </div>
                        <div class="col-6 form-group">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" x-model="end" @input="calc()" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-muted">Estimated Hours</span>
                        <span class="fw-bold text-dark" x-text="hours + ' hrs'"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Task / Reason</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="What task will be accomplished?"></textarea>
                    </div>

                    <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#d97706,#92400e)">Submit OT Request</button>
                </form>
            </div>

            <!-- Day Off Form -->
            <div x-show="tab === 'dayoff'" x-cloak class="form-card fade-in">
                <div class="form-header">
                    <div class="form-icon" style="background:#ede9fe; color:#6d28d9;">
                        <i class="fa-solid fa-shuffle"></i>
                    </div>
                    <h3 class="form-title">Change Day Off</h3>
                </div>

                <form method="POST" action="{{ route('employee.changedayoff.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Original Day Off</label>
                        <input type="date" name="original_date" class="form-control" required>
                    </div>

                    <div class="text-center my-2 text-muted">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Preferred Date</label>
                        <input type="date" name="requested_date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" rows="2" placeholder="Brief explanation"></textarea>
                    </div>

                    <button type="submit" class="btn-submit" style="background:linear-gradient(135deg,#6d28d9,#4c1d95)">Submit Request</button>
                </form>
            </div>
        </div>

        <!-- History Section -->
        <h2 class="history-title">Request History</h2>

        <!-- Leave History -->
        <div x-show="tab === 'leave'">
            @forelse($leaveRequests as $leave)
            <div class="history-card fade-in">
                <div class="d-flex align-items-center w-100">
                    <div class="history-icon" style="background:#e0f2fe; color:#0369a1;">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <div class="history-info">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="history-type">{{ $leave->leaveType->name }}</div>
                                <div class="history-date">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</div>
                            </div>
                            <span class="status-badge status-{{ strtolower($leave->status) }}">{{ $leave->status }}</span>
                        </div>
                        @if($leave->admin_comment)
                            <div class="small text-danger mt-1 p-1 bg-light rounded px-2" style="font-size:0.7rem;">Note: {{ $leave->admin_comment }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted border rounded-3 bg-white">
                <i class="fa-regular fa-folder-open mb-2 display-6"></i>
                <p class="small m-0">No leave requests found</p>
            </div>
            @endforelse
            <div class="mt-3">{{ $leaveRequests->links() }}</div>
        </div>

        <!-- OT History -->
        <div x-show="tab === 'ot'" x-cloak>
            @forelse($otRequests as $ot)
            <div class="history-card fade-in">
                <div class="d-flex align-items-center w-100">
                    <div class="history-icon" style="background:#fef9c3; color:#b45309;">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="history-info">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="history-type">Overtime</div>
                                <div class="history-date">{{ \Carbon\Carbon::parse($ot->ot_date)->format('M d, Y') }} • {{ number_format($ot->total_hours, 1) }}h</div>
                            </div>
                            <span class="status-badge status-{{ strtolower($ot->status) }}">{{ $ot->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted border rounded-3 bg-white">
                <i class="fa-regular fa-folder-open mb-2 display-6"></i>
                <p class="small m-0">No overtime requests found</p>
            </div>
            @endforelse
            <div class="mt-3">{{ $otRequests->links() }}</div>
        </div>

         <!-- Day Off History -->
         <div x-show="tab === 'dayoff'" x-cloak>
            @forelse($dayoffRequests as $req)
            <div class="history-card fade-in">
                <div class="d-flex align-items-center w-100">
                    <div class="history-icon" style="background:#ede9fe; color:#6d28d9;">
                        <i class="fa-solid fa-shuffle"></i>
                    </div>
                    <div class="history-info">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="history-type">Day Off Change</div>
                                <div class="history-date">
                                    <span class="text-danger text-decoration-line-through">{{ \Carbon\Carbon::parse($req->original_date)->format('M d') }}</span>
                                    <i class="fa-solid fa-arrow-right mx-1 text-muted" style="font-size:0.7em"></i>
                                    <span class="text-success">{{ \Carbon\Carbon::parse($req->requested_date)->format('M d') }}</span>
                                </div>
                            </div>
                            <span class="status-badge status-{{ strtolower($req->status) }}">{{ $req->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted border rounded-3 bg-white">
                <i class="fa-regular fa-folder-open mb-2 display-6"></i>
                <p class="small m-0">No day-off requests found</p>
            </div>
            @endforelse
            <div class="mt-3">{{ $dayoffRequests->links() }}</div>
        </div>

        <div style="height: 100px;"></div>
    </div>
</x-layouts.employee>
