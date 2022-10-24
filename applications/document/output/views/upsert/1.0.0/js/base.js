(function (window) {

    'use strict';

    let pathname = window.location.pathname.split(String.fromCharCode(47)), widgets = window.page.getWidgets();
    window.reference = pathname.slice(4);

    window.elements = {};
    window.elements.content = document.createElement('div');
    window.elements.content.id = 'content';

    window.elements.main = document.createElement('div');
    window.elements.main.id = 'main';
    window.page.addHTMLElement(window.elements.main);

    window.elements.wrapper = document.createElement('div');
    window.elements.wrapper.id = 'wrapper';
    window.elements.main.appendChild(window.elements.wrapper);

    window.elements.grid = document.createElement('div');
    window.elements.grid.className = 'pure-u-22-24 pure-u-lg-16-24 resize';
    window.elements.grid.appendChild(window.elements.content);

    window.elements.row = document.createElement('div');
    window.elements.row.className = 'pure-g';
    window.elements.row.appendChild(window.elements.grid);

    window.elements.wrapper.appendChild(window.elements.row);

    widgets.header = new Header();
    widgets.header.setUrl(window.page.iam);
    widgets.header.setTitle(window.page.getTranslate('header.app_name'));

    let profile = widgets.header.getProfile(), burger = profile.getMenu();
    profile.setUsername(window.page.user.email);
    profile.setImage(window.page.user.picture);

    if (window.page.checkPolicy('iam/user/view/upsert') && window.page.checkPolicy('iam/user/action/update/me')) {
        let account_label = window.page.getTranslate('header.buttons.my_account'), account = burger.addItem(account_label, 'account_circle');
        account.href = window.page.iam + 'iam/user/upsert/' + window.page.user._key;
    }

    let logout = window.page.getTranslate('header.buttons.logout');
    burger.addItem(logout, 'exit_to_app', function () {
        let xhr = new WXmlHttpRequest(),
            api = '/api/sso/user/gateway/iam/iam/user/logout'
                + String.fromCharCode(63)
                + 'timestamp'
                + String.fromCharCode(61)
                + Date.now();
        xhr.setRequestUrl(api);
        xhr.setCallbackSuccess(function (response) {
            if (response.hasOwnProperty('return_url')) document.location.href = response.return_url;
        });
        xhr.request();
    });

    window.page.addHTMLElement(widgets.header.out());

    let menu = '/api/sso/user/menu'
        + String.fromCharCode(63)
        + 'timestamp'
        + String.fromCharCode(61)
        + Date.now()
    widgets.menu = new Menu();
    widgets.menu.setNearElement(window.elements.main);
    widgets.menu.setRequestUrl(menu);
    widgets.menu.setNavigator(window.page.getNavigator().join('/'));
    widgets.menu.request(function (response) {
        if (response.hasOwnProperty('header')) this.setHeader(response.header);
        if (false === response.hasOwnProperty('data')) return;
        this.pushModules(response.data);

        let pathname = window.location.pathname.split(/[\\\/]/);
        if (pathname.hasOwnProperty(2)) {
            let list = this.getList();
            for (let item = 0; item < list.length; item++) {
                let href = list[item].out().getAttribute('href');
                if (href === null) continue;

                let split = href.split(/[\\\/]/);
                if (split.hasOwnProperty(2)
                    && pathname[2] === split[2]) list[item].out().classList.add('active');
            }
        }
    });

    window.page.addHTMLElement(widgets.menu.out());

    let title = window.reference.length === 0 ? window.page.getTranslate('nav.add') : window.page.getTranslate('nav.edit'),
        back = '/document/output/read';
    widgets.nav = new Nav();
    widgets.nav.setBack(back);
    widgets.nav.setReturnButton('arrow_back');
    widgets.nav.setTitle(title);

    window.elements.main.appendChild(widgets.nav.out());

    widgets.modal = new window.Page.Widget.Organizer();

    let insert_api = '/api/document/output/insert'
        + String.fromCharCode(63)
        + 'timestamp'
        + String.fromCharCode(61)
        + Date.now();
    widgets.form = new Form();
    widgets.form.setRequestUrl(insert_api);
    for (let item = 0; item < window.page.tables.project.fields.length; item++) widgets.form.addInput(window.page.tables.project.fields[item]);

    widgets.tabs = new Tabs();
    widgets.tabs.name = 'data-tab-name';
    widgets.tabs.setEventShow(function (ev) {
        let name = Tabs.closestAttribute(ev.target, widgets.tabs.name);
        if (name === null) return;
    });

    window.elements.content.appendChild(widgets.tabs.out());

    let form_project = widgets.form.out(),
        form_project_name = window.page.getTranslate('tabs.project');
    widgets.tabs.addItem(form_project_name, form_project, 'material-icons settings').show().out();

    window.elements.content.appendChild(form_project);

    let form_dependencies = widgets.form.findContainer('project_dependencies'),
        form_dependencies_name = window.page.getTranslate('tabs.' + form_dependencies.getMatrixName());
    widgets.tabs.addItem(form_dependencies_name, form_dependencies.out(), 'material-icons share').out();

    Form.removeElementDOM(form_dependencies.getRow().out());

    window.elements.content.appendChild(form_dependencies.out());

    let form_editor = widgets.form.findContainer('project_hyper_text_markup_language'),
        form_editor_name = window.page.getTranslate('tabs.' + form_editor.getMatrixName());

    form_editor.out().classList.add('hide');

    widgets.modal.html = new Modal();
    widgets.modal.html.setSize(16);
    widgets.modal.html.setTitle(window.page.getTranslate('modal.tiny'));

    widgets.modal.html.container_html = document.createElement('div');
    widgets.modal.html.container_html.className = 'buttons-form';

    window.elements.content.appendChild(widgets.modal.html.out());


    widgets.connector = new Connector(widgets.modal.html, window.tinymce);
    widgets.tabs.addItem(form_editor_name, widgets.connector.out(), 'material-icons edit').out();

    widgets.modal.html.buttons = {};
    widgets.modal.html.buttons.save = new Button();
    widgets.modal.html.buttons.save.addStyle('flat');
    widgets.modal.html.buttons.save.setText(window.page.getTranslate('buttons.save'));
    widgets.modal.html.buttons.save.onClick(function () {
        let matrix = tinymce.activeEditor.getElement(),
            preview = widgets.connector.getPreview();

        matrix.value = tinymce.activeEditor.getContent();
        for (let item = 0; item < preview.length; item++) {
            let example = preview[item].getPreview(),
                textarea = preview[item].getTextarea();

            example.innerHTML = textarea.value;
        }

        form_editor.getPlugin().reset();
        widgets.modal.html.hide();
    });

    widgets.modal.html.buttons.cancel = new Button();
    widgets.modal.html.buttons.cancel.addStyle('flat red');
    widgets.modal.html.buttons.cancel.setText(window.page.getTranslate('buttons.cancel'));
    widgets.modal.html.buttons.cancel.onClick(function () {
        widgets.modal.html.hide();
    });

    widgets.modal.html.container_html.appendChild(widgets.modal.html.buttons.save.out());
    widgets.modal.html.container_html.appendChild(widgets.modal.html.buttons.cancel.out());

    widgets.modal.html.setBottom(widgets.modal.html.container_html);

    window.elements.content.appendChild(widgets.connector.out());

    let form_header_name = window.page.getTranslate('tabs.header'),
        form_header = widgets.form.getRow('header').getEncapsulate();
    widgets.tabs.addItem(form_header_name, form_header, 'material-icons title').out();
    window.elements.content.appendChild(form_header);

    let form_footer_name = window.page.getTranslate('tabs.footer'),
        form_footer = widgets.form.getRow('footer').getEncapsulate();
    widgets.tabs.addItem(form_footer_name, form_footer, 'material-icons horizontal_split').out();
    window.elements.content.appendChild(form_footer);

    let form_editor_css = widgets.form.findContainer('project_cascade_style_sheet'),
        form_editor_css_name = window.page.getTranslate('tabs.' + form_editor_css.getMatrixName());
    widgets.tabs.addItem(form_editor_css_name, form_editor_css.getRow().getEncapsulate(), 'material-icons format_paint').out();
    window.elements.content.appendChild(form_editor_css.getRow().getEncapsulate());

    let form_editor_js = widgets.form.findContainer('project_javascript'),
        form_editor_js_name = window.page.getTranslate('tabs.' + form_editor_js.getMatrixName());
    widgets.tabs.addItem(form_editor_js_name, form_editor_js.getRow().getEncapsulate(), 'material-icons code').out();
    window.elements.content.appendChild(form_editor_js.getRow().getEncapsulate());

    let buttons_form = document.createElement('div');
    buttons_form.className = 'buttons-form';
    window.elements.content.appendChild(buttons_form);

    let submit = new Button(),
        icon = window.reference.length === 0
            ? 'add'
            : 'save';

    submit.getIcon().set(icon);
    submit.setText(window.reference.length === 0 ? window.page.getTranslate('button.add') : window.page.getTranslate('button.save'));
    submit.onClick(function () {
        this.getLoader().apply(window.page.getTranslate('button.loader'));

        let preview = widgets.connector.getPreview(),
            edited = [];

        for (let item = 0; item < preview.length; item++) {
            let textarea = preview[item].getTextarea();
            edited.push({
                project_hyper_text_markup_language_text: textarea.value
            });
        }

        form_editor.getPlugin().reset();
        widgets.form.set('project_hyper_text_markup_language', edited.reverse());
        widgets.form.request(function () {
            submit.getLoader().remove();
        });
    });

    buttons_form.appendChild(submit.out());

    let clone = Page.getUrlParameter('clone');
    if (typeof clone !== 'undefined') window.reference.push(clone);

    if (window.reference.length === 0) return;

    if (typeof clone === 'undefined') {
        let update_api = '/api/document/output/update'
            + String.fromCharCode(47)
            + encodeURIComponent(window.reference[0])
            + String.fromCharCode(63)
            + 'timestamp'
            + String.fromCharCode(61)
            + Date.now();
        widgets.form.setRequestUrl(update_api);
        widgets.form.getManager().show();

        let duplicate = new Button();

        duplicate.addStyle('flat green');
        duplicate.getIcon().set('content_copy');
        duplicate.setText(window.page.getTranslate('button.duplicate'));
        duplicate.onClick(function () {
            window.location = '/document/output/upsert?clone'
                + String.fromCharCode(61)
                + window.reference[0];
        });

        buttons_form.insertBefore(duplicate.out(), submit.out());
    }

    let xhr = new WXmlHttpRequest(),
        api = '/api/document/output/detail'
            + String.fromCharCode(47)
            + encodeURIComponent(window.reference[0])
            + String.fromCharCode(63)
            + 'timestamp'
            + String.fromCharCode(61)
            + Date.now();
    xhr.setRequestUrl(api);
    xhr.setCallbackSuccess(function (response) {
        for (let item in response.data) switch (item) {
            case 'application':
                widgets.form.set('id_project_application', response.data[item]);
                break;
            case 'project_hyper_text_markup_language':
                widgets.form.set(item, response.data[item]);
                let value = response.data[item].map(function (item) {
                    return item.project_hyper_text_markup_language_text;
                });
                widgets.connector.set(value);
                break;
            default:
                widgets.form.set(item, response.data[item]);
        }

        let header = widgets.form.getInput('project_header'),
            footer = widgets.form.getInput('project_footer');

        Connector.Preview.initialize(tinymce, header, function (ed) {
            ed.on('change', function () {
                widgets.form.set('project_header', ed.getContent());
            });
        });
        Connector.Preview.initialize(tinymce, footer, function (ed) {
            ed.on('change', function () {
                widgets.form.set('project_footer', ed.getContent());
            });
        });

        widgets.form.getManager().hide();
    });
    xhr.request();

})(window);
