@php
    use App\Models\Contact;
    $contact = Contact::first();
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso — Panel Administrativo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            font-family: system-ui, -apple-system, sans-serif;
            background: #ffffff;
        }

        .bg-grid { display: none; }

        /* Card */
        .card {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.35), 0 0 0 1px rgba(255,255,255,0.08);
            overflow: hidden;
            animation: cardIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Card header */
        .card-header {
            padding: 10px 36px 8px;
            background: linear-gradient(135deg, #52A028 0%, #6cb832 60%, #B4CB19 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
        }

        .logo-wrap {
            width: 260px; height: 140px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-wrap img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .logo-fallback {
            width: 72px; height: 72px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .logo-fallback svg { width: 36px; height: 36px; color: #fff; }

        /* Form area */
        .card-body { padding: 28px 36px 32px; }

        /* Error */
        .error-box {
            display: flex; align-items: flex-start; gap: 10px;
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 8px; padding: 12px 14px;
            color: #dc2626; font-size: 0.82rem; margin-bottom: 20px;
        }
        .error-box svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

        /* Field */
        .field { margin-bottom: 18px; }
        .field label {
            display: block; font-size: 0.82rem; font-weight: 600;
            color: #374151; margin-bottom: 6px; letter-spacing: 0.01em;
        }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: #9ca3af; pointer-events: none;
        }
        .input-wrap input {
            width: 100%; border: 1px solid #d1d5db; border-radius: 9px;
            padding: 10px 12px 10px 38px;
            font-size: 0.875rem; color: #111827; background: #f9fafb;
            outline: none; transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .input-wrap input:focus {
            border-color: #52A028; background: #fff;
            box-shadow: 0 0 0 3px rgba(82,160,40,0.14);
        }
        .input-wrap input::placeholder { color: #9ca3af; }

        /* Toggle password */
        .toggle-pass {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #9ca3af; padding: 2px;
            display: flex; align-items: center;
            transition: color 0.15s;
        }
        .toggle-pass:hover { color: #52A028; }
        .toggle-pass svg { width: 16px; height: 16px; }
        .input-wrap input[type="password"] { padding-right: 40px; }

        /* Remember row */
        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 22px;
        }
        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px; accent-color: #52A028; cursor: pointer; flex-shrink: 0;
        }
        .remember-row label {
            font-size: 0.82rem; color: #6b7280; cursor: pointer; user-select: none;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 11px 18px;
            background: linear-gradient(135deg, #52A028 0%, #65b530 100%);
            color: #fff;
            border: none; border-radius: 9px;
            font-size: 0.9rem; font-weight: 700;
            letter-spacing: 0.01em; cursor: pointer;
            transition: opacity 0.15s, transform 0.1s, box-shadow 0.15s;
            box-shadow: 0 4px 14px rgba(82,160,40,0.35);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover { opacity: 0.92; box-shadow: 0 6px 20px rgba(82,160,40,0.45); }
        .btn-submit:active { transform: scale(0.98); }
        .btn-submit svg { width: 16px; height: 16px; }

        /* Footer */
        .card-footer {
            padding: 14px 36px;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
            text-align: center;
        }
        .card-footer a {
            font-size: 0.78rem; color: #9ca3af;
            text-decoration: none; transition: color 0.15s;
        }
        .card-footer a:hover { color: #52A028; }

        /* Responsive */
        @media (max-width: 480px) {
            .card-header, .card-body, .card-footer { padding-left: 24px; padding-right: 24px; }
        }
    </style>
</head>
<body>
    <div class="bg-grid" aria-hidden="true"></div>

    <div class="card">
        <!-- Header / Branding -->
        <div class="card-header">
            <div class="logo-wrap">
                @if($contact && $contact->icono_2)
                    <img src="{{ Storage::url($contact->icono_2) }}" alt="Logo">
                @elseif($contact && $contact->icono_1)
                    <img src="{{ Storage::url($contact->icono_1) }}" alt="Logo">
                @else
                    <div class="logo-fallback">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form -->
        <div class="card-body">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                @if($errors->any())
                    <div class="error-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}"
                               placeholder="admin@empresa.com"
                               required autofocus autocomplete="email">
                    </div>
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input id="password" name="password" type="password"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                        <button type="button" class="toggle-pass" id="togglePassword" title="Mostrar/ocultar contraseña" aria-label="Mostrar contraseña">
                            <svg id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">Recordarme en este dispositivo</label>
                </div>

                <button type="submit" class="btn-submit">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Ingresar al panel
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <a href="{{ url('/') }}">← Volver al sitio público</a>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const passInput = document.getElementById('password');

        toggle?.addEventListener('click', function() {
            const isText = passInput.type === 'text';
            passInput.type = isText ? 'password' : 'text';
            document.getElementById('eyeIcon').innerHTML = isText
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        });
    </script>
</body>
</html>
