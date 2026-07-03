<div>
    <section class="sectors-banner">
        @if($pageData && $pageData->image_banner)
            <img src="{{ Storage::url($pageData->image_banner) }}" alt="Sectores" class="sectors-banner-image">
        @else
            <div class="sectors-banner-fallback"></div>
        @endif

        <div class="sectors-banner-overlay"></div>

        <div class="sectors-banner-inner">
            <nav class="sectors-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <span>Sectores</span>
            </nav>

            <h1>Sectores</h1>
        </div>
    </section>

    <section class="sectors-list">
        <div class="sectors-grid">
            @forelse($sectors as $sector)
                @if($sector->slug)
                    <a wire:navigate href="{{ route('sectores.detalle', $sector->slug) }}" class="sector-card sector-card--link">
                        <img src="{{ Storage::url($sector->image) }}" alt="{{ $sector->title }}" loading="lazy">
                        <div class="sector-card-shade"></div>
                        <h2>{{ $sector->title }}</h2>
                    </a>
                @else
                    <article class="sector-card">
                        <img src="{{ Storage::url($sector->image) }}" alt="{{ $sector->title }}" loading="lazy">
                        <div class="sector-card-shade"></div>
                        <h2>{{ $sector->title }}</h2>
                    </article>
                @endif
            @empty
                <p class="sectors-empty">No hay sectores disponibles.</p>
            @endforelse
        </div>
    </section>

    <style>
        .sectors-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .sectors-banner-image,
        .sectors-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sectors-banner-fallback {
            background: #143a1a;
        }

        .sectors-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%),
                linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%),
                linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%),
                linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .sectors-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .sectors-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .sectors-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .sectors-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .sectors-list {
            background: #fff;
            padding: 76px 0 112px;
        }

        .sectors-grid {
            max-width: 1224px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .sector-card {
            position: relative;
            height: 266px;
            overflow: hidden;
            margin: 0;
            background: #d9d9d9;
        }

        .sector-card--link {
            display: block;
            text-decoration: none;
            cursor: pointer;
        }

        .sector-card img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            filter: grayscale(100%);
            transition: filter .3s ease, transform .3s ease;
        }

        .sector-card:hover img {
            filter: grayscale(0%);
            transform: scale(1.03);
        }

        .sector-card-shade {
            position: absolute;
            inset: auto 0 0;
            height: 46%;
            background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,.46) 100%);
            pointer-events: none;
        }

        .sector-card h2 {
            position: absolute;
            left: 24px;
            bottom: 24px;
            z-index: 2;
            margin: 0;
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            line-height: 1.1;
        }

        .sectors-empty {
            grid-column: 1 / -1;
            margin: 0;
            text-align: center;
            color: #64748b;
            padding: 48px 0;
        }

        @media (max-width: 1199px) {
            .sectors-banner {
                height: 340px;
            }

            .sectors-banner-inner,
            .sectors-grid {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .sectors-breadcrumb {
                margin-bottom: 85px;
            }

            .sectors-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .sectors-banner {
                height: 290px;
            }

            .sectors-banner-inner,
            .sectors-grid {
                margin-left: 20px;
                margin-right: 20px;
            }

            .sectors-banner-inner {
                padding-top: 74px;
            }

            .sectors-breadcrumb {
                margin-bottom: 76px;
            }

            .sectors-banner h1 {
                font-size: 32px;
            }

            .sectors-list {
                padding: 52px 0 78px;
            }

            .sectors-grid {
                grid-template-columns: 1fr;
            }

            .sector-card {
                height: 236px;
            }

            .sector-card img {
                filter: grayscale(0%);
            }
        }

        @keyframes sec-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .sectors-breadcrumb {
            animation: sec-fade-up 0.65s ease 0.1s both;
        }

        .sectors-banner h1 {
            animation: sec-fade-up 0.65s ease 0.25s both;
        }

        .sector-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.55s ease, transform 0.55s ease;
        }

        .sector-card.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
    (function() {
        function initReveal() {
            var cards = Array.prototype.slice.call(document.querySelectorAll('.sector-card'));
            if (!cards.length) return;

            cards.forEach(function(c) { c.classList.remove('is-visible'); });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    var index = cards.indexOf(entry.target);
                    setTimeout(function() {
                        entry.target.classList.add('is-visible');
                    }, (index % 3) * 80);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.05 });

            cards.forEach(function(card) { observer.observe(card); });
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
