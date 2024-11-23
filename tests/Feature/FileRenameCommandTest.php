<?php

it('file-rename command exists', function () {
    $this->artisan('migration:file-rename .')->assertExitCode(0);
});
