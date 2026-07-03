<div
    x-data="{ _lastPerPage: null }"
    x-init="
        var sync = () => {
            var p = window.innerWidth < 768 ? 8 : 16;
            if (p === _lastPerPage) return;
            _lastPerPage = p;
            $wire.setPerPage(p);
        };
        sync();
        var t = null;
        window.addEventListener('resize', () => { clearTimeout(t); t = setTimeout(sync, 200); });
    "
>
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
                <span>Equipamiento industrial</span>
            </nav>

            <h1>Equipamiento industrial</h1>
        </div>
    </section>

    <section class="equipment-list">

        <div class="equipment-search-wrap">
            <input
                type="text"
                wire:model.live.debounce.350ms="search"
                placeholder="Buscar equipamiento"
                class="equipment-search-input"
            >
            <span class="equipment-search-btn" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </span>
        </div>

        <div class="equipment-grid"
             wire:loading.class.delay="eq-grid-loading"
             wire:target="search, setPerPage, previousPage, nextPage, gotoPage">
            @forelse($items as $entry)
                @if($entry['type'] === 'category')
                    @php $cat = $entry['item']; @endphp
                    <a wire:navigate href="{{ route('productos.categoria', $cat->slug) }}" class="equipment-card" style="animation-delay: {{ ($loop->index % 4) * 70 }}ms">
                        <span class="equipment-card-image">
                            @if($cat->imagen)
                                <img src="{{ Storage::url($cat->imagen) }}" alt="{{ $cat->title }}" loading="lazy">
                            @endif
                        </span>
                        <span class="equipment-card-title">{{ $cat->title }}</span>
                        <svg class="equipment-cat-arrow" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="#52A028" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @else
                    @php $product = $entry['item']; $cover = $product->mainImage ?: $product->images->first(); @endphp
                    <a wire:navigate href="{{ route('productos.detalle', $product->slug) }}" class="equipment-card" style="animation-delay: {{ ($loop->index % 4) * 70 }}ms">
                        <span class="equipment-card-image">
                            @if($cover)
                                <img src="{{ Storage::url($cover->image) }}" alt="{{ $product->title }}" loading="lazy">
                            @endif
                        </span>
                        <span class="equipment-card-title">{{ $product->title }}</span>
                    </a>
                @endif
            @empty
                <p class="equipment-empty">
                    {{ $search ? 'No se encontraron resultados para "' . $search . '".' : 'No hay productos disponibles.' }}
                </p>
            @endforelse
        </div>

        @if($items->hasPages())
            <div class="equipment-pagination">
                @if($items->onFirstPage())
                    <span class="eq-page-arrow disabled">&#8249;</span>
                @else
                    <button wire:click="previousPage" class="eq-page-arrow">&#8249;</button>
                @endif

                @foreach(range(1, $items->lastPage()) as $p)
                    @if($p === $items->currentPage())
                        <span class="eq-page-num active">{{ $p }}</span>
                    @elseif($p === 1 || $p === $items->lastPage() || abs($p - $items->currentPage()) <= 2)
                        <button wire:click="gotoPage({{ $p }})" class="eq-page-num">{{ $p }}</button>
                    @elseif(abs($p - $items->currentPage()) === 3)
                        <span class="eq-page-ellipsis">…</span>
                    @endif
                @endforeach

                @if($items->hasMorePages())
                    <button wire:click="nextPage" class="eq-page-arrow">&#8250;</button>
                @else
                    <span class="eq-page-arrow disabled">&#8250;</span>
                @endif
            </div>
        @endif


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
            padding: 76px 0 116px;
        }

        /* Search */
        .equipment-search-wrap {
            max-width: 1224px;
            margin: 0 auto 24px;
            display: flex;
            align-items: stretch;
            height: 38px;
            border: 1.5px solid #D1D5DB;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .equipment-search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0 16px;
            font-size: 15px;
            color: #1a1a1a;
            background: transparent;
            height: 100%;
        }

        .equipment-search-input::placeholder {
            color: #9CA3AF;
        }

        .equipment-search-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            flex-shrink: 0;
            background: #52A028;
            color: #fff;
        }

        /* Grid */
        .equipment-grid {
            max-width: 1224px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }

        .equipment-card {
            position: relative;
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

        .equipment-cat-arrow {
            position: absolute;
            right: 18px;
            bottom: 18px;
            transition: transform .2s ease;
        }

        .equipment-card:hover .equipment-cat-arrow {
            transform: translateX(3px);
        }

        .equipment-empty {
            grid-column: 1 / -1;
            margin: 0;
            text-align: center;
            color: #64748b;
            padding: 48px 0;
        }

        /* Pagination */
        .equipment-pagination {
            max-width: 1224px;
            margin: 56px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .eq-page-arrow,
        .eq-page-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding-inline: 6px;
            border: 1.5px solid #E5E5E5;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 600;
            color: #444;
            background: #fff;
            cursor: pointer;
            transition: border-color .15s, background .15s, color .15s;
            line-height: 1;
        }

        .eq-page-arrow { font-size: 20px; }

        .eq-page-arrow:hover:not(.disabled),
        .eq-page-num:hover:not(.active) {
            border-color: #52A028;
            color: #52A028;
        }

        .eq-page-num.active {
            background: #52A028;
            border-color: #52A028;
            color: #fff;
            cursor: default;
        }

        .eq-page-arrow.disabled {
            opacity: .35;
            cursor: default;
        }

        .eq-page-ellipsis {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            color: #9CA3AF;
            font-size: 15px;
        }

        @media (max-width: 1199px) {
            .equipment-banner {
                height: 340px;
            }

            .equipment-banner-inner,
            .equipment-search-wrap,
            .equipment-grid,
            .equipment-pagination {
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
            .equipment-search-wrap,
            .equipment-grid,
            .equipment-pagination {
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
                padding: 52px 0 78px;
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

            .equipment-cat-arrow {
                width: 18px;
                height: 18px;
            }

            .equipment-search-wrap {
                margin-bottom: 28px;
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
            animation: eq-card-in 0.45s ease both;
        }

        @keyframes eq-card-in {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .equipment-grid {
            transition: opacity 0.2s ease;
        }

        .eq-grid-loading {
            opacity: 0.2;
            pointer-events: none;
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

        function replayCards() {
            var cards = Array.from(document.querySelectorAll('.equipment-grid .equipment-card'));
            if (!cards.length) return;
            cards.forEach(function(card, i) {
                card.style.animation = 'none';
                card.offsetHeight;
                card.style.animation = 'eq-card-in 0.4s ease both';
                card.style.animationDelay = (i % 4 * 60) + 'ms';
            });
            equalizeGrid();
        }

        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(equalizeGrid, 150);
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', equalizeGrid);
        } else {
            equalizeGrid();
        }

        document.addEventListener('livewire:navigated', equalizeGrid);

        document.addEventListener('livewire:initialized', function() {
            equalizeGrid();
            Livewire.hook('commit', function({ succeed }) {
                succeed(function() {
                    replayCards();
                });
            });
        });
    })();
    </script>

</div>
