import app from "flarum/admin/app";

const trans = (key: string) => {
    return app.translator.trans(`nearata-auth-minecraft.admin.settings.${key}`);
}

app.initializers.add("nearata-auth-minecraft", () => {
    app.extensionData
        .for("nearata-auth-minecraft")
        .registerSetting({
            setting: "nearata-auth-minecraft.server-ip",
            type: "text",
            label: trans("minecraft_server.label"),
            help: trans("minecraft_server.help")
        })
        .registerSetting({
            setting: "nearata-auth-minecraft.api-url",
            type: "url",
            label: trans("api_url.label"),
            help: trans("api_url.help")
        })
        .registerSetting({
            setting: "nearata-auth-minecraft.api-secret",
            type: "password",
            label: trans("api_secret.label"),
            help: trans("api_secret.help")
        });
});
