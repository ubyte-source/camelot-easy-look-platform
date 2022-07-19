<?PHP

namespace configurations\editor;

use Knight\Lock;

final class TinyMCE
{
	use Lock;

	const KEY = ENVIRONMENT_TINYMCE_APIKEY;
}
