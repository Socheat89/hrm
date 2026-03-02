<x-layouts.employee :show-page-banner="false">
<style>
.atd-wrap{max-width:500px;margin:0 auto;padding:.5rem 1rem 3rem;display:flex;flex-direction:column;gap:1.1rem}
.atd-clock{text-align:center;font-size:clamp(2.4rem,10vw,4rem);font-weight:900;letter-spacing:-.04em;color:#0f3460;line-height:1;user-select:none}
.atd-date{text-align:center;font-size:.82rem;color:#5a7fa8;margin-top:.25rem;font-weight:500}

/* ─── action buttons ─── */
.atd-actions{display:grid;grid-template-columns:1fr 1fr;gap:.9rem}
.atd-btn{border:none;border-radius:18px;padding:1.2rem .5rem;display:flex;flex-direction:column;align-items:center;gap:.4rem;cursor:pointer;transition:transform .15s,box-shadow .15s;font-family:inherit}
.atd-btn:active{transform:scale(.97)}
.atd-btn.checkin{background:linear-gradient(135deg,#1a6b44,#27ae60);color:#fff;box-shadow:0 8px 24px rgba(27,107,68,.3)}
.atd-btn.checkout{background:linear-gradient(135deg,#1a3a6b,#2d5fb3);color:#fff;box-shadow:0 8px 24px rgba(26,58,107,.3)}
.atd-btn.disabled-btn{background:#e8ecf0;color:#aab4c0;cursor:not-allowed;box-shadow:none}
.atd-btn .btn-icon{font-size:2rem;line-height:1}
.atd-btn .btn-label{font-size:.88rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase}
.atd-btn .btn-sub{font-size:.7rem;opacity:.8;font-weight:500}
.atd-btn.busy{animation:pulse-btn 1s infinite}
@keyframes pulse-btn{0%,100%{opacity:1}50%{opacity:.6}}

/* ─── single scan button ─── */
.atd-scan-btn{width:100%;border:none;border-radius:18px;padding:1.4rem 1rem;display:flex;flex-direction:column;align-items:center;gap:.4rem;cursor:pointer;transition:transform .15s,box-shadow .15s;font-family:inherit;background:linear-gradient(135deg,#0f3460,#1565c0);color:#fff;box-shadow:0 8px 24px rgba(15,52,96,.3)}
.atd-scan-btn:active{transform:scale(.98)}
.atd-scan-btn .btn-icon{font-size:2.2rem;line-height:1}
.atd-scan-btn .btn-label{font-size:1rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase}
.atd-scan-btn .btn-sub{font-size:.76rem;opacity:.85;font-weight:500}

/* ─── type selector (overlays inside camera) ─── */
.atd-type-sel{display:flex;gap:.6rem;padding:.55rem .8rem;background:rgba(8,24,52,.88)}
.type-opt{flex:1;padding:.52rem .4rem;border:2px solid rgba(255,255,255,.2);border-radius:10px;font-size:.82rem;font-weight:700;cursor:pointer;text-align:center;transition:all .15s;background:rgba(255,255,255,.07);color:#c8d8ee;font-family:inherit}
.type-opt.active-in{border-color:#27ae60;background:rgba(27,174,96,.25);color:#7ae8a8}
.type-opt.active-out{border-color:#4a90d9;background:rgba(74,144,217,.25);color:#88c4f5}

/* ─── camera box ─── */
.qr-box{border-radius:18px;overflow:hidden;border:2px solid #c5d8ef;background:#000;display:none;position:relative}
.qr-box.visible{display:block}
.qr-box #qrReader video{width:100%!important;height:280px!important;object-fit:cover;display:block}
.qr-aim{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none}
.qr-aim svg{width:180px;height:180px;opacity:.7}
.qr-status-bar{background:rgba(3,26,46,.85);color:#7db8f5;text-align:center;font-size:.82rem;font-weight:600;padding:.45rem .8rem}

/* ─── result cards ─── */
.atd-result{border-radius:16px;padding:1rem 1.2rem;display:flex;align-items:flex-start;gap:.8rem;animation:slide-in .3s ease}
.atd-result.success{background:#e8f5e9;border:1px solid #a5d6a7}
.atd-result.error{background:#fdecea;border:1px solid #ef9a9a}
.atd-result-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
.success .atd-result-icon{background:#c8e6c9}
.error .atd-result-icon{background:#ffcdd2}
.atd-result-body h6{margin:0 0 .2rem;font-weight:800;font-size:.93rem}
.atd-result-body p{margin:0;font-size:.8rem;color:#555;line-height:1.4}
.success .atd-result-body h6{color:#1b5e20}
.error .atd-result-body h6{color:#b71c1c}
@keyframes slide-in{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}

.atd-msg{font-size:.83rem;padding:.5rem .9rem;border-radius:10px;text-align:center;display:none}
.atd-msg.visible{display:block}
.atd-msg.info{background:#e3edf8;color:#1a3a6b}
.atd-msg.danger{background:#fdecea;color:#b71c1c}
.atd-msg.ok{background:#e8f5e9;color:#1b5e20}

/* ─── summary ─── */
.atd-summary-card{background:#fff;border:1px solid #dde8f4;border-radius:16px;overflow:hidden}
.atd-summary-header{background:#f0f6ff;padding:.55rem 1rem;font-size:.75rem;font-weight:700;color:#2d5086;text-transform:uppercase;letter-spacing:.06em}
.atd-summary-row{display:flex;align-items:center;gap:.75rem;padding:.6rem 1rem;border-bottom:1px solid #eaf1fb}
.atd-summary-row:last-child{border-bottom:none}
.atd-summary-badge{width:28px;height:28px;border-radius:50%;font-size:.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.badge-in{background:#e8f5e9;color:#1b6b31}
.badge-out{background:#e3eaf5;color:#2d5086}
.badge-miss{background:#f5f5f5;color:#b0bec5}
.atd-summary-row .row-label{font-size:.82rem;color:#5a7fa8;font-weight:600;flex:1}
.atd-summary-row .row-time{font-size:.88rem;font-weight:800;color:#1b6b31}
.atd-summary-row .row-miss{font-size:.82rem;color:#cdd5df}

/* ─── stats ─── */
.atd-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:.7rem}
.atd-stat-box{background:#f5f9ff;border:1px solid #dde8f5;border-radius:12px;padding:.55rem .5rem;text-align:center}
.atd-stat-box .n{font-size:1.15rem;font-weight:900;color:#0f3460}
.atd-stat-box .l{font-size:.66rem;color:#7a96b5;font-weight:600;text-transform:uppercase;letter-spacing:.05em}
.atd-stat-box.late .n{color:#e65100}
.atd-stat-box.ot .n{color:#1565c0}

/* show only camera area while scanning */
.atd-wrap.scanning .scan-hide-on-active{display:none!important}
</style>

<div class="atd-wrap">

    {{-- Clock --}}
    <div class="scan-hide-on-active">
        <div class="atd-clock" id="liveClock">--:--:--</div>
        <div class="atd-date"  id="liveDate"></div>
    </div>

    {{-- Flash result --}}
    @if(session('scan_result'))
        @php $res = session('scan_result'); @endphp
        <div class="atd-result {{ $res['type'] }} scan-hide-on-active">
            <div class="atd-result-icon">{{ $res['type']==='success' ? '✅' : '❌' }}</div>
            <div class="atd-result-body">
                @if($res['type']==='success')
                    <h6>{{ $res['scan_type'] ?? 'Scan' }} Recorded</h6>
                    <p>{{ $res['time'] ?? '' }} &middot; <strong>{{ $res['status'] ?? 'On Time' }}</strong>
                        @if(!empty($res['distance'])) &middot; {{ $res['distance'] }} from branch @endif
                    </p>
                @else
                    <h6>Scan Rejected</h6>
                    <p>{{ $res['message'] ?? 'Could not record attendance.' }}</p>
                @endif
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="atd-result error scan-hide-on-active">
            <div class="atd-result-icon">❌</div>
            <div class="atd-result-body"><h6>Error</h6><p>{{ $errors->first() }}</p></div>
        </div>
    @endif

    {{-- Hidden form --}}
    <form method="POST" action="{{ route('employee.attendance.store') }}" id="scanForm" style="display:none">
        @csrf
        <input type="hidden" name="scan_type"   id="scanTypeInput" value="">
        <input type="hidden" name="device_info" id="deviceInfo"    value="">
        <input type="hidden" name="qr_token"    id="qrTokenInput"  value="">
        <input type="hidden" name="latitude"    id="latInput"      value="">
        <input type="hidden" name="longitude"   id="lngInput"      value="">
    </form>

    @if(!$allDone)
    {{-- Single scan button --}}
    @php
        $ciTypes   = ['morning_in','lunch_in'];
        $autoLabel = in_array($autoDefault, $ciTypes) ? 'Check-In' : 'Check-Out';
        $autoEmoji = in_array($autoDefault, $ciTypes) ? '🟢' : '🔵';
    @endphp
    <button type="button" class="atd-scan-btn scan-hide-on-active" id="btnScan">
        <span class="btn-icon">📷</span>
        <span class="btn-label">Scan Attendance</span>
        <span class="btn-sub" id="btnScanSub">Auto: {{ $autoEmoji }} {{ $autoLabel }}</span>
    </button>

    @else
    {{-- All done card --}}
    <div style="text-align:center;padding:1.5rem;background:#e8f5e9;border-radius:18px;border:1px solid #a5d6a7">
        <div style="font-size:3rem">✅</div>
        <div style="font-weight:800;color:#1b5e20;margin-top:.4rem">All scans completed!</div>
        <div style="font-size:.82rem;color:#2e7d32;margin-top:.2rem">See you tomorrow</div>
    </div>
    @endif

    {{-- Camera area (hidden until button tapped) --}}
    @if(in_array($scanMode, ['qr','gps_qr']))
    <div class="qr-box" id="qrBox">
        <div id="qrReader"></div>
        <div class="qr-aim">
            <svg viewBox="0 0 200 200" fill="none"><rect x="8" y="8" width="50" height="50" rx="6" stroke="white" stroke-width="5" fill="none"/><rect x="142" y="8" width="50" height="50" rx="6" stroke="white" stroke-width="5" fill="none"/><rect x="8" y="142" width="50" height="50" rx="6" stroke="white" stroke-width="5" fill="none"/><rect x="142" y="142" width="50" height="50" rx="6" stroke="white" stroke-width="5" fill="none"/></svg>
        </div>
        {{-- Type selector shown inside camera when active --}}
        <div class="atd-type-sel" id="typeSel">
            @if($nextCheckIn)
            <button type="button" class="type-opt" id="typeOptIn" data-type="{{ $nextCheckIn }}">🟢 Check-In</button>
            @endif
            @if($nextCheckOut)
            <button type="button" class="type-opt" id="typeOptOut" data-type="{{ $nextCheckOut }}">🔵 Check-Out</button>
            @endif
        </div>
        <div class="qr-status-bar" id="qrStatus">Point camera at QR code…</div>
    </div>
    <button type="button" id="cancelBtn" onclick="cancelScan()" style="display:none;width:100%;padding:.55rem;border:1.5px solid #ef9a9a;border-radius:12px;background:#fdecea;color:#b71c1c;font-size:.85rem;font-weight:700;cursor:pointer">✕ Cancel</button>
    @endif

    <div id="scanMsg" class="atd-msg"></div>

    {{-- Today's summary --}}
    <div class="atd-summary-card scan-hide-on-active">
        <div class="atd-summary-header">Today's Scan Summary</div>
        @php
            $summaryRows = [
                'morning_in'  => ['label' => 'Check-In 1',  'type' => 'in'],
                'lunch_out'   => ['label' => 'Check-Out 1', 'type' => 'out'],
                'lunch_in'    => ['label' => 'Check-In 2',  'type' => 'in'],
                'evening_out' => ['label' => 'Check-Out 2', 'type' => 'out'],
            ];
        @endphp
        @foreach($summaryRows as $key => $row)
            @php $log = $todayLogs->get($key); @endphp
            <div class="atd-summary-row">
                <div class="atd-summary-badge {{ $log ? 'badge-'.($row['type']) : 'badge-miss' }}">
                    {{ $row['type']==='in' ? 'IN' : 'OUT' }}
                </div>
                <span class="row-label">{{ $row['label'] }}</span>
                @if($log)
                    <span class="row-time">{{ $log->scanned_at?->format('H:i') }}</span>
                @else
                    <span class="row-miss">— —</span>
                @endif
            </div>
        @endforeach
    </div>

    @if($session)
    <div class="atd-stats scan-hide-on-active">
        <div class="atd-stat-box">
            <div class="n">{{ number_format($session->work_minutes/60,1) }}h</div>
            <div class="l">Work Hrs</div>
        </div>
        <div class="atd-stat-box late">
            <div class="n">{{ $session->late_minutes }}</div>
            <div class="l">Late Min</div>
        </div>
        <div class="atd-stat-box ot">
            <div class="n">{{ number_format($session->overtime_minutes/60,1) }}h</div>
            <div class="l">Overtime</div>
        </div>
    </div>
    @endif

</div>

@if(in_array($scanMode, ['qr','gps_qr']))
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endif

<script>
window.addEventListener('load', function () {
    /* clock */
    function tick(){
        const n=new Date();
        document.getElementById('liveClock').textContent=n.toLocaleTimeString([],{hour:'2-digit',minute:'2-digit',second:'2-digit'});
        document.getElementById('liveDate').textContent=n.toLocaleDateString([],{weekday:'long',year:'numeric',month:'long',day:'numeric'});
    }
    tick(); setInterval(tick,1000);
    document.getElementById('deviceInfo').value=navigator.userAgent;

    const SCAN_MODE    = '{{ $scanMode }}';
    const NEEDS_GPS    = SCAN_MODE==='gps' || SCAN_MODE==='gps_qr';
    const NEEDS_QR     = SCAN_MODE==='qr'  || SCAN_MODE==='gps_qr';
    const AUTO_DEFAULT = '{{ $autoDefault }}';
    const NEXT_CI      = '{{ $nextCheckIn ?? "" }}';
    const NEXT_CO      = '{{ $nextCheckOut ?? "" }}';
    const CI_TYPES     = ['morning_in','lunch_in'];

    const form       = document.getElementById('scanForm');
    const typeInput  = document.getElementById('scanTypeInput');
    const qrTokenInp = document.getElementById('qrTokenInput');
    const latInp     = document.getElementById('latInput');
    const lngInp     = document.getElementById('lngInput');
    const msgEl      = document.getElementById('scanMsg');
    const wrapEl     = document.querySelector('.atd-wrap');
    const qrBoxEl    = document.getElementById('qrBox');
    const qrStatusEl = document.getElementById('qrStatus');
    const cancelBtn  = document.getElementById('cancelBtn');
    const typeOptIn  = document.getElementById('typeOptIn');
    const typeOptOut = document.getElementById('typeOptOut');
    const btnScanSub = document.getElementById('btnScanSub');

    /* Current scan type — starts with auto-default */
    let currentType = AUTO_DEFAULT;

    function showMsg(t,k='info'){msgEl.textContent=t;msgEl.className='atd-msg visible '+k;}
    function hideMsg(){msgEl.className='atd-msg';}
    function setStatus(t){if(qrStatusEl)qrStatusEl.textContent=t;}
    function setScanningUI(active){if(wrapEl)wrapEl.classList.toggle('scanning',active);}

    /* Type selector */
    function setActiveType(type){
        if(!type)return;
        currentType=type;
        typeInput.value=type;
        const isCI=CI_TYPES.includes(type);
        if(typeOptIn){typeOptIn.className='type-opt'+(NEXT_CI===type?' active-in':'');}
        if(typeOptOut){typeOptOut.className='type-opt'+(NEXT_CO===type?' active-out':'');}
        if(btnScanSub)btnScanSub.textContent='Auto: '+(isCI?'🟢 Check-In':'🔵 Check-Out');
    }

    /* Wire type selector buttons */
    if(typeOptIn)  typeOptIn.addEventListener('click',function(){setActiveType(NEXT_CI);});
    if(typeOptOut) typeOptOut.addEventListener('click',function(){setActiveType(NEXT_CO);});

    /* Apply initial active state */
    setActiveType(AUTO_DEFAULT);

    /* GPS */
    let gpsPromise=null;
    function getGps(){
        if(gpsPromise)return gpsPromise;
        gpsPromise=new Promise(resolve=>{
            if(!navigator.geolocation){resolve(false);return;}
            navigator.geolocation.getCurrentPosition(
                p=>{latInp.value=p.coords.latitude.toFixed(6);lngInp.value=p.coords.longitude.toFixed(6);resolve(true);},
                ()=>resolve(false),
                {enableHighAccuracy:true,timeout:14000,maximumAge:0}
            );
        });
        return gpsPromise;
    }

    /* QR scanner */
    let scanner=null, scanRunning=false, submitted=false;

    async function startQrScanner(){
        if(scanRunning)return;
        typeInput.value=currentType;
        qrTokenInp.value='';
        submitted=false;
        try{
        setScanningUI(true);
        qrBoxEl.classList.add('visible');
        if(cancelBtn)cancelBtn.style.display='block';
        setStatus('🎯 Point camera at the QR code…');
        if(!scanner) scanner=new Html5Qrcode('qrReader');
            await scanner.start(
                {facingMode:'environment'},
                {fps:12, qrbox:function(w,h){const s=Math.min(w,h)*0.7;return{width:s,height:s};}},
                async decoded=>{
                    if(submitted)return;
                    submitted=true;
                    typeInput.value=currentType; // capture selected type at scan moment
                    qrTokenInp.value=decoded.trim();
                    setStatus('✅ QR scanned! Verifying…');
                    try{await scanner.stop();}catch(_){}
                    scanRunning=false;
                    if(NEEDS_GPS){
                        setStatus('📍 Getting location…');
                        const ok=await getGps();
                        if(!ok){
                            showMsg('Location access denied. Please allow GPS and try again.','danger');
                            setStatus('⚠ Location denied.');
                            if(qrBoxEl)qrBoxEl.classList.remove('visible');
                            if(cancelBtn)cancelBtn.style.display='none';
                            setScanningUI(false);
                            submitted=false;
                            return;
                        }
                    }
                    setStatus('Submitting…');
                    form.submit();
                },
                ()=>{}
            );
            scanRunning=true;
        }catch(err){
            scanRunning=false;
            setScanningUI(false);
            qrBoxEl.classList.remove('visible');
            if(cancelBtn)cancelBtn.style.display='none';
            if(err.name==='NotAllowedError'||err.name==='PermissionDeniedError')
                showMsg('Camera permission denied. Please allow camera access.','danger');
            else if(err.name==='NotFoundError')
                showMsg('No camera found on this device.','danger');
            else if(location.protocol!=='https:'&&location.hostname!=='localhost')
                showMsg('HTTPS required for camera access.','danger');
            else
                showMsg('Cannot start camera: '+err.message,'danger');
        }
    }

    window.cancelScan=async function(){
        submitted=false;
        if(scanner&&scanRunning){try{await scanner.stop();}catch(_){}}
        scanRunning=false;
        setScanningUI(false);
        if(qrBoxEl)qrBoxEl.classList.remove('visible');
        if(cancelBtn)cancelBtn.style.display='none';
        hideMsg();
    };

    /* GPS-only scan */
    async function doGpsScan(){
        typeInput.value=currentType;
        showMsg('Getting your location…','info');
        const ok=await getGps();
        if(!ok){showMsg('Location denied. Allow GPS and try again.','danger');return;}
        showMsg('Location found. Submitting…','ok');
        form.submit();
    }

    /* Single scan button */
    const btnScan=document.getElementById('btnScan');
    if(btnScan){
        btnScan.addEventListener('click',function(){
            if(NEEDS_QR){
                if(scanRunning){window.cancelScan();return;}
                startQrScanner();
            }else{
                doGpsScan();
            }
        });
    }
});
</script>
</x-layouts.employee>