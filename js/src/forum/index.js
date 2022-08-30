import ChangeEmailModal from "./components/ChangeEmailModal";
import MinecraftLogInModal from "./components/MinecraftLogInModal";
import app from "flarum/app";
import Button from "flarum/common/components/Button";
import { extend } from "flarum/common/extend";
import LogInButtons from "flarum/forum/components/LogInButtons";
import SettingsPage from "flarum/forum/components/SettingsPage";

app.initializers.add("nearata-auth-minecraft", () => {
    extend(LogInButtons.prototype, "items", function (items) {
        items.add(
            "authMinecraft",
            m(
                Button,
                {
                    className: "Button LogInButton LogInButton--minecraft",
                    onclick: () => app.modal.show(MinecraftLogInModal),
                },
                app.translator.trans(
                    "nearata-auth-minecraft.forum.log_in_button_title"
                )
            )
        );
    });

    extend(SettingsPage.prototype, "accountItems", function (items) {
        const user = this.user;

        if (user.email().endsWith("auth-minecraft.net")) {
            items.replace(
                "changeEmail",
                m(
                    Button,
                    {
                        className: "Button",
                        onclick: () =>
                            app.modal.show(ChangeEmailModal, { user }),
                    },
                    app.translator.trans(
                        "nearata-auth-minecraft.forum.settings.change_email_button"
                    )
                )
            );
        }
    });
});
