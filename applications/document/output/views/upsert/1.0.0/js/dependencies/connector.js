(function (window) {

	'use strict';

	class Preview {

		static remove() {
			return 'delete_forever';
		}
		static add() {
			return 'add';
		}
		static template() {
			return {
				branding: false,
				height: '500',
				plugins: 'print preview powerpaste casechange importcss searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap quickbars emoticons advtable export',
				toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist checklist | forecolor backcolor casechange formatpainter removeformat',
				extended_valid_elements: '*[data-*],script[language|type|src],style',
				custom_elements: 'style'
			};
		}

		constructor(connector) {
			this.connector = connector;
			this.elements = {};
		}
		getConnector() {
			return this.connector;
		}
		getTextarea() {
			if (this.elements.hasOwnProperty('textarea')) return this.elements.textarea;
			this.elements.textarea = document.createElement('textarea');
			this.elements.textarea.className = 'tiny';
			return this.elements.textarea;
		}
		setTextarea(value) {
			let textarea = this.getTextarea(),
				preview = this.getPreview();

			textarea.value = value;
			preview.innerHTML = value;
			return this;
		}
		getPreview() {
			if (this.elements.hasOwnProperty('preview')) return this.elements.preview;
			this.elements.preview = document.createElement('p');
			this.elements.preview.className = 'preview';
			this.elements.preview.setAttribute(Connector.handle(), ':tiny');
			this.elements.preview.addEventListener('click', this, false);
			return this.elements.preview;
		}
		getWrapper() {
			if (this.elements.hasOwnProperty('wrapper')) return this.elements.wrapper;
			let add = this.getAdd();
			this.elements.wrapper = document.createElement('p');
			this.elements.wrapper.className = 'wrapper add';
			this.elements.wrapper.appendChild(add);
			return this.elements.wrapper;
		}
		getAdd() {
			if (this.elements.hasOwnProperty('add')) return this.elements.add;
			this.elements.add = document.createElement('button');
			this.elements.add.appendChild(Connector.getIcon(this.constructor.add()));
			this.elements.add.setAttribute(Connector.handle(), ':add');
			this.elements.add.addEventListener('click', this, false);
			return this.elements.add;
		}
		getRemove() {
			if (this.elements.hasOwnProperty('remove')) return this.elements.remove;
			this.elements.remove = Connector.getIcon(this.constructor.remove());
			this.elements.remove.classList.add('remove');
			this.elements.remove.setAttribute(Connector.handle(), ':remove');
			this.elements.remove.addEventListener('click', this, false);
			return this.elements.remove;
		}
		getEventArea() {
			if (this.elements.hasOwnProperty('event')) return this.elements.event;
			let add = this.getWrapper(),
				remove = this.getRemove(),
				preview = this.getPreview();

			this.elements.event = document.createElement('article');
			this.elements.event.className = 'event';
			this.elements.event.appendChild(preview);
			this.elements.event.appendChild(remove);
			this.elements.event.appendChild(add);

			return this.elements.event;
		}
		tiny() {
			let connector = this.getConnector(),
				textarea = this.getTextarea(),
				modal = connector.getModal();

			modal.setContent(textarea);
			modal.setActionHide(function () {
				tinymce.get(textarea.id).destroy();
			});

			this.constructor.initialize(connector.getTinyMCE(), textarea);
			modal.show();
		}
		add() {
			this.getConnector().add(this);
		}
		remove() {
			let connector = this.getConnector(),
				index = connector.findIndex(this);

			if (index === null) return;

			Connector.removeElementDOM(this.getEventArea());
			connector.getPreview().splice(index, 1);
		}
		handleEvent(event) {
			let attribute = Connector.closestAttribute(event.target, Connector.handle());
			if (attribute === null) return;

			let attribute_split = attribute.split(/\s+/);
			for (let item = 0; item < attribute_split.length; item++) {
				let execute = attribute_split[item].split(String.fromCharCode(58));
				if (execute.length !== 2) break;
				if (execute[0] === event.type || 0 === execute[0].length) {
					if (typeof this[execute[1]] !== 'function') continue;

					this[execute[1]].call(this, event);
				}
			}
		}
		static initialize(tiny, target, events) {
			let template = this.template();
			if (typeof events === 'function') template.setup = events;

			template.target = target;
			tiny.init(template);
		}
	}

	class Connector {

		static handle() {
			return 'data-handle-event';
		}

		constructor(modal, tinymce) {
			this.modal = modal;
			this.tinymce = tinymce;
			this.elements = {};
			this.elements.preview = [];

			this.add();
		}

		getModal() {
			return this.modal;
		}
		getTinyMCE() {
			return this.tinymce;
		}
		getPreview() {
			return this.elements.preview;
		}
		findIndex(preview) {
			if (false === (preview instanceof Preview)) return null;
			let container = this.getPreview();
			for (let item = 0; item < container.length; item++)
				if (preview === container[item])
					return item;
			return null;
		}
		getContent() {
			if (this.elements.hasOwnProperty('content')) return this.elements.content;
			this.elements.content = document.createElement('div');
			this.elements.content.className = 'connector';
			return this.elements.content;
		}
		out() {
			return this.getContent();
		}
		add(element) {
			let preview = new Preview(this), index = this.findIndex(element), position = null;
			this.getPreview().splice(index, 0, preview);
			if (null !== index) {
				position = element.getEventArea();
				position = position.nextSibling;
			}
			this.getContent().insertBefore(preview.getEventArea(), position);
			return preview;
		}
		drop() {
			let container = this.getPreview();
			for (let item = 0; item < container.length; item++) this.constructor.removeElementDOM(container[item].getEventArea());
			this.elements.preview = [];
			return this;
		}
		set(matrix) {
			this.drop();
			for (let item = 0; item < matrix.length; item++) this.add().setTextarea(matrix[item]);
			return this;
		}
		handleEvent(event) {
			let attribute = this.constructor.closestAttribute(event.target, this.constructor.handle());
			if (attribute === null) return;

			let attribute_split = attribute.split(/\s+/);
			for (let item = 0; item < attribute_split.length; item++) {
				let execute = attribute_split[item].split(String.fromCharCode(58));
				if (execute.length !== 2) break;
				if (execute[0] === event.type || 0 === execute[0].length) {
					if (typeof this[execute[1]] !== 'function') continue;

					this[execute[1]].call(this, event);
				}
			}
		}
		static closestAttribute(target, attribute, html) {
			if (typeof attribute === 'undefined'
				|| !attribute.length) return null;

			let result = null, element = target;

			do {
				let tagname = element.tagName.toLowerCase();
				if (tagname === 'body') return null;

				result = element.getAttribute(attribute);
				if (result !== null) {
					result = result.toString();
					if (result.length) break;
				}

				element = element.parentNode;
			} while (element !== null
				|| typeof element === 'undefined');

			if (typeof html === 'undefined'
				|| html !== true) return result;

			return element;
		}
		static getIcon(material) {
			let icon = document.createElement('i');
			icon.className = 'material-icons';
			icon.innerText = material;
			return icon;
		}
		static removeElementDOM(element) {
			let parent = element === null || typeof element === 'undefined' || typeof element.parentNode === 'undefined' ? null : element.parentNode;
			if (parent === null) return false;
			parent.removeChild(element);
			return true;
		}
	};

	window.Connector = Connector;
	window.Connector.Preview = Preview;

})(window);
