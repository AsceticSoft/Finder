<?php

declare(strict_types=1);

namespace Borodulin\Finder;

use Borodulin\Finder\Exception\ParseException;

class ClassExtractor
{
    private bool $skipAbstract;

    public function __construct(bool $skipAbstract = true)
    {
        $this->skipAbstract = $skipAbstract;
    }

    public function __invoke($filename): ?string
    {
        $tokens = \PhpToken::getAll(file_get_contents($filename));

        $namespace = '';

        $token = current($tokens);
        while (false !== $token) {
            if ($this->skipAbstract && $token->is(T_ABSTRACT)) {
                return null;
            }
            if ($token->is(T_NAMESPACE)) {
                $namespace = $this->extractNamespace($tokens);
            }
            if ($token->is(T_CLASS)) {
                $className = $this->extractClassName($tokens);

                return $namespace ? "$namespace\\$className" : $className;
            }
            $token = next($tokens);
        }

        return null;
    }

    private function nextToken(array &$tokens, int $tokenType): string
    {
        $token = next($tokens);
        if ($token->is($tokenType)) {
            return $token->text;
        }
        throw new ParseException('Parse error. Expected '.token_name($tokenType));
    }

    private function isNextToken(array &$tokens, int $tokenType): bool
    {
        $token = next($tokens);

        return $token->is($tokenType);
    }

    private function extractNamespace(array &$tokens): string
    {
        $this->nextToken($tokens, T_WHITESPACE);

        return $this->nextToken($tokens, T_NAME_QUALIFIED);
    }

    private function extractClassName(array &$tokens): string
    {
        $this->nextToken($tokens, T_WHITESPACE);

        return $this->nextToken($tokens, T_STRING);
    }
}
