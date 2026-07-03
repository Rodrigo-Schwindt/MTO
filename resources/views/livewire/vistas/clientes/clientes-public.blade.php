<div
    x-data="{
        activeCategory: 'all',
        isChanging: false,
        selectCategory(category) {
            if (this.activeCategory === category || this.isChanging) return;

            this.isChanging = true;

            setTimeout(() => {
                this.activeCategory = category;
                this.$nextTick(() => {
                    setTimeout(() => this.isChanging = false, 40);
                });
            }, 140);
        }
    }"
    x-init="$nextTick(() => activeCategory = 'all')"
>
    <section class="clients-banner">
        @if($pageData && $pageData->image_banner)
            <img src="{{ Storage::url($pageData->image_banner) }}" alt="Clientes" class="clients-banner-image">
        @else
            <div class="clients-banner-fallback"></div>
        @endif

        <div class="clients-banner-overlay"></div>

        <div class="clients-banner-inner">
            <nav class="clients-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <span>Clientes</span>
            </nav>

            <h1>Nuestros clientes</h1>
        </div>
    </section>

    <section class="clients-section">
        <div class="clients-inner">
            <div class="clients-tabs" role="tablist" aria-label="Categorias de clientes">
                <button type="button"
                        class="clients-tab"
                        :class="{ 'is-active': activeCategory === 'all' }"
                        @click="selectCategory('all')">
                    Todos
                </button>

                @foreach($categories as $category)
                    <button type="button"
                            class="clients-tab"
                            :class="{ 'is-active': activeCategory === '{{ $category->id }}' }"
                            @click="selectCategory('{{ $category->id }}')">
                        {{ $category->title }}
                    </button>
                @endforeach
            </div>

            <div class="clients-grid" :class="{ 'is-changing': isChanging }">
                @forelse($brands as $brand)
                    <div class="clients-brand-card"
                         x-show="activeCategory === 'all' || activeCategory === '{{ $brand->brand_category_id }}'">
                        <img src="{{ Storage::url($brand->image) }}" alt="Cliente" loading="lazy">
                    </div>
                @empty
                    <p class="clients-empty">No hay clientes cargados.</p>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .clients-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .clients-banner-image,
        .clients-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .clients-banner-fallback {
            background: #143a1a;
        }

        .clients-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%),
                linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%),
                linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%),
                linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .clients-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .clients-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .clients-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .clients-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .clients-section {
            background: #fff;
            padding: 96px 0 116px;
        }

        .clients-inner {
            max-width: 1224px;
            margin: 0 auto;
        }

        .clients-tabs {
            display: flex;
            align-items: flex-end;
            gap: 24px;
            border-bottom: 1px solid #40404133;
            
            margin-bottom: 22px;
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: none;
        }

        .clients-tabs::-webkit-scrollbar {
            display: none;
        }

        .clients-tab {
            position: relative;
            padding: 0 0 16px;
            border: 0;
            background: transparent;
            color: #868686;
            font-size: 16px;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
            cursor: pointer;
        }

        .clients-tab::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 100%;
            height: 3px;
            background: transparent;
        }

        .clients-tab.is-active {
            color: #52A028;
        }

        .clients-tab.is-active::after {
            background: #52A028;
        }

        .clients-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 24px;
            opacity: 1;
            transition: opacity .18s ease;
            will-change: opacity;
        }

        .clients-grid.is-changing {
            opacity: 0;
        }

        .clients-brand-card {
            height: 104px;
            border: 1px solid #e5e7eb;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .clients-brand-card img {
            max-width: 100%;
            max-height: 104px;
            object-fit: contain;
            filter: grayscale(100%);
            opacity: .78;
            transition: filter .3s ease, opacity .3s ease;
        }

        .clients-brand-card:hover img {
            filter: grayscale(0%);
            opacity: 1;
        }

        .clients-empty {
            grid-column: 1 / -1;
            margin: 0;
            padding: 48px 0;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 1199px) {
            .clients-banner {
                height: 340px;
            }

            .clients-banner-inner,
            .clients-inner {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .clients-breadcrumb {
                margin-bottom: 85px;
            }

            .clients-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 767px) {
            .clients-banner {
                height: 290px;
            }

            .clients-banner-inner,
            .clients-inner {
                margin-left: 20px;
                margin-right: 20px;
            }

            .clients-banner-inner {
                padding-top: 74px;
            }

            .clients-breadcrumb {
                margin-bottom: 76px;
            }

            .clients-banner h1 {
                font-size: 32px;
            }

            .clients-section {
                padding: 56px 0 78px;
            }

            .clients-tabs {
                gap: 18px;
            }

            .clients-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .clients-brand-card {
                height: 86px;
                padding: 18px;
            }

            .clients-brand-card img {
                filter: grayscale(0%);
                opacity: 1;
            }
        }

        @keyframes cli-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .clients-breadcrumb {
            animation: cli-fade-up 0.65s ease 0.1s both;
        }

        .clients-banner h1 {
            animation: cli-fade-up 0.65s ease 0.25s both;
        }

        .clients-section {
            animation: cli-fade-up 0.7s ease 0.2s both;
        }
    </style>
</div>
