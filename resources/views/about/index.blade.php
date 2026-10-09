@extends('frontend.layouts.app')

@section('title', __('Giới thiệu'))

@push('styles')
    <style>
        .about-page {
            color: #1d1b18;
        }

        .about-hero {
            min-height: 560px;
            padding: 5rem 0 4rem;
            background-image: linear-gradient(90deg, rgba(26, 20, 16, 0.72), rgba(26, 20, 16, 0.35)),
                url('{{ asset('yakiniku-king/bg-about.jpg') }}');
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #f3efe9;
            position: relative;
        }

        .about-hero .col-lg-8 {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            backdrop-filter: blur(5px);
            box-shadow: 0 18px 40px rgba(18, 14, 11, 0.12);
        }

        .about-eyebrow {
            color: #ff0000;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .about-title {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            line-height: 1.2;
            margin: 0.5rem 0 1rem;
            text-align: center;
            color: #ffffff;
        }

        .about-lead {
            max-width: 700px;
            margin: 0 auto;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.08rem;
            line-height: 1.8;
            text-align: center;
        }

        .about-image {
            display: block;
            width: min(100%, 440px);
            height: auto;
            max-height: 420px;
            margin: 0 auto;
            object-fit: contain;
            border-radius: 20px;
            box-shadow: none;
            background: transparent;
            padding: 0;
        }

        .about-section {
            display: flex;
            align-items: flex-end;
            padding: 0;
            background-image: url('{{ asset('yakiniku-king/images.jpg') }}');
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            background-color: #f2efe9;
            min-height: 620px;
            width: 100%;
        }

        .about-section .container {
            width: 100%;
            max-width: 100%;
            padding: 0 0 0;
            display: flex;
            align-items: flex-end;
        }

        .about-content-panel {
            width: 100%;
            margin: 0;
            border-radius: 0;
            padding: 2.5rem 4rem;
        }

        .about-content-panel {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 18px 40px rgba(25, 23, 21, 0.08);
            backdrop-filter: blur(2px);
        }

        .about-content-panel h3 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #1d1b18;
        }

        .about-content-panel p {
            margin-bottom: 0;
            color: rgba(29, 27, 24, 0.8);
            line-height: 1.8;
        }

        @media (max-width: 767.98px) {
            .about-hero {
                padding-top: 4rem;
            }
        }
    </style>
@endpush

@section('content')
    <section class="about-page">
        <div class="about-hero">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <p class="about-eyebrow mb-3">{{ __('Về chúng tôi') }}</p>
                        <img class="about-image mb-4" src="{{ asset('yakiniku-king/logo1.png') }}" alt="Yakiniku King restaurant" loading="lazy">
                        <h1 class="about-title">{{ __('Đơn vị 10 năm trong lĩnh vực ẩm thực nướng Nhật Bản') }}</h1>
                        <p class="about-lead">
                            {{ __('Tại Yakiniku King, chúng tôi miệt mài vì chất lượng của từng món ăn, tin rằng mỗi buổi ăn tối nên là một trải nghiệm trọn vẹn: thực phẩm tươi ngon, không gian ấm cúng và dịch vụ tận tâm. Chúng tôi mang đến phong cách nướng Nhật Bản hiện đại, kết hợp giữa chất lượng nguyên liệu và sự sáng tạo trong từng món ăn.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-section">
            <div class="container">
                <div class="about-content-panel">
                    <div class="row g-4 align-items-start">
                        <div class="col-md-6">
                            <h3>{{ __('Thực đơn') }}</h3>
                            <p>{{ __('Chúng tôi chọn nguyên liệu từ các nguồn uy tín, mang đến những món nướng thơm lừng, mềm ngon và đậm vị. Từ bò Wagyu, thịt tươi cho đến combo gia đình, mọi món đều được chế biến theo tiêu chuẩn cao.') }}</p>
                        </div>

                        <div class="col-md-6">
                            <h3>{{ __('Không gian & Dịch vụ') }}</h3>
                            <p>{{ __('Thiết kế hiện đại nhưng vẫn giữ cảm giác ấm áp, phù hợp cho các buổi hẹn hò, họp mặt bạn bè và tiệc gia đình. Mỗi góc nhỏ đều được chăm chút để tạo nên trải nghiệm thoải mái nhất. Đội ngũ nhân viên luôn sẵn sàng hỗ trợ bạn từ lúc đặt bàn đến khi kết thúc bữa ăn, nhằm mang lại sự hài lòng trong từng khoảnh khắc thưởng thức.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
