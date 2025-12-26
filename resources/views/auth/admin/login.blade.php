<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{fileExist(['url'=>@$site_setting->favicon,'type'=>'favicon'])}}" type="image/x-icon">
    <link rel="icon" href="{{fileExist(['url'=>@$site_setting->favicon,'type'=>'favicon'])}}" type="image/x-icon">
    <title>{{(@$site_setting->title_suffix)?(@$site_setting->title_suffix):'Election Survey System'}} | {{ @$title ?? 'Admin' }} Login</title>
    <style>
        :root{
            --primary:#D71920;
            --green:#1F7A3A;
            --charcoal:#0B0B0B;
            --gold:#F5C400;
            --muted:#6B7280;
            --bg:#FFFFFF;
            --card:#FFFFFF;
            --border:#E5E7EB;
            --radius:12px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{height:100%;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;color:var(--charcoal);background-color:var(--bg);background-image:
            radial-gradient(circle at 15% 18%, rgba(31,122,58,0.16) 0%, rgba(31,122,58,0) 56%),
            radial-gradient(circle at 84% 22%, rgba(215,25,32,0.15) 0%, rgba(215,25,32,0) 58%),
            radial-gradient(circle at 70% 88%, rgba(245,196,0,0.12) 0%, rgba(245,196,0,0) 60%),
            linear-gradient(135deg, rgba(255,255,255,0.78) 0%, rgba(255,255,255,0.90) 100%),
            url('{{ asset('common/images/y.jpg') }}');
            background-size:auto, auto, auto, auto, cover;background-position:center, center, center, center, center;background-repeat:no-repeat;background-attachment:fixed}
        body::before{content:"";position:fixed;inset:0;background:linear-gradient(90deg, rgba(31,122,58,0.05) 0%, rgba(245,196,0,0.03) 50%, rgba(215,25,32,0.05) 100%);pointer-events:none;opacity:0.7}
        .page-root{position:relative;display:flex;min-height:100vh;align-items:center;justify-content:flex-end;padding:36px}
        .split{display:flex;width:100%;max-width:1160px;align-items:center;justify-content:flex-end}
        .panel{display:flex}
        .panel-form{align-items:center;justify-content:flex-end;width:100%}
        .card{background:rgba(255,255,255,0.88);padding:28px;border-radius:var(--radius);border:2px solid rgba(240,240,240,0.85);box-shadow:0 12px 32px rgba(0,0,0,0.10);width:100%;max-width:460px}
        .card-topbar{height:5px;border-radius:999px;background:linear-gradient(90deg, var(--green) 0%, var(--gold) 55%, var(--primary) 100%);margin-bottom:18px;box-shadow:0 2px 8px rgba(31,122,58,0.22)}
        .card-brand{display:flex;align-items:center;gap:10px;margin-bottom:10px}
        .card-logo{width:40px;height:40px;object-fit:contain}
        .card-system{font-weight:800;letter-spacing:0.8px;font-size:12.5px;text-transform:uppercase}
        .muted{color:var(--muted);font-size:13px}
        .small{font-size:12.5px}
        .card-header h2{margin:10px 0 4px;font-size:20px;line-height:1.2}
        .card-header p{margin:0}
        .divider{height:1px;background:var(--border);margin:14px 0}
        .form-row{margin:12px 0}
        .form-row label{display:block;font-weight:650;margin-bottom:6px;font-size:13px}
        .form-row input[type=text],.form-row input[type=password],.form-row select{width:100%;padding:11px 12px;border:1px solid #d7dde3;border-radius:8px;background:#fff;transition:border-color .15s ease, box-shadow .15s ease;font-size:14px}
        .form-row input::placeholder{color:#9aa4af}
        .password-wrap{display:flex;gap:10px}
        .password-wrap input{flex:1}
        .toggle-pass{background:#fff;border:1px solid #d7dde3;padding:10px 12px;border-radius:8px;cursor:pointer;font-weight:650;color:var(--charcoal);font-size:13px}
        .inline{display:flex;align-items:center;gap:12px;justify-content:space-between}
        .checkbox{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--charcoal);cursor:pointer}
        .checkbox input{width:16px;height:16px;cursor:pointer}
        .btn-primary{display:flex;align-items:center;justify-content:center;gap:10px;background:var(--primary);color:#fff;padding:12px 18px;border-radius:10px;border:none;font-weight:800;cursor:pointer;width:100%;box-shadow:0 4px 14px rgba(215,25,32,0.28);transition:transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;font-size:15px}
        .btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(215,25,32,0.35);filter:brightness(0.97)}
        .icon{width:16px;height:16px;display:inline-block}
        .badge{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg, rgba(31,122,58,0.08), rgba(31,122,58,0.12));border:1px solid rgba(31,122,58,0.22);padding:7px 11px;border-radius:999px;color:var(--green);font-size:12.5px;font-weight:600}
        .form-footer{display:flex;justify-content:space-between;margin-top:14px}
        .form-footer a{color:var(--muted);text-decoration:none;font-size:13px}
        .form-footer a:hover{text-decoration:underline}
        .card-footer{margin-top:14px}
        .meta{margin-top:12px;display:flex;gap:10px;color:var(--muted);font-size:12.5px;justify-content:flex-end}
        .error{color:var(--primary);font-size:13px;margin-top:6px;min-height:18px}
        input:focus,select:focus,button:focus{outline:3px solid rgba(31,122,58,0.16);outline-offset:2px}
        .form-row input:focus,.form-row select:focus{border-color:var(--green);box-shadow:0 0 0 4px rgba(31,122,58,0.12)}
        @media (max-width:980px){.page-root{justify-content:center}.split{justify-content:center}.panel-form{justify-content:center}}
        @media (max-width:520px){.page-root{padding:18px}.card{padding:18px}}
    </style>
</head>
<body>
<main class="page-root" role="main">
    <div class="split">
        <section class="panel panel-form" aria-labelledby="login-heading">
            <div class="card" role="form" aria-describedby="auth-help">
                <div class="card-topbar" aria-hidden="true"></div>
                <header class="card-header">
                    <div class="card-brand">
                        <img src="{{ fileExist(['url'=>@$site_setting->favicon,'type'=>'favicon']) }}" alt="" class="card-logo" onerror="this.style.display='none'">
                        <div>
                            <div class="card-system">{{ strtoupper(@$site_setting->title_suffix ?? 'Election Survey System') }}</div>
                            <div class="muted small">Secure • Neutral • Data‑Driven Electoral Insights</div>
                        </div>
                    </div>
                    <h2 id="login-heading">{{ @$title ?? 'Admin' }} Sign in</h2>
                    <p id="auth-help" class="muted">Authorized access only. All activities are logged and monitored.</p>
                </header>

                <div class="divider" aria-hidden="true"></div>

                <form id="loginForm" method="post" action="{{ $url }}" novalidate>
                    {{ csrf_field() }}

                    @if(session()->has('login_error'))
                        <div class="error" role="alert">{{ session()->get('login_error') }}</div>
                    @endif
                    @if(session()->has('login_success'))
                        <div class="muted" role="status">{{ session()->get('login_success') }}</div>
                    @endif

                    <div class="form-row">
                        <label for="user">Email or Mobile</label>
                        <input id="user" name="user" type="text" value="{{ old('user') }}" autocomplete="username" required>
                        @if ($errors->has('user'))
                            <div class="error">{{ $errors->first('user') }}</div>
                        @else
                            <div class="error" data-for="user" aria-live="polite"></div>
                        @endif
                    </div>

                    <div class="form-row">
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input id="password" name="password" type="password" autocomplete="current-password" required>
                            <button type="button" class="toggle-pass" aria-label="Show password">Show</button>
                        </div>
                        @if ($errors->has('password'))
                            <div class="error">{{ $errors->first('password') }}</div>
                        @else
                            <div class="error" data-for="password" aria-live="polite"></div>
                        @endif
                    </div>

                 

                    <div class="form-row inline">
                        <label class="checkbox"><input id="remember" type="checkbox" name="remember"> Remember me</label>
                        <div class="badge" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 2l7 4v6c0 5-3 9-7 10C8 21 5 17 5 12V6l7-4z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M9.5 12.5l1.7 1.7 3.8-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            SSL Secured
                        </div>
                    </div>

                    {{-- <div class="form-row">
                        <label class="checkbox"><input id="notrobot" type="checkbox" name="notrobot"> I'm not a robot</label>
                    </div> --}}

                    <div class="form-row">
                        <button class="btn-primary" type="submit">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M7 11V8.5a5 5 0 0110 0V11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <rect x="6" y="11" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/>
                            </svg>
                            Sign In
                        </button>
                    </div>

                    <div class="form-footer">
                        <a href="{{ route('admin.forget.password.get') }}">Forgot Password?</a>
                        <a href="{{ url('/') }}">Home</a>
                    </div>
                </form>

                <footer class="card-footer muted small">
                <span>© {{ date('Y') }} {{ @$site_setting->title_suffix ?? 'Commission for Electoral Integrity' }}</span>
                <span>v1.0.0</span>
            </footer>
            </div>
           
        </section>
    </div>
</main>

<script>
(function(){
    var toggle = document.querySelector('.toggle-pass');
    var password = document.getElementById('password');
    if(toggle && password){
        toggle.addEventListener('click', function(){
            if(password.type==='password'){ password.type='text'; toggle.textContent='Hide'; toggle.setAttribute('aria-label','Hide password') }
            else { password.type='password'; toggle.textContent='Show'; toggle.setAttribute('aria-label','Show password') }
        });
    }
    var form = document.getElementById('loginForm');
    if(form){
        form.addEventListener('submit', function(e){
            var valid = true;
            var user = document.getElementById('user');
            var pass = document.getElementById('password');
            var notrobot = document.getElementById('notrobot');
            document.querySelectorAll('.error').forEach(function(el){ if(el.dataset.for) el.textContent='' });
            if(!user.value.trim()){ var uerr = document.querySelector('.error[data-for="user"]'); if(uerr) uerr.textContent='Please enter email or mobile.'; valid=false }
            if(!pass.value){ var perr = document.querySelector('.error[data-for="password"]'); if(perr) perr.textContent='Please enter your password.'; valid=false }
            if(notrobot && !notrobot.checked){ var rerr = document.querySelector('.error[data-for="user"]'); if(rerr) rerr.textContent='Please confirm you are not a robot.'; valid=false }
            if(!valid){ e.preventDefault(); user.focus(); }
        });
    }
})();
</script>
</body>
</html>
