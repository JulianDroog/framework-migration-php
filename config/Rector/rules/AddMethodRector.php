<?php

declare(strict_types=1);

namespace App\Rector;

use PhpParser\Node;
use Rector\Rector\AbstractRector;

final class AddMethodRector extends AbstractRector
{
    public function getRuleDefinition(): \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new RuleDefinition('Add a custom method to classes', [
            new CodeSample(
                '<?php class SomeClass {}',
                '<?php class SomeClass { public function customMethod() {} }'
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        // Ensure the node is a class
        if (!$node instanceof Class_) {
            return null;
        }

        // Check if the method already exists to avoid duplication
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $stmt->name->toString() === 'customMethod') {
                return null; // Method already exists, no need to add it
            }
        }

        // Create the new method
        $method = new ClassMethod('customMethod');
        $method->flags = Class_::MODIFIER_PUBLIC; // public method
        $method->stmts = []; // Empty method body for now

        // Add the new method to the class
        $node->stmts[] = $method;

        return $node;
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }
}
