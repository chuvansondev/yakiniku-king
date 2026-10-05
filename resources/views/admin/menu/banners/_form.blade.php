<div style="margin-bottom:15px;">

    <label>
        Tiêu đề
    </label>

    <br>

    <input
        type="text"
        name="title"
        value="{{ old('title', $banner->title ?? '') }}"
        style="width:100%; padding:10px;"
    >

</div>

@include('admin.menu.partials.english-field', [
    'name' => 'title_en',
    'label' => 'Tiêu đề',
    'value' => $banner->title_en ?? '',
])


<div style="margin-bottom:15px;">

    <label>
        Loại Banner
    </label>

    <br>

    <select
        name="type"
        id="banner-type"
        style="width:100%; padding:10px;"
    >

        <option
            value="image"
            {{ old('type', $banner->type ?? \App\Enums\BannerType::Image->value) === \App\Enums\BannerType::Image->value ? 'selected' : '' }}
        >
            Hình ảnh
        </option>

        <option
            value="video"
            {{ old('type', $banner->type ?? '') === \App\Enums\BannerType::Video->value ? 'selected' : '' }}
        >
            Video
        </option>

    </select>

</div>


<div
    id="image-field"
    data-image-field
    style="margin-bottom:15px;"
>

    <label>
        Hình ảnh
    </label>

    <br>

    <input
        type="file"
        name="image"
        accept=".jpg,.jpeg,.png,.webp"
    >


    @if(isset($banner) && $banner->image)

        <input type="hidden" name="remove_image" value="0" data-image-remove-value>
        <div data-current-image-preview style="margin-top:10px;">

            <p>Ảnh hiện tại:</p>

            <img
                src="{{ asset('storage/' . $banner->image) }}"
                style="
                    width:300px;
                    max-height:180px;
                    object-fit:cover;
                "
            >

        </div>

        <button class="admin-image-remove-button" type="button" data-image-remove-toggle aria-pressed="false">Xóa ảnh hiện tại</button>

    @endif

</div>


<div
    id="video-field"
    style="margin-bottom:15px;"
>

    <label>
        Video URL
    </label>

    <br>

    <input
        type="url"
        name="video_url"
        value="{{ old('video_url', $banner->video_url ?? '') }}"
        placeholder="https://www.youtube.com/..."
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:15px;">

    <label>
        Link khi click Banner
    </label>

    <br>

    <input
        type="text"
        name="link"
        value="{{ old('link', $banner->link ?? '') }}"
        placeholder="https://example.com"
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:15px;">

    <label>
        Thứ tự hiển thị
    </label>

    <br>

    <input
        type="number"
        name="sort_order"
        value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
        min="0"
        style="width:100%; padding:10px;"
    >

</div>


<div style="margin-bottom:15px;">

    <label>

        <input
            type="checkbox"
            name="status"
            value="1"
            {{ old('status', $banner->status ?? true) ? 'checked' : '' }}
        >

        Hiển thị Banner

    </label>

</div>


<script>

    function toggleBannerFields() {

        const type = document.getElementById('banner-type').value;

        const imageField = document.getElementById('image-field');

        const videoField = document.getElementById('video-field');


        if (type === @json(\App\Enums\BannerType::Image->value)) {

            imageField.style.display = 'block';

            videoField.style.display = 'none';

        } else {

            imageField.style.display = 'none';

            videoField.style.display = 'block';

        }

    }


    document
        .getElementById('banner-type')
        .addEventListener('change', toggleBannerFields);


    toggleBannerFields();

</script>
