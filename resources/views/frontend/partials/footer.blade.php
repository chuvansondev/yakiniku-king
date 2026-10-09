<style>
        .site-footer {
            --footer-bg: #191d1b;
            --footer-panel: #222825;
            --footer-text: #c5cbc6;
            --footer-line: rgba(255, 255, 255, .12);
            --footer-accent: #ff0000;
            --footer-action: #a83d32;
            margin-top: 0;
            background: var(--footer-bg);
            color: var(--footer-text);
        }

        .site-footer__main {
            padding-top: 4.5rem;
            padding-bottom: 3.5rem;
        }

        .site-footer__grid {
            display: grid;
            grid-template-columns: 1.15fr .9fr 1fr;
            gap: 3rem;
        }

        .site-footer__brandline {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .site-footer__logo {
            display: block;
            width: 76px;
            height: 68px;
            object-fit: contain;
        }

        .site-footer__brand-name {
            margin: 0;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .site-footer__eyebrow {
            display: block;
            margin-bottom: .65rem;
            color: var(--footer-accent);
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .site-footer__title {
            margin-bottom: 1rem;
            color: #fff;
            font-size: 1.15rem;
            font-weight: 650;
        }

        .site-footer__copy {
            margin-bottom: .8rem;
            color: var(--footer-text);
            font-size: .925rem;
            line-height: 1.75;
        }

        .site-footer__contact {
            display: grid;
            gap: .5rem;
            margin-top: 1.25rem;
        }

        .site-footer a {
            color: #f1f2ef;
            text-decoration: none;
            transition: color .18s ease, background-color .18s ease, border-color .18s ease;
        }

        .site-footer a:hover,
        .site-footer a:focus-visible {
            color: var(--footer-accent);
        }

        .site-footer__signup {
            margin: 1.25rem 0 1.75rem;
            padding: 1.25rem;
            border: 1px solid var(--footer-line);
            border-left: 3px solid var(--footer-accent);
            background: var(--footer-panel);
        }

        .site-footer__signup p {
            margin-bottom: 1rem;
            font-size: .925rem;
            line-height: 1.65;
        }

        .site-footer__signup .btn {
            padding: .65rem 1.1rem;
            border: 1px solid var(--footer-action);
            border-radius: 2px;
            background: var(--footer-action);
            color: #fff;
            font-weight: 600;
        }

        .site-footer__signup .btn:hover,
        .site-footer__signup .btn:focus-visible {
            border-color: #c75a4d;
            background: #c75a4d;
            color: #fff;
        }

        .site-footer__legal {
            margin: 0;
            color: #aab2ac;
            font-size: .8rem;
            line-height: 1.7;
        }

        .site-footer__topics {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .6rem 1rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .site-footer__topics a {
            color: var(--footer-text);
            font-size: .9rem;
            line-height: 1.5;
        }

        .site-footer__socials {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .7rem;
            margin-bottom: 1.5rem;
        }

        .site-footer__social-link {
            display: grid;
            width: 48px;
            height: 48px;
            place-items: center;
            border: 1px solid var(--footer-line);
            border-radius: 3px;
            background: var(--footer-panel);
        }

        .site-footer__social-link img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        .site-footer__certificate {
            display: inline-block;
            max-width: 200px;
        }

        .site-footer__certificate img {
            display: block;
            width: 100%;
            height: auto;
        }

        .site-footer__facebook {
            width: 100%;
            height: 240px;
            margin-top: 1.5rem;
            border: 0;
            overflow: hidden;
        }

        .offer-modal .modal-dialog {
            width: calc(100% - 1.5rem);
            max-width: 520px;
        }

        .offer-modal__content {
            overflow: hidden;
            border: 1px solid rgba(215, 179, 106, .4);
            border-radius: 6px;
            background: #f6f4ee;
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, .28);
        }

        .offer-modal__header {
            position: relative;
            display: block;
            padding: 2rem 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            background: #1c2420;
            color: #fff;
        }

        .offer-modal__eyebrow {
            display: block;
            margin-bottom: .65rem;
            color: #ff0000;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .offer-modal__title {
            margin: 0 2rem .65rem 0;
            color: #fff;
            font-size: 1.55rem;
            font-weight: 700;
        }

        .offer-modal__intro {
            max-width: 390px;
            margin: 0;
            color: #c5cbc6;
            font-size: .9rem;
            line-height: 1.65;
        }

        .offer-modal__close {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
        }

        .offer-modal__body {
            padding: 1.75rem 2rem 1rem;
        }

        .offer-modal .form-label {
            margin-bottom: .5rem;
            color: #29322d;
            font-size: .85rem;
            font-weight: 650;
        }

        .offer-modal .form-control,
        .offer-modal .form-select {
            min-height: 48px;
            border-color: #d7dad5;
            border-radius: 3px;
            background: #fff;
            color: #202722;
        }

        .offer-modal .form-control::placeholder {
            color: #858b85;
        }

        .offer-modal .form-control:focus,
        .offer-modal .form-select:focus {
            border-color: #a83d32;
            box-shadow: 0 0 0 .2rem rgba(168, 61, 50, .14);
        }

        .offer-modal__footer {
            padding: .5rem 2rem 2rem;
            border-top: 0;
        }

        .offer-modal__submit {
            width: 100%;
            min-height: 48px;
            border: 1px solid #a83d32;
            border-radius: 3px;
            background: #a83d32;
            color: #fff;
            font-weight: 650;
        }

        .offer-modal__submit:hover,
        .offer-modal__submit:focus-visible {
            border-color: #c75a4d;
            background: #c75a4d;
            color: #fff;
        }

        .offer-modal__submit:disabled {
            border-color: #8f4942;
            background: #8f4942;
            color: #fff;
        }

        #offerRegistrationMessage {
            border-radius: 3px;
        }

        @media (max-width: 575.98px) {
            .offer-modal .modal-dialog {
                width: calc(100% - 1rem);
                margin: .5rem auto;
            }

            .offer-modal__header {
                padding: 1.5rem 1.25rem 1.25rem;
            }

            .offer-modal__title {
                font-size: 1.35rem;
            }

            .offer-modal__body {
                padding: 1.25rem 1.25rem .75rem;
            }

            .offer-modal__footer {
                padding: .5rem 1.25rem 1.25rem;
            }
        }

        .site-footer__bottom {
            border-top: 1px solid var(--footer-line);
        }

        .site-footer__copyright {
            padding: 1rem 0;
            color: #aab2ac;
            font-size: .8rem;
        }

        @media (max-width: 991.98px) {
            .site-footer__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 2.5rem;
            }

        }

        @media (max-width: 767.98px) {
            .site-footer__main {
                padding-top: 3rem;
                padding-bottom: 2.5rem;
            }

            .site-footer__grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 2rem;
            }

        }
</style>

<footer class="site-footer">
    <div class="container site-footer__main site-footer__grid">
            <section aria-labelledby="footer-brand-title">
                <div class="site-footer__brandline">
                    <a href="{{ url('/') }}" aria-label="{{ localized_setting('site_name') }} - {{ __('Trang chủ') }}">
                        <img
                            class="site-footer__logo"
                            src="{{ asset('yakiniku-king/logo1.png') }}"
                            alt="Yakiniku King logo"
                            width="76"
                            height="68">
                    </a>
                    <h2 class="site-footer__brand-name" id="footer-brand-title">
                        {{ localized_setting('site_name') }}
                    </h2>
                </div>

                <span class="site-footer__eyebrow">{{ __('Nhà hàng') }}</span>
                <p class="site-footer__copy">{{ localized_setting('footer_address') }}</p>
                <p class="site-footer__copy">{{ __('Thời gian phục vụ từ 11h đến 23h.') }}</p>

                <div class="site-footer__contact">
                    <a href="tel:{{ setting('hotline') }}">{{ setting('hotline_vn_jp') }}</a>
                    <a href="tel:{{ setting('hotline') }}">{{ setting('hotline_en') }}</a>
                    <a href="mailto:ussina.landmark81@ussinavietnam.com">{{ setting('email') }}</a>
                </div>
                <br>
                <span class="site-footer__eyebrow">{{ __('Theo dõi và đánh giá') }}</span>
                <div class="site-footer__socials">
                    <a class="site-footer__social-link" href="https://www.facebook.com/ussinavietnam/" aria-label="Facebook Ussina Vietnam">
                        <img src="{{ asset('yakiniku-king/logo-facebook.png') }}" alt="" width="28" height="28">
                    </a>
                    <a class="site-footer__social-link" href="https://www.google.com/search?sxsrf=ACYBGNQPLfjiPbZ8qK6wTcU4GcFIQNJDsA%3A1568026028380&ei=rC12XazzFpDj-AaSx4_ADw&q=Ussina+Aging+Beef+%26+Bar+landmark+81&oq=Ussina+Aging+Beef+%26+Bar+landmark+81&gs_l=psy-ab.3..35i39l2j38.8560.16186..16983...2.2..0.180.1696.1j14......0....1..gws-wiz.......0i71j0j0i22i30j0i203j33i160j35i304i39.kzaVPMSedbs&ved=0ahUKEwis-a2TyMPkAhWQMd4KHZLjA_gQ4dUDCAs&uact=5#lrd=0x31752965c64ce237:0x8b8e188d592080ca,1,," aria-label="{{ __('Đánh giá trên Google') }}">
                        <img src="{{ asset('yakiniku-king/gg-my-business.png') }}" alt="" width="28" height="28">
                    </a>
                    <a class="site-footer__social-link" href="https://www.tripadvisor.com.vn/Restaurant_Review-g293925-d19647189-Reviews-Ussina_Aging_Beef_Bar-Ho_Chi_Minh_City.html" aria-label="{{ __('Ussina trên Tripadvisor') }}">
                        <img src="{{ asset('yakiniku-king/tripadvisor-icon.png') }}" alt="" width="28" height="28">
                    </a>
                </div>
                <a
                    class="site-footer__certificate"
                    href="https://online.gov.vn/nen-tang/76be9e1f-3034-43bf-a3b6-193d8d81904a"
                    aria-label="{{ __('Thông tin đăng ký Bộ Công Thương') }}">
                    <img src="https://ussinavietnam.vn/wp-content/uploads/2020/08/dathongbaobct.png" alt="{{ __('Đã thông báo Bộ Công Thương') }}" width="200" height="70" loading="lazy">
                </a>
            </section>

            <section aria-labelledby="footer-offers-title">
                <span class="site-footer__eyebrow">{{ __('Kết nối với chúng tôi') }}</span>
                <h2 class="site-footer__title" id="footer-offers-title">{{ __('Đăng ký nhận ưu đãi') }}</h2>
                <div class="site-footer__signup">
                    <p>{{ __('Đăng ký nhận thư điện tử để không bỏ lỡ những ưu đãi mới nhất.') }}</p>
                    <button
                        type="button"
                        class="btn"
                        data-bs-toggle="modal"
                        data-bs-target="#offerRegistrationModal">
                        {{ __('Đăng ký') }}
                    </button>
                </div>
                <p class="site-footer__legal">
                    {{ __('NHÀ HÀNG USSINA – VINCOM LANDMARK 81') }}<br>
                    {{ __('Giấy CNĐKDN: 0312225168-002 – Ngày cấp: 01/04/2019') }}<br>
                    {{ __('Cơ quan cấp: Phòng Đăng ký kinh doanh – Sở kế hoạch và Đầu tư TP.HCM') }}<br>
                    {{ __('Địa chỉ đăng ký kinh doanh: Tầng L77, Tòa nhà Landmark 81, 720A Điện Biên Phủ, phường Thạnh Mỹ Tây, Thành phố Hồ Chí Minh, Việt Nam') }}
                </p>
            </section>

            <section aria-labelledby="footer-topics-title">
                <span class="site-footer__eyebrow">{{ __('Khám phá') }}</span>
                <h2 class="site-footer__title" id="footer-topics-title">{{ __('Chủ đề nổi bật') }}</h2>
                <ul class="site-footer__topics">
                    <li><a href="https://ussinavietnam.vn/tag/am-thuc-nhat-ban/">{{ __('Ẩm thực Nhật Bản') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/nha-hang-nhat-ban/">{{ __('Nhà hàng Nhật Bản') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/mon-an-nhat-ban/">{{ __('Món ăn Nhật Bản') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/mon-ngon-nhat-ban/">{{ __('Món ngon Nhật Bản') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/nha-hang-co-view-dep/">{{ __('Nhà hàng có view đẹp') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/nha-hang-mon-nhat/">{{ __('Nhà hàng món Nhật') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/nha-hang-sang-trong/">{{ __('Nhà hàng sang trọng') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/bo-wagyu/">{{ __('Bò Wagyu') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/nha-hang-bo-wagyu/">{{ __('Nhà hàng bò Wagyu') }}</a></li>
                    <li><a href="https://ussinavietnam.vn/tag/thit-bo-wagyu-cao-cap/">{{ __('Thịt bò Wagyu cao cấp') }}</a></li>
                </ul>
                <iframe
                    class="site-footer__facebook"
                    src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fussinavietnam%2F&tabs=&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true"
                    title="{{ __('Facebook Ussina Vietnam') }}"
                    scrolling="no"
                    frameborder="0"
                    allowfullscreen="true"
                    allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                    loading="lazy">
                </iframe>
            </section>
    </div>

    <div class="site-footer__bottom">
        <div class="container site-footer__copyright">
            © {{ date('Y') }} {{ localized_setting('site_name') }}. {{ __('Quản lý bởi Sagi') }}
        </div>
    </div>
</footer>

<button
    type="button"
    id="backToTop"
    class="btn btn-danger position-fixed bottom-0 end-0 m-3 rounded-circle d-none shadow"
    style="width: 48px; height: 48px; z-index: 1030;"
    aria-label="{{ __('Lên đầu trang') }}"
    title="{{ __('Lên đầu trang') }}">
    <span aria-hidden="true">&uarr;</span>
</button>

<div
    class="modal fade offer-modal"
    id="offerRegistrationModal"
    tabindex="-1"
    aria-labelledby="offerRegistrationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content offer-modal__content">
            <div class="modal-header offer-modal__header">
                <span class="offer-modal__eyebrow">Yakiniku King</span>
                <h2 class="modal-title offer-modal__title" id="offerRegistrationModalLabel">{{ __('Đăng ký nhận ưu đãi') }}</h2>
                <p class="offer-modal__intro" id="offerRegistrationDescription">{{ __('Nhận tin mới về thực đơn và ưu đãi dành riêng từ nhà hàng.') }}</p>
                <button
                    type="button"
                    class="btn-close btn-close-white offer-modal__close"
                    data-bs-dismiss="modal"
                    aria-label="{{ __('Đóng cửa sổ đăng ký') }}"></button>
            </div>

            <form id="offerRegistrationForm" action="{{ route('leads.store') }}" method="POST">
                @csrf
                <div class="modal-body offer-modal__body">
                    <div
                        id="offerRegistrationMessage"
                        class="alert d-none"
                        role="alert"></div>

                    <div class="mb-4">
                        <label class="form-label" for="offerSalutation">{{ __('Danh xưng') }}</label>
                        <select class="form-select" id="offerSalutation" name="salutation" required>
                            <option value="" selected disabled>{{ __('Chọn danh xưng') }}</option>
                            <option value="Ông">{{ __('Ông') }}</option>
                            <option value="Bà">{{ __('Bà') }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="offerName">{{ __('Họ và tên') }}</label>
                        <input class="form-control" id="offerName" name="name" type="text" maxlength="255" autocomplete="name" placeholder="Alex Morgan" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="offerEmail">{{ __('Email') }}</label>
                        <input class="form-control" id="offerEmail" name="email" type="email" maxlength="255" autocomplete="email" placeholder="ban@example.com" required>
                    </div>

                    <div>
                        <label class="form-label" for="offerPhone">{{ __('Số điện thoại') }}</label>
                        <input class="form-control" id="offerPhone" name="phone" type="tel" maxlength="30" autocomplete="tel" placeholder="09xx xxx xxx" required>
                    </div>
                </div>

                <div class="modal-footer offer-modal__footer">
                    <button type="submit" class="btn offer-modal__submit" id="offerRegistrationSubmit">{{ __('Gửi đăng ký') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('offerRegistrationForm');
            const message = document.getElementById('offerRegistrationMessage');
            const submitButton = document.getElementById('offerRegistrationSubmit');
            const modalElement = document.getElementById('offerRegistrationModal');
            const backToTopButton = document.getElementById('backToTop');

            const updateBackToTopVisibility = () => {
                backToTopButton.classList.toggle('d-none', window.scrollY < 250);
            };

            window.addEventListener('scroll', updateBackToTopVisibility, { passive: true });
            updateBackToTopVisibility();

            backToTopButton.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                message.className = 'alert d-none';
                submitButton.disabled = true;
                submitButton.textContent = @json(__('Đang gửi...'));

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        },
                        body: new FormData(form),
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        const validationErrors = Object.values(data.errors ?? {}).flat();
                        throw new Error(validationErrors.join(' ') || @json(__('Không thể gửi đăng ký.')));
                    }

                    message.className = 'alert alert-success';
                    message.textContent = data.message;
                    form.reset();
                    form.classList.remove('was-validated');

                    window.setTimeout(() => {
                        bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                        message.className = 'alert d-none';
                    }, 1800);
                } catch (error) {
                    message.className = 'alert alert-danger';
                    message.textContent = error.message;
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = @json(__('Gửi đăng ký'));
                }
            });
        });
    </script>
@endpush
