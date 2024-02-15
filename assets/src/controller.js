/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

import { Controller } from 'stimulus';

class default_1 extends Controller {
	static targets = ['input', 'placeholder', 'preview', 'previewClearButton', 'previewFilename', 'previewImage'];

	connect() {
		console.log('connected ux-dropzonemultiple-controller.js')
		// Reset when connecting to work with Turbolinks
		this.clear();

		// Clear on click on clear button
		this.previewClearButtonTarget.addEventListener('click', () => this.clear());

		// Listen on input change and display preview
		this.inputTarget.addEventListener('change', (event) => this.onInputChange(event));

		this._dispatchEvent('dropzonemultiple:connect');
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

		this._dispatchEvent('dropzonemultiple:clear');
	}

	onInputChange(event) {
		for (var fileItem in event.target.files) {
			var file = event.target.files[fileItem];
			if (typeof file === 'undefined') {
				return;
			}

			// Hide the input and placeholder
			this.inputTarget.style.display = 'none';
			this.placeholderTarget.style.display = 'none';

			this.previewTarget.style.display = 'flex';

			// If the file is an image, load it and display it as preview
			this.previewImageTarget.style.display = 'none';
			if (file.type && file.type.indexOf('image') !== -1) {
				this._populateImagePreview(file);
			}

			this._dispatchEvent('dropzonemultiple:change', event.target.files);
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

	_dispatchEvent(name, payload = null, canBubble = false, cancelable = false) {
		const userEvent = document.createEvent('CustomEvent');
		userEvent.initCustomEvent(name, canBubble, cancelable, payload);

		this.element.dispatchEvent(userEvent);
	}
}