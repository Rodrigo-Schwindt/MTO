<div
    x-data="{ show: false, shownSection2: false }"
    x-init="setTimeout(() => show = true, 50)"
    x-show="show"
    x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="w-full overflow-hidden bg-white">

    @if($nosotros)
        <section class="about-banner">
            @if($nosotros->banner_image)
                <img src="{{ Storage::url($nosotros->banner_image) }}" alt="Nosotros" class="about-banner-image">
            @elseif($nosotros->image)
                <img src="{{ Storage::url($nosotros->image) }}" alt="Nosotros" class="about-banner-image">
            @else
                <div class="about-banner-fallback"></div>
            @endif

            <div class="about-banner-overlay"></div>

            <div class="about-banner-inner">
                <nav class="about-breadcrumb">
                    <a wire:navigate href="{{ url('/') }}">Inicio</a>
                    <span>›</span>
                    <span>Nosotros</span>
                </nav>

                <h1>Nosotros</h1>
            </div>
        </section>

        <section class="about-intro">
            <div class="about-intro-copy">
                <h2>{{ $nosotros->title ?: 'Quienes somos' }}</h2>

                <div class="about-description">
                    {!! str_replace('&nbsp;', ' ', $nosotros->description) !!}
                </div>

                @php
                    $partners = collect([
                        $nosotros->partner_logo_1,
                        $nosotros->partner_logo_2,
                        $nosotros->partner_logo_3,
                        $nosotros->partner_logo_4,
                    ])->filter();
                @endphp

                @if($partners->isNotEmpty())
                    <div class="about-partners">
                        <h3>{{ $nosotros->partners_title ?: 'Somos socios de' }}</h3>
                        <div class="about-partners-grid">
                            @foreach($partners as $partnerLogo)
                                <img src="{{ Storage::url($partnerLogo) }}" alt="Socio">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="about-intro-image">
                @if($nosotros->image)
                    <img src="{{ Storage::url($nosotros->image) }}" alt="{{ $nosotros->title }}">
                @endif
            </div>
        </section>

        @php
            $whyCards = collect([1,2,3,4,5])->map(function ($n) use ($nosotros) {
                $title = $nosotros->{'title_'.$n};
                $description = $nosotros->{'description_'.$n};
                $image = $nosotros->{'image_'.$n};
                $descriptionText = trim(strip_tags(str_replace('&nbsp;', ' ', $description ?? '')));

                if (!filled($title) && !filled($descriptionText) && !filled($image)) {
                    return null;
                }

                return compact('title', 'description', 'image');
            })->filter()->values();
        @endphp

        @if($whyCards->isNotEmpty())
        <section class="about-why" x-intersect.once="shownSection2 = true">
            <div class="about-why-inner">
                <h2 :class="shownSection2 ? 'is-visible' : ''">¿Por que elegirnos?</h2>

                <div class="about-cards about-cards-count-{{ $whyCards->count() }}">
                    @foreach($whyCards as $index => $card)

                        <article class="about-card about-card-{{ $index + 1 }}">
                            @if($card['image'])
                                <div class="about-card-icon">
                                    <img src="{{ Storage::url($card['image']) }}" alt="{{ $card['title'] }}">
                                </div>
                            @endif

                            @if($card['title'])
                                <h3>{{ $card['title'] }}</h3>
                            @endif

                            @if($card['description'])
                                <div class="about-card-text">
                                    {!! str_replace('&nbsp;', ' ', $card['description']) !!}
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endif

    <style>
        .about-banner {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background: #143a1a;
        }

        .about-banner-image,
        .about-banner-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-banner-fallback {
            background: #143a1a;
        }

        .about-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(20, 58, 26, 0.68) 0%, rgba(20, 58, 26, 0.26) 32%, rgba(20, 58, 26, 0) 58%), linear-gradient(180deg, rgba(20, 58, 26, 0) 42%, rgba(20, 58, 26, 0.62) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.10) 100%), linear-gradient(180deg, rgba(67, 158, 51, 0.20) 0%, rgba(180, 203, 25, 0.20) 100%);
        }

        .about-banner-inner {
            position: relative;
            z-index: 2;
            max-width: 1224px;
            height: 100%;
            margin: 0 auto;
            padding-top: 94px;
            display: flex;
            flex-direction: column;
        }

        .about-breadcrumb {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #fff;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 148px;
        }

        .about-breadcrumb a {
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .about-banner h1 {
            margin: 0;
            color: #fff;
            font-size: 40px;
            font-weight: 700;
            line-height: 1;
            text-transform: uppercase;
            text-shadow: 0 6px 20px rgba(0,0,0,.55);
        }

        .about-intro {
            max-width: 1224px;
            margin: 0 auto;
            padding: 88px 0 72px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: start;
        }

        .about-intro-copy h2 {
            margin: 0 0 13px;
            color: #333;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
        }

        .about-description {
            color: #111;
            font-size: 17px;
            line-height: 1.55;
        }

        .about-description p {
            margin: 0 0 18px;
        }

      

        .about-intro-image {
            width: 600px;
            height: 440px;
            overflow: hidden;
            background: #e5e7eb;
        }

        .about-intro-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .about-partners {
            margin-top: 54px;
        }

        .about-partners h3 {
            margin: 0 0 18px;
            color: #353C47;
            font-size: 18px;
            font-weight: 800;
        }

        .about-partners-grid {
            display: flex;
            align-items: center;
            gap: 22px;
            flex-wrap: wrap;
        }

        .about-partners-grid img {
            max-width: 138px;
            max-height: 69px;
            object-fit: contain;
        }

        .about-why {
            background: linear-gradient(180deg, #2e771f 0%, #829908 100%);
            padding: 76px 0 48px;
        }

        .about-why-inner {
            max-width: 1224px;
            margin: 0 auto;
        }

        .about-why h2 {
            margin: 0 0 28px;
            color: #fff;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .about-why h2.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .about-cards {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 24px;
        }

        .about-card {
            grid-column: span 2;
            min-height: 380px;
            background: #fff;
            padding: 58px 36px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            opacity: 0;
            animation: fadeInUp .7s ease-out forwards;
        }

        .about-card-2 { animation-delay: .1s; }
        .about-card-3 { animation-delay: .2s; }
        .about-card-4 { animation-delay: .3s; }
        .about-card-5 { animation-delay: .4s; }

        .about-cards-count-1 .about-card {
            grid-column: span 6;
        }

        .about-cards-count-2 .about-card,
        .about-cards-count-4 .about-card,
        .about-cards-count-5 .about-card-4,
        .about-cards-count-5 .about-card-5 {
            grid-column: span 3;
        }

        .about-card-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 34px;
        }

        .about-card-icon img {
            max-width: 52px;
            max-height: 52px;
            object-fit: contain;
        }

        .about-card h3 {
            margin: 0 0 28px;
            color: #353C47;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .about-card-text {
            color: #111;
            font-size: 14px;
            line-height: 1.48;
        }

        .about-card-text p {
            margin: 0 0 10px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 1199px) {
            .about-banner-inner,
            .about-intro,
            .about-why-inner {
                max-width: none;
                margin-left: 28px;
                margin-right: 28px;
            }

            .about-banner {
                height: 340px;
            }

            .about-breadcrumb {
                margin-bottom: 85px;
            }

            .about-intro {
                grid-template-columns: 1fr;
                gap: 36px;
                padding: 64px 0;
            }

            .about-intro-image {
                width: 100%;
                height: 360px;
            }
        }

        @media (max-width: 899px) {
            .about-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .about-card,
            .about-cards-count-1 .about-card,
            .about-cards-count-2 .about-card,
            .about-cards-count-4 .about-card,
            .about-cards-count-5 .about-card-4,
            .about-cards-count-5 .about-card-5,
            .about-card-4,
            .about-card-5 {
                grid-column: auto;
            }

            .about-card {
                padding: 44px 28px 28px;
            }
        }

        @media (max-width: 767px) {
            .about-banner {
                height: 290px;
            }

            .about-banner-inner,
            .about-intro,
            .about-why-inner {
                margin-left: 20px;
                margin-right: 20px;
            }

            .about-banner-inner {
                padding-top: 74px;
            }

            .about-breadcrumb {
                margin-bottom: 76px;
            }

            .about-banner h1 {
                font-size: 32px;
            }

            .about-intro {
                padding: 44px 0;
            }

            .about-intro-image {
                height: 280px;
            }

            .about-cards {
                grid-template-columns: 1fr;
            }

            .about-why {
                padding: 48px 0;
            }
        }
    </style>
</div>
