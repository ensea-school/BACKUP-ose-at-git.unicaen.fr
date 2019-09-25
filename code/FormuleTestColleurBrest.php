<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Interop\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$formuleTestIntervenantId = 107;

$data = "P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	4,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	9 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	11,25 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	13,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	15,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	20,25 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	24,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	26,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	31,25 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	35,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	39,75 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	43,75 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	46 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	50,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	52,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	55 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	2 h	1 HETD	2 HETD	57 HETD	0 h	0,67 HETD	0 HETD		1 HETD	2 HETD	0 HETD		2 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	61 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	65,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	67,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	70 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	71,5 HETD	0 h	1 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	73 HETD	0 h	1 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	77 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	81 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	84,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	88 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	92,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0,83 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0,83 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	96,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	98,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	100,25 HETD	0 h	1 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	101,75 HETD	0 h	1 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	105,75 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	107,25 HETD	0 h	1 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	108,75 HETD	0 h	1 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	111 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	115 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4 HETD	0 HETD		4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1 HETD	1,5 HETD	116,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	120 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	123,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	128 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0,83 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0,83 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	132,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0,83 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0,83 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	136,75 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	141 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	144,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	148 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1 h	1,5 HETD	1,5 HETD	149,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	1,5 HETD	0,28 HETD		1,5 HETD	0 HETD	0 HETD	0 HETD	0,28 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	3 h	1,5 HETD	4,5 HETD	154 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	4,5 HETD	0 HETD		4,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	158,25 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	162,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	4,25 HETD	0,79 HETD		4,25 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	166 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	169,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	171,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	174 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	176,25 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	178,5 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0,42 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0,42 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	182 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	185,5 HETD	0 h	0,67 HETD	0 HETD		1 HETD	3,5 HETD	0,65 HETD		3,5 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	187,75 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	190 HETD	0 h	1,5 HETD	0 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	192 HETD	0,17 h	1,5 HETD	0,25 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	192 HETD	1,5 h	1,5 HETD	2,25 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	192 HETD	1,5 h	1,5 HETD	2,25 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	2,25 HETD	192 HETD	1,5 h	1,5 HETD	2,25 HETD		1,5 HETD	2,25 HETD	0 HETD		2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	192 HETD	4 h	0,67 HETD	2,67 HETD		1 HETD	4 HETD	0,74 HETD		2,67 HETD	0 HETD	0 HETD	0 HETD	0,74 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	192 HETD	4,25 h	0,67 HETD	2,83 HETD		1 HETD	4,25 HETD	0,79 HETD		2,83 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	1 HETD	4 HETD	192 HETD	4 h	0,67 HETD	2,67 HETD		1 HETD	4 HETD	0,74 HETD		2,67 HETD	0 HETD	0 HETD	0 HETD	0,74 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,25 h	1 HETD	4,25 HETD	192 HETD	4,25 h	0,67 HETD	2,83 HETD		1 HETD	4,25 HETD	0,79 HETD		2,83 HETD	0 HETD	0 HETD	0 HETD	0,79 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	192 HETD	3,5 h	0,67 HETD	2,33 HETD		1 HETD	3,5 HETD	0,65 HETD		2,33 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	3,5 h	1 HETD	3,5 HETD	192 HETD	3,5 h	0,67 HETD	2,33 HETD		1 HETD	3,5 HETD	0,65 HETD		2,33 HETD	0 HETD	0 HETD	0 HETD	0,65 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4,5 h	1 HETD	4,5 HETD	192 HETD	4,5 h	0,67 HETD	3 HETD		1 HETD	4,5 HETD	0,83 HETD		3 HETD	0 HETD	0 HETD	0 HETD	0,83 HETD	0 HETD	0 HETD	0 HETD	0 HETD
";

/*
Structures :
1	Droit
2	Histoire
3	IAE
4	IUT
5	Lettres
6	Santé
7	Sciences
8	SUAPS
9	Université
*/
$data = explode("\n", $data);
?>
<style>
    table {
        font-size: 8pt;
    }
</style>
<table class="table table-bordered table-condensed table-extra-condensed table-hover">
    <tr>
        <th>Composante</th>
        <th>Service statutaire</th>
        <th>Référentiel</th>
        <th>Type d'intervention</th>
        <th>Taux FI</th>
        <th>Taux FA</th>
        <th>Taux FC</th>
        <th>Modulateur HC</th>
        <th>Heures</th>
        <th>SFi</th>
        <th>SFa</th>
        <th>SFc</th>
        <th>SRef</th>
        <th>HCFi</th>
        <th>HCFa</th>
        <th>HCFc</th>
        <th>HCFcM</th>
        <th>HCRef</th>
        <th>Tableau</th>
    </tr>
    <?php

    /** @var \Doctrine\ORM\EntityManager $bdd */
    $bdd = $sl->get(\Application\Constants::BDD);
    /** @var \Application\Service\FormuleTestIntervenantService $ftiService */
    $ftiService = $sl->get(\Application\Service\FormuleTestIntervenantService::class);
    $fti        = $ftiService->get($formuleTestIntervenantId);

    $bdd->getConnection()->exec('DELETE FROM formule_test_volume_horaire WHERE intervenant_test_id = ' . $formuleTestIntervenantId);
    foreach ($data as $l) {
        if (trim($l)) {
            $c = explode("\t", trim($l));

            $correspStructs = [
                'P1'   => 1,
                '901'  => 1,
                'FDS'  => 1,
                'C1'   => 2,
                '909'  => 2,
                'C2'   => 3,
                '920'  => 3,
                'C3'   => 4,
                'UNIV' => 9,
            ];

            $composante             = $correspStructs[$c[0]];
            $serviceStatutaire      = strtolower($c[6]) == 'oui';
            $typeIntervention       = $c[1];
            $referentiel            = false;
            $tauxFi                 = $c[2];
            $tauxFa                 = $c[3];
            $tauxFc                 = $c[4];
            $modulateurHC           = $c[5];
            $heures                 = $c[7];
            $serviceFi              = stringToFloat(substr($c[19], 0, -5));
            $serviceFa              = stringToFloat(substr($c[20], 0, -5));
            $serviceFc              = stringToFloat(substr($c[21], 0, -5));
            $serviceReferentiel     = stringToFloat(substr($c[22], 0, -5));
            $heuresComplFi          = stringToFloat(substr($c[23], 0, -5));
            $heuresComplFa          = stringToFloat(substr($c[24], 0, -5));
            $heuresComplFc          = stringToFloat(substr($c[25], 0, -5));
            $heuresComplFcMaj       = stringToFloat(substr($c[26], 0, -5));
            $heuresComplReferentiel = stringToFloat(substr($c[27], 0, -5));

            // Transformations
            if ($typeIntervention == 'Référentiel') $typeIntervention = 'REFERENTIEL';

            $referentiel = $typeIntervention == 'REFERENTIEL';
            $tauxFi = (float)str_replace(',','.',substr($tauxFi,0,-1)) / 100;
            $tauxFa = (float)str_replace(',','.',substr($tauxFa,0,-1)) / 100;
            $tauxFc = (float)str_replace(',','.',substr($tauxFc,0,-1)) / 100;

            if ('' == $modulateurHC) $modulateurHC = 1;

            $heures = substr($heures, 0, -2);
            $heures = stringToFloat($heures);



            $debug = false;
//            $debug = true;

            // Traitement et affichage
            $composante = $sl->get(\Application\Constants::BDD)->getRepository(\Application\Entity\Db\FormuleTestStructure::class)->find($composante);
            if ($debug) {
                $c = '<pre>' . var_export($c, true) . '</pre>';
            } else {
                $c  = '';
                $vh = new \Application\Entity\Db\FormuleTestVolumeHoraire();
                $vh->setIntervenantTest($fti);
                $vh->setStructureTest($composante);
                $vh->setServiceStatutaire($serviceStatutaire);
                $vh->setReferentiel($referentiel);
                $vh->setTypeInterventionCode($typeIntervention);
                $vh->setTauxFi($tauxFi);
                $vh->setTauxFa($tauxFa);
                $vh->setTauxFc($tauxFc);
                $vh->setPonderationServiceCompl($modulateurHC);
                $vh->setHeures($heures);
                $vh->setAServiceFi($serviceFi);
                $vh->setAServiceFa($serviceFa);
                $vh->setAServiceFc($serviceFc);
                $vh->setAServiceReferentiel($serviceReferentiel);
                $vh->setAHeuresComplFi($heuresComplFi);
                $vh->setAHeuresComplFa($heuresComplFa);
                $vh->setAHeuresComplFc($heuresComplFc);
                $vh->setAHeuresComplFcMajorees($heuresComplFcMaj);
                $vh->setAHeuresComplReferentiel($heuresComplReferentiel);
                $bdd->persist($vh);
                $bdd->flush($vh);
            }

            ?>
            <tr>
                <td><?= $composante ?></td>
                <td><?= $serviceStatutaire ? 'Oui' : 'Non' ?></td>
                <td><?= $referentiel ? 'Oui' : 'Non' ?></td>
                <td><?= $typeIntervention ?></td>
                <td><?= $tauxFi ?></td>
                <td><?= $tauxFa ?></td>
                <td><?= $tauxFc ?></td>
                <td><?= $modulateurHC ?></td>
                <td><?= $heures ?></td>
                <td><?= $serviceFi ?></td>
                <td><?= $serviceFa ?></td>
                <td><?= $serviceFc ?></td>
                <td><?= $serviceReferentiel ?></td>
                <td><?= $heuresComplFi ?></td>
                <td><?= $heuresComplFa ?></td>
                <td><?= $heuresComplFc ?></td>
                <td><?= $heuresComplFcMaj ?></td>
                <td><?= $heuresComplReferentiel ?></td>

                <td><?= $c ?></td>
            </tr>

            <?php
        }
    }
    ?>
</table>
