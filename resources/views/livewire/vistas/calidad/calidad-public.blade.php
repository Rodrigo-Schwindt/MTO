@php
    $title = $pageData?->title ?: 'Politicas de calidad';
    $description = str_replace('&nbsp;', ' ', (string) ($pageData?->description ?? ''));
@endphp

<div>
    <section class="quality-banner">
        @if($pageData && $pageData->image_banner)
            <img src="{{ Storage::url($pageData->image_banner) }}" alt="Calidad" class="quality-banner-image">
        @else
            <div class="quality-banner-fallback"></div>
        @endif

        <div class="quality-banner-overlay"></div>

        <div class="quality-banner-inner">
            <nav class="quality-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <span>Calidad</span>
            </nav>

            <h1>Calidad</h1>
        </div>
    </section>

    <section class="quality-section">
        <div class="quality-grid">
            <div class="quality-content">
                <h2>{{ $title }}</h2>

                <div class="quality-description">
                    {!! $description !!}
                </div>

                @if($downloads->isNotEmpty())
                    <div class="quality-downloads">
                        <h3>Descargas</h3>

                        <div class="quality-download-list">
                            @foreach($downloads as $download)
                                @php
                                    $extension = strtoupper(pathinfo($download->original_name ?: $download->file, PATHINFO_EXTENSION));
                                    $sizeKb = $download->size ? max(1, round($download->size / 1024)) : null;
                                    $downloadMeta = collect([
                                        $sizeKb ? "{$sizeKb}kb" : null,
                                        $extension ?: null,
                                    ])->filter()->implode(' - ');
                                @endphp
                                <a href="{{ Storage::url($download->file) }}" target="_blank" class="quality-download-item">
                                    <span class="quality-download-icon" aria-hidden="true">
                                        @if($download->image)
                                            <img src="{{ Storage::url($download->image) }}" alt="{{ $download->title }}">
                                        @else
                                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                                <circle cx="24" cy="24" r="21" stroke="#2D8FD6" stroke-width="2"/>
                                                <text x="24" y="29" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" font-weight="700" fill="#2D8FD6">ISO</text>
                                            </svg>
                                        @endif
                                    </span>
                                    <span class="quality-download-text">
                                        <strong>{{ $download->title }}</strong>
                                        <small>{{ $downloadMeta }}</small>
                                    </span>
                                    <span class="quality-download-action" aria-hidden="true">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M14 2V6C14 6.53043 14.2107 7.03914 14.5858 7.41421C14.9609 7.78929 15.4696 8 16 8H20M12 18V12M15 15L12 18L9 15M15 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V7L15 2Z" stroke="#52A028" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="quality-image-wrap">
                @if($pageData && $pageData->image)
                    <img src="{{ Storage::url($pageData->image) }}" alt="{{ $title }}">
                @endif
            </div>
        </div>
    </section>

    <style>
        .quality-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .quality-banner-image,
        .quality-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .quality-banner-fallback {
            background: #143a1a;
        }

        .quality-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%),
                linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%),
                linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%),
                linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .quality-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .quality-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .quality-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .quality-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .quality-section {
            background: #fff;
            padding: 78px 0 112px;
        }

        .quality-grid {
            max-width: 1224px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 72px;
            align-items: start;
        }

        .quality-content h2 {
            margin: 18px 0 28px;
            color: #404041;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.15;
        }

        .quality-description {
            color: #1f1f1f;
            font-size: 16px;
            line-height: 1.55;
        }

        .quality-description p {
            margin: 0 0 24px;
        }

        .quality-description strong,
        .quality-description b {
            font-weight: 700;
        }

        .quality-downloads {
            margin-top: 88px;
        }

        .quality-downloads h3 {
            margin: 0 0 32px;
            color: #404041;
            font-size: 24px;
            font-weight: 700;
        }

        .quality-download-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quality-download-item {
            min-height: 60px;
            display: grid;
            grid-template-columns: 60px minmax(0, 1fr) 34px;
            align-items: center;
            gap: 14px;
            padding: 0 12px 0 0;
            background: #D1D2D426;
            border: 1px solid #D1D2D280;
            border-radius: 3px;
            overflow: hidden;
            cursor: pointer;
            color: inherit;
            text-decoration: none;
            transition: background .2s ease;
        }

        .quality-download-item:hover {
            background: #ededed;
        }

        .quality-download-icon {
            width: 60px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            align-self: stretch;
        }

        .quality-download-icon img {
            width: 100%;
            height: 100%;
            background: #fff;
            object-fit: contain;
            display: block;
        }

        .quality-download-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .quality-download-text strong {
            color: #404041;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.1;
        }

        .quality-download-text small {
            margin-top: 3px;
            color: #1f1f1f;
            font-size: 12px;
            line-height: 1.1;
        }

        .quality-download-action {
            color: #52A028;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quality-image-wrap {
            width: 100%;
            height: 614px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .quality-image-wrap img {
            width: 100%;
            height: 100%;
            min-height: 560px;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 1199px) {
            .quality-banner {
                height: 340px;
            }

            .quality-banner-inner,
            .quality-grid {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .quality-breadcrumb {
                margin-bottom: 85px;
            }

            .quality-grid {
                gap: 40px;
            }
        }

        @media (max-width: 767px) {
            .quality-banner {
                height: 290px;
            }

            .quality-banner-inner,
            .quality-grid {
                margin-left: 20px;
                margin-right: 20px;
            }

            .quality-banner-inner {
                padding-top: 74px;
            }

            .quality-breadcrumb {
                margin-bottom: 76px;
            }

            .quality-banner h1 {
                font-size: 32px;
            }

            .quality-section {
                padding: 52px 0 78px;
            }

            .quality-grid {
                grid-template-columns: 1fr;
            }

            .quality-content h2 {
                font-size: 28px;
                margin-top: 0;
            }

            .quality-downloads {
                margin-top: 48px;
            }

            .quality-image-wrap,
            .quality-image-wrap img {
                min-height: 360px;
            }
        }

        @keyframes qual-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .quality-breadcrumb {
            animation: qual-fade-up 0.65s ease 0.1s both;
        }

        .quality-banner h1 {
            animation: qual-fade-up 0.65s ease 0.25s both;
        }

        .quality-content {
            animation: qual-fade-up 0.7s ease 0.15s both;
        }

        .quality-image-wrap {
            animation: qual-fade-up 0.7s ease 0.3s both;
        }

        .quality-download-item {
            opacity: 0;
            transition: background .2s ease, opacity 0.5s ease;
        }

        .quality-download-item.is-visible {
            opacity: 1;
        }
    </style>

    <script>
    (function() {
        function initReveal() {
            var items = Array.prototype.slice.call(document.querySelectorAll('.quality-download-item'));
            if (!items.length) return;

            items.forEach(function(el) { el.classList.remove('is-visible'); });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    var index = items.indexOf(entry.target);
                    setTimeout(function() {
                        entry.target.classList.add('is-visible');
                    }, index * 80);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.05 });

            items.forEach(function(el) { observer.observe(el); });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initReveal);
        } else {
            initReveal();
        }

        document.addEventListener('livewire:navigated', initReveal);
    })();
    </script>
</div>
