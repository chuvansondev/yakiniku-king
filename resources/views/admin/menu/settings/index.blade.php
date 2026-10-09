@extends('admin.layouts.app')

@section('title', 'Cài đặt website')
@section('page-title', 'Cài đặt website')

@section('content')
    <div class="admin-settings-page">
        <header class="admin-settings-heading">
            <div>
                <p class="admin-settings-eyebrow">Quản lý nội dung</p>
                <h1>Cài đặt website</h1>
                <p class="admin-settings-description">Cập nhật thông tin liên hệ và nội dung hiển thị ở chân trang.</p>
            </div>
        </header>

        @if (session('success'))
            <div class="admin-settings-notice" role="status">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-settings-errors" role="alert">
                <strong>Có lỗi xảy ra:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="admin-settings-form" action="{{ route('admin.menu.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <section class="admin-settings-section" aria-labelledby="settings-site-title">
                <div class="admin-settings-section-heading">
                    <span class="admin-settings-section-mark" aria-hidden="true">01</span>
                    <div>
                        <h2 id="settings-site-title">Thông tin website</h2>
                        <p>Tên thương hiệu hiển thị trên website.</p>
                    </div>
                </div>
                <div class="admin-settings-grid">
                    <div class="admin-settings-field">
                        <label for="site_name">Tên website</label>
                        <input id="site_name" type="text" name="settings[site_name]" value="{{ old('settings.site_name', $settings['site_name'] ?? '') }}">
                    </div>
                    <div class="admin-settings-field">
                        <label for="site_name_en">Tên website (English)</label>
                        <input id="site_name_en" type="text" name="settings[site_name_en]" value="{{ old('settings.site_name_en', $settings['site_name_en'] ?? '') }}">
                    </div>
                </div>
            </section>

            <section class="admin-settings-section" aria-labelledby="settings-contact-title">
                <div class="admin-settings-section-heading">
                    <span class="admin-settings-section-mark" aria-hidden="true">02</span>
                    <div>
                        <h2 id="settings-contact-title">Liên hệ</h2>
                        <p>Thông tin khách hàng dùng để liên hệ với nhà hàng.</p>
                    </div>
                </div>
                <div class="admin-settings-grid">
                    <div class="admin-settings-field">
                        <label for="hotline">Hotline</label>
                        <input id="hotline" type="text" name="settings[hotline]" value="{{ old('settings.hotline', $settings['hotline'] ?? '') }}">
                    </div>
                    <div class="admin-settings-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="settings[email]" value="{{ old('settings.email', $settings['email'] ?? '') }}">
                    </div>
                </div>
            </section>

            <section class="admin-settings-section" aria-labelledby="settings-social-title">
                <div class="admin-settings-section-heading">
                    <span class="admin-settings-section-mark" aria-hidden="true">03</span>
                    <div>
                        <h2 id="settings-social-title">Kênh mạng xã hội</h2>
                        <p>Đường dẫn đến các trang chính thức của nhà hàng.</p>
                    </div>
                </div>
                <div class="admin-settings-grid">
                    <div class="admin-settings-field">
                        <label for="facebook_url">Facebook URL</label>
                        <input id="facebook_url" type="url" name="settings[facebook_url]" value="{{ old('settings.facebook_url', $settings['facebook_url'] ?? '') }}">
                    </div>
                    <div class="admin-settings-field">
                        <label for="youtube_url">YouTube URL</label>
                        <input id="youtube_url" type="url" name="settings[youtube_url]" value="{{ old('settings.youtube_url', $settings['youtube_url'] ?? '') }}">
                    </div>
                    <div class="admin-settings-field">
                        <label for="zalo_url">Zalo URL</label>
                        <input id="zalo_url" type="url" name="settings[zalo_url]" value="{{ old('settings.zalo_url', $settings['zalo_url'] ?? '') }}">
                    </div>
                </div>
            </section>

            <section class="admin-settings-section" aria-labelledby="settings-footer-title">
                <div class="admin-settings-section-heading">
                    <span class="admin-settings-section-mark" aria-hidden="true">04</span>
                    <div>
                        <h2 id="settings-footer-title">Nội dung chân trang</h2>
                        <p>Nội dung địa chỉ và mô tả được hiển thị ở cuối website.</p>
                    </div>
                </div>
                <div class="admin-settings-grid">
                    <div class="admin-settings-field">
                        <label for="footer_address">Địa chỉ Footer</label>
                        <textarea id="footer_address" name="settings[footer_address]" rows="3">{{ old('settings.footer_address', $settings['footer_address'] ?? '') }}</textarea>
                    </div>
                    <div class="admin-settings-field">
                        <label for="footer_address_en">Địa chỉ Footer (English)</label>
                        <textarea id="footer_address_en" name="settings[footer_address_en]" rows="3">{{ old('settings.footer_address_en', $settings['footer_address_en'] ?? '') }}</textarea>
                    </div>
                    <div class="admin-settings-field">
                        <label for="footer_description">Nội dung Footer</label>
                        <textarea id="footer_description" name="settings[footer_description]" rows="5">{{ old('settings.footer_description', $settings['footer_description'] ?? '') }}</textarea>
                    </div>
                    <div class="admin-settings-field">
                        <label for="footer_description_en">Nội dung Footer (English)</label>
                        <textarea id="footer_description_en" name="settings[footer_description_en]" rows="5">{{ old('settings.footer_description_en', $settings['footer_description_en'] ?? '') }}</textarea>
                    </div>
                </div>
            </section>

            <footer class="admin-settings-actions">
                <span class="admin-settings-save-note">
                    <svg viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                        <path d="M10 2.5 18 17H2L10 2.5Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.6" />
                        <path d="M10 7v4.5m0 2.2v.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8" />
                    </svg>
                    Nhớ lưu thay đổi trước khi rời trang.
                </span>
                <button type="submit">Lưu cài đặt</button>
            </footer>
        </form>
    </div>
@endsection
