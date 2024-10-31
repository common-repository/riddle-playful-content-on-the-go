<?php

namespace src\Api;

interface RiddleLoaderInterface
{
    public function getRiddle($riddleId): ?array;
    public function getEmbedCode($riddleId, array $params): ?string;
    public static function isAuthorized(): bool;
}