@php
    $description = str_replace('&nbsp;', ' ', (string) ($sector->description ?? ''));
@endphp

<div>
    <section class="secdet-section">
        <div class="secdet-inner">
            <nav class="secdet-breadcrumb">
                <a wire:navigate href="{{ url('/') }}">Inicio</a>
                <span>&rsaquo;</span>
                <a wire:navigate href="{{ route('sectores.public') }}">Sectores</a>
                <span>&rsaquo;</span>
                <span>{{ $sector->title }}</span>
            </nav>

            <div class="secdet-grid">
                <div class="secdet-content">
                    <h1>{{ $sector->title }}</h1>

                    <div class="secdet-description">
                        {!! $description !!}
                    </div>
                </div>

                <div class="secdet-image-wrap">
                    @if($sector->image)
                        <img src="{{ Storage::url($sector->image) }}" alt="{{ $sector->title }}">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <style>
        .secdet-section {
            background: #fff;
            padding: 0 0 112px;
        }

        .secdet-inner {
            max-width: 1224px;
            margin: 0 auto;
            padding-top: 24px;
        }

        .secdet-breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            color: #8a8a8a;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 54px;
        }

        .secdet-breadcrumb a {
            color: #555;
            font-weight: 700;
            text-decoration: none;
        }

        .secdet-breadcrumb a:hover {
            color: #52A028;
        }

        .secdet-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 72px;
            align-items: start;
        }

        .secdet-content h1 {
            margin: 0 0 28px;
            color: #404041;
            font-size: 36px;
            font-weight: 700;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .secdet-description {
            color: #1f1f1f;
            font-size: 16px;
            line-height: 1.55;
        }

        .secdet-description p {
            margin: 0 0 24px;
        }

        .secdet-description strong,
        .secdet-description b {
            font-weight: 700;
        }

        .secdet-image-wrap {
            width: 100%;
            height: 580px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .secdet-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 1199px) {
            .secdet-inner {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .secdet-grid {
                gap: 40px;
            }
        }

        @media (max-width: 767px) {
            .secdet-section {
                padding: 0 0 78px;
            }

            .secdet-inner {
                margin-left: 20px;
                margin-right: 20px;
                padding-top: 20px;
            }

            .secdet-breadcrumb {
                margin-bottom: 36px;
            }

            .secdet-grid {
                grid-template-columns: 1fr;
            }

            .secdet-content h1 {
                font-size: 28px;
                margin-bottom: 20px;
            }

            .secdet-image-wrap {
                height: 340px;
            }
        }

        @keyframes secdet-fade-up {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .secdet-breadcrumb {
            animation: secdet-fade-up 0.55s ease 0.05s both;
        }

        .secdet-content {
            animation: secdet-fade-up 0.65s ease 0.15s both;
        }

        .secdet-image-wrap {
            animation: secdet-fade-up 0.65s ease 0.3s both;
        }
    </style>
</div>
