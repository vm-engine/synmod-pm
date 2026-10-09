<?php

declare(strict_types=1);

use VmEngine\Pm\Services\Sph\Docx\DocxXml;

test('escaping drops characters XML 1.0 forbids and escapes markup', function () {
    expect(DocxXml::e("A\x0B<b>\x0C&\tB\n"))->toBe("A&lt;b&gt;&amp;\tB\n");
});
