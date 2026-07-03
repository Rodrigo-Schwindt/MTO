<section class="home-about-section" x-data="{ shown: false }" x-intersect.once.threshold.0.2="shown = true">
    @php $nosotros = $nosotros ?? \App\Models\Nosotros::first(); @endphp

    @if($nosotros)
        <div class="home-about-wrap">
            <div
                class="home-about-image"
                :class="shown ? 'is-visible' : ''"
            >
                @if($nosotros->image_home)
                    <img
                        src="{{ Storage::url($nosotros->image_home) }}"
                        alt="{{ $nosotros->title_home }}"
                    >
                @endif
            </div>

            <div
                class="home-about-content"
                :class="shown ? 'is-visible' : ''"
            >
                <div class="home-about-inner">
                    <h2>{{ $nosotros->title_home ?: 'Quienes somos' }}</h2>

                    <div class="home-about-copy">
                        {!! str_replace('&nbsp;', ' ', $nosotros->description_home) !!}
                    </div>

                    <a wire:navigate href="/nosotros" class="home-about-button">
                        Mas informacion
                    </a>
                </div>
            </div>
        </div>

        <style>
            .home-about-section {
                width: 100%;
                background: #fff;
                overflow: hidden;
                margin: 0;
            }

            .home-about-wrap {
                display: grid;
                grid-template-columns: 1fr 1fr;
                height: 600px;
            }

            .home-about-image,
            .home-about-content {
                height: 600px;
                opacity: 0;
                transition: opacity .8s ease, transform .8s ease;
            }

            .home-about-image {
                transform: translateX(-28px);
                background: #111;
            }

            .home-about-content {
                transform: translateX(28px);
                background: linear-gradient(180deg, #2e771f 0%, #829908 100%);
                color: #fff;
                display: flex;
                align-items: center;
                overflow: hidden;
            }

            .home-about-image.is-visible,
            .home-about-content.is-visible {
                opacity: 1;
                transform: translateX(0);
            }

            .home-about-image img {
                width: 100%;
                height: 100%;
                display: block;
                object-fit: cover;
                object-position: center;
            }

            .home-about-inner {
                width: 100%;
                height: 100%;
                margin-left: 56px;
                max-height: 100%;
                padding: 52px 72px 52px 0;
                display: flex;
                flex-direction: column;
            }

            .home-about-kicker {
                margin: 0 0 8px;
                color: #fff;
                font-size: 15px;
                font-weight: 800;
                line-height: 1.2;
                text-transform: uppercase;
            }

            .home-about-inner h2 {
                margin: 0 0 28px;
                color: #fff;
                font-size: 40px;
                font-weight: 700;
                line-height: 1.12;
            }

            .home-about-copy {
                color: rgba(255, 255, 255, .9);
                font-size: 16px;
                line-height: 1.5;
                max-height: 265px;
                overflow: hidden;
                opacity: 0.9;
            }

          

            .home-about-copy p:first-child,
            .home-about-copy strong {
                color: #fff;
            }

            .home-about-button {
                width: 271px;
                height: 41px;
                margin-top: auto;
                flex: 0 0 auto;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid rgba(255, 255, 255, .78);
                color: #fff;
                font-size: 16px;
                font-weight: 600;
                line-height: 1;
                text-decoration: none;
                transition: background-color .2s ease, color .2s ease;
            }

            .home-about-button:hover {
                background: #fff;
                color: #2e771f;
            }

            @media (max-width: 1199px) {
                .home-about-wrap {
                    grid-template-columns: 1fr;
                    height: auto;
                }

                .home-about-image,
                .home-about-content {
                    height: auto;
                    min-height: 420px;
                }

                .home-about-inner {
                    max-width: none;
                    height: auto;
                    margin-left: 0;
                    padding: 52px 32px;
                }

                .home-about-button {
                    margin-top: 42px;
                }
            }

            @media (max-width: 767px) {
                .home-about-image,
                .home-about-content {
                    min-height: 300px;
                }

                .home-about-inner {
                    height: auto;
                    padding: 40px 20px;
                }

                .home-about-inner h2 {
                    font-size: 26px;
                    margin-bottom: 22px;
                }

                .home-about-copy {
                    font-size: 15px;
                }

                .home-about-button {
                    width: 100%;
                    max-width: 260px;
                    margin-top: 34px;
                }
            }
        </style>
    @endif
</section>
