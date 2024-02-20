import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets= ['input', 'placeholder', 'preview', 'previewClearButton', 'previewFilename', 'previewImage'];

    connect() {
        this.clear();
        this.previewClearButtonTarget.addEventListener('click', () => this.clear());
        this.inputTarget.addEventListener('change', (event) => this.onInputChange(event));
        this.dispatchEvent('connect');
    }
    clear() {
        this.inputTarget.value = '';
        this.inputTarget.style.display = 'block';
        this.placeholderTarget.style.display = 'block';
        this.previewTarget.style.display = 'none';
        this.previewImageTarget.style.display = 'none';
        this.previewImageTarget.style.backgroundImage = 'none';
        this.previewFilenameTarget.textContent = '';
        document.querySelectorAll('.dropzonemultiple-preview-image-container').forEach((e) => e.remove());
        this.dispatchEvent('clear');
    }
    onInputChange(event) {
        for (var fileItem in event.target.files) {
            var file = event.target.files[fileItem];
            if (typeof file === 'undefined') {
                return;
            }
            this.inputTarget.style.display = 'none';
            this.placeholderTarget.style.display = 'none';
            // this.previewFilenameTarget.textContent = file.name;
            this.previewTarget.style.display = 'flex';
            this.previewImageTarget.style.display = 'none';
            if (file.type && file.type.indexOf('image') !== -1) {
                this._populateImagePreview(file);
            }
            this.dispatchEvent('change', file);
        }
    }
    _populateImagePreview(file) {
        if (typeof FileReader === 'undefined') {
            // FileReader API not available, skip
            return;
        }

        const reader = new FileReader();

        reader.addEventListener('load', (event) => {
            var parentDiv = document.createElement("div");
            parentDiv.classList.add('dropzonemultiple-preview-image-container')

            var divPreview = document.createElement("div");
            divPreview.classList.add('dropzonemultiple-preview-image')
            divPreview.style.backgroundImage = 'url("' + event.target.result + '")'

            var divFileName = document.createElement("div");
            divFileName.textContent = file.name


            parentDiv.appendChild(divPreview);
            parentDiv.appendChild(divFileName);

            this.previewImageTarget.parentNode.appendChild(parentDiv);
        });

        reader.readAsDataURL(file);
    }
    // dispatchEvent(name, payload = {}) {
    //     this.dispatch(name, { detail: payload, prefix: 'dropzonemultiple' });
    // }
    dispatchEvent(name, payload = null, canBubble = false, cancelable = false) {
        const userEvent = document.createEvent('CustomEvent');
        userEvent.initCustomEvent(name, canBubble, cancelable, payload);

        this.element.dispatchEvent(userEvent);
    }
}