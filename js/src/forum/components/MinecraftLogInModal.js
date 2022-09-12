import Button from "flarum/common/components/Button";
import Modal from "flarum/common/components/Modal";
import app from "flarum/forum/app";

const trans = (key, options = {}) => {
    return app.translator.trans(`nearata-auth-minecraft.forum.${key}`, options);
};

export default class MinecraftLogInModal extends Modal {
    oninit(vnode) {
        super.oninit(vnode);
    }

    className() {
        return "MinecraftLogInModal Modal--small";
    }

    title() {
        return trans("log_in_modal.title");
    }

    content() {
        return [
            m(".Modal-body", [
                m(".Form.Form--centered", [
                    m(
                        "p.helpText",
                        trans("modal_help", {
                            server: app.forum.attribute(
                                "nearataMinecraftServerIp"
                            ),
                        })
                    ),
                    m(".Form-group", [
                        m(
                            Button,
                            {
                                type: "submit",
                                className:
                                    "Button Button--primary Button--block",
                                loading: this.loading,
                            },
                            trans("log_in_modal.submit_button")
                        ),
                    ]),
                ]),
            ]),
        ];
    }

    onsubmit(e) {
        e.preventDefault();

        this.loading = true;

        app.request({
            url: `${app.forum.attribute("apiUrl")}/auth/minecraft`,
            method: "POST",
            errorHandler: this.onerror.bind(this),
        })
            .then((response) => {
                app.authenticationComplete(response);
            })
            .catch(() => {})
            .then(this.loaded.bind(this));
    }

    onerror(error) {
        if (error.status === 400) {
            error.alert.content = trans("login_error");
        }

        super.onerror(error);
    }
}
