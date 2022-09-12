import MinecraftLogInModal from "./components/MinecraftLogInModal";
import { extend } from "flarum/common/extend";
import app from "flarum/forum/app";
import LogInButtons from "flarum/forum/components/LogInButtons";
import SettingsPage from "flarum/forum/components/SettingsPage";

app.initializers.add("nearata-auth-minecraft", () => {
    extend(LogInButtons.prototype, "items", function (items) {
        const base = app.forum.attribute("baseUrl");

        const button = m(
            "button",
            {
                class: "Button LogInButton LogInButton--minecraft hasIcon",
                type: "button",
                onclick: () => app.modal.show(MinecraftLogInModal),
            },
            [
                m("img", {
                    class: "icon Button-icon",
                    src: `${base}/assets/extensions/nearata-auth-minecraft/minecraft-icon.png`,
                }),
                m("span.Button-label", [
                    app.translator.trans(
                        "nearata-auth-minecraft.forum.log_in_button_title"
                    ),
                ]),
            ]
        );

        items.add("nearataAuthMinecraft", button);
    });

    extend(SettingsPage.prototype, "accountItems", function (items) {
        const user = this.user;

        if (user.attribute("nearataAuthMinecraftFromServer")) {
            items.remove("changeEmail");
        }
    });
});
