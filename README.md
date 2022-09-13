# Minecraft Auth

> A [Flarum](http://flarum.org) extension. Allow users to login with Minecraft.

## Requirements

You need a Minecraft server with [OAuth](https://github.com/Neapovil/oauth) plugin installed.

## Install

```sh
composer require nearata/flarum-ext-auth-minecraft
```

## Remove

Disable the extension, click uninstall and run these commands:

```sh
composer remove nearata/flarum-ext-auth-minecraft
php flarum cache:clear
```

## How to use

You need to fill all the fields in Minecraft Auth admin settings page.

### Minecraft Server IP

The Minecraft server IP that is using the OAuth plugin.

### API URL

If for example you are running Flarum and the Minecraft Server on the same machine, you are free to use `http://localhost:4567/verify`. If the Minecraft Server is on a different machine, you need to update `localhost` with the public IP of that machine.

### API Secret

The API secret can be found in the `Plugins/OAuth/config.json` file.

## Links

- [Packagist](https://packagist.org/packages/nearata/flarum-ext-auth-minecraft)
