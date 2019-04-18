<?php

class FramgiaTestsVariableValidVariableNameSniffTest
{
    public $snake_case_variable = 'snake';
    private $camelCaseVariable;
}

$a = new FramgiaTestsVariableValidVariableNameSniffTest();
$a->snake_case_variable;

public $varialbe;
private $_variable;
$snake_case_variable = 'snake';
$camelCaseVariable;
$a = "this is a $snake_case_variable test";
