<?php

namespace App\Extensions\NeoEloquent;

use Vinelab\NeoEloquent\Query\Grammars\CypherGrammar as NeoCypherGrammar;

class CypherGrammar extends NeoCypherGrammar {

    public function craftRelation($parentNode, $relationLabel, $relatedNode, $relatedLabels, $direction, $bare = false)
    {
        $relation = '(%s)-[%s]-%s';

        return ($bare) ? sprintf($relation, $parentNode, $relationLabel, $relatedNode)
            : sprintf($relation, $parentNode, $relationLabel, '('. $relatedNode.$relatedLabels .')');
    }
}
