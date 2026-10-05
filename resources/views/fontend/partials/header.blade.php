<header>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

        <div class="container">

            {{-- Logo --}}
            <a class="navbar-brand fw-bold"
               href="{{ url('/') }}">
                <img src="{{ asset('yakiniku-king/logo.png') }}" alt="Yakiniku King logo" width="none" height="80">
            </a>


            {{-- Mobile button --}}
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#frontendNavbar">

                <span class="navbar-toggler-icon"></span>

            </button>


            {{-- Menu --}}
            <div class="collapse navbar-collapse"
                 id="frontendNavbar">

                <ul class="navbar-nav align-items-right ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ url('/') }}">
                            {{ __('Trang chủ') }}
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="{{ route('menu.index') }}"
                           id="menuDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            {{ __('Thực đơn') }}
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                            <li><a class="dropdown-item" href="{{ route('menu.must-try') }}">{{ __('Món nên thử') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('menu.combos') }}">Combo</a></li>
                            <li><a class="dropdown-item" href="{{ route('menu.for-kids') }}">{{ __('Dành cho trẻ em') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('menu.promotions') }}">{{ __('Khuyến mãi') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('menu.index') }}">{{ __('Khám phá thực đơn') }}</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           id="ourSecretDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            {{ __('Bí quyết của chúng tôi') }}
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="ourSecretDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('secret.recipes') }}">
                                    {{ __('Công thức') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('secret.tips') }}">
                                    {{ __('Bí kíp ăn ngon') }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('about') }}">
                            {{ __('Về chúng tôi') }}
                        </a>
                    </li>
                </ul>

                <form class="d-flex align-items-center gap-1 ms-lg-3 pb-3 pb-lg-0" method="POST" action="{{ route('locale.update') }}" aria-label="{{ __('Chọn ngôn ngữ') }}">
                    @csrf
                    <button class="btn btn-sm {{ app()->getLocale() === \App\Enums\AppLocale::Vietnamese->value ? 'btn-danger' : 'btn-outline-secondary' }}" type="submit" name="locale" value="{{ \App\Enums\AppLocale::Vietnamese->value }}" lang="{{ \App\Enums\AppLocale::Vietnamese->value }}" aria-pressed="{{ app()->getLocale() === \App\Enums\AppLocale::Vietnamese->value ? 'true' : 'false' }}">VI</button>
                    <button class="btn btn-sm {{ app()->getLocale() === \App\Enums\AppLocale::English->value ? 'btn-danger' : 'btn-outline-secondary' }}" type="submit" name="locale" value="{{ \App\Enums\AppLocale::English->value }}" lang="{{ \App\Enums\AppLocale::English->value }}" aria-pressed="{{ app()->getLocale() === \App\Enums\AppLocale::English->value ? 'true' : 'false' }}">EN</button>
                </form>
            </div>

        </div>

    </nav>

</header>
