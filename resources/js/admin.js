import { createApp } from 'vue/dist/vue.esm-bundler.js';

const adminShell = document.querySelector('[data-admin-shell]');
const adminMenuToggle = document.querySelector('[data-admin-menu-toggle]');
const adminBackdrop = document.querySelector('[data-admin-backdrop]');

if (adminShell && adminMenuToggle && adminBackdrop) {
    const sidebar = adminShell.querySelector('#admin-sidebar');
    const mobileNavigation = window.matchMedia('(max-width: 960px)');

    const syncAdminMenu = (isOpen = adminShell.classList.contains('admin-sidebar-open')) => {
        const isMobile = mobileNavigation.matches;
        adminShell.classList.toggle('admin-sidebar-open', isMobile && isOpen);
        adminMenuToggle.setAttribute('aria-expanded', String(isMobile && isOpen));

        if (sidebar) {
            sidebar.inert = isMobile && !isOpen;
            sidebar.setAttribute('aria-hidden', String(isMobile && !isOpen));
        }

        adminBackdrop.setAttribute('aria-hidden', String(!isMobile || !isOpen));
    };

    const closeAdminMenu = (restoreFocus = false) => {
        adminShell.classList.remove('admin-sidebar-open');
        syncAdminMenu(false);

        if (restoreFocus && mobileNavigation.matches) adminMenuToggle.focus();
    };

    adminMenuToggle.addEventListener('click', () => {
        syncAdminMenu(!adminShell.classList.contains('admin-sidebar-open'));
    });

    adminBackdrop.addEventListener('click', closeAdminMenu);
    adminShell.querySelectorAll('.admin-nav-link').forEach((link) => {
        link.addEventListener('click', closeAdminMenu);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && adminShell.classList.contains('admin-sidebar-open')) {
            closeAdminMenu(true);
        }
    });

    mobileNavigation.addEventListener('change', () => syncAdminMenu(false));
    syncAdminMenu(false);
}

const ImageUploadField = {
    props: {
        name: { type: String, default: 'image' },
        accept: { type: String, default: '.jpg,.jpeg,.png,.webp' },
        imagePath: { type: String, default: '' },
        storageUrl: { type: String, default: '' },
        alt: { type: String, default: '' },
        label: { type: String, default: 'H\u00ecnh \u1ea3nh' },
        previewStyle: { type: String, default: 'width:300px; max-height:200px; object-fit:cover;' },
        showCaption: { type: Boolean, default: true },
    },
    data: () => ({ removeImage: false, previewUrl: '' }),
    computed: {
        imageUrl() {
            return this.imagePath ? `${this.storageUrl}/${this.imagePath}` : '';
        },
    },
    methods: {
        onFileChange(event) {
            const file = event.target.files?.[0];
            this.removeImage = false;
            if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
            this.previewUrl = file ? URL.createObjectURL(file) : '';
        },
    },
    beforeUnmount() {
        if (this.previewUrl) URL.revokeObjectURL(this.previewUrl);
    },
    template: `
        <label>{{ label }}</label><br>
        <input type="file" :name="name" :accept="accept" @change="onFileChange">
        <template v-if="imagePath">
            <input type="hidden" name="remove_image" :value="removeImage ? '1' : '0'">
            <div class="admin-current-image-preview" :hidden="removeImage">
                <p v-if="showCaption">\u1ea2nh hi\u1ec7n t\u1ea1i:</p>
                <img class="admin-current-image" :src="imageUrl" :alt="alt" :style="previewStyle">
            </div>
            <button class="admin-image-remove-button" type="button" :aria-pressed="removeImage ? 'true' : 'false'" @click="removeImage = !removeImage">
                {{ removeImage ? 'Gi\u1eef \u1ea3nh hi\u1ec7n t\u1ea1i' : 'X\u00f3a \u1ea3nh hi\u1ec7n t\u1ea1i' }}
            </button>
        </template>
        <div v-if="previewUrl" class="admin-current-image-preview">
            <p>Xem tr\u01b0\u1edbc:</p>
            <img class="admin-current-image" :src="previewUrl" alt="\u1ea2nh \u0111\u01b0\u1ee3c ch\u1ecdn" :style="previewStyle">
        </div>
    `,
};

const BannerForm = {
    components: { ImageUploadField },
    props: {
        initialValues: { type: Object, required: true },
        imageType: { type: String, required: true },
        videoType: { type: String, required: true },
        storageUrl: { type: String, required: true },
    },
    data() {
        return { values: { ...this.initialValues } };
    },
    template: `
        <div class="admin-form-field">
            <label for="banner-title">Ti\u00eau \u0111\u1ec1</label>
            <input id="banner-title" class="admin-form-input" type="text" name="title" v-model="values.title">
        </div>
        <div class="admin-form-field">
            <label for="banner-title-en">Ti\u00eau \u0111\u1ec1 (English)</label>
            <input id="banner-title-en" class="admin-form-input" type="text" name="title_en" v-model="values.title_en">
        </div>
        <div class="admin-form-field">
            <label for="banner-type">Lo\u1ea1i banner</label>
            <select id="banner-type" class="admin-form-input" name="type" v-model="values.type">
                <option :value="imageType">H\u00ecnh \u1ea3nh</option>
                <option :value="videoType">Video</option>
            </select>
        </div>
        <div v-show="values.type === imageType" class="admin-form-field" data-image-field>
            <ImageUploadField
                label="H\u00ecnh \u1ea3nh"
                :image-path="values.image"
                :storage-url="storageUrl"
                :alt="values.title"
                preview-style="width:300px; max-height:180px; object-fit:cover;"
            />
        </div>
        <div v-show="values.type === videoType" class="admin-form-field">
            <label for="banner-video-url">Video URL</label>
            <input id="banner-video-url" class="admin-form-input" type="url" name="video_url" v-model="values.video_url" placeholder="https://www.youtube.com/...">
        </div>
        <div class="admin-form-field">
            <label for="banner-link">Link khi click banner</label>
            <input id="banner-link" class="admin-form-input" type="text" name="link" v-model="values.link" placeholder="https://example.com">
        </div>
        <div class="admin-form-field">
            <label for="banner-sort-order">Th\u1ee9 t\u1ef1 hi\u1ec3n th\u1ecb</label>
            <input id="banner-sort-order" class="admin-form-input" type="number" name="sort_order" v-model="values.sort_order" min="0">
        </div>
        <div class="admin-form-field">
            <label>
                <input type="checkbox" name="status" value="1" v-model="values.status">
                Hi\u1ec3n th\u1ecb banner
            </label>
        </div>
    `,
};

const readJsonAttribute = (element, name) => {
    try {
        return JSON.parse(element.dataset[name] ?? '{}');
    } catch {
        return {};
    }
};

document.querySelectorAll('[data-vue-image-field]').forEach((element) => {
    createApp(ImageUploadField, {
        name: element.dataset.name,
        accept: element.dataset.accept,
        imagePath: element.dataset.imagePath,
        storageUrl: element.dataset.storageUrl,
        alt: element.dataset.imageAlt,
        label: element.dataset.label,
        previewStyle: element.dataset.previewStyle,
        showCaption: element.dataset.showCaption === 'true',
    }).mount(element);
});

document.querySelectorAll('[data-vue-banner-form]').forEach((element) => {
    createApp(BannerForm, {
        initialValues: readJsonAttribute(element, 'values'),
        imageType: element.dataset.imageType,
        videoType: element.dataset.videoType,
        storageUrl: element.dataset.storageUrl,
    }).mount(element);
});
