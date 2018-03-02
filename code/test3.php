<?php

use Unicaen\OpenDocument\Document;

$r = new Document('/home/laurent/Contrat.odt');

$m = $r->getMeta();

var_dump($m);

var_dump($m->getUserDefined('testUD'));