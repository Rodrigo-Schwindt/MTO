<div>
    <section class="equipment-banner">
        @if($pageData && $pageData->image_banner)
            <img src="{{ Storage::url($pageData->image_banner) }}" alt="Equipamiento industrial" class="equipment-banner-image">
        @else
            <div class="equipment-banner-fallback"></div>
        @endif

        <div class="equipment-banner-overlay"></div>

        <div class="equipment-banner-inner">
            <nav class="equipment-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <a wire:navigate href="{{ route('productos') }}">Equipamiento industrial</a>
                <span>&rsaquo;</span>
                <span>{{ $category->title }}</span>
            </nav>

            <h1>Equipamiento industrial</h1>
        </div>
    </section>

    <section class="equipment-list">
        <div class="equipment-cat-header">
            <h2 class="equipment-cat-title">{{ $category->title }}</h2>
        </div>
        <div class="equipment-grid">
            @forelse($products as $product)
                @php $cover = $product->mainImage ?: $product->images->first(); @endphp
                <a wire:navigate href="{{ route('productos.detalle', $product->slug) }}" class="equipment-card">
                    <span class="equipment-card-image">
                        @if($cover)
                            <img src="{{ Storage::url($cover->image) }}" alt="{{ $product->title }}" loading="lazy">
                        @endif
                    </span>
                    <span class="equipment-card-title">{{ $product->title }}</span>
                </a>
            @empty
                <p class="equipment-empty">No hay productos en esta categoría.</p>
            @endforelse
        </div>
    </section>

    <style>
        .equipment-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .equipment-banner-image,
        .equipment-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .equipment-banner-fallback {
            background: #143a1a;
        }

        .equipment-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%),
                linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%),
                linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%),
                linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .equipment-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .equipment-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .equipment-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .equipment-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .equipment-list {
            background: #fff;
            padding: 56px 0 116px;
        }

        .equipment-cat-header {
            max-width: 1224px;
            margin: 0 auto 36px;
        }

        .equipment-cat-title {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
        }

        .equipment-grid {
            max-width: 1224px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .equipment-card {
            border: 1px solid #E5E5E5;
            display: flex;
            flex-direction: column;
            color: inherit;
            text-decoration: none;
            transition: border-color .2s ease, transform .2s ease;
            overflow: hidden;
        }

        .equipment-card:hover {
            border-color: #52A028;
            transform: translateY(-2px);
        }

        .equipment-card-image {
            height: 200px;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .equipment-card-image img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
        }

        .equipment-card-title {
            padding-block: 8px;
            padding-inline: 24px;
            color: #737275;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.15;
        }

        .equipment-empty {
            grid-column: 1 / -1;
            margin: 0;
            text-align: center;
            color: #64748b;
            padding: 48px 0;
        }

        @media (max-width: 1199px) {
            .equipment-banner {
                height: 340px;
            }

            .equipment-banner-inner,
            .equipment-cat-header,
            .equipment-grid {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .equipment-breadcrumb {
                margin-bottom: 85px;
            }

            .equipment-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .equipment-card-image {
                height: 160px;
            }

            .equipment-card-image img {
                max-height: 160px;
            }

            .equipment-card-title {
                font-size: 18px;
                padding-inline: 18px;
            }
        }

        @media (max-width: 767px) {
            .equipment-banner {
                height: 290px;
            }

            .equipment-banner-inner,
            .equipment-cat-header,
            .equipment-grid {
                margin-left: 20px;
                margin-right: 20px;
            }

            .equipment-banner-inner {
                padding-top: 74px;
            }

            .equipment-breadcrumb {
                margin-bottom: 76px;
            }

            .equipment-banner h1 {
                font-size: 32px;
            }

            .equipment-list {
                padding: 40px 0 78px;
            }

            .equipment-cat-title {
                font-size: 22px;
            }

            .equipment-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .equipment-card-image {
                height: 130px;
            }

            .equipment-card-image img {
                max-height: 130px;
            }

            .equipment-card-title {
                font-size: 15px;
                padding-inline: 12px;
                padding-block: 10px;
            }
        }

        @keyframes eq-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .equipment-breadcrumb {
            animation: eq-fade-up 0.65s ease 0.1s both;
        }

        .equipment-banner h1 {
            animation: eq-fade-up 0.65s ease 0.25s both;
        }

        .equipment-card {
            opacity: 0;
            transition: border-color .2s ease, transform .2s ease, opacity 0.5s ease;
        }

        .equipment-card.is-visible {
            opacity: 1;
        }
    </style>

    <script>
    (function() {
        function equalizeGrid() {
            var grid = document.querySelector('.equipment-grid');
            if (!grid) return;
            grid.style.gridAutoRows = '';
            grid.offsetHeight;
            var max = 0;
            Array.from(grid.querySelectorAll('.equipment-card')).forEach(function(c) {
                if (c.offsetHeight > max) max = c.offsetHeight;
            });
            if (max > 0) grid.style.gridAutoRows = Math.max(max, 300) + 'px';
        }

        function initReveal() {
            var cards = Array.prototype.slice.call(document.querySelectorAll('.equipment-card'));
            if (!cards.length) return;

            cards.forEach(function(c) { c.classList.remove('is-visible'); });

            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    var index = cards.indexOf(entry.target);
                    setTimeout(function() {
                        entry.target.classList.add('is-visible');
                    }, (index % 4) * 70);
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.05 });

            cards.forEach(function(card) { observer.observe(card); });
            equalizeGrid();
        }

        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(equalizeGrid, 150);
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initReveal);
        } else {
            initReveal();
        }

        document.addEventListener('livewire:navigated', initReveal);
    })();
    </script>
</div>
