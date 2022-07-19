<?PHP

namespace applications\document;

use Menu\Base as Menu;

return Menu::create(function (Menu $item) {
    $item->getField('icon')->setValue('integration_instructions');
    $item->setViewsFavorite('read');
});
