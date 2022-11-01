<?php

declare(strict_types=1);

namespace Compolomus\Sudoku;

use InvalidArgumentException;

class Sudoku
{
    private array $shift = [
        4 => 1,
        9 => 2,
        16 => 3,
        25 => 4,
        36 => 5
    ];

    private int $lines;

    private int $chunkSide;

    private array $allEqual;

    private array $linesArray = [];

    private array $columnsArray = [];

    private array $squaresArray = [];

    /**
     * @param int $line
     * @throws InvalidArgumentException
     */
    public function __construct(int $line = 9)
    {
        if (!array_key_exists($line, $this->shift)) {
            throw new InvalidArgumentException(
                'Value options for lines is [' . implode(', ', array_keys($this->shift)) . ']'
            );
        }

        $this->lines = 9; // $this->lines = $line; // > 9 is hard
        $this->init();
    }

    /**
     * Set start values and linked arrays
     * @return void
     */
    private function init(): void
    {
        $all = $this->lines ** 2;
        $this->chunkSide = (int) sqrt($this->lines);
        $this->allEqual = array_fill(1, $all, 0);

        foreach ($this->allEqual as $key => &$value) {
            $index = $this->getIndex($key);
            $this->linesArray[$index['lineNum']][$key] = &$value;
            $this->columnsArray[$index['colNum']][$key] = &$value;
            $this->squaresArray[$index['chunkNum']][$key] = &$value;
        }
    }

    private function getIndex(int $index): array
    {
        $lineNum = $index <= $this->lines ? 1 : (int) ($index / $this->lines) + ($index % $this->lines === 0 ? 0 : 1);
        $colNum = $index <= $this->lines ? $index : ($index % $this->lines) + ($index % $this->lines === 0 ? $this->lines : 0);
        $bigLine = $this->chunkSide * $this->lines;
        $chunkNum = (ceil($colNum / $this->chunkSide)) + (($this->shift[$this->lines] * abs(ceil(($index - $bigLine) / $bigLine))) + (int) (--$index / $bigLine));

        return [
            'lineNum' => $lineNum,
            'colNum' => $colNum,
            'chunkNum' => $chunkNum
        ];
    }

    private function compare(int $square, int $value): array
    {
        $return = [];

        foreach (array_keys($this->squaresArray[$square]) as $pos) {
            $stats = $this->getIndex($pos);
            if ($this->allEqual[$pos] === 0
                && !in_array($value, $this->linesArray[$stats['lineNum']], true)
                && !in_array($value, $this->columnsArray[$stats['colNum']], true)
            ) {
                $return[] = $pos;
            }
        }

        return $return;
    }

    private function preGenerate(): int
    {
        $notSet = 0;

        for ($setNumber = 1; $setNumber <= $this->lines; $setNumber++) {
            for ($currentSquare = 1; $currentSquare <= $this->lines; $currentSquare++) {
                $availableKeys = $this->compare($currentSquare, $setNumber);
                if (count($availableKeys)) {
                    $randomValueKey = $availableKeys[array_rand($availableKeys)];
                    $this->allEqual[$randomValueKey] = $setNumber;
                } else {
                    ++$notSet;
                }
            }
        }

        return $notSet;
    }

    /**
     * Recursive generator
     * @return array
     */
    public function generate(): array
    {
        $process = true;
        while ($process) {
            $result = $this->preGenerate();
            if ($result > 0) {
                $this->init();
                $this->generate();
                break;
            }
            $process = false;
        }

        return $this->allEqual;
    }
}
