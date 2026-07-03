@php
    use Illuminate\Support\Str;
@endphp

<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
>
    <section class="news-banner">
        @if($banner && $banner->image_banner)
            <img src="{{ Storage::url($banner->image_banner) }}" alt="Novedades" class="news-banner-image">
        @else
            <div class="news-banner-fallback"></div>
        @endif

        <div class="news-banner-overlay"></div>

        <div class="news-banner-inner">
            <nav class="news-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <span>Novedades</span>
            </nav>

            <h1>Novedades</h1>
        </div>
    </section>

    <section class="news-list">
        <div class="news-grid">
            @forelse ($novedades as $item)
                @php
                    $category = $item->novcategories->first()->title ?? 'Novedad';
                    $description = trim(html_entity_decode(strip_tags($item->description), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                @endphp

                <a wire:navigate href="{{ route('novedad.detalle', $item->id) }}" class="news-card">
                    <span class="news-card-image">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}" loading="lazy">
                        @endif
                    </span>

                    <span class="news-card-body">
                        <span class="news-card-category">{{ $category }}</span>
                        <span class="news-card-title">{{ $item->title }}</span>
                        <span class="news-card-description">{{ Str::limit($description, 116) }}</span>
                        <span class="news-card-link">Leer m&aacute;s</span>
                    </span>
                </a>
            @empty
                <p class="news-empty">No hay novedades disponibles.</p>
            @endforelse
        </div>

        @if($novedades->hasPages())
            <div class="news-pagination">
                {{ $novedades->links() }}
            </div>
        @endif
    </section>

    <style>
        .news-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .news-banner-image,
        .news-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-banner-fallback {
            background: #143a1a;
        }

        .news-banner-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%),
                linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%),
                linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%),
                linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .news-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .news-breadcrumb {
            display: flex;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .news-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .news-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .news-list {
            padding: 74px 0 112px;
            background: #fff;
        }

        .news-grid {
            max-width: 1224px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 56px 24px;
        }

        .news-card {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .news-card-image {
            display: block;
            height: 242px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .news-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .news-card:hover .news-card-image img {
            transform: scale(1.04);
        }

        .news-card-body {
            display: block;
            padding: 22px 20px 0;
        }

        .news-card-category {
            display: block;
            margin-bottom: 12px;
            color: #0B982C;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
        }

        .news-card-title {
            display: block;
            min-height: 58px;
            margin-bottom: 12px;
            color: #404041;
            font-size: 26px;
            font-weight: 400;
            line-height: 1.1;
            transition: color .2s ease;
        }

        .news-card:hover .news-card-title {
            color: #52A028;
        }

        .news-card-description {
            display: block;
            min-height: 54px;
            color: #404041;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.4;
        }

        .news-card-link {
            display: block;
            margin-top: 22px;
            color: #9b9b9b;
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            transition: color .2s ease;
        }

        .news-card:hover .news-card-link {
            color: #52A028;
        }

        .news-empty {
            grid-column: 1 / -1;
            margin: 0;
            padding: 56px 0;
            text-align: center;
            color: #64748b;
        }

        .news-pagination {
            max-width: 1224px;
            margin: 54px auto 0;
        }

        @media (max-width: 1239px) {
            .news-banner-inner,
            .news-grid,
            .news-pagination {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .news-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 44px 24px;
            }
        }

        @media (max-width: 767px) {
            .news-banner {
                height: 290px;
            }

            .news-banner-inner,
            .news-grid,
            .news-pagination {
                margin-left: 20px;
                margin-right: 20px;
            }

            .news-banner-inner {
                padding-top: 74px;
            }

            .news-breadcrumb {
                margin-bottom: 82px;
            }

            .news-banner h1 {
                font-size: 32px;
            }

            .news-list {
                padding: 52px 0 76px;
            }

            .news-grid {
                grid-template-columns: 1fr;
                gap: 42px;
            }

            .news-card-image {
                height: 230px;
            }

            .news-card-body {
                padding: 18px 0 0;
            }

            .news-card-title {
                min-height: auto;
                font-size: 22px;
            }

            .news-card-description {
                min-height: auto;
                font-size: 15px;
            }
        }
    </style>
</div>
