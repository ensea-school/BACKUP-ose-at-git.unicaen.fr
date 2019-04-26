<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$formuleTestIntervenantId = 109;

$data = "P1	CM	100,00 %	0,00 %	0,00 %		Oui	2 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	2 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TD	100,00 %	0,00 %	0,00 %		Oui	1,5 h	1,5 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	3 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	3 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	3 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	3 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	4 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	2 h	3 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
P1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	4 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	4 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	4 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	4 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	5 h	7,5 HETD	0 HETD	0 HETD	0 HETD	3,45 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TD	100,00 %	0,00 %	0,00 %		Oui	2 h	2 HETD	0 HETD	0 HETD	0 HETD	0,92 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	3,5 h	5,25 HETD	0 HETD	0 HETD	0 HETD	2,41 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C1	CM	100,00 %	0,00 %	0,00 %		Oui	2 h	3 HETD	0 HETD	0 HETD	0 HETD	1,38 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	TP	100,00 %	0,00 %	0,00 %		Oui	4 h	2,67 HETD	0 HETD	0 HETD	0 HETD	1,84 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C2	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C3	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
C1	CM	100,00 %	0,00 %	0,00 %		Oui	1,5 h	2,25 HETD	0 HETD	0 HETD	0 HETD	1,03 HETD	0 HETD	0 HETD	0 HETD	0 HETD
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
        font-size:8pt;
    }
</style>
<table class="table table-bordered table-condensed table-extra-condensed table-hover">
    <tr>
        <th>Composante</th>
        <th>Service statutaire</th>
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
                'P1' => 1,
                'C1' => 2,
                'C2' => 3,
                'C3' => 4,
            ];

            $composante             = $correspStructs[$c[0]];
            $serviceStatutaire      = true;
            $typeIntervention       = $c[1];
            $referentiel            = false;
            $tauxFi                 = 1;
            $tauxFa                 = 0;
            $tauxFc                 = 0;
            $modulateurHC           = 1;
            $heures                 = $c[7];
            $serviceFi              = stringToFloat(substr($c[8],0,-5));
            $serviceFa              = stringToFloat(substr($c[9],0,-5));
            $serviceFc              = stringToFloat(substr($c[10],0,-5));
            $serviceReferentiel     = stringToFloat(substr($c[11],0,-5));
            $heuresComplFi          = stringToFloat(substr($c[12],0,-5));
            $heuresComplFa          = stringToFloat(substr($c[13],0,-5));
            $heuresComplFc          = stringToFloat(substr($c[14],0,-5));
            $heuresComplFcMaj       = stringToFloat(substr($c[15],0,-5));
            $heuresComplReferentiel = stringToFloat(substr($c[16],0,-5));

            // Transformations
            $heures = substr($heures, 0, -2);
            $heures = stringToFloat($heures);

            $debug = false;
            //$debug = true;

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
                <td><?= $serviceStatutaire ?></td>
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
