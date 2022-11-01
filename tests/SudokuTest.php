<?php

namespace Compolomus\Sudoku;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SudokuTest extends TestCase
{

    public function testGenerate()
    {
        $sudoku = new Sudoku();
        $count = count($sudoku->generate());
        $this->assertCount(81, $sudoku->generate());
    }

    public function test__construct()
    {
        $object = new Sudoku();

        $this->assertIsObject($object);
        $this->assertInstanceOf(Sudoku::class, $object);

        $this->expectException(InvalidArgumentException::class);
        new Sudoku(5);
    }
}
