<div
    x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 transform -translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
>
    <section class="news-detail">
        <nav class="news-detail-breadcrumb">
            <a wire:navigate href="{{ url('/') }}">Inicio</a>
            <span>&rsaquo;</span>
            <a wire:navigate href="{{ route('novedades.public') }}">Novedades</a>
            <span>&rsaquo;</span>
            <span>{{ $novedad->title }}</span>
        </nav>

        @if($showDetail)
            <article class="news-detail-article">
                <div class="news-detail-heading">
                    <p class="news-detail-category">{{ $novedad->novcategories->first()->title ?? 'Novedad' }}</p>
                    <h1>{{ $novedad->title }}</h1>
                </div>

                @if($novedad->image)
                    <figure class="news-detail-image">
                        <img src="{{ Storage::url($novedad->image) }}" alt="{{ $novedad->title }}">
                    </figure>
                @endif

                <div class="news-detail-content">
                    {!! str_replace('&nbsp;', ' ', $novedad->description) !!}
                </div>
            </article>
        @endif
    </section>

    <style>
        .news-detail {
            max-width: 1224px;
            margin: 0 auto;
            padding: 24px 0 112px;
            background: #fff;
        }

        .news-detail-breadcrumb {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #8a8a8a;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 56px;
        }

        .news-detail-breadcrumb a {
            color: #555;
            font-weight: 700;
            text-decoration: none;
        }

        .news-detail-article {
            width: 100%;
        }

        .news-detail-heading {
            max-width: 900px;
            margin-bottom: 30px;
        }

        .news-detail-category {
            margin: 0 0 12px;
            color: #0B982C;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
            text-transform: uppercase;
        }

        .news-detail-heading h1 {
            margin: 0;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
            color: #404041;
            font-size: 40px;
            font-weight: 700;
            line-height: 1.18;
        }

        .news-detail-image {
            width: 100%;
            height: 520px;
            margin: 0 0 42px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .news-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .news-detail-content {
            max-width: 900px;
            color: #2f3032;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.65;
        }

        .news-detail-content p {
            margin: 0 0 18px;
        }

        .news-detail-content h2,
        .news-detail-content h3 {
            margin: 34px 0 14px;
            color: #404041;
            font-weight: 700;
            line-height: 1.25;
        }

        .news-detail-content ul,
        .news-detail-content ol {
            margin: 0 0 20px 22px;
            padding: 0;
        }

        .news-detail-content li {
            margin-bottom: 8px;
        }

        .news-detail-content a {
            color: #52A028;
            text-decoration: underline;
        }

        .news-detail-content img {
            max-width: 100%;
            height: auto;
            margin: 28px 0;
            display: block;
        }

        @media (max-width: 1239px) {
            .news-detail {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .news-detail-image {
                height: 430px;
            }
        }

        @media (max-width: 767px) {
            .news-detail {
                margin-left: 20px;
                margin-right: 20px;
                padding: 20px 0 76px;
            }

            .news-detail-breadcrumb {
                margin-bottom: 36px;
                flex-wrap: wrap;
            }

            .news-detail-heading h1 {
                font-size: 30px;
            }

            .news-detail-image {
                height: 260px;
                margin-bottom: 30px;
            }

            .news-detail-content {
                font-size: 16px;
                line-height: 1.6;
            }
        }
    </style>
</div>
