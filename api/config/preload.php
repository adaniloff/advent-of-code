<?php

declare(strict_types=1);

if (file_exists(dirname(__DIR__).'/var/cache/prod/App_KernelProdContainer.preload.php')) {
    require dirname(__DIR__).'/var/cache/prod/App_KernelProdContainer.preload.php';
} elseif (file_exists(dirname(__DIR__).'/var/cache/staging/App_KernelStagingContainer.preload.php')) {
    require dirname(__DIR__).'/var/cache/staging/App_KernelStagingContainer.preload.php';
}
