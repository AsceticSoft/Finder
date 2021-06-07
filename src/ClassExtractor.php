<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

use AsceticSoft\Finder\Exception\FileReadException;
use AsceticSoft\Finder\Exception\ParseException;

class ClassExtractor implements ClassExtractorInterface
{
    private bool $skipAbstract;

    public function __construct(bool $skipAbstract = true)
    {
        $this->skipAbstract = $skipAbstract;
    }

    public function findClassName(string $filename): ?string
    {
        $fileContent = file_get_contents($filename);
        if (false === $fileContent) {
            throw new FileReadException($filename);
        }

        $tokens = \PhpToken::tokenize($fileContent);

        $namespace = '';

        $token = current($tokens);
        while (false !== $token) {
            if ($this->skipAbstract && $token->is(T_ABSTRACT)) {
                return null;
            }
            if ($token->is(T_NAMESPACE)) {
                $namespace = $this->extractNamespace($tokens);
            } elseif ($token->is(T_CLASS)) {
                return ($namespace ? "$namespace\\" : '') . $this->extractClassName($tokens);
            }
            $token = next($tokens);
        }

        return null;
    }

    /**
     * @param \PhpToken[] $tokens
     */
    private function nextToken(array &$tokens, int $tokenType): string
    {
        $token = next($tokens);
        if ($token->is($tokenType)) {
            return $token->text;
        }
        throw new ParseException('Parse error. Expected ' . token_name($tokenType));
    }

    /**
     * @param \PhpToken[] $tokens
     */
    private function extractNamespace(array &$tokens): string
    {
        $this->nextToken($tokens, T_WHITESPACE);

        return $this->nextToken($tokens, T_NAME_QUALIFIED);
    }

    /**
     * @param \PhpToken[] $tokens
     */
    private function extractClassName(array &$tokens): string
    {
        $this->nextToken($tokens, T_WHITESPACE);

        return $this->nextToken($tokens, T_STRING);
    }
}
