<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FlowHR') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *,*::before,*::after{box-sizing:border-box}
        :root{
            --blue:#3b82f6;--indigo:#6366f1;--purple:#8b5cf6;
            --dark:#040d1a;--dark2:#071428;--dark3:#0c1f38;
            --card:#0e1e35;--border:rgba(59,130,246,.18);
            --text:#94a3b8;--white:#f0f6ff;
        }
        body{
            font-family:'Outfit',sans-serif;
            background:var(--dark);
            color:var(--text);
            -webkit-font-smoothing:antialiased;
            overflow-x:hidden;
            margin:0;padding:0;
        }
        [x-cloak]{display:none!important}

        /* ── LOADING SPINNER ── */
        .page-loader{
            position:fixed;inset:0;z-index:200;
            background:var(--dark);
            display:flex;align-items:center;justify-content:center;
            transition:opacity .5s
        }
        .spinner{
            width:44px;height:44px;border-radius:50%;
            border:3px solid rgba(59,130,246,.2);
            border-top-color:var(--blue);
            animation:spin .8s linear infinite
        }
        @keyframes spin{to{transform:rotate(360deg)}}

        /* ── LAYOUT ── */
        .auth-wrap{
            min-height:100vh;display:flex;
        }

        /* ── LEFT PANEL ── */
        .auth-left{
            width:55%;display:flex;flex-direction:column;
            justify-content:space-between;
            padding:3rem;position:relative;overflow:hidden;
            background:var(--dark2);
        }
        @media(max-width:1023px){.auth-left{display:none}}

        .auth-left-bg1{
            position:absolute;top:-15%;left:-15%;
            width:60%;height:60%;border-radius:50%;
            background:radial-gradient(circle,rgba(59,130,246,.22) 0%,transparent 70%);
            pointer-events:none;animation:blobA 9s ease-in-out infinite alternate
        }
        .auth-left-bg2{
            position:absolute;bottom:-15%;right:-15%;
            width:70%;height:70%;border-radius:50%;
            background:radial-gradient(circle,rgba(139,92,246,.15) 0%,transparent 70%);
            pointer-events:none;animation:blobA 12s ease-in-out infinite alternate-reverse
        }
        @keyframes blobA{0%{transform:scale(1) translate(0,0)}100%{transform:scale(1.15) translate(40px,30px)}}

        .auth-left-dots{
            position:absolute;inset:0;pointer-events:none;
            background-image:radial-gradient(rgba(59,130,246,.25) 1px,transparent 1px);
            background-size:36px 36px;opacity:.25
        }

        .auth-logo{
            position:relative;z-index:1;
            display:flex;align-items:center;gap:.75rem;
            text-decoration:none
        }
        .auth-logo-icon{
            width:42px;height:42px;border-radius:12px;
            background:linear-gradient(135deg,var(--blue),var(--indigo));
            display:flex;align-items:center;justify-content:center;
            color:white;font-size:1.1rem;
            box-shadow:0 0 24px rgba(59,130,246,.45)
        }
        .auth-logo-text{
            font-size:1.6rem;font-weight:900;color:var(--white);letter-spacing:-.03em
        }
        .auth-logo-text span{
            background:linear-gradient(90deg,var(--blue),var(--purple));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent
        }

        .auth-left-body{position:relative;z-index:1}
        .auth-left-tag{
            display:inline-flex;align-items:center;gap:.5rem;
            padding:.35rem 1rem;border-radius:100px;
            background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.25);
            font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
            color:var(--blue);margin-bottom:1.5rem
        }
        .auth-left-tag span{
            width:6px;height:6px;background:var(--blue);border-radius:50%;
            animation:pulse 1.5s infinite
        }
        @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.6)}}

        .auth-left-h{
            font-size:clamp(2rem,3.5vw,3rem);font-weight:900;
            color:var(--white);line-height:1.12;margin-bottom:1.2rem;
            letter-spacing:-.025em
        }
        .auth-left-h .grad{
            background:linear-gradient(135deg,var(--blue),var(--purple),#ec4899);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent
        }
        .auth-left-p{
            font-size:1rem;color:var(--text);line-height:1.85;
            max-width:380px;margin-bottom:2.5rem
        }

        /* Feature pills */
        .auth-features{display:flex;flex-direction:column;gap:.85rem}
        .auth-feat{
            display:flex;align-items:center;gap:1rem;
            padding:1rem 1.25rem;border-radius:14px;
            background:rgba(14,30,53,.7);border:1px solid var(--border);
            transition:border-color .3s
        }
        .auth-feat:hover{border-color:rgba(99,102,241,.4)}
        .auth-feat-icon{
            width:38px;height:38px;border-radius:10px;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;font-size:.95rem
        }
        .auth-feat-icon.blue{background:rgba(59,130,246,.15);color:#60a5fa}
        .auth-feat-icon.green{background:rgba(34,197,94,.12);color:#4ade80}
        .auth-feat-icon.purple{background:rgba(139,92,246,.15);color:#a78bfa}
        .auth-feat-text strong{display:block;font-size:.88rem;font-weight:700;color:var(--white)}
        .auth-feat-text span{font-size:.78rem;color:var(--text)}

        /* Stats row */
        .auth-stats{
            display:flex;gap:2.5rem;padding-top:2rem;
            border-top:1px solid var(--border);position:relative;z-index:1
        }
        .auth-stat-num{
            font-size:1.6rem;font-weight:900;color:var(--white);line-height:1
        }
        .auth-stat-num span{color:var(--blue)}
        .auth-stat-label{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#475569;margin-top:.3rem}

        .auth-left-footer{
            position:relative;z-index:1;
            display:flex;gap:1.5rem;font-size:.8rem;font-weight:600;color:#334155
        }
        .auth-left-footer a{color:#475569;text-decoration:none;transition:color .2s}
        .auth-left-footer a:hover{color:var(--white)}

        /* ── RIGHT PANEL ── */
        .auth-right{
            flex:1;display:flex;flex-direction:column;
            align-items:center;justify-content:center;
            padding:2rem;position:relative;
            background:linear-gradient(160deg,var(--dark) 0%,var(--dark3) 100%)
        }

        /* Top-right glow */
        .auth-right::before{
            content:'';position:absolute;top:-100px;right:-100px;
            width:400px;height:400px;border-radius:50%;
            background:radial-gradient(circle,rgba(99,102,241,.12) 0%,transparent 70%);
            pointer-events:none
        }
        .auth-right::after{
            content:'';position:absolute;bottom:-100px;left:-100px;
            width:350px;height:350px;border-radius:50%;
            background:radial-gradient(circle,rgba(59,130,246,.08) 0%,transparent 70%);
            pointer-events:none
        }

        /* Mobile logo */
        .auth-mobile-logo{
            display:none;align-items:center;gap:.6rem;
            margin-bottom:2rem;text-decoration:none
        }
        @media(max-width:1023px){.auth-mobile-logo{display:flex}}
        .auth-mobile-logo-icon{
            width:36px;height:36px;border-radius:10px;
            background:linear-gradient(135deg,var(--blue),var(--indigo));
            display:flex;align-items:center;justify-content:center;
            color:white;font-size:.95rem;
            box-shadow:0 0 16px rgba(59,130,246,.4)
        }
        .auth-mobile-logo-text{font-size:1.3rem;font-weight:900;color:var(--white)}
        .auth-mobile-logo-text span{color:var(--blue)}

        /* Form card */
        .auth-card{
            width:100%;max-width:440px;position:relative;z-index:1;
        }
        .auth-card-inner{
            background:rgba(14,30,53,.85);
            border:1px solid var(--border);
            border-radius:24px;padding:2.5rem;
            box-shadow:0 30px 80px rgba(0,0,0,.4),inset 0 1px 0 rgba(255,255,255,.05)
        }

        /* Input labels */
        .auth-label{
            display:block;font-size:.75rem;font-weight:700;
            letter-spacing:.1em;text-transform:uppercase;
            color:#64748b;margin-bottom:.45rem
        }
        /* Inputs override */
        .auth-input{
            display:block;width:100%;padding:.85rem 1.1rem;
            background:rgba(255,255,255,.04);
            border:1.5px solid rgba(59,130,246,.15);
            border-radius:12px;
            font-size:.95rem;font-weight:500;color:var(--white);
            font-family:'Outfit',sans-serif;
            outline:none;transition:all .25s
        }
        .auth-input::placeholder{color:#334155}
        .auth-input:focus{
            border-color:var(--blue);
            background:rgba(59,130,246,.07);
            box-shadow:0 0 0 3px rgba(59,130,246,.12)
        }
        .auth-input-wrap{position:relative}
        .auth-input-icon{
            position:absolute;left:1rem;top:50%;transform:translateY(-50%);
            color:#334155;font-size:.85rem;pointer-events:none;transition:color .25s
        }
        .auth-input-wrap:focus-within .auth-input-icon{color:var(--blue)}
        .auth-input-wrap .auth-input{padding-left:2.6rem}

        /* Toggle password */
        .pw-toggle{
            position:absolute;right:1rem;top:50%;transform:translateY(-50%);
            background:none;border:none;color:#334155;cursor:pointer;
            font-size:.85rem;transition:color .2s;padding:0
        }
        .pw-toggle:hover{color:var(--blue)}

        /* Submit button */
        .auth-btn{
            display:flex;align-items:center;justify-content:center;gap:.7rem;
            width:100%;padding:1rem;border-radius:12px;
            background:linear-gradient(135deg,var(--blue),var(--indigo));
            color:white;font-size:.95rem;font-weight:800;letter-spacing:.02em;
            border:none;cursor:pointer;
            box-shadow:0 8px 30px rgba(59,130,246,.35);
            transition:all .3s;font-family:'Outfit',sans-serif
        }
        .auth-btn:hover{transform:translateY(-2px);box-shadow:0 16px 40px rgba(59,130,246,.5)}
        .auth-btn:active{transform:translateY(0)}

        /* Divider */
        .auth-divider{
            display:flex;align-items:center;gap:1rem;margin:1.5rem 0
        }
        .auth-divider-line{flex:1;height:1px;background:var(--border)}
        .auth-divider-text{font-size:.75rem;font-weight:600;color:#334155;white-space:nowrap}

        /* Links */
        .auth-link{color:var(--blue);font-weight:700;text-decoration:none;transition:opacity .2s}
        .auth-link:hover{opacity:.8;text-decoration:underline}

        .auth-bottom{
            text-align:center;margin-top:1.5rem;
            font-size:.85rem;font-weight:500;color:#475569
        }

        /* Error messages */
        .auth-error{
            color:#f87171;font-size:.78rem;font-weight:600;margin-top:.4rem;display:flex;align-items:center;gap:.4rem
        }

        /* Progress steps */
        .auth-steps{
            display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem
        }
        .auth-step{
            width:28px;height:4px;border-radius:4px;
            background:rgba(59,130,246,.15);transition:background .3s
        }
        .auth-step.active{background:linear-gradient(90deg,var(--blue),var(--indigo))}
        .auth-step.done{background:rgba(34,197,94,.5)}
    </style>
</head>
<body>
    <!-- Loading -->
    <div class="page-loader" id="loader">
        <div style="text-align:center">
            <div class="spinner" style="margin:0 auto 1rem"></div>
            <p style="font-size:.8rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#334155">Mekong CyberUnit</p>
        </div>
    </div>

    <div class="auth-wrap">
        <!-- LEFT PANEL -->
        <div class="auth-left">
            <div class="auth-left-bg1"></div>
            <div class="auth-left-bg2"></div>
            <div class="auth-left-dots"></div>

            <!-- Logo -->
            <a href="/" class="auth-logo">
                <div class="auth-logo-icon"><i class="fa-solid fa-bolt-lightning"></i></div>
                <span class="auth-logo-text">Mekong<span>CyberUnit</span></span>
            </a>

            <!-- Body copy -->
            <div class="auth-left-body">
                <div class="auth-left-tag"><span></span>MODERN HR SYSTEM</div>
                <h2 class="auth-left-h">
                    Build a Team<br>
                    <span class="grad">You Love</span>
                </h2>
                <p class="auth-left-p">
                    Mekong CyberUnit helps you manage employees, attendance, and payroll in one smart, fast, and accurate system.
                </p>

                <div class="auth-features">
                    <div class="auth-feat">
                        <div class="auth-feat-icon blue"><i class="fa-solid fa-id-card-clip"></i></div>
                        <div class="auth-feat-text">
                            <strong>QR Code Attendance</strong>
                            <span>Instant clock-in/out with GPS</span>
                        </div>
                    </div>
                    <div class="auth-feat">
                        <div class="auth-feat-icon green"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                        <div class="auth-feat-text">
                            <strong>Automated Payroll</strong>
                            <span>Calculate, deduct, and send automatically</span>
                        </div>
                    </div>
                    <div class="auth-feat">
                        <div class="auth-feat-icon purple"><i class="fa-solid fa-chart-line"></i></div>
                        <div class="auth-feat-text">
                            <strong>Detailed Reports</strong>
                            <span>Clear charts and data, easy to use</span>
                        </div>
                    </div>
                </div>

                <div class="auth-stats" style="margin-top:2rem">
                    <div>
                        <div class="auth-stat-num">500<span>+</span></div>
                        <div class="auth-stat-label">Companies</div>
                    </div>
                    <div>
                        <div class="auth-stat-num">99.9<span>%</span></div>
                        <div class="auth-stat-label">Stability</div>
                    </div>
                    <div>
                        <div class="auth-stat-num">24<span>/7</span></div>
                        <div class="auth-stat-label">Support</div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="auth-left-footer">
                <span>&copy; {{ date('Y') }} Mekong CyberUnit</span>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="auth-right">
            <a href="/" class="auth-mobile-logo">
                <div class="auth-mobile-logo-icon"><i class="fa-solid fa-bolt-lightning"></i></div>
                <span class="auth-mobile-logo-text">Mekong<span>CyberUnit</span></span>
            </a>

            <div class="auth-card">
                <div class="auth-card-inner">
                    {{ $slot }}
                </div>
                <p class="auth-bottom">
                    Need help? <a href="#" class="auth-link">Contact Support</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('loader');
            if(loader){ loader.style.opacity='0'; setTimeout(()=>loader.remove(),600); }
        });
    </script>
</body>
</html>
