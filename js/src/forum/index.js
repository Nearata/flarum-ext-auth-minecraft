import app from 'flarum/app';
import { extend } from 'flarum/common/extend';
import Button from 'flarum/common/components/Button';
import LogInButtons from 'flarum/forum/components/LogInButtons';

import MinecraftLogInModal from './components/MinecraftLogInModal';

app.initializers.add('nearata-auth-minecraft', () => {
    extend(LogInButtons.prototype, 'items', function (items) {
        items.add(
            'authMinecraft',
            m(Button, {
                className: 'Button LogInButton LogInButton--minecraft',
                onclick: () => app.modal.show(MinecraftLogInModal)
            }, app.translator.trans('nearata-auth-minecraft.forum.log_in_button_title'))
        )
    });
});
