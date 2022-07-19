<?PHP

namespace applications\document\output;

use Menu\Base as Menu;

return Menu::create(function (Menu $item) {
    $item->getField('icon')->setValue('picture_as_pdf');
    $item->setViewsFavorite('read');
});
