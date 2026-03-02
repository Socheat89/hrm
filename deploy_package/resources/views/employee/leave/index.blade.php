<x-layouts.employee page-title="My Requests" page-description="Manage your Leave, Overtime (OT), and Day Off changes.">
    <style>
        [x-cloak] { display: none !important; }
        
        .balance-card {
            background: linear-gradient(135deg, #12395f 0%, #2a5b8e 100%);
            color: #fff;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 30px rgba(17, 40, 72, 0.15);
            position: relative;
            overflow: hidden;
        }

        .balance-card::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .nav-pills-custom {
            background: #fff;
            padding: 5px;
            border-radius: 16px;
            box-shadow: var(--shadow-soft);
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
        }

        .nav-pills-custom .nav-link {
            border-radius: 12px;
            color: var(--muted);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.65rem 1rem;
            flex: 1;
            text-align: center;
            border: none;
            background: transparent;
            transition: all 0.2s ease;
        }

        .nav-pills-custom .nav-link.active {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 4px 10px rgba(31, 79, 130, 0.2);
        }

        .form-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .form-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .leave-entry {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.2rem;
            background: #fff;
            transition: transform 0.2s;
        }

        .leave-entry:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
            border-color: #cbd5e1;
        }

        .status-badge {
            border-radius: 8px;
            font-size: 0.72rem;
            padding: 0.35rem 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-approved { color: #047857; background: #d1fae5; }
        .status-rejected { color: #b91c1c; background: #fee2e2; }
        .status-pending { color: #b45309; background: #fef3c7; }

        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            background-color: #f8fafc;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(31, 79, 130, 0.1);
        }

        .input-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.4rem;
        }

        .btn-submit {
            background: var(--brand);
            color: #fff;
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 700;
            border: none;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
        }
        
        .request-type-icon {
            width: 32px; height: 32px;
            border-radius: 10px;
            display: inline-flex; justify-content: center; align-items: center;
            margin-right: 10px;
        }
    </style>

    <div x-data="{ tab: 'leave', isRequesting: false }">
        <!-- Dashboard Summary / Balance -->
        <section class="balance-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="d-block mb-1 opacity-75" style="font-size: 0.85rem;">Available Leave Balance</span>
                    <h2 class="mb-0 fw-bold" style="font-size: 2rem;">{{ number_format($employee->leave_balance_days, 1) }} <span style="font-size: 1rem; font-weight: 500; opacity: 0.8;">Days</span></h2>
                </div>
                <!-- Optional graphic/icon -->
                <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 16px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
        </section>

        <!-- Start Request Toggle Button -->
        <div class="d-flex justify-content-center mb-4" x-show="!isRequesting" x-transition>
            <button @click="isRequesting = true" class="btn btn-submit d-flex align-items-center justify-content-center gap-2" style="font-size: 1.05rem; padding: 0.9rem;">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                ការស្នើសុំថ្មី (New Request)
            </button>
        </div>

        <!-- Requests Section Container -->
        <div x-show="isRequesting" x-transition x-cloak>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-muted">ជ្រើសរើសប្រភេទ (Select Type)</h6>
                <button @click="isRequesting = false" class="btn text-danger btn-sm bg-white border border-danger shadow-sm rounded-pill fw-bold px-3">
                    <svg width="16" height="16" class="me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    បិទ (Close)
                </button>
            </div>

            <!-- Dynamic Tabs -->
            <nav class="nav-pills-custom">
            <button class="nav-link" :class="{ 'active': tab === 'leave' }" @click="tab = 'leave'">
                <svg width="18" height="18" class="mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <br>Leave
            </button>
            <button class="nav-link" :class="{ 'active': tab === 'ot' }" @click="tab = 'ot'">
                <svg width="18" height="18" class="mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <br>OT Request
            </button>
            <button class="nav-link" :class="{ 'active': tab === 'dayoff' }" @click="tab = 'dayoff'">
                <svg width="18" height="18" class="mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <br>Change Dayoff
            </button>
        </nav>

        <!-- FORM: Leave Request -->
        <section class="form-section mb-4" :class="{ 'active': tab === 'leave' }">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="request-type-icon" style="background:#e0e7ff; color:#059669;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h3 class="mb-0 fw-bold" style="font-size: 1.15rem; color:#1e293b;">New Leave Request</h3>
                </div>

                <form method="POST" enctype="multipart/form-data" action="{{ route('employee.leave.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="input-label">Leave Type</label>
                        <select name="leave_type_id" class="form-select" required>
                            <option value="">Select Type</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="input-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="input-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="input-label">Reason (Optional)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Why are you requesting leave?"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="input-label">Attachment (Optional)</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                    <div class="col-12 pt-2">
                        <button class="btn-submit">Submit Leave Request</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- FORM: OT Request -->
        <section class="form-section mb-4" :class="{ 'active': tab === 'ot' }">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="request-type-icon" style="background:#fef3c7; color:#d97706;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="mb-0 fw-bold" style="font-size: 1.15rem; color:#1e293b;">Overtime (OT) Request</h3>
                </div>

                <form method="POST" action="{{ route('employee.overtime.store') }}" class="row g-3" x-data="{ start: '', end: '', hours: '0.0', calcHours() {
                        if (this.start && this.end) {
                            let s = new Date(`1970-01-01T${this.start}:00`);
                            let e = new Date(`1970-01-01T${this.end}:00`);
                            if (e > s) {
                                this.hours = ((e - s) / 3600000).toFixed(1);
                            } else {
                                this.hours = '0.0 (Invalid)';
                            }
                        } else {
                            this.hours = '0.0';
                        }
                    } }">
                    @csrf
                    <div class="col-12">
                        <label class="input-label">Date of Overtime</label>
                        <input type="date" name="ot_date" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="input-label">Start Time</label>
                        <input type="time" name="start_time" x-model="start" @input="calcHours()" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="input-label">End Time</label>
                        <input type="time" name="end_time" x-model="end" @input="calcHours()" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary" style="font-size:0.85rem">Total Hours Estimated</span>
                                <strong class="text-primary font-bold" x-text="hours + ' hrs'"></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="input-label">Task / Reason</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="What task will be accomplished?"></textarea>
                    </div>
                    <div class="col-12 pt-2">
                        <button class="btn-submit" style="background: #eab308; color: #fff;">Request OT</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- FORM: Change Day Off Request -->
        <section class="form-section mb-4" :class="{ 'active': tab === 'dayoff' }">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="request-type-icon" style="background:#e0f2fe; color:#4f46e5;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="mb-0 fw-bold" style="font-size: 1.15rem; color:#1e293b;">Change Day Off</h3>
                </div>

                <form method="POST" action="{{ route('employee.changedayoff.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="input-label">Original Day Off Date</label>
                        <input type="date" name="original_date" class="form-control" required>
                    </div>
                    <div class="col-12 text-center my-2 text-muted">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                    <div class="col-12">
                        <label class="input-label">New Wanted Day Off</label>
                        <input type="date" name="requested_date" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="input-label">Reason</label>
                        <textarea class="form-control" name="reason" rows="2" placeholder="Brief explanation"></textarea>
                    </div>
                    <div class="col-12 pt-2">
                        <button class="btn-submit" style="background: #4f46e5;">Request Change</button>
                    </div>
                </form>
            </div>
        </section>
        </div> <!-- End of Requests Section Container -->

        <!-- Requests History -->
        <section>
            <div class="d-flex justify-content-between align-items-end mb-3 mt-4">
                <h3 class="mb-0 fw-bold" style="font-size: 1.1rem; color: #334155;">Recent Requests</h3>
            </div>

            <!-- LEAVE HISTORY -->
            <div x-show="tab === 'leave'" x-transition class="d-grid gap-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-light text-secondary border">{{ $leaveRequests->total() }} records</span>
                </div>
                @forelse($leaveRequests as $leave)
                    <article class="leave-entry">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded p-2 text-secondary">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold" style="font-size: 0.95rem; color:#1e293b;">{{ $leave->leaveType->name }}</h4>
                                    <small class="text-muted">{{ number_format($leave->days, 1) }} Days</small>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $leave->status }}">{{ $leave->status }}</span>
                        </div>
                        
                        <div class="mt-3 bg-light rounded px-3 py-2" style="font-size: 0.85rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-slate-500 fw-medium">Dates:</span>
                                <span class="text-slate-800 fw-bold">{{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}</span>
                            </div>
                            @if($leave->reason)
                                <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                                    <span class="text-slate-500 fw-medium">Reason:</span>
                                    <span class="text-slate-800 text-end" style="max-width: 70%">{{ $leave->reason }}</span>
                                </div>
                            @endif
                            @if($leave->admin_comment)
                                <div class="mt-2 text-danger">
                                    <span class="fw-bold">HR Reply:</span> {{ $leave->admin_comment }}
                                </div>
                            @endif
                        </div>

                        @if($leave->attachment_path)
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill fw-bold" style="font-size:0.75rem;">
                                    View Proof
                                </a>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 border-0 shadow-sm">
                        <svg width="48" height="48" class="text-muted opacity-50 mb-3 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <h6 class="fw-bold text-slate-800">No Leave Requests</h6>
                    </div>
                @endforelse
                <div class="mt-2">{{ $leaveRequests->links() }}</div>
            </div>

            <!-- OT HISTORY -->
            <div x-show="tab === 'ot'" x-cloak x-transition class="d-grid gap-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-light text-secondary border">{{ $otRequests->total() }} records</span>
                </div>
                @forelse($otRequests as $ot)
                    <article class="leave-entry">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded p-2 text-warning">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold" style="font-size: 0.95rem; color:#1e293b;">Overtime</h4>
                                    <small class="text-muted">{{ number_format($ot->total_hours, 1) }} Hours</small>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $ot->status }}">{{ $ot->status }}</span>
                        </div>
                        
                        <div class="mt-3 bg-light rounded px-3 py-2" style="font-size: 0.85rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-slate-500 fw-medium">Date:</span>
                                <span class="text-slate-800 fw-bold">{{ \Carbon\Carbon::parse($ot->ot_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-slate-500 fw-medium">Time:</span>
                                <span class="text-slate-800 fw-bold">{{ \Carbon\Carbon::parse($ot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($ot->end_time)->format('h:i A') }}</span>
                            </div>
                            @if($ot->reason)
                                <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                                    <span class="text-slate-500 fw-medium">Task:</span>
                                    <span class="text-slate-800 text-end" style="max-width: 70%">{{ $ot->reason }}</span>
                                </div>
                            @endif
                            @if($ot->admin_comment)
                                <div class="mt-2 text-danger">
                                    <span class="fw-bold">HR Reply:</span> {{ $ot->admin_comment }}
                                </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 border-0 shadow-sm">
                        <svg width="48" height="48" class="text-muted opacity-50 mb-3 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h6 class="fw-bold text-slate-800">No OT Requests</h6>
                    </div>
                @endforelse
                <div class="mt-2">{{ $otRequests->links() }}</div>
            </div>

            <!-- CHANGE DAYOFF HISTORY -->
            <div x-show="tab === 'dayoff'" x-cloak x-transition class="d-grid gap-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-light text-secondary border">{{ $dayoffRequests->total() }} records</span>
                </div>
                @forelse($dayoffRequests as $do)
                    <article class="leave-entry">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-light rounded p-2 text-primary">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold" style="font-size: 0.95rem; color:#1e293b;">Day Off Change</h4>
                                </div>
                            </div>
                            <span class="status-badge status-{{ $do->status }}">{{ $do->status }}</span>
                        </div>
                        
                        <div class="mt-3 bg-light rounded px-3 py-2" style="font-size: 0.85rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-slate-500 fw-medium">Original:</span>
                                <span class="text-slate-800 fw-bold text-decoration-line-through text-danger">{{ \Carbon\Carbon::parse($do->original_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-slate-500 fw-medium">New Wanted:</span>
                                <span class="text-slate-800 fw-bold text-success">{{ \Carbon\Carbon::parse($do->requested_date)->format('M d, Y') }}</span>
                            </div>
                            @if($do->reason)
                                <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                                    <span class="text-slate-500 fw-medium">Reason:</span>
                                    <span class="text-slate-800 text-end" style="max-width: 70%">{{ $do->reason }}</span>
                                </div>
                            @endif
                            @if($do->admin_comment)
                                <div class="mt-2 text-danger">
                                    <span class="fw-bold">HR Reply:</span> {{ $do->admin_comment }}
                                </div>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 border-0 shadow-sm">
                        <svg width="48" height="48" class="text-muted opacity-50 mb-3 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                        <h6 class="fw-bold text-slate-800">No Day Off Changes</h6>
                    </div>
                @endforelse
                <div class="mt-2">{{ $dayoffRequests->links() }}</div>
            </div>
        </section>
    </div>

    <!-- Alpine.js for Tabs Setup -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-layouts.employee>
