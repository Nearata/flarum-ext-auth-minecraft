import Button from "flarum/common/components/Button";
import Modal from "flarum/common/components/Modal";
import Stream from "flarum/common/utils/Stream";

const trans = (key, options = {}) => {
    return app.translator.trans(
        `nearata-auth-minecraft.forum.change_email_modal.${key}`,
        options
    );
};

export default class ChangeEmailModal extends Modal {
    oninit(vnode) {
        super.oninit(vnode);

        this.user = this.attrs.user;

        this.success = false;
        this.email = Stream(this.user.email());
        this.token = Stream();
    }

    className() {
        return "ChangeEmailModal Modal--small";
    }

    title() {
        return trans("title");
    }

    content() {
        if (this.success) {
            return [
                m(".Modal-body", [
                    m(".Form.Form--centered", [
                        m("p.helpText", [
                            trans("confirmation_message", {
                                email: m("strong", this.email()),
                            }),
                        ]),
                        m(".Form-group", [
                            m(
                                Button,
                                {
                                    className:
                                        "Button Button--primary Button--block",
                                    onclick: this.hide.bind(this),
                                },
                                trans("dismiss_button")
                            ),
                        ]),
                    ]),
                ]),
            ];
        }

        return [
            m(".Modal-body", [
                m(".Form.Form--centered", [
                    m(
                        "p.helpText",
                        app.translator.trans(
                            "nearata-auth-minecraft.forum.token_help"
                        )
                    ),
                    m(".Form-group", [
                        m("input", {
                            className: "FormControl",
                            type: "email",
                            name: "email",
                            placeholder: this.user.email(),
                            bidi: this.email,
                            disabled: this.loading,
                            autocomplete: "off",
                        }),
                    ]),
                    m(".Form-group", [
                        m("input", {
                            className: "FormControl",
                            type: "text",
                            name: "token",
                            placeholder: trans("token_placeholder"),
                            bidi: this.token,
                            disabled: this.loading,
                            autocomplete: "off",
                        }),
                    ]),
                    m(".Form-group", [
                        m(
                            Button,
                            {
                                className:
                                    "Button Button--primary Button--block",
                                type: "submit",
                                loading: this.loading,
                            },
                            trans("submit_button")
                        ),
                    ]),
                ]),
            ]),
        ];
    }

    onsubmit(e) {
        e.preventDefault();

        if (this.email() === this.user.email()) {
            this.hide();
            return;
        }

        this.loading = true;
        this.alertAttrs = null;

        app.request({
            url: `${app.forum.attribute("apiUrl")}/minecraft/changeEmail`,
            method: "POST",
            body: { email: this.email(), token: this.token },
            errorHandler: this.onerror.bind(this),
        })
            .then(() => {
                this.success = true;
            })
            .catch(() => {})
            .then(this.loaded.bind(this));
    }

    onerror(error) {
        if (error.status === 403) {
            error.alert.content = app.translator.trans(
                "nearata-auth-minecraft.api.error_403"
            );
        }

        super.onerror(error);
    }
}
