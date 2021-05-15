import Button from 'flarum/common/components/Button';
import Modal from 'flarum/common/components/Modal';
import Stream from 'flarum/common/utils/Stream';

const trans = key => {
    return app.translator.trans(`nearata-auth-minecraft.forum.${key}`);
}

export default class MinecraftLogInModal extends Modal {
    oninit(vnode) {
        super.oninit(vnode);

        this.email = Stream();
        this.token = Stream();
    }

    className() {
        return 'MinecraftLogInModal Modal--small';
    }

    title() {
        return trans('modal_title')
    }

    content() {
        return [
            m('.Modal-body', [
                m('.Form.Form--centered', [
                    m('.Form-group', [
                        m('', trans('log_in_help'))
                    ]),
                    m('.Form-group', [
                        m('input.FormControl', {
                            bidi: this.email,
                            placeholder: trans('email_placeholder'),
                            name: 'email',
                            type: 'text',
                            autocomplete: 'off'
                        })
                    ]),
                    m('.Form-group', [
                        m('input.FormControl', {
                            bidi: this.token,
                            placeholder: trans('token_placeholder'),
                            name: 'token',
                            type: 'text',
                            autocomplete: 'off'
                        })
                    ]),
                    m('.Form-group', [
                        m(Button, {
                            type: 'submit',
                            className: 'Button Button--primary Button--block',
                            disabled: this.loading,
                            loading: this.loading
                        }, trans('submit_button'))
                    ])
                ])
            ]),
            m('.Modal-footer', [
                m('span', [
                    'Powered by ',
                    m('a', {
                        href: 'https://mc-oauth.net/',
                        target:'_blank'
                    }, 'Minecraft oAuth')
                ])
            ])
        ];
    }

    onsubmit(e) {
        e.preventDefault();

        this.loading = true;

        app.request({
            url: `${app.forum.attribute('apiUrl')}/auth/minecraft`,
            method: 'POST',
            body: { email: this.email(), token: this.token() },
            errorHandler: this.onerror.bind(this)
        })
        .then(response => {
            app.authenticationComplete(response);
            if ('loggedIn' in response) {
                window.location.reload;
            }
        })
        .catch(() => {})
        .then(this.loaded.bind(this));
    }

    onerror(error) {
        if (error.status === 403) {
            error.alert.content = app.translator.trans('nearata-auth-minecraft.api.error_403');
        }

        super.onerror(error);
    }
}
